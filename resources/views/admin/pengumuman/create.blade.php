@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-[#F8FAFC] p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER --}}
        <div class="bg-[#004AAD] rounded-t-xl px-8 py-6 shadow-md flex justify-between items-center border-b border-white/10">
            <div>
                <h2 class="text-white font-bold text-xl tracking-wider uppercase">
                    Buat Pengumuman Baru
                </h2>
                <p class="text-white/60 text-[10px] mt-1 uppercase tracking-[0.2em]">Sistem Kesiswaan SMK Muhammadiyah 2 Metro</p>
            </div>
        </div>

        {{-- FORM CARD --}}
        <div class="bg-white rounded-b-xl shadow-xl border-x border-b border-gray-100 overflow-hidden">
            
            @if ($errors->any())
                <div class="bg-red-50 border-b border-red-200 p-6">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle text-red-500 mt-1"></i>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800 uppercase">Terjadi Kesalahan:</h3>
                            <ul class="mt-1 text-xs text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                @csrf

                {{-- TAHUN AJARAN --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        Tahun Ajaran
                    </label>
                    <div class="relative">
                       <input type="text"
        value="{{ $tahunAktif->tahun_ajaran ?? 'N/A' }}"
        readonly
        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 bg-gray-100 cursor-not-allowed font-medium">

    <input type="hidden"
        name="id_tahun_ajaran"
        value="{{ $tahunAktif->id_tahun_ajaran ?? '' }}">
                    </div>
                </div>

                {{-- JUDUL PENGUMUMAN --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        Judul Pengumuman
                    </label>
                    <input type="text" name="judul" value="{{ old('judul') }}" required
                        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] transition-all placeholder:text-gray-300 bg-gray-50/30 uppercase" 
                        placeholder="Contoh: PENGUMUMAN LIBUR SEMESTER">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- KATEGORI --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Kategori
                        </label>
                        <select name="kategori" required
                            class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all cursor-pointer">
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="Akademik">Akademik</option>
                            <option value="Kegiatan">Kegiatan</option>
                            <option value="Informasi">Informasi</option>
                        </select>
                    </div>

                    {{-- TARGET --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Target
                        </label>
                        <select name="target" id="target_select" required
                            class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all cursor-pointer">
                            <option value="semua">Semua</option>
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                            <option value="kelas">Per Kelas</option>
                        </select>
                    </div>
                </div>

                {{-- PILIH KELAS (Hidden by Default) --}}
                <div id="wrapper_pilih_kelas" class="hidden relative group animate-fade-in">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        Pilih Kelas Spesifik
                    </label>
                    <select name="id_kelas"
                        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all cursor-pointer">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- ISI PENGUMUMAN --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        Isi Pengumuman
                    </label>
                    <textarea name="isi" rows="5" required
                        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] transition-all bg-gray-50/30"
                        placeholder="Tuliskan isi pengumuman..."></textarea>
                </div>

                {{-- UPLOAD FILE --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        Lampiran Unggah File / PDF / Gambar (Opsional)
                    </label>
                    <div class="relative border-2 border-dashed border-gray-200 rounded-xl p-8 flex flex-col items-center justify-center bg-gray-50/30 hover:bg-gray-50 transition-all cursor-pointer group-focus-within:border-[#004AAD]">
                        <input type="file" name="file_lampiran" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-slate-300 mb-2 group-hover:text-[#004AAD] transition-colors"></i>
                        <p class="text-[10px] text-slate-400 font-medium">Unggah lampiran anda dapat berupa file / PDF / Gambar</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- TANGGAL DIBUAT --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Tanggal Dibuat
                        </label>
                        <input type="date" name="tanggal_dibuat" value="{{ date('Y-m-d') }}" required
                            class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all">
                    </div>

                    {{-- TANGGAL TAYANG --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Tanggal Tayang
                        </label>
                        <input type="date" name="tanggal_tayang" required
                            class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all">
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        Status
                    </label>
                    <select name="status" required
                        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all cursor-pointer">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex flex-row items-center gap-4 pt-6 mt-4 md:pt-10 md:mt-8 border-t border-gray-50">
                    <a href="{{ route('admin.pengumuman.index') }}" 
                    class="flex-1 px-4 py-4 bg-slate-100 text-slate-500 rounded-xl font-bold text-[10px] md:text-xs capitalize tracking-widest hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" 
                    class="flex-1 bg-[#004AAD] text-white py-4 px-4 rounded-xl font-bold text-[10px] md:text-xs capitalize tracking-widest hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/10 active:scale-[0.98]">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('target_select').addEventListener('change', function() {
        const wrapper = document.getElementById('wrapper_pilih_kelas');
        if (this.value === 'kelas') {
            wrapper.classList.remove('hidden');
        } else {
            wrapper.classList.add('hidden');
        }
    });
</script>

<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection