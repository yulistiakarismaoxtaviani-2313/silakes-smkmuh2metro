@extends('layouts.admin')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans">
    
    {{-- 1. Statistik Cards (Gaya Modern Identik) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">

        {{-- Filter Tahun Ajaran --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-calendar-alt text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Tahun Ajaran</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">
        {{ $tahunAjaranAktif ?? 'N/A' }}
    </p>
            </div>
        </div>

        {{-- Total Prestasi --}}
         <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-trophy text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Total Prestasi</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $stats['total'] }}</p>
            </div>
        </div>

        {{-- Akademik --}}
         <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-book-reader text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Akademik</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $stats['akademik'] }}</p>
            </div>
        </div>

        {{-- Non-Akademik --}}
         <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-medal text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Non-Akademik</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $stats['non_akademik'] }}</p>
            </div>
        </div>
    </div>

    {{-- 2. Main Container --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        
        {{-- Header Area & Filters --}}
        <div class="p-6 border-b border-gray-300 bg-white">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-6 bg-[#004aad] rounded-full"></div>
                    <h2 class="font-bold text-black uppercase tracking-widest text-sm">Daftar Prestasi Siswa</h2>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <form action="{{ route('admin.prestasi.index') }}" method="GET" class="w-full flex flex-col lg:flex-row gap-3 lg:items-center">
    

    {{-- Filter Kelas & Status --}}
    <div class="flex w-full lg:w-auto gap-3">
        <select name="kelas" onchange="this.form.submit()" 
            class="w-full lg:w-auto px-4 py-2.5 text-[11px] font-reguler capitalize border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-100 outline-none bg-white text-black">
            <option value="">Semua Kelas</option>
            @foreach($daftar_kelas as $kelas)
                <option value="{{ $kelas->id_kelas }}" {{ request('kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                    {{ $kelas->nama_kelas }}
                </option>
            @endforeach
        </select>

        <select name="status" onchange="this.form.submit()" 
            class="w-full lg:w-auto px-4 py-2.5 text-[11px] font-reguler capitalize border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-100 outline-none bg-white text-black">
            <option value="">Semua Status</option>
            <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Proses</option>
            <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
    </div>

    {{-- Search --}}
<div class="flex w-full lg:flex-1 gap-3"> 

    <div class="relative flex-1 lg:min-w-[260px]">

        <input type="text" name="search" value="{{ request('search') }}"

            placeholder="Cari Nama / NIS Siswa..."

            class="w-full pl-4 pr-10 py-2.5 text-[11px] font-reguler capitalize border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#004aad] outline-none transition-all text-black">



        <button type="submit"

            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#004aad]">

            <i class="fas fa-search"></i>

        </button>

    </div>



    {{-- Tombol Unduh --}}

    <div class="relative shrink-0">

        <button type="button"

            onclick="document.getElementById('downloadMenu').classList.toggle('hidden')"

            class="bg-[#004aad] text-white px-4 py-2.5 rounded-xl flex items-center gap-2 hover:bg-blue-700 transition-all text-xs font-semibold whitespace-nowrap">

            <i class="fas fa-download"></i>

            <span>Unduh</span>

            <i class="fas fa-chevron-down text-[10px]"></i>

        </button>



        <div id="downloadMenu"

            class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-xl shadow-lg z-50 overflow-hidden">



            <a href="{{ route('admin.prestasi.rekap.pdf', request()->query()) }}"

                class="flex items-center gap-2 px-4 py-3 text-xs text-gray-700 hover:bg-red-50">

                <i class="fas fa-file-pdf text-blue-500"></i>

                Unduh PDF

            </a>



            <a href="{{ route('admin.prestasi.rekap.excel', request()->query()) }}"

                class="flex items-center gap-2 px-4 py-3 text-xs text-gray-700 hover:bg-green-50">

                <i class="fas fa-file-excel text-blue-500"></i>

                Unduh Excel

            </a>
            </div>
              </div>
</div>
</form>
            </div>
        </div>
        </div>

        {{-- 3. Tabel Area --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-50 border-b-2 border-gray-300">
                    <tr class="text-slate-700 uppercase text-xs tracking-wider">
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">No</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Nis</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Nama Siswa</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Kelas</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Nama Lomba</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Peringkat</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse($prestasi as $item)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            {{ ($prestasi->currentPage() - 1) * $prestasi->perPage() + $loop->iteration }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            {{ $item->siswa->nis ?? '-' }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-left uppercase border-r border-gray-300 whitespace-nowrap">
                                {{ $item->siswa->user->nama ?? 'NAMA TIDAK DITEMUKAN' }}
                           
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            {{ $item->siswa->kelas->nama_kelas ?? '-' }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium uppercase text-left border-r border-gray-300 whitespace-nowrap">
                                {{ $item->nama_lomba }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            <span class="text-state-900 px-3 py-1 rounded-lg font-reguler text-[10px] uppercase">
                                {{ $item->peringkat }}
                            </span>
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            @if($item->status_validasi == 'Disetujui')
                                <span class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-lg font-black text-[10px] uppercase">Disetujui</span>
                            @elseif($item->status_validasi == 'Ditolak')
                                <span class="bg-red-50 text-red-700 px-3 py-1 rounded-lg font-black text-[10px] uppercase">Ditolak</span>
                            @else
                                <span class="bg-amber-50 text-amber-700 px-3 py-1 rounded-lg font-black text-[10px] uppercase">Proses</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center">
                                {{-- Tombol Detail (Dengan Teks) --}}
                                <a href="{{ route('admin.prestasi.show', $item->id_prestasi) }}" 
                                   class="bg-white text-[#004aad] px-4 py-2 flex items-center gap-2 rounded-xl hover:bg-[#004aad] hover:text-white transition-all shadow-sm border border-blue-100 text-xs font-semibold">
                                    <i class="fas fa-eye text-xs"></i> 
                                    <span>Detail</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-20 text-black italic text-center font-bold tracking-widest uppercase text-xs">
                            Belum ada data prestasi yang ditemukan.
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
        {{ $prestasi->firstItem() ?? 0 }} - {{ $prestasi->lastItem() ?? 0 }}
    dari {{ $prestasi->total() }}
</div>

    <!-- Paginasi -->
<div class="ml-auto custom-pagination">
    {{ $prestasi->appends(request()->query())->links() }}
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