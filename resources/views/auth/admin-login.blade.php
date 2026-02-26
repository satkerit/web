<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Inter', system-ui, sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #f0fdfa 0%, #e0f7f4 50%, #d1faf4 100%);
        }

        .login-container {
            min-height: 100vh;
            display: flex;
        }

        /* Left Panel - Branding */
        .brand-panel {
            display: none;
            flex: 1;
            background: linear-gradient(145deg, #0d9488 0%, #0f766e 50%, #115e59 100%);
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(59, 218, 203, 0.15) 0%, transparent 60%);
            pointer-events: none;
        }

        .brand-panel::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -30%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .brand-content {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
            max-width: 480px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 3rem;
        }

        .brand-logo-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .brand-logo-icon svg {
            width: 24px;
            height: 24px;
            color: #5eead4;
        }

        .brand-logo-text {
            font-size: 1.25rem;
            font-weight: 600;
            letter-spacing: -0.02em;
        }

        .brand-title {
            font-size: 2.75rem;
            font-weight: 700;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
        }

        .brand-subtitle {
            font-size: 1.125rem;
            line-height: 1.7;
            opacity: 0.85;
            margin-bottom: 3rem;
        }

        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .brand-feature {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .brand-feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-feature-icon svg {
            width: 20px;
            height: 20px;
            color: #5eead4;
        }

        .brand-feature-text {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        /* Right Panel - Form */
        .form-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .form-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-logo-mobile {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .form-logo-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(13, 148, 136, 0.25);
        }

        .form-logo-icon svg {
            width: 26px;
            height: 26px;
            color: white;
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #134e4a;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .form-subtitle {
            color: #5f7974;
            font-size: 0.95rem;
        }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(13, 148, 136, 0.08);
        }

        /* Alert Messages */
        .alert {
            padding: 0.875rem 1rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .alert-success {
            background: #f0fdfa;
            border: 1px solid #99f6e4;
            color: #0d9488;
        }

        .alert svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #115e59;
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            transition: color 0.2s;
        }

        .input-icon svg {
            width: 18px;
            height: 18px;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #0d9488;
            background: white;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        .form-input:focus + .input-icon,
        .form-input:focus ~ .input-icon {
            color: #0d9488;
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .form-input.has-error {
            border-color: #f87171;
        }

        .form-input.has-error:focus {
            box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .password-toggle:hover {
            color: #64748b;
            background: #f1f5f9;
        }

        .password-toggle svg {
            width: 18px;
            height: 18px;
            display: block;
        }

        .field-error {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.375rem;
        }

        /* Captcha Box */
        .captcha-box {
            background: linear-gradient(135deg, #f0fdfa 0%, #e6f7f5 100%);
            border: 1.5px solid #99f6e4;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1.25rem;
        }

        .captcha-label {
            font-size: 0.8rem;
            color: #5f7974;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.375rem;
        }

        .captcha-question {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0d9488;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }

        .captcha-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid #d1faf4;
            border-radius: 8px;
            font-size: 1rem;
            text-align: center;
            background: white;
            color: #134e4a;
            font-weight: 500;
            transition: all 0.2s;
        }

        .captcha-input:focus {
            outline: none;
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        .captcha-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        /* Form Options */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .checkbox-wrapper input {
            width: 16px;
            height: 16px;
            accent-color: #0d9488;
            cursor: pointer;
        }

        .checkbox-label {
            font-size: 0.875rem;
            color: #64748b;
        }

        .forgot-link {
            font-size: 0.875rem;
            color: #0d9488;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #115e59;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 0.95rem 1.5rem;
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(13, 148, 136, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn svg {
            width: 18px;
            height: 18px;
        }

        /* Back Link */
        .back-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #0d9488;
        }

        .back-link svg {
            width: 16px;
            height: 16px;
            transition: transform 0.2s;
        }

        .back-link:hover svg {
            transform: translateX(-3px);
        }

        /* Footer */
        .form-footer {
            margin-top: 2rem;
            text-align: center;
        }

        .footer-badges {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
        }

        .footer-badge {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            color: #64748b;
            font-size: 0.8rem;
        }

        .footer-badge svg {
            width: 14px;
            height: 14px;
            color: #0d9488;
        }

        /* Responsive */
        @media (min-width: 1024px) {
            .brand-panel {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .form-logo-mobile {
                display: none;
            }

            .form-panel {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 480px) {
            .form-panel {
                padding: 1rem;
            }

            .form-card {
                padding: 1.5rem;
                border-radius: 16px;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .footer-badges {
                flex-direction: column;
                gap: 0.75rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Left Panel - Branding -->
        <div class="brand-panel">
            <div class="brand-content">
                <div class="brand-logo">
                    <div class="brand-logo-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="brand-logo-text">{{ config('app.name') }}</span>
                </div>

                <h1 class="brand-title">Selamat Datang di Admin Portal</h1>
                <p class="brand-subtitle">Kelola sistem dengan mudah, aman, dan efisien. Akses kontrol penuh untuk mengelola semua fitur dan pengaturan.</p>

                <div class="brand-features">
                    <div class="brand-feature">
                        <div class="brand-feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="brand-feature-text">Dashboard analitik real-time</span>
                    </div>
                    <div class="brand-feature">
                        <div class="brand-feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                        <span class="brand-feature-text">Manajemen pengguna terintegrasi</span>
                    </div>
                    <div class="brand-feature">
                        <div class="brand-feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <span class="brand-feature-text">Keamanan tingkat enterprise</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="form-panel">
            <div class="form-wrapper">
                <div class="form-header">
                    <!-- Mobile Logo -->
                    <div class="form-logo-mobile">
                        <div class="form-logo-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                    </div>
                    <h2 class="form-title">Admin Portal</h2>
                    <p class="form-subtitle">Masuk untuk melanjutkan ke dashboard</p>
                </div>

                <div class="form-card">
                    <!-- Alert Messages -->
                    @if(session('error'))
                        <div class="alert alert-error">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }">
                        @csrf

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-wrapper">
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    class="form-input @error('email') has-error @enderror"
                                    placeholder="admin@example.com"
                                >
                                <span class="input-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                    </svg>
                                </span>
                            </div>
                            @error('email')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-wrapper">
                                <input
                                    :type="showPassword ? 'text' : 'password'"
                                    id="password"
                                    name="password"
                                    required
                                    class="form-input @error('password') has-error @enderror"
                                    placeholder="Masukkan password"
                                >
                                <span class="input-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                                <button type="button" class="password-toggle" @click="showPassword = !showPassword">
                                    <svg x-show="!showPassword" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showPassword" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CAPTCHA -->
                        <div class="captcha-box">
                            <div class="captcha-label">Verifikasi Keamanan</div>
                            <div class="captcha-question">{{ $captcha_question }}</div>
                            <input
                                type="number"
                                id="captcha_answer"
                                name="captcha_answer"
                                required
                                class="captcha-input"
                                placeholder="Masukkan hasil"
                            >
                            @error('captcha_answer')
                                <p class="field-error" style="text-align: center;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="form-options">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="remember">
                                <span class="checkbox-label">Ingat saya</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="submit-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span>Masuk ke Dashboard</span>
                        </button>
                    </form>

                    <!-- Back to Home -->
                    <div class="back-section">
                        <a href="{{ route('home') }}" class="back-link">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Kembali ke Beranda</span>
                        </a>
                    </div>
                </div>

                <!-- Footer -->
                <div class="form-footer">
                    <div class="footer-badges">
                        <div class="footer-badge">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Koneksi Aman</span>
                        </div>
                        <div class="footer-badge">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span>24/7 Monitoring</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
