@extends('layouts.siswa')

@section('content')
<div class="p-6 bg-[#f1f5f9] min-h-screen font-sans">
    
    {{-- 1. Judul Utama --}}
    <div class="bg-[#004aad] text-white py-2 px-4 rounded-sm mb-6 shadow-md">
        <h1 class="text-xl font-black text-center uppercase tracking-[0.2em]">RIWAYAT PRESENSI SAYA</h1>
    </div>

    {{-- 2. Statistik Ringkas (Gaya Admin) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-sm border border-gray-200 shadow-sm text-center">
            <p class="text-[10px] text-[#004aad] font-black uppercase">Total Hadir</p>
            <p class="text-2xl font-black text-[#004aad]">{{ $riwayat->where('status', 'hadir')->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-sm border border-gray-200 shadow-sm text-center">
            <p class="text-[10px] text-orange-500 font-black uppercase">Izin/Sakit</p>
            <p class="text-2xl font-black text-orange-500">{{ $riwayat->whereIn('status', ['izin', 'sakit'])->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-sm border border-gray-200 shadow-sm text-center">
            <p class="text-[10px] text-red-600 font-black uppercase">Alfa</p>
            <p class="text-2xl font-black text-red-600">{{ $riwayat->where('status', 'alfa')->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-sm border border-gray-200 shadow-sm text-center">
            <p class="text-[10px] text-gray-500 font-black uppercase">Total Sesi</p>
            <p class="text-2xl font-black text-gray-800">{{ $riwayat->count() }}</p>
        </div>
    </div>

    {{-- 3. Tabel Riwayat --}}
    <div class="bg-white rounded-sm shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#004aad] text-white uppercase text-center">
                    <tr class="divide-x divide-white/20">
                        <th class="p-4 w-16 font-black italic">No</th>
                        <th class="p-4 font-black text-left">Tanggal & Waktu</th>
                        <th class="p-4 font-black">Sesi Pelajaran</th>
                        <th class="p-4 font-black">Status</th>
                        <th class="p-4 font-black text-left">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="text-center uppercase font-bold text-gray-700">
                    @forelse($riwayat as $data)
                    <tr class="border-b border-gray-100 divide-x divide-gray-50 hover:bg-gray-50 transition-colors">
                        <td class="p-4 text-[#004aad]">{{ $loop->iteration }}</td>
                        <td class="p-4 text-left">
                            <div class="flex flex-col">
                                <span class="text-xs">{{ \Carbon\Carbon::parse($data->waktu_absen)->isoFormat('D MMMM Y') }}</span>
                                <span class="text-[10px] text-gray-400 italic">{{ \Carbon\Carbon::parse($data->waktu_absen)->format('H:i') }} WIB</span>
                            </div>
                        </td>
                        <td class="p-4 text-xs">{{ $data->presensi->jam_pelajaran ?? '-' }}</td>
                        <td class="p-4">
                            @php
                                $color = [
                                    'hadir' => 'bg-green-100 text-green-700 border-green-200',
                                    'izin'  => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'sakit' => 'bg-orange-100 text-orange-700 border-orange-200',
                                    'alfa'  => 'bg-red-100 text-red-700 border-red-200',
                                ][$data->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                            @endphp
                            <span class="{{ $color }} px-3 py-1 rounded-sm border text-[10px] font-black inline-block min-w-[70px]">
                                {{ $data->status }}
                            </span>
                        </td>
                        <td class="p-4 text-left text-[10px] text-gray-500 italic max-w-xs truncate">
                            {{ $data->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-16 text-gray-400 italic text-center font-bold tracking-widest uppercase">
                            Belum ada riwayat presensi yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Footer Tabel --}}
        <div class="bg-[#004aad] p-3 text-white">
            <div class="text-[10px] font-bold uppercase tracking-widest pl-4 italic">
                Data ditarik secara realtime dari server kesiswaan
            </div>
        </div>
    </div>

    {{-- Tombol Kembali --}}
    <div class="mt-6">
        <a href="{{ route('siswa.presensi.index') }}" class="bg-gray-800 text-white px-6 py-2 rounded-sm text-xs font-black uppercase hover:bg-black transition shadow-md inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Beranda
        </a>
    </div>
</div>

<style>
    .rounded-sm { border-radius: 2px !important; }

    @media (max-width: 767px) {
    /* 1. Sembunyikan kolom yang kurang penting di HP agar tabel tidak terlalu lebar */
    table thead tr th:nth-child(1), /* Sembunyikan No */
    table tbody tr td:nth-child(1),
    table thead tr th:nth-child(5), /* Sembunyikan Keterangan */
    table tbody tr td:nth-child(5) {
        display: none !important;
    }

    /* 2. Sesuaikan ukuran font agar muat */
    table { font-size: 10px !important; }
    
    /* 3. Perkecil padding sel agar tabel lebih ramping */
    table td, table th {
        padding: 8px 4px !important;
    }

    /* 4. Pastikan kartu statistik tersusun 2x2 dengan rapi */
    .grid-cols-2 {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 0.5rem !important;
    }

    /* 5. Paksa judul agar tidak memakan banyak tempat */
    h1 { font-size: 0.9rem !important; }

    /* 6. Pastikan container utama tetap punya sedikit napas */
    .p-6 { padding: 10px !important; }
}
</style>
@endsection