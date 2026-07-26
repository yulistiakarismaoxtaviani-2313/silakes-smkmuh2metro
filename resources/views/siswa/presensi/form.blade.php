@extends('layouts.siswa')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-blue-800 p-4 text-center">
            <h3 class="text-white font-bold text-lg uppercase">Presensi Kelas</h3>
        </div>

        <div class="p-6">
            <div class="text-center mb-8">
                <h4 class="font-black text-blue-900 text-2xl uppercase">{{ $sesi->jam_pelajaran }}</h4>
                <div class="mt-2 p-3 bg-blue-50 rounded-lg border border-blue-100 inline-block w-full">
                    <p class="text-sm text-blue-800">Tanggal Presensi : <b>{{ \Carbon\Carbon::parse($sesi->tanggal)->isoFormat('dddd, D MMMM Y') }}</b></p>
                    <p class="text-sm text-blue-800">Waktu Presensi : <b>{{ \Carbon\Carbon::parse($sesi->waktu_dibuka)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->waktu_ditutup)->format('H:i') }} WIB</b></p>
                </div>
            </div>

            <form action="{{ route('siswa.presensi.store') }}" method="POST" enctype="multipart/form-data" x-data="{ status: 'hadir' }">
                @csrf
                <input type="hidden" name="id_presensi" value="{{ $sesi->id_presensi }}">

                <div class="grid grid-cols-1 gap-3 mb-6">
                    @foreach(['hadir', 'alfa', 'izin', 'sakit'] as $st)
                    <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all"
                        :class="status === '{{ $st }}' ? 'border-blue-600 bg-blue-50' : 'border-gray-100 hover:border-blue-200'">
                        <input type="radio" name="status" value="{{ $st }}" x-model="status" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="ml-3 font-bold text-gray-700 uppercase">{{ ucfirst($st) }}</span>
                    </label>
                    @endforeach
                </div>

                <div x-show="status === 'izin' || status === 'sakit'" x-transition class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload file (Surat izin/dokter/kegiatan)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 00-4 4H12a4 4 0 00-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 005.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file_bukti" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Upload File</span>
                                    <input id="file_bukti" name="file_bukti" type="file" class="sr-only">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 italic text-blue-400" id="file-name">PNG, JPG, PDF up to 2MB</p>
                        </div>
                    </div>
                    <textarea name="keterangan" rows="3" class="mt-3 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Tambahkan keterangan alasan..."></textarea>
                </div>

                <div class="flex gap-4 mt-8">
                    <a href="{{ route('siswa.presensi.index') }}" class="flex-1 text-center py-3 border-2 border-gray-200 rounded-xl font-bold text-gray-500 hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="flex-1 py-3 bg-blue-800 rounded-xl font-bold text-white hover:bg-blue-900 shadow-lg shadow-blue-200 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script sederhana untuk menampilkan nama file yang diupload
    document.getElementById('file_bukti').onchange = function () {
        document.getElementById('file-name').innerHTML = this.files[0].name;
    };
</script>
@endsection