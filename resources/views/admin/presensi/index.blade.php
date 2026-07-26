@extends('layouts.admin')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans">
    
    {{-- 1. Info Bar (Gaya Split Layout) --}}
    <div class="grid grid-cols-3 md:grid-cols-3 gap-0 bg-white border-b-4 border-[#004aad] rounded-xl shadow-sm mb-6 overflow-hidden divide-y md:divide-y-0 md:divide-x divide-gray-100">
        <div class="p-4 md:p-6 flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-calendar-alt text-xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1.5">Tahun Ajaran</p>
                <p class="text-xs md:text-sm font-black text-black uppercase">{{ $tahun_aktif->tahun_ajaran ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="p-4 md:p-6 flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1.5">Total Siswa</p>
                <p class="text-xs md:text-sm font-black text-black uppercase">{{ number_format($stats['total_siswa'] ?? 0, 0, ',', '.') }} SISWA</p>
            </div>
        </div>
        <div class="p-4 md:p-6 flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                <i class="fas fa-door-open text-xl"></i>
            </div>
            <div class="text-center md:text-left">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1.5">Total Kelas</p>
                <p class="text-xs md:text-sm font-black text-black uppercase">{{ $stats['total_kelas'] ?? 0 }} KELAS</p>
            </div>
        </div>
    </div>

    {{-- 2. Ringkasan Statistik (Gaya Card Rounded-xl) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl flex items-center gap-4 shadow-sm border border-gray-200">
            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-lock-open text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] text-gray-500 font-black uppercase leading-tight">Presensi Dibuka</p>
                <p class="text-xl font-black text-black">{{ $stats['presensi_buka'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl flex items-center gap-4 shadow-sm border border-gray-200">
            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-lock text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] text-gray-500 font-black uppercase leading-tight">Presensi Ditutup</p>
                <p class="text-xl font-black text-black">{{ $stats['presensi_tutup'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl flex items-center gap-4 shadow-sm border border-gray-200">
            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-check-double text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] text-gray-500 font-black uppercase leading-tight">Kelas Sudah Isi</p>
                <p class="text-xl font-black text-black">{{ $stats['kelas_sudah'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl flex items-center gap-4 shadow-sm border border-gray-200">
            <div class="text-[#004aad] bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] text-gray-500 font-black uppercase leading-tight">Kelas Belum Isi</p>
                <p class="text-xl font-black text-black">{{ $stats['kelas_belum'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    {{-- 3. Main Container Tabel --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        
        {{-- Header Area & Filter --}}
        <div class="p-6  border-b border-gray-300 bg-white">
            <div class="flex flex-row justify-between items-center gap-4 mb-6 whitespace-nowrap">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-6 bg-[#004aad] rounded-full"></div>
                    <h2 class="font-bold text-black uppercase tracking-widest text-[12px] md:text-sm">Riwayat Presensi</h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.presensi.create') }}" class="bg-[#004aad] text-white px-3 md:px-5 py-2.5 flex items-center gap-2 rounded-xl shadow-sm hover:bg-blue-900 transition-all text-[8px] md:text-[10px] font-black uppercase tracking-widest">
                        <i class="fas fa-plus-circle text-sm"></i> Buka Presensi Kelas
                    </a>
                </div>
            </div>

            {{-- Form Filter (3 Kolom) --}}
            <form action="{{ route('admin.presensi.index') }}" method="GET" class="grid grid-cols-3 md:grid-cols-3 gap-3">
                {{-- Filter Status --}}
                <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-300 text-black text-[9px] md:text-[11px] font-reguler rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 capitalize">
                    <option value="">Semua Status</option>
                    <option value="dibuka" {{ request('status') == 'dibuka' ? 'selected' : '' }}>Dibuka</option>
                    <option value="ditutup" {{ request('status') == 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                </select>

                <select name="jam" onchange="this.form.submit()" class="bg-white border border-gray-300 text-black text-[9px] md:text-[11px] font-reguler rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 capitalize">

    <option value="">Semua Sesi</option>

    @foreach($jamPelajaran as $jam)
        <option value="{{ $jam->id_jam }}"
            {{ request('jam') == $jam->id_jam ? 'selected' : '' }}>
            {{ $jam->nama_jam }}
        </option>
    @endforeach

</select>

                {{-- Filter Tanggal (Search) --}}
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Tanggal..." class="bg-white border border-gray-300 text-black text-[9px] md:text-[11px] font-reguler rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 capitalize">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <i class="fas fa-search text-gray-400 text-[8px] md:text-sm px-0.4"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Area --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-50 border-b-2 border-gray-300">
                    <tr class="text-slate-700 uppercase text-xs tracking-wider text-center">
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">No</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Tanggal</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Jam Pelajaran</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Waktu Mulai & Tutup</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Sudah Isi</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Belum Isi</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Status</th>
                        <th class="p-3 md:p-4 whitespace-nowrap text-center font-bold border-r border-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @php
                        // Menghitung kemunculan setiap tanggal pada halaman paginasi ini untuk acuan rowspan
                        $tanggalCounts = [];
                        foreach ($presensis as $p) {
                            $tglFormatted = \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y');
                            $tanggalCounts[$tglFormatted] = ($tanggalCounts[$tglFormatted] ?? 0) + 1;
                        }

                        // Menyimpan track tanggal mana saja yang sudah dirender kolom utamanya
                        $renderedTanggal = [];
                        $currentNo = $presensis->firstItem();
                    @endphp

                    @forelse($presensis as $key => $p)
                        @php
                            $tglHariIni = \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y');
                            
                            $sudah = \App\Models\DetailPresensi::where('id_presensi', $p->id_presensi)
                                        ->whereIn('detail_presensi.status', ['hadir', 'sakit', 'izin']) 
                                        ->join('siswa', 'detail_presensi.id_siswa', '=', 'siswa.id_siswa')
                                        ->distinct('siswa.id_kelas')
                                        ->count('siswa.id_kelas');
                            $total_k = \App\Models\Kelas::count();
                            $belum = max(0, $total_k - $sudah);
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            
                            {{-- LOGIKA ROWSPAN: Kolom NO dan TANGGAL hanya dibuat di baris pertama kemunculan tanggal tersebut --}}
                            @if (!in_array($tglHariIni, $renderedTanggal))
                                @php 
                                    $rowspan = $tanggalCounts[$tglHariIni]; 
                                    $renderedTanggal[] = $tglHariIni;
                                @endphp
                                <td rowspan="{{ $rowspan }}" class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                                    {{ $currentNo++ }}
                                </td>
                                <td rowspan="{{ $rowspan }}" class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                                    {{ $tglHariIni }}
                                </td>
                            @endif

                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
    {{ $p->jamPelajaran->nama_jam ?? '-' }}
</td>

<td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
    {{ $p->jam_pelajaran ?? '-' }}
</td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-lg font-reguler capitalize">
                                    {{ $sudah }} Kelas
                                </span>
                            </td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                                <span class="px-3 py-1 font-reguler capitalize">
                                    {{ $belum }} Kelas
                                </span>
                            </td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                                @php 
        // 1. Cek apakah di database statusnya masih 'dibuka'
        $isDbOpen = $p->status_sesi == 'dibuka';
        
        // 2. Cek apakah waktu sekarang sudah melewati waktu_ditutup
        $waktuSelesai = \Carbon\Carbon::parse($p->waktu_ditutup);
        $isSudahLewatWaktu = \Carbon\Carbon::now()->greaterThan($waktuSelesai);
        
        // 3. Status Final: Hanya benar-benar 'Dibuka' jika di DB 'dibuka' DAN belum lewat waktu
        $tampilkanSebagaiBuka = ($isDbOpen && !$isSudahLewatWaktu);
    @endphp
    
    <span class="{{ $tampilkanSebagaiBuka ? 'bg-green-50 text-green-600 border-green-200' : 'bg-red-50 text-red-600 border-red-200' }} px-4 py-1 rounded-lg text-[10px] font-reguler border capitalize">
        {{ $tampilkanSebagaiBuka ? 'Dibuka' : 'Ditutup' }}
    </span>
                            </td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
{{-- Tombol Detail --}}
                                    <a href="{{ route('admin.presensi.show', $p->id_presensi) }}" 
                                       class="bg-blue-50 text-[#004aad] w-9 h-9 flex items-center justify-center rounded-xl hover:bg-[#004aad] hover:text-white transition-all shadow-sm border border-blue-100"
                                       title="Detail Presensi">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    {{-- Tombol Hapus (Jaga-jaga Tanggal Merah) --}}
                                    <form action="{{ route('admin.presensi.destroy', $p->id_presensi) }}" method="POST" class="inline">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus sesi presensi ini? Semua data rekap absensi siswa pada jam pelajaran ini akan dihapus permanen.')"
                                                class="bg-red-50 text-red-600 w-9 h-9 flex items-center justify-center rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100"
                                                title="Hapus Sesi">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-20 text-black text-center font-reguler tracking-widest uppercase text-xs">
                                Data presensi tidak ditemukan.
                            </td>
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
            {{ $presensis->firstItem() ?? 0 }} - {{ $presensis->lastItem() ?? 0 }}
        dari {{ $presensis->total() }}
    </div>

    <div class="ml-auto custom-pagination">
        {{ $presensis->appends(request()->query())->links('pagination::tailwind') }}
    </div>

</div>
    </div>
</div>

<style>
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