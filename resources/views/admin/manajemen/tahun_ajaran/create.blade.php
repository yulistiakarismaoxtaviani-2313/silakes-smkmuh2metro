@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-[#F8FAFC] p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER --}}
        <div class="bg-[#004AAD] rounded-t-xl px-8 py-6 shadow-md flex justify-between items-center border-b border-white/10">
            <div>
                <h2 class="text-white font-bold text-xl tracking-wider uppercase">
                    Tambah Tahun Ajaran
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

            <form action="{{ route('admin.tahun-ajaran.store') }}" method="POST" class="p-10 space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- TAHUN AJARAN --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Tahun Ajaran
                        </label>
                        <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran') }}" required
                            class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] transition-all placeholder:text-gray-300 bg-gray-50/30 uppercase" 
                            placeholder="Contoh: 2025/2026">
                        <p class="mt-2 text-[10px] text-slate-400 italic">Format: YYYY/YYYY (misal: 2025/2026)</p>
                    </div>

                    {{-- STATUS --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Status Aktivasi
                        </label>
                        <div class="relative">
                            <select name="status" required
                                class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 appearance-none transition-all cursor-pointer">
                                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        <p class="mt-2 text-[10px] text-blue-800 font-medium italic">* Mengaktifkan ini akan menonaktifkan tahun ajaran lainnya.</p>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex justify-end gap-4 pt-8 border-t border-gray-100 mt-10">
                    <a href="{{ route('admin.tahun-ajaran.index') }}" 
                        class="px-10 py-3.5 border border-gray-300 rounded-xl text-slate-500 text-xs font-bold uppercase tracking-[0.15em] hover:bg-gray-50 transition-all active:scale-95">
                        Batal
                    </a>
                    <button type="submit" 
                        class="px-10 py-3.5 bg-[#004AAD] text-white rounded-xl text-xs font-bold uppercase tracking-[0.15em] shadow-lg shadow-blue-900/20 hover:bg-[#003d8f] hover:-translate-y-0.5 transition-all active:scale-95">
                        Simpan Tahun Ajaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection