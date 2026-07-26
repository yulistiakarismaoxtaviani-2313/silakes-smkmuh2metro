<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Prestasi Siswa</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop td {
            border: none;
        }

        .judul {
            text-align: center;
            line-height: 1.3;
        }

        .judul h2 {
            margin: 0;
            font-size: 24px;
        }

        .judul p {
            margin: 0;
        }

        

        .info td {
            border: none;
            padding: 2px;
        }

        .data th,
        .data td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        .data th {
            background: #f3f4f6;
            font-weight: bold;
        }

        .left {
            text-align: left !important;
        }

        .ttd {
            width: 250px;
            float: right;
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>
<body>


{{-- KOP SURAT --}}
<table class="kop">
    <tr>
        <td width="15%" align="center">
            <img src="{{ public_path('img/logo-muh.png') }}" width="150">
        </td>

        <td width="70%">
            <div class="judul">

                <p>MAJELIS PENDIDIKAN DASAR DAN MENENGAH</p>

                <p>PIMPINAN CABANG MUHAMMADIYAH METRO BARAT</p>

                <h2 style="margin:5px 0;">
                    SMK MUHAMMADIYAH 2 METRO
                </h2>

                <p>
                    NPSN : 10807594 |
                    STATUS AKREDITASI "A"
                    NSS : 402126103006
                </p>

                <p>
                    Alamat : Jl.Khairbras II Ganjar Asri No. 12 14/IV
                    Kec. Metro Barat
                </p>

                <p>
                    ☎ (0725) 42983
                    Fax (0725) 42983
                    Kota Metro – Lampung 34114
                </p>

                <p>
                    E-Mail : smkmuhammadiyah2metro@gmail.com
                </p>

            </div>
        </td>

        <td width="15%" align="center">
            <img src="{{ public_path('img/logo-smk2.png') }}" width="150">
        </td>
    </tr>
</table>

<hr style="border:2px solid black; margin:5px 0 1px 0;">
<hr style="border:0.5px solid black; margin:0;">

<h3 style="text-align:center; margin-bottom:15px;">
    LAPORAN REKAP PRESTASI SISWA
</h3>

<table class="info">
    <tr>
        <td width="120">Nama Sekolah</td>
        <td width="10">:</td>
        <td>SMK MUHAMMADIYAH 2 METRO</td>
    </tr>
    <tr>
        <td>NPSN</td>
        <td>:</td>
        <td>10807594</td>
    </tr>
    <tr>
        <td>Kab/Kota</td>
        <td>:</td>
        <td>Metro</td>
    </tr>
    <tr>
        <td>Provinsi</td>
        <td>:</td>
        <td>Lampung</td>
    </tr>
</table>

<br>

<table class="data">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="15%">NIS</th>
            <th width="25%">Nama Siswa</th>
            <th width="10%">Kelas</th>
            <th width="25%">Nama Lomba</th>
            <th width="12%">Tingkat</th>
            <th width="10%">Peringkat</th>
            <th width="10%">Status</th>
            <th width="10%">Bebas SPP</th>
        </tr>
    </thead>

    <tbody>
        @foreach($prestasi as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>

            <td>
                {{ $item->siswa->nis ?? '-' }}
            </td>

            <td class="left">
                {{ $item->siswa->user->nama ?? '-' }}
            </td>

            <td>
                {{ $item->siswa->kelas->nama_kelas ?? '-' }}
            </td>

            <td class="left">
                {{ $item->nama_lomba }}
            </td>

            <td>
                {{ $item->tingkat ?? '-' }}
            </td>
            
            <td>
                {{ $item->peringkat }}
            </td>

            <td>
                {{ $item->status_validasi }}
            </td>

            <td>
                {{ $item->bebas_spp ?? '-' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>