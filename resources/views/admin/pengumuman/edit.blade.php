@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-[#F8FAFC] p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER (Identik dengan Kelola Kelas) --}}
        <div class="bg-[#004AAD] rounded-t-xl px-8 py-6 shadow-md flex justify-between items-center border-b border-white/10">
            <div>
                <h2 class="text-white font-bold text-xl tracking-wider uppercase">
                    Edit Pengumuman
                </h2>
                <p class="text-white/60 text-[10px] mt-1 uppercase tracking-[0.2em]">Sistem Kesiswaan SMK Muhammadiyah 2 Metro</p>
            </div>
        </div>

        {{-- FORM CARD --}}
        <div class="bg-white rounded-b-xl shadow-xl border-x border-b border-gray-100 overflow-hidden">
            
            {{-- Alert Error --}}
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

            <form action="{{ route('admin.pengumuman.update', $pengumuman->id_pengumuman) }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- JUDUL PENGUMUMAN --}}
                    <div class="relative group md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Judul Pengumuman
                        </label>
                        <input type="text" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required
                            class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] transition-all bg-gray-50/30 uppercase"
                            placeholder="Masukkan Judul Pengumuman">
                    </div>

                    {{-- KATEGORI --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Kategori
                        </label>
                        <select name="kategori" required
                            class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all cursor-pointer">
                            <option value="Akademik" {{ old('kategori', $pengumuman->kategori) == 'Akademik' ? 'selected' : '' }}>AKADEMIK</option>
                            <option value="Kegiatan" {{ old('kategori', $pengumuman->kategori) == 'Kegiatan' ? 'selected' : '' }}>KEGIATAN</option>
                            <option value="Informasi" {{ old('kategori', $pengumuman->kategori) == 'Informasi' ? 'selected' : '' }}>INFORMASI</option>
                        </select>
                    </div>

                    {{-- TARGET AUDIENS --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Target Audiens
                        </label>
                        <select name="target" required
                            class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all cursor-pointer">
                            <option value="semua" {{ old('target', $pengumuman->target) == 'semua' ? 'selected' : '' }}>SEMUA WARGA SEKOLAH</option>
                            <option value="guru" {{ old('target', $pengumuman->target) == 'guru' ? 'selected' : '' }}>KHUSUS GURU</option>
                            <option value="siswa" {{ old('target', $pengumuman->target) == 'siswa' ? 'selected' : '' }}>KHUSUS SISWA</option>
                        </select>
                    </div>

                    {{-- ISI PENGUMUMAN --}}
                    <div class="relative group md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Isi Pesan / Konten
                        </label>
                        <textarea name="isi" rows="6" required
                            class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] transition-all bg-gray-50/30"
                            placeholder="Tuliskan isi pengumuman secara lengkap...">{{ old('isi', $pengumuman->isi) }}</textarea>
                    </div>

                    {{-- STATUS PUBLIKASI --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Status Publikasi
                        </label>
                        <select name="status" required
                            class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all cursor-pointer">
                            <option value="aktif" {{ old('status', $pengumuman->status) == 'aktif' ? 'selected' : '' }}>AKTIF / TAYANG</option>
                            <option value="nonaktif" {{ old('status', $pengumuman->status) == 'nonaktif' ? 'selected' : '' }}>NON-AKTIF / ARSIP</option>
                        </select>
                    </div>

                    {{-- LAMPIRAN FILE --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Update Lampiran (Opsional)
                        </label>
                        <input type="file" name="file_lampiran" 
                            class="w-full border border-gray-200 rounded-xl px-5 py-3 text-xs text-slate-500 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] transition-all bg-gray-50/30 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-[#004AAD] file:text-white hover:file:bg-blue-800 cursor-pointer">
                        @if($pengumuman->file_lampiran)
                            <p class="text-[9px] text-emerald-600 mt-2 italic">* File saat ini: {{ $pengumuman->file_lampiran }}</p>
                        @endif
                    </div>
                </div>

                {{-- INFO (Identik dengan Kelola Kelas) --}}
                <div class="bg-blue-50/50 border-l-4 border-[#004AAD] p-4 rounded-r-xl">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-info-circle text-[#004AAD]"></i>
                        <p class="text-[11px] text-slate-600 font-medium leading-relaxed italic">
                            Pastikan konten pengumuman sudah benar. Perubahan ini akan langsung terlihat oleh target audiens yang dipilih.
                        </p>
                    </div>
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
@endsection