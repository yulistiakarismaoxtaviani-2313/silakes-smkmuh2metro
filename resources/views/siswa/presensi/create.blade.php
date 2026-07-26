@extends('layouts.siswa')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <div class="bg-blue-800 p-4 text-center">
            <h2 class="text-white font-bold text-lg uppercase">Form Presensi Siswa</h2>
        </div>

        <div class="p-6">
            <div class="mb-6 text-center border-b pb-4">
                <h3 class="text-2xl font-black text-blue-900">{{ $sesi_db->jadwal->nama_pelajaran ?? 'Jam Pelajaran' }}</h3>
                <p class="text-gray-500">{{ \Carbon\Carbon::parse($sesi_db->tanggal)->translatedFormat('l, d F Y') }}</p>
                <span class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-bold mt-2 inline-block">
                    Sesi: {{ $sesi_db->waktu_dibuka }} - {{ $sesi_db->waktu_ditutup }} WIB
                </span>
            </div>

            <form action="{{ route('siswa.presensi.store') }}" method="POST" enctype="multipart/form-data" x-data="{ status: 'hadir' }">
                @csrf
                <input type="hidden" name="id_presensi" value="{{ $sesi_db->id_presensi }}">

                <div class="space-y-3 mb-6">
                    <p class="font-bold text-gray-700">Pilih Status Kehadiran:</p>
                    @foreach(['hadir', 'izin', 'sakit'] as $s)
                    <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-blue-50 transition" :class="status === '{{ $s }}' ? 'border-blue-600 bg-blue-50' : 'border-gray-200'">
                        <input type="radio" name="status" value="{{ $s }}" x-model="status" class="w-5 h-5 text-blue-600">
                        <span class="ml-3 font-bold uppercase text-gray-700">{{ $s }}</span>
                    </label>
                    @endforeach
                </div>

                <div x-show="status !== 'hadir'" x-transition class="bg-yellow-50 p-4 rounded-xl border border-yellow-200 mb-6">
                    <label class="block text-sm font-bold text-yellow-800 mb-2">Alasan & Bukti (Wajib)</label>
                    <textarea name="keterangan" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 mb-3" placeholder="Tuliskan alasan singkat..."></textarea>
                    <input type="file" name="file_bukti" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('siswa.presensi.index') }}" class="flex-1 text-center py-3 border border-gray-300 rounded-xl font-bold text-gray-600 hover:bg-gray-50">Batal</a>
                    <button type="submit" class="flex-1 py-3 bg-blue-800 text-white rounded-xl font-bold hover:bg-blue-900 shadow-lg">Kirim Absensi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @media (max-width: 767px) {
        /* Memastikan container tidak menabrak pinggiran layar */
        .p-6 { 
            padding: 1rem !important; 
        }

        /* Mengubah font agar lebih proporsional di layar kecil */
        .text-2xl { 
            font-size: 1.25rem !important; 
        }

        /* Membuat label form lebih nyaman dilihat */
        .space-y-3 label {
            padding: 1rem !important;
            font-size: 0.875rem !important;
        }

        /* Input file agar tidak berantakan */
        input[type="file"] {
            font-size: 0.75rem !important;
        }

        /* Tombol aksi jadi stack (bertumpuk) jika layar sangat sempit */
        .flex.gap-3 {
            flex-direction: column !important;
        }
        
        /* Memastikan container maksimal lebar penuh */
        .max-w-2xl {
            width: 100% !important;
            margin: 0 !important;
        }
    }
</style>
@endsection