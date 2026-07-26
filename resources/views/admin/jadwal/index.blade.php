@extends('layouts.admin')

@section('content')
{{-- Gunakan variabel tab dari request atau default ke 'pelajaran' --}}
<div class="p-0 md:p-6 bg-[#f1f5f9] min-h-screen font-sans" x-data="{ tab: '{{ request('tab', 'pelajaran') }}' }">

    {{-- 1. Statistik Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-calendar text-xl md:text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Tahun Ajaran</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $tahunAjaranAktif }}</p>
            </div>
        </div>

        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-calendar-alt text-xl md:text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Total Jadwal</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $totalJadwal }}</p>
            </div>
        </div>

        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-door-open text-xl md:text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Total Kelas</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $totalKelas }} Kelas</p>
            </div>
        </div>

        <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border-b-4 border-b-[#004aad] flex flex-col md:flex-row items-center gap-3 md:gap-5">
        <div class="bg-blue-50 w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center text-[#004aad] shadow-sm border border-blue-100 shrink-0">
            <i class="fas fa-chalkboard-user text-xl md:text-2xl"></i>
            </div>
            <div class="text-center md:text-left">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">Total Guru</p>
            <p class="text-xs md:text-base font-bold text-slate-800 uppercase leading-none">{{ $totalGuru }} Orang</p>
            </div>
        </div>
    </div>

    {{-- 2. Tab Switcher --}}
    <div class="flex bg-white p-1 md:p-2 rounded-xl shadow-sm border border-gray-200 mb-6 md:mb-8 w-full">
        <button @click="tab = 'pelajaran'" 
            :class="tab === 'pelajaran' ? 'bg-[#004aad] text-white shadow-md' : 'text-gray-500 hover:bg-blue-100'"
        class="flex-1 py-2.5 md:py-3 rounded-lg text-[10px] md:text-sm font-bold uppercase transition-all duration-200 tracking-tight md:tracking-normal">
            Jadwal Pelajaran
        </button>
        <button @click="tab = 'ujian'" 
            :class="tab === 'ujian' ? 'bg-[#004aad] text-white shadow-md' : 'text-gray-500 hover:bg-blue-100'"
        class="flex-1 py-2.5 md:py-3 rounded-lg text-[10px] md:text-sm font-bold uppercase transition-all duration-200 tracking-tight md:tracking-normal">
            Jadwal Ujian
        </button>
    </div>

    {{-- 3. Main Container (Header, Filter, Tabel, Footer) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-300 overflow-hidden">
        
        {{-- Header Area --}}
        <div class="p-6 border-b border-gray-300 bg-white relative text-center">
            <h2 class="font-bold text-gray-800 uppercase tracking-widest text-[14px] md:text-base"
                x-text="tab === 'pelajaran' ? 'Data Kelola Jadwal Pelajaran' : 'Data Kelola Jadwal Ujian Siswa'">
            </h2>
            
            <div class="mt-4 md:mt-0 md:absolute right-3 md:right-6 md:top-1/2 md:-translate-y-1/2 flex justify-center">
                <a :href="tab === 'pelajaran' ? '{{ route('admin.jadwal.pelajaran.create') }}' : '{{ route('admin.jadwal.ujian.create') }}'" 
                    class="bg-[#004aad] text-white px-5 py-2 rounded-lg inline-flex items-center gap-2 text-xs font-bold hover:bg-blue-800 transition uppercase shadow-sm w-max">
                    <i class="fas fa-plus-circle"></i> Buat <span x-text="tab === 'pelajaran' ? 'Jadwal Pelajaran' : 'Jadwal Ujian'"></span>
                </a>
            </div>
        </div>

        {{-- Filter Area --}}
        <div class="p-4 md:p-6 bg-gray-50/50 border-b border-gray-300">
            <form action="{{ route('admin.jadwal.index') }}" method="GET">
                <input type="hidden" name="tab" :value="tab">

                {{-- Layout Pelajaran --}}
                <template x-if="tab === 'pelajaran'">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 w-full">
                        <select name="id_semester" onchange="this.form.submit()" class="bg-white border border-gray-300 text-slate-900 text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] w-full p-2.5 font-reguler capitalize">
                            <option value="">Semua Semester</option>
                            @foreach($semester as $s)
                                <option value="{{ $s->id_semester }}" {{ request('id_semester') == $s->id_semester ? 'selected' : '' }}>{{ $s->nama_semester }}</option>
                            @endforeach
                        </select>

                        <select name="id_kelas" onchange="this.form.submit()" class="bg-white border border-gray-300 text-slate-900 text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] block w-full p-2.5 font-reguler capitalize">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>

                        <div class="relative w-full col-span-2 md:col-span-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400 text-[10px]"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" class="bg-white border border-gray-300 text-gray-600 text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] block w-full p-2.5 pl-10 font-reguler capitalize" placeholder="Cari Pelajaran...">
                        </div>
                    </div>
                </template>

                {{-- Layout Ujian --}}
                <template x-if="tab === 'ujian'">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full">
                        <select name="id_semester" onchange="this.form.submit()" class="bg-white border border-gray-300 text-slate-900 text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] block w-full p-2.5 font-reguler capitalize">
                            <option value="">Semua Semester</option>
                            @foreach($semester as $s)
                                <option value="{{ $s->id_semester }}" {{ request('id_semester') == $s->id_semester ? 'selected' : '' }}>{{ $s->nama_semester }}</option>
                            @endforeach
                        </select>

                        <select name="id_kelas" onchange="this.form.submit()" class="bg-white border border-gray-300 text-slate-900 text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] block w-full p-2.5 font-reguler capitalize">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>

                        <select name="id_jenis_ujian" onchange="this.form.submit()" class="bg-white border border-gray-300 text-slate-900 text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] block w-full p-2.5 font-reguler capitalize">
                            <option value="">Semua Jenis Ujian</option>
                            @foreach($jenisUjian as $ju)
                                <option value="{{ $ju->id_jenis_ujian }}" {{ request('id_jenis_ujian') == $ju->id_jenis_ujian ? 'selected' : '' }}>{{ $ju->nama_ujian }}</option>
                            @endforeach
                        </select>

                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400 text-[10px]"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" class="bg-white border border-gray-300 text-gray-600 text-[11px] rounded-xl focus:ring-[#004aad] focus:border-[#004aad] block w-full p-2.5 pl-10 font-reguler capitalize" placeholder="Cari Judul Ujian...">
                        </div>
                    </div>
                </template>
            </form>
        </div>

        {{-- Tabel Area --}}
        <div id="tabel-jadwal" class="w-full overflow-x-auto">
            {{-- Tabel Pelajaran --}}
            <table x-show="tab === 'pelajaran'" class="w-full text-sm border-collapse border-spacing-0">
                <thead class="bg-gray-50 border-b-2 border-gray-300">
                    <tr class="text-slate-700 uppercase text-xs tracking-wider">
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">No</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Nama Kelas</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Wali Kelas</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Jumlah Mapel</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Status</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 uppercase">
                    @forelse($pelajaran as $index => $k)
                    <tr class="hover:bg-blue-50/30 transition-colors text-xs text-center">
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $pelajaran->firstItem() + $index }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $k->nama_kelas }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-left border-r border-gray-300 whitespace-nowrap">{{ $k->guru->user->nama ?? 'TIDAK ADA WALI KELAS' }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $k->jadwalPelajaran->count() }} MAPEL</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-lg text-[10px] font-reguler border border-green-200 uppercase">Tersimpan</span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                {{-- Tombol Lihat/Detail --}}
                                <a href="{{ route('admin.jadwal.pelajaran.show', $k->id_kelas) }}" 
                                   class="bg-blue-50 text-[#004aad] w-9 h-9 flex items-center justify-center rounded-xl hover:bg-[#004aad] hover:text-white transition-all shadow-sm border border-blue-100"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.jadwal.pelajaran.edit', $k->id_kelas) }}" 
                                   class="bg-orange-50 text-orange-600 w-9 h-9 flex items-center justify-center rounded-xl hover:bg-orange-500 hover:text-white transition-all shadow-sm border border-orange-100"
                                   title="Edit Data">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.jadwal.pelajaran.destroy', $k->id_kelas) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus semua jadwal kelas ini?')" 
                                            class="bg-red-50 text-red-600 w-9 h-9 flex items-center justify-center rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100"
                                            title="Hapus Data">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-20 text-gray-400 italic text-center font-medium tracking-widest uppercase">Jadwal pelajaran belum tersedia.</td></tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Tabel Ujian --}}
            <table x-show="tab === 'ujian'" x-cloak class="w-full text-sm border-collapse border-spacing-0">
                <thead class="bg-gray-50 border-b-2 border-gray-300">
                    <tr class="text-slate-700 uppercase text-xs tracking-wider">
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">No</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Nama Kelas</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Jenis Ujian</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Judul Jadwal</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Status</th>
                        <th class="p-3 md:p-4 whitespace-nowrap font-bold border-r border-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 uppercase">
                    @forelse($ujian as $index => $u)
                    <tr class="hover:bg-blue-50/30 transition-colors text-xs text-center">
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $ujian->firstItem() + $index }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">{{ $u->kelas->nama_kelas ?? '-' }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-left border-r border-gray-300 whitespace-nowrap">{{ $u->jenisUjian->nama_ujian ?? '-' }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-left border-r border-gray-300 whitespace-nowrap">{{ $u->judul }}</td>
                        <td class="p-3 md:p-4 text-gray-600 font-medium text-center border-r border-gray-300 whitespace-nowrap">
                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-lg text-[10px] font-reguler border border-green-200 uppercase">Aktif</span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                {{-- Tombol Lihat/Detail --}}
                                <a href="{{ route('admin.jadwal.ujian.show', $u->id_jadwal_ujian) }}" 
                                   class="bg-blue-50 text-[#004aad] w-9 h-9 flex items-center justify-center rounded-xl hover:bg-[#004aad] hover:text-white transition-all shadow-sm border border-blue-100"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.jadwal.ujian.edit', $u->id_jadwal_ujian) }}" 
                                   class="bg-orange-50 text-orange-600 w-9 h-9 flex items-center justify-center rounded-xl hover:bg-orange-500 hover:text-white transition-all shadow-sm border border-orange-100"
                                   title="Edit Data">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.jadwal.ujian.destroy', $u->id_jadwal_ujian) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus jadwal ujian ini?')" 
                                            class="bg-red-50 text-red-600 w-9 h-9 flex items-center justify-center rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100"
                                            title="Hapus Data">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-20 text-gray-400 italic text-center font-medium tracking-widest uppercase">Jadwal ujian belum tersedia.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer & Pagination --}}
        <div class="bg-white p-4 border-t border-gray-200 rounded-b-xl">

    <div class="text-[11px] flex items-center gap-3">
    <span>Menampilkan</span>

    <div class="text-gray-500 whitespace-nowrap">
                    <span x-text="tab === 'pelajaran' ? '{{ $pelajaran->firstItem() ?? 0 }}' : '{{ $ujian->firstItem() ?? 0 }}'"></span>
                    - 
                    <span x-text="tab === 'pelajaran' ? '{{ $pelajaran->lastItem() ?? 0 }}' : '{{ $ujian->lastItem() ?? 0 }}'"></span>
                </span>
                dari
                <span class="font-reguler" x-text="tab === 'pelajaran' ? '{{ $pelajaran->total() }}' : '{{ $ujian->total() }}'"></span>
                <span x-text="tab === 'pelajaran' ?"></span>
            </div>

            <div class="ml-auto custom-pagination">
                <div x-show="tab === 'pelajaran'">
                    {{ $pelajaran->fragment('tabel-jadwal')->appends(['tab' => 'pelajaran'])->links() }}
                </div>
                <div x-show="tab === 'ujian'">
                    {{ $ujian->fragment('tabel-jadwal')->appends(['tab' => 'ujian'])->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    /* ===========================
       Konsistensi UI
    =========================== */
    .rounded-xl {
        border-radius: 0.75rem !important;
    }

    .border-gray-300 {
        border-color: #cbd5e1 !important;
    }

    /* ===========================
       Pagination - Desktop
    =========================== */
    @media (min-width: 768px) {

        /* Sembunyikan layout mobile bawaan Laravel */
        .custom-pagination nav > div:first-child {
            display: none !important;
        }

        /* Tampilkan layout desktop */
        .custom-pagination nav > div:last-child {
            display: flex !important;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
    }
</style>
@endsection