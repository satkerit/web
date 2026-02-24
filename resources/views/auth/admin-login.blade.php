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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .floating-shapes {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }
        
        .shape-1 {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape-2 {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .shape-3 {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 15%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            transition: color 0.3s ease;
        }
        
        .form-input {
            padding-left: 48px;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            border-color: #6366f1;
        }
        
        .form-input:focus + .input-icon {
            color: #6366f1;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .logo-glow {
            filter: drop-shadow(0 0 20px rgba(99, 102, 241, 0.3));
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }
        
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Background with animated gradient -->
    <div class="gradient-bg min-h-screen flex items-center justify-center relative overflow-hidden">
        <!-- Floating shapes for visual appeal -->
        <div class="floating-shapes shape-1"></div>
        <div class="floating-shapes shape-2"></div>
        <div class="floating-shapes shape-3"></div>
        
        <!-- Main container -->
        <div class="container mx-auto px-4 z-10">
            <div class="max-w-6xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-8 items-center min-h-screen">
                    
                    <!-- Left side - Brand/Info Section -->
                    <div class="text-white lg:block hidden slide-in-left">
                        <div class="mb-8">
                            <div class="inline-flex items-center space-x-3 mb-6">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-shield-alt text-white text-xl"></i>
                                </div>
                                <span class="text-white/80 font-medium">Admin Portal</span>
                            </div>
                            
                            <h1 class="text-5xl font-bold mb-6 leading-tight">
                                Selamat Datang di<br>
                                <span class="text-yellow-300">Dashboard Admin</span>
                            </h1>
                            
                            <p class="text-xl text-white/80 mb-8 leading-relaxed">
                                Kelola sistem dengan aman dan efisien. Akses kontrol penuh atas semua fitur dan pengaturan.
                            </p>
                            
                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-green-400/20 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-chart-line text-green-300"></i>
                                        </div>
                                        <div>
                                            <div class="text-2xl font-bold text-white">Analytics</div>
                                            <div class="text-sm text-white/70">Real-time data</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-400/20 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-users text-blue-300"></i>
                                        </div>
                                        <div>
                                            <div class="text-2xl font-bold text-white">Users</div>
                                            <div class="text-sm text-white/70">Management</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-6 text-white/70">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-400 rounded-full pulse"></div>
                                    <span>Sistem Aman</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-blue-400 rounded-full pulse"></div>
                                    <span>24/7 Support</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right side - Login Form -->
                    <div class="fade-in">
                        <div class="glass-effect rounded-2xl shadow-2xl p-8 max-w-md mx-auto">
                            <!-- Logo Section -->
                            <div class="text-center mb-8">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl mb-4 logo-glow">
                                    <i class="fas fa-user-shield text-white text-2xl"></i>
                                </div>
                                <h2 class="text-3xl font-bold text-gray-900 mb-2">Admin Login</h2>
                                <p class="text-gray-600">Masuk ke panel administrasi</p>
                            </div>
                            
                            <!-- Error Messages -->
                            @if(session('error'))
                                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center space-x-3">
                                    <i class="fas fa-exclamation-circle text-red-500"></i>
                                    <span class="text-red-700 text-sm">{{ session('error') }}</span>
                                </div>
                            @endif
                            
                            @if(session('success'))
                                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center space-x-3">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span class="text-green-700 text-sm">{{ session('success') }}</span>
                                </div>
                            @endif
                            
                            <!-- Login Form -->
                            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                                @csrf
                                
                                <!-- Email Input -->
                                <div class="input-group">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email') }}" 
                                        required 
                                        autofocus
                                        class="form-input w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                                        placeholder="admin@example.com"
                                    >
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Password Input -->
                                <div class="input-group" x-data="{ show: false }">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input 
                                        :type="show ? 'text' : 'password'" 
                                        id="password" 
                                        name="password" 
                                        required
                                        class="form-input w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror"
                                        placeholder="••••••••"
                                    >
                                    <button 
                                        type="button" 
                                        @click="show = !show"
                                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                                    >
                                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- CAPTCHA -->
                                <div>
                                    <label for="captcha_answer" class="block text-sm font-medium text-gray-700 mb-2">
                                        Keamanan: <span class="font-semibold text-indigo-600">{{ $captcha_question }}</span>
                                    </label>
                                    <div class="input-group">
                                        <i class="fas fa-calculator input-icon"></i>
                                        <input 
                                            type="number" 
                                            id="captcha_answer" 
                                            name="captcha_answer" 
                                            required
                                            class="form-input w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('captcha_answer') border-red-500 @enderror"
                                            placeholder="Hasil perhitungan..."
                                        >
                                    </div>
                                    @error('captcha_answer')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Remember Me & Forgot Password -->
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            name="remember"
                                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                        >
                                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                                    </label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
                                            Lupa password?
                                        </a>
                                    @endif
                                </div>
                                
                                <!-- Submit Button -->
                                <button 
                                    type="submit"
                                    class="btn-primary w-full text-white py-3 px-4 rounded-xl font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    <span class="flex items-center justify-center space-x-2">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>Masuk ke Dashboard</span>
                                    </span>
                                </button>
                            </form>
                            
                            <!-- Back to Home -->
                            <div class="mt-8 text-center">
                                <a href="{{ route('home') }}" class="inline-flex items-center space-x-2 text-gray-600 hover:text-indigo-600 font-medium transition-colors">
                                    <i class="fas fa-arrow-left"></i>
                                    <span>Kembali ke Beranda</span>
                                </a>
                            </div>
                            
                            <!-- Footer Info -->
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <div class="text-center text-xs text-gray-500">
                                    <p class="mb-2">Butuh bantuan? Hubungi IT Support</p>
                                    <div class="flex justify-center space-x-4">
                                        <span><i class="fas fa-shield-alt"></i> Secure Connection</span>
                                        <span><i class="fas fa-clock"></i> 24/7 Monitoring</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alpine.js for interactions -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>

</html>