@extends('layouts.siswa')

@section('content')
<div id="konten-kontak" class="flex-1 bg-[#F8FAFC] p-4 md:p-8 overflow-y-auto custom-scrollbar font-sans">
    <div class="max-w-3xl mx-auto">

        {{-- Card Utama --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            {{-- Header Form --}}
            <div class="bg-[#004AAD] p-6 text-left">
                <h2 class="text-white font-bold text-sm uppercase tracking-[0.2em]">Edit Kontak</h2>
                <p class="text-blue-100/70 text-[10px] uppercase font-medium mt-1 tracking-wider">Sistem Kesiswaan SMK Muhammadiyah 2 Metro</p>
            </div>

            <form action="{{ route('siswa.profil.updateKontak') }}" method="POST" class="p-8 md:p-10">
                @csrf
                @method('PUT')

                <div class="space-y-8">
                    {{-- Nomor HP --}}
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Nomor HP / WhatsApp</label>
                        <div class="flex items-center bg-slate-50 border border-slate-100 rounded-xl focus-within:bg-white focus-within:border-[#004AAD] focus-within:ring-4 focus-within:ring-blue-50 transition-all overflow-hidden">
                            <span class="pl-4 pr-2 text-sm font-bold text-slate-400 border-r border-slate-200">+62</span>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $user->siswa->profil->no_hp ?? '') }}" 
                                   class="w-full py-3 px-3 text-sm text-slate-700 outline-none bg-transparent" 
                                   placeholder="8123456xxx">
                        </div>
                        @error('no_hp') <p class="text-red-500 text-[10px] mt-2 font-bold uppercase tracking-tight italic">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 text-sm text-slate-700 focus:bg-white focus:border-[#004AAD] focus:ring-4 focus:ring-blue-50 outline-none transition-all"
                               placeholder="nama@email.com">
                        @error('email') <p class="text-red-500 text-[10px] mt-2 font-bold uppercase tracking-tight italic">{{ $message }}</p> @enderror
                    </div>

                    {{-- Informasi --}}
                    <div class="bg-blue-50 p-5 rounded-2xl border-l-4 border-[#004AAD]">
                        <p class="text-[10px] text-[#004AAD]/80 leading-relaxed font-bold uppercase tracking-tight">
                            Info: Pastikan nomor dan email aktif agar sekolah dapat menghubungi anda terkait informasi akademik.
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

<style>
    @media (max-width: 767px) {
        #konten-kontak .space-y-8 { space-y: 1.5rem !important; }
    }
</style>
@endsection