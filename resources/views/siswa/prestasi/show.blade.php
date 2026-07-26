@extends('layouts.siswa')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans">
    
    {{-- KONTAINER FULL WIDTH --}}
    <div class="w-full space-y-8">
        
        {{-- 2. Konten Utama (Profil & Metadata Lomba) --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden w-full">
            {{-- Bagian Judul & Profil Siswa --}}
            <div class="p-8 md:p-10 border-b border-gray-100 bg-gray-50/30">
                <div class="flex flex-col md:flex-row items-center gap-8 mb-10">
                    <div class="relative flex-shrink-0">
                        <img src="{{ Auth::user()->siswa && Auth::user()->siswa->foto ? asset('storage/profil/' . Auth::user()->siswa->foto) : asset('img/default-user.png') }}" 
                             class="w-32 h-32 rounded-2xl object-cover shadow-md border-4 border-white">
                        <div class="absolute -bottom-2 -right-2 bg-yellow-400 text-[#004aad] w-10 h-10 rounded-xl flex items-center justify-center shadow-lg border-2 border-white">
                            <i class="fa-solid fa-star text-sm"></i>
                        </div>
                    </div>
                    <div class="text-center md:text-left">
                        <span class="px-3 py-1 bg-blue-50 text-[#004aad] text-[10px] font-extrabold uppercase rounded-lg border border-blue-100 tracking-wider mb-3 inline-block">
                            Profil Siswa Berprestasi
                        </span>
                        <h2 class="text-2xl md:text-4xl font-black text-slate-800 leading-tight uppercase tracking-tight">
                            {{ Auth::user()->siswa->nama_siswa ?? Auth::user()->nama }}
                        </h2>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-widest mt-1">Kelas: {{ Auth::user()->siswa->kelas->nama_kelas ?? '-' }}</p>
                    </div>
                </div>

                {{-- Baris Info Cepat (Metadata Lomba Gaya Pengumuman) --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">
                    <div class="bg-white p-3 md:p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center md:items-center gap-2 md:gap-4">
                        <div class="text-[#004aad] bg-blue-50 w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center text-sm md:text-lg">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="text-center md:text-left">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em]">Peringkat</p>
                            <p class="text-[11px] md:text-sm font-bold text-slate-700 uppercase truncate">{{ $prestasi->peringkat }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-3 md:p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center md:items-center gap-2 md:gap-4">
                        <div class="text-[#004aad] bg-blue-50 w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center text-sm md:text-lg">
                            <i class="fas fa-globe-asia"></i>
                        </div>
                        <div class="text-center md:text-left">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em]">Tingkat</p>
                            <p class="text-[11px] md:text-sm font-bold text-slate-700 uppercase truncate">{{ $prestasi->tingkat }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-3 md:p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center md:items-center gap-2 md:gap-4">
                        <div class="text-[#004aad] bg-blue-50 w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center text-sm md:text-lg">
                            <i class="far fa-calendar-check"></i>
                        </div>
                        <div class="text-center md:text-left">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em]">Waktu</p>
                            <p class="text-[11px] md:text-sm font-bold text-slate-700 uppercase truncate">{{ \Carbon\Carbon::parse($prestasi->tanggal)->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-3 md:p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center md:items-center gap-2 md:gap-4">
                        <div class="text-emerald-500 bg-emerald-50 w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center text-sm md:text-lg">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="text-center md:text-left">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em]">Status Verifikasi</p>
                            <p class="text-[11px] md:text-sm font-bold {{ $prestasi->status_validasi == 'Disetujui' ? 'text-emerald-600' : 'text-amber-600' }} uppercase tracking-widest">
                                {{ $prestasi->status_validasi ?? 'Proses' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Bagian Isi Detail --}}
            <div class="p-6 md:p-14">
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] block mb-2">Nama Kompetisi</label>
                        <h3 class="text-lg md:text-2xl font-black text-slate-800 uppercase leading-snug">{{ $prestasi->nama_lomba }}</h3>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] block mb-2">Penyelenggara</label>
                        <p class="text-lg font-bold text-slate-600 uppercase">{{ $prestasi->penyelenggara_lomba }}</p>
                    </div>
                </div>

                {{-- Reward SPP (Highlight khusus) --}}
                <div class="mt-8 md:mt-12 p-6 md:p-8 bg-emerald-50 rounded-2xl border-l-8 border-emerald-500 flex flex-row items-center justify-between shadow-inner gap-4">
                    <div>
                        <p class="text-[9px] font-black text-emerald-700 uppercase tracking-widest mb-1">Benefit Kejuaraan</p>
                        <p class="text-[13px] md:text-2xl font-black text-emerald-600 uppercase">Reward Bebas SPP: {{ $prestasi->bebas_spp ?? '-' }}</p>
                    </div>
                    <i class="fas fa-gift text-emerald-200 text-4xl md:text-5xl"></i>
                </div>
            </div>
        </div>

        {{-- 3. Bagian Lampiran (Bukti Sertifikat) --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden w-full">
            <div class="p-6 border-b border-gray-100 flex flex-row flex-nowrap justify-between items-center gap-2 bg-white px-4 md:px-10">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-1.5 h-5 md:w-2 md:h-6 bg-[#004aad] rounded-full shrink-0"></div>
                    <h3 class="font-black text-[9px] md:text-xs uppercase tracking-[0.1em] md:tracking-[0.2em] text-slate-800 truncate">Berkas Bukti & Sertifikat</h3>
                </div>
                @if($prestasi->file_bukti)
                <a href="{{ asset('storage/sertifikat/' . $prestasi->file_bukti) }}" download 
                   class="bg-[#004aad] text-white px-3 py-2 md:px-6 md:py-3 rounded-xl text-[9px] md:text-[10px] font-black uppercase hover:bg-slate-800 transition shadow-md tracking-widest shrink-0 flex items-center justify-center">
                    <i class="fa-solid fa-download md:mr-2"></i><span class="hidden md:inline">Unduh</span> 
                </a>
                @endif
            </div>
            
            <div class="p-4 md:p-14 bg-gray-50/30">
                <div class="bg-white rounded-2xl p-4 md:p-8 border border-gray-100 flex flex-col items-center justify-center min-h-[250px] md:min-h-[400px] shadow-sm">
                    @if($prestasi->file_bukti)
                        <img src="{{ asset('storage/sertifikat/' . $prestasi->file_bukti) }}" 
                             class="max-w-full h-auto rounded-xl md:rounded-3xl shadow-2xl border-[6px] md:border-[12px] border-gray-50 transition-transform duration-500">
                    @else
                        <div class="text-center opacity-30">
                            <i class="fa-solid fa-folder-open fa-3x md:fa-4x mb-4 text-gray-300"></i>
                            <p class="font-bold uppercase tracking-[0.2em] md:tracking-[0.3em] text-[9px] md:text-xs text-gray-400">Berkas sertifikat belum diunggah</p>
                        </div>
                    @endif
                </div>

                {{-- Catatan Admin --}}
                <div class="mt-10">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fas fa-comment-dots text-gray-400"></i>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Catatan Admin</span>
                    </div>
                    <div class="p-8 bg-blue-50/50 rounded-2xl border-l-8 border-[#004aad] italic text-sm text-slate-600 shadow-inner">
                        "{{ $prestasi->keterangan ?? 'Data sedang dalam proses verifikasi oleh bagian kesiswaan. Harap menunggu konfirmasi lebih lanjut.' }}"
                    </div>
                </div>
            </div>
        </div>

        

    </div>
</div>

<style>
    .rounded-2xl { border-radius: 1rem !important; }
    .rounded-3xl { border-radius: 1.5rem !important; }
    .rounded-xl { border-radius: 0.75rem !important; }

/* Mengatasi layout yang terlalu lebar di HP */
    @media (max-width: 640px) {
        .p-8, .p-10, .p-14 { padding: 1.5rem !important; }
        .gap-8 { gap: 1.5rem !important; }
        .text-4xl { font-size: 1.8rem !important; }
    }
    
    /* Memastikan gambar sertifikat tetap proporsional */
    img.shadow-2xl {
        max-height: 50vh;
        object-fit: contain;
    }
</style>
@endsection