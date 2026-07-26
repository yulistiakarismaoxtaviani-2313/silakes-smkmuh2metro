@extends('layouts.siswa')

@section('content')
<div class="flex-1 bg-[#F8FAFC] p-4 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    <div class="max-w-3xl mx-auto">
        
        {{-- Card Utama --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            {{-- Header Form --}}
            <div class="bg-[#004AAD] p-6 text-left">
                <h2 class="text-white font-bold text-sm uppercase tracking-[0.2em]">Keamanan & Password</h2>
                <p class="text-blue-100/70 text-[10px] capitalize font-medium mt-1 tracking-wider">Perbarui kata sandi secara berkala untuk keamanan akun</p>
            </div>

            {{-- Alert Notifikasi --}}
            @if(session('success'))
                <div class="mx-8 mt-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-xl flex items-center gap-3">
                    <i class="fas fa-check-circle text-sm"></i>
                    <p class="text-[11px] font-bold uppercase tracking-tight">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-8 mt-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-xl flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-sm"></i>
                    <p class="text-[11px] font-bold uppercase tracking-tight">{{ session('error') }}</p>
                </div>
            @endif

            <form action="{{ route('siswa.profil.updatePassword') }}" method="POST" class="p-8 md:p-10">
    @csrf
    @method('PUT')

    <div class="space-y-8">

    {{-- Username --}}
        <div>
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">
                Username
            </label>

            <input type="text"
                   name="username"
                   value="{{ old('username', auth()->user()->username) }}"
                   class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all"
                   placeholder="Masukkan username">

            @error('username')
                <p class="text-red-500 text-[10px] mt-2 font-bold uppercase tracking-tight italic">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Password Lama --}}
        <div class="relative">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">
                Kata Sandi Saat Ini
            </label>

            <input type="password"
                   name="current_password"
                   id="current_password"
                   class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 pr-12 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all"
                   placeholder="Masukkan password lama kamu">

            <button type="button"
                    onclick="togglePassword('current_password')"
                    class="absolute right-4 top-[38px] text-slate-300 hover:text-[#004AAD] transition-colors">
                <i class="far fa-eye" id="icon-current_password"></i>
            </button>

            @error('current_password')
                <p class="text-red-500 text-[10px] mt-2 font-bold uppercase tracking-tight italic">
                    {{ $message }}
                </p>
            @enderror

            <p class="text-[10px] text-slate-400 mt-2 italic font-medium">
                *Wajib diisi untuk verifikasi identitas
            </p>
        </div>

        <div class="border-t border-gray-50"></div>

        {{-- Password Baru --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="relative">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">
                    Kata Sandi Baru
                </label>

                <input type="password"
                       name="password"
                       id="password"
                       class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 pr-12 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all"
                       placeholder="Min. 8 Karakter">

                <button type="button"
                        onclick="togglePassword('password')"
                        class="absolute right-4 top-[38px] text-slate-300 hover:text-[#004AAD] transition-colors">
                    <i class="far fa-eye" id="icon-password"></i>
                </button>

                @error('password')
                    <p class="text-red-500 text-[10px] mt-2 font-bold uppercase tracking-tight italic">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="relative">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">
                    Konfirmasi Kata Sandi
                </label>

                <input type="password"
                       name="password_confirmation"
                       id="password_confirmation"
                       class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 pr-12 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all"
                       placeholder="Ulangi password baru">

                <button type="button"
                        onclick="togglePassword('password_confirmation')"
                        class="absolute right-4 top-[38px] text-slate-300 hover:text-[#004AAD] transition-colors">
                    <i class="far fa-eye" id="icon-password_confirmation"></i>
                </button>
            </div>

        </div>

        {{-- Tips Keamanan --}}
        <div class="bg-blue-50 p-5 rounded-2xl border-l-4 border-[#004AAD]">
            <div class="flex gap-4">
                <div class="text-[#004AAD] shrink-0">
                    <i class="fas fa-shield-alt text-sm"></i>
                </div>
                <p class="text-[10px] text-[#004AAD]/80 leading-relaxed font-bold capitalize tracking-tight">
                    Tips Keamanan: Gunakan kombinasi huruf dan angka yang unik.
                    Hindari menggunakan informasi pribadi seperti tanggal lahir
                    atau nama sebagai password.
                </p>
            </div>
        </div>

    </div>

    <div class="flex items-center gap-4 pt-6 mt-6 md:pt-10 md:mt-10 border-t border-gray-50">
        <a href="{{ route('siswa.profil.index') }}"
           class="flex-1 px-4 py-4 bg-slate-100 text-slate-500 rounded-xl font-bold text-[10px] md:text-xs capitalize tracking-widest hover:bg-slate-200 transition-all text-center">
            Batal
        </a>

        <button type="submit"
                class="flex-1 bg-[#004AAD] text-white py-4 px-4 rounded-xl font-bold text-[10px] md:text-xs capitalize tracking-widest hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/10 active:scale-[0.98]">
            Simpan
        </button>
    </div>
</form>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById('icon-' + inputId);
        
        if (input.type === "password") {
            input.type = "text";
            // Ganti ke ikon mata garis coret
            icon.classList.remove('far', 'fa-eye');
            icon.classList.add('fas', 'fa-eye-slash');
        } else {
            input.type = "password";
            // Kembalikan ke ikon mata garis
            icon.classList.remove('fas', 'fa-eye-slash');
            icon.classList.add('far', 'fa-eye');
        }
    }
</script>
@endsection