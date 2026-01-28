<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AuditTrail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Simple Math CAPTCHA
        $num1 = random_int(1, 10);
        $num2 = random_int(1, 10);
        $operator = random_int(0, 1) ? '+' : '-';

        if ($operator === '-') {
            // Ensure positive result for simplicity
            if ($num1 < $num2) {
                [$num1, $num2] = [$num2, $num1];
            }
        }

        $answer = $operator === '+' ? $num1 + $num2 : $num1 - $num2;

        session(['login_captcha_answer' => $answer]);

        return view('auth.admin-login', [
            'captcha_question' => "$num1 $operator $num2 = ?",
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Check if user is active
        if (!$request->user()->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        $request->session()->regenerate();

        // Log login activity
        AuditTrail::log('login', 'User login: ' . $request->user()->name);

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Log logout activity before logout
        if ($user) {
            AuditTrail::log('logout', 'User logout: ' . $user->name);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
