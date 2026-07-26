@extends('layouts.admin')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans">

    {{-- 1. Statistik Cards (Ikon FontAwesome Biru & Gaya Clean) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 mb-8">
        {{-- Tahun Ajaran --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-calendar-alt text-2xl text-[#004aad]"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Tahun Ajaran</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $tahun_aktif->tahun_ajaran ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Total Kelas --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-school text-2xl text-[#004aad]"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Total Kelas</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $kelas->total() }} Data</p>
            </div>
        </div>

        {{-- Total Siswa --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-users text-2xl text-[#004aad]"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Total Siswa</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ number_format($total_siswa ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Total Wali Kelas --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-user-tie text-2xl text-[#004aad]"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Wali Kelas</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $total_wali ?? 0 }} Guru</p>
            </div>
        </div>
    </div>

    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-500 text-white text-xs font-bold uppercase rounded-xl shadow-md">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- 2. Main Container --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        
        {{-- Header Area --}}
        <div class="p-6 border-b border-gray-300 bg-white relative text-center">
            <h2 class="font-bold text-gray-800 uppercase tracking-widest text-[15px] md:text-base">
                Data Kelola Kelas & Wali Kelas
            </h2>
            
            <div class="mt-4 md:mt-0 md:absolute md:right-6 md:top-1/2 md:-translate-y-1/2 flex w-full justify-center md:justify-end gap-2 px-2">
                <a href="{{ route('admin.kelas.create') }}" 
                    class="bg-[#004aad] text-white px-3 py-2 rounded-lg flex items-center gap-1.5 text-[9px] md:text-xs font-bold hover:bg-blue-800 transition uppercase shadow-sm whitespace-nowrap">
                    <i class="fas fa-plus-circle"></i> Tambah Kelas
                </a>
                
                <form action="{{ route('admin.kelas.import') }}" method="POST" enctype="multipart/form-data" id="formImportKelas" class="inline">
                    @csrf
                    <input type="file" name="file_excel" id="file_excel_kelas" class="hidden" onchange="document.getElementById('formImportKelas').submit()">
                    <button type="button" onclick="document.getElementById('file_excel_kelas').click()" class="border border-gray-300 px-3 py-2 rounded-lg flex items-center gap-1.5 text-[9px] md:text-xs font-bold text-gray-500 bg-white shadow-sm hover:bg-gray-50 transition uppercase whitespace-nowrap">
                        <i class="fas fa-file-excel text-green-700"></i> Import
                    </button>
                </form>
            </div>
        </div>

        {{-- Filter Area --}}
        <div class="p-6 bg-gray-50/50 border-b border-gray-300">
            <form action="{{ route('admin.kelas.index') }}" method="GET">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full">
                    <select name="tingkat" onchange="this.form.submit()" class="bg-white border border-gray-300  text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] w-full p-2.5 font-reguler">
                        <option value="">Semua Tingkat</option>
                        <option value="X" {{ request('tingkat') == 'X' ? 'selected' : '' }}>Tingkat X</option>
                        <option value="XI" {{ request('tingkat') == 'XI' ? 'selected' : '' }}>Tingkat XI</option>
                        <option value="XII" {{ request('tingkat') == 'XII' ? 'selected' : '' }}>Tingkat XII</option>
                    </select>

                    <select name="jurusan" onchange="this.form.submit()" class="bg-white border border-gray-300  text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] w-full p-2.5 font-reguler">
                        <option value="">Semua Jurusan</option>
                        @foreach($data_jurusan as $j)
                            <option value="{{ $j->id_program_keahlian }}" {{ request('jurusan') == $j->id_program_keahlian ? 'selected' : '' }}>
                                {{ strtoupper($j->nama_program) }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-300  text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] w-full p-2.5 font-reguler">
                        <option value="">Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>

                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-[10px]"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="bg-white border border-gray-300 text-gray-600 text-[7px] md:text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] block w-full p-2.5 pl-10 font-reguler" 
                            placeholder="Cari Nama Kelas / Wali...">
                    </div>
                </div>
            </form>
        </div>

        {{-- Table Area --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border-spacing-0">
                <thead class="bg-gray-50 border-b-2 border-gray-300">
                    <tr class="text-slate-700 uppercase text-xs tracking-wider text-center">
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">No</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Nama Kelas</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Wali Kelas</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Jumlah Siswa</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Status</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 uppercase">
                    @forelse($kelas as $item)
                    <tr class="hover:bg-blue-50/30 transition-colors text-xs text-center">
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            {{ ($kelas->currentPage() - 1) * $kelas->perPage() + $loop->iteration }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            {{ $item->nama_kelas }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-left border-r border-gray-300 whitespace-nowrap">
                            <span class="font-reguler">{{ $item->guru?->user?->nama ?? 'TANPA WALI' }}</span>
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            {{ $item->siswa_count ?? 0 }} SISWA
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            @if($item->status == 'aktif')
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-lg text-[10px] font-reguler border border-green-200 capitalize">
                                    Aktif
                                </span>
                            @else
                                <span class="bg-red-100 text-red-500 px-3 py-1 rounded-lg text-[10px] font-reguler border border-red-200 capitalize">
                                    Non-Aktif
                                </span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                {{-- Tombol Lihat/Detail --}}
                                <a href="{{ route('admin.kelas.show', $item->id_kelas) }}" 
                                   class="bg-blue-50 text-[#004aad] w-9 h-9 flex items-center justify-center rounded-xl hover:bg-[#004aad] hover:text-white transition-all shadow-sm border border-blue-100" 
                                   title="Lihat Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.kelas.edit', $item->id_kelas) }}" 
                                   class="bg-orange-50 text-orange-600 w-9 h-9 flex items-center justify-center rounded-xl hover:bg-orange-500 hover:text-white transition-all shadow-sm border border-orange-100" 
                                   title="Edit Data">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.kelas.destroy', $item->id_kelas) }}" method="POST" class="inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data kelas ini?')" 
                                            class="bg-red-50 text-red-600 w-9 h-9 flex items-center justify-center rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100" 
                                            title="Hapus Data">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-20 text-gray-400 italic text-center font-medium tracking-widest uppercase">
                            <i class="fas fa-folder-open mb-3 text-4xl block opacity-20"></i>
                            Belum ada data kelas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer & Pagination --}}
        <div class="bg-white p-4 border-t border-gray-200 rounded-b-xl">

    <div class="text-[11px] flex items-center gap-3">
    <span>Menampilkan</span>

    <div class="text-gray-500 whitespace-nowrap">
                    {{ $kelas->firstItem() ?? 0 }} - {{ $kelas->lastItem() ?? 0 }}
                dari {{ $kelas->total() }}
            </div>

 <div class="ml-auto custom-pagination">
                {{ $kelas->appends(request()->query())->links() }}
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