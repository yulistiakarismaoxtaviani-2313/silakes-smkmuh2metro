@extends('layouts.walikelas')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans text-black">
    
    {{-- 1. STATISTIK CARDS --}}
    <div class="grid grid-cols-3 md:grid-cols-6 gap-4 mb-8">
        @php
            $stats_cards = [
                ['label' => 'Total Siswa', 'val' => $stats['total'], 'icon' => 'fa-users'],
                ['label' => 'Hadir', 'val' => $stats['hadir'], 'icon' => 'fa-check-circle'],
                ['label' => 'Izin', 'val' => $stats['izin'], 'icon' => 'fa-envelope-open-text'],
                ['label' => 'Sakit', 'val' => $stats['sakit'], 'icon' => 'fa-envelope-open-text'],
                ['label' => 'Alfa', 'val' => $stats['alfa'], 'icon' => 'fa-times-circle'],
                ['label' => 'Belum Absen', 'val' => $stats['belum'], 'icon' => 'fa-clock'],
            ];
        @endphp
        @foreach($stats_cards as $c)
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
        
        {{-- Header & Search Section --}}
        <div class="p-6 bg-white border-b border-gray-200 flex flex-col lg:flex-row justify-between items-center gap-4">
            <div class="w-full lg:w-auto">
                <h2 class="text-[11px] md:text-sm text-center font-bold text-black uppercase tracking-widest">Monitoring Presensi Siswa {{ $kelas->nama_kelas }}</h2>
            </div>

            <div class="flex items-center gap-3 w-full lg:w-auto">
                <form action="{{ route('walikelas.presensi.kelas') }}" method="GET" class="relative flex-1 lg:w-72">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." 
                           class="border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm w-full outline-none focus:border-[#004aad] text-black capitalize font-normal">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-[#004aad]">
                        <i class="fas fa-search"></i>
                    </div>
                </form>
                
                <div class="bg-blue-100 text-[#004aad] px-4 py-2.5 rounded-lg text-[10px] font-bold capitalize shadow-sm">
                    Hari Ini
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
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Jam Pelajaran</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Status</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-black">
                    @forelse($data_presensi as $s)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $s->nis }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-left border-r border-gray-300 uppercase whitespace-nowrap">{{ $s->user->nama }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            @forelse($sesi_dibuka as $sesi)
                                <span class="block text-[10px] text-gray-600 font-medium mb-1 last:mb-0 uppercase whitespace-nowrap">{{ $sesi->jamPelajaran->nama_jam ?? '-' }}</span>
                            @empty
                                <span class="text-gray-300 italic text-[10px] font-normal whitespace-nowrap">Tidak ada sesi</span>
                            @endforelse
                        </td>
                        <td class="p-4 text-center border-r border-gray-200 font-normal whitespace-nowrap">
                            @php 
                                $status_list = collect($s->absen_hari_ini);
                            @endphp
                            
                            @if($status_list->isEmpty())
                                <span class="text-gray-300  text-[10px] font-normal capitalize tracking-tighter">Belum Absen</span>
                            @else
                                <div class="flex flex-col gap-1 items-center">
                                @foreach($status_list as $sesi_id => $status)
                                    <span class="text-black text-[10px] font-medium capitalize tracking-tighter whitespace-nowrap">
                                        {{ $status == 'hadir' ? '✓ Hadir' : $status }}
                                    </span>
                                @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <a href="{{ route('walikelas.presensi.show', $s->id_siswa) }}" 
                               class="bg-white text-[#004aad] px-4 py-2 rounded-lg hover:bg-white hover:text-[#004aad] transition text-[10px] font-bold uppercase flex items-center justify-center gap-2 mx-auto w-fit shadow-md">
                                <i class="fas fa-eye text-[#004aad]"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-20 text-gray-400 italic text-center uppercase tracking-widest font-normal">
                            Data siswa tidak tersedia atau pencarian tidak ditemukan.
                        </td>
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