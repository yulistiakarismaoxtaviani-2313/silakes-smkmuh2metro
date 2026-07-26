@extends('layouts.guru')

@section('content')


<div class="p-0 md:p-6 bg-[#f8fafc] min-h-screen font-sans">

    {{-- Container Satu Jalur (Sejajar ke Bawah) --}}
    <div class="max-w-3xl mx-auto space-y-8">
        
        {{-- SECTION 1: FOTO PROFIL --}}
        <div class="bg-white rounded-[1rem] shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-[#004aad] p-4 text-center">
                <h3 class="text-white text-[10px] font-black uppercase tracking-[0.2em]">1. Foto Profil</h3>
            </div>
            
            <form action="{{ route('guru.profil.updateFoto') }}" method="POST" enctype="multipart/form-data" class="p-8 flex flex-col items-center">
                @csrf
                <div x-data="{ photoPreview: null }" class="relative group mb-6">
                    <div class="w-40 h-40 bg-slate-50 rounded-full border-2 border-dashed border-slate-200 overflow-hidden flex items-center justify-center p-1 group-hover:border-[#004aad] transition-all">
                        <template x-if="!photoPreview">
                            <img src="{{ asset('storage/profil/' . ($user->photo ?? 'default.png')) }}" class="w-full h-full object-cover rounded-full shadow-md">
                        </template>
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-full h-full object-cover rounded-full">
                        </template>
                    </div>

                    <label class="absolute bottom-0 right-0 bg-[#004aad] w-10 h-10 rounded-full text-white shadow-xl cursor-pointer hover:scale-110 transition-all flex items-center justify-center border-4 border-white">
                        <i class="fa-solid fa-camera text-xs"></i>
                        <input type="file" name="foto" class="hidden" required @change="
                            const file = $event.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = (e) => { photoPreview = e.target.result; };
                                reader.readAsDataURL(file);
                            }
                        ">
                    </label>
                </div>
                
                <button type="submit" class="bg-[#004aad] text-white px-8 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-800 transition-all shadow-lg">
                    Simpan Foto Baru
                </button>
            </form>
        </div>

        {{-- SECTION 2: INFORMASI PERSONAL --}}
        <div class="bg-white rounded-[1rem] shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center gap-3 bg-slate-50/50">
                <span class="w-2 h-6 bg-[#004aad] rounded-full"></span>
                <h3 class="text-slate-800 font-black text-xs uppercase tracking-widest">2. Informasi Pribadi</h3>
            </div>

            <form action="{{ route('guru.profil.update') }}" method="POST" class="p-8 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" 
                               class="w-full bg-slate-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-[#004aad] transition-all outline-none">
                    </div>


                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $user->guru->profilGuru->no_hp ?? '') }}" 
                               class="w-full bg-slate-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-[#004aad] transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="w-full bg-slate-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-[#004aad] transition-all outline-none">
                    </div>
                    
<div x-data="{ openMapel: false }" class="space-y-2">
    
    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
        Mata Pelajaran
    </label>

    {{-- Tombol Dropdown --}}
    <button type="button"
            @click="openMapel = !openMapel"
            class="w-full bg-slate-50 border border-gray-100 rounded-2xl px-5 py-3 text-left flex justify-between items-center">

        <span class="text-sm font-bold text-slate-700">
            Pilih Mata Pelajaran
        </span>

        <i class="fa-solid fa-chevron-down text-slate-400"></i>
    </button>

    {{-- Isi Dropdown --}}
    <div x-show="openMapel"
         x-transition
         class="border border-gray-100 rounded-2xl p-4 bg-slate-50 max-h-48 overflow-y-auto">

        @foreach(\App\Models\Mapel::all() as $mapel)
            <label class="flex items-center gap-3 py-2 cursor-pointer">
                <input type="checkbox"
                       name="mapel[]"
                       value="{{ $mapel->id_mapel }}"
                       {{ $user->guru->mapel->contains('id_mapel', $mapel->id_mapel) ? 'checked' : '' }}>

                <span class="text-sm font-medium">
                    {{ $mapel->nama_mapel }}
                </span>
            </label>
        @endforeach

    </div>
</div>

                </div>
                <button type="submit" class="w-full bg-[#004aad] text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-800 transition-all shadow-lg">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- SECTION 3: KEAMANAN AKUN --}}
<div class="bg-white rounded-[1rem] shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex items-center gap-3 bg-slate-50/50">
        <span class="w-2 h-6 bg-[#004aad] rounded-full"></span>
        <h3 class="text-slate-800 font-black text-xs uppercase tracking-widest">3. Keamanan Akun</h3>
    </div>

    
    <form action="{{ route('guru.profil.updatePassword') }}" method="POST" class="p-8 space-y-6" x-data="{ showPass: false, showConfirm: false }">
        @csrf

        {{-- Username --}}

    <div class="space-y-2">

        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">

            Username

        </label>



        <input type="text"
       name="username"
       value="{{ old('username', $user->username) }}"
       class="w-full bg-slate-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-[#004aad] transition-all outline-none">

    </div>

    
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- Password Baru --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Password Baru</label>
                <div class="relative">
                    <input :type="showPass ? 'text' : 'password'" name="password" placeholder="Minimal 8 karakter"
                           class="w-full bg-slate-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-rose-500 transition-all outline-none">
                    
                    <button type="button" @click="showPass = !showPass" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#004aad] transition-colors">
                        <template x-if="!showPass">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </template>
                        <template x-if="showPass">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 014.13-5.13m4.131-1.071A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.012 2.023M3 3l18 18"></path></svg>
                        </template>
                    </button>
                </div>
            </div>

            {{-- Konfirmasi Password --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                <div class="relative">
                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" placeholder="Ulangi password"
                           class="w-full bg-slate-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-rose-500 transition-all outline-none">
                    
                    <button type="button" @click="showConfirm = !showConfirm" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#004aad] transition-colors">
                        <template x-if="!showConfirm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </template>
                        <template x-if="showConfirm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 014.13-5.13m4.131-1.071A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.012 2.023M3 3l18 18"></path></svg>
                        </template>
                    </button>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-[#004aad] text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-black transition-all shadow-lg">
            Simpan Perubahan
        </button>
    </form>
</div>

    </div>
</div>

<style>
    .font-poppins { font-family: 'Poppins', sans-serif; }
    input:focus { ring: none; outline: none; }
</style>
@endsection