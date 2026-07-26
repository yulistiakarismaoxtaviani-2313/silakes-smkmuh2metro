@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-[#F8FAFC] p-0 md:p-8 overflow-y-auto custom-scrollbar">
    
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER --}}
        <div class="bg-[#004AAD] rounded-t-xl px-8 py-6 shadow-md flex justify-between items-center border-b border-white/10">
            <div>
                <h2 class="text-white font-bold text-xl tracking-wider uppercase">
                    Tambah Kelas Baru
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

            <form action="{{ route('admin.kelas.store') }}" method="POST" class="p-10 space-y-8">
                @csrf

                {{-- PROGRAM KEAHLIAN (JURUSAN) --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        Program Keahlian (Jurusan)
                    </label>
                    <div class="relative">
                        <select name="id_program_keahlian" required
                            class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 appearance-none transition-all cursor-pointer">
                            <option value="" disabled selected>-- Pilih Program Keahlian --</option>
                            @foreach($data_jurusan as $j)
                                <option value="{{ $j->id_program_keahlian }}" {{ old('id_program_keahlian') == $j->id_program_keahlian ? 'selected' : '' }}>
                                    {{ $j->nama_program }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- NAMA KELAS --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        Nama Kelas
                    </label>
                    <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}" required
                        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] transition-all placeholder:text-gray-300 bg-gray-50/30 uppercase" 
                        placeholder="Contoh: X TKJ 2">
                </div>

                {{-- WALI KELAS --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        Pilih Wali Kelas
                    </label>
                    <div class="relative">
                        <select name="id_guru" required
                            class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 appearance-none transition-all cursor-pointer">
                            <option value="" disabled selected>-- Pilih Guru --</option>
                            @foreach($data_guru as $guru)
                                <option value="{{ $guru->id_guru }}" {{ old('id_guru') == $guru->id_guru ? 'selected' : '' }}>
                                    {{ $guru->user?->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- TINGKAT --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Tingkat
                        </label>
                        <select name="tingkat" required
                            class="w-full border border-gray-300 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 appearance-none transition-all cursor-pointer">
                            <option value="X" {{ old('tingkat') == 'X' ? 'selected' : '' }}>X (SEPULUH)</option>
                            <option value="XI" {{ old('tingkat') == 'XI' ? 'selected' : '' }}>XI (SEBELAS)</option>
                            <option value="XII" {{ old('tingkat') == 'XII' ? 'selected' : '' }}>XII (DUA BELAS)</option>
                        </select>
                    </div>

                    {{-- STATUS --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Status Kelas
                        </label>
                        <select name="status" required
                            class="w-full border border-gray-300 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all cursor-pointer">
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>AKTIF</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>NON-AKTIF</option>
                        </select>
                    </div>
                </div>

                {{-- INFO --}}
                <div class="bg-blue-50/50 border-l-4 border-[#004AAD] p-4 rounded-r-xl">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-info-circle text-[#004AAD]"></i>
                        <p class="text-[11px] text-slate-600 font-medium leading-relaxed italic">
                            Data tahun ajaran dan semester akan otomatis mengikuti periode yang sedang aktif saat ini.
                        </p>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex flex-row items-center gap-4 pt-6 mt-4 md:pt-10 md:mt-8 border-t border-gray-50">
                    <a href="{{ route('admin.kelas.index') }}" 
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
@endsection