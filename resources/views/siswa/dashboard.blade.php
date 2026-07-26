@extends('layouts.siswa')

@section('content')
@php
    $total = ($hadir + $alfa + $izin + $sakit) ?: 1;
    $p_hadir = ($hadir / $total) * 100;
    $p_alfa = ($alfa / $total) * 100;
    $p_izin = ($izin / $total) * 100;
    $p_sakit = ($sakit / $total) * 100;
    $persentase = round($p_hadir, 1);
@endphp

<div class="p-6 bg-[#f1f5f9] min-h-screen font-sans text-black">
    
    {{-- Header Section: Welcome Banner (Solid Style) --}}
    <div class="relative bg-white p-8 rounded-md shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-gray-50 rounded-full opacity-60"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                {{-- Icon Kotak Solid --}}
                <div class="w-14 h-14 bg-[#004aad] rounded-md flex items-center justify-center text-white text-xl shrink-0">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-black uppercase tracking-[0.2em] leading-none mb-2">Selamat Datang Kembali</p>
                    <h1 class="text-2xl font-bold text-black capitalize tracking-tight leading-none">
                        {{ Auth::user()->nama }}
                    </h1>
                    <p class="text-xs text-black mt-2 capitalize font-normal">Di Sistem Kesiswaan SMK Muhammadiyah 2 Metro</p>
                </div>
            </div>

            {{-- Widget Tanggal & Jam --}}
            <div class="flex items-center gap-4 bg-gray-50 px-5 py-3 rounded-md border border-gray-100">
                <div class="text-right">
                    <p class="text-[9px] font-bold text-black uppercase tracking-[0.2em] leading-none mb-1">Hari Ini</p>
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
    </div>

    {{-- STATISTIK PRESENSI --}}
    <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h2 class="text-xs font-bold text-black uppercase tracking-widest">
                Statistik Presensi Bulan Ini
            </h2>

        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                {{-- KARTU PRESENSI INDIVIDU --}}
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['Hadir', $hadir, 'fa-user-check'], 
                        ['Alfa', $alfa, 'fa-user-times'], 
                        ['Izin', $izin, 'fa-envelope-open-text'], 
                        ['Sakit', $sakit, 'fa-medkit']
                    ] as $card)
                    <div class="bg-white p-5 rounded-md shadow-sm border-b-4 border-[#004aad] flex items-center justify-between border border-gray-100">
                        <div>
                            <p class="text-[10px] text-black uppercase tracking-widest font-semibold">
                                {{ $card[0] }}
                            </p>
                            <h3 class="text-2xl font-bold text-black mt-1">
                                {{ $card[1] }}
                            </h3>
                        </div>
                        <div class="text-[#004aad] text-base w-9 h-9 flex items-center justify-center bg-blue-50 rounded-sm">
                            <i class="fas {{ $card[2] }}"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                {{-- GRAFIK LINGKARAN CONIC --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-8  lg:pl-8">
                    <div class="relative">
                        <div class="w-48 h-48 rounded-full shadow-sm flex items-center justify-center border-4 border-gray-50" 
                             style="background: conic-gradient(#004aad 0% {{ $p_hadir }}%, #ef4444 {{ $p_hadir }}% {{ $p_hadir + $p_alfa }}%, #3b82f6 {{ $p_hadir + $p_alfa }}% {{ $p_hadir + $p_alfa + $p_izin }}%, #f59e0b {{ $p_hadir + $p_alfa + $p_izin }}% 100%);">
                            <div class="w-36 h-36 rounded-full bg-white flex flex-col items-center justify-center">
                                <span class="text-[10px] font-bold text-black uppercase tracking-wider">Hadir</span>
                                <h1 class="text-3xl font-black text-black mt-0.5">{{ $persentase }}%</h1>
                            </div>
                        </div>
                    </div>
<div class="legend-wrapper">
    <div class="legend-item">
        <div class="w-3 h-3 rounded-full bg-[#004aad] flex-shrink-0"></div>
        <span class="text-[10px] font-bold uppercase text-black">Hadir</span>
    </div>
    <div class="legend-item">
        <div class="w-3 h-3 rounded-full bg-red-500 flex-shrink-0"></div>
        <span class="text-[10px] font-bold uppercase text-black">Alfa</span>
    </div>
    <div class="legend-item">
        <div class="w-3 h-3 rounded-full bg-blue-500 flex-shrink-0"></div>
        <span class="text-[10px] font-bold uppercase text-black">Izin</span>
    </div>
    <div class="legend-item">
        <div class="w-3 h-3 rounded-full bg-amber-500 flex-shrink-0"></div>
        <span class="text-[10px] font-bold uppercase text-black">Sakit</span>
    </div>
</div>
                </div>
            </div>
        </div>
        <a href="{{ route('siswa.presensi.index') }}" class="block text-center py-3.5 bg-gray-50 hover:bg-blue-50/50 transition-all font-bold text-[10px] text-black uppercase tracking-widest border-t border-gray-100">
            Lihat Detail Presensi →
        </a>
    </div>

    {{-- VALIDASI PRESTASI --}}
    <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h2 class="text-xs font-bold text-black uppercase tracking-widest">
                Status Validasi Prestasi
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse text-center">
                <thead>
                    <tr class="bg-white text-black uppercase text-[9px] tracking-[0.2em] border-b border-gray-100">
                        <th class="p-4 font-bold">Tanggal</th>
                        <th class="p-4 font-bold text-left">Nama Lomba / Cabang</th>
                        <th class="p-4 font-bold">Tingkat</th>
                        <th class="p-4 font-bold">Peringkat</th>
                        <th class="p-4 font-bold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($prestasiTerbaru as $pt)
                    <tr class="hover:bg-blue-50/20 transition-colors">
                        <td class="p-4 text-[11px] font-medium text-black whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($pt->created_at)->locale('id')->translatedFormat('d M Y') }}
                        </td>
                        <td class="p-4 text-left">
                            <p class="font-bold text-black uppercase text-[11px] leading-tight">{{ $pt->nama_lomba }}</p>
                            <p class="text-[9px] text-black font-normal uppercase tracking-tighter mt-1">{{ $pt->cabang_lomba }}</p>
                        </td>
                        <td class="p-4">
                            <span class="bg-gray-100 text-black px-2.5 py-1 rounded-sm font-bold text-[10px] border border-gray-200 uppercase">
                                {{ $pt->tingkat }}
                            </span>
                        </td>
                        <td class="p-4 font-bold text-[11px] text-black uppercase">
                            {{ $pt->peringkat }}
                        </td>
                        <td class="p-4">
                            @if($pt->status_validasi == 'Disetujui')
                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 px-3 py-1 rounded-sm text-[10px] font-bold uppercase tracking-wide">Disetujui</span>
                            @elseif($pt->status_validasi == 'Pending')
                                <span class="bg-amber-50 text-amber-700 border border-amber-100 px-3 py-1 rounded-sm text-[10px] font-bold uppercase tracking-wide">Proses</span>
                            @else
                                <span class="bg-red-50 text-red-700 border border-red-100 px-3 py-1 rounded-sm text-[10px] font-bold uppercase tracking-wide">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-16 text-center text-black italic text-[10px] uppercase tracking-widest font-normal">
                            Belum ada riwayat prestasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PENGUMUMAN & JADWAL --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- PENGUMUMAN --}}
        <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 bg-gray-50">
                <h2 class="text-xs font-bold text-black uppercase tracking-widest">Pengumuman Terbaru</h2>
            </div>
            <div class="flex-1 p-6">
                @if($pengumuman)
                <div class="bg-gray-50 rounded-md overflow-hidden border border-gray-200/60">
                    <div class="bg-gray-50 px-5 py-4 text-black">
                        <h3 class="text-base font-bold mt-1 leading-tight uppercase">{{ $pengumuman->judul }}</h3>
                    </div>
                    <div class="p-5">
                        <p class="text-black text-xs leading-relaxed font-normal">{{ Str::limit($pengumuman->isi, 180) }}</p>
                    </div>
                </div>
                @else
                <div class="h-full py-12 flex items-center justify-center text-black italic text-[10px] uppercase tracking-widest font-normal">
                    Belum ada pengumuman terbaru.
                </div>
                @endif
            </div>
            <a href="{{ route('siswa.informasi.pengumuman.index') }}" class="p-4 border-t border-gray-100 text-[10px] text-black font-bold uppercase tracking-widest hover:bg-gray-50 transition duration-150 block text-center">
                Lihat Semua Pengumuman →
            </a>
        </div>

        {{-- JADWAL HARI INI --}}
        <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 bg-gray-50">
                <h2 class="text-xs font-bold text-black uppercase tracking-widest">Jadwal Hari Ini</h2>
            </div>
            
            <div class="flex-1 p-6 space-y-3">
                @forelse($jadwalHariIni as $jadwal)
                <div class="bg-gray-50 border border-gray-200/50 rounded-sm p-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-black text-xs tracking-wide uppercase">
                            @if($jadwal->jenis === 'kbm')
                                {{ $jadwal->mapel->nama_mapel ?? 'Mata Pelajaran Tidak Diketahui' }}
                            @else
                                {{ $jadwal->kegiatan_kustom ?? ucfirst($jadwal->jenis) }}
                            @endif
                        </h3>
                        <p class="text-[10px] text-black mt-1 font-medium bg-gray-200/60 px-2 py-0.5 rounded-sm w-max">
                            {{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }} WIB
                        </p>
                    </div>
                </div>
                @empty
                <div class="h-full py-12 flex items-center justify-center text-black italic text-[10px] tracking-widest font-normal">
                    Tidak ada jadwal pelajaran hari ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    .font-sans { font-family: 'Poppins', sans-serif !important; }

    /* --- KUNCI LEGENDA (Grid Statis) --- */
    /* Kita gunakan .legend-wrapper yang sesuai dengan class HTML Anda */
    .legend-wrapper {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important; /* Paksa 2 kolom rata */
        gap: 10px !important;
        width: 100% !important;
        max-width: 200px !important; /* Batasi agar rapi di tengah */
        margin-top: 10px !important;
        justify-items: start; /* Mengunci item di posisi kiri kolom grid */
    }

    .legend-item {
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        font-size: 10px !important;
        white-space: nowrap; /* Mencegah teks turun baris */
    }

    /* --- PERBAIKAN TAMPILAN HP --- */
   @media (max-width: 768px) {
    .p-6 { padding: 1rem !important; }
    
    /* Membuat statistik lebih proporsional di HP */
    .grid-cols-2 { gap: 0.75rem !important; }
    
    /* Penyesuaian ukuran chart agar tidak memakan ruang terlalu banyak */
    .w-48 { width: 140px !important; height: 140px !important; }
    .w-36 { width: 105px !important; height: 105px !important; }
    
   
    }
</style>
@endsection