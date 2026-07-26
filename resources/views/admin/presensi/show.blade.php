@extends('layouts.admin')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans">
    
    {{-- 1. Header Info Bar (Gaya Split Layout - Disamakan Persis) --}}
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
                    <p class="text-xs md:text-sm font-black text-black uppercase {{ $presensi->status_asli == 'dibuka' ? 'text-black' : 'text-black' }}">
                    {{ $presensi->status_asli }}
                    </p>
                
            </div>
        </div>
    </div>

    {{-- 2. Ringkasan Statistik (Warna Ikon & Font Disamakan) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach(['hadir' => 'fa-check-circle', 'sakit' => 'fa-thermometer-half', 'izin' => 'fa-envelope-open-text', 'alfa' => 'fa-times-circle'] as $status => $icon)
        <div class="bg-white p-4 rounded-xl flex items-center gap-4 shadow-sm border border-gray-200">
            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas {{ $icon }} text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] text-gray-500 font-black uppercase leading-tight">Total {{ $status }}</p>
                <p class="text-xl font-black text-black">{{ $total_stats[$status] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- 3. Main Container Tabel Rekap Per Kelas --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        
        {{-- Header Area & Filter --}}
        <div class="p-6 border-b border-gray-300 bg-white">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-6 bg-[#004aad] rounded-full"></div>
                    <h2 class="font-bold text-black uppercase tracking-widest text-sm">Tabel Data Presensi Per Kelas</h2>
                </div>
            </div>

            {{-- Form Filter --}}
            <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-2 md:grid-cols-2 gap-3">
                <select name="id_kelas" onchange="this.form.submit()" class="bg-white border border-gray-300 text-black text-[11px] font-reguler rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Semua Kelas</option>
                    @foreach($daftar_kelas as $k)
                        <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>

                <div class="relative w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Kelas..." class="bg-white border border-gray-300 text-black text-[11px] font-medium rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                        <i class="fas fa-search text-[10px] md:text-sm"></i>
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
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Kelas</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Jumlah Siswa</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Hadir</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Izin</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Sakit</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Alfa</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse($rekap_kelas as $index => $rk)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                                {{ $index + 1 }}
                            </td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                                {{ $rk->nama_kelas }}</p>
                            </td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                                {{ $rk->total_siswa }}
                            </td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $rk->hadir }}</td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $rk->izin }}</td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $rk->sakit }}</td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $rk->alfa }}</td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                                <a href="{{ route('admin.presensi.kelas.detail', [$presensi->id_presensi, $rk->id_kelas]) }}" 
                                   class="bg-white text-[#004aad] px-4 py-1.5 rounded-lg shadow-sm hover:bg-[#004aad] hover:text-white transition inline-flex items-center gap-2 text-[10px] font-black capitalize">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-20 text-gray-400 text-center font-medium capitalize text-xs">
                                Data kelas tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white p-4 border-t border-gray-200 rounded-b-xl">

    <div class="text-[11px] flex items-center gap-3">
    <span>Menampilkan</span>

    <div class="text-gray-500 whitespace-nowrap">
            {{ $rekap_kelas->firstItem() ?? 0 }} - {{ $rekap_kelas->lastItem() ?? 0 }}
        dari {{ $rekap_kelas->total() }} 
    </div>

    <div class="ml-auto custom-pagination">
        {{ $rekap_kelas->appends(request()->query())->links('pagination::tailwind') }}
    </div>

</div>
    </div>
</div>

<style>
    /* ===========================
       Konsistensi UI
    =========================== */
    .rounded-xl {
        border-radius: 0.75rem !important;
    }

    .border-gray-300 {
        border-color: #cbd5e1 !important;
    }

    /* ===========================
       Pagination - Desktop
    =========================== */
    @media (min-width: 768px) {

        /* Sembunyikan layout mobile bawaan Laravel */
        .custom-pagination nav > div:first-child {
            display: none !important;
        }

        /* Tampilkan layout desktop */
        .custom-pagination nav > div:last-child {
            display: flex !important;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
    }
</style>
@endsection