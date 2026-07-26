@extends(
    request()->routeIs('walikelas.*')
        ? 'layouts.walikelas'
        : 'layouts.guru'
)

@section('content')

@php
    $prefix = request()->routeIs('walikelas.*') ? 'walikelas' : 'guru';
@endphp

<div class="flex-1 bg-[#F8FAFC] p-0 md:p-8 overflow-y-auto custom-scrollbar font-sans">


    <div class="space-y-6">
        
        {{-- 2. Card Identitas Sesi --}}
        <div class="bg-white p-6 md:p-8 rounded-[1rem] shadow-sm border border-gray-200 flex flex-col md:flex-row items-center gap-8">
            <div class="flex-shrink-0">
                <div class="bg-blue-50 w-24 h-24 md:w-32 md:h-32 rounded-3xl flex items-center justify-center border border-blue-100 shadow-inner">
                    <i class="fa-solid fa-file-signature text-[#004aad] text-4xl md:text-5xl"></i>
                </div>
            </div>

            <div class="flex-grow w-full text-center md:text-left space-y-4">
                <div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-1">Nama Kelas</span>
                    <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tight leading-none">{{ $namaKelas }}</h2>
                </div>
                
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-3">
                    <span class="px-4 py-2 bg-slate-50 border border-gray-200 rounded-xl text-[10px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-regular fa-calendar-days text-[#004aad]"></i>
                        {{ \Carbon\Carbon::parse($presensi->tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                    </span>
                    <span class="px-4 py-2 bg-slate-50 border border-gray-200 rounded-xl text-[10px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-regular fa-clock text-[#004aad]"></i>
                        Jam Ke: {{ $presensi->jam_pelajaran }}
                    </span>
                    <span class="px-4 py-2 bg-emerald-50 border border-emerald-100 rounded-xl text-[10px] font-black text-emerald-600 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ $presensi->status_sesi }}
                    </span>
                </div>
            </div>
        </div>

        {{-- 3. Grid Statistik --}}
<div class="grid grid-cols-3 gap-2 md:gap-6">
    
    {{-- Card Statistik --}}
    <div class="bg-white p-2 md:p-6 rounded-2xl md:rounded-3xl shadow-sm border-b-2 md:border-b-4 border-b-[#004aad] flex items-center justify-between">
        <div class="flex flex-col">
            <p class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total</p>
            <p class="text-lg md:text-3xl font-black text-slate-800">{{ $rekap['total'] }}</p>
        </div>
        {{-- Ikon tetap tampil di samping --}}
        <div class="w-6 h-6 md:w-12 md:h-12 bg-blue-50 rounded-lg md:rounded-xl flex items-center justify-center shrink-0">
            <i class="fa-solid fa-users text-[#004aad] text-sm md:text-base"></i>
        </div>
    </div>

    {{-- Ulangi untuk Card lainnya dengan struktur yang sama --}}
    <div class="bg-white p-3 md:p-6 rounded-2xl md:rounded-3xl shadow-sm border-b-2 md:border-b-4 border-b-[#004aad] flex items-center justify-between">
        <div class="flex flex-col">
            <p class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Sudah Absen</p>
            <p class="text-lg md:text-3xl font-black text-slate-800">{{ $rekap['total'] - $rekap['belum'] }}</p>
        </div>
        <div class="w-6 h-6 md:w-12 md:h-12 bg-blue-50 rounded-lg md:rounded-xl flex items-center justify-center shrink-0">
            <i class="fa-solid fa-user-check text-[#004aad] text-sm md:text-base"></i>
        </div>
    </div>

    <div class="bg-white p-3 md:p-6 rounded-2xl md:rounded-3xl shadow-sm border-b-2 md:border-b-4 border-b-[#004aad] flex items-center justify-between">
        <div class="flex flex-col">
            <p class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Belum Absen</p>
            <p class="text-lg md:text-3xl font-black text-slate-800">{{ $rekap['belum'] }}</p>
        </div>
        <div class="w-6 h-6 md:w-12 md:h-12 bg-blue-50 rounded-lg md:rounded-xl flex items-center justify-center shrink-0">
            <i class="fa-solid fa-user-xmark text-[#004aad] text-sm md:text-base"></i>
        </div>
    </div>
        </div>

        {{-- 4. Tabel Data Siswa --}}
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-black text-slate-800 text-[11px] uppercase tracking-[0.2em]">Daftar Presensi Real-Time</h3>
                <span class="text-[9px] bg-white px-3 py-1 rounded-full border border-gray-200 font-bold text-slate-400 uppercase">Auto-Refresh Aktif</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-white">
                            <th class="px-6 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest w-16">No</th>
                            <th class="px-6 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest w-28">NIS</th>
                            <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Lengkap</th>
                            <th class="px-6 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest w-36">Status Sistem</th>
                            <th class="px-6 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest w-56">Koreksi Guru</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($detail_presensi as $item)
                        <tr id="row-{{ $item->id }}" class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4 text-center font-reguler text-slate-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-center font-reguler text-slate-600 tracking-wider">{{ $item->siswa->nis ?? '-' }}</td>
                            <td class="px-6 py-4 text-left font-reguler text-slate-700 uppercase group-hover:text-[#004aad] transition-colors">
                                {{ $item->siswa->user->nama ?? 'N/A' }}
                            </td>
                            
                            {{-- Status Sistem Berdasarkan Pilihan Siswa --}}
                            <td id="status-badge-{{ $item->id }}" class="px-6 py-4 text-center">
                                @if($item->status == 'hadir')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100 text-[9px] font-black uppercase">
                                       </i> Hadir
                                    </span>
                                @elseif($item->status == 'belum')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-red-50 text-red-600 border border-red-100 text-[9px] font-black uppercase animate-pulse">
                                        </i> Belum
                                    </span>
                                @elseif($item->status == 'izin')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-blue-50 text-blue-600 border border-blue-100 text-[9px] font-black uppercase">
                                     </i> Izin
                                    </span>
                                @elseif($item->status == 'sakit')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-orange-50 text-orange-600 border border-orange-100 text-[9px] font-black uppercase">
                                        </i> Sakit
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-rose-50 text-rose-600 border border-rose-100 text-[9px] font-black uppercase">
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </td>

                            {{-- Kolom Koreksi Guru (Aksi Pembanding Realita Kelas) --}}
                            <td id="aksi-container-{{ $item->id }}" class="px-6 py-4 text-center">
                                @if($item->status == 'hadir')
                                    {{-- Siswa Mengaku Hadir, Tapi Guru Cek Fisik Orangnya Ga Ada --}}
                                    <button onclick="koreksiStatus('{{ $item->id }}', 'alfa', this)" 
                                            class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 rounded-xl text-[9px] font-black uppercase transition flex items-center justify-center gap-1 mx-auto shadow-sm">
                                        <i class="fa-solid fa-user-slash"></i> Ambil Tindakan (Alfa)
                                    </button>
                                @elseif($item->status == 'belum')
                                    {{-- Siswa Belum Absen, Guru Bisa Bantu Tandai Langsung --}}
                                    <div class="inline-flex rounded-xl border border-gray-200 p-0.5 bg-gray-50 gap-0.5 shadow-sm">
                                        <button onclick="koreksiStatus('{{ $item->id }}', 'hadir', this)" class="px-2 py-1 hover:bg-white text-slate-600 hover:text-emerald-600 rounded-lg text-[8px] font-black uppercase transition">Hadir</button>
                                        <button onclick="koreksiStatus('{{ $item->id }}', 'izin', this)" class="px-2 py-1 hover:bg-white text-slate-600 hover:text-blue-600 rounded-lg text-[8px] font-black uppercase transition">Izin</button>
                                        <button onclick="koreksiStatus('{{ $item->id }}', 'sakit', this)" class="px-2 py-1 hover:bg-white text-slate-600 hover:text-orange-600 rounded-lg text-[8px] font-black uppercase transition">Sakit</button>
                                    </div>
                                @else
                                    {{-- Mengembalikan data ke kondisi hadir biasa --}}
                                    <button onclick="koreksiStatus('{{ $item->id }}', 'hadir', this)" 
                                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border border-gray-200 text-slate-600 rounded-xl text-[9px] font-bold uppercase transition flex items-center justify-center gap-1 mx-auto shadow-sm">
                                        <i class="fa-solid fa-rotate-left"></i> Pulihkan Hadir
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 5. Rekap Akhir & Navigasi --}}
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-200">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="p-4 bg-slate-50 rounded-2xl border border-gray-100 text-center">
                    <span class="block text-[8px] font-black text-slate-400 uppercase mb-1">Hadir</span>
                    <span id="rekap-hadir" class="text-xl font-black text-emerald-600">{{ $rekap['hadir'] }}</span>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-gray-100 text-center">
                    <span class="block text-[8px] font-black text-slate-400 uppercase mb-1">Izin</span>
                    <span id="rekap-izin" class="text-xl font-black text-blue-600">{{ $rekap['izin'] }}</span>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-gray-100 text-center">
                    <span class="block text-[8px] font-black text-slate-400 uppercase mb-1">Sakit</span>
                    <span id="rekap-sakit" class="text-xl font-black text-orange-500">{{ $rekap['sakit'] }}</span>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-gray-100 text-center">
                    <span class="block text-[8px] font-black text-slate-400 uppercase mb-1">Alfa</span>
                    <span id="rekap-alfa" class="text-xl font-black text-red-600">{{ $rekap['alfa'] }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <a href="{{ url()->current() }}?id_kelas={{ request('id_kelas') }}" 
                       class="flex-1 bg-white border border-gray-200 text-slate-600 py-4 rounded-2xl text-[10px] font-black uppercase hover:bg-slate-50 transition flex items-center justify-center gap-3 no-underline">
                        <i class="fa-solid fa-rotate text-[#004aad]"></i> Refresh Data
                    </a>
                    
                </div>
                <form action="{{ route($prefix.'.presensi.confirm', $presensi->id_presensi) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_kelas" value="{{ request('id_kelas') }}">
                    <button type="submit" class="w-full bg-[#004aad] text-white py-4 rounded-2xl text-[11px] font-black uppercase hover:bg-blue-800 transition shadow-xl shadow-blue-100 flex items-center justify-center gap-3 tracking-[0.1em]">
                        <i class="fa-solid fa-lock"></i> Validasi & Kunci Presensi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-2xl { border-radius: 1rem !important; }
    .rounded-3xl { border-radius: 1.5rem !important; }
    .no-underline { text-decoration: none !important; }
</style>

<script>
function koreksiStatus(idDetail, statusBaru, element) {
    element.style.opacity = '0.5';

    // Disesuaikan mengarah ke url prefix /presensi/update-status/{id} sesuai web.php kamu

    @php
    $updateUrl = request()->routeIs('walikelas.*')
        ? '/walikelas/presensi-mengajar/update-status'
        : '/guru/presensi/update-status';
        @endphp
    fetch(`{{ $updateUrl }}/${idDetail}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: statusBaru,
            id_kelas: '{{ request('id_kelas') }}',
            id_presensi: '{{ $presensi->id_presensi }}' 
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP error ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        element.style.opacity = '1';
        
        if (data.success) {
            const idBaru = data.id_aktual;
            
            // 1. Sinkronisasi ID kontainer baris agar state tidak tabrakan
            const rowEl = document.getElementById(`row-${idDetail}`);
            if (rowEl && idDetail !== idBaru) {
                rowEl.id = `row-${idBaru}`;
                const bdg = document.getElementById(`status-badge-${idDetail}`);
                if(bdg) bdg.id = `status-badge-${idBaru}`;
                const aks = document.getElementById(`aksi-container-${idDetail}`);
                if(aks) aks.id = `aksi-container-${idBaru}`;
            }

            // 2. Render Ulang Badge Status Sistem
            const badgeTd = document.getElementById(`status-badge-${idBaru}`);
            let badgeHTML = '';
            if (statusBaru === 'hadir') {
                badgeHTML = `<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 text-[9px] font-black uppercase"><i class="fa-solid fa-check"></i> Hadir</span>`;
            } else if (statusBaru === 'izin') {
                badgeHTML = `<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-50 text-blue-600 border border-blue-100 text-[9px] font-black uppercase"><i class="fa-solid fa-envelope"></i> Izin</span>`;
            } else if (statusBaru === 'sakit') {
                badgeHTML = `<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-orange-50 text-orange-600 border border-orange-100 text-[9px] font-black uppercase"><i class="fa-solid fa-house-chimney-medical"></i> Sakit</span>`;
            } else if (statusBaru === 'alfa') {
                badgeHTML = `<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-rose-50 text-rose-600 border border-rose-100 text-[9px] font-black uppercase"><i class="fa-solid fa-xmark"></i> Alfa</span>`;
            } else {
                badgeHTML = `<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-50 text-red-600 border border-red-100 text-[9px] font-black uppercase animate-pulse"><i class="fa-solid fa-clock-rotate-left"></i> Belum</span>`;
            }
            if(badgeTd) badgeTd.innerHTML = badgeHTML;

            // 3. Render Ulang Tombol Aksi di Sebelah Kanan
            const aksiTd = document.getElementById(`aksi-container-${idBaru}`);
            let aksiHTML = '';
            if (statusBaru === 'hadir') {
                aksiHTML = `<button onclick="koreksiStatus('${idBaru}', 'alfa', this)" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 rounded-xl text-[9px] font-black uppercase transition flex items-center justify-center gap-1 mx-auto shadow-sm"><i class="fa-solid fa-user-slash"></i> Ambil Tindakan (Alfa)</button>`;
            } else if (statusBaru === 'belum') {
                aksiHTML = `<div class="inline-flex rounded-xl border border-gray-200 p-0.5 bg-gray-50 gap-0.5 shadow-sm">
                    <button onclick="koreksiStatus('${idBaru}', 'hadir', this)" class="px-2 py-1 hover:bg-white text-slate-600 hover:text-emerald-600 rounded-lg text-[8px] font-black uppercase transition">Hadir</button>
                    <button onclick="koreksiStatus('${idBaru}', 'izin', this)" class="px-2 py-1 hover:bg-white text-slate-600 hover:text-blue-600 rounded-lg text-[8px] font-black uppercase transition">Izin</button>
                    <button onclick="koreksiStatus('${idBaru}', 'sakit', this)" class="px-2 py-1 hover:bg-white text-slate-600 hover:text-orange-600 rounded-lg text-[8px] font-black uppercase transition">Sakit</button>
                </div>`;
            } else {
                aksiHTML = `<button onclick="koreksiStatus('${idBaru}', 'hadir', this)" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border border-gray-200 text-slate-600 rounded-xl text-[9px] font-bold uppercase transition flex items-center justify-center gap-1 mx-auto shadow-sm"><i class="fa-solid fa-rotate-left"></i> Pulihkan Hadir</button>`;
            }
            if(aksiTd) aksiTd.innerHTML = aksiHTML;

            // 4. Perbarui data Widget Rekap secara dinamis
           if (data.rekapbaru) {

    const rekapHadir = document.getElementById('rekap-hadir');
    const rekapIzin = document.getElementById('rekap-izin');
    const rekapSakit = document.getElementById('rekap-sakit');
    const rekapAlfa = document.getElementById('rekap-alfa');
    const statBelum = document.getElementById('stat-belum');
    const statMengisi = document.getElementById('stat-mengisi');

    if (rekapHadir) rekapHadir.innerText = data.rekapbaru.hadir;
    if (rekapIzin) rekapIzin.innerText = data.rekapbaru.izin;
    if (rekapSakit) rekapSakit.innerText = data.rekapbaru.sakit;
    if (rekapAlfa) rekapAlfa.innerText = data.rekapbaru.alfa;

    if (statBelum) statBelum.innerText = data.rekapbaru.belum;
    if (statMengisi) statMengisi.innerText = data.rekapbaru.total - data.rekapbaru.belum;
}
        } else {
            alert('Gagal mengeksekusi koreksi.');
        }
    })
   .catch(error => {
    element.style.opacity = '1';
    console.error(error);
    alert('Terjadi kesalahan saat memproses data.');
});
}
</script>
@endsection