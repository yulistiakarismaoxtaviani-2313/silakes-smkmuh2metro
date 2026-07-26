@extends('layouts.admin')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans">
    
    {{-- 1. Statistik Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-6 mb-8">
        {{-- Total Mapel --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-book text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Total Mata Pelajaran</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $mapel->total() }}</p>
            </div>
        </div>

        {{-- Tahun Ajaran Aktif (Hardcoded sesuai referensi Anda) --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-calendar-check text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Tahun Ajaran</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $tahun_aktif->tahun_ajaran ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Shortcut Tambah --}}
        <a href="{{ route('admin.mapel.create') }}" class="col-span-2 md:col-span-1 group bg-[#004aad] p-6 rounded-xl shadow-md flex items-center justify-between gap-5 hover:bg-blue-800 transition-all cursor-pointer text-decoration-none">
            <div class="flex items-center gap-5">
                <div class="bg-white/20 w-14 h-14 rounded-xl flex items-center justify-center text-white shadow-sm">
                    <i class="fas fa-plus text-2xl"></i>
                </div>
                <div>
                    <p class="text-white font-black uppercase tracking-widest text-sm">Tambah Mapel</p>
                    <p class="text-blue-200 text-[10px] uppercase">Input Mata Pelajaran Baru</p>
                </div>
            </div>
            <i class="fas fa-chevron-right text-white/50 group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    {{-- 2. Alert Messages --}}
    @if(session('success'))
        <div class="bg-emerald-500 text-white px-6 py-4 rounded-xl mb-6 text-xs font-bold uppercase tracking-widest shadow-lg flex items-center gap-3">
            <i class="fas fa-check-double text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white px-6 py-4 rounded-xl mb-6 text-xs font-bold uppercase tracking-widest shadow-lg flex items-center gap-3">
            <i class="fas fa-exclamation-triangle text-lg"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- 3. Main Table Container --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        <div class="p-6 border-b border-gray-300 bg-white flex flex-col md:flex-row justify-between items-left md:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-2 h-6 bg-[#004aad] rounded-full"></div>
                <h2 class="font-bold text-gray-800 uppercase tracking-widest text-sm">Daftar Mata Pelajaran</h2>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border-spacing-0">
                <thead class="bg-gray-50 border-b-2 border-gray-300">
                    <tr class="text-slate-700 uppercase text-xs tracking-wider">
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300 w-15">No</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Nama Mata Pelajaran</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse($mapel as $item)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-4 text-center font-normal text-slate-800 border-r border-gray-300">
                            {{ ($mapel->currentPage() - 1) * $mapel->perPage() + $loop->iteration }}
                        </td>
                        <td class="p-4 pl-6 text-left border-r border-gray-300">
                            <span class="text-slate-900 font-reguler uppercase text-xs tracking-tight">
                                {{ $item->nama_mapel }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.mapel.edit', $item->id_mapel) }}" 
                                   class="bg-orange-50 text-orange-600 w-9 h-9 flex items-center justify-center rounded-xl hover:bg-orange-500 hover:text-white transition-all shadow-sm border border-orange-100"
                                   title="Edit Data">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.mapel.destroy', $item->id_mapel) }}" method="POST" id="form-hapus-{{ $item->id_mapel }}">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $item->id_mapel }}', '{{ $item->nama_mapel }}')"
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
                        <td colspan="3" class="p-20 text-slate-400 italic text-center font-bold tracking-widest uppercase text-xs">
                            Belum ada data mata pelajaran tersedia
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
            {{ $mapel->firstItem() ?? 0 }} - {{ $mapel->lastItem() ?? 0 }}
            dari
            {{ $mapel->total() }}
        </div>

        <div class="ml-auto custom-pagination">
            {{ $mapel->appends(request()->query())->links() }}
        

        </div>
    </div>
</div>

<script>
    function confirmDelete(id, nama) {
        if (confirm('Apakah Anda yakin ingin menghapus Mata Pelajaran "' + nama + '"? Menghapus Mapel akan berdampak pada data nilai terkait.')) {
            document.getElementById('form-hapus-' + id).submit();
        }
    }
</script>

<style>
    .rounded-xl {
        border-radius: 0.75rem !important;
    }

    .border-gray-300 {
        border-color: #cbd5e1 !important;
    }

    /* ===========================
       Pagination
    =========================== */

    .custom-pagination .pagination {
        display: flex !important;
        gap: 6px;
        margin: 0;
    }

    .custom-pagination .page-item {
        list-style: none;
    }

    .custom-pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        background: white !important;
        border: 1px solid #cbd5e1 !important;
        color: #64748b !important;
        font-size: 12px;
        font-weight: 800;
        border-radius: 8px !important;
        transition: .2s;
    }

    .custom-pagination .page-item.active .page-link {
        background: #004aad !important;
        border-color: #004aad !important;
        color: white !important;
    }

    .custom-pagination .page-link:hover {
        background: #f8fafc !important;
        border-color: #004aad !important;
        color: #004aad !important;
    }

    @media (min-width:768px){

        /* Hilangkan tampilan mobile bawaan Laravel */
        .custom-pagination nav > div:first-child{
            display:none !important;
        }

        /* Tampilkan tampilan desktop */
        .custom-pagination nav > div:last-child{
            display:flex !important;
            justify-content:space-between;
            align-items:center;
            width:100%;
        }
    }
</style>
@endsection