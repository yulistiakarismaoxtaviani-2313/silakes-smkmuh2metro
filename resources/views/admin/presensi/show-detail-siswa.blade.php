@extends('layouts.admin')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans">
    
    {{-- 1. Header Info Bar (Gaya Split Layout) --}}
    <div class="grid grid-cols-3 md:grid-cols-3 gap-0 bg-white border-b-4 border-[#004aad] rounded-xl shadow-sm mb-6 overflow-hidden divide-y md:divide-y-0 md:divide-x divide-gray-100">
        {{-- Tanggal --}}
        <div class="p-4 md:p-6 flex flex-col md:flex-row items-center gap-3 md:gap-5">
            <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-calendar-day text-xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1.5">Tanggal</p>
                <p class="text-xs md:text-sm font-black text-black uppercase">{{ \Carbon\Carbon::parse($presensi->tanggal)->translatedFormat('d F Y') }}</p>
            </div>
        </div>
        {{-- Jam Pelajaran --}}
        <div class="p-4 md:p-6 flex flex-col md:flex-row items-center gap-3 md:gap-5">
            <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-clock text-xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1.5">Jam Pelajaran</p>
                <p class="text-xs md:text-sm font-black text-black uppercase">{{ $presensi->jam_pelajaran }}</p>
            </div>
        </div>
        {{-- Status Sesi --}}
        <div class="p-4 md:p-6 flex flex-col md:flex-row items-center gap-3 md:gap-5">
            <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas {{ $presensi->status_asli == 'dibuka' ? 'fa-lock-open' : 'fa-lock' }} text-xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1.5">Status Sesi</p>
                    <p class="text-xs md:text-sm  font-bold uppercase {{ $presensi->status_asli == 'dibuka' ? 'text-state-900' : 'text-black' }}">
                        {{ $presensi->status_asli }}
                    </p>
                
            </div>
        </div>
    </div>

    {{-- 2. Ringkasan Statistik Kehadiran (Warna Font Disamakan Persis) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl flex items-center gap-4 shadow-sm border border-gray-200">
            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] text-gray-500 font-black uppercase leading-tight">Total Hadir</p>
                <p class="text-xl font-black text-black">{{ $siswa->where('status', 'hadir')->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl flex items-center gap-4 shadow-sm border border-gray-200">
            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-thermometer-half text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] text-gray-500 font-black uppercase leading-tight">Total Sakit</p>
                <p class="text-xl font-black text-black">{{ $siswa->where('status', 'sakit')->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl flex items-center gap-4 shadow-sm border border-gray-200">
            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-envelope-open-text text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] text-gray-500 font-black uppercase leading-tight">Total Izin</p>
                <p class="text-xl font-black text-black">{{ $siswa->where('status', 'izin')->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl flex items-center gap-4 shadow-sm border border-gray-200">
            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-times-circle text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] text-gray-500 font-black uppercase leading-tight">Total Alfa</p>
                <p class="text-xl font-black text-black">{{ $siswa->where('status', 'alfa')->count() }}</p>
            </div>
        </div>
    </div>

    {{-- 3. Main Container Tabel --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        
        {{-- Header Area & Filter --}}
        <div class="p-6 border-b border-gray-300 bg-white">
            <div class="flex flex-row lg:flex-row justify-between items-center gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-6 bg-[#004aad] rounded-full"></div>
                    <h2 class="font-bold text-black uppercase tracking-widest text-sm">Detail Presensi: {{ $kelas->nama_kelas }}</h2>
                </div>
                <div class="flex flex-wrap gap-2">
                </div>
            </div>

            {{-- Form Filter --}}
            <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-2 md:grid-cols-2 gap-3">
                <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-300 text-state-900 text-[8px] md:text-[11px] font-reguler rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 capitalize">
                    <option value="">Semua Status Kehadiran</option>
                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="alfa" {{ request('status') == 'alfa' ? 'selected' : '' }}>Alfa</option>
                </select>

                <div class="relative w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama atau NIS..." class="bg-white border border-gray-300 text-black text-[8px] md:text-[11px] font-medium rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 capitalize">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                        <i class="fas fa-search text-[9px] md:text-sm"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Area --}}
<div class="overflow-x-auto">
    <table class="w-full text-sm border-collapse">
        <thead class="bg-gray-50 border-b-2 border-gray-300">
            <tr class="text-slate-700 uppercase text-xs tracking-wider text-center">
                <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">No</th>
                <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">NIS</th>
                <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Nama Siswa</th>
                <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Status Kehadiran</th>
                <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Keterangan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-300">
            @forelse($siswa as $index => $item)
                <tr class="hover:bg-blue-50/30 transition-colors text-state-900">
                    {{-- No --}}
                    <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                        {{ $index + 1 }}
                    </td>
                    {{-- NIS (Kolom Terpisah) --}}
                    <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                        {{ $item->nis }}
                    </td>
                    {{-- Nama Siswa --}}
                    <td class="p-3 md:p-4 text-gray-600 font-medium text-left border-r border-gray-300 whitespace-nowrap uppercase">
                        {{ $item->nama_siswa }}</p>
                    </td>
                    {{-- Status Kehadiran --}}
                    <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                        @php
                            $status = $item->status;
                            $badgeStyle = [
                                'hadir' => 'bg-green-100 text-green-600 border-green-200',
                                'sakit' => 'bg-blue-100 text-blue-600 border-blue-200',
                                'izin'  => 'bg-indigo-100 text-indigo-600 border-indigo-200',
                                'alfa'  => 'bg-red-100 text-red-600 border-red-200',
                            ][$status] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                        @endphp
                        <span class="{{ $badgeStyle }} px-4 py-1 rounded-xl text-[10px] font-reguler border capitalize inline-block">
                            {{ $status }}
                            @if($item->getRawOriginal('status') == null && $status == 'alfa')
                                <span class="text-[8px] opacity-70 block font-normal">(Belum Absen)</span>
                            @endif
                        </span>
                    </td>
                    {{-- Keterangan --}}
                    <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                        {{ $item->keterangan ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-20 text-state-900 text-center font-reguler tracking-widest capitalize text-xs">
                        Data siswa tidak ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

        {{-- Footer --}}
        <div class="bg-gray-50 p-4 border-t border-gray-300 flex justify-between items-center">
            <div class="text-[11px] text-state-900 font-reguler capitalize tracking-wider">
                Menampilkan {{ $siswa->count() }} Data Siswa
            </div>
            @if(request('status') || request('search'))
                <a href="{{ url()->current() }}" class="text-[10px] text-red-500 font-black hover:underline uppercase">RESET FILTER</a>
            @endif
        </div>
    </div>
</div>

<style>
    /* Konsistensi Rounded XL */
    .rounded-xl { border-radius: 0.75rem !important; }
    .border-gray-300 { border-color: #cbd5e1 !important; }

    /* Pagination Modern Style */
    .custom-pagination nav div:first-child { display: none !important; }
    .custom-pagination .pagination { display: flex !important; gap: 6px; margin: 0; }
    .custom-pagination .page-item { list-style: none; }
    .custom-pagination .page-link {
        display: flex; align-items: center; justify-content: center; 
        min-width: 36px; height: 36px;
        background-color: white !important; 
        border: 1px solid #cbd5e1 !important;
        color: black !important; 
        font-size: 11px; font-weight: 800; 
        border-radius: 8px !important;
        transition: all 0.2s;
    }
    .custom-pagination .page-item.active .page-link { 
        background-color: #004aad !important; 
        border-color: #004aad !important;
        color: white !important; 
    }
    .custom-pagination .page-link:hover {
        border-color: #004aad !important;
        color: #004aad !important;
    }
</style>
@endsection