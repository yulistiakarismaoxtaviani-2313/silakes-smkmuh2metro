@extends('layouts.admin')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans text-black">

    {{-- Header Section: Welcome Banner (Solid Style) --}}
    <div class="relative bg-white p-8 rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        {{-- Dekorasi Latar Belakang (Soft Solid) --}}
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-gray-50 rounded-full opacity-60"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                {{-- Icon Kotak Solid --}}
                <div class="w-16 h-14 md:w-14 md:h-16 bg-[#004aad] rounded-2xl flex items-center justify-center shadow-md">
                    <i class="fas fa-school text-white  text-2xl"></i>
                </div>
                
                <div>
                    <h1 class="text-1xl md:text-2xl font-bold text-black capitalize tracking-tight leading-none">
                        Monitoring Kesiswaan
                    </h1>
                    <p class="text-[8px] md:text-xs text-black mt-2 capitalize font-normal"> Sistem Kesiswaan SMK Muhammadiyah 2 Metro</p>
                </div>
            </div>

            {{-- Widget Tanggal & Jam --}}
            <div class="flex items-center gap-4 bg-gray-50 px-5 py-3 rounded-2xl border border-gray-100">
                <div class="text-right">
                    <p class="text-[9px] font-bold text-black uppercase tracking-[0.2em] leading-none mb-1">Tanggal Hari Ini</p>
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

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 mb-8">
        @php
            $stats = [
                ['label' => 'Total Siswa','val' => $totalSiswa,'icon' => 'fa-users',],
                ['label' => 'Total Guru','val' => $totalGuru,'icon' => 'fa-chalkboard-teacher',],
                ['label' => 'Total Kelas','val' => $totalKelas,'icon' => 'fa-door-open',],
                ['label' => 'Program Keahlian','val' => $totalProdi,'icon' => 'fa-graduation-cap',],
            ];
        @endphp

        @foreach($stats as $st)
        <div class="bg-white p-5 rounded-lg shadow-sm border-b-4 border-[#004aad] flex items-center justify-between group hover:translate-y-[-4px] transition-all duration-300">
            <div>
                <p class="text-[10px] text-black uppercase tracking-widest font-semibold">
                    {{ $st['label'] }}
                </p>
                <h3 class="text-2xl font-bold text-black mt-1">
                    {{ number_format($st['val'], 0, ',', '.') }}
                </h3>
            </div>
            <div class="text-[#004aad] text-xl w-12 h-12 flex items-center justify-center bg-blue-50 rounded-xl group-hover:bg-[#004aad] group-hover:text-white transition-all">
                <i class="fas {{ $st['icon'] }}"></i>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Split Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
    @if($role == 'admin_presensi')
        {{-- Tabel Siswa Perlu Tindakan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h2 class="text-[11px] md:text-xs font-bold text-black uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-list-ol text-[#004aad]"></i>Siswa Perlu Tindakan (Alfa Terbanyak)
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse text-center">
                    <thead>
                        <tr class="bg-white text-black uppercase text-[9px] tracking-[0.2em] border-b border-gray-100">
                            <th class="p-4 font-bold">No</th>
                            <th class="p-4 font-bold text-left">Nama Siswa</th>
                            <th class="p-4 font-bold">Kelas</th>
                            <th class="p-4 font-bold">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($siswaBermasalah as $index => $s)
                        @php
                            $nama = is_object($s) ? ($s->nama ?? '') : ($s['nama'] ?? '');
                            $nis = is_object($s) ? ($s->nis ?? '') : ($s['nis'] ?? '');
                            $kelas = is_object($s) ? ($s->kelas ?? ($s->nama_kelas ?? '-')) : ($s['kelas'] ?? ($s['nama_kelas'] ?? '-'));
                            $alfa = is_object($s) ? ($s->jumlah_alfa ?? ($s->alfa ?? 0)) : ($s['jumlah_alfa'] ?? ($s['alfa'] ?? 0));
                        @endphp
                        <tr class="hover:bg-blue-50/20 transition-colors">
                            <td class="p-4 text-[11px] font-medium text-black">{{ $index + 1 }}</td>
                            <td class="p-4 text-left">
                                <p class="font-bold text-black uppercase text-[11px] leading-tight">{{ $nama }}</p>
                                <p class="text-[9px] text-black font-normal uppercase tracking-tighter mt-1">{{ $nis }}</p>
                            </td>
                            <td class="p-4">
                                <span class="bg-gray-100 text-black px-2.5 py-1 rounded-md font-bold text-[10px] border border-gray-200 uppercase">
                                    {{ $kelas }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="bg-gray-100 text-black px-3 py-1 rounded-full font-bold text-[10px] border border-gray-200 uppercase">
                                    {{ $alfa }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-16 text-center text-black italic text-[10px] uppercase tracking-widest font-normal">
                                Data ketidakhadiran belum tersedia.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Visual Monitoring Bar --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-50">
                <div>
                    <h2 class="text-xs font-bold text-black uppercase tracking-widest mb-1">Visual Monitoring</h2>
                </div>
                <div class="text-[#004aad] bg-blue-50 p-3 rounded-xl border border-blue-100">
                    <i class="fas fa-chart-bar text-xl"></i>
                </div>
            </div>
            
            <div class="space-y-7">
                @forelse($siswaBermasalah as $index => $item)
                @php
                    $namaItem = is_object($item) ? ($item->nama ?? '') : ($item['nama'] ?? '');
                    $kelasItem = is_object($item) ? ($item->kelas ?? ($item->nama_kelas ?? '-')) : ($item['kelas'] ?? ($item['nama_kelas'] ?? '-'));
                    $alfaItem = is_object($item) ? ($item->jumlah_alfa ?? ($item->alfa ?? 0)) : ($item['jumlah_alfa'] ?? ($item['alfa'] ?? 0));
                    $percent = min(($alfaItem / 20) * 100, 100); 
                @endphp
                <div>
                    <div class="flex justify-between text-[10px] font-medium uppercase mb-2">
                        <div class="truncate pr-4">
                            <span class="text-black font-bold block leading-tight">{{ $namaItem }}</span>
                            <span class="text-black text-[9px] tracking-tight font-normal mt-0.5 block">{{ $kelasItem }}</span>
                        </div>
                        <span class="text-black font-bold tracking-tighter self-end">{{ $alfaItem }} / 20</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-[#004aad] h-full rounded-full transition-all duration-1000 ease-out" 
                             style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                @empty
                <div class="p-16 text-center text-black italic text-[10px] uppercase tracking-widest font-normal">
                    Data ketidakhadiran belum tersedia.
                </div>
                @endforelse
            </div>
        </div>
        @endif


            </div>
    
            @if($role == 'admin_prestasi')
    {{-- PRESTASI SISWA TERBARU --}}
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h2 class="text-xs font-bold text-black uppercase tracking-widest">
                Prestasi Siswa Terbaru
            </h2>

            <a href="{{ route('admin.prestasi.index') }}"
               class="text-[10px] font-bold text-[#004aad] uppercase hover:underline">
                Lihat Semua →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse text-center">
                <thead>
                    <tr class="bg-white text-black uppercase text-[9px] tracking-[0.2em] border-b border-gray-100">
                        <th class="p-4 font-bold">Tanggal</th>
                        <th class="p-4 font-bold text-left">Nama Siswa</th>
                        <th class="p-4 font-bold text-left">Prestasi</th>
                        <th class="p-4 font-bold">Tingkat</th>
                        <th class="p-4 font-bold">Peringkat</th>
                        <th class="p-4 font-bold">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($prestasiTerbaru as $prestasi)

                    <tr class="hover:bg-blue-50/20 transition-colors">

                        <td class="p-4 text-[11px] whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($prestasi->created_at)->translatedFormat('d M Y') }}
                        </td>

                        <td class="p-4 text-left">
                            <p class="font-bold text-black uppercase text-[11px]">
                                {{ $prestasi->siswa->user->nama ?? '-' }}
                            </p>

                            <p class="text-[9px] text-gray-500 uppercase mt-1">
                                {{ $prestasi->siswa->kelas->nama_kelas ?? '-' }}
                            </p>
                        </td>

                        <td class="p-4 text-left">
                            <p class="font-bold text-black uppercase text-[11px]">
                                {{ $prestasi->nama_lomba }}
                            </p>

                            <p class="text-[9px] text-gray-500 uppercase mt-1">
                                {{ $prestasi->cabang_lomba }}
                            </p>
                        </td>

                        <td class="p-4">
                            <span class="bg-gray-100 border border-gray-200 rounded-md px-3 py-1 text-[10px] font-bold uppercase">
                                {{ $prestasi->tingkat }}
                            </span>
                        </td>

                        <td class="p-4 font-bold text-[11px] uppercase">
                            {{ $prestasi->peringkat }}
                        </td>

                        <td class="p-4">
    <span class="bg-amber-50 text-amber-700 border border-amber-100 px-3 py-1 rounded-md text-[10px] font-bold uppercase">
        Proses
    </span>
</td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" class="p-16 text-center italic text-[10px] uppercase tracking-widest">
                            Belum ada data prestasi.
                        </td>
                    </tr>

                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

<style>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    .font-sans { font-family: 'Poppins', sans-serif !important; }
</style>
@endsection