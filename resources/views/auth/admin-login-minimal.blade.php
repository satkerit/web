<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/alpine-bundle.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Space Grotesk', sans-serif;
            background: #fafafa;
            color: #1a1a1a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 1200px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        
        .brand-section {
            padding: 3rem;
        }
        
        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 4rem;
            text-decoration: none;
            color: inherit;
        }
        
        .logo-icon {
            width: 56px;
            height: 56px;
            background: #000;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
        }
        
        .logo-text {
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: -0.02em;
        }
        
        .brand-title {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: -0.04em;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #000 0%, #666 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .brand-subtitle {
            font-size: 1.25rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 3rem;
            font-weight: 400;
        }
        
        .features {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .feature {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .feature-icon {
            width: 40px;
            height: 40px;
            background: #f0f0f0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .feature-icon i {
            font-size: 1.1rem;
            color: #000;
        }
        
        .feature-content h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .feature-content p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        
        .login-section {
            background: #fff;
            border-radius: 32px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .login-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }
        
        .login-subtitle {
            color: #666;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #f0f0f0;
            border-radius: 16px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.2s ease;
            background: #fafafa;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #000;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.05);
        }
        
        .form-input::placeholder {
            color: #999;
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 1.1rem;
            transition: color 0.2s ease;
        }
        
        .form-input:focus ~ .input-icon {
            color: #000;
        }
        
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        
        .password-toggle:hover {
            color: #000;
            background: #f0f0f0;
        }
        
        .captcha-container {
            background: #f8f8f8;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid #f0f0f0;
        }
        
        .captcha-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: #666;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .captcha-question {
            font-size: 1.25rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1rem;
            color: #000;
        }
        
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .checkbox {
            width: 18px;
            height: 18px;
            accent-color: #000;
        }
        
        .checkbox-label {
            font-size: 0.9rem;
            color: #666;
        }
        
        .link {
            color: #000;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: opacity 0.2s ease;
        }
        
        .link:hover {
            opacity: 0.7;
        }
        
        .submit-btn {
            width: 100%;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 16px;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s ease;
            letter-spacing: -0.01em;
        }
        
        .submit-btn:hover {
            background: #333;
            transform: translateY(-1px);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .error-message {
            background: #fee;
            border: 1px solid #fcc;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            color: #c33;
            font-size: 0.9rem;
        }
        
        .success-message {
            background: #efe;
            border: 1px solid #cfc;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            color: #363;
            font-size: 0.9rem;
        }
        
        .back-link {
            text-align: center;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .login-wrapper {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .brand-section {
                padding: 1.5rem;
                text-align: center;
            }
            
            .brand-title {
                font-size: 2.5rem;
            }
            
            .login-section {
                padding: 2rem;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }
            
            .brand-title {
                font-size: 2rem;
            }
            
            .login-section {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <!-- Brand Section -->
        <div class="brand-section">
            <a href="{{ route('home') }}" class="brand-logo">
                <div class="logo-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="logo-text">BPRS Bangka Belitung</div>
            </a>
            
            <h1 class="brand-title">Admin Portal</h1>
            <p class="brand-subtitle">
                Kelola sistem dengan aman dan efisien. Akses kontrol penuh atas semua fitur dan pengaturan.
            </p>
            
            <div class="features">
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Analytics Dashboard</h3>
                        <p>Monitor performa sistem dengan data real-time</p>
                    </div>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="feature-content">
                        <h3>User Management</h3>
                        <p>Kelola pengguna dan hak akses dengan mudah</p>
                    </div>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="feature-content">
                        <h3>System Settings</h3>
                        <p>Konfigurasi sistem sesuai kebutuhan</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Login Section -->
        <div class="login-section">
            <div class="login-header">
                <h2 class="login-title">Welcome Back</h2>
                <p class="login-subtitle">Masuk ke panel administrasi</p>
            </div>
            
            @if(session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.login') }}" x-data="{ showPassword: false }">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            class="form-input"
                            placeholder="admin@example.com"
                        >
                    </div>
                    @error('email')
                        <div style="color: #c33; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            :type="showPassword ? 'text' : 'password'" 
                            id="password" 
                            name="password" 
                            required
                            class="form-input"
                            placeholder="••••••••"
                        >
                        <span class="password-toggle" @click="showPassword = !showPassword">
                            <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </span>
                    </div>
                    @error('password')
                        <div style="color: #c33; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="captcha-container">
                    <div class="captcha-label">Security Check</div>
                    <div class="captcha-question">{{ $captcha_question }}</div>
                    <div class="input-wrapper">
                        <i class="fas fa-calculator input-icon"></i>
                        <input 
                            type="number" 
                            id="captcha_answer" 
                            name="captcha_answer" 
                            required
                            class="form-input"
                            placeholder="Enter result"
                        >
                    </div>
                    @error('captcha_answer')
                        <div style="color: #c33; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-options">
                    <label class="checkbox-group">
                        <input type="checkbox" name="remember" class="checkbox">
                        <span class="checkbox-label">Ingat saya</span>
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="link">Lupa password?</a>
                    @endif
                </div>
                
                <button type="submit" class="submit-btn">
                    Masuk ke Dashboard
                </button>
            </form>
            
            <div class="back-link">
                <a href="{{ route('home') }}" class="link">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
    
</body>

</html>