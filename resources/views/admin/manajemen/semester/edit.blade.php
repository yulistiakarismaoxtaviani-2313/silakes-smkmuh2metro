@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-[#F8FAFC] p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER --}}
        <div class="bg-[#004AAD] rounded-t-xl px-8 py-6 shadow-md flex justify-between items-center border-b border-white/10">
            <div>
                <h2 class="text-white font-bold text-xl tracking-wider uppercase">
                    Edit Data Semester
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

            <form action="{{ route('admin.semester.update', $semester->id_semester) }}" method="POST" class="p-10 space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- NAMA SEMESTER --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Nama Semester
                        </label>
                        <input type="text" name="nama_semester" value="{{ old('nama_semester', $semester->nama_semester) }}" required
                            class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] transition-all bg-gray-50/30 uppercase" 
                            placeholder="Contoh: GANJIL">
                    </div>

                    {{-- STATUS --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Status Aktivasi
                        </label>
                        <div class="relative">
                            <select name="status" required
                                class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 appearance-none transition-all cursor-pointer">
                                <option value="aktif" {{ old('status', $semester->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $semester->status) == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INFO TAHUN AJARAN TERKAIT --}}
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-[#004AAD] p-2 rounded-lg shadow-sm">
                            <i class="fas fa-calendar-alt text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Terhubung Dengan Tahun Ajaran</p>
                            <p class="text-sm font-black text-slate-700 uppercase">
                                {{ $semester->tahunAjaran->tahun_ajaran ?? 'Tidak Terikat' }}
                            </p>
                        </div>
                    </div>
                    <span class="text-[9px] bg-slate-200 text-slate-500 px-3 py-1 rounded-full font-black uppercase tracking-widest">Read Only</span>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex flex-row items-center gap-4 pt-6 mt-4 md:pt-10 md:mt-8 border-t border-gray-50">
                    <a href="{{ route('admin.semester.index') }}" 
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