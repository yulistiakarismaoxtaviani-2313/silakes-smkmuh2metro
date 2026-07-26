@extends('layouts.admin')

@section('content')
<div class="p-0 md:p-6 bg-gray-50 min-h-screen">
    {{-- Header Utama --}}
    <div class="bg-[#004AAD] text-white p-5 rounded-t-xl font-bold text-center uppercase tracking-[0.3em] shadow-lg">
        Manajemen Profil Admin
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-blue-50 border-l-4 border-[#004AAD] text-[#004AAD] p-4 mt-4 shadow-sm flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <p class="text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif

    <div class="mt-6 space-y-6">
        
        {{-- BAGIAN ATAS: RINGKASAN PROFIL --}}
        <div class="bg-white rounded-xl shadow-md border-t-4 border-[#004AAD] overflow-hidden">
            <div class="p-8 flex flex-col md:flex-row items-center gap-8">
                <div class="relative group">
                    <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-gray-100 shadow-xl bg-white flex items-center justify-center cursor-zoom-in">
                        @if($user->photo)
                            <a href="{{ asset('storage/profil/' . $user->photo) }}" target="_blank">
                                <img src="{{ asset('storage/profil/' . $user->photo) }}?v={{ time() }}" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                                     alt="Profile">
                            </a>
                        @else
                            <div class="w-full h-full bg-blue-50 flex items-center justify-center text-[#004AAD] text-6xl font-black">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-center md:text-left flex-1">
                    <h3 class="font-black text-3xl text-[#004AAD] uppercase tracking-tight">{{ $user->nama }}</h3>
                    <p class="text-xs text-gray-400 uppercase tracking-[0.4em] font-bold mt-2">Administrator Sistem</p>
                    
                    <div class="flex flex-wrap gap-4 mt-6">
                        <div class="flex items-center gap-3 bg-blue-50 px-4 py-2 rounded-full border border-blue-100">
                            <i class="fas fa-envelope text-[#004AAD]"></i>
                            <span class="text-sm text-gray-700 font-semibold">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN BAWAH: FORM --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{ showOld: false, showNew: false, showConf: false }">
            
            {{-- Form Pengaturan Profil --}}
            <div class="bg-white rounded-xl shadow-md border-t-4 border-[#004AAD]">
                <div class="p-4 bg-gray-50 border-b flex items-center gap-3">
                    <i class="fas fa-id-card text-[#004AAD] text-lg"></i>
                    <h4 class="font-black text-[#004AAD] uppercase text-xs tracking-widest">Informasi Dasar</h4>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf 
                        @method('PATCH')
                        
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" 
                                class="w-full border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#004AAD] outline-none border">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Email Login</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                class="w-full border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#004AAD] outline-none border">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Ganti Foto Profil</label>
                            <input type="file" name="photo" class="w-full text-xs border rounded-lg p-2 bg-gray-50">
                        </div>

                        <button type="submit" class="w-full bg-[#004AAD] text-white py-3 rounded-lg text-xs font-black uppercase tracking-widest hover:bg-blue-900 transition flex items-center justify-center gap-2">
                            <i class="fas fa-sync-alt"></i> Update Profil
                        </button>
                    </form>
                </div>
            </div>

            {{-- Form Ganti Password dengan Ikon Mata --}}
            <div class="bg-white rounded-xl shadow-md border-t-4 border-[#004AAD]">
                <div class="p-4 bg-gray-50 border-b flex items-center gap-3">
                    <i class="fas fa-lock text-[#004AAD] text-lg"></i>
                    <h4 class="font-black text-[#004AAD] uppercase text-xs tracking-widest">Keamanan Akun</h4>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.profil.password') }}" method="POST" class="space-y-4">
                        @csrf 
                        @method('PUT')
                        
                        {{-- Password Saat Ini --}}
                        <div class="relative">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Password Saat Ini</label>
                            <div class="relative">
                                <input :type="showOld ? 'text' : 'password'" name="current_password" required 
                                    class="w-full border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#004AAD] outline-none border pr-12">
                                <button type="button" @click="showOld = !showOld" class="absolute right-3 top-1/2 -translate-y-1/2 focus:outline-none">
                                    <img :src="showOld ? '{{ asset('img/icons/eye1.png') }}' : '{{ asset('img/icons/eye.png') }}'" class="w-6 h-6 opacity-70 hover:opacity-100 transition-opacity">
                                </button>
                            </div>
                        </div>
                        
                        {{-- Password Baru --}}
                        <div class="relative">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Password Baru</label>
                            <div class="relative">
                                <input :type="showNew ? 'text' : 'password'" name="password" required 
                                    class="w-full border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#004AAD] outline-none border pr-12">
                                <button type="button" @click="showNew = !showNew" class="absolute right-3 top-1/2 -translate-y-1/2 focus:outline-none">
                                    <img :src="showNew ? '{{ asset('img/icons/eye1.png') }}' : '{{ asset('img/icons/eye.png') }}'" class="w-6 h-6 opacity-70 hover:opacity-100 transition-opacity">
                                </button>
                            </div>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="relative">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input :type="showConf ? 'text' : 'password'" name="password_confirmation" required 
                                    class="w-full border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#004AAD] outline-none border pr-12">
                                <button type="button" @click="showConf = !showConf" class="absolute right-3 top-1/2 -translate-y-1/2 focus:outline-none">
                                    <img :src="showConf ? '{{ asset('img/icons/eye1.png') }}' : '{{ asset('img/icons/eye.png') }}'" class="w-6 h-6 opacity-70 hover:opacity-100 transition-opacity">
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-[#004AAD] text-white py-3 rounded-lg text-xs font-black uppercase tracking-widest hover:bg-blue-900 transition flex items-center justify-center gap-2">
                            <i class="fas fa-key"></i> Ganti Password
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection