@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-[#F8FAFC] p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER --}}
        <div class="bg-[#004AAD] rounded-t-xl px-8 py-6 shadow-md flex justify-between items-center border-b border-white/10">
            <div>
                <h2 class="text-white font-bold text-xl tracking-wider uppercase">
                    Tambah Guru Baru
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

            <form action="{{ route('admin.guru.store') }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                @csrf

                {{-- NBM --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        NBM (Nomor Baku Muhammadiyah)
                    </label>
                    <input type="text" name="nip" value="{{ old('nip') }}" required
                        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] transition-all placeholder:text-gray-300 bg-gray-50/30" 
                        placeholder="Contoh: 19880221...">
                </div>

                {{-- NAMA --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        Nama Lengkap Guru
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] transition-all placeholder:text-gray-300 uppercase bg-gray-50/30" 
                        placeholder="Masukkan nama lengkap beserta gelar...">
                </div>

                {{-- JENIS KELAMIN --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-3 ml-1">
                        Jenis Kelamin
                    </label>
                    <div class="flex gap-8 p-4 bg-gray-50/50 rounded-xl border border-gray-100 w-fit">
                        <label class="flex items-center gap-3 cursor-pointer group/radio">
                            <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required 
                                class="w-5 h-5 text-[#004AAD] border-gray-300 focus:ring-[#004AAD]">
                            <span class="text-sm font-semibold text-slate-600 group-hover/radio:text-[#004AAD] transition-colors">Laki-laki</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group/radio">
                            <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required 
                                class="w-5 h-5 text-[#004AAD] border-gray-300 focus:ring-[#004AAD]">
                            <span class="text-sm font-semibold text-slate-600 group-hover/radio:text-[#004AAD] transition-colors">Perempuan</span>
                        </label>
                    </div>
                </div>

                {{-- MATA PELAJARAN --}}
<div x-data="{ openMapel: false }" class="relative group">
    
    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1">
        Mata Pelajaran
    </label>

    {{-- Tombol Dropdown --}}
    <button type="button"
        @click="openMapel = !openMapel"
        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 bg-gray-50/30 flex justify-between items-center">

        <span>Pilih Mata Pelajaran</span>

        <i class="fas fa-chevron-down text-slate-400"></i>
    </button>

    {{-- Isi Dropdown --}}
    <div x-show="openMapel"
         @click.away="openMapel = false"
         x-transition
         class="absolute z-50 mt-2 w-full bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto p-3">

        @foreach($mapels as $mapel)
            <label class="flex items-center gap-3 py-2 px-2 hover:bg-slate-50 rounded-lg cursor-pointer">
                
                <input type="checkbox"
                       name="mapel[]"
                       value="{{ $mapel->id_mapel }}"
                       {{ in_array($mapel->id_mapel, old('mapel', [])) ? 'checked' : '' }}>

                <span class="text-sm text-slate-700">
                    {{ $mapel->nama_mapel }}
                </span>

            </label>
        @endforeach

    </div>
</div>

                {{-- STATUS KEAKTIFAN --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        Status Keaktifan
                    </label>
                    <select name="status" required
                        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all cursor-pointer">
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>AKTIF</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>NON-AKTIF</option>
                    </select>
                </div>

                {{-- FOTO --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1">
                        Foto Profil Guru (Opsional)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-xl hover:border-[#004AAD]/50 transition-all bg-gray-50/30">
                        <div class="space-y-2 text-center">
                            <i class="fas fa-image text-slate-300 text-4xl mb-2"></i>
                            <div class="flex text-sm text-slate-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-bold text-[#004AAD] hover:underline focus-within:outline-none">
                                    <span>Klik untuk upload</span>
                                    <input id="file-upload" name="foto" type="file" class="sr-only" onchange="updateFileName(this)">
                                </label>
                                <p class="pl-1 text-slate-400 font-medium">atau tarik gambar</p>
                            </div>
                            <p id="file-name" class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">PNG, JPG, JPEG (Maks. 2MB)</p>
                        </div>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex flex-row items-center gap-4 pt-6 mt-4 md:pt-10 md:mt-8 border-t border-gray-50">
                    <a href="{{ route('admin.guru.index') }}" 
                        class="flex-1 px-4 py-4 bg-slate-100 text-slate-500 rounded-xl font-bold text-[10px] md:text-xs capitalize tracking-widest hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" 
                        class="flex-1 bg-[#004AAD] text-white py-4 px-4 rounded-xl font-bold text-[10px] md:text-xs capitalize tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-900/10 active:scale-[0.98]">
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
            target.textContent = "File terpilih: " + fileName;
            target.classList.remove('text-slate-400');
            target.classList.add('text-green-600');
        }
    }
</script>
@endsection