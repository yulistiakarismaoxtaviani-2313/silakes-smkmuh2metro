@extends('layouts.guru')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans">
    
    {{-- 1. Statistik Cards (3 Kolom) --}}
    <div class="grid grid-cols-3 gap-2 md:gap-6 mb-8">

        {{-- Card Status --}}
        <div class="bg-white p-2 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-1 md:gap-5 text-center md:text-left">
        <div class="bg-blue-50 p-2 md:p-4 rounded-xl text-[#004aad]">
                <i class="fas fa-user-tie text-sm md:text-2xl"></i>
            </div>
            <div>
                <p class="text-[8px] md:text-xs font-semibold text-gray-400 uppercase tracking-wider">Status Anda</p>
                <p class="text-[8px] md:text-lg font-bold text-slate-800">Tenaga Pendidik</p>
            </div>
        </div>

        {{-- Card Total Pengumuman --}}
       <div class="bg-white p-2 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-1 md:gap-5 text-center md:text-left">
        <div class="bg-blue-50 p-2 md:p-4 rounded-lg text-[#004aad]">
                <i class="fas fa-bullhorn text-sm md:text-2xl"></i>
            </div>
            <div>
                <p class="text-[8px] md:text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Informasi</p>
                <p class="text-[10px] md:text-2xl font-bold text-slate-800">{{ $pengumuman->count() }}</p>
            </div>
        </div>

         {{-- Card Update Terakhir --}}
         <div class="bg-white p-2 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-1 md:gap-5 text-center md:text-left">
            <div class="bg-blue-50 p-2 md:p-4 rounded-xl text-[#004aad]">
            <i class="fas fa-clock-rotate-left text-sm md:text-2xl"></i>
        </div>
        <div>
            <p class="text-[8px] md:text-xs font-semibold text-gray-400 uppercase tracking-wider">Update Terbaru</p>
            <p class="text-[10px] md:text-lg font-bold text-slate-800">
                {{ $pengumuman->first() ? \Carbon\Carbon::parse($pengumuman->first()->tanggal_tayang)->translatedFormat('d F Y') : '-' }}
            </p>
            </div>
        </div>
    </div>

   {{-- KONTAINER UTAMA (Satu Background Putih untuk Header & List) --}}
    <div class="bg-white rounded-2xl shadow-[0_4px_25px_rgb(0,0,0,0.03)] border border-gray-200 overflow-hidden">
        
        {{-- Header & Filter --}}
        <div class="p-6 border-b border-gray-100 bg-white">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 lg:gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <div class="h-6 w-1 bg-[#004aad] rounded-full"></div>
                        <h2 class="text-slate-800 font-black uppercase tracking-widest text-sm">
                            Informasi Pengumuman
                        </h2>
                    </div>
                </div>

                {{-- Form Filter & Search --}}
                <form action="{{ route('guru.pengumuman.index') }}" method="GET" class="flex flex-row items-center gap-2">
                    <div class="w-1/3 md:w-44">
                        <select name="kategori" onchange="this.form.submit()" 
                            class="w-full border border-gray-200 rounded-xl px-2 py-2.5 text-[9px] md:text-[10px] text-gray-600 font-bold capitalize outline-none focus:border-[#004AAD] focus:ring-2 focus:ring-blue-50 bg-gray-50 cursor-pointer transition-all">
                            <option value="">Semua</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('kategori') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-2/3 md:flex-grow relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Cari Judul atau Kategori..." 
                            class="border border-gray-200 rounded-xl px-3 py-2.5 text-[9px] md:text-[10px] w-full outline-none focus:border-[#004AAD] focus:ring-2 focus:ring-blue-50 bg-gray-50 capitalize font-semibold pr-8 transition-all">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#004AAD] hover:scale-110 transition-transform p-1.5">
                            <i class="fas fa-search text-[9px] md:text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Area Grid Card --}}
        <div class="p-4 md:p-8 bg-gray-50/50">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8">
                @forelse($pengumuman as $p)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-400 overflow-hidden hover:shadow-md hover:border-[#004aad] transition-all duration-300 group flex flex-col h-full">
                    
                    {{-- Konten Card --}}
                    <div class="p-5 md:p-7">
                        <div class="flex justify-between items-center mb-4 md:mb-5">
                            <span class="px-2 py-0.5 md:px-3 md:py-1 bg-blue-50 text-gray-600 text-[8px] md:text-[9px] font-extrabold uppercase rounded-lg border border-blue-100 tracking-wider">
                                {{ $p->kategori ?? 'UMUM' }}
                            </span>
                            <div class="flex items-center text-gray-400 gap-1.5">
                                <i class="far fa-calendar-alt text-[8px] md:text-[9px]"></i>
                                <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-tight">
                                    {{ \Carbon\Carbon::parse($p->tanggal_tayang)->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </div>

                        <h3 class="text-slate-800 font-bold uppercase text-xs md:text-base mb-2 md:mb-3 leading-snug">                            
                            {{ $p->judul }}
                        </h3>
                        
                         <p class="text-gray-500 text-[10px] md:text-xs leading-relaxed line-clamp-3 font-medium">
                            {{ strip_tags($p->isi) }}
                        </p>
                    </div>

                    {{-- Tombol Detail --}}
                    <div class="px-5 pb-5 md:px-7 md:pb-7 mt-auto">
                        <a href="{{ route('guru.pengumuman.show', $p->id_pengumuman) }}" 
                           class="flex items-center justify-between group/btn bg-slate-50 hover:bg-[#004aad] p-2.5 md:p-3.5 rounded-xl transition-all duration-300 border border-gray-100">
                            <span class="text-gray-600 group-hover/btn:text-white text-[9px] md:text-[10px] font-extrabold uppercase tracking-widest">Baca Selengkapnya</span>
                            <div class="bg-white w-6 h-6 md:w-7 md:h-7 rounded-lg flex items-center justify-center shadow-sm">
                                <i class="fas fa-arrow-right text-[#004aad] text-[8px] md:text-[9px]"></i>
                            </div>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-10 md:py-20 text-center">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-gray-100">
                        <i class="fas fa-info-circle text-xl md:text-2xl text-gray-200"></i>
                    </div>
                    <p class="text-gray-400 italic text-[10px] md:text-sm font-medium">Belum ada pengumuman tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-2xl { border-radius: 1rem !important; }
    .rounded-xl { border-radius: 0.75rem !important; }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
    .group:hover { transform: translateY(-3px); }
</style>
@endsection