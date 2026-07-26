@extends('layouts.walikelas')

@section('content')
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans text-black">    

    <div class="flex flex-col gap-8">
        
        {{-- 2. SISI ATAS: Foto & Identitas Utama --}}
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row items-center gap-10 text-center md:text-left">
            
            <div class="flex-shrink-0">
                <div class="w-52 h-64 rounded-xl overflow-hidden border border-gray-200 p-1 bg-white">
                    <img src="{{ $siswa->foto ? asset('storage/profil/' . $siswa->foto) : asset('img/default-foto.png') }}" 
                         alt="Foto {{ $siswa->user->nama ?? 'Siswa' }}" 
                         class="w-full h-full object-cover rounded-lg">
                </div>
            </div>

            <div class="flex-grow w-full space-y-6">
                <div>
                    <dt class="text-[10px] font-medium text-gray-400 uppercase tracking-[0.2em] mb-2">Nama Lengkap Siswa</dt>
                    <h2 class="text-3xl font-bold text-black leading-tight uppercase tracking-tight">
                        {{ $siswa->user->nama ?? 'NAMA TIDAK DITEMUKAN' }}
                    </h2>
                </div>
                
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-3 pb-6 border-b border-gray-100">
                    <p class="inline-flex text-sm font-bold text-gray-600 bg-gray-100 px-6 py-2 rounded-lg border border-gray-200 uppercase tracking-widest">
                        NIS: {{ $siswa->nis }}
                    </p>
                    <span class="inline-flex items-center gap-2 px-5 py-2 rounded-lg border {{ $siswa->status == 'aktif' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200' }} text-[10px] font-black uppercase tracking-wider">
                        <span class="{{ $siswa->status == 'aktif' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                        {{ $siswa->status == 'aktif' ? 'Akun Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 gap-4 pt-2 text-left">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <dt class="text-[9px] font-medium text-gray-400 uppercase tracking-widest mb-1">Kelas Saat Ini</dt>
                        <dd class="text-lg font-bold text-black uppercase">{{ $kelas->nama_kelas ?? '-' }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <dt class="text-[9px] font-medium text-gray-400 uppercase tracking-widest mb-1">Tahun Ajaran</dt>
                        <dd class="text-lg font-bold text-black uppercase">
                            {{ $siswa->tahunAjaran->tahun_ajaran ?? '-' }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. SISI BAWAH: Kelompok Detail Data --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- Data Akademik --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100">
                    <h3 class="text-gray-600 font-bold text-[11px] uppercase tracking-widest">Informasi Akademik</h3>
                </div>
                <div class="p-6 grid grid-cols-1 gap-3">
                    @php
                        $academicData = [
                            ['label' => 'Nama Lengkap', 'value' => $siswa->user->nama ?? '-'],
                            ['label' => 'Username Login', 'value' => $siswa->nis],
                            ['label' => 'Jenis Kelamin', 'value' => $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'],
                            ['label' => 'Wali Kelas Pengampu', 'value' => Auth::user()->nama ?? '-'],
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
                    <h3 class="text-gray-600 font-bold text-[11px] uppercase tracking-widest">Pribadi & Kontak</h3>
                </div>
                <div class="p-6 grid grid-cols-1 gap-3">
                    @php
                        $profil = $siswa->profil;
                        $personalData = [
                            ['label' => 'Tempat, Tanggal Lahir', 'value' => ($profil->tempat_lahir ?? '-') . ', ' . (isset($profil->tanggal_lahir) ? \Carbon\Carbon::parse($profil->tanggal_lahir)->format('d F Y') : '-')],
                            ['label' => 'Agama', 'value' => $profil->agama ?? '-'],
                            ['label' => 'Nomor WhatsApp', 'value' => $profil->no_hp ?? '-'],
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

            {{-- Data Orang Tua (Pemisahan Kolom Nama & Pekerjaan) --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100">
                    <h3 class="text-gray-600 font-bold text-[11px] uppercase tracking-widest">Informasi Orang Tua / Wali</h3>
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

        </div>

       
        </div>
    </div>
</div>
@endsection