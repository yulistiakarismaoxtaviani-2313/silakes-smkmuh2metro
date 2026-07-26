@extends('layouts.siswa')

@section('content')
{{-- Container Utama dengan Alpine.js Data --}}
<div id="konten-utama" class="flex-1 bg-[#F8FAFC] p-8 overflow-y-auto custom-scrollbar font-sans"
     x-data="{ 
        showModal: false, 
        imgFull: '', 
        imageUrl: null,
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                this.imageUrl = URL.createObjectURL(file);
            }
        }
     }">
    
    <div class="max-w-4xl mx-auto">
        
        {{-- Alert Success --}}
        @if(session('success'))
            <div class="mb-8 flex items-center gap-4 bg-emerald-50 border border-emerald-100 p-5 rounded-xl shadow-sm">
                <div class="bg-emerald-600 p-2 rounded-lg">
                    <i class="fas fa-check text-white text-sm"></i>
                </div>
                <div>
                    <h4 class="text-emerald-800 font-bold text-sm tracking-tight">Berhasil!</h4>
                    <p class="text-emerald-600/80 text-xs font-medium mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Card Utama --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="flex flex-col md:flex-row">
                
                {{-- SISI KIRI: PROFIL SAAT INI (SOLID BLUE - NO GRAPHICS) --}}
                <div class="md:w-[35%] bg-[#004AAD] p-10 text-center flex flex-col justify-center items-center text-white relative">
                    <div class="relative z-10 w-full">
                        <span class="px-3 py-1 bg-white/10 rounded-full text-blue-100 text-[10px] font-bold uppercase tracking-widest border border-white/20 mb-8 inline-block">
                            Foto Profil Aktif
                        </span>
                        
                        {{-- Foto Klik-able --}}
                        <div class="relative inline-block p-1 bg-white rounded-2xl shadow-2xl mb-6 transition-all hover:scale-105 group cursor-zoom-in"
                             @click="showModal = true; imgFull = '{{ asset('storage/profil/' . (Auth::user()->photo ?? 'default.png')) }}'">
                            <img src="{{ asset('storage/profil/' . (Auth::user()->photo ?? 'default.png')) }}" 
                                 class="w-32 h-40 object-cover rounded-xl shadow-inner">
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity rounded-xl">
                                <i class="fas fa-search-plus text-white text-xl"></i>
                            </div>
                        </div>
                        
                        <h2 class="text-lg font-bold tracking-tight leading-tight uppercase">{{ Auth::user()->nama }}</h2>
                        <p class="text-blue-100/70 text-[11px] font-medium mt-2 uppercase tracking-widest">NIS: {{ Auth::user()->siswa->nis ?? '-' }}</p>
                    </div>
                </div>

                {{-- SISI KANAN: FORM UPDATE --}}
                <div class="md:w-[65%] p-10 md:p-12 bg-white">
                    <div class="mb-10 border-b border-gray-50 pb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-camera text-[#004AAD] text-sm"></i>
                            </div>
                            <h3 class="text-slate-800 font-bold text-sm tracking-tight uppercase">Update Foto Profil</h3>
                        </div>
                        <p class="text-slate-400 text-xs font-medium">Unggah foto terbaru untuk memperbarui identitas digital anda.</p>
                    </div>

                    <form action="{{ route('siswa.profil.updateFoto') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="space-y-8">
                            {{-- Area Upload --}}
                            <div class="relative">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 italic">Format: JPG, PNG (Max 2MB)</label>
                                <input type="file" name="foto" id="foto-input" class="hidden" @change="previewImage" accept="image/*" required>
                                
                                <label for="foto-input" class="flex items-center gap-6 p-6 border-2 border-dashed border-slate-200 rounded-2xl hover:border-[#004AAD] hover:bg-blue-50/30 transition-all cursor-pointer group">
                                    {{-- Preview Square --}}
                                    <div class="w-20 h-24 bg-slate-50 rounded-xl overflow-hidden shrink-0 border border-slate-100 shadow-sm relative">
                                        <template x-if="imageUrl">
                                            <img :src="imageUrl" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!imageUrl">
                                            <div class="w-full h-full flex items-center justify-center opacity-20 text-[#004AAD]">
                                                <i class="fas fa-images text-3xl"></i>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="flex-1">
                                        <p class="font-bold text-slate-700 text-sm group-hover:text-[#004AAD] transition-colors uppercase tracking-tight">Cari File Foto...</p>
                                        <p class="text-[10px] text-slate-400 mt-1 font-semibold uppercase italic tracking-tighter">Klik untuk memilih file</p>
                                    </div>

                                    <div class="bg-blue-50 text-[#004AAD] group-hover:bg-[#004AAD] group-hover:text-white w-10 h-10 rounded-full flex items-center justify-center transition-all shadow-sm">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                </label>
                                @error('foto') <span class="text-red-500 text-[10px] mt-2 block font-bold uppercase italic tracking-tight">{{ $message }}</span> @enderror
                            </div>

                            {{-- Hint Box --}}
                            <div class="flex gap-4 p-4 bg-blue-50 border-l-4 border-[#004AAD] rounded-r-xl rounded-l-md">
                                <div class="shrink-0 text-[#004AAD] mt-0.5">
                                    <i class="fas fa-info-circle text-sm"></i>
                                </div>
                                <p class="text-[10px] text-[#004AAD]/80 leading-relaxed font-bold uppercase tracking-tight">
                                    PENTING: Gunakan foto resmi berseragam sekolah agar mudah diverifikasi oleh guru dan staf.
                                </p>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-4 pt-4">
    {{-- Tambahkan 'w-full' atau 'min-w-[120px]' agar tombol Simpan punya ukuran tetap --}}
    <button type="submit" class="flex-1 min-w-[120px] bg-[#004AAD] text-white py-4 px-6 rounded-xl font-bold text-xs uppercase tracking-[0.2em] hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/10 active:scale-[0.98]">
        Simpan
    </button>
    
    {{-- Tambahkan 'min-w-[120px]' agar tombol Batal tidak terlihat terlalu kecil atau tidak rata --}}
    <a href="{{ route('siswa.profil.index') }}" class="flex-1 min-w-[120px] text-center px-8 py-4 bg-slate-100 text-slate-500 rounded-xl font-bold text-xs uppercase tracking-[0.2em] hover:bg-slate-200 transition-all border border-slate-200">
        Batal
    </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ZOOM --}}
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-[999] flex items-center justify-center bg-black/95 p-6"
         style="display: none;"
         @keydown.escape.window="showModal = false">
        
        <button @click="showModal = false" class="absolute top-8 right-8 text-white/50 hover:text-white transition-colors">
            <i class="fas fa-times text-2xl"></i>
        </button>

        <img :src="imgFull" 
             class="max-w-full max-h-[85vh] rounded-xl shadow-2xl border-4 border-white/10"
             @click.away="showModal = false">
    </div>

</div>

<style>
    @media (max-width: 767px) {
        /* 1. Reset Container Utama agar Full-Width */
        #konten-utama { padding: 0 !important; }
        #konten-utama .max-w-4xl { max-width: 100% !important; margin: 0 !important; }
        
        /* 2. Kartu Utama Full-Width (Tanpa Radius Samping) */
        #konten-utama .bg-white.rounded-2xl { 
            border-radius: 0 !important; 
            margin: 0 !important; 
            width: 100% !important;
            box-shadow: none !important;
        }

        /* 3. Container Biru: Full-Width + Elemen Sejajar Tengah */
        #konten-utama .bg-\[#004AAD\] {
            width: 100% !important;
            border-radius: 0 !important;
            margin: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            align-items: center !important;
            padding: 2rem 1rem !important;
        }

        #konten-utama .bg-\[#004AAD\] .relative { margin: 0 auto 1rem auto !important; }
        #konten-utama .bg-\[#004AAD\] h2, 
        #konten-utama .bg-\[#004AAD\] p { margin: 0 !important; text-align: center !important; width: 100% !important; }
        #konten-utama .bg-\[#004AAD\] span { margin-bottom: 1rem !important; align-self: center !important; }

        /* 4. Area Upload: Compact & Vertikal */
        #konten-utama label[for="foto-input"] {
            flex-direction: column !important;
            gap: 1rem !important;
            text-align: center !important;
            padding: 1.5rem !important;
        }
        #konten-utama label[for="foto-input"] .group-hover\:bg-\[\#004AAD\] { margin-top: 0.5rem !important; }
        #konten-utama label[for="foto-input"] .flex-1 { width: 100% !important; }

        /* 5. Tombol Simpan & Batal: Sejajar Horizontal */
        #konten-utama .flex.items-center.gap-4.pt-4 {
            display: flex !important;
            flex-direction: row !important;
            width: 100% !important;
            gap: 0.5rem !important;
        }
        #konten-utama .flex.items-center.gap-4.pt-4 > * { flex: 1 !important; }
    }
</style>
@endsection