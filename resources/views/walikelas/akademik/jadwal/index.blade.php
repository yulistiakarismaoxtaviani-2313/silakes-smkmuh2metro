@extends('layouts.walikelas')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans" x-data="{ tab: 'mengajar' }">

    {{-- 1. Statistik Cards (Gaya Rounded & Clean) --}}
     <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 mb-8">

        {{-- Card Nama Guru --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
               <i class="fas fa-chalkboard-user text-xl md:text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Nama Guru</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ Auth::user()->nama }}</p>
            </div>
        </div>

        {{-- Card Mata Pelajaran --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-book-open text-xl md:text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Mata Pelajaran</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $guru->mapel->pluck('nama_mapel')->implode(', ') ?: 'N/A' }}</p>
            </div>
        </div>

        {{-- Card Tahun Ajaran --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-calendar-alt text-xl md:text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Tahun Ajaran</p>
            <p class="text-xs md:text-base font-bold text-slate-800 capitalize leading-none">{{ $tahun_ajaran }} ({{ $semester ?? 'Ganjil' }})</p>
            </div>
        </div>

        {{-- Card Total Jam --}}
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-clock text-xl md:text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Total Mengajar</p>
                <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $total_jam }} Jam</p>
            </div>
        </div>
    </div>

    {{-- 2. Tab Switcher (Modern Rounded Style) --}}
    <div class="flex bg-white p-1 md:p-2 rounded-xl shadow-sm border border-gray-200 mb-6 md:mb-8 w-full">
        <button @click="tab = 'mengajar'" 
            :class="tab === 'mengajar' ? 'bg-[#004aad] text-white shadow-md' : 'text-gray-500 hover:bg-blue-100'"
            class="flex-1 py-2.5 md:py-3 rounded-lg text-[10px] md:text-sm font-bold uppercase transition-all duration-200 tracking-tight md:tracking-normal">
            Jadwal Mengajar
        </button>
        <button @click="tab = 'ujian'" 
            :class="tab === 'ujian' ? 'bg-[#004aad] text-white shadow-md' : 'text-gray-500 hover:bg-blue-100'"
            class="flex-1 py-2.5 md:py-3 rounded-lg text-[10px] md:text-sm font-bold uppercase transition-all duration-200 tracking-tight md:tracking-normal">
            Jadwal Pengawas Ujian
        </button>
    </div>

    {{-- 3. Main Table Container --}}
    <div class="bg-white rounded-none md:rounded-xl shadow-sm border-x-0 md:border border-gray-300 overflow-hidden w-full mt-6">
    
    {{-- Header Container --}}
    <div class="p-4 md:p-6 border-b border-gray-100 bg-white">

    {{-- Flex Container: tumpuk di HP (flex-col), sejajar di Desktop (md:flex-row) --}}
        <div class="flex flex-col md:flex-row items-center gap-4">

        <div class="order-1 md:order-2 w-full md:w-auto flex justify-end">
                <a :href="tab === 'mengajar' ? '{{ route('walikelas.jadwal.download.mengajar') }}' : '{{ route('walikelas.jadwal.download.ujian') }}'" 
                    class="bg-[#004aad] text-white px-5 py-2 rounded-lg flex items-center gap-2 text-xs font-bold hover:bg-blue-800 transition capitalize shadow-sm">
                    <i class="fas fa-download"></i> Unduh
                </a>
            </div>

        
            <div class="order-2 md:order-1 flex-1 w-full text-center md:text-center">
                <h2 class="font-bold text-gray-800 uppercase tracking-widest text-[12px] md:text-base leading-tight" 
                x-text="tab === 'mengajar' ? 'Daftar Jadwal Mengajar Mingguan' : 'Daftar Jadwal Pengawas Ujian Guru'">
            </h2>
            </div>
          </div>  
        </div>

        <div class="overflow-x-auto">
            {{-- --- TABEL MENGAJAR --- --}}
            <table x-show="tab === 'mengajar'" class="w-full text-sm border-collapse border-spacing-0">
                <thead class="bg-gray-50 border-b-2 border-gray-300">
                    <tr class="text-slate-700 uppercase text-xs tracking-wider">
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Hari</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Waktu</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Kelas</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold text-center">Mata Pelajaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse($jadwal_mengajar as $hari => $sessions)
                        @foreach($sessions as $index => $session)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            @if($index == 0)
                            <td rowspan="{{ count($sessions) }}" class="p-3 md:p-4 text-slate-800 font-bold bg-gray-50/50 text-center border-r-2 border-gray-300">
                                {{ ucwords(strtolower($hari)) }}
                            </td>
                            @endif
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                                {{ $session->jam_mulai }} - {{ $session->jam_selesai }}
                            </td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                                {{ $session->kelas->nama_kelas ?? '-' }}
                            </td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap uppercase">
                                {{ ucwords(strtolower($session->mapel->nama_mapel ?? '-')) }}
                            </td>
                        </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="4" class="p-20 text-gray-400 italic text-center font-medium tracking-widest uppercase">
                                Jadwal mengajar belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- --- TABEL UJIAN --- --}}
            <table x-show="tab === 'ujian'" x-cloak class="w-full text-sm border-collapse border-spacing-0">
                <thead class="bg-gray-50 border-b-2 border-gray-300">
                    <tr class="text-slate-700 uppercase text-xs tracking-wider">
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Tanggal</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Waktu</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Kelas</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Ruangan</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Peran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse($jadwal_ujian as $date => $exams)
                        @foreach($exams as $index => $exam)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            @if($index == 0)
                            <td rowspan="{{ count($exams) }}" class="p-3 md:p-4 text-slate-800 font-bold bg-gray-50/50 text-center border-r-2 border-gray-300 whitespace-nowrap">
                                {{ $date }}
                            </td>
                            @endif
                            <td class="p-3 md:p-4 text-slate-800 font-reguler bg-gray-50/50 text-center border-r-2 border-gray-300 whitespace-nowrap">
                                {{ $exam->jam_mulai }} - {{ $exam->jam_selesai }}
                            </td>
                            <td class="p-3 md:p-4 text-slate-800 font-reguler bg-gray-50/50 text-center border-r-2 border-gray-300 whitespace-nowrap">
                                {{ $exam->jadwalUjian->kelas->nama_kelas ?? 'N/A' }}
                            </td>
                            <td class="p-3 md:p-4 text-slate-800 font-reguler bg-gray-50/50 text-center border-r-2 border-gray-300 whitespace-nowrap">
                                    {{ $exam->ruangan ?? '-' }}
                                </span>
                            </td>
                            <td class="p-3 md:p-4 text-slate-800 font-reguler bg-gray-50/50 text-center border-r-2 border-gray-300 whitespace-nowrap">
                                {{ $exam->peran ?? 'Pengawas' }}
                            </td>
                        </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="p-20 text-gray-400 italic text-center font-medium tracking-widest uppercase">
                                Jadwal pengawas ujian belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .border-gray-300 { border-color: #cbd5e1 !important; }
    .rounded-xl { border-radius: 0.75rem !important; }
    .font-sans { font-family: 'Poppins', sans-serif; }
</style>
@endsection