@extends('layouts.admin')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans overflow-y-auto custom-scrollbar">
    
    <div class="w-full space-y-8">
        
        {{-- SISI ATAS: Ringkasan Kelas & Wali Kelas --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden w-full">
            <div class="p-8 md:p-10 flex flex-col md:flex-row items-center md:items-start text-center md:text-left gap-6 md:gap-10 bg-white">                
                {{-- Visual Identitas Kelas --}}
                <div class="flex flex-col items-center md:items-start gap-2">
                    <div class="w-36 h-36 rounded-2xl bg-[#004AAD] flex flex-col items-center justify-center text-white shadow-md border-4 border-blue-50 p-4 text-center">
                        <span class="text-[9px] uppercase font-bold opacity-75 mb-1 tracking-wider">Kelas</span>
                        <span class="text-2xl font-black uppercase leading-none tracking-tight">{{ $ujian->kelas->nama_kelas }}</span>
                    </div>
                </div>

                <div class="flex-grow w-full space-y-6">
                    <div>
                        <dt class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Wali Kelas Utama</dt>
                        <h2 class="text-2xl md:text-3xl font-black text-slate-800 leading-tight uppercase tracking-tight">
                            {{ $ujian->kelas->guru->user->nama ?? 'Belum Ditentukan' }}
                        </h2>
                    </div>
                    
                    <div class="flex flex-wrap justify-center md:justify-start items-center gap-3 pb-4 border-b border-gray-100">
                <p class="inline-flex text-[10px] md:text-xs font-extrabold text-[#004aad] bg-blue-50 px-4 py-2 rounded-lg border border-blue-100 uppercase tracking-wider">
                            Total: {{ $ujian->details->count() }} Mata Uji
                        </p>
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-50 text-emerald-700 border-emerald-100 text-[10px] font-bold uppercase tracking-wider border">
                            Status Jadwal Ujian Aktif
                        </span>
                    </div>

                    {{-- Metadata Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-2 gap-4 pt-2">
                        <div class="p-4 bg-gray-50/50 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-xl flex items-center justify-center text-sm">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div>
                                <dt class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Tahun Ajaran</dt>
                                <dd class="text-sm font-black text-slate-700 uppercase">{{ $ujian->tahunAjaran->tahun_ajaran ?? '-' }}</dd>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-gray-50/50 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-xl flex items-center justify-center text-sm">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div>
                                <dt class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Semester</dt>
                                <dd class="text-sm font-black text-slate-700 uppercase">{{ $ujian->semester->nama_semester ?? '-' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SISI BAWAH: Rincian Jadwal Ujian --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden w-full">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white px-10">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-6 bg-[#004aad] rounded-full"></div>
                    <h3 class="font-black text-xs uppercase tracking-[0.2em] text-slate-800">
                        Jadwal Ujian Kelas {{ $ujian->kelas->nama_kelas }} 
                        Semester {{ $ujian->semester->nama_semester ?? '-' }} 
                        Tahun Ajaran {{ $ujian->tahunAjaran->tahun_ajaran ?? '-' }}
                    </h3>
                </div>
            </div>

            <div class="p-8 md:p-10 bg-gray-50/30">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php 
                        $hariUrut = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; 
                        $detailsGrouped = $ujian->details->groupBy(fn($item) => ucwords(strtolower($item->hari)));
                    @endphp
                    
                    @foreach($hariUrut as $h)
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col h-full">
                            {{-- Header Hari --}}
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 text-center">
                                <span class="text-slate-800 font-black uppercase text-[10px] tracking-[0.2em]">{{ $h }}</span>
                            </div>
                            
                            {{-- Isi Jadwal --}}
                            <div class="flex-grow">
                                @if(isset($detailsGrouped[$h]) && $detailsGrouped[$h]->count() > 0)
                                    <table class="w-full">
                                        @foreach($detailsGrouped[$h]->sortBy('jam_mulai') as $item)
                                            <tr class="border-b border-gray-50 last:border-0">
                                                <td class="pl-6 py-4 w-24 text-[9px] font-bold text-slate-400 font-mono leading-tight">
                                                    {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}
                                                    <span class="block text-gray-300">-</span>{{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                                                </td>
                                               <td class="pr-6 py-4">
    {{-- Nama Mapel --}}
    <div class="text-[11px] font-black text-slate-800 uppercase leading-tight mb-2">
        {{ $item->mapel->nama_mapel }}
    </div>

    {{-- Detail Pengawas & Ruangan --}}
    <div class="flex flex-col gap-1 items-start">
        {{-- Nama Pengawas --}}
        <div class="text-[10px] font-bold text-gray-400 uppercase">
            {{ $item->pengawas->user->nama ?? '-' }}
        </div>
        
        {{-- Ruangan dengan Background --}}
        <div class="text-[9px] font-reguler text-gray-700 bg-gray-100 px-2 py-0.5 rounded uppercase tracking-wider">
            Ruangan {{ $item->ruangan }}
        </div>
    </div>
</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @else
                                    <div class="p-6 text-center text-gray-300 italic text-[10px] uppercase tracking-widest h-full flex items-center justify-center">
                                        Tidak ada jadwal
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-2xl { border-radius: 1rem !important; }
    .rounded-3xl { border-radius: 1.5rem !important; }
    .rounded-xl { border-radius: 0.75rem !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #004AAD; border-radius: 10px; }
</style>
@endsection