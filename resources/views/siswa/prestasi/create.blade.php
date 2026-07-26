@extends('layouts.siswa')

@section('content')
<div class="flex-1 bg-[#F8FAFC] p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER --}}
        <div class="bg-[#004AAD] rounded-t-xl px-6 md:px-8 py-5 shadow-md flex justify-between items-center border-b border-white/10">
            <div class="flex items-center gap-4">
                <div>
                    <h2 class="text-white font-bold text-lg md:text-xl tracking-tight leading-none uppercase">
                        Unggah Prestasi Baru
                    </h2>
                    <p class="text-white/60 text-[9px] md:text-[10px] mt-1 uppercase tracking-[0.2em]">
                        Sistem Kesiswaan • SMK Muhammadiyah 2 Metro
                    </p>
                </div>
            </div>
        </div>

        {{-- FORM CARD --}}
        <div class="bg-white rounded-b-xl shadow-xl border-x border-b border-gray-100 overflow-hidden">
            
            @if ($errors->any())
                <div class="bg-red-50 border-b border-red-200 p-6">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle text-red-500 mt-1"></i>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">Terjadi Kesalahan:</h3>
                            <ul class="mt-1 text-xs text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('siswa.prestasi.store') }}" method="POST" enctype="multipart/form-data" class="p-5 md:p-10 space-y-6">
                @csrf

                {{-- INFO PESAN --}}
                <div class="bg-blue-50/50 border border-blue-100 p-4 md:p-5 rounded-xl flex items-start gap-3">
                    <i class="fas fa-info-circle text-[#004AAD] mt-0.5 shrink-0"></i>
                    <p class="text-[11px] md:text-[13px] text-slate-600 leading-relaxed font-normal">
                        Lengkapi semua data di bawah ini. Pastikan bukti prestasi (sertifikat/foto) terlihat jelas agar mudah diverifikasi oleh admin.
                    </p>
                </div>

                {{-- GRID 1: DATA IDENTITAS --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                    <div class="relative">
                        <label class="block text-[11px] font-semibold text-slate-500 capitalize mb-1 ml-1">Nama lengkap</label>
                        <input type="text" value="{{ Auth::user()->nama }}" readonly
                            class="w-full border border-gray-100 rounded-xl px-4 py-3 text-sm text-slate-500 bg-gray-50/50 outline-none font-medium cursor-not-allowed">
                    </div>

                    <div class="relative">
                        <label class="block text-[11px] font-semibold text-slate-500 capitalize mb-1 ml-1">NIS</label>
                        <input type="text" value="{{ $siswa->nis }}" readonly
                            class="w-full border border-gray-100 rounded-xl px-4 py-3 text-sm text-slate-500 bg-gray-50/50 outline-none font-medium cursor-not-allowed">
                    </div>

                    <div class="relative">
                        <label class="block text-[11px] font-semibold text-slate-500 capitalize mb-1 ml-1">Kelas</label>
                        <input type="text" value="{{ $siswa->kelas->nama_kelas ?? 'Tidak Terdaftar' }}" readonly
                            class="w-full border border-gray-100 rounded-xl px-4 py-3 text-sm text-slate-500 bg-gray-50/50 outline-none font-medium cursor-not-allowed">
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- GRID 2: INPUT UTAMA --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="relative">
                        <label class="block text-[11px] font-semibold text-slate-700 capitalize mb-2 ml-1">Kategori prestasi</label>
                        <div class="flex gap-4 p-2 bg-gray-50/50 rounded-xl border border-gray-100">
                            <label class="flex-1 flex items-center justify-center gap-2 cursor-pointer py-2 hover:bg-white rounded-lg transition-all">
                                <input type="radio" name="kategori" value="Akademik" {{ old('kategori') == 'Akademik' ? 'checked' : '' }} required class="w-4 h-4 text-[#004AAD]">
                                <span class="text-xs font-medium text-slate-700">Akademik</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center gap-2 cursor-pointer py-2 hover:bg-white rounded-lg transition-all">
                                <input type="radio" name="kategori" value="Non-Akademik" {{ old('kategori') == 'Non-Akademik' ? 'checked' : '' }} required class="w-4 h-4 text-[#004AAD]">
                                <span class="text-xs font-medium text-slate-700">Non-Akademik</span>
                            </label>
                        </div>
                    </div>

                    <div class="relative">
                        <label class="block text-[11px] font-semibold text-slate-700 capitalize mb-2 ml-1">Nama lomba / kegiatan</label>
                        <input type="text" name="nama_lomba" value="{{ old('nama_lomba') }}" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 font-medium" 
                            placeholder="Contoh: LKS Web Technologies">
                    </div>

                    <div class="relative">
                        <label class="block text-[11px] font-semibold text-slate-700 capitalize mb-2 ml-1">Penyelenggara</label>
                        <input type="text" name="penyelenggara" value="{{ old('penyelenggara') }}" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 font-medium">
                    </div>

                    <div class="relative">
                        <label class="block text-[11px] font-semibold text-slate-700 capitalize mb-2 ml-1">Cabang lomba</label>
                        <input type="text" name="cabang_lomba" value="{{ old('cabang_lomba') }}" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 font-medium">
                    </div>

                    <div class="relative">
                        <label class="block text-[11px] font-semibold text-slate-700 capitalize mb-2 ml-1">Tingkat prestasi</label>
                        <select name="tingkat" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 cursor-pointer font-medium">
                            <option value="Sekolah">Sekolah</option>
                            <option value="Kabupaten">Kabupaten/Kota</option>
                            <option value="Provinsi">Provinsi</option>
                            <option value="Nasional">Nasional</option>
                            <option value="Internasional">Internasional</option>
                        </select>
                    </div>

                    <div class="relative">
                        <label class="block text-[11px] font-semibold text-slate-700 capitalize mb-2 ml-1">Perolehan juara</label>
                        <select name="juara" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 cursor-pointer font-medium">
                            <option value="Juara 1">Juara 1</option>
                            <option value="Juara 2">Juara 2</option>
                            <option value="Juara 3">Juara 3</option>
                            <option value="Harapan">Harapan</option>
                            <option value="Peserta">Peserta / Partisipasi</option>
                        </select>
                    </div>

                    <div class="relative col-span-full">
                        <label class="block text-[11px] font-semibold text-slate-700 capitalize mb-2 ml-1">Tanggal pelaksanaan</label>
                        <input type="date" name="tanggal_lomba" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 font-medium">
                    </div>

                    {{-- UPLOAD BUKTI --}}
                    <div class="relative col-span-full pt-2">
                        <label class="block text-[11px] font-semibold text-slate-700 capitalize mb-3 ml-1">Unggah bukti sertifikat / foto</label>
                        <div class="flex justify-center px-4 pt-8 pb-8 border-2 border-gray-200 border-dashed rounded-xl hover:border-[#004AAD]/50 transition-all bg-gray-50/30">
                            <div class="text-center">
                                <i class="fas fa-file-signature text-slate-300 text-4xl mb-3"></i>
                                <label for="file-upload" class="cursor-pointer bg-white rounded-lg px-4 py-2 shadow-sm border border-gray-100 text-[11px] font-bold text-[#004AAD] hover:bg-gray-50">
                                    <span>Pilih File</span>
                                    <input id="file-upload" name="sertifikat" type="file" class="sr-only" onchange="updateFileName(this)" accept="image/*,.pdf">
                                </label>
                                <p id="file-name" class="mt-3 text-[10px] text-slate-400 font-medium">Maks. 20MB (JPG/PNG/PDF)</p>
                            </div>
                        </div>
                    </div>
                </div>

{{-- ACTION BUTTONS: Menggunakan flex-row agar selalu sejajar ke samping --}}
<div class="flex flex-row justify-end gap-3 pt-6 border-t border-gray-100 mt-6">
    <a href="{{ route('siswa.prestasi.index') }}" 
        class="flex-1 md:flex-none text-center px-6 py-3 border border-gray-300 rounded-xl text-slate-700 text-sm font-semibold hover:bg-gray-50 transition-all active:scale-95">
        Batal
    </a>
    <button type="submit" 
        class="flex-1 md:flex-none px-6 py-3 bg-[#004AAD] text-white rounded-xl text-sm font-semibold shadow-lg shadow-blue-900/20 hover:bg-[#003d8f] transition-all active:scale-95">
        Simpan
    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            const target = document.getElementById('file-name');
            target.innerHTML = `<i class="fas fa-check-circle mr-1 text-green-500"></i> <span class="text-green-600 font-bold uppercase text-[9px]">Terpilih: ${fileName}</span>`;
            target.classList.remove('text-slate-400');
        }
    }
</script>

<style>
  .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    /* Mencegah Zoom Otomatis di iPhone saat input diklik */
    @media screen and (max-width: 768px) {
        input, select, textarea { font-size: 16px !important; }
    }
</style>
@endsection