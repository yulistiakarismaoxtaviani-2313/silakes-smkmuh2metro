@extends('layouts.admin')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans">
    
    {{-- 1. Statistik Cards --}}
<div class="grid grid-cols-3 gap-3 md:gap-6 mb-8">
    
    {{-- Card Tahun Ajaran --}}
    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-calendar-alt text-2xl"></i>
        </div>
        <div class="text-center md:text-left w-full">
        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Tahun Ajaran</p>
         <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">

            {{ $tahunAktif->tahun_ajaran ?? 'N/A' }}

        </p>
        </div>
    </div>

        {{-- Total Siswa --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Total Siswa</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ number_format($totalSiswa, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Total Kelas --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-school text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Total Kelas</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $totalKelas }}</p>
            </div>
        </div>
    </div>

    {{-- 2. Main Container --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        
        {{-- Header Area & Filters --}}
<div class="p-6 border-b border-gray-300 bg-white">
    <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3 w-full lg:w-auto">
            <div class="w-2 h-6 bg-[#004aad] rounded-full"></div>
            <h2 class="font-bold text-gray-800 uppercase tracking-widest text-sm">Rekap Presensi Siswa</h2>
        </div>

        <form action="{{ route('admin.rekap.index') }}" method="GET" class="grid grid-cols-2 lg:flex w-full gap-3 lg:w-auto items-center">
            {{-- Mempertahankan filter Tahun Ajaran dari atas --}}
            <input type="hidden" name="tahun_ajaran" value="{{ $selectedTA }}">

            {{-- Search Bar --}}
            <div class="relative w-full">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari Kelas / Wali Kelas..." 
                    class="w-full pl-4 pr-10 py-2.5 text-[11px] font-reguler capitalize border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#004aad] outline-none transition-all">
                <button type="submit" class="absolute right-2 md:right-3 top-[42%] md:top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#004aad]">
                    <i class="fas fa-search text-[10px] md:text-base"></i>
                </button>
            </div>

            {{-- Filter Group (Tingkat & Semester) --}}
            {{-- Menggunakan flex w-full agar berbagi ruang sama rata di HP --}}
            <div class="contents">
                <select name="tingkat" onchange="this.form.submit()" 
                    class="w-full lg:w-auto px-4 py-2.5 text-[11px] font-reguler capitalize border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-100 outline-none bg-white">
                    <option value="">Semua Tingkat</option>
                    <option value="X" {{ request('tingkat') == 'X' ? 'selected' : '' }}>Tingkat X</option>
                    <option value="XI" {{ request('tingkat') == 'XI' ? 'selected' : '' }}>Tingkat XI</option>
                    <option value="XII" {{ request('tingkat') == 'XII' ? 'selected' : '' }}>Tingkat XII</option>
                </select>

                <select name="tahun_ajaran" onchange="this.form.submit()"
    class="w-full lg:w-auto px-4 py-2.5 text-[11px] font-reguler capitalize border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-100 outline-none bg-white">

    <option value="">Semua Tahun Ajaran</option>

    @foreach($listTahunAjaran as $ta)
        <option value="{{ $ta->id_tahun_ajaran }}"
            {{ request('tahun_ajaran') == $ta->id_tahun_ajaran ? 'selected' : '' }}>
            {{ $ta->tahun_ajaran }}
        </option>
    @endforeach

</select>

                <select name="semester" onchange="this.form.submit()" 
                    class="w-full lg:w-auto px-4 py-2.5 text-[11px] font-reguler capitalize border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-100 outline-none bg-white">
                    <option value="">Semua Semester</option>
                    @foreach($listSemester as $sem)
                        <option value="{{ $sem->id_semester }}" {{ $selectedSem == $sem->id_semester ? 'selected' : '' }}>
                            {{ $sem->nama_semester }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

        {{-- 3. Tabel Area --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-50 border-b-2 border-gray-300">
                    <tr class="text-slate-700 uppercase text-xs tracking-wider">
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">No</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Nama Kelas</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Wali Kelas</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Alfa </th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Sakit </th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Izin </th>
                        <th class="p-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse($dataRekap as $index => $item)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            {{ $dataRekap->firstItem() + $index }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            <span class="text-slate-900 font-reguler uppercase text-xs tracking-tight">
                                {{ $item->nama_kelas }}
                            </span>
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            <span class="text-slate-900 font-reguler text-[11px] uppercase">
                                {{ $item->guru->user->nama ?? 'BELUM DISET' }}
                            </span>
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            <span class="text-slate-900 px-3 py-1 rounded-lg font-reguler text-xs">
                                {{ $item->alpa_count }}
                            </span>
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            <span class=" text-slate-900 px-3 py-1 rounded-lg font-reguler text-xs">
                                {{ $item->sakit_count }}
                            </span>
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            <span class="text-slate-900 px-3 py-1 rounded-lg font-reguler text-xs">
                                {{ $item->izin_count }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center">
                                {{-- Tombol Detail Rekap (Dengan Teks) --}}
                                <a href="{{ route('admin.rekap.show', $item->id_kelas) }}" 
                                   class="bg-white text-[#004aad] px-4 py-2 flex items-center gap-2 rounded-xl hover:bg-[#004aad] hover:text-white transition-all shadow-sm border border-blue-100 text-xs font-semibold">
                                    <i class="fas fa-eye text-xs"></i> 
                                    <span>Detail</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-20 text-slate-400 italic text-center font-bold tracking-widest uppercase text-xs">
                            Data rekap presensi tidak ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 4. Footer & Pagination --}}
        <div class="bg-white p-4 border-t border-gray-200 rounded-b-xl">

    <div class="text-[11px] flex items-center gap-3">
    <span>Menampilkan</span>

    <div class="text-gray-500 whitespace-nowrap">
            {{ $dataRekap->firstItem() ?? 0 }} - {{ $dataRekap->lastItem() ?? 0 }}
        dari {{ $dataRekap->total() }} 
    </div>

    <div class="ml-auto custom-pagination">
        {{ $dataRekap->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>

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