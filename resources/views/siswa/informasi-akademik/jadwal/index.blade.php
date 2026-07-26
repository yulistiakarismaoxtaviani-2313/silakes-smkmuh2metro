@extends('layouts.siswa')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans" x-data="{ tab: 'pelajaran' }">

    {{-- 2. Statistik Cards (Gaya Rounded & Clean seperti referensi) --}}
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
{{-- Card Kelas --}}
    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-door-open text-xl md:text-2xl"></i>
        </div>
        <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Kelas Anda</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $stats['nama_kelas'] }}</p>
        </div>
    </div>

    {{-- Card Wali Kelas --}}
    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-chalkboard-user text-xl md:text-2xl"></i>
        </div>
        <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Wali Kelas</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $stats['wali_kelas'] }}</p>
        </div>
    </div>

    {{-- Card Tahun Ajaran --}}
    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-calendar-alt text-xl md:text-2xl"></i>
        </div>
        <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Tahun Ajaran</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $stats['tahun_ajaran'] }}</p>
        </div>
    </div>

    {{-- Card Semester --}}
    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-book-open text-xl md:text-2xl"></i>
        </div>
        <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Semester</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $stats['semester'] }}</p>
        </div>
    </div>
</div>

    {{-- 3. Tab Switcher (Lebar disamakan dengan Card dan Tabel) --}}
    <div class="flex bg-white p-1 md:p-2 rounded-xl shadow-sm border border-gray-200 mb-6 md:mb-8 w-full">
    <button @click="tab = 'pelajaran'" 
        :class="tab === 'pelajaran' ? 'bg-[#004aad] text-white shadow-md' : 'text-gray-500 hover:bg-blue-50 active:bg-blue-100'"
        class="flex-1 py-2.5 md:py-3 rounded-lg text-[10px] md:text-sm font-bold uppercase transition-all duration-200 tracking-tight md:tracking-normal">
        Jadwal Pelajaran
    </button>
    <button @click="tab = 'ujian'" 
        :class="tab === 'ujian' ? 'bg-[#004aad] text-white shadow-md' : 'text-gray-500 hover:bg-blue-50 active:bg-blue-100'"
        class="flex-1 py-2.5 md:py-3 rounded-lg text-[10px] md:text-sm font-bold uppercase transition-all duration-200 tracking-tight md:tracking-normal">
        Jadwal Ujian
    </button>
</div>

    {{-- 4. Main Table Container --}}
    <div class="bg-white rounded-none md:rounded-xl shadow-sm border-x-0 md:border border-gray-300 overflow-hidden w-full mt-6">
    {{-- Header Container --}}
    <div class="p-4 md:p-6 border-b border-gray-100 bg-white">
        
        {{-- Flex Container: tumpuk di HP (flex-col), sejajar di Desktop (md:flex-row) --}}
        <div class="flex flex-col md:flex-row items-center gap-4">
            
            {{-- Tombol Unduh: Di HP order-1 (paling atas), di Desktop order-2 --}}
            <div class="order-1 md:order-2 w-full md:w-auto flex justify-end">
                <a :href="tab === 'pelajaran' ? '{{ route('siswa.informasi.jadwal.download', ['type' => 'pelajaran']) }}' : '{{ route('siswa.informasi.jadwal.download', ['type' => 'ujian']) }}'" 
                   class="bg-[#004aad] text-white px-4 py-2 rounded-lg flex items-center gap-2 text-[9px] md:text-xs font-bold hover:bg-blue-800 transition capitalize shadow-sm">
                    <i class="fas fa-download"></i> Unduh
                </a>
            </div>

            {{-- Judul: Di HP order-2 (di bawah tombol), di Desktop order-1 --}}
            <div class="order-2 md:order-1 flex-1 w-full text-center md:text-center">
                <h2 class="font-bold text-gray-800 uppercase tracking-widest text-[10px] md:text-base leading-tight" 
                    x-text="tab === 'pelajaran' ? 'Jadwal Pelajaran Semester {{ $stats['semester'] }} Kelas {{ $stats['nama_kelas'] }} Tahun Ajaran {{ $stats['tahun_ajaran'] }}' : 
                    '{{ $stats['judul_ujian'] }}'">
                </h2>
            </div>
        </div>
        </div>

        <div class="overflow-x-auto">

            {{-- TABEL PELAJARAN --}}
            <table x-show="tab === 'pelajaran'" class="w-full text-[10px] md:text-sm border-collapse">
                <thead class="bg-gray-50 border-b-2 border-gray-300">
        {{-- Header tetap uppercase biasanya lebih rapi, tapi jika ingin ganti besar kecil, hapus class 'uppercase' --}}
        <tr class="text-slate-700 uppercase text-xs tracking-wider">
            <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Hari</th>
            <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Waktu</th>
            <th class="p-3 md:p-4 whitespace-nowrap font-bold text-center border-r border-gray-300">Mata Pelajaran</th>
            <th class="p-3 md:p-4 whitespace-nowrap font-bold text-center">Guru Pengajar</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-300">
        @forelse($jadwalPelajaran as $hari => $items)
            @foreach($items as $index => $item)
            <tr class="hover:bg-blue-50/30 transition-colors">
                @if($index == 0)
                {{-- Kolom Hari: Menggunakan ucwords agar "SENIN" jadi "Senin" --}}
                <td rowspan="{{ count($items) }}" class="p-3 md:p-4 text-slate-800 font-bold bg-gray-50/50 text-center border-r-2 border-gray-300">
                    {{ ucwords(strtolower($hari)) }}
                </td>
                @endif

                <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                    {{ date('H:i', strtotime($item->jam_mulai)) }} - {{ date('H:i', strtotime($item->jam_selesai)) }}
                </td>

                <td class="p-3 md:p-4 text-gray-600 font-medium uppercase text-left border-r border-gray-300 whitespace-nowrap">

    @if($item->jenis == 'istirahat')

        {{ strtoupper($item->kegiatan_kustom) }}

    @elseif($item->jenis == 'non_kbm')

        {{ strtoupper($item->kegiatan_kustom) }}

    @else

        {{ ucwords(strtolower($item->mapel->nama_mapel ?? '-')) }}

    @endif

</td>

                <td class="p-3 md:p-4 text-left text-gray-600 uppercase min-w-[120px]">

    @if($item->jenis == 'kbm')

        {{ ucwords(strtolower($item->guru->user->nama ?? '-')) }}

    @else

        -

    @endif

</td>
            </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="4" class="p-10 text-gray-400 italic text-center text-xs">
                    Jadwal Belum Tersedia
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

            {{-- TABEL UJIAN --}}
<table x-show="tab === 'ujian'" x-cloak class="w-full text-[10px] md:text-sm border-collapse">
    <thead class="bg-gray-50 border-b-2 border-gray-300">
        <tr class="text-slate-700 uppercase text-xs tracking-wider">
            <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Hari</th>
            <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Waktu</th>
            <th class="p-3 md:p-4 whitespace-nowrap font-bold text-left border-r border-gray-300">Mata Pelajaran</th>
            <th class="p-3 md:p-4 whitespace-nowrap font-bold text-center border-r border-gray-300">Pengawas</th>
            <th class="p-3 md:p-4 whitespace-nowrap font-bold">Ruangan</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-300">
        @forelse($jadwalUjian as $hari => $items)
            @foreach($items as $index => $item)
            <tr class="hover:bg-blue-50/30 transition-colors">
                @if($index == 0)
                <td rowspan="{{ count($items) }}" class="p-3 md:p-4 text-slate-800 font-bold bg-gray-50/50 text-center border-r-2 border-gray-300 whitespace-nowrap">
                    {{-- Hari: SENIN -> Senin --}}
                    {{ ucwords(strtolower($hari)) }}
                </td>
                @endif
                <td class="p-3 md:p-4 text-gray-600 text-center border-r border-gray-300 whitespace-nowrap">
                    {{ date('H:i', strtotime($item->jam_mulai)) }} - {{ date('H:i', strtotime($item->jam_selesai)) }}
                </td>
                <td class="p-3 md:p-4 text-left text-slate-800 border-r border-gray-300 min-w-[120px]">
                    {{-- Mapel: BAHASA INGGRIS -> Bahasa Inggris --}}
                    {{ ucwords(strtolower($item->mapel->nama_mapel ?? '-')) }}
                </td>
                <td class="p-3 md:p-4 text-left text-gray-600 border-r border-gray-300 min-w-[120px]">
                    {{-- Pengawas: NAMA GURU -> Nama Guru --}}
                    {{ ucwords(strtolower($item->pengawas->user->nama ?? 'TBA')) }}
                </td>
                <td class="p-3 md:p-4 text-center">
                    {{-- Ruang tetap UPPERCASE karena biasanya kode ruang seperti 'LAB-01' atau 'R.10' lebih cocok huruf besar --}}
                    <span class="text-slate-800 px-2 py-1 rounded font-reguler text-[9px] md:text-[11px]  uppercase whitespace-nowrap">
                        {{ $item->ruangan ?? '-' }}
                    </span>
                </td>
            </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="5" class="p-20 text-gray-400 italic text-center font-medium tracking-widest">
                    Jadwal Ujian Belum Tersedia
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
        </div>
    </div>
</div>

<style>
    /* Styling tambahan untuk memperhalus tampilan */
    [x-cloak] { display: none !important; }
    
    /* Membuat garis tabel lebih bold/terlihat */
    .border-gray-300 {
        border-color: #cbd5e1 !important; /* warna slate-300 */
    }

    /* Memperbaiki radius pada sisi luar */
    .rounded-xl { border-radius: 0.75rem !important; }
</style>
@endsection