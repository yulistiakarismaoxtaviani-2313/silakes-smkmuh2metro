@extends('layouts.siswa') {{-- Sesuaikan dengan nama layout kamu --}}

@section('content')
<div class="p-6 bg-[#f1f5f9] min-h-screen font-sans" x-data="{ tab: '{{ request('tab', 'hari-ini') }}' }">

    {{-- 3. Tab Switcher (Disesuaikan dengan Referensi Jadwal: Rounded-xl & Clean) --}}
    <div class="flex bg-white p-2 rounded-xl shadow-sm border border-gray-200 mb-8 w-full">
        <button @click="tab = 'hari-ini'; history.replaceState(null, null, '?tab=hari-ini');" 
            :class="tab === 'hari-ini' ? 'bg-[#004aad] text-white shadow-md' : 'text-gray-500 hover:bg-blue-100'"
            class="flex-1 py-3 rounded-lg text-sm font-bold uppercase transition-all duration-200 outline-none">
            Hari Ini
        </button>
        <button @click="tab='riwayat';history.replaceState(null, null, '?tab=riwayat');" 
            :class="tab === 'riwayat' ? 'bg-[#004aad] text-white shadow-md' : 'text-gray-500 hover:bg-blue-100'"
            class="flex-1 py-3 rounded-lg text-sm font-bold uppercase transition-all duration-200 outline-none">
            Riwayat
        </button>
        <button @click="tab='rekap';history.replaceState(null, null, '?tab=rekap');" 
            :class="tab === 'rekap' ? 'bg-[#004aad] text-white shadow-md' : 'text-gray-500 hover:bg-blue-100'"
            class="flex-1 py-3 rounded-lg text-sm font-bold uppercase transition-all duration-200 outline-none">
            Rekap
        </button>
    </div>

    {{-- 4. Main Content Area (Disesuaikan dengan gaya Tabel Jadwal) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        
        {{-- Header Tab Content --}}
        <div class="p-6 border-b border-gray-300 bg-white flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-center md:text-left">
                <h2 class="font-bold text-gray-800 uppercase tracking-widest text-base" x-text="tab.replace('-', ' ')"></h2>
            </div>
            
            <div class="flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-lg border border-blue-100">
                <i class="far fa-calendar-alt text-[#004aad] text-xs"></i>
                <span class="text-[11px] font-bold text-[#004aad] uppercase tracking-wide">
                    {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </div>

        <div class="p-6">
            {{-- TAB: HARI INI --}}
            <div x-show="tab === 'hari-ini'" class="space-y-4">
                @forelse($sesi_aktif as $sesi)
                    @php
                        $sudahAbsen = $sesi->details->first();
                        $sekarang = \Carbon\Carbon::now();
                        $buka = \Carbon\Carbon::parse($sesi->waktu_dibuka);
                        $tutup = \Carbon\Carbon::parse($sesi->waktu_ditutup);
                        
                        // Cek apakah siswa sudah benar-benar konfirmasi isi absen (Hadir/Izin/Sakit)
                        $sudahFixAbsen = ($sudahAbsen && in_array($sudahAbsen->status, ['hadir', 'sakit', 'izin']));
                        $sudahDivalidasiGuru = (
    $sudahAbsen &&
    $sudahAbsen->status == 'alfa' &&
    $sudahAbsen->keterangan == 'Divalidasi oleh Guru (Otomatis Alfa)'
);
                    @endphp

                    <div class="bg-white border border-gray-300 rounded-xl p-5 flex flex-col md:flex-row justify-between items-center hover:bg-gray-50 transition-all group">
                        <div class="mb-4 md:mb-0 flex items-center gap-5">
                            {{-- Icon --}}
                            <div class="hidden md:flex bg-gray-50 p-3 rounded-xl border border-gray-200">
                                <i class="fas fa-clock text-[#004aad] text-lg"></i>
                            </div>
                            
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-medium text-gray-500 capitalize tracking-wide">Sesi pelajaran</span>
                                    <span class="h-1 w-1 bg-gray-300 rounded-full"></span>
                                    <h5 class="font-reguler text-black text-[10px] capitalize">{{ strtolower($sesi->jamPelajaran->nama_jam ?? '-') }}</h5>
                                </div>
                                <p class="text-xs text-black font-medium">
                                    Waktu presensi: <span class="font-bold">{{ $buka->format('H:i') }} - {{ $tutup->format('H:i') }} WIB</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 w-full md:w-auto">

    @if($sudahFixAbsen)

        <div class="flex-1 md:flex-none text-center px-4 py-2.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[10px] font-bold capitalize flex items-center justify-center gap-2">
            <i class="fas fa-check-circle text-xs"></i>
            Terabsen: {{ $sudahAbsen->status }}
        </div>

        <button disabled class="px-5 py-2.5 bg-gray-100 text-gray-400 rounded-lg text-[10px] font-bold capitalize cursor-not-allowed border border-gray-200">
            Selesai
        </button>

    @elseif($sudahDivalidasiGuru)

        <div class="flex-1 md:flex-none text-center px-4 py-2.5 bg-red-50 text-red-700 border border-red-200 rounded-lg text-[10px] font-bold capitalize flex items-center justify-center gap-2">
            <i class="fas fa-times-circle text-xs"></i>
            Terabsen: Alfa
        </div>

        <button disabled class="px-5 py-2.5 bg-gray-100 text-gray-400 rounded-lg text-[10px] font-bold capitalize cursor-not-allowed border border-gray-200">
            Selesai
        </button>

    @elseif($sekarang->between($buka, $tutup))

        <div class="flex-1 md:flex-none text-center px-4 py-2.5 bg-blue-50 text-[#004aad] border border-blue-200 rounded-lg text-[10px] font-bold capitalize animate-pulse flex items-center justify-center">
            Belum presensi
        </div>

        <a href="{{ route('siswa.presensi.form', $sesi->id_presensi) }}"
           class="flex-1 md:flex-none text-center bg-[#004aad] text-white px-6 py-2.5 rounded-lg text-[10px] font-bold capitalize hover:bg-blue-800 transition shadow-sm active:scale-95">
            Absen sekarang
        </a>

    @elseif($sekarang->lt($buka))

        <div class="flex-1 md:flex-none text-center px-4 py-2.5 bg-gray-50 text-gray-500 border border-gray-200 rounded-lg text-[10px] font-bold capitalize flex items-center justify-center">
            Belum dimulai
        </div>

        <button disabled class="flex-1 md:flex-none px-6 py-2.5 bg-gray-200 text-gray-400 rounded-lg text-[10px] font-bold capitalize cursor-not-allowed">
            Menunggu
        </button>

    @else

        <div class="flex-1 md:flex-none text-center px-4 py-2.5 bg-red-50 text-red-700 border border-red-200 rounded-lg text-[10px] font-bold capitalize flex items-center justify-center">
            Waktu habis
        </div>

        <button disabled class="flex-1 md:flex-none px-6 py-2.5 bg-red-100 text-red-400 rounded-lg text-[10px] font-bold capitalize cursor-not-allowed border border-red-200">
            Ditutup
        </button>

    @endif

</div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                        <div class="bg-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-gray-200">
                            <i class="fas fa-clipboard-list text-gray-300 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 font-bold capitalize tracking-wide text-xs">Jadwal presensi tidak ditemukan hari ini</p>
                    </div>
                @endforelse
            </div>


            {{-- TAB: RIWAYAT (Custom Grey Border, Black Font & Capitalize Style) --}}
            <div x-show="tab === 'riwayat'" x-cloak class="space-y-6">
                
                {{-- 1. Statistik Cards (Tetap Biru sesuai keinginan sebelumnya) --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    {{-- Hadir --}}
                    <div class="bg-white p-4 rounded-xl border border-[#004aad] border-b-4 flex items-center gap-4 shadow-sm">
                        <div class="bg-blue-50 p-3 rounded-xl">
                            <i class="fas fa-user-check text-[#004aad] text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hadir</p>
                            <p class="text-2xl font-bold text-[#004aad] leading-none">{{ $rekap['hadir'] }}</p>
                        </div>
                    </div>

                    {{-- Izin --}}
                    <div class="bg-white p-4 rounded-xl border border-[#004aad] border-b-4 flex items-center gap-4 shadow-sm">
                        <div class="bg-blue-50 p-3 rounded-xl">
                            <i class="fas fa-envelope-open-text text-[#004aad] text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Izin</p>
                            <p class="text-2xl font-bold text-[#004aad] leading-none">{{ $rekap['izin'] }}</p>
                        </div>
                    </div>

                    {{-- Sakit --}}
                    <div class="bg-white p-4 rounded-xl border border-[#004aad] border-b-4 flex items-center gap-4 shadow-sm">
                        <div class="bg-blue-50 p-3 rounded-xl">
                            <i class="fas fa-medkit text-[#004aad] text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sakit</p>
                            <p class="text-2xl font-bold text-[#004aad] leading-none">{{ $rekap['sakit'] }}</p>
                        </div>
                    </div>

                    {{-- Alfa --}}
                    <div class="bg-white p-4 rounded-xl border border-[#004aad] border-b-4 flex items-center gap-4 shadow-sm">
                        <div class="bg-blue-50 p-3 rounded-xl">
                            <i class="fas fa-user-times text-[#004aad] text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Alfa</p>
                            <p class="text-2xl font-bold text-[#004aad] leading-none">{{ $rekap['alfa'] }}</p>
                        </div>
                    </div>
                </div>

                {{-- 3. Data Grouping (Border Abu-abu & Font Hitam Capitalize) --}}
                <div class="space-y-6">
                    @php
                        $groupedRiwayat = collect($riwayat)->groupBy('tanggal');
                    @endphp

                    @forelse($groupedRiwayat as $tanggal => $items)
                    <div class="bg-white border border-gray-300 rounded-xl shadow-md overflow-hidden">
                        {{-- Header Tanggal --}}
                        <div class="bg-gray-50 border-b border-gray-300 px-6 py-4 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class=" p-2 rounded-lg">
                                    <i class="far fa-calendar-alt text-[#004aad] text-xs"></i>
                                </div>
                                <h3 class="text-sm font-bold text-black capitalize tracking-tight">
                                    {{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM YYYY') }}
                                </h3>
                            </div>
                            <span class="bg-white border border-gray-300 text-[10px] font-bold text-black px-4 py-1.5 rounded-full shadow-sm capitalize">
                                {{ $items->count() }} Sesi Pelajaran
                            </span>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            @foreach($items as $item)
                            <div class="flex flex-col md:flex-row items-center justify-between p-6 hover:bg-gray-50 transition-colors group gap-4">
                                
                                {{-- Sesi --}}
                                <div class="w-full md:w-1/4">
                                    <p class="text-[10px] font-bold text-gray-500 capitalize mb-1">Jam Pelajaran</p>
                                    <div class="text-sm font-bold text-black leading-tight capitalize">
                                        {{ strtolower($item['jam_pelajaran']) }}
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="w-full md:w-1/4 flex flex-col items-center border-y md:border-y-0 md:border-x border-gray-200 py-4 md:py-0">
                                    <p class="text-[10px] font-bold text-gray-500 capitalize mb-2">Status Kehadiran</p>
                                    @php
                                        $badgeStyle = [
                                            'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'sakit' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'izin'  => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'alfa'  => 'bg-red-50 text-red-700 border-red-200',
                                        ];
                                        $style = $badgeStyle[$item['status']] ?? 'bg-gray-50 text-gray-400 border-gray-200';
                                    @endphp
                                    <span class="px-6 py-2 border rounded-xl text-[10px] capitalize font-bold {{ $style }} min-w-[120px] text-center">
                                        {{ $item['status'] }}
                                    </span>
                                </div>

                                {{-- Waktu --}}
                                <div class="w-full md:w-1/4 flex flex-col items-center">
                                    <p class="text-[10px] font-bold text-gray-500 capitalize mb-1">Waktu Presensi</p>
                                    <span class="text-sm font-bold text-black">
                                        {{ $item['waktu_absen'] ?? '--:--' }} Wib
                                    </span>
                                </div>

                                {{-- Bukti --}}
                                <div class="w-full md:w-1/4 flex flex-col items-end">
                                    <p class="text-[10px] font-bold text-gray-500 capitalize mb-1">Lampiran Bukti</p>
                                    @if($item['file_bukti'])
                                        <a href="{{ asset('storage/bukti_absen/'.$item['file_bukti']) }}" target="_blank" 
                                           class="bg-[#004aad] text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition shadow-sm flex items-center gap-2 text-[10px] font-bold capitalize">
                                            <i class="fas fa-image"></i> Lihat Bukti
                                        </a>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-300 italic capitalize">Tidak Ada File</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-20 bg-white border-2 border-dashed border-gray-300 rounded-xl">
                        <p class="text-gray-400 font-bold capitalize tracking-wide text-xs">Data riwayat tidak ditemukan</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- TAB: REKAP (Black Font, Selective Bold & Proper Case) --}}
        <div x-show="tab === 'rekap'" x-cloak class="p-6 space-y-6">

            {{-- Filter Semester, Tahun & Tombol --}}
            <form method="GET" action="{{ route('siswa.presensi.index') }}">
                <input type="hidden" name="tab" :value="tab">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                <div class="relative w-full">
                    <select 
                         name="id_semester"

    onchange="this.form.submit()"

    class="w-full bg-white text-xs text-black border border-gray-400 px-4 py-3.5 rounded-xl outline-none appearance-none">



    @foreach($semesterList as $s)

        <option value="{{ $s->id_semester }}"

            {{ $id_semester == $s->id_semester ? 'selected' : '' }}>

            Semester {{ ucfirst($s->nama_semester) }}

        </option>

    @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-black">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>

                <div class="relative w-full">
                    <select 
                    
    name="id_tahun_ajaran"
    onchange="this.form.submit()"
    class="w-full bg-white text-xs text-black border border-gray-400 px-4 py-3.5 rounded-xl outline-none appearance-none">

    @foreach($tahunAjaranList as $ta)
        <option value="{{ $ta->id_tahun_ajaran }}"
            {{ $id_tahun_ajaran == $ta->id_tahun_ajaran ? 'selected' : '' }}>
            {{ $ta->tahun_ajaran }}
        </option>
    @endforeach

</select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-black">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>

                <a href="{{ route('siswa.presensi.unduh', [
    'id_semester' => $id_semester,
    'id_tahun_ajaran' => $id_tahun_ajaran
]) }}"
class="bg-[#004aad] hover:bg-blue-800 text-white px-6 py-3.5 rounded-xl font-bold text-xs capitalize flex items-center justify-between shadow-md transition-all">

    Unduh
    <i class="fas fa-download ml-2"></i>

</a>
            </div>
            </form>

            {{-- Progress Card --}}
            <div class="bg-gray-50 border border-gray-300 p-6 rounded-xl shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-sm text-black capitalize">
                        Presentase Kehadiran Semester Ini : 
                        <span class="font-bold ml-1">
                            {{ $rekap_total['total'] > 0 ? number_format(($rekap_total['hadir'] / $rekap_total['total']) * 100, 1) : 0 }}%
                        </span>
                    </h4>
                </div>
                
                {{-- Tabel Rekap Bulanan --}}
                <div class="bg-white rounded-xl border border-gray-300 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-300">
                                    <th class="p-4 text-[10px] font-bold text-black uppercase tracking-widest">BULAN</th>
                                    <th class="p-4 text-[10px] font-bold text-black uppercase tracking-widest text-center">HADIR</th>
                                    <th class="p-4 text-[10px] font-bold text-black uppercase tracking-widest text-center">ALFA</th>
                                    <th class="p-4 text-[10px] font-bold text-black uppercase tracking-widest text-center">IZIN</th>
                                    <th class="p-4 text-[10px] font-bold text-black uppercase tracking-widest text-center">SAKIT</th>
                                    <th class="p-4 text-[10px] font-bold text-black uppercase tracking-widest text-center">% HADIR</th>
                                </tr>
                            </tbody>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($rekap_bulanan as $bulan => $data)
                                <tr class="hover:bg-gray-50 transition-colors text-black">
                                    <td class="p-4 text-xs capitalize">{{ $bulan }}</td>
                                    <td class="p-4 text-xs text-center font-medium">{{ $data['hadir'] }}</td>
                                    <td class="p-4 text-xs text-center font-medium">{{ $data['alfa'] }}</td>
                                    <td class="p-4 text-xs text-center font-medium">{{ $data['izin'] }}</td>
                                    <td class="p-4 text-xs text-center font-medium">{{ $data['sakit'] }}</td>
                                    <td class="p-4 text-xs text-center font-bold">
                                        {{ $data['total'] > 0 ? number_format(($data['hadir'] / $data['total']) * 100, 0) : 0 }}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Catatan --}}
            <div class="bg-white border-l-4 border-b-0 border-r-0 border-t-0 border-[#004aad] border border-gray-300 p-4 rounded-xl shadow-sm">
                <h5 class="text-xs font-bold text-black capitalize mb-2">Catatan Penting</h5>
                <ul class="text-[10px] text-black space-y-1 capitalize">
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-black rounded-full"></span> 
                        Kehadiran kurang dari 75% wajib membawa surat keterangan orang tua
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-black rounded-full"></span> 
                        Rekap ini berdasarkan data absensi semester yang dipilih
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }

    @media (max-width: 767px) {
        /* Pastikan container utama tidak melebar */
        .p-6 { padding: 0.75rem !important; }

        /* Paksa semua elemen statistik jadi 2 kolom (atau 1 kolom jika mau) */
        .grid-cols-4 { 
            grid-template-columns: repeat(2, 1fr) !important; 
        }

        /* PERBAIKAN KHUSUS ITEM RIWAYAT */
        /* Ubah item riwayat dari flex-row ke block */
        .group.flex-col.md\:flex-row {
            display: block !important;
            padding: 1rem !important;
        }

        /* Paksa lebar setiap kolom di dalam riwayat menjadi 100% */
        .w-full.md\:w-1\/4 {
            width: 100% !important;
            border: none !important; /* Hilangkan border samping agar tidak aneh */
            margin-bottom: 0.5rem !important;
            text-align: left !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
        }

        /* Atur tampilan status agar tidak menumpuk */
        .w-full.md\:w-1\/4 .px-6 {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        /* Sembunyikan label header kolom di tabel riwayat jika perlu, atau perkecil */
        .w-full.md\:w-1\/4 p.text-\[10px\] {
            margin-bottom: 0 !important;
        }

        /* Pastikan tabel rekap tetap bisa scroll */
        .overflow-x-auto {
            display: block;
            width: 100%;
            overflow-x: auto;
        }
    }
</style>
@endsection