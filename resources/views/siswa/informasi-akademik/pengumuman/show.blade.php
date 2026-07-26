@extends('layouts.siswa')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans">
    
    
    {{-- KONTAINER FULL WIDTH (Tanpa max-w-5xl) --}}
    <div class="w-full space-y-4 md:space-y-8 bg-[#f1f5f9] md:bg-transparent">
        
        {{-- 2. Konten Utama --}}
        <div class="bg-white rounded-1xl md:rounded-3xl shadow-sm border-1 md:border border-gray-200 overflow-hidden w-full mb-6">
            {{-- Bagian Judul & Meta --}}
            <div class="p-8 md:p-10 border-b border-gray-100 bg-gray-50/30">
                <span class="px-3 py-1 bg-blue-50 text-[#004aad] text-[10px] font-extrabold uppercase rounded-lg border border-blue-100 tracking-wider mb-4 inline-block">
                    {{ $detail->kategori }}
                </span>
                <h2 class="text-2xl md:text-4xl font-black text-slate-800 leading-tight uppercase tracking-tight mb-8">
                    {{ $detail->judul }}
                </h2>

                {{-- Baris Info Cepat (Metadata) --}}
                <div class="grid grid-cols-3 md:grid-cols-3 gap-3 md:gap-6">
                    <div class="bg-white p-2 md:p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center gap-1 md:gap-4 text-center md:text-left">
                        <div class="text-[#004aad] bg-blue-50 w-8 h-8 md:w-12 md:h-12 rounded-lg flex items-center justify-center text-[10px] md:text-lg shrink-0">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <div>
                            <p class="text-[7px] md:text-[9px] font-black text-gray-400 uppercase tracking-widest">Tanggal Terbit</p>
                            <p class="text-[9px] md:text-sm font-bold text-slate-700 uppercase">{{ \Carbon\Carbon::parse($detail->tanggal_tayang)->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-2 md:p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center gap-1 md:gap-4 text-center md:text-left">
                        <div class="text-[#004aad] bg-blue-50 w-8 h-8 md:w-12 md:h-12 rounded-lg flex items-center justify-center text-[10px] md:text-lg shrink-0">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <p class="text-[7px] md:text-[9px] font-black text-gray-400 uppercase tracking-widest">Penerima</p>
                            <p class="text-[9px] md:text-sm font-bold text-slate-700 uppercase truncate">{{ $detail->target ?? 'Semua Siswa' }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-2 md:p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center gap-1 md:gap-4 text-center md:text-left">
                        <div class="text-emerald-500 bg-emerald-50 w-8 h-8 md:w-12 md:h-12 rounded-lg flex items-center justify-center text-[10px] md:text-lg shrink-0">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-[7px] md:text-[9px] font-black text-gray-400 uppercase tracking-widest">Status</p>
                            <p class="text-[9px] md:text-sm font-bold text-emerald-600 uppercase">Aktif</p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Bagian Isi Teks --}}
            <div class="p-8 md:p-14">
                <article class="prose max-w-none text-slate-600 text-sm md:text-lg leading-relaxed font-medium">
                    {!! $detail->isi !!}
                </article>

                {{-- Catatan --}}
                <div class="mt-12 p-8 bg-blue-50/50 rounded-2xl border-l-8 border-[#004aad] italic text-sm text-slate-600 shadow-inner">
                    "Harap perhatikan instruksi atau lampiran yang tertera di atas. Jika ada pertanyaan, silahkan hubungi bagian kesiswaan atau wali kelas masing-masing."
                </div>
            </div>
        </div>

        {{-- 3. Bagian Lampiran (Full Width) --}}
        <div class="bg-white rounded-none md:rounded-3xl shadow-sm border-0 md:border border-gray-200 overflow-hidden w-full">
            <div class="p-4 border-b border-gray-100 flex flex-row justify-between items-center bg-white px-4 md:px-10">
                <div class="flex items-center gap-2 md:gap-3 overflow-hidden">
                    <div class="w-1 h-5 md:w-2 md:h-6 bg-[#004aad] rounded-full shrink-0"></div>
                    <h3 class="font-black text-[9px] md:text-xs uppercase tracking-[0.1em] text-slate-800 truncate">Lampiran</h3>
                </div>
                @if($detail->file_lampiran)
                <a href="{{ asset('uploads/pengumuman/' . $detail->file_lampiran) }}" download 
                   class="bg-[#004aad] text-white px-3 py-2 rounded-lg md:rounded-xl text-[8px] md:text-[10px] font-black uppercase hover:bg-slate-800 transition shadow-md whitespace-nowrap shrink-0">
                    <i class="fa-solid fa-download mr-2"></i> Unduh
                </a>
                @endif
            </div>
            
            <div class="p-4 md:p-14 bg-gray-50/30">
                @if($detail->file_lampiran)
                    @php
                        $ext = pathinfo($detail->file_lampiran, PATHINFO_EXTENSION);
                        $isImg = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                        $isPdf = strtolower($ext) === 'pdf';
                    @endphp

                    <div >
                        @if($isImg)
                            <img src="{{ asset('uploads/pengumuman/' . $detail->file_lampiran) }}" 
                                 class="max-w-full h-auto rounded-xl md:rounded-3xl shadow-lg border-[4px] md:border-[12px] border-gray-50">
                        @elseif($isPdf)
                            <div class="text-center p-4 md:p-10">
                                <div class="w-16 h-16 md:w-28 md:h-28 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-8 shadow-inner">
                                    <i class="fa-solid fa-file-pdf text-3xl md:text-5xl"></i>
                                </div>
                                <p class="font-black uppercase text-[9px] md:text-[11px] text-gray-400 tracking-[0.2em] mb-4 md:mb-8 italic">Dokumen Lampiran Tersedia</p>
                                <a href="{{ asset('uploads/pengumuman/' . $detail->file_lampiran) }}" target="_blank" 
                                   class="inline-block px-6 md:px-12 py-3 md:py-5 bg-slate-800 text-white rounded-xl md:rounded-2xl font-black text-[9px] md:text-[11px] uppercase tracking-[0.1em] hover:bg-[#004aad] transition-all shadow-xl">
                                   Pratinjau Dokumen
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-10 opacity-30">
                        <i class="fa-solid fa-folder-open fa-3x mb-2 text-gray-300"></i>
                        <p class="font-bold uppercase tracking-widest text-[10px] text-gray-400">Tidak ada berkas lampiran</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<style>
    .rounded-2xl { border-radius: 1rem !important; }
    .rounded-3xl { border-radius: 1.5rem !important; }
    .rounded-xl { border-radius: 0.75rem !important; }
    
    article {
        color: #334155 !important;
    }
    article p { margin-bottom: 2rem; }
    article b, article strong { color: #0f172a; font-weight: 800; }
    /* Memastikan gambar di dalam konten juga responsif */
    article img { border-radius: 1.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
</style>
@endsection