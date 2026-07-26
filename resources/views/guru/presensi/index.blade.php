@extends(
    request()->routeIs('walikelas.*')
        ? 'layouts.walikelas'
        : 'layouts.guru'
)

@section('content')
<div class="flex-1 bg-[#F8FAFC] p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    
    {{-- 1. Header Halaman --}}
    <div class="flex items-center gap-3 mb-6 md:mb-8">
        <div class="w-2 h-8 bg-[#004aad] rounded-full"></div>
        <h1 class="text-lg md:text-2xl font-black text-slate-800 uppercase tracking-tight">Presensi Kelas Hari Ini</h1>
    </div>


    @if(session('info'))
    <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-blue-700">
        {{ session('info') }}
    </div>
@endif

    {{-- 2. Kontainer Utama --}}
    <div class="space-y-6">
        @forelse($classes->groupBy('name') as $className => $sessions)
        <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-200 overflow-hidden w-full transition-all hover:shadow-md">
            
            {{-- Header Grup: Nama Kelas --}}
<div class="p-4 md:p-8 border-b border-gray-100 bg-gray-50/30">
    {{-- Kita buat div pembungkus utama menjadi flex-row (sejajar) --}}
    <div class="flex justify-between items-start gap-4">
        
        {{-- Kiri: Icon, Nama Kelas, Info --}}
        <div class="flex items-center gap-3 md:gap-5">
            <div class="bg-blue-50 w-12 h-12 md:w-16 md:h-16 rounded-xl md:rounded-2xl flex items-center justify-center border border-blue-100 shrink-0">
                <i class="fa-solid fa-school text-[#004aad] text-lg md:text-2xl"></i>
            </div>
            <div>
                <h2 class="text-lg md:text-2xl font-black text-slate-800 uppercase tracking-tight mb-0.5">{{ $className }}</h2>
                <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2 py-0.5 md:px-3 md:py-1 bg-white border border-gray-200 rounded-lg text-[9px] font-bold text-slate-500 uppercase tracking-wider">
                                    <i class="fa-regular fa-calendar-days text-[#004aad]"></i>
                                    @php
                                        $rawDate = $sessions->first()['date'];
                                        $isFormatted = preg_match('/[a-zA-Z]/', $rawDate);
                                    @endphp
                                    {{ $isFormatted ? $rawDate : \Carbon\Carbon::parse($rawDate)->locale('id')->translatedFormat('l, d F Y') }}
                                </span>
                                <span class="px-2 py-0.5 md:px-3 md:py-1 bg-white border border-gray-200 rounded-lg text-[9px] font-bold text-slate-500 uppercase tracking-wider">
                                    <i class="fa-solid fa-users text-[#004aad]"></i>
                                    {{ $sessions->first()['siswa'] }} Siswa
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-3  md:px-5 md:py-2 bg-[#004aad] rounded-lg md:rounded-xl shadow-lg shrink-0">
                        <span class="text-[9px] font-black text-white uppercase tracking-[0.1em]">{{ $sessions->count() }} Sesi</span>
                    </div>
                </div>
            </div>

            {{-- Tabel Sesi --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
    <th class="w-40 px-6 py-5 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">
        Sesi
    </th>

    <th class="w-52 px-6 py-5 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">
        Waktu Presensi
    </th>

    <th class="w-40 px-6 py-5 text-center text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">
        Status
    </th>

    <th class="w-32 px-6 py-5 text-center text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">
        Aksi
    </th>
</tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($sessions as $c)
                        <tr class="hover:bg-blue-50/30 transition-colors group">

{{-- Sesi --}}
<td class="px-6 py-4 whitespace-nowrap text-left">
    <span class="text-slate-700 text-[10px]">
        {{ $c['sesi'] }}
    </span>
</td>

{{-- Jam --}}
<td class="px-6 py-4 whitespace-nowrap text-left">
    <span class="text-slate-700 text-[10px]">
        {{ $c['jam'] }}
    </span>
</td>

{{-- Status --}}
<td class="px-6 py-4 whitespace-nowrap text-center">
       @if($c['status'] == 'Berlangsung')
    <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full border border-emerald-100 text-[9px] font-black uppercase">
        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
        {{ $c['status'] }}
    </span>
@else
    <span class="inline-flex items-center px-3 py-1 bg-slate-50 text-slate-400 rounded-full border border-slate-100 text-[9px] font-black uppercase">
        {{ $c['status'] }}
    </span>
@endif
</td>

{{-- Aksi --}}
<td class="px-2 md:px-8 py-4 text-right whitespace-nowrap w-[30%]">

    @php
        $routeShow = request()->routeIs('walikelas.*')
            ? 'walikelas.presensi.mengajar.show'
            : 'guru.presensi.show';
    @endphp

    <a href="{{ route($routeShow, [
            'id' => $c['id'],
            'id_kelas' => $c['id_kelas']
        ]) }}"
       class="inline-flex items-center justify-center px-3 py-1.5 bg-[#004aad] text-white rounded-lg text-[8px] md:text-[9px] font-black uppercase transition-all hover:bg-blue-800">

        Detail <i class="fas fa-chevron-right ml-0.5 text-[7px]"></i>

    </a>
</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 p-24 text-center">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-clipboard-list text-slate-200 text-4xl"></i>
            </div>
            <p class="text-slate-400 font-bold tracking-widest uppercase text-xs">Belum ada jadwal presensi untuk hari ini</p>
        </div>
        @endforelse
    </div>

</div>

<style>
    .rounded-xl { border-radius: 0.75rem !important; }
    .rounded-2xl { border-radius: 1rem !important; }
    .rounded-3xl { border-radius: 1.5rem !important; }
    
    .font-sans { font-family: 'Poppins', sans-serif; }
    
    /* Menghilangkan garis bawah pada tautan */
    .no-underline { text-decoration: none !important; }
</style>
@endsection