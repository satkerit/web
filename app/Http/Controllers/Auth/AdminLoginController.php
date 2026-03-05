<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        // Simple Math CAPTCHA
        $num1 = random_int(1, 10);
        $num2 = random_int(1, 10);
        $operator = random_int(0, 1) ? '+' : '-';

        if ($operator === '-') {
            // ensure positive result for simplicity
            if ($num1 < $num2) {
                [$num1, $num2] = [$num2, $num1];
            }
        }

        $answer = $operator === '+' ? $num1 + $num2 : $num1 - $num2;

        // Store captcha answer in session with multiple keys for compatibility
        session([
            'login_captcha_answer' => $answer,
            'admin_login_captcha_answer' => $answer, // Fallback for compatibility
        ]);
        
        \Log::info('CAPTCHA Generated', [
            'question' => "$num1 $operator $num2 = ?",
            'answer' => $answer,
            'session_id' => session()->getId(),
        ]);

        return view('auth.admin-login', [
            'captcha_question' => "$num1 $operator $num2 = ?",
        ]);
    }

    /**
     * Handle admin login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'captcha_answer' => 'required|numeric',
        ]);

        // Validate CAPTCHA
        $sessionAnswer = session('login_captcha_answer') ?? session('admin_login_captcha_answer');
        $userAnswer = $request->captcha_answer;
        
        \Log::info('CAPTCHA Validation', [
            'session_answer' => $sessionAnswer,
            'user_answer' => $userAnswer,
            'session_answer_type' => gettype($sessionAnswer),
            'user_answer_type' => gettype($userAnswer),
            'comparison' => (int)$userAnswer === (int)$sessionAnswer,
            'session_id' => session()->getId(),
            'all_session' => session()->all(),
        ]);
        
        if ($sessionAnswer === null) {
            throw ValidationException::withMessages([
                'captcha_answer' => ['Sesi CAPTCHA tidak ditemukan. Silakan refresh halaman dan coba lagi.'],
            ]);
        }
        
        if ((int)$userAnswer !== (int)$sessionAnswer) {
            throw ValidationException::withMessages([
                'captcha_answer' => ['Jawaban keamanan salah. Silakan coba lagi.'],
            ]);
        }

        // Check rate limiting
        $key = 'admin-login-' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'email' => ['Terlalu banyak percobaan login. Silakan coba lagi dalam ' . RateLimiter::availableIn($key) . ' detik.'],
            ]);
        }

        if (Auth::guard('web')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            // Check if user has admin role
            if (!Auth::user()->hasRole('super_admin') && !Auth::user()->hasRole('admin')) {
                Auth::guard('web')->logout();

                RateLimiter::hit($key, 60);

                throw ValidationException::withMessages([
                    'email' => ['Anda tidak memiliki akses ke halaman admin.'],
                ]);
            }

            RateLimiter::clear($key);
            
            // Clear CAPTCHA from session after successful login
            session()->forget('login_captcha_answer');

            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($key, 60);

        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    /**
     * Handle admin logout request.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil keluar.');
    }
}