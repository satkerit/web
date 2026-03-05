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
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
            font-family: 'Rajdhani', sans-serif;
            background: #0a0a0a;
            color: #ffffff;
            overflow: hidden;
        }
        
        .cyber-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(0, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 0, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(0, 255, 0, 0.1) 0%, transparent 50%);
            z-index: -2;
        }
        
        .grid-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 255, 255, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: -1;
            animation: grid-move 20s linear infinite;
        }
        
        @keyframes grid-move {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }
        
        .scan-line {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, transparent, #00ffff, transparent);
            animation: scan 3s linear infinite;
            z-index: 1000;
        }
        
        @keyframes scan {
            0% { transform: translateY(-10px); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateY(100vh); opacity: 0; }
        }
        
        .login-container {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .login-card {
            background: rgba(0, 0, 0, 0.9);
            border: 2px solid #00ffff;
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            position: relative;
            box-shadow: 
                0 0 50px rgba(0, 255, 255, 0.3),
                inset 0 0 50px rgba(0, 255, 255, 0.05);
            animation: card-glow 4s ease-in-out infinite alternate;
        }
        
        @keyframes card-glow {
            0% { 
                box-shadow: 
                    0 0 50px rgba(0, 255, 255, 0.3),
                    inset 0 0 50px rgba(0, 255, 255, 0.05);
            }
            100% { 
                box-shadow: 
                    0 0 80px rgba(0, 255, 255, 0.5),
                    inset 0 0 80px rgba(0, 255, 255, 0.1);
            }
        }
        
        .card-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(45deg, #00ffff, #ff00ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            animation: logo-pulse 2s ease-in-out infinite;
        }
        
        @keyframes logo-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .title {
            font-family: 'Orbitron', monospace;
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(45deg, #00ffff, #ff00ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 0.5rem;
        }
        
        .subtitle {
            color: #888;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .form-label {
            display: block;
            color: #00ffff;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        
        .form-input {
            width: 100%;
            background: rgba(0, 0, 0, 0.8);
            border: 2px solid #333;
            border-radius: 10px;
            padding: 1rem 1rem 1rem 3rem;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Rajdhani', sans-serif;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #00ffff;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);
            background: rgba(0, 0, 0, 0.9);
        }
        
        .form-input::placeholder {
            color: #555;
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #00ffff;
            font-size: 1.2rem;
        }
        
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #00ffff;
        }
        
        .captcha-container {
            background: rgba(0, 255, 255, 0.1);
            border: 1px solid #00ffff;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .captcha-label {
            color: #00ffff;
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .captcha-question {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
            font-family: 'Orbitron', monospace;
        }
        
        .checkbox-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
        }
        
        .checkbox {
            width: 18px;
            height: 18px;
            margin-right: 0.5rem;
            accent-color: #00ffff;
        }
        
        .checkbox-label {
            color: #888;
            font-size: 0.9rem;
        }
        
        .link {
            color: #00ffff;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .link:hover {
            color: #ff00ff;
        }
        
        .submit-btn {
            width: 100%;
            background: linear-gradient(45deg, #00ffff, #0080ff);
            border: none;
            border-radius: 10px;
            padding: 1rem;
            color: #000;
            font-size: 1.1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-family: 'Orbitron', monospace;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 255, 255, 0.4);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .submit-btn:hover::before {
            left: 100%;
        }
        
        .error-message {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid #ff0000;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            color: #ff6666;
            font-size: 0.9rem;
            text-align: center;
        }
        
        .success-message {
            background: rgba(0, 255, 0, 0.1);
            border: 1px solid #00ff00;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            color: #66ff66;
            font-size: 0.9rem;
            text-align: center;
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .status-indicators {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: status-blink 2s infinite;
        }
        
        .status-dot.green {
            background: #00ff00;
            box-shadow: 0 0 10px #00ff00;
        }
        
        .status-dot.blue {
            background: #00ffff;
            box-shadow: 0 0 10px #00ffff;
        }
        
        @keyframes status-blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        
        @media (max-width: 768px) {
            .login-card {
                margin: 1rem;
                padding: 2rem;
            }
            
            .title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="cyber-bg"></div>
    <div class="grid-overlay"></div>
    <div class="scan-line"></div>
    
    <div class="login-container">
        <div class="login-card">
            <div class="status-indicators">
                <div class="status-dot green"></div>
                <div class="status-dot blue"></div>
            </div>
            
            <div class="card-header">
                <div class="logo">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h1 class="title">ADMIN PORTAL</h1>
                <p class="subtitle">SECURE ACCESS</p>
            </div>
            
            @if(session('error'))
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.login') }}" x-data="{ showPassword: false }">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div style="position: relative;">
                        <i class="fas fa-envelope input-icon"></i>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            class="form-input"
                            placeholder="admin@system.com"
                        >
                    </div>
                    @error('email')
                        <div style="color: #ff6666; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Access Code</label>
                    <div style="position: relative;">
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
                        <div style="color: #ff6666; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="captcha-container">
                    <div class="captcha-label">Security Protocol</div>
                    <div class="captcha-question">{{ $captcha_question }}</div>
                    <div style="position: relative;">
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
                        <div style="color: #ff6666; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="checkbox-container">
                    <label class="checkbox-group">
                        <input type="checkbox" name="remember" class="checkbox">
                        <span class="checkbox-label">Remember Session</span>
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="link">Forgot Code?</a>
                    @endif
                </div>
                
                <button type="submit" class="submit-btn">
                    <span>INITIATE LOGIN</span>
                </button>
            </form>
            
            <div class="back-link">
                <a href="{{ route('home') }}" class="link">
                    <i class="fas fa-arrow-left"></i> Return to Mainframe
                </a>
            </div>
        </div>
    </div>
    
</body>

</html>