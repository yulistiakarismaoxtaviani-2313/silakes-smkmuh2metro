@extends('layouts.siswa')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans">
    

{{-- 2. Statistik Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8">
    
    {{-- Card Status (Terapkan pola ini ke semua 4 card) --}}
    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-user-check text-xl md:text-2xl"></i>
        </div>
        <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Status Anda</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ Auth::user()->siswa->kelas->nama_kelas ?? 'Siswa Aktif' }}</p>
        </div>
    </div>

    {{-- Card Total Input --}}
    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-file-invoice text-xl md:text-2xl"></i>
        </div>
        <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Total Input</p>
            <p class="text-xs md:text-2xl font-black text-slate-800 leading-none">{{ $prestasi->count() }}</p>
        </div>
    </div>

    {{-- Card Akademik --}}
    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-graduation-cap text-xl md:text-2xl"></i>
        </div>
        <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Akademik</p>
            <p class="text-xs md:text-2xl font-black text-slate-800 leading-none">{{ $prestasi->where('kategori', 'Akademik')->count() }}</p>
        </div>
    </div>

    {{-- Card Non-Akademik --}}
    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-medal text-xl md:text-2xl"></i>
        </div>
        <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Non-Akademik</p>
            <p class="text-xs md:text-2xl font-black text-slate-800 leading-none">{{ $prestasi->where('kategori', 'Non-Akademik')->count() }}</p>
        </div>
        </div>
    </div>

    {{-- 3. Main Table Container (Gaya Tabel Jadwal) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        <div class="p-6 border-b border-gray-300 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-2 h-6 bg-[#004aad] rounded-full"></div>
                <h2 class="font-bold text-gray-800 uppercase tracking-widest text-sm">Daftar Riwayat Prestasi</h2>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar pb-2">
            <table class="w-full text-[10px] md:text-sm border-collapse border-spacing-0">
                <thead class="bg-gray-50 border-b-2 border-gray-300">
                    <tr class="text-slate-700 uppercase text-[11px] tracking-wider">
                        <th class="p-4 w-12 font-bold border-r border-gray-300 text-center">No</th>
                        <th class="p-4 font-bold text-left border-r border-gray-300 pl-6">Nama Lomba</th>
                        <th class="p-4 font-bold border-r border-gray-300 text-center">Kategori</th>
                        <th class="p-4 font-bold border-r border-gray-300 text-center">Tanggal</th>
                        <th class="p-4 font-bold border-r border-gray-300 text-center">Tingkat</th>
                        <th class="p-4 font-bold border-r border-gray-300 text-center">Status</th>
                        <th class="p-4 font-bold text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse($prestasi as $index => $item)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-4 text-center font-bold text-[#004aad] border-r border-gray-300">
                            {{ $loop->iteration }}
                        </td>
                        <td class="p-4 pl-6 text-left border-r border-gray-300">
                            <span class="text-slate-800 font-bold uppercase text-xs leading-tight">
                                {{ $item->nama_lomba }}
                            </span>
                        </td>
                        <td class="p-4 text-center border-r border-gray-300">
                            <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-lg border border-gray-200 uppercase">
                                {{ $item->kategori }}
                            </span>
                        </td>
                        <td class="p-4 text-center text-gray-600 font-medium border-r border-gray-300">
                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                        </td>
                        <td class="p-4 text-center text-slate-700 font-bold text-xs uppercase border-r border-gray-300">
                            {{ $item->tingkat }}
                        </td>
                        <td class="p-4 text-center border-r border-gray-300">
                            @if($item->status_validasi == 'Disetujui')
                                <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase border border-emerald-100">Disetujui</span>
                            @elseif($item->status_validasi == 'Ditolak')
                                <span class="bg-red-50 text-red-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase border border-red-100">Ditolak</span>
                            @else
                                <span class="bg-amber-50 text-amber-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase border border-amber-100">Proses</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('siswa.prestasi.show', $item->id_prestasi) }}" 
                                   class="bg-blue-50 text-[#004aad] w-9 h-9 flex items-center justify-center rounded-xl hover:bg-[#004aad] hover:text-white transition-all shadow-sm border border-blue-100">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <form action="{{ route('siswa.prestasi.destroy', $item->id_prestasi) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus data prestasi ini?')"
                                            class="bg-red-50 text-red-600 w-9 h-9 flex items-center justify-center rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-20 text-gray-400 italic text-center font-medium tracking-widest uppercase text-xs">
                            Belum ada riwayat prestasi yang diinput
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Konsistensi Radius */
    .rounded-xl { border-radius: 0.75rem !important; }
    .rounded-2xl { border-radius: 1rem !important; }
    
    /* Garis tabel sesuai referensi Jadwal */
    .border-gray-300 {
        border-color: #cbd5e1 !important; 
    }

    .custom-scrollbar::-webkit-scrollbar {
    height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

/* TAMBAHAN KHUSUS TAMPILAN HP */
@media screen and (max-width: 768px) {
    /* Mencegah overflow horizontal */
    body, html {
        overflow-x: hidden;
    }

    /* Membuat kartu statistik menjadi 2 kolom agar tidak terlalu panjang ke bawah */
    .grid-cols-1.md\:grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    /* Menyesuaikan ukuran font tabel agar tidak pecah di layar kecil */
    table td, table th {
        padding: 8px 6px !important;
        white-space: nowrap; /* Mencegah teks terpotong/turun ke bawah */
    }
    /* Memastikan tombol aksi tetap terlihat */
    .w-28 {
        width: auto !important;
    }
}
</style>
@endsection