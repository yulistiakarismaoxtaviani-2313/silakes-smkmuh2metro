@extends('layouts.admin')

@section('content')
@php
    // Memetakan data lama dari database agar terbaca dengan format Alpine.js 'rows'
    $dataJadwal = $jadwal->map(function($item) {
        return [
            'hari' => $item->hari,
            'jam_mulai' => \Carbon\Carbon::parse($item->jam_mulai)->format('H:i'),
            'jam_selesai' => \Carbon\Carbon::parse($item->jam_selesai)->format('H:i'),
            'jenis' => $item->jenis ?? 'kbm',
            'id_mapel' => $item->id_mapel ?? '',
            'id_guru' => $item->id_guru ?? '',
            'kegiatan_kustom' => $item->kegiatan_kustom ?? '',
        ];
    });
@endphp

<div class="flex-1 bg-[#f1f5f9] p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans" x-data="handlerEditPelajaran()">
    
    <div class="max-w-6xl mx-auto">
        
        @if($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-xl mb-6 text-xs font-bold uppercase shadow-lg shadow-red-200">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
        </div>
        @endif

        {{-- HEADER --}}
        <div class="bg-[#004AAD] rounded-t-2xl px-8 py-7 shadow-sm flex justify-between items-center">
            <div>
                <h2 class="text-white font-black text-18 md:text-xl tracking-widest uppercase">
                    Edit Jadwal Pelajaran
                </h2>
                <p class="text-white/60 text-[10px] mt-1 uppercase tracking-[0.2em]">
                    Sistem Kesiswaan SMK Muhammadiyah 2 Metro
                </p>
            </div>

        </div>

        {{-- FORM UTAMA --}}
        <form action="{{ route('admin.jadwal.pelajaran.update', $kelas->id_kelas) }}" method="POST" class="bg-white rounded-b-2xl shadow-sm border-x border-b border-gray-200 overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-8">
{{-- Master Data Area (3 Kolom Sejajar - Mengambil data jadwal yang sedang diedit) --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 p-6 bg-slate-50 rounded-2xl border border-slate-200">
    
    {{-- Kolom 1: Nama Kelas (Read Only) --}}
    <div class="space-y-2">
        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Nama Kelas</label>
        <div class="w-full border border-gray-300 rounded-xl px-4 py-3 text-xs text-slate-400 bg-gray-100 font-bold uppercase cursor-not-allowed">
            {{ $kelas->nama_kelas }}
        </div>
        <input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas }}">
    </div>
    
    {{-- Kolom 2: Semester (Read Only - Diambil dari data jadwal kelas ini) --}}
    <div class="space-y-2">
        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Semester</label>
        <div class="w-full border border-gray-300 rounded-xl px-4 py-3 text-xs text-slate-400 bg-gray-100 font-bold uppercase cursor-not-allowed">
            {{ $jadwal->first()->semester->nama_semester ?? ($semesterAktif->nama_semester ?? 'SEMESTER TIDAK DITEMUKAN') }}
        </div>
        <input type="hidden" name="id_semester" value="{{ $jadwal->first()->id_semester ?? ($semesterAktif->id_semester ?? '') }}">
    </div>

    {{-- Kolom 3: Tahun Ajaran (Read Only - Diambil dari data jadwal kelas ini) --}}
    <div class="space-y-2">
        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Tahun Ajaran</label>
        <div class="w-full border border-gray-300 rounded-xl px-4 py-3 text-xs text-slate-400 bg-gray-100 font-bold uppercase cursor-not-allowed">
            {{ $jadwal->first()->tahunAjaran->tahun_ajaran ?? ($tahunAjaranAktif->tahun_ajaran ?? 'TAHUN AJARAN TIDAK DITEMUKAN') }}
        </div>
        <input type="hidden" name="id_tahun_ajaran" value="{{ $jadwal->first()->id_tahun_ajaran ?? ($tahunAjaranAktif->id_tahun_ajaran ?? '') }}">
    </div>
                </div>

                {{-- Table Input Section --}}
                <div class="rounded-lg md:rounded-2xl border border-gray-300 mb-6 shadow-sm overflow-x-auto">
                    <table class="w-full table-auto text-sm border-collapse">
        <thead class="bg-gray-50 border-b border-gray-300 text-slate-700 uppercase text-[10px] font-black tracking-widest">
                            <tr class="divide-x divide-gray-300">
                                <th class="p-4 text-center whitespace-nowrap">Hari</th>
                                <th class="p-4 text-center whitespace-nowrap">Jam Mulai</th>
                                <th class="p-4 text-center whitespace-nowrap">Jam Selesai</th>
                                <th class="p-4 text-center whitespace-nowrap">Jenis Baris</th>
                                <th class="p-4 text-center whitespace-nowrap">Mata Pelajaran</th>
                                <th class="p-4 text-center whitespace-nowrap">Guru Pengajar</th>
                                <th class="p-4 text-center whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-for="(row, index) in rows" :key="index">
                                <tr class="hover:bg-blue-50/50 transition-colors divide-x divide-gray-100">
                                    
                                    {{-- Kolom 1: Hari --}}
                                    <td class="p-3 min-w-[120px]">
                                        <select :name="`inputs[${index}][hari]`" x-model="row.hari" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-medium cursor-pointer" required>
                                            <option value="">Pilih Hari</option>
                                            <option value="Senin">Senin</option>
                                            <option value="Selasa">Selasa</option>
                                            <option value="Rabu">Rabu</option>
                                            <option value="Kamis">Kamis</option>
                                            <option value="Jumat">Jumat</option>
                                            <option value="Sabtu">Sabtu</option>
                                        </select>
                                    </td>

                                    {{-- Kolom 2: Jam Mulai --}}
                                    <td class="p-3 min-w-[120px]">
                                        <input type="time" :name="`inputs[${index}][jam_mulai]`" x-model="row.jam_mulai" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-medium" required>
                                    </td>

                                    {{-- Kolom 3: Jam Selesai --}}
                                    <td class="p-3 min-w-[120px]">
                                        <input type="time" :name="`inputs[${index}][jam_selesai]`" x-model="row.jam_selesai" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-medium" required>
                                    </td>

                                    {{-- Kolom 4: Jenis Baris --}}
                                    <td class="p-3 min-w-[120px]">
                                        <select :name="`inputs[${index}][jenis]`" x-model="row.jenis" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-medium text-slate-800 cursor-pointer">
                                            <option value="kbm">Mapel (KBM)</option>
                                            <option value="istirahat">Istirahat</option>
                                            <option value="non_kbm">Non-KBM</option>
                                        </select>
                                    </td>

                                    {{-- Kolom 5: Mata Pelajaran / Kegiatan --}}
                                    <td class="p-3 min-w-[120px]">
                                        {{-- Dropdown Mapel (Jika jenis === 'kbm') --}}
                                        <div x-show="row.jenis === 'kbm'">
                                            <select :name="`inputs[${index}][id_mapel]`" :required="row.jenis === 'kbm'" x-model="row.id_mapel" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-medium text-slate-900 cursor-pointer">
                                                <option value="">Pilih Mata Pelajaran</option>
                                                @foreach(($mapel ?? []) as $m)
                                                    <option value="{{ $m->id_mapel }}">{{ $m->nama_mapel }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Input Teks (Jika jenis !== 'kbm') --}}
                                        <div x-show="row.jenis !== 'kbm'" x-cloak>
                                            <input type="text" :name="`inputs[${index}][kegiatan_kustom]`" :required="row.jenis !== 'kbm'" x-model="row.kegiatan_kustom" placeholder="Contoh: Istirahat Sholat / Budi Pekerti" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-slate-50 text-slate-900 font-medium">
                                        </div>
                                    </td>

                                    {{-- Kolom 6: Guru Pengajar --}}
                                    <td class="p-3 min-w-[120px]">
                                        <select :name="`inputs[${index}][id_guru]`" 
                                                x-model="row.id_guru"
                                                :disabled="row.jenis !== 'kbm'" 
                                                :required="row.jenis === 'kbm'" 
                                                :class="row.jenis !== 'kbm' ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-white text-slate-600 cursor-pointer'" 
                                                class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none font-medium transition-colors">
                                            <option value="">Pilih Guru</option>
                                            @foreach(($guru ?? []) as $g)
                                                <option value="{{ $g->id_guru }}">{{ $g->user->nama }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    {{-- Kolom 7: Tombol Hapus Baris Satuan --}}
                                    <td class="p-3 min-w-[120px] text-center">
                                        <button type="button" @click="removeSpecificRow(index)" class="w-8 h-8 text-red-500 rounded-lg hover:bg-red-50 hover:text-white transition-all">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>

                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Action Buttons Row --}}
                <div class="flex flex-col md:flex-row gap-4 items-center">
                    <button type="button" @click="addRow" class="w-full md:flex-1 py-3.5 bg-blue-50 text-[#004AAD] rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-100 transition-all border-2 border-dashed border-blue-200 group">
                        <i class="fas fa-plus-circle mr-2 group-hover:scale-110 transition-transform"></i> Tambah Baris Pelajaran
                    </button>
                </div>

                {{-- Footer Submit --}}
                <div class="flex flex-row items-center gap-4 pt-6 mt-4 md:pt-10 md:mt-8 border-t border-gray-50">
            
                        <a href="{{ route('admin.jadwal.index') }}" class="flex-1 px-4 py-4 bg-slate-100 text-slate-500 rounded-xl font-bold text-[10px] md:text-xs capitalize tracking-widest hover:bg-slate-200 transition-all text-center">
                            Batal
                        </a>
                        <button type="submit" class="flex-1 bg-[#004AAD] text-white py-4 px-4 rounded-xl font-bold text-[10px] md:text-xs capitalize tracking-widest hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/10 active:scale-[0.98]">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    select, input { -webkit-appearance: none; }
</style>

<script>
function handlerEditPelajaran() {
    return {
        // Memuat array jadwal lama dari database langsung ke baris input dinamis
        rows: @json($dataJadwal), 
        
        addRow() {
            this.rows.push({ 
                hari: '',
                jam_mulai: '',
                jam_selesai: '',
                jenis: 'kbm',
                id_mapel: '',
                id_guru: '',
                kegiatan_kustom: ''
            });
        },
        removeSpecificRow(index) {
            if(this.rows.length > 1) {
                this.rows.splice(index, 1);
            } else {
                alert('Minimal harus menyisakan satu baris jadwal.');
            }
        }
    }
}
</script>
@endsection