@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-gray-50 p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans text-black">
    
    <div class="flex flex-col gap-8">
        
        {{-- SISI ATAS: Ringkasan Utama & Meta Data Kelas --}}
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row items-center gap-10 text-center md:text-left">
            
            {{-- Visual Identitas Kelas --}}
            <div class="flex-shrink-0">
                <div class="w-52 h-40 rounded-xl bg-[#004AAD] flex flex-col items-center justify-center text-white shadow-md border-4 border-blue-50 p-4 text-center">
                    <span class="text-[10px] uppercase font-black opacity-75 mb-1 tracking-[0.2em]">Kelas</span>
                    <span class="text-3xl font-black uppercase leading-none tracking-tight">{{ $kelas->nama_kelas }}</span>
                </div>
            </div>

            {{-- Sisi Kanan: Identitas & Metadata --}}
            <div class="flex-grow w-full space-y-6">
                
                {{-- Identitas Utama --}}
                <div class="space-y-2">
                    <dt class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Wali Kelas Saat Ini</dt>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-800 leading-tight uppercase tracking-tight">
                        {{ $kelas->guru->user->nama ?? 'BELUM DITENTUKAN' }}
                    </h2>
                </div>

                {{-- Baris Info Cepat (Metadata dengan Ikon) di dalam grid --}}
                <div class="grid grid-cols-3 md:grid-cols-3 gap-2 md:gap-6 pt-2 text-left">
                    
                    {{-- Tahun Ajaran --}}
                    <div class="bg-white p-3 md:p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center md:items-center gap-2 md:gap-4">
                        <div class="text-[#004aad] bg-blue-50 w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center text-sm md:text-lg">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <div class="text-center md:text-left">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em]">Tahun Ajaran</p>
                            <p class="text-[11px] md:text-sm font-bold text-slate-700 uppercase truncate">
                                {{ $kelas->tahunAjaran->tahun_ajaran ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    
                    {{-- Total Siswa --}}
                    <div class="bg-white p-3 md:p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center md:items-center gap-2 md:gap-4">
                        <div class="text-[#004aad] bg-blue-50 w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center text-sm md:text-lg">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="text-center md:text-left">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em]">Total Siswa</p>
                            <p class="text-[11px] md:text-sm font-bold text-slate-700 uppercase truncate">
                                {{ $kelas->siswa->count() }} Siswa
                            </p>
                        </div>
                    </div>

                    {{-- Status Kelas --}}
                    <div class="bg-white p-3 md:p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center md:items-center gap-2 md:gap-4">
                        <div class="{{ $kelas->status == 'aktif' ? 'text-emerald-500 bg-emerald-50' : 'text-red-500 bg-red-50' }} w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center text-sm md:text-lg">
                            <i class="fas {{ $kelas->status == 'aktif' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        </div>
                        <div class="text-center md:text-left">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em]">Status Kelas</p>
                            <p class="text-sm font-bold {{ $kelas->status == 'aktif' ? 'text-emerald-600' : 'text-red-600' }} text-[11px] md:text-sm font-bold  uppercase truncate">
                                {{ $kelas->status == 'aktif' ? 'Aktif' : 'Nonaktif' }}
                            </p>
                        </div>
                    </div>

                </div> {{-- Akhir Baris Info Cepat --}}
                
            </div> {{-- Akhir Sisi Kanan (flex-grow) --}}
            
        </div> {{-- Akhir SISI ATAS Card --}}


        {{-- SISI BAWAH: Daftar Siswa (Tabel Modern) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden w-full">
            
            <div class="p-2 md:p-6 border-b border-gray-100 flex justify-between items-center bg-white px-8">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-5 bg-[#004aad] rounded-full"></div>
                    <h3 class="font-black text-[10px] md:text-xs uppercase tracking-[0.2em] text-slate-800">Daftar Siswa Dalam Kelas</h3>
                </div>
                <div class="bg-slate-100 text-slate-600 px-2 md:px-4 py-1.5 rounded-lg text-[10px] font-black capitalize tracking-widest border border-slate-200">
                    Total: {{ $kelas->siswa->count() }}
                </div>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-slate-700 uppercase text-xs tracking-wider text-center">
                            <th class="p-3 md:p-5 whitespace-nowrap text-center font-bold border-r border-gray-300">No</th>
                            <th class="p-3 md:p-5 whitespace-nowrap text-center font-bold border-r border-gray-300">NIS</th>
                            <th class="p-3 md:p-5 whitespace-nowrap text-center font-bold border-r border-gray-300">Nama Lengkap</th>
                            <th class="p-3 md:p-5 whitespace-nowrap text-center font-bold border-r border-gray-300">JK</th>
                            <th class="p-3 md:p-5 whitespace-nowrap text-center font-bold border-r border-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($kelas->siswa as $index => $siswa)
                        <tr class="hover:bg-slate-50/80 transition-all group">
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $index + 1 }}</td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $siswa->nis }}</td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-left border-r border-gray-300 whitespace-nowrap">{{ $siswa->user->nama }}</td>
                            <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $siswa->jenis_kelamin }}</span>
                            </td>
                            <td class="p-5 text-center">
                                <span class="bg-emerald-50 text-emerald-700 px-4 py-1.5 rounded-lg text-[10px] font-black uppercase border border-emerald-100 tracking-wider">
                                    {{ $siswa->status ?? 'Aktif' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-20 text-center">
                                <div class="flex flex-col items-center justify-center opacity-30 text-gray-400">
                                    <i class="fa-solid fa-folder-open fa-4x mb-4 text-gray-300"></i>
                                    <p class="font-bold uppercase tracking-[0.3em] text-xs text-gray-400">Belum ada siswa di kelas ini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            
            </div>
            
        </div> {{-- Akhir SISI BAWAH Card --}}
        
    </div>
</div>
@endsection