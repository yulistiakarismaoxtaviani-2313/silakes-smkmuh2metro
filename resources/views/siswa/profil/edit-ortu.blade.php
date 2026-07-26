@extends('layouts.siswa')

@section('content')
<div class="flex-1 bg-[#F8FAFC] p-4 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    <div class="max-w-3xl mx-auto">
        
        {{-- Card Utama --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            {{-- Header Form --}}
            <div class="bg-[#004AAD] p-6 text-left">
                <h2 class="text-white font-bold text-sm uppercase tracking-[0.2em]">Edit Data Orang Tua</h2>
                <p class="text-blue-100/70 text-[10px] uppercase font-medium mt-1 tracking-wider">Sistem Kesiswaan SMK Muhammadiyah 2 Metro</p>
            </div>

            <form action="{{ route('siswa.profil.updateOrtu') }}" method="POST" class="p-8 md:p-10">
                @csrf
                @method('PUT')

                <div class="space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        {{-- Sektor Ayah --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="h-px flex-1 bg-slate-100"></span>
                                <h3 class="text-[#004AAD] font-bold text-[11px] uppercase tracking-[0.15em]">Data Ayah</h3>
                                <span class="h-px flex-1 bg-slate-100"></span>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Nama Lengkap Ayah</label>
                                <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $user->siswa->profil->nama_ayah ?? '') }}" 
                                       class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all"
                                       placeholder="Nama Ayah">
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Pekerjaan Ayah</label>
                                <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $user->siswa->profil->pekerjaan_ayah ?? '') }}" 
                                       class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all"
                                       placeholder="Pekerjaan Ayah">
                            </div>
                        </div>

                        {{-- Sektor Ibu --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="h-px flex-1 bg-slate-100"></span>
                                <h3 class="text-[#004AAD] font-bold text-[11px] uppercase tracking-[0.15em]">Data Ibu</h3>
                                <span class="h-px flex-1 bg-slate-100"></span>
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Nama Lengkap Ibu</label>
                                <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $user->siswa->profil->nama_ibu ?? '') }}" 
                                       class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all"
                                       placeholder="Nama Ibu">
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Pekerjaan Ibu</label>
                                <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $user->siswa->profil->pekerjaan_ibu ?? '') }}" 
                                       class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all"
                                       placeholder="Pekerjaan Ibu">
                            </div>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div class="bg-blue-50 p-5 rounded-2xl border-l-4 border-[#004AAD]">
                        <p class="text-[10px] text-[#004AAD]/80 leading-relaxed font-bold uppercase tracking-tight">
                            Info: Data ini digunakan untuk keperluan administrasi sekolah dan komunikasi dengan orang tua/wali murid.
                        </p>
                    </div>
                </div>

               {{-- Footer Form / Buttons --}}
                <div class="flex items-center gap-4 pt-6 mt-6 md:pt-10 md:mt-10 border-t border-gray-50">
                    <a href="{{ route('siswa.profil.index') }}" 
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