@extends('layouts.walikelas')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans text-black">

{{-- Statistik Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        {{-- Card Kelas --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-chalkboard text-xl md:text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Kelas</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $namaKelas }}</p>
            </div>
        </div>

        {{-- Card Total Siswa --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-users text-xl md:text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Total Siswa</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $totalSiswa }}</p>
            </div>
        </div>

        {{-- Card Laki-Laki --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-mars text-xl md:text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Laki-Laki</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $lakiLaki }}</p>
            </div>
        </div>

        {{-- Card Perempuan --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-venus text-xl md:text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Perempuan</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $perempuan }}</p>
            </div>
        </div>
    </div>

    {{-- Main Table Container --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-50 overflow-hidden">
        
        {{-- Filter & Search Section --}}
        <div class="p-6 bg-white border-b border-gray-200">
            <form action="{{ route('walikelas.siswa.index') }}" method="GET" class="flex flex-col lg:flex-row items-center gap-4">
                
                <div class="flex gap-3 w-full lg:w-auto">
                    <select name="jenis_kelamin" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2 text-xs text-black capitalize outline-none focus:border-[#004aad] w-full lg:w-44">
                        <option value="">Jenis Kelamin</option>
                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>

                    <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2 text-xs text-black capitalize outline-none focus:border-[#004aad] w-full lg:w-44">
                        <option value="">Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>

                <div class="relative flex w-full lg:flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari nama atau nis siswa..." 
                           class="border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 text-sm w-full outline-none focus:border-[#004aad] text-black">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[#004aad]">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-black uppercase text-xs tracking-wider text-center">
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">No</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">NIS</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Nama Siswa</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">JK</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">No HP</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Status</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-black">
                    @forelse($siswa as $index => $s)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $siswa->firstItem() + $index }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $s->nis }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-left border-r border-gray-300 uppercase whitespace-nowrap">{{ $s->user->nama ?? '-' }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $s->jenis_kelamin }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $s->profil->no_hp ?? '-' }}</td>
                        
                       <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            <span class="{{ $s->status == 'aktif' ? 'bg-green-100 text-green-600 border-green-200' : 'bg-red-100 text-red-600 border-red-200' }} px-3 py-1 rounded-full text-[10px] font-bold border uppercase">
                                {{ $s->status }}
                            </span>
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            <div class="flex justify-center">
                                <a href="{{ route('walikelas.siswa.show', $s->id_siswa) }}" class="bg-white text-[#004AAD] px-4 py-2 rounded-lg  hover:bg-blue-800 hover:text-white transition text-[10px] font-reguler flex items-center gap-2 shadow-sm capitalize">
                                    <i class="fas fa-eye text-xs"></i> Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-20 text-gray-400 italic text-center uppercase tracking-widest">Belum ada data siswa.</td>
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
    {{ $siswa->firstItem() ?? 0 }} - {{ $siswa->lastItem() ?? 0 }} dari {{ $siswa->total() }}
    </div>
    <div class="ml-auto custom-pagination">
    {{ $siswa->appends(request()->query())->links() }}
</div>

</div>
        </div>
    </div>
</div>

<style>
    .font-sans { font-family: 'Poppins', sans-serif; }
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