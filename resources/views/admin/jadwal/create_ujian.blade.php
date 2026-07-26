@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-[#f1f5f9] p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans" x-data="handlerUjian()">
    
    <div class="max-w-7xl mx-auto">
        
        @if($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-xl mb-6 text-xs font-bold uppercase shadow-lg shadow-red-200 animate-bounce">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
        </div>
        @endif

        {{-- HEADER (Gaya Rounded & Berwibawa) --}}
        <div class="bg-[#004AAD] rounded-t-2xl px-8 py-7 shadow-sm flex justify-between items-center">
            <div>
                <h2 class="text-white font-black text-xl tracking-widest uppercase">
                    Buat Jadwal Ujian
                </h2>
                <p class="text-white/60 text-[10px] mt-1 uppercase tracking-[0.2em]">Manajemen Kurikulum • SMK Muhammadiyah 2 Metro</p>
            </div>

        </div>

        {{-- FORM UTAMA --}}
        <form action="{{ route('admin.jadwal.ujian.store') }}" method="POST" class="bg-white rounded-b-2xl shadow-sm border-x border-b border-gray-200 overflow-hidden">
            @csrf
            
            <div class="p-8">
                {{-- Master Data Area --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10 p-6 bg-slate-50 rounded-2xl border border-slate-200">
                    
                    {{-- Judul Ujian (Full Width di Baris Pertama) --}}
                    <div class="md:col-span-4 space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Judul / Nama Ujian</label>
                        <input type="text" name="judul" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-xs text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-[#004AAD] outline-none bg-white font-bold transition-all uppercase placeholder:font-normal placeholder:lowercase"
                            placeholder="Contoh: Penilaian Akhir Semester Ganjil">
                    </div>

                    {{-- Kolom 1: Pilih Kelas --}}
                    <div class="md:col-span-1 space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Pilih Kelas</label>
                        <select name="id_kelas" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-xs text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-[#004AAD] outline-none bg-white font-bold transition-all uppercase cursor-pointer" required>
                            <option value="">-- KELAS --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kolom 2: Jenis Ujian --}}
                    <div class="md:col-span-1 space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Jenis Ujian</label>
                        <select name="id_jenis_ujian" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-xs text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-[#004AAD] outline-none bg-white font-bold transition-all uppercase cursor-pointer" required>
                            <option value="">-- JENIS UJIAN --</option>
                            @foreach($jenis_ujian as $ju)
                                <option value="{{ $ju->id_jenis_ujian }}">{{ $ju->nama_ujian }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Kolom 3: Semester (Read Only) --}}
                    <div class="md:col-span-1 space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Semester</label>
                        <div class="w-full border border-gray-300 rounded-xl px-4 py-3 text-xs text-slate-900 bg-white font-bold uppercase">
                            {{ $semesterAktif->nama_semester ?? 'TIDAK ADA AKTIF' }}
                        </div>
                        <input type="hidden" name="id_semester" value="{{ $semesterAktif->id_semester ?? $semesterAktif->id ?? '' }}">
                    </div>

                    {{-- Kolom 4: Tahun Ajaran (Read Only) --}}
                    <div class="md:col-span-1 space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Tahun Ajaran</label>
                        <div class="w-full border border-gray-300 rounded-xl px-4 py-3 text-xs text-slate-900 bg-white font-bold uppercase">
                            {{ $tahunAjaranAktif->tahun_ajaran ?? 'TIDAK ADA AKTIF' }}
                        </div>
                        <input type="hidden" name="id_tahun_ajaran" value="{{ $tahunAjaranAktif->id_tahun_ajaran ?? $tahunAjaranAktif->id ?? '' }}">
                    </div>
                </div>

                {{-- Table Input Section --}}
               <div class="rounded-lg md:rounded-2xl border border-gray-300 mb-6 shadow-sm overflow-x-auto">
                    <table class="w-full table-auto text-sm border-collapse">
        <thead class="bg-gray-50 border-b border-gray-300 text-slate-700 uppercase text-[10px] font-black tracking-widest">
            <tr class="divide-x divide-gray-300">
                <th class="p-4 text-center whitespace-nowrap">Hari</th>
                <th class="p-4 text-center whitespace-nowrap">Tanggal</th>
                <th class="p-4 text-center whitespace-nowrap">Mulai</th>
                <th class="p-4 text-center whitespace-nowrap">Selesai</th>
                <th class="p-4 text-center whitespace-nowrap">Mata Pelajaran</th>
                <th class="p-4 text-center whitespace-nowrap">Pengawas</th>
                <th class="p-4 text-center whitespace-nowrap">Ruangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-for="(row, index) in rows" :key="index">
                                <tr class="hover:bg-blue-50/50 transition-colors divide-x divide-gray-100">
                                    <td class="p-3 min-w-[120px]">
                                        <select :name="`details[${index}][hari]`" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-reguler capitalize cursor-pointer" required>
                                            <option value="">Hari</option>
                                            <option value="SENIN">Senin</option>
                                            <option value="SELASA">Selasa</option>
                                            <option value="RABU">Rabu</option>
                                            <option value="KAMIS">Kamis</option>
                                            <option value="JUMAT">Jumat</option>
                                            <option value="SABTU">Sabtu</option>
                                        </select>
                                    </td>
                                    <td class="p-3 min-w-[120px]">
                                        <input type="date" :name="`details[${index}][tanggal]`" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-reguler" required>
                                    </td>
                                    <td class="p-3 min-w-[120px]">
                                        <input type="time" :name="`details[${index}][jam_mulai]`" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-reguler" required>
                                    </td>
                                    <td class="p-3 min-w-[120px]">
                                        <input type="time" :name="`details[${index}][jam_selesai]`" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-reguler" required>
                                    </td>
                                    <td class="p-3 min-w-[120px]">
                                        <select :name="`details[${index}][id_mapel]`" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-reguler text-slate-900 capitalize cursor-pointer" required>
                                            <option value="">Pilih Mata Pelajaran</option>
                                            @foreach($mapel as $m)
                                                <option value="{{ $m->id_mapel }}">{{ $m->nama_mapel }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-3 min-w-[120px]">
                                        <select :name="`details[${index}][id_pengawas]`" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-reguler text-slate-600 capitalize cursor-pointer" required>
                                            <option value="">Pilih Pengawas</option>
                                            @foreach($pengawas as $p)
                                                <option value="{{ $p->id_guru }}">{{ $p->user->nama }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-3 min-w-[120px]">
                                        <select :name="`details[${index}][ruangan]`" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-reguler text-slate-600 capitalize cursor-pointer" required>
                                            <option value="">Ruangan</option>
                                            @foreach($ruangan as $r)
                                                <option value="{{ $r->nama_kelas }}">{{ $r->nama_kelas }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Action Buttons Row --}}
                <div class="flex flex-col md:flex-row gap-4 items-center">
                    <button type="button" @click="addRow" class="w-full md:flex-1 py-3.5 bg-blue-50 text-[#004AAD] rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-100 transition-all border-2 border-dashed border-blue-200 group">
                        <i class="fas fa-plus-circle mr-2 group-hover:scale-110 transition-transform"></i> Tambah Baris Jadwal Ujian
                    </button>
                    <button type="button" @click="removeRow" x-show="rows.length > 1" class="w-full md:w-auto px-8 py-3.5 bg-red-50 text-red-500 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-100 transition-all border border-red-100">
                        <i class="fas fa-trash-alt mr-2"></i> Hapus Baris Terakhir
                    </button>
                </div>

                {{-- Footer Submit --}}
                <div class="flex flex-row items-center gap-4 pt-6 mt-4 md:pt-10 md:mt-8 border-t border-gray-50">
                        <a href="{{ route('admin.jadwal.index', ['tab' => 'ujian']) }}" class="flex-1 px-4 py-4 bg-slate-100 text-slate-500 rounded-xl font-bold text-[10px] md:text-xs capitalize tracking-widest hover:bg-slate-200 transition-all text-center">
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
function handlerUjian() {
    return {
        rows: [{}, {}, {}], 
        addRow() {
            this.rows.push({});
        },
        removeRow() {
            if(this.rows.length > 1) this.rows.pop();
        }
    }
}
</script>
@endsection