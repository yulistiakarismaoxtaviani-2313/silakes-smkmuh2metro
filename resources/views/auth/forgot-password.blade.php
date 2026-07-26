<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SMK Muhammadiyah 2 Metro</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body{
            font-family:'Inter',sans-serif;
        }

        .login-card{
            box-shadow:0 10px 30px -10px rgba(0,0,0,.07);
        }

        input:focus{
            outline:none!important;
            border-color:#1e3a8a!important;
            box-shadow:0 0 0 3px rgba(30,58,138,.05);
        }

        @media (max-width:480px){

            .login-card{
                border-radius:0!important;
                border:none!important;
                box-shadow:none!important;
                padding:2rem 1.5rem!important;
            }

            body{
                background:white!important;
                padding:0!important;
            }

            input{
                padding-top:1rem!important;
                padding-bottom:1rem!important;
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

        <p class="text-xs text-gray-500 mt-4 leading-5">
            Masukkan Username, Email, NIS, atau NBM untuk memverifikasi akun Anda sebelum menghubungi Admin.
        </p>

    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">

        <div class="flex items-start gap-3">

            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mt-0.5"></i>

            <div>

                <h3 class="text-sm font-bold text-green-700">
                    Akun Berhasil Diverifikasi
                </h3>

                <p class="text-xs text-green-700 mt-1 leading-5">
                    Identitas akun Anda berhasil ditemukan.
                    Silakan menghubungi <strong>Admin</strong> untuk melakukan reset password.
                    Setelah password direset, Anda dapat login menggunakan password default yang diberikan oleh Admin.
                </p>

            </div>

        </div>

    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">

        <div class="flex items-start gap-3">

            <i data-lucide="x-circle" class="w-5 h-5 text-red-600 mt-0.5"></i>

            <div>

                <h3 class="text-sm font-bold text-red-700">
                    Akun Tidak Ditemukan
                </h3>

                <p class="text-xs text-red-700 mt-1 leading-5">
                    Username, Email, NIS, atau NBM yang Anda masukkan tidak terdaftar.
                    Silakan periksa kembali identitas yang dimasukkan.
                </p>

            </div>

        </div>

    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">

        <div class="flex items-start gap-3">

            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mt-0.5"></i>

            <div>

                <h3 class="text-sm font-bold text-red-700">
                    Terjadi Kesalahan
                </h3>

                <p class="text-xs text-red-700 mt-1 leading-5">
                    {{ $errors->first() }}
                </p>

            </div>

        </div>

    </div>
    @endif

    <form action="{{ route('forgot.password.submit') }}" method="POST">

        @csrf

        <div>

            <label class="block text-xs font-bold text-gray-500 mb-2 ml-1">
                Username / Email / NIS / NBM
            </label>

            <div class="relative">

                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </span>

                <input
                    type="text"
                    name="identity"
                    value="{{ old('identity') }}"
                    class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl transition-all text-sm font-medium placeholder:text-gray-300"
                    placeholder="Masukkan Identitas Anda"
                    required>

            </div>

        </div>

        <div class="mt-8">

            <button
                type="submit"
                class="w-full py-4 bg-[#1e3a8a] hover:bg-blue-800 text-white font-extrabold rounded-xl transition-all shadow-md flex items-center justify-center gap-2 text-xs tracking-widest uppercase">

                Verifikasi Akun

            </button>

        </div>

    </form>

    <div class="mt-8 text-center">

        <a href="{{ route('login') }}"
           class="text-sm font-semibold text-[#1e3a8a] hover:underline">

            ← Kembali ke Login

        </a>

    </div>

</div>

<script>
    lucide.createIcons();
</script>

</body>
</html>