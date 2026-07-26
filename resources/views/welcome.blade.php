@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col font-poppins text-[#000000] bg-white overflow-x-hidden">

    <nav class="sticky top-0 z-[100] bg-[#004AAD] text-white px-6 md:px-16 py-3 flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-4">
            <img src="{{ asset('img/logo-smk-old.png') }}" class="h-10 md:h-14">
        </div>
        <div class="flex gap-4 md:gap-8 text-[10px] md:text-[11px] font-bold uppercase tracking-widest">
        </div>
    </nav>

   <!-- HERO SECTION -->
<section class="relative bg-white overflow-hidden">

    <!-- Garis Biru -->
    <div class="absolute bottom-0 left-0 w-full h-[6px] bg-[#004AAD] z-30"></div>

    <!-- Background Gedung -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('img/sekolah1.png') }}"
             class="w-full h-full object-cover opacity-10
                    object-[center_0%] md:object-[center_5%]">
    </div>

    <!-- Content -->
    <div class="container mx-auto px-6 md:px-16 relative z-10 flex flex-col md:flex-row items-end justify-between">

        <!-- Text -->
        <div class="w-full md:w-1/2 flex flex-col items-center md:items-start text-center md:text-left pt-6 pb-0 md:py-20">

            <div class="flex flex-wrap justify-center md:justify-start gap-3 bg-white px-4 py-2 rounded-lg border-[2.5px] border-[#004AAD]/40 shadow-lg mb-3 md:mb-6">
                <img src="{{ asset('img/logo-muh.png') }}" class="h-7 md:h-9 w-auto">
                <img src="{{ asset('img/logo-smk2.png') }}" class="h-7 md:h-9 w-auto">
                <img src="{{ asset('img/logo-pk.png') }}" class="h-6 md:h-8 w-8 md:w-9">
                <img src="{{ asset('img/logo-akreditasi.png') }}" class="h-7 md:h-9 w-auto">
            </div>

            <h3 class="font-qwigley text-3xl md:text-6xl leading-tight">
                Selamat Datang
            </h3>

            <h2 class="text-2xl md:text-5xl font-extrabold text-[#004AAD] leading-none uppercase tracking-tight">
                DI SISTEM KESISWAAN
            </h2>

            <h2 class="text-lg md:text-3xl font-bold text-black uppercase tracking-tighter mt-1">
                SMK MUHAMMADIYAH 2 METRO
            </h2>

        </div>

        <!-- Gambar Siswa -->
        <div class="w-full md:w-1/2 flex justify-center md:justify-end self-end -mt-10 md:mt-0">

            <img src="{{ asset('img/siswa.png') }}"
                 class="w-auto h-[170px] md:h-[420px] block z-20">

        </div>

    </div>

</section>

    <!-- MAIN CONTENT -->
    <section class="container mx-auto px-6 md:px-16 py-10 md:py-16 grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
        
        <div class="lg:col-span-8 space-y-6">
            <!-- REGISTER STEP -->
            <div class="border-[1.5px] border-blue-200 rounded-2xl p-4 md:p-8 shadow-sm flex items-center justify-between bg-white overflow-x-auto gap-2">
                <div class="flex flex-col items-center min-w-[70px]">
                    <img src="{{ asset('img/icons/icon-1.png') }}" class="h-8 md:h-12 mb-2">
                    <p class="text-[9px] font-bold text-gray-500 uppercase text-center">Daftar Akun</p>
                </div>
                <img src="{{ asset('img/arrows/arrow-1.png') }}" class="h-3 w-auto opacity-60">
                <div class="flex flex-col items-center min-w-[70px]">
                    <img src="{{ asset('img/icons/icon-2.png') }}" class="h-8 md:h-12 mb-2">
                    <p class="text-[9px] font-bold text-gray-500 uppercase text-center">Lengkapi Data</p>
                </div>
                <img src="{{ asset('img/arrows/arrow-1.png') }}" class="h-3 w-auto opacity-60">
                <div class="flex flex-col items-center min-w-[70px]">
                    <img src="{{ asset('img/icons/icon-6.png') }}" class="h-8 md:h-12 mb-2">
                    <p class="text-[9px] font-bold text-gray-500 uppercase text-center">Verifikasi</p>
                </div>
                <img src="{{ asset('img/arrows/arrow-1.png') }}" class="h-3 w-auto opacity-60">
                <div class="flex flex-col items-center min-w-[70px]">
                    <img src="{{ asset('img/icons/icon-5.png') }}" class="h-8 md:h-12 mb-2">
                    <p class="text-[9px] font-bold text-[#004AAD] uppercase text-center">Selesai</p>
                </div>
            </div>

            <!-- LOGIN STEP -->
            <div class="border-[1.5px] border-blue-200 rounded-2xl p-4 md:p-8 shadow-sm flex items-center justify-between bg-white text-[#004AAD] overflow-x-auto gap-2">
                <div class="flex flex-col items-center min-w-[70px]">
                    <img src="{{ asset('img/icons/icon-3.png') }}" class="h-8 md:h-12 mb-2">
                    <p class="text-[9px] font-bold uppercase text-center text-gray-500">Isi Email</p>
                </div>
                <img src="{{ asset('img/arrows/arrow-1.png') }}" class="h-3 w-auto opacity-60">
                <div class="flex flex-col items-center min-w-[70px]">
                    <img src="{{ asset('img/icons/icon-6.png') }}" class="h-8 md:h-12 mb-2">
                    <p class="text-[9px] font-bold uppercase text-center text-gray-500">Password</p>
                </div>
                <img src="{{ asset('img/arrows/arrow-1.png') }}" class="h-3 w-auto opacity-60">
                <div class="flex flex-col items-center min-w-[70px]">
                    <img src="{{ asset('img/icons/icon-4.png') }}" class="h-8 md:h-12 mb-2">
                    <p class="text-[9px] font-bold uppercase text-center text-gray-500">Login</p>
                </div>
                <img src="{{ asset('img/arrows/arrow-1.png') }}" class="h-3 w-auto opacity-60">
                <div class="flex flex-col items-center min-w-[70px]">
                    <img src="{{ asset('img/icons/icon-5.png') }}" class="h-8 md:h-12 mb-2">
                    <p class="text-[9px] font-bold uppercase text-center">Selesai</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            <div>
                <p class="text-[#004AAD] font-semibold text-sm mb-2">Belum punya akun?</p>
                <a href="{{ route('register') }}" class="block w-full py-3 md:py-4 bg-[#F75855] text-white text-center font-bold text-lg md:text-2xl rounded-xl shadow-lg hover:bg-red-600 uppercase">Registrasi</a>
            </div>
            <div>
                <p class="text-[#004AAD] font-semibold text-sm mb-2">Sudah punya akun?</p>
                <a href="{{ route('login') }}" class="block w-full py-3 md:py-4 bg-[#004AAD] text-white text-center font-bold text-lg md:text-2xl rounded-xl shadow-lg hover:bg-blue-800 uppercase">Login</a>
            </div>
        </div>
    </section>
</div>
@endsection