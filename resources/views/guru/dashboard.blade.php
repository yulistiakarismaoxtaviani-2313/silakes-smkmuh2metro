@extends('layouts.guru')

@section('content')
<div class="px-0 md:px-6 py-0 md:py-4 bg-[#f1f5f9] min-h-screen font-sans">
    

 {{-- 1. Banner Selamat Datang - Dashboard Guru Version --}}
    <div class="bg-white border-2 border-gray-200 rounded-2xl p-5 md:p-8 flex flex-col md:flex-row items-center justify-between shadow-sm mb-6 md:mb-10 relative overflow-hidden gap-6">

        
        <div class="flex items-center space-x-4 md:space-x-6 relative z-10 w-full">
            <div class="bg-[#004aad] p-4 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100 shrink-0">
                <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                </svg>
            </div>

            <div>
                <h1 class="text-gray-800 font-extrabold text-lg md:text-2xl tracking-tight leading-tight">
                    Selamat Datang, <span class="text-[#004aad]">{{ Auth::user()->nama }}</span>!
                </h1>
                <p class="text-gray-400 text-[9px] md:text-[10px] font-bold uppercase tracking-[0.15em] mt-1">
                    Panel Guru • SMK Muhammadiyah 2 Metro
                </p>
            </div>
        </div>

        {{-- Widget Tanggal & Jam --}}
            <div class="flex items-center gap-4 bg-gray-50 px-5 py-3 rounded-2xl border border-gray-100">
                <div class="text-right">
                    <p class="text-[9px] font-bold text-black uppercase tracking-[0.2em] leading-none mb-1">Update Terkini</p>
                    <p class="text-xs font-semibold text-black uppercase">
                        {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div class="w-[1px] h-8 bg-gray-200"></div>
                <div class="text-[#004aad]">
                    <i class="far fa-clock text-xl"></i>
                </div>
     </div>
        </div>

    <div class="space-y-6 md:space-y-8">
        
        {{-- SECTION 1: PRESENSI HARI INI --}}
        <div class="bg-white rounded-[1rem] shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-user text-[#004aad]"></i>
                    <h3 class="font-black text-slate-800 text-[11px] uppercase tracking-[0.2em]">Presensi Kelas Siswa Hari Ini</h3>
                </div>
            </div>

            <div class="p-4 md:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    @forelse($presensiHariIni as $item)
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden hover:border-[#004aad] transition-all shadow-sm">
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-slate-50/50">
                            <span class="font-black text-slate-800 uppercase text-xs tracking-tight group-hover:text-[#004aad] transition-colors">{{ $item['nama_kelas'] }}</span>
                            
                            @if($item['status_sesi'] == 'ditutup')
                                <span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase border border-emerald-100">
                                    <i class="fa-solid fa-check-double"></i> Selesai
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-red-50 text-red-600 text-[8px] font-black uppercase border border-red-100 animate-pulse">
                                    <i class="fa-solid fa-spinner fa-spin"></i> Sedang Berjalan
                                </span>
                            @endif
                        </div>
                        
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <span class="text-[9px] font-black text-slate-400 uppercase block mb-1 tracking-widest">Total Siswa</span>
                                    <span class="text-2xl font-black text-slate-800">{{ $item['total'] }}</span>
                                </div>
                                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-users text-[#004aad] text-xs"></i>
                                </div>
                            </div>

                            {{-- Indikator Warna H, I, S, A --}}
                            <div class="grid grid-cols-4 gap-2">
                                <div class="text-center p-2 bg-emerald-50/50 rounded-xl border border-emerald-100/50">
                                    <span class="block text-[8px] font-black text-emerald-400 uppercase mb-1">H</span>
                                    <span class="text-xs font-black text-emerald-600">{{ $item['hadir'] }}</span>
                                </div>
                                <div class="text-center p-2 bg-blue-50/50 rounded-xl border border-blue-100/50">
                                    <span class="block text-[8px] font-black text-blue-400 uppercase mb-1">I</span>
                                    <span class="text-xs font-black text-blue-600">{{ $item['izin'] }}</span>
                                </div>
                                <div class="text-center p-2 bg-amber-50/50 rounded-xl border border-amber-100/50">
                                    <span class="block text-[8px] font-black text-amber-400 uppercase mb-1">S</span>
                                    <span class="text-xs font-black text-amber-600">{{ $item['sakit'] }}</span>
                                </div>
                                <div class="text-center p-2 bg-red-50/50 rounded-xl border border-red-100/50">
                                    <span class="block text-[8px] font-black text-red-400 uppercase mb-1">A</span>
                                    <span class="text-xs font-black text-red-600">{{ $item['alfa'] }}</span>
                                </div>
                            </div>
@if($item['id_presensi'] !== null)
    <!-- Tombol Aktif jika Sesi Sudah Dibuka -->
    <a href="{{ route('guru.presensi.show', [
    'id' => $item['id_presensi'],
    'id_kelas' => $item['id_kelas']
]) }}" class="mt-5 w-full bg-white text-slate-800 py-3 rounded-2xl text-[10px] font-black uppercase transition-all flex items-center justify-center gap-2 no-underline shadow-sm border-2 border-gray-100 hover:bg-[#004aad] hover:text-white hover:border-[#004aad] group/btn">
        Buka Presensi <i class="fa-solid fa-arrow-right-to-bracket text-[10px] transition-transform group-hover/btn:translate-x-1"></i>
    </a>
@else
    <!-- Tombol Non-Aktif / Disable jika Sesi Belum Dibuka -->
    <button type="button" disabled class="mt-5 w-full bg-slate-100 text-slate-400 py-3 rounded-2xl text-[10px] font-black uppercase flex items-center justify-center gap-2 border-2 border-slate-200 cursor-not-allowed">
        Sesi Belum Dibuka <i class="fa-solid fa-lock text-[10px]"></i>
    </button>
@endif
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-10 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-gray-200">
                        <i class="fa-solid fa-clipboard-question text-slate-300 text-4xl mb-3"></i>
                        <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">Belum ada sesi presensi yang dibuat hari ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- SECTION 2: GRID BAWAH (PENGUMUMAN & JADWAL) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            {{-- Bagian Pengumuman --}}
            <div class="bg-white p-2 rounded-[1rem] shadow-sm border border-gray-200">
                <div class="bg-[#004aad] text-white p-8 rounded-[1rem] h-full relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-6 h-1 bg-white/30 rounded-full"></span>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-200">Info Akademik</span>
                        </div>
                        
                        <h4 class="text-2xl font-black leading-tight mb-4">
                            {{ $pengumuman->judul ?? 'Belum Ada Pengumuman Terbaru' }}
                        </h4>
                        
                        <p class="text-blue-100/80 text-xs font-medium leading-relaxed mb-6">
                            @if($pengumuman)
                                {{ Str::limit(strip_tags($pengumuman->isi), 120) }}
                            @else
                                Tetap pantau halaman dashboard ini untuk mendapatkan informasi terbaru seputar kegiatan akademik dan sekolah.
                            @endif
                        </p>

                        @if($pengumuman)
                        <a href="{{ route('guru.pengumuman.show', $pengumuman->id_pengumuman) }}" class="inline-flex items-center gap-3 px-6 py-3 bg-white text-[#004aad] rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-50 transition-all no-underline shadow-xl">
                            Baca Selengkapnya <i class="fa-solid fa-chevron-right"></i>
                        </a>
                        @endif
                    </div>
                    <i class="fa-solid fa-bullhorn absolute -right-6 -bottom-6 text-9xl text-white/10 -rotate-12 group-hover:scale-110 transition-transform duration-700"></i>
                </div>
            </div>

            {{-- Bagian Jadwal --}}
            <div class="bg-white p-6 rounded-[1rem] shadow-sm border border-gray-200">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Jadwal Mengajar</h3>
                        <p class="text-lg font-black text-slate-800 uppercase">Hari Ini</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center border border-blue-100">
                        <i class="fa-solid fa-calendar-day text-[#004aad]"></i>
                    </div>
                </div>
                
                <div class="space-y-4">
                    @forelse($jadwalHariIni as $j)
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-1xl border border-gray-100 group hover:bg-white hover:border-[#004aad]/20 hover:shadow-md transition-all">
                        
                        <div class="h-8 w-[1px] bg-gray-200"></div>
                        <div class="flex-grow">
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-tight">
                                {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                            </span>
                            <p class="font-black text-slate-800 uppercase tracking-tight leading-tight">{{ $j->kelas->nama_kelas }}</p>
                            <p class="text-[9px] font-bold text-[#004aad] uppercase mt-0.5">{{ $j->mapel->nama_mapel ?? 'Mata Pelajaran' }}</p>
                        </div>
                        <i class="fa-solid fa-circle-chevron-right text-slate-200 group-hover:text-[#004aad] transition-colors"></i>
                    </div>
                    @empty
                    <div class="py-10 text-center">
                        <img src="https://illustrations.popsy.co/slate/calendar.svg" alt="no schedule" class="w-24 mx-auto mb-4 opacity-50">
                        <p class="text-slate-400 font-black uppercase text-[10px] tracking-widest">Tidak ada jadwal mengajar hari ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Utility kustom untuk mendukung desain radius besar */
    .rounded-2xl { border-radius: 1rem !important; }
    .rounded-3xl { border-radius: 1.5rem !important; }
    .no-underline { text-decoration: none !important; }
    
    /* Smooth Scroll */
    html { scroll-behavior: smooth; }
</style>
@endsection