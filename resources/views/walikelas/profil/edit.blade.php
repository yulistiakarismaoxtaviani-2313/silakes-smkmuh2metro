@extends('layouts.walikelas')

@section('content')


<div class="p-0 md:p-6 bg-[#f8fafc] min-h-screen font-sans">

    {{-- Container Satu Jalur (Sejajar ke Bawah) --}}
    <div class="max-w-3xl mx-auto space-y-8">

        @if(session('success'))
        <div class="mx-4 p-4 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-emerald-100">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
        @endif

        {{-- SECTION 1: FOTO PROFIL --}}
        <div class="bg-white rounded-[1rem] shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-[#004aad] p-4 text-center">
                <h3 class="text-white text-[10px] font-black uppercase tracking-[0.2em]">1. Foto Profil</h3>
            </div>
            
            <form action="{{ route('walikelas.profil.updateFoto') }}" method="POST" enctype="multipart/form-data" class="p-8 flex flex-col items-center">
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
                
                <p class="text-[9px] text-slate-400 font-bold uppercase mb-6 tracking-tight">Format: JPG, PNG (Max. 2MB)</p>

                <button type="submit" class="bg-[#004aad] text-white px-8 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-500 transition-all shadow-lg active:scale-95">
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

            <form action="{{ route('walikelas.profil.update') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" 
                               class="w-full bg-slate-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-[#004aad] transition-all outline-none">
                        @error('nama') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Mata Pelajaran</label>
                        <input type="text" name="mapel" value="{{ old('mapel', $user->guru->profilGuru->mapel ?? '') }}" 
                               class="w-full bg-slate-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-[#004aad] transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $user->guru->profilGuru->no_hp ?? '') }}" 
                               class="w-full bg-slate-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-[#004aad] transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Instansi</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="w-full bg-slate-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-[#004aad] transition-all outline-none">
                    </div>
                </div>

                <div class="flex gap-4 p-4 bg-blue-50/50 border-l-[4px] border-[#004aad] rounded-r-2xl">
                    <i class="fa-solid fa-circle-info text-[#004aad] mt-0.5"></i>
                    <p class="text-[10px] text-slate-500 leading-relaxed font-bold uppercase tracking-tight">
                        Catatan: Data NIP dan penetapan Kelas dikelola secara terpusat oleh Admin Kurikulum.
                    </p>
                </div>

                <button type="submit" class="w-full bg-[#004aad] text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-500 transition-all shadow-lg active:scale-[0.98]">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- SECTION 3: KEAMANAN AKUN --}}
        <div id="password-section" class="bg-white rounded-[1rem] shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center gap-3 bg-slate-50/50">
                <span class="w-2 h-6 bg-[#004aad] rounded-full"></span>
                <h3 class="text-slate-800 font-black text-xs uppercase tracking-widest">3. Keamanan Akun</h3>
            </div>

            <form action="{{ route('walikelas.profil.updatePassword') }}" method="POST" class="p-8 space-y-6" x-data="{ showPass: false, showConfirm: false }">
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

    @error('username')
        <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Password Baru --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Password Baru</label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" name="password" placeholder="Minimal 8 karakter"
                                   class="w-full bg-slate-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-rose-500 transition-all outline-none">
                            
                            <button type="button" @click="showPass = !showPass" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#004aad] transition-colors">
                                <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('password') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" placeholder="Ulangi password baru"
                                   class="w-full bg-slate-50 border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-rose-500 transition-all outline-none">
                            
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#004aad] transition-colors">
                                <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#004aad] text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-500 transition-all shadow-lg active:scale-[0.98]">
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