@extends('layouts.siswa')

@section('content')
<div id="konten-form" class="flex-1 bg-[#F8FAFC] p-4 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    <div class="max-w-3xl mx-auto">
        


        {{-- Card Utama --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            {{-- Header Form --}}
            <div class="bg-[#004AAD] p-4 text-left">
                <h2 class="text-white font-bold text-sm uppercase tracking-[0.2em]">Edit Data Pribadi</h2>
                <p class="text-blue-100/70 text-[10px] uppercase font-medium mt-1 tracking-wider">Sistem Kesiswaan SMK Muhammadiyah 2 Metro</p>
            </div>

            <form action="{{ route('siswa.profil.updatePribadi') }}" method="POST" class="p-8 md:p-10">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ $user->nama }}" 
                               class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all"
                               placeholder="Masukkan nama lengkap">
                    </div>

                    {{-- Grid: Tempat & Tanggal Lahir --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ $user->siswa->profil->tempat_lahir ?? '' }}" 
                                   class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ $user->siswa->profil->tanggal_lahir ?? '' }}" 
                                   class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Jenis Kelamin & Agama --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 block">Jenis Kelamin</label>
                            <div class="flex gap-4">
                                <label class="flex-1 flex items-center justify-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:bg-blue-50 transition-all">
                                    <input type="radio" name="jenis_kelamin" value="L" class="w-4 h-4 text-[#004AAD] focus:ring-0" {{ ($user->siswa->jenis_kelamin ?? '') == 'L' ? 'checked' : '' }}>
                                    <span class="text-xs font-bold text-slate-600 capitalize">Laki-laki</span>
                                </label>
                                <label class="flex-1 flex items-center justify-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:bg-blue-50 transition-all">
                                    <input type="radio" name="jenis_kelamin" value="P" class="w-4 h-4 text-[#004AAD] focus:ring-0" {{ ($user->siswa->jenis_kelamin ?? '') == 'P' ? 'checked' : '' }}>
                                    <span class="text-xs font-bold text-slate-600 capitalize">Perempuan</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Agama</label>
                            <select name="agama" class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] outline-none transition-all appearance-none">
                                <option value="Islam" {{ ($user->siswa->profil->agama ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ ($user->siswa->profil->agama ?? '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ ($user->siswa->profil->agama ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ ($user->siswa->profil->agama ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Budha" {{ ($user->siswa->profil->agama ?? '') == 'Budha' ? 'selected' : '' }}>Budha</option>
                            </select>
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Alamat Lengkap</label>
                        <textarea name="alamat_siswa" rows="3" 
                                  class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all mt-0.5"
                                  placeholder="Masukkan alamat lengkap">{{ $user->siswa->profil->alamat_siswa ?? '' }}</textarea>
                    </div>
                </div>

                {{-- Footer Form / Buttons --}}
                <div class="flex flex-row items-center gap-4 pt-6 mt-4 md:pt-10 md:mt-8 border-t border-gray-50">
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

<style>
    @media (max-width: 767px) {
        #konten-form .p-8 { padding: 1rem !important; }
        
        /* Membuat form lebih kompak di mobile */
        #konten-form .space-y-6 { space-y: 1rem !important; }
        
        /* Memastikan button full width di mobile sudah diatur di class HTML-nya */
    }
</style>
@endsection