@extends('layouts.admin')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans">
    
    {{-- 1. Info Bar (Gaya Split Layout yang Kamu Suka) --}}
    <div class="grid grid-cols-3 md:grid-cols-3 gap-0 bg-white border-b-4 border-[#004aad] rounded-xl shadow-sm mb-6 overflow-hidden divide-y md:divide-y-0 md:divide-x divide-gray-100">
        <div class="p-4 md:p-6 flex flex-col md:flex-row items-center gap-3 md:gap-5">
            <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-school text-xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1.5">Nama Kelas</p>
                <p class="text-xs md:text-sm font-black text-black uppercase">{{ $kelas->nama_kelas }}</p>
            </div>
        </div>
        <div class="p-4 md:p-6 flex flex-col md:flex-row items-center gap-3 md:gap-5">
            <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-user-tie text-xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1.5">Wali Kelas</p>
                <p class="text-xs md:text-sm font-black text-black uppercase">{{ $kelas->guru->user->nama ?? 'BELUM DITENTUKAN' }}</p>
            </div>
        </div>
        <div class="p-4 md:p-6 flex flex-col md:flex-row items-center gap-3 md:gap-5">
            <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1.5">Total Siswa</p>
                <p class="text-xs md:text-sm font-black text-black uppercase">{{ $siswa->count() }} SISWA</p>
            </div>
        </div>
    </div>

    {{-- 2. Ringkasan Statistik (Gaya Card yang Kamu Suka) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        {{-- Total Hadir --}}
        <div class="bg-white p-4 rounded-xl flex items-center gap-4 shadow-sm border border-gray-200">
            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] text-gray-500 font-black uppercase leading-tight">Total Hadir</p>
                <p class="text-xl font-black text-black">{{ $total_stats['hadir'] }}</p>
            </div>
        </div>

        {{-- Total Sakit --}}
        <div class="bg-white p-4 rounded-xl flex items-center gap-4 shadow-sm border border-gray-200">
            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-briefcase-medical text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] text-gray-500 font-black uppercase leading-tight">Total Sakit</p>
                <p class="text-xl font-black text-black">{{ $total_stats['sakit'] }}</p>
            </div>
        </div>

        {{-- Total Izin --}}
        <div class="bg-white p-4 rounded-xl flex items-center gap-4 shadow-sm border border-gray-200">
            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-envelope text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] text-gray-500 font-black uppercase leading-tight">Total Izin</p>
                <p class="text-xl font-black text-black">{{ $total_stats['izin'] }}</p>
            </div>
        </div>

        {{-- Total Alfa --}}
        <div class="bg-white p-4 rounded-xl flex items-center gap-4 shadow-sm border border-gray-200">
            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-user-slash text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] text-gray-500 font-black uppercase leading-tight">Total Alfa</p>
                <p class="text-xl font-black text-black">{{ $total_stats['alfa'] }}</p>
            </div>
        </div>
    </div>

    {{-- 2. Main Container Tabel --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        
        {{-- Header Area --}}
        <div class="p-6 border-b border-gray-300 bg-white">
            <div class="flex flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-6 bg-[#004aad] rounded-full"></div>
                    <h2 class="font-bold text-black uppercase tracking-widest text-[11px] md:text-sm">
                        Detail Rekap: {{ $kelas->nama_kelas }} ({{ $kelas->tahunAjaran->tahun_ajaran ?? '-' }})
                    </h2>
                </div>

               <div class="relative">
    <button type="button"
        onclick="document.getElementById('downloadMenu{{ $kelas->id_kelas }}').classList.toggle('hidden')"
        class="bg-[#004aad] text-white px-4 py-2 flex items-center gap-2 rounded-xl shadow-sm hover:bg-blue-900 transition-all text-[10px] font-black capitalize tracking-widest">

        <i class="fas fa-download"></i>
        <span>Unduh</span>
        <i class="fas fa-chevron-down text-[9px]"></i>
    </button>

    <div id="downloadMenu{{ $kelas->id_kelas }}"
        class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-xl shadow-lg z-50 overflow-hidden">

        <a href="{{ route('admin.rekap.download', $kelas->id_kelas) }}"
           class="flex items-center gap-2 px-4 py-3 text-xs text-gray-700 hover:bg-red-50">

            <i class="fas fa-file-pdf text-blue-800"></i>
            Unduh PDF
        </a>

        <a href="{{ route('admin.rekap.download.excel', $kelas->id_kelas) }}"
           class="flex items-center gap-2 px-4 py-3 text-xs text-gray-700 hover:bg-green-50">

            <i class="fas fa-file-excel text-blue-800"></i>
            Unduh Excel
        </a>

    </div>
</div>
                
            </div>
        </div>

        {{-- 3. Tabel Area --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-50 border-b-2 border-gray-300">
                    <tr class="text-slate-700 uppercase text-xs tracking-wider text-center">
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">No</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">NIS</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Nama Siswa</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Alfa</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Sakit</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Izin</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse($siswa as $index => $item)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            {{ $index + 1 }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            {{ $item->nis }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-left uppercase border-r border-gray-300 whitespace-nowrap">
                                {{ $item->user?->nama ?? 'NAMA TIDAK DITEMUKAN' }}
                      
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                        {{ $item->alfa_count }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                           {{ $item->sakit_count }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            {{ $item->izin_count }}
                        </td>
                        <td class="p-3 md:p-4 text-gray-600 font-bold text-center border-r border-gray-300 whitespace-nowrap">
                           
                                {{ $item->alfa_count + $item->sakit_count + $item->izin_count }}
                           
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-20 text-black italic text-center font-bold tracking-widest uppercase text-xs">
                            Belum ada data siswa di kelas ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>

<style>
    /* Konsistensi Desain Rounded XL */
    .rounded-xl { border-radius: 0.75rem !important; }
    .border-gray-300 { border-color: #cbd5e1 !important; }
    
    /* Memastikan teks tabel benar-benar hitam */
    table tbody td {
        color: #000000 !important;
    }
</style>
@endsection