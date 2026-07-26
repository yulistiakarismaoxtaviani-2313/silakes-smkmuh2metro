@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-gray-50 p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans text-black">
    
    <div class="flex flex-col gap-8">

    @if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl text-sm font-semibold">
    {{ session('success') }}
</div>
@endif

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row items-center gap-10 text-center md:text-left">
            
            {{-- Sisi Kiri: Foto Guru --}}
            <div class="flex-shrink-0">
                <div class="w-52 h-64 rounded-xl overflow-hidden border border-gray-200 p-1 bg-white">
                    <img src="{{ $guru->foto ? asset('storage/profil/' . $guru->foto) : asset('img/default-foto.png') }}" 
                         alt="Foto {{ $guru->user->nama ?? 'Guru' }}" 
                         class="w-full h-full object-cover rounded-lg">
                </div>
            </div>

            {{-- Sisi Kanan: Identitas & Metadata (Dibungkus agar sejajar dengan foto) --}}
            <div class="flex-grow w-full space-y-6">
                
                {{-- Identitas Utama --}}
                <div class="space-y-2">
                    <dt class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Nama Lengkap Guru</dt>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-800 leading-tight uppercase tracking-tight">
                        {{ $guru->user->nama ?? 'NAMA TIDAK DITEMUKAN' }}
                    </h2>
                    <div class="flex flex-wrap justify-center md:justify-start items-center gap-3 mt-2">
                        <p class="inline-flex text-xs font-bold text-[#004AAD] bg-blue-50 px-4 py-1.5 rounded-xl border border-blue-100 uppercase tracking-widest">
                            NBM: {{ $guru->nip }}
                        </p>
                    </div>
                </div>

                {{-- Baris Info Cepat (Metadata dengan Ikon) di dalam grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2 text-left">
                    
                    {{-- Jabatan Tambahan --}}
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                        <div class="text-[#004aad] bg-blue-50 w-12 h-12 rounded-xl flex items-center justify-center text-lg-none flex-shrink-0">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Jabatan Tambahan</p>
                            <p class="text-sm font-bold text-slate-700 uppercase">
                                {{ $guru->kelas ? 'WALI KELAS ' . $guru->kelas->nama_kelas : 'GURU MATA PELAJARAN' }}
                            </p>
                        </div>
                    </div>

                    {{-- Status Guru --}}
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                        <div class="{{ $guru->status == 'aktif' ? 'text-emerald-500 bg-emerald-50' : 'text-red-500 bg-red-50' }} w-12 h-12 rounded-xl flex items-center justify-center text-lg-none flex-shrink-0">
                            <i class="fas {{ $guru->status == 'aktif' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Status Keaktifan</p>
                            <p class="text-sm font-bold {{ $guru->status == 'aktif' ? 'text-emerald-600' : 'text-red-600' }} uppercase tracking-widest">
                                {{ $guru->status == 'aktif' ? 'Aktif' : 'Nonaktif' }}
                            </p>
                        </div>
                    </div>

                </div> {{-- Akhir Baris Info Cepat --}}
                
            </div> {{-- Akhir Sisi Kanan (flex-grow) --}}
            
        </div> {{-- Akhir SISI ATAS Card --}}


        {{-- SISI BAWAH: Kelompok Detail Data --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- Data Akademik & Kepegawaian --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100">
                    <h3 class="text-gray-600 font-bold text-[11px] uppercase tracking-widest">Data Akademik & Kepegawaian</h3>
                </div>
                <div class="p-6 grid grid-cols-1 gap-3">
                    @php
                        $academicData = [
                            ['label' => 'Nama Lengkap', 'value' => $guru->user->nama ?? '-'],
                            ['label' => 'NBM', 'value' => $guru->nip],
                            ['label' => 'Jenis Kelamin', 'value' => $guru->jenis_kelamin == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN'],
                            ['label' => 'Mata Pelajaran', 'value' => $guru->mapel->pluck('nama_mapel')->implode(',') ?: '-'],
                            ['label' => 'Wali Kelas Di', 'value' => $guru->kelas->nama_kelas ?? 'TIDAK MENJABAT'],
                            ['label' => 'Status Akun Sistem', 'value' => strtoupper($guru->status)],
                        ];
                    @endphp
                    @foreach($academicData as $data)
                        <div class="p-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                            <dt class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ $data['label'] }}</dt>
                            <dd class="text-sm font-bold text-black uppercase">{{ $data['value'] }}</dd>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Data Kontak & Informasi Tambahan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100">
                    <h3 class="text-gray-600 font-bold text-[11px] uppercase tracking-widest">Kontak & Informasi Tambahan</h3>
                </div>
                <div class="p-6 grid grid-cols-1 gap-3">
                    @php
                        $profil = $guru->profilGuru;
                        $personalData = [
                            ['label' => 'Alamat Email', 'value' => $guru->user->email ?? '-'],
                            ['label' => 'Nomor WhatsApp / HP', 'value' => $profil->no_hp ?? '-'],
                            ['label' => 'Status Akun Profil', 'value' => $profil->status_akun ?? '-'],
                            ['label' => 'Tanggal Data Masuk', 'value' => $guru->created_at->format('d F Y')],
                        ];
                    @endphp
                    @foreach($personalData as $data)
                        <div class="p-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                            <dt class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ $data['label'] }}</dt>
                            <dd class="text-sm font-bold text-black ">{{ $data['value'] }}</dd>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Keamanan Akun --}}
<div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

    <div class="bg-gray-50 px-8 py-4 border-b border-gray-100">
        <h3 class="text-gray-600 font-bold text-[11px] uppercase tracking-widest">
            Keamanan Akun
        </h3>
    </div>

    <div class="p-6 flex flex-col md:flex-row items-center justify-between gap-6">

        <div class="flex items-start gap-4">

            <div>
                <h4 class="text-base font-bold text-slate-800 mb-2">
                    Reset Password Guru
                </h4>

                <p class="text-sm text-gray-500 leading-6 max-w-2xl">
                    Password guru akan dikembalikan ke password default menggunakan
                    <span class="font-semibold text-slate-700">NBM</span>.
                    Setelah berhasil login, guru disarankan segera mengganti password untuk menjaga keamanan akun.
                </p>
            </div>

        </div>

        <form action="{{ route('admin.guru.reset-password', $guru->id_guru) }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin mereset password guru ini?\n\nPassword akan dikembalikan menjadi NBM guru.')">

            @csrf
            @method('PUT')

            <button
                type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-[#004AAD] hover:bg-blue-600 text-white rounded-xl font-semibold shadow-sm transition whitespace-nowrap">

                <i class="fas fa-key"></i>
                Reset Password

            </button>

        </form>

    </div>

</div>

        </div> {{-- Akhir SISI BAWAH Grid --}}
        
    </div>
</div>
@endsection