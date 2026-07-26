@extends('layouts.walikelas')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans text-black">

    {{-- Profil Siswa Card --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-6 mb-8">
        <div class="w-20 h-20 bg-gray-50 rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <img src="{{ asset('storage/' . $siswa->foto) }}" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name={{ $siswa->user->nama }}&background=004aad&color=fff'">
        </div>
        <div class="text-center md:text-left flex-1">
            <h1 class="text-xl font-bold text-black uppercase tracking-tight mb-1">{{ $siswa->user->nama }}</h1>
            <div class="flex flex-wrap justify-center md:justify-start gap-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-id-card text-[#004aad] text-xs"></i>
                    <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">NIS: <span class="text-black">{{ $siswa->nis }}</span></p>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-chalkboard text-[#004aad] text-xs"></i>
                    <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">Kelas: <span class="text-black">{{ $siswa->kelas->nama_kelas }}</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Log Absen List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <span class="font-bold text-black text-xs uppercase tracking-widest">Aktivitas Sesi: {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}</span>
            <div class="text-[#004aad]">
                <i class="fas fa-history"></i>
            </div>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse($riwayat_lengkap as $item)
            <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-5">
                    {{-- Ikon Sesi Biru --}}
                    <div class="bg-white border-2 border-[#004aad] text-[#004aad] w-14 h-14 rounded-xl flex flex-col items-center justify-center shadow-sm">
                        <span class="text-[9px] font-bold leading-none uppercase">JAM</span>
                        <span class="text-lg font-black">{{ substr($item->jam_pelajaran, -1) }}</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider mb-1">Mata Pelajaran / Sesi</p>
                        <p class="text-sm font-bold text-black uppercase">{{ $item->jam_pelajaran }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:flex md:gap-12 w-full md:w-auto">
                    <div>
                        <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider mb-1">Status Kehadiran</p>
                        <span class="text-black text-[11px] font-bold uppercase">
                            @if($item->status == 'belum')
                                <span class="text-gray-300 italic font-normal">Belum Absen</span>
                            @else
                                {{ $item->status == 'hadir' ? '✓ HADIR' : $item->status }}
                            @endif
                        </span>
                    </div>
                    <div class="text-right md:text-left">
                        <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider mb-1">Waktu Absen</p>
                        <p class="text-sm font-medium text-black">{{ $item->waktu_absen }} {{ $item->waktu_absen != '-' ? 'WIB' : '' }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-20 text-center text-gray-300 italic uppercase text-xs tracking-widest font-medium">
                Tidak ada sesi jam pelajaran yang dibuka hari ini.
            </div>
            @endforelse
        </div>
    </div>

</div>

<style>
    .font-sans { font-family: 'Poppins', sans-serif; }
    .rounded-xl { border-radius: 0.75rem !important; }
</style>
@endsection