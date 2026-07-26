@extends('layouts.walikelas')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans text-black">
    
    {{-- 1. STATISTIK CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        @php
            $cards = [
                ['label' => 'Total Hadir', 'val' => $summary['total_h'], 'icon' => 'fa-check-circle'],
                ['label' => 'Total Sakit', 'val' => $summary['total_s'], 'icon' => 'fa-medkit'],
                ['label' => 'Total Izin', 'val' => $summary['total_i'], 'icon' => 'fa-envelope'],
                ['label' => 'Total Alfa', 'val' => $summary['total_a'], 'icon' => 'fa-times-circle'],
            ];
        @endphp
        @foreach($cards as $c)
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">    
        <div class="text-[#004aad] text-2xl w-10 flex justify-center">
                <i class="fas {{ $c['icon'] }}"></i>
            </div>
             </div>  
            <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">{{ $c['label'] }}</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $c['val'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- 2. MAIN CONTAINER --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Header --}}
<div class="p-6 bg-white border-b border-gray-200">

    <div class="flex flex-col lg:flex-row justify-between lg:items-center gap-5">

        <div>
            <h2 class="text-[11px] md:text-sm font-bold text-black uppercase tracking-widest">
                Tabel Rekap Presensi Siswa {{ $summary['nama_kelas'] }}
            </h2>

            <div class="flex flex-wrap items-center gap-4 mt-3 text-[11px] text-gray-500 font-semibold uppercase">

                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-[#004aad]"></i>
                    <span>
                        Tahun Ajaran :
                        <strong class="text-black">
                            {{ $kelas->tahunAjaran->tahun_ajaran ?? '-' }}
                        </strong>
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fas fa-book text-[#004aad]"></i>
                    <span>
                        Semester :
                        <strong class="text-black">
                            {{ $kelas->semester->nama_semester ?? '-' }}
                        </strong>
                    </span>
                </div>

            </div>
        </div>

        <div class="flex items-center gap-3 w-full lg:w-auto">

            <form action="{{ route('walikelas.rekap.index') }}"
                  method="GET"
                  class="relative flex-1 lg:w-72">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama siswa..."
                    class="border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm w-full outline-none focus:border-[#004aad] text-black capitalize">

                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[#004aad]">
                    <i class="fas fa-search"></i>
                </div>

            </form>

            <a href="{{ route('walikelas.rekap.pdf') }}"
               class="bg-[#004aad] text-white px-4 py-2.5 rounded-lg hover:bg-blue-800 transition text-[10px] font-bold flex items-center gap-2 shadow-sm capitalize">
                <i class="fas fa-download text-xs"></i>
                Unduh
            </a>

        </div>

    </div>

</div>

        {{-- Tabel Data --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-black uppercase text-xs tracking-wider">
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">No</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">NIS</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Nama Siswa</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">JK</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Hadir</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Izin</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Sakit</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Alfa</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-black">
                    @forelse($list_siswa as $s)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $s['nis'] }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-left border-r border-gray-300 uppercase whitespace-nowrap">{{ $s['nama'] }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $s['jk'] }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $s['h'] }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $s['i'] }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $s['s'] }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $s['a'] }}</td>
                        <td class="p-3 md:p-4 text-center font-bold bg-gray-50/50 whitespace-nowrap">
                            {{ $s['h'] + $s['i'] + $s['s'] + $s['a'] }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-20 text-gray-400 italic text-center uppercase tracking-widest">Data tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        
    </div>
</div>

<style>
    .font-sans { font-family: 'Poppins', sans-serif; }
    .rounded-xl { border-radius: 0.75rem !important; }
</style>
@endsection