@extends('layouts.walikelas')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans text-black">
    
    {{-- Header Section: Welcome Banner (Solid Style) --}}
    <div class="relative bg-white p-8 rounded-md shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-gray-50 rounded-full opacity-60"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-[#004aad] rounded-md flex items-center justify-center text-white text-xl shrink-0">
                    <i class="fas fa-user-tie text-white text-2xl"></i>
                </div>
                
                <div>
                    <h1 class="text-2xl font-semibold text-black capitalize tracking-tight leading-none">
                        Selamat Datang <span class="text-[#004aad] uppercase font-bold">{{ Auth::user()->nama }}, </span>Wali Kelas {{ $namaKelas }}!
                    </h1>
                </div>
            </div>

            {{-- Widget Tanggal & Jam --}}
            <div class="flex items-center gap-4 bg-gray-50 px-5 py-3 rounded-2xl border border-gray-100">
                <div class="text-right">
                    <p class="text-[9px] font-bold text-black uppercase tracking-[0.2em] leading-none mb-1">Update Terkini</p>
                    <p class="text-xs font-semibold text-black uppercase">
                        {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div class="w-[1px] h-8 bg-gray-200"></div>
                <div class="text-[#004aad]">
                    <i class="far fa-clock text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 mb-8">
        @php
            $cards = [
                ['label' => 'Total Siswa', 'value' => $totalSiswa, 'icon' => 'fa-users'],
                ['label' => 'Hadir Hari Ini', 'value' => $stats['hadir'], 'icon' => 'fa-user-check'],
                ['label' => 'Izin & Sakit', 'value' => $stats['izin_sakit'], 'icon' => 'fa-envelope-open-text'],
                ['label' => 'Butuh Atensi', 'value' => $siswaBermasalah->count(), 'icon' => 'fa-exclamation-triangle']
            ];
        @endphp

        @foreach($cards as $card)
        <div class="bg-white p-5 rounded-xl shadow-sm border-b-4 border-[#004aad] flex items-center justify-between group hover:translate-y-[-4px] transition-all duration-300">
            <div>
                <p class="text-[10px] text-black uppercase tracking-widest font-semibold">
                    {{ $card['label'] }}
                </p>
                <h3 class="text-2xl font-bold text-black mt-1">
                    {{ $card['value'] }}
                </h3>
            </div>
            <div class="text-[#004aad] text-xl w-12 h-12 flex items-center justify-center bg-blue-50 rounded-xl group-hover:bg-[#004aad] group-hover:text-white transition-all">
                <i class="fas {{ $card['icon'] }}"></i>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Ringkasan Akumulasi Alfa --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        {{-- Tabel Siswa --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h2 class="text-xs font-bold text-black uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-list-ol text-[#004aad]"></i>
                    Daftar Siswa Alfa Terbanyak
                </h2>
                <a href="#" class="text-[9px] font-bold text-black uppercase hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse text-center">
                    <thead>
                        <tr class="bg-white text-black uppercase text-[9px] tracking-[0.2em] border-b border-gray-100">
                            <th class="p-4 font-bold">No</th>
                            <th class="p-4 font-bold text-left">Nama Siswa</th>
                            <th class="p-4 font-bold">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($siswaBermasalah as $index => $siswa)
                        <tr class="hover:bg-blue-50/20 transition-colors">
                            <td class="p-4 text-[11px] font-medium text-black">{{ $index + 1 }}</td>
                            <td class="p-4 text-left">
                                <p class="font-bold text-black uppercase text-[11px] leading-tight">{{ $siswa->nama }}</p>
                                <p class="text-[9px] text-black font-normal uppercase tracking-tighter">{{ $siswa->nis }}</p>
                            </td>
                            <td class="p-4">
                                <span class="bg-gray-100 text-black px-3 py-1 rounded-full font-bold text-[10px] border border-gray-200 uppercase">
                                    {{ $siswa->jumlah_alfa }}
                                </span>
                            </td>
                            <td class="p-4">
                               
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-16 text-center text-black italic text-[10px] uppercase tracking-widest font-normal">
                                Data ketidakhadiran belum tersedia.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Visual Monitoring Bar --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-50">
                <div>
                    <h2 class="text-xs font-bold text-black uppercase tracking-widest mb-1">Visual Monitoring</h2>
                </div>
                <div class="text-[#004aad] bg-blue-50 p-3 rounded-xl border border-blue-100">
                    <i class="fas fa-chart-bar text-xl"></i>
                </div>
            </div>
            
            <div class="space-y-7">
                @foreach($siswaBermasalah as $index => $item)
                <div>
                    <div class="flex justify-between text-[10px] font-medium uppercase mb-2">
                        <span class="truncate pr-4 text-black font-medium">{{ $item->nama }}</span>
                        <span class="text-black font-bold tracking-tighter">{{ $item->jumlah_alfa }} / 20</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        @php 
                            $percent = min(($item->jumlah_alfa / 20) * 100, 100); 
                        @endphp
                        <div class="bg-[#004aad] h-full rounded-full transition-all duration-1000 ease-out" 
                             style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    .font-sans { font-family: 'Poppins', sans-serif !important; }
</style>
@endsection