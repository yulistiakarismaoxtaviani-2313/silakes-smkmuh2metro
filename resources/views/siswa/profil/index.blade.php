@extends('layouts.siswa')

@section('content')
<div id="konten-utama">
<div class="flex-1 bg-[#F8FAFC] p-8 overflow-y-auto custom-scrollbar font-sans">
    
    <div class="max-w-4xl mx-auto">
 

        <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
            
            {{-- HEADER PROFIL --}}
            <div x-data="{ photoModal: false }" class="bg-[#004AAD] pt-12 pb-10 text-center relative">
                <div @click="photoModal = true" class="inline-block relative p-1.5 bg-white rounded-2xl shadow-2xl cursor-zoom-in group transition-all hover:scale-105 z-10">
                    <img src="{{ asset('storage/profil/' . ($user->photo ?? 'default.png')) }}" 
                         class="w-32 h-40 object-cover rounded-xl">
                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity rounded-xl">
                        <i class="fas fa-search-plus text-white text-xl"></i>
                    </div>
                </div>

                <h2 class="mt-6 text-white font-bold text-2xl tracking-tight z-10 relative">{{ $user->nama }}</h2>
                
                <div class="mt-4 flex flex-col items-center gap-2 z-10 relative">
                    <span class="px-4 py-1.5 bg-white/10 rounded-full text-blue-50 text-[11px] font-semibold tracking-wide border border-white/20 backdrop-blur-sm">
                        NIS : {{ $user->siswa->nis ?? '-' }}
                    </span>
                    
                    <div class="flex items-center justify-center gap-1">
    <p class="text-blue-100/80 text-[11px] font-medium uppercase leading-none">
        {{ $user->siswa->profil->programKeahlian->nama_program ?? 'Program Keahlian Belum Diatur' }}
        |
        {{ $user->siswa->profil->konsentrasi_keahlian ?? 'Konsentrasi Belum Diatur' }}
    </p>

    <a href="{{ route('siswa.profil.edit-program') }}"
       class="text-white/50 hover:text-white transition-colors flex items-center">
        <i class="fas fa-edit text-[10px]"></i>
    </a>
</div>
                </div>

                {{-- MODAL FOTO --}}
                <div x-show="photoModal" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     @keydown.escape.window="photoModal = false"
                     class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/90"
                     style="display: none;">
                    <button @click="photoModal = false" class="absolute top-5 right-5 text-white hover:scale-110 transition-transform">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                    <img src="{{ asset('storage/profil/' . ($user->photo ?? 'default.png')) }}" 
                         @click.away="photoModal = false"
                         class="max-w-full max-h-[85vh] rounded-xl shadow-2xl transform transition-all">
                </div>
            </div>

            {{-- BODY DATA --}}
            <div class="p-8 md:p-12 space-y-12">
                
                {{-- 1. DATA PRIBADI --}}
                <div class="relative">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user text-[#004AAD] text-sm"></i>
                            </div>
                            <h3 class="text-slate-800 font-bold text-sm tracking-tight">Data Pribadi</h3>
                        </div>
                        <a href="{{ route('siswa.profil.edit-pribadi') }}" class="flex items-center gap-2 text-[11px] font-bold text-[#004AAD] bg-blue-50 px-4 py-2 rounded-lg hover:bg-[#004AAD] hover:text-white transition-all">
                            <i class="fas fa-edit text-[10px]"></i> Edit Profil
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 bg-gray-50/50 p-8 rounded-2xl border border-gray-100">
                        <div class="space-y-1">
                            <span class="text-slate-400 text-[11px] font-semibold uppercase tracking-wider">Nama Lengkap</span>
                            <p class="text-slate-700 font-medium text-sm">{{ $user->nama }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-slate-400 text-[11px] font-semibold uppercase tracking-wider">Jenis Kelamin</span>
                            <p class="text-slate-700 font-medium text-sm">
                                @php $jk = strtoupper($user->siswa->jenis_kelamin ?? ''); @endphp
                                {{ $jk == 'L' ? 'Laki-laki' : ($jk == 'P' ? 'Perempuan' : '-') }}
                            </p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-slate-400 text-[11px] font-semibold uppercase tracking-wider">Tempat, Tanggal Lahir</span>
                            <p class="text-slate-700 font-medium text-sm">{{ $user->siswa->profil->tempat_lahir ?? '-' }}, {{ $user->siswa->profil->tanggal_lahir ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-slate-400 text-[11px] font-semibold uppercase tracking-wider">Agama</span>
                            <p class="text-slate-700 font-medium text-sm">{{ $user->siswa->profil->agama ?? '-' }}</p>
                        </div>
                        <div class="space-y-1 col-span-full">
                            <span class="text-slate-400 text-[11px] font-semibold uppercase tracking-wider">Alamat Lengkap</span>
                            <p class="text-slate-700 font-medium text-sm leading-relaxed">{{ $user->siswa->profil->alamat_siswa ?? 'Alamat belum dilengkapi' }}</p>
                        </div>
                    </div>
                </div>

{{-- 2. INFORMASI KONTAK & KEAMANAN --}}
<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-50 pb-3">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-address-card text-[#004AAD] text-sm"></i>
            </div>
            <h3 class="text-slate-800 font-bold text-sm tracking-tight">Kontak & Keamanan Akun</h3>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('siswa.profil.edit-kontak') }}" class="text-[#004AAD] hover:scale-110 transition-transform" title="Edit Kontak">
                <i class="fas fa-pen-square text-xl"></i>
            </a>
            <a href="{{ route('siswa.profil.edit-password') }}" class="text-[#004AAD] hover:scale-110 transition-transform" title="Ubah Sandi">
            </a>
        </div>
    </div>
    
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white border border-gray-100 p-6 rounded-2xl shadow-sm">


{{-- NO HP / WHATSAPP --}}
<div class="flex items-center gap-4 md:border-t border-gray-100 md:pt-6">
    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-[#004AAD]">
        <i class="fab fa-whatsapp text-lg"></i>
    </div>
    <div>
        <span class="text-slate-400 text-[10px] font-bold uppercase block mb-0.5 tracking-wider">
            WhatsApp
        </span>
        <p class="text-slate-700 font-medium text-sm">
            {{ $user->siswa->profil->no_hp ?? '-' }}
        </p>
    </div>
</div>
    
{{-- EMAIL --}}
       <div class="flex items-center gap-4 md:border-l border-gray-100 md:pl-6">
    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-[#004AAD]">
        <i class="far fa-envelope text-lg"></i>
    </div>
    <div>
        <span class="text-slate-400 text-[10px] font-bold uppercase block mb-0.5 tracking-wider">
            Email Akun
        </span>
        <p class="text-slate-700 font-medium text-sm truncate max-w-[150px] md:max-w-none">
            {{ $user->email }}
        </p>
    </div>
</div>

    {{-- USERNAME --}}
<div class="flex items-center gap-4">
    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-[#004AAD]">
        <i class="far fa-user text-lg"></i>
    </div>
    <div>
        <span class="text-slate-400 text-[10px] font-bold uppercase block mb-0.5 tracking-wider">
            Username
        </span>
        <p class="text-slate-700 font-medium text-sm">
            {{ $user->username }}
        </p>
    </div>
</div>

        {{-- KATA SANDI --}}
<div class="flex items-center justify-between md:border-t md:border-l border-gray-100 md:pt-6 md:pl-6">
    <div class="flex items-center gap-4">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-[#004AAD]">
            <i class="fas fa-lock text-lg"></i>
        </div>
        <div>
            <span class="text-slate-400 text-[10px] font-bold uppercase block mb-0.5 tracking-wider">
                Password
            </span>
            <p class="text-slate-700 font-medium text-sm">••••••••</p>
        </div>
    </div>

    <a href="{{ route('siswa.profil.edit-password') }}"
       class="text-[9px] font-bold text-[#004AAD] border border-blue-100 px-2 py-1 rounded-md hover:bg-blue-50 transition-colors shrink-0">
        Ubah
    </a>
</div>
    </div>
</div>

                {{-- 4. ORANG TUA / WALI (FULL WIDTH) --}}
                <div class="space-y-6">
                    <div class="flex justify-between items-center border-b border-gray-50 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-[#004AAD] text-sm"></i>
                            </div>
                            <h3 class="text-slate-800 font-bold text-sm tracking-tight">Data Orang Tua / Wali</h3>
                        </div>
                        <a href="{{ route('siswa.profil.edit-ortu') }}" class="text-[#004AAD] hover:scale-110 transition-transform">
                            <i class="fas fa-pen-square text-xl"></i>
                        </a>
                    </div>
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2">
                            <div class="p-8 border-b md:border-b-0 md:border-r border-gray-100">
                                <div class="flex items-center gap-2 mb-6">
                                    <div class="w-1.5 h-4 bg-[#004AAD] rounded-full"></div>
                                    <h4 class="text-[11px] font-bold uppercase text-slate-400 tracking-widest">Informasi Ayah</h4>
                                </div>
                                <div class="space-y-5">
                                    <div>
                                        <span class="text-slate-400 text-[10px] font-bold uppercase block mb-0.5">Nama Lengkap Ayah</span>
                                        <p class="text-slate-800 font-bold text-sm">{{ $user->siswa->profil->nama_ayah ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 text-[10px] font-bold uppercase block mb-0.5">Pekerjaan Ayah</span>
                                        <p class="text-slate-700 font-medium text-sm">{{ $user->siswa->profil->pekerjaan_ayah ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-8">
                                <div class="flex items-center gap-2 mb-6">
                                    <div class="w-1.5 h-4 bg-[#004AAD] rounded-full"></div>
                                    <h4 class="text-[11px] font-bold uppercase text-slate-400 tracking-widest">Informasi Ibu</h4>
                                </div>
                                <div class="space-y-5">
                                    <div>
                                        <span class="text-slate-400 text-[10px] font-bold uppercase block mb-0.5">Nama Lengkap Ibu</span>
                                        <p class="text-slate-800 font-bold text-sm">{{ $user->siswa->profil->nama_ibu ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 text-[10px] font-bold uppercase block mb-0.5">Pekerjaan Ibu</span>
                                        <p class="text-slate-700 font-medium text-sm">{{ $user->siswa->profil->pekerjaan_ibu ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 5. STATUS AKADEMIK (SOLID COLOR) --}}
                <div class="pt-4">
                    <div class="bg-[#004AAD] p-8 rounded-2xl shadow-lg shadow-blue-900/10 text-white relative overflow-hidden">
                        <i class="fas fa-graduation-cap absolute -right-4 -bottom-4 text-white/10 text-9xl"></i>
                        <div class="flex flex-col md:flex-row justify-between items-center gap-8 relative z-10">
                            <div class="flex items-center gap-6">
                                <div class="relative">
                                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/30">
                                        <i class="fas fa-check-circle text-2xl text-white"></i>
                                    </div>
                                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-400 rounded-full border-2 border-[#004AAD] animate-pulse"></div>
                                </div>
                                <div>
                                    <span class="text-blue-100 text-[10px] font-bold uppercase tracking-widest">Status Keaktifan</span>
                                    <h4 class="text-2xl font-bold tracking-tight">{{ $user->siswa->profil->status_akun ?? 'Aktif' }}</h4>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-xl border border-white/20 text-center min-w-[120px]">
                                    <span class="text-blue-100 text-[9px] font-bold uppercase block mb-1">Tahun Ajaran</span>
                                    <p class="text-sm font-bold">{{ $user->siswa->tahunAjaran->tahun_ajaran ?? '-' }}</p>
                                </div>
                                <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-xl border border-white/20 text-center min-w-[120px]">
                                    <span class="text-blue-100 text-[9px] font-bold uppercase block mb-1">Data Per</span>
                                    <p class="text-sm font-bold">{{ date('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 767px) {
        /* Kita tambahkan ID/Class konten utama di depan setiap selector */
        /* Ganti #konten-utama dengan ID yang ada di div pembungkus konten Anda */
        
        #konten-utama .p-8 { padding: 0.75rem !important; }

        #konten-utama .rounded-xl, 
        #konten-utama .rounded-2xl { 
            border-radius: 1rem !important; 
        }

        #konten-utama .max-w-4xl { 
            max-width: 100% !important; 
            margin: 0 !important; 
        }

        #konten-utama .bg-\[#004AAD\] {
            border-radius: 1rem !important; 
            margin-left: 0 !important; 
            margin-right: 0 !important;
            width: 100% !important;
        }

        #konten-utama .shadow-xl, 
        #konten-utama .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }
    }
</style>
@endsection