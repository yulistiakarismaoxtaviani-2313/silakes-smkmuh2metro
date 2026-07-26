@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-gray-50 p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans text-black">
    
   <div class="flex flex-col gap-8">
        
    {{-- SISI ATAS: Foto & Identitas Utama --}}
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row items-center gap-10 text-center md:text-left">
        
        {{-- Sisi Kiri: Foto Siswa --}}
        <div class="flex-shrink-0">
            <div class="w-52 h-64 rounded-xl overflow-hidden border border-gray-200 p-1 bg-white">
                <img src="{{ $siswa->foto ? asset('storage/profil/' . $siswa->foto) : asset('img/default-foto.png') }}" 
                     alt="Foto {{ $siswa->user->nama ?? 'Siswa' }}" 
                     class="w-full h-full object-cover rounded-lg">
            </div>
        </div>

        {{-- Sisi Kanan: Identitas & Metadata (Dibungkus dalam satu flex-grow agar sejajar dengan foto) --}}
        <div class="flex-grow w-full space-y-6">
            
            {{-- Identitas Utama --}}
            <div class="space-y-2">
                <dt class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Nama Lengkap Siswa</dt>
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 leading-tight uppercase tracking-tight">
                    {{ $siswa->user->nama ?? 'NAMA TIDAK DITEMUKAN' }}
                </h2>
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-3 mt-2">
                    <p class="inline-flex text-xs font-bold text-[#004AAD] bg-blue-50 px-4 py-1.5 rounded-xl border border-blue-100 uppercase tracking-widest">
                        NIS: {{ $siswa->nis }}
                    </p>
                </div>
            </div>

            {{-- Baris Info Cepat (Metadata dengan Ikon) di dalam grid --}}
            <div class="grid grid-cols-3 md:grid-cols-3 gap-4 md:gap-6 pt-2 text-center">
                
                {{-- Kelas Saat Ini --}}
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
                    <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                        <div class="text-center md:text-left">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Kelas</p>
                        <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">
                            {{ $siswa->kelas->nama_kelas ?? '-' }}
                        </p>
                    </div>
                </div>
                
                {{-- Tahun Ajaran --}}
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
                    <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                        <div class="text-center md:text-left">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5 whitespace-nowrap">Tahun Ajaran</p>
                        <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">
                            {{ $siswa->tahunAjaran->tahun_ajaran ?? ($siswa->kelas->tahunAjaran->nama_tahun ?? '-') }}
                        </p>
                    </div>
                </div>

                {{-- Status Siswa --}}
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
                    <div class="{{ $siswa->status == 'aktif' ? 'text-emerald-500 bg-emerald-50' : 'text-red-500 bg-red-50' }} w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
                        <i class="fas {{ $siswa->status == 'aktif' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                    </div>
                    <div class="text-center md:text-left">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Status</p>
                        <p class="text-xs md:text-sm font-bold {{ $siswa->status == 'aktif' ? 'text-emerald-600' : 'text-red-600' }} uppercase tracking-widest">
                            {{ $siswa->status == 'aktif' ? 'Aktif' : 'Nonaktif' }}
                        </p>
                    </div>
                </div>

            </div> {{-- Akhir Baris Info Cepat --}}
            
        </div> {{-- Akhir Sisi Kanan (flex-grow) --}}
        
    </div> {{-- Akhir SISI ATAS Card --}}
    


        {{-- SISI BAWAH: Kelompok Detail Data --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- Data Akademik & Profil --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100">
                    <h3 class="text-gray-600 font-bold text-[11px] uppercase tracking-widest">Data Akademik & Profil</h3>
                </div>
                <div class="p-6 grid grid-cols-1 gap-3">
                    @php
                        $academicData = [
                            ['label' => 'Nama Lengkap', 'value' => $siswa->user->nama ?? '-'],
                            ['label' => 'NIS / Username', 'value' => $siswa->nis],
                            ['label' => 'Jenis Kelamin', 'value' => $siswa->jenis_kelamin == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN'],
                            ['label' => 'Kelas', 'value' => $siswa->kelas->nama_kelas ?? '-'],
                            ['label' => 'Status Akun Sistem', 'value' => strtoupper($siswa->status)],
                            ['label' => 'Tanggal Registrasi', 'value' => $siswa->created_at->format('d F Y')],
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

            {{-- Data Pribadi & Kontak --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100">
                    <h3 class="text-gray-600 font-bold text-[11px] uppercase tracking-widest">Kontak & Informasi Pribadi</h3>
                </div>
                <div class="p-6 grid grid-cols-1 gap-3">
                    @php
                        $profil = $siswa->profil;
                        $personalData = [
                            ['label' => 'Tempat, Tanggal Lahir', 'value' => ($profil->tempat_lahir ?? '-') . ', ' . (isset($profil->tanggal_lahir) ? \Carbon\Carbon::parse($profil->tanggal_lahir)->format('d F Y') : '-')],
                            ['label' => 'Agama', 'value' => $profil->agama ?? '-'],
                            ['label' => 'Alamat Email', 'value' => $siswa->user->email ?? '-'],
                            ['label' => 'Nomor WhatsApp / HP', 'value' => $profil->no_hp ?? '-'],
                            ['label' => 'Alamat Lengkap', 'value' => $profil->alamat_siswa ?? '-'],
                        ];
                    @endphp
                    @foreach($personalData as $data)
                        <div class="p-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                            <dt class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ $data['label'] }}</dt>
                            <dd class="text-sm font-bold text-black uppercase">{{ $data['value'] }}</dd>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Data Orang Tua / Wali --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100">
                    <h3 class="text-gray-600 font-bold text-[11px] uppercase tracking-widest">Data Orang Tua / Wali</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Baris Ayah --}}
                    <div class="p-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                        <dt class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama Ayah</dt>
                        <dd class="text-sm font-bold text-black uppercase">{{ $profil->nama_ayah ?? '-' }}</dd>
                    </div>
                    <div class="p-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                        <dt class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Pekerjaan Ayah</dt>
                        <dd class="text-sm font-bold text-black uppercase">{{ $profil->pekerjaan_ayah ?? '-' }}</dd>
                    </div>
                    {{-- Baris Ibu --}}
                    <div class="p-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                        <dt class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama Ibu</dt>
                        <dd class="text-sm font-bold text-black uppercase">{{ $profil->nama_ibu ?? '-' }}</dd>
                    </div>
                    <div class="p-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                        <dt class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Pekerjaan Ibu</dt>
                        <dd class="text-sm font-bold text-black uppercase">{{ $profil->pekerjaan_ibu ?? '-' }}</dd>
                    </div>
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
                    Reset Password Siswa
                </h4>

                <p class="text-sm text-gray-500 leading-6 max-w-2xl">
                    Password siswa akan dikembalikan ke password default menggunakan
                    <span class="font-semibold text-slate-700">NIS</span>.Setelah berhasil login, siswa disarankan segera mengganti password untuk menjaga keamanan akun.
                </p>
            </div>

        </div>

        <form action="{{ route('admin.siswa.reset-password', $siswa->id_siswa) }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin mereset password siswa ini?\n\nPassword akan dikembalikan menjadi NIS siswa.')">

            @csrf
            @method('PUT')

            <button
                type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-[#1e3a8a] hover:bg-blue-800 text-white rounded-xl font-semibold shadow-sm transition whitespace-nowrap">

                <i class="fas fa-key"></i>
                Reset Password

            </button>

        </form>

    </div>

</div>

        </div>
    </div>
</div>
@endsection