@extends('layouts.admin')

@section('content')
<div class="flex-1 bg-[#f1f5f9] p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans" x-data="handlerEditUjian()">
    
    <div class="max-w-6xl mx-auto">
        
        @if($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-xl mb-6 text-xs font-bold uppercase shadow-lg shadow-red-200">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
        </div>
        @endif

        {{-- HEADER --}}
        <div class="bg-[#004AAD] rounded-t-2xl px-8 py-7 shadow-sm flex justify-between items-center">
            <div>
                <h2 class="text-white font-black text-xl tracking-widest uppercase">Edit Jadwal Ujian</h2>
                <p class="text-white/60 text-[10px] mt-1 uppercase tracking-[0.2em]">Manajemen Kurikulum • SMK Muhammadiyah 2 Metro</p>
            </div>
        </div>

        {{-- FORM UTAMA --}}
        <form action="{{ route('admin.jadwal.ujian.update', $ujian->id_jadwal_ujian) }}" method="POST" class="bg-white rounded-b-2xl shadow-sm border-x border-b border-gray-200 overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-8">
                {{-- Master Data Area (3 Kolom Sejajar) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 p-6 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="md:col-span-3 space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Judul / Nama Ujian</label>
                        <input type="text" name="judul" value="{{ old('judul', $ujian->judul) }}" required class="w-full border border-gray-300 rounded-xl px-4 py-3 text-xs text-slate-700 focus:border-blue-500 outline-none bg-white font-medium uppercase transition-all">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Pilih Kelas</label>
                        <select name="id_kelas" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-xs text-slate-700 focus:border-blue-500 outline-none bg-white font-medium uppercase cursor-pointer" required>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ $ujian->id_kelas == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Semester</label>
                        <div class="w-full border border-gray-300 rounded-xl px-4 py-3 text-xs text-slate-400 bg-gray-100 font-medium uppercase cursor-not-allowed">{{ $ujian->semester->nama_semester ?? '-' }}</div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] ml-1">Tahun Ajaran</label>
                        <div class="w-full border border-gray-300 rounded-xl px-4 py-3 text-xs text-slate-400 bg-gray-100 font-medium uppercase cursor-not-allowed">{{ $ujian->tahunAjaran->tahun_ajaran ?? '-' }}</div>
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
                                <th class="p-4 text-center whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-for="(row, index) in rows" :key="index">
                                <tr class="hover:bg-blue-50/50 transition-colors divide-x divide-gray-100">
                                    <td class="p-3">
                                        <select :name="`details[${index}][hari]`" x-model="row.hari" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-medium uppercase cursor-pointer" required>
                                            <option value="SENIN">SENIN</option><option value="SELASA">SELASA</option><option value="RABU">RABU</option>
                                            <option value="KAMIS">KAMIS</option><option value="JUMAT">JUMAT</option><option value="SABTU">SABTU</option>
                                        </select>
                                    </td>
                                    <td class="p-3 min-w-[120px]"><input type="date" :name="`details[${index}][tanggal]`" x-model="row.tanggal" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-medium" required></td>
                                    <td class="p-3 min-w-[120px]"><input type="time" :name="`details[${index}][jam_mulai]`" x-model="row.jam_mulai" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-medium" required></td>
                                    <td class="p-3 min-w-[120px]"><input type="time" :name="`details[${index}][jam_selesai]`" x-model="row.jam_selesai" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-medium" required></td>
                                    <td class="p-3 min-w-[120px]">
                                        <select :name="`details[${index}][id_mapel]`" x-model="row.id_mapel" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-medium uppercase" required>
                                            @foreach($mapel as $m) <option value="{{ $m->id_mapel }}">{{ $m->nama_mapel }}</option> @endforeach
                                        </select>
                                    </td>
                                    <td class="p-3 min-w-[120px]">
                                        <select :name="`details[${index}][id_pengawas]`" x-model="row.id_pengawas" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-medium uppercase" required>
                                            @foreach($pengawas as $p) <option value="{{ $p->id_guru }}">{{ $p->user->nama }}</option> @endforeach
                                        </select>
                                    </td>
                                    <td class="p-3 min-w-[120px]">
                                        <input type="text" :name="`details[${index}][ruangan]`" x-model="row.ruangan" class="w-full text-[11px] p-2.5 border border-gray-200 rounded-lg focus:border-blue-500 outline-none bg-white font-medium uppercase" required>
                                    </td>
                                    <td class="p-3 min-w-[120px] text-center">
                                        <button type="button" @click="removeSpecificRow(index)" class="w-8 h-8 text-red-500 rounded-lg hover:bg-red-50 hover:text-white transition-all"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col md:flex-row gap-4 items-center">
                    <button type="button" @click="addRow" class="w-full md:flex-1 py-3.5 bg-blue-50 text-[#004AAD] rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-100 transition-all border-2 border-dashed border-blue-200 group">
                        <i class="fas fa-plus-circle mr-2 group-hover:scale-110 transition-transform"></i> Tambah Baris Jadwal Ujian
                    </button>
                </div>

                {{-- Footer Submit --}}
                        <div class="flex flex-row items-center gap-4 pt-6 mt-4 md:pt-10 md:mt-8 border-t border-gray-50">

                        <a href="{{ route('admin.jadwal.index') }}" class="flex-1 px-4 py-4 bg-slate-100 text-slate-500 rounded-xl font-bold text-[10px] md:text-xs capitalize tracking-widest hover:bg-slate-200 transition-all text-center">
                            Batal</a>
                        <button type="submit" class="flex-1 bg-[#004AAD] text-white py-4 px-4 rounded-xl font-bold text-[10px] md:text-xs capitalize tracking-widest hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/10 active:scale-[0.98]">
                            Simpan</button>
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
    select, input { -webkit-appearance: none; }
</style>

<script>
function handlerEditUjian() {
    return {
        rows: @json($ujian->details), 
        addRow() {
            this.rows.push({ hari: 'SENIN', tanggal: '', jam_mulai: '', jam_selesai: '', id_mapel: '', id_pengawas: '', ruangan: '' });
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