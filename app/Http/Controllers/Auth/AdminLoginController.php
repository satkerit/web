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

        session(['admin_login_captcha_answer' => $answer]);

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
        if ($request->captcha_answer != session('admin_login_captcha_answer')) {
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

            return redirect()->intended('/admin/dashboard');
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

        return redirect('/');
    }
}