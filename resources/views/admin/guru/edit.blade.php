@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-[#F8FAFC] p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER --}}
        <div class="bg-[#004AAD] rounded-t-xl px-8 py-6 shadow-md flex justify-between items-center border-b border-white/10">
            <div>
                <h2 class="text-white font-bold text-xl tracking-wider uppercase">
                    Edit Data Guru
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

            <form action="{{ route('admin.guru.update', $guru->id_guru) }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                @csrf
                @method('PUT')

                {{-- NBM (Read Only) --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">
                        NBM (Tidak Dapat Diubah)
                    </label>
                    <input type="text" value="{{ $guru->nip }}" readonly
                        class="w-full border border-gray-100 rounded-xl px-5 py-4 text-sm text-slate-400 bg-gray-50 cursor-not-allowed">
                </div>

                {{-- NAMA --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        Nama Lengkap Guru
                    </label>
                    <input type="text" name="nama" value="{{ old('nama', $guru->user->nama ?? '') }}" required
                        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] transition-all bg-gray-50/30 uppercase">
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

        <span>
            {{ $guru->mapel->count() }} Mata Pelajaran Dipilih
        </span>

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
                       {{ $guru->mapel->contains('id_mapel', $mapel->id_mapel) ? 'checked' : '' }}
                       class="text-[#004AAD]">

                <span class="text-sm text-slate-700">
                    {{ $mapel->nama_mapel }}
                </span>
            </label>
        @endforeach

    </div>
</div>

                {{-- JENIS KELAMIN --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-3 ml-1">
                        Jenis Kelamin
                    </label>
                    <div class="flex gap-8 p-4 bg-gray-50/50 rounded-xl border border-gray-100 w-fit">
                        <label class="flex items-center gap-3 cursor-pointer group/radio">
                            <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'checked' : '' }}
                                class="w-5 h-5 text-[#004AAD] border-gray-300 focus:ring-[#004AAD]">
                            <span class="text-sm font-semibold text-slate-600 group-hover/radio:text-[#004AAD]">Laki-laki</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group/radio">
                            <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'checked' : '' }}
                                class="w-5 h-5 text-[#004AAD] border-gray-300 focus:ring-[#004AAD]">
                            <span class="text-sm font-semibold text-slate-600 group-hover/radio:text-[#004AAD]">Perempuan</span>
                        </label>
                    </div>
                </div>

                {{-- STATUS KEAKTIFAN --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        Status Keaktifan
                    </label>
                    <select name="status" required
                        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all cursor-pointer">
                        <option value="aktif" {{ old('status', $guru->status) == 'aktif' ? 'selected' : '' }}>AKTIF</option>
                        <option value="nonaktif" {{ old('status', $guru->status) == 'nonaktif' ? 'selected' : '' }}>NON-AKTIF</option>
                    </select>
                </div>

                {{-- FOTO --}}
                <div class="relative group">
                    <label class="block text-[8px] md:text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1">
                        Foto Profil Baru (Kosongkan jika tidak diganti)
                    </label>
                    <div class="flex flex-col md:flex-row items-center gap-4 p-4 bg-gray-50/30 border-2 border-dashed border-gray-200 rounded-xl">
                        <div class="shrink-0">
                            @if($guru->foto)
                                <img src="{{ asset('storage/profil/' . $guru->foto) }}" class="w-16 h-16 rounded-lg object-cover border-2 border-white shadow-sm">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-chalkboard-teacher text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="foto" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#004AAD]/10 file:text-[#004AAD] hover:file:bg-[#004AAD]/20 cursor-pointer">
                            <p class="mt-2 text-[10px] text-slate-400 font-medium uppercase tracking-wider">Maks. 2MB (PNG, JPG, JPEG)</p>
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
                        class="flex-1 bg-[#004AAD] text-white py-4 px-4 rounded-xl font-bold text-[10px] md:text-xs capitalize tracking-widest hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/10 active:scale-[0.98]">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection