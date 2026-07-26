@extends('layouts.walikelas')

@section('content')


<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans" x-data="{ photoModal: false }">

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-[1rem] shadow-sm border border-gray-200 overflow-hidden">
            
            {{-- Header Profil Wali Kelas (KARTU BIRU dengan Pattern) --}}
            <div class="bg-[#004aad] pt-16 pb-12 text-center relative overflow-hidden group">
                {{-- SVG Pattern Tipis --}}
                <svg class="absolute inset-0 w-full h-full opacity-10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <pattern id="pattern-circles" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                        <circle cx="20" cy="20" r="1" fill="currentColor"></circle>
                    </pattern>
                    <rect width="100%" height="100%" fill="url(#pattern-circles)"></rect>
                </svg>

                {{-- Foto Profil dengan Fitur Zoom --}}
                <div class="relative z-10 inline-block">
                    <div @click="photoModal = true" class="relative p-1.5 bg-white rounded-[1.5rem] shadow-2xl cursor-zoom-in transition-all hover:scale-105 group/photo">
                        <img src="{{ asset('storage/profil/' . ($user->photo ?? 'default.png')) }}" 
                             class="w-32 h-44 object-cover rounded-[1.2rem]">
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/photo:opacity-100 flex items-center justify-center transition-opacity rounded-[1.2rem]">
                            <i class="fa-solid fa-magnifying-glass-plus text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <h2 class="mt-6 text-white font-black text-2xl tracking-tight relative z-10 uppercase">{{ $user->nama }}</h2>
                
                <div class="mt-4 flex flex-col items-center gap-2 relative z-10">
                    <span class="px-4 py-1.5 bg-white/10 rounded-full text-blue-100 text-[10px] font-black uppercase tracking-widest border border-white/20 backdrop-blur-sm">
                        NBM : {{ $user->guru->nip ?? '-' }}
                    </span>
                    <div class="flex items-center gap-2">
                        <p class="text-blue-200/80 text-[11px] font-bold uppercase tracking-tight">
                            <i class="fa-solid fa-chalkboard-user mr-1"></i> {{ $user->guru->profilGuru->mapel ?? 'Mata Pelajaran' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Konten Data --}}
            <div class="p-10 md:p-12">
                {{-- SECTION 1: INFORMASI DASAR --}}
                <div class="flex items-center gap-3 mb-8">
                    <span class="w-8 h-1.5 bg-[#004aad] rounded-full"></span>
                    <h3 class="text-slate-800 font-black text-xs uppercase tracking-[0.2em]">Informasi Dasar</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Lengkap --}}
                    <div class="p-5 bg-slate-50 rounded-3xl border border-gray-100 group hover:bg-white hover:border-[#004aad]/20 transition-all">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nama Lengkap</span>
                        <span class="text-sm font-black text-slate-800">{{ $user->nama }}</span>
                    </div>
                    
                    {{-- NIP --}}
                    <div class="p-5 bg-slate-50 rounded-3xl border border-gray-100 group hover:bg-white hover:border-[#004aad]/20 transition-all">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">NBM</span>
                        <span class="text-sm font-black text-slate-800">{{ $user->guru->nip ?? '-' }}</span>
                    </div>

                    {{-- Mata Pelajaran --}}
                    <div class="p-5 bg-slate-50 rounded-3xl border border-gray-100 group hover:bg-white hover:border-[#004aad]/20 transition-all">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Guru Mata Pelajaran</span>
                        <span class="text-sm font-black text-slate-800 uppercase">{{ $user->guru->profilGuru->mapel ?? '-' }}</span>
                    </div>
                    
                    {{-- Tanggung Jawab Kelas --}}
                    <div class="p-5 bg-slate-50 rounded-3xl border border-gray-100 group hover:bg-white hover:border-[#004aad]/20 transition-all">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Tanggung Jawab Kelas</span>
                        <span class="text-sm font-black text-slate-800">
                            {{ $user->guru->kelas ? 'Wali Kelas ' . $user->guru->kelas->nama_kelas : 'Belum Ditentukan' }}
                        </span>
                    </div>

                    {{-- Email --}}
                    <div class="p-5 bg-slate-50 rounded-3xl border border-gray-100 group hover:bg-white hover:border-[#004aad]/20 transition-all">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Email</span>
                        <span class="text-sm font-black text-slate-800">{{ $user->email }}</span>
                    </div>
                    
                    {{-- No HP --}}
                    <div class="p-5 bg-slate-50 rounded-3xl border border-gray-100 group hover:bg-white hover:border-[#004aad]/20 transition-all">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">No HP</span>
                        <span class="text-sm font-black text-slate-800">{{ $user->guru->profilGuru->no_hp ?? '-' }}</span>
                    </div>
                </div>

                {{-- SECTION 2: KEAMANAN & AKUN --}}
                <div class="flex items-center gap-3 mb-8 mt-12">
                    <span class="w-8 h-1.5 bg-[#004aad] rounded-full"></span>
                    <h3 class="text-slate-800 font-black text-xs uppercase tracking-[0.2em]">Keamanan & Akun</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Username --}}
<div class="p-5 bg-slate-50 rounded-3xl border border-gray-100 group hover:bg-white hover:border-[#004aad]/20 transition-all">
    <div class="flex justify-between items-start mb-1">
        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Username</span>
    </div>
    <span class="text-sm font-black text-slate-800">{{ $user->username }}</span>
</div>

{{-- Email Login --}}
<div class="p-5 bg-slate-50 rounded-3xl border border-gray-100 group hover:bg-white hover:border-[#004aad]/20 transition-all">
    <div class="flex justify-between items-start mb-1">
        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Email</span>
    </div>
    <span class="text-sm font-black text-slate-800">{{ $user->email }}</span>
</div>

                    {{-- Password Masking --}}
                    <div class="p-5 bg-slate-50 rounded-3xl border border-gray-100 group hover:bg-white hover:border-[#004aad]/20 transition-all">
                        <div class="flex justify-between items-start mb-1">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Password</span>
                            <span class="text-[8px] bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full font-black uppercase">Enkripsi Aktif</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-black text-slate-800 tracking-[0.3em] uppercase">••••••••</span>
                            <a href="{{ route('walikelas.profil.edit') }}#password-section" class="text-[9px] font-black text-[#004aad] uppercase no-underline hover:underline">Ubah Password</a>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="mt-12 flex flex-col items-center gap-6">
                    <a href="{{ route('walikelas.profil.edit') }}" 
                       class="w-full md:w-auto bg-[#004aad] hover:bg-blue-800 text-white font-black text-[10px] uppercase tracking-[0.2em] px-12 py-4 rounded-2xl shadow-xl shadow-blue-100 transition-all hover:scale-105 flex items-center justify-center gap-3 no-underline">
                        <i class="fa-solid fa-user-pen"></i>
                        Edit Data Profil
                    </a>
                    
                   
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL LIGHTBOX FOTO --}}
    <div x-show="photoModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @keydown.escape.window="photoModal = false"
         class="fixed inset-0 z-[999] flex items-center justify-center p-6 bg-slate-900/90 backdrop-blur-sm"
         style="display: none;">
        
        <div class="relative max-w-sm w-full" @click.away="photoModal = false">
            <button @click="photoModal = false" class="absolute -top-12 right-0 text-white hover:text-red-400 transition-colors">
                <i class="fa-solid fa-xmark text-3xl"></i>
            </button>
            <img src="{{ asset('storage/profil/' . ($user->photo ?? 'default.png')) }}" 
                 class="w-full rounded-[2rem] shadow-2xl border-4 border-white">
        </div>
    </div>
</div>

<style>
    /* Utility tambahan agar sinkron dengan dashboard */
    .rounded-4xl { border-radius: 2rem !important; }
    .font-poppins { font-family: 'Poppins', sans-serif; }
</style>
@endsection