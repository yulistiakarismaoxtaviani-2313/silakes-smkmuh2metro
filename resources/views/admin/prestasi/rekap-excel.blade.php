<table>

    {{-- KOP SEKOLAH --}}
    <tr>
        <td colspan="7" align="center">
            MAJELIS PENDIDIKAN DASAR DAN MENENGAH
        </td>
    </tr>

    <tr>
        <td colspan="7" align="center">
            PIMPINAN CABANG MUHAMMADIYAH METRO BARAT
        </td>
    </tr>

    <tr>
        <td colspan="7" align="center">
            <strong>SMK MUHAMMADIYAH 2 METRO</strong>
        </td>
    </tr>

    <tr>
        <td colspan="7" align="center">
            NPSN : 10807594 | STATUS AKREDITASI "A" NSS : 402126103006
        </td>
    </tr>

    <tr>
        <td colspan="7" align="center">
            Alamat : Jl.Khairbras II Ganjar Asri No. 12 14/IV Kec. Metro Barat
        </td>
    </tr>

    <tr>
        <td colspan="7" align="center">
            Telp. (0725) 42983 Fax (0725) 42983 Kota Metro – Lampung 34114
        </td>
    </tr>

    <tr>
        <td colspan="7" align="center">
            E-Mail : smkmuhammadiyah2metro@gmail.com
        </td>
    </tr>

    {{-- Garis --}}
    <tr>
        <td style="border-top:3px solid #000;"></td>
        <td style="border-top:3px solid #000;"></td>
        <td style="border-top:3px solid #000;"></td>
        <td style="border-top:3px solid #000;"></td>
        <td style="border-top:3px solid #000;"></td>
        <td style="border-top:3px solid #000;"></td>
        <td style="border-top:3px solid #000;"></td>
    </tr>

    <tr>
        <td colspan="7" align="center">
            <strong>LAPORAN REKAP PRESTASI SISWA</strong>
        </td>
    </tr>

    <tr></tr>

    {{-- Informasi Sekolah --}}
    <tr>
        <td><strong>Nama Sekolah</strong></td>
        <td>:</td>
        <td>SMK MUHAMMADIYAH 2 METRO</td>
    </tr>

    <tr>
        <td><strong>NPSN</strong></td>
        <td>:</td>
        <td>10807594</td>
    </tr>

    <tr>
        <td><strong>Kab/Kota</strong></td>
        <td>:</td>
        <td>METRO PUSAT</td>
    </tr>

    <tr>
        <td><strong>Provinsi</strong></td>
        <td>:</td>
        <td>LAMPUNG</td>
    </tr>

    <tr></tr>

    {{-- Header Tabel --}}
    <tr>
        <th style="border:1px solid #000; background:#d9d9d9;">
            No
        </th>

        <th style="border:1px solid #000; background:#d9d9d9;">
            NIS
        </th>

        <th style="border:1px solid #000; background:#d9d9d9;">
            Nama Siswa
        </th>

        <th style="border:1px solid #000; background:#d9d9d9;">
            Kelas
        </th>

        <th style="border:1px solid #000; background:#d9d9d9;">
            Nama Lomba
        </th>

        <th style="border:1px solid #000; background:#d9d9d9;">
            Tingkat
        </th>

        <th style="border:1px solid #000; background:#d9d9d9;">
            Peringkat
        </th>

        <th style="border:1px solid #000; background:#d9d9d9;">
            Status
        </th>

        <th style="border:1px solid #000; background:#d9d9d9;">
            Bebas SPP
        </th>
    </tr>

    {{-- Data --}}
    @foreach($prestasi as $item)
    <tr>

        <td style="border:1px solid #000;">
            {{ $loop->iteration }}
        </td>

        <td style="border:1px solid #000;">
            {{ $item->siswa->nis ?? '-' }}
        </td>

        <td style="border:1px solid #000;">
            {{ $item->siswa->user->nama ?? '-' }}
        </td>

        <td style="border:1px solid #000;">
            {{ $item->siswa->kelas->nama_kelas ?? '-' }}
        </td>

        <td style="border:1px solid #000;">
            {{ $item->nama_lomba }}
        </td>

        <td style="border:1px solid #000;">
            {{ $item->tingkat }}
        </td>

        <td style="border:1px solid #000;">
            {{ $item->peringkat }}
        </td>

        <td style="border:1px solid #000;">
            {{ $item->status_validasi }}
        </td>

        <td style="border:1px solid #000;">
            {{ $item->bebas_spp }}
        </td>

    </tr>
    @endforeach

</table>