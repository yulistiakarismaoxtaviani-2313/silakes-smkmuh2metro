@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-[#F8FAFC] p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER --}}
        <div class="bg-[#004AAD] rounded-t-xl px-8 py-6 shadow-md flex justify-between items-center border-b border-white/10">
            <div>
                <h2 class="text-white font-bold text-xl tracking-wider uppercase">
                    Tambah Presensi Kelas
                </h2>
                <p class="text-white/60 text-[10px] mt-1 uppercase tracking-[0.2em]">Sistem Kesiswaan SMK Muhammadiyah 2 Metro</p>
            </div>
        </div>

        {{-- FORM CARD --}}
        <div class="bg-white rounded-b-xl shadow-xl border-x border-b border-gray-100 overflow-hidden">
            
            {{-- AREA NOTIFIKASI / ERROR --}}
            @if(session('error') || $errors->any())
                <div class="bg-red-50 border-b border-red-200 p-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-500 mt-1"></i>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800 uppercase">Terjadi Kesalahan:</h3>
                            <ul class="mt-1 text-xs text-red-700 list-disc list-inside space-y-1">
                                @if(session('error'))
                                    <li>{{ session('error') }}</li>
                                @endif
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.presensi.store') }}" method="POST" class="p-10 space-y-8">
                @csrf

                {{-- GRID RENTANG TANGGAL --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- DARI TANGGAL --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Dari Tanggal
                        </label>
                        <input type="date" name="tanggal_mulai" required value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                            class="clean-date-input w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all font-medium">
                    </div>

                    {{-- SAMPAI TANGGAL --}}
                    <div class="relative group">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                            Sampai Tanggal
                        </label>
                        <input type="date" name="tanggal_selesai" required value="{{ old('tanggal_selesai', date('Y-m-d')) }}"
                            class="clean-date-input w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all font-medium">
                    </div>
                </div>

                {{-- BERLAKU UNTUK --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1">
                        Berlaku Untuk
                    </label>
                    <div class="relative">
                        <select disabled class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-400 bg-gray-100 cursor-not-allowed font-medium appearance-none">
                            <option value="semua" selected>Semua Kelas (Otomatis Menyesuaikan Master Jadwal)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                            <i class="fas fa-lock text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- KETERANGAN TAMBAHAN --}}
                <div class="relative group">
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-[#004AAD]">
                        Keterangan Sesi (Opsional)
                    </label>
                    <textarea name="keterangan" rows="2" placeholder="Contoh: Presensi Bulanan Rutin"
                        class="w-full border border-gray-200 rounded-xl px-5 py-4 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#004AAD]/10 focus:border-[#004AAD] bg-gray-50/30 transition-all font-medium resize-none">{{ old('keterangan') }}</textarea>
                </div>

                {{-- INFO BOX AUTOMATION --}}
                <div class="bg-blue-50/50 border-l-4 border-[#004AAD] p-4 rounded-r-xl">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-[#004AAD] mt-0.5"></i>
                        <div class="text-[11px] text-slate-600 font-medium leading-relaxed">
                            <span class="font-bold text-[#004AAD]">Sistem mendeteksi Pengaturan Otomatis Terjadwal:</span>
                            <ul class="list-disc list-inside mt-2 space-y-1 text-slate-500">
                                <li>Hari <span class="font-semibold text-red-500">Minggu akan dilewati secara otomatis</span>.</li>
                                <li>Jam presensi menyesuaikan data di <span class="font-semibold text-slate-700">Kelola Jadwal</span>.</li>
                                <li>Otomatis hanya menyertakan siswa di kelas bersangkutan.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex justify-end gap-4 pt-8 border-t border-gray-100 mt-10">
                    <a href="{{ route('admin.presensi.index') }}" 
                        class="px-10 py-3.5 border border-gray-300 rounded-xl text-slate-500 text-xs font-bold capitalize tracking-[0.15em] hover:bg-gray-50 transition-all active:scale-95">
                        Batal
                    </a>
                    <button type="submit" 
                        class="px-10 py-3.5 bg-[#004AAD] text-white rounded-xl text-xs font-bold capitalize tracking-[0.15em] shadow-lg shadow-blue-900/20 hover:bg-[#003d8f] hover:-translate-y-0.5 transition-all active:scale-95">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .clean-date-input::-webkit-calendar-picker-indicator {
        cursor: pointer;
        padding: 4px;
        opacity: 0.5;
        transition: opacity 0.2s;
    }
    .clean-date-input::-webkit-calendar-picker-indicator:hover {
        opacity: 0.9;
    }
</style>
@endsection