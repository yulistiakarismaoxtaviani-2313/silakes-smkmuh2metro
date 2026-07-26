@extends('layouts.admin')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans">
    
    {{-- 1. Statistik Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-6 mb-8">
        {{-- Total Jenis Ujian --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-file-signature text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Total Kategori</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $jenis_ujian->total() }}</p>
            </div>
        </div>

        {{-- Info Box --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-info-circle text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Informasi</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">Gunakan jenis ini untuk kategori Jenis Ujian</p>
            </div>
        </div>

        {{-- Shortcut Tambah --}}
        <a href="{{ route('admin.jenis-ujian.create') }}"  class="col-span-2 md:col-span-1 group bg-[#004aad] p-6 rounded-xl shadow-md flex items-center justify-between gap-5 hover:bg-blue-800 transition-all cursor-pointer text-decoration-none">
            <div class="flex items-center gap-5">
                <div class="bg-white/20 w-14 h-14 rounded-xl flex items-center justify-center text-white shadow-sm">
                    <i class="fas fa-plus text-2xl"></i>
                </div>
                <div>
                    <p class="text-white font-black uppercase tracking-widest text-sm">Tambah Data</p>
                    <p class="text-blue-200 text-[10px] uppercase">Buat Kategori Ujian Baru</p>
                </div>
            </div>
            <i class="fas fa-chevron-right text-white/50 group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    {{-- 2. Success Message --}}
    @if(session('success'))
        <div class="bg-emerald-500 text-white px-6 py-4 rounded-xl mb-6 text-xs font-bold uppercase tracking-widest shadow-lg flex items-center gap-3">
            <i class="fas fa-check-double text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- 3. Main Table Container --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        <div class="p-6 border-b border-gray-300 bg-white flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-2 h-6 bg-[#004aad] rounded-full"></div>
                <h2 class="font-bold text-gray-800 uppercase tracking-widest text-sm">Manajemen Jenis Ujian</h2>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border-spacing-0">
                <thead class="bg-gray-50 border-b-2 border-gray-300">
                    <tr class="text-slate-700 uppercase text-xs tracking-wider">
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300 w-15">No</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Nama Jenis Ujian</th>
                        <th class="p-4 font-bold text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse($jenis_ujian as $item)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-4 text-center font-normal text-slate-800 border-r border-gray-300">
                            {{ ($jenis_ujian->currentPage() - 1) * $jenis_ujian->perPage() + $loop->iteration }}
                        </td>
                        <td class="p-4 pl-6 text-left border-r border-gray-300">
                            <span class="text-slate-800 font-normal uppercase text-xs tracking-tight">
                                {{ $item->nama_ujian }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.jenis-ujian.edit', $item->id_jenis_ujian) }}" 
                                   class="bg-orange-50 text-orange-600 w-9 h-9 flex items-center justify-center rounded-xl hover:bg-orange-500 hover:text-white transition-all shadow-sm border border-orange-100"
                                   title="Edit Data">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.jenis-ujian.destroy', $item->id_jenis_ujian) }}" method="POST" id="form-hapus-{{ $item->id_jenis_ujian }}">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $item->id_jenis_ujian }}', '{{ $item->nama_ujian }}')"
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
                            Belum ada data jenis ujian tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, nama) {
        if (confirm('Apakah Anda yakin ingin menghapus Jenis Ujian "' + nama + '"? Semua data nilai terkait akan terdampak.')) {
            document.getElementById('form-hapus-' + id).submit();
        }
    }
</script>

<style>
    .rounded-xl { border-radius: 0.75rem !important; }
    .border-gray-300 { border-color: #cbd5e1 !important; }
    
    .custom-pagination nav div:first-child { display: none !important; }
    .custom-pagination .pagination { display: flex !important; gap: 6px; margin: 0; }
    .custom-pagination .page-item { list-style: none; }
    .custom-pagination .page-link {
        display: flex; align-items: center; justify-content: center; 
        min-width: 36px; height: 36px;
        background-color: white !important; 
        border: 1px solid #cbd5e1 !important;
        color: #64748b !important; 
        font-size: 12px; font-weight: 800; 
        border-radius: 8px !important;
        transition: all 0.2s;
    }
    .custom-pagination .page-item.active .page-link { 
        background-color: #004aad !important; 
        border-color: #004aad !important;
        color: white !important; 
    }
    .custom-pagination .page-link:hover {
        background-color: #f8fafc !important;
        border-color: #004aad !important;
        color: #004aad !important;
    }
</style>
@endsection