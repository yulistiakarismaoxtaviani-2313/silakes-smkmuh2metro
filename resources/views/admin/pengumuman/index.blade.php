@extends('layouts.admin')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans">
    {{-- 1. Statistik Cards (Gaya Rounded & Clean - Identik dengan Jadwal) --}}
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

        {{-- Total Pengumuman --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-clipboard-list text-2xl text-[#004aad]"></i>
            </div>
           <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Total Pengumuman</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $totalPengumuman }} Data</p>
            </div>
        </div>

        {{-- Pengumuman Terbaru --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-bullhorn text-2xl text-[#004aad]"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Pengumuman Terbaru</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $pengumumanTerbaru }}</p>
            </div>
        </div>

        {{-- Pengumuman Hari Ini --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-clock text-2xl text-[#004aad]"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Hari Ini</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $pengumumanHariIni }} Data</p>
            </div>
        </div>
    </div>

    {{-- 2. Main Container (Tabel & Filter) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        
        {{-- Header Area: Judul & Tombol Buat --}}
        <div class="p-6 border-b border-gray-300 bg-white relative text-center">
            <h2 class="font-bold text-gray-800 uppercase tracking-widest text-[14px] md:text-base">
                Data Kelola Pengumuman Sekolah
            </h2>
            
            <div class="mt-4 md:mt-0 md:absolute right-3 md:right-6 md:top-1/2 md:-translate-y-1/2 flex justify-center">
                <a href="{{ route('admin.pengumuman.create') }}" 
                    class="bg-[#004aad] text-white px-5 py-2 rounded-lg inline-flex items-center gap-2 text-xs font-bold hover:bg-blue-800 transition uppercase shadow-sm w-max">
                    <i class="fas fa-plus-circle"></i> Buat Pengumuman Baru
                </a>
            </div>
        </div>

        {{-- Filter Area --}}
        <div class="p-6 bg-gray-50/50 border-b border-gray-300">
            <form action="{{ route('admin.pengumuman.index') }}" method="GET">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full">
                    {{-- Kategori --}}
                    <select name="kategori" onchange="this.form.submit()" class="bg-white border border-gray-300 text-slate-900 text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] w-full p-2.5 font-reguler capitalize">
                        <option value="">Semua Kategori</option>
                        <option value="Akademik" {{ request('kategori') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="Kegiatan" {{ request('kategori') == 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                        <option value="Informasi" {{ request('kategori') == 'Informasi' ? 'selected' : '' }}>Informasi</option>
                    </select>

                    {{-- Status --}}
                    <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-300 text-slate-900 text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] w-full p-2.5 font-reguler capitalize">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>

                    {{-- Urutkan --}}
                    <select name="urutkan" onchange="this.form.submit()" class="bg-white border border-gray-300 text-slate-900 text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] w-full p-2.5 font-reguler capitalize">
                        <option value="baru" {{ request('urutkan') == 'baru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="lama" {{ request('urutkan') == 'lama' ? 'selected' : '' }}>Terlama</option>
                        <option value="judul" {{ request('urutkan') == 'judul' ? 'selected' : '' }}>A-Z (Judul)</option>
                    </select>

                    {{-- Search --}}
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-[10px]"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="bg-white border border-gray-300 text-gray-600 text-[8px] md:text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] block w-full p-3 pl-8 font-reguler reguler" 
                            placeholder="Cari Judul Pengumuman...">
                    </div>
                </div>
            </form>
        </div>

        {{-- Table Area --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border-spacing-0">
                <thead class="bg-gray-50 border-b-2 border-gray-300">
                    <tr class="text-slate-700 uppercase text-xs tracking-wider text-center">
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">No</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Kategori</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Isi Pengumuman</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Tanggal Tayang</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Status</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 uppercase">
                    @forelse($pengumuman as $index => $item)
                    <tr class="hover:bg-blue-50/30 transition-colors text-xs text-center">
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            {{ ($pengumuman->currentPage() - 1) * $pengumuman->perPage() + $loop->iteration }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">  
                                {{ $item->kategori }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class=" font-reguler tracking-tight capitalize">{{ $item->judul }}</span>
                                <span class="text-[9px] text-gray-400 font-medium mt-1 capitalize">
                                    Target: {{ $item->target }} 
                                    @if($item->target == 'kelas' && $item->id_kelas)
                                        ({{ $item->kelas->nama_kelas ?? 'N/A' }})
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            {{ $item->tanggal_tayang ? \Carbon\Carbon::parse($item->tanggal_tayang)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            @if($item->status == 'aktif')
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-lg text-[10px] font-reguler border border-green-200 capitalize">
                                    Aktif
                                </span>
                            @else
                                <span class="bg-red-100 text-red-500 px-3 py-1 rounded-lg text-[10px] font-bold border border-red-200 capitalize">
                                    Non-Aktif
                                </span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                {{-- Tombol Lihat/Detail --}}
                                <a href="{{ route('admin.pengumuman.show', $item->id_pengumuman) }}" 
                                   class="bg-blue-50 text-[#004aad] w-9 h-9 flex items-center justify-center rounded-xl hover:bg-[#004aad] hover:text-white transition-all shadow-sm border border-blue-100" 
                                   title="Lihat Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.pengumuman.edit', $item->id_pengumuman) }}" 
                                   class="bg-orange-50 text-orange-600 w-9 h-9 flex items-center justify-center rounded-xl hover:bg-orange-500 hover:text-white transition-all shadow-sm border border-orange-100" 
                                   title="Edit Data">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.pengumuman.destroy', $item->id_pengumuman) }}" method="POST" class="inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data pengumuman ini?')" 
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
                            Belum ada data pengumuman.
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
                    {{ $pengumuman->firstItem() ?? 0 }} - {{ $pengumuman->lastItem() ?? 0 }}
                dari {{ $pengumuman->total() }} 
            </div>

            <div class="ml-auto custom-pagination">
                {{ $pengumuman->appends(request()->query())->links() }}
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