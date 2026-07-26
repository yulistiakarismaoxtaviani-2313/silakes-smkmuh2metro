<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMK Muhammadiyah 2 Metro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .login-card {
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.07);
        }

        -webkit-font-smoothing: antialiased;

        input:focus {
            outline: none !important;
            border-color: #1e3a8a !important;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.05);
        }

        @media (max-width: 480px) {
        .login-card {
            border-radius: 0 !important; /* Full lebar di HP agar terlihat modern */
            border: none !important;
            box-shadow: none !important;
            padding: 2rem 1.5rem !important;
        }

        body {
            background-color: white !important; /* Background putih bersih di HP */
            padding: 0 !important;
        }

        /* Perbesar area klik input di HP agar tidak terlalu rapat */
        input {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }
    }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-[420px] w-full bg-white rounded-[0.5rem] login-card border border-gray-100 p-8 md:p-10 my-10">
        
        <div class="text-center mb-8">
            <img src="{{ asset('img/logo-smk1.png') }}" alt="Logo SMK" class="w-24 h-24 object-contain mx-auto mb-4">
            <h1 class="text-[#1e3a8a] font-black text-xl tracking-tight leading-none">
                SISTEM KESISWAAN
            </h1>
            <h2 class="text-[#1e3a8a] font-bold text-sm tracking-tight mt-1">
                SMK MUHAMMADIYAH 2 METRO
            </h2>
        </div>

        @if(session('loginError'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-xs font-bold text-center">
                {{ session('loginError') }}
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2 ml-1">Username / Email / NIS / NBM</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#1e3a8a]">
                        <i data-lucide="user" class="w-4 h-4 transition-all"></i>
                    </span>
                    <input type="text" name="login_identity" 
                        class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl transition-all text-sm font-medium placeholder:text-gray-300 focus:ring-0" 
                        placeholder="Masukkan Identitas Anda" required>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-2 px-1">
                    <label class="text-xs font-bold text-gray-500">Password</label>
                    <!-- Font link diperbaiki ke text-xs -->
                    <a href="{{ route('forgot.password') }}" class="text-xs text-[#1e3a8a] font-semibold hover:underline tracking-tight">
                        Lupa Password?
                    </a>
                </div>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#1e3a8a]">
                        <i data-lucide="lock" class="w-4 h-4 transition-all"></i>
                    </span>
                    <input type="password" id="passwordField" name="password" 
                        class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl transition-all text-sm font-medium placeholder:text-gray-300 focus:ring-0" 
                        placeholder="............" required>
                    
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center focus:outline-none bg-transparent border-none cursor-pointer">
                        <i id="eyeIcon" data-lucide="eye" class="w-5 h-5 text-gray-300 hover:text-gray-600 transition-all"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center pt-1 ml-1">
                <input type="checkbox" id="remember" name="remember" class="w-4 h-4 border-gray-300 rounded text-[#1e3a8a] focus:ring-[#1e3a8a] cursor-pointer">
                <label for="remember" class="ml-2 text-sm font-bold text-gray-500 cursor-pointer select-none">Ingat Saya</label>
            </div>

            <div class="pt-2">
                <button type="submit" 
                    class="w-full py-4 bg-[#1e3a8a] hover:bg-blue-800 text-white font-extrabold rounded-xl transition-all shadow-md flex items-center justify-center gap-2 text-xs tracking-widest active:scale-[0.98] border-none cursor-pointer uppercase">
                    Login
                </button>
            </div>
        </form>

        <div class="mt-10 pt-8 border-t border-gray-100 text-center">
            <!-- Font footer diperbaiki ke text-xs agar konsisten -->
            <p class="text-xs text-gray-400 mb-4 font-bold tracking-widest uppercase">Belum Memiliki Akun?</p>
            <a href="/register" 
                class="w-full py-3.5 bg-orange-400 hover:bg-orange-500 text-white font-bold rounded-xl transition-all shadow-sm flex items-center justify-center text-xs tracking-wider active:scale-[0.98] uppercase">
                Register
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function togglePassword() {
            const passwordField = document.getElementById('passwordField');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordField.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
</body>
</html>