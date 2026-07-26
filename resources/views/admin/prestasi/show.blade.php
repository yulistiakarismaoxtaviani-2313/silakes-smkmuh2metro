@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-gray-50 p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans text-black">


    <div class="flex flex-col gap-8">
        
        {{-- 2. Ringkasan Utama (Profil Siswa) --}}
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row items-center gap-10">
            <div class="flex-shrink-0">
                <div class="w-40 h-40 rounded-2xl overflow-hidden border border-gray-200 p-1 bg-white shadow-sm">
                    <img src="{{ $prestasi->siswa->foto ? asset('storage/profil/' . $prestasi->siswa->foto) : asset('img/default-user.png') }}" 
                         class="w-full h-full object-cover rounded-xl">
                </div>
            </div>

            <div class="flex-grow w-full space-y-4 text-center md:text-left">
                <div>
                    <dt class="text-[10px] font-medium text-gray-400 uppercase tracking-[0.2em] mb-1">Nama Lengkap Siswa</dt>
                    <h2 class="text-3xl font-bold text-black leading-tight uppercase tracking-tight">
                        {{ $prestasi->siswa->user->nama ?? 'Nama Tidak Ditemukan' }}
                    </h2>
                </div>
                
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-3">
                    <p class="inline-flex text-xs font-bold text-gray-600 bg-blue-100 px-6 py-2.5 rounded-xl border border-gray-200 uppercase tracking-widest">
                        NIS: {{ $prestasi->siswa->nis }}
                    </p>
                    <p class="inline-flex text-xs font-bold text-gray-600 bg-green-100 px-6 py-2.5 rounded-xl border border-gray-200 uppercase tracking-widest">
                        Kelas: {{ $prestasi->siswa->kelas->nama_kelas ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- 3. Detail Section & Validasi --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- Data Teknis Kompetisi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-gray-700 font-bold text-[11px] uppercase tracking-widest">Data Teknis Kompetisi</h3>
                    <i class="fa-solid fa-file-invoice text-gray-300"></i>
                </div>
                
                <div class="p-6 space-y-3">
                    @php
                        $competitionData = [
                            ['label' => 'Nama Lomba', 'value' => $prestasi->nama_lomba, 'primary' => true],
                            ['label' => 'Penyelenggara', 'value' => $prestasi->penyelenggara_lomba],
                            ['label' => 'Kategori / Tingkat', 'value' => $prestasi->kategori . ' | ' . $prestasi->tingkat],
                            ['label' => 'Peringkat', 'value' => $prestasi->peringkat],
                            ['label' => 'Tanggal Lomba', 'value' => \Carbon\Carbon::parse($prestasi->tanggal)->format('d F Y')],
                        ];
                    @endphp
                    @foreach($competitionData as $data)
                        <div class="p-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                            <dt class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ $data['label'] }}</dt>
                            <dd class="text-sm font-bold {{ isset($data['primary']) ? 'text-state-900' : 'text-black' }} capitalize">{{ $data['value'] }}</dd>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Panel Validasi Admin --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100 flex justify-between items-center text-gray-700">
                    <h3 class="font-bold text-[11px] uppercase tracking-widest">Panel Validasi & Keputusan</h3>
                    <i class="fa-solid fa-gavel"></i>
                </div>
                <div class="p-8">
                    <form action="{{ route('admin.prestasi.validasi', $prestasi->id_prestasi) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Reward Bebas SPP (Bulan)</label>
                            <input type="number" name="bebas_spp" value="{{ $prestasi->bebas_spp }}" 
                                   class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-[#004AAD] transition font-bold text-black bg-gray-50">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Update Status Validasi</label>
                            <select name="status" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-[#004AAD] transition font-bold text-black bg-gray-50">
                                <option value="Menunggu" {{ $prestasi->status_validasi == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Disetujui" {{ $prestasi->status_validasi == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="Ditolak" {{ $prestasi->status_validasi == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Catatan / Keterangan</label>
                            <textarea name="keterangan" rows="3" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-[#004AAD] transition font-medium text-sm bg-gray-50" placeholder="Contoh: Selamat! Berkas sesuai.">{{ $prestasi->keterangan }}</textarea>
                        </div>

                        <button type="submit" class="w-full bg-[#004AAD] text-white py-4 rounded-xl font-bold text-[11px] uppercase tracking-widest hover:bg-blue-800 transition shadow-md flex items-center justify-center gap-3">
                            <i class="fa-solid fa-save"></i> Simpan Perubahan Data
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- 4. Berkas Sertifikat --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-8 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-gray-600 font-bold text-[11px] uppercase tracking-widest">Bukti Sertifikat</h3>
                @if($prestasi->file_bukti)
                <a href="{{ asset('storage/sertifikat/' . $prestasi->file_bukti) }}" download class="bg-[#004AAD] text-white border border-gray-200 px-5 py-2 rounded-xl text-[10px] font-bold uppercase hover:bg-gray-50 transition shadow-sm text-decoration-none">
                    <i class="fa-solid fa-download mr-1"></i> Unduh
                </a>
                @endif
            </div>
            <div class="p-8">
                    @if($prestasi->file_bukti)
                        <img src="{{ asset('storage/sertifikat/' . $prestasi->file_bukti) }}" 
                             class="max-w-full h-auto rounded-xl shadow-lg border border-gray-200" 
                             alt="Sertifikat">
                    @else
                        <div class="text-center opacity-40 text-gray-400">
                            <i class="fa-solid fa-image fa-4x mb-4"></i>
                            <p class="font-bold uppercase tracking-widest text-[10px]">Siswa tidak mengunggah berkas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        
        </div>

    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
@endsection