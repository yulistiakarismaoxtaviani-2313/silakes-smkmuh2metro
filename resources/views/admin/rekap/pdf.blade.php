<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Presensi Kelas</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .title{
    text-align:center;
    margin-bottom:15px;
    line-height:1.1;
}

.title h2,
.title p{
    margin:0;
    padding:0;
    font-size:15px;
    font-weight:bold;
}

        .info-table{
            width:100%;
            margin-bottom:20px;
            border-collapse:collapse;
        }

        .info-table td{
            padding:5px;
        }

        .summary{
            width:100%;
            margin-bottom:20px;
            border-collapse:collapse;
        }

        .summary th,
        .summary td{
            border:1px solid #000;
            padding:8px;
            text-align:center;
        }

        .summary th{
            background:#e5e7eb;
        }

        .main-table{
            width:100%;
            border-collapse:collapse;
        }

        .main-table th,
        .main-table td{
            border:1px solid #000;
            padding:8px;
        }

        .main-table th{
            background:#e5e7eb;
            text-align:center;
            font-weight:bold;
        }

        .center{
            text-align:center;
        }

        .footer{
            margin-top:50px;
            width:100%;
        }

        .footer td{
            text-align:center;
            padding-top:50px;
        }
    </style>
</head>
<body>

    {{-- Judul --}}
    <div class="title">
        <h2>REKAP PRESENSI KELAS SISWA</h2>
    <p>
        SMK MUHAMMADIYAH 2 METRO
    </p>
        <p>
            TAHUN PELAJARAN :
           {{ strtoupper($kelas->tahunAjaran->tahun_ajaran ?? '-') }} | SEMESTER : {{ strtoupper($kelas->semester->nama_semester ?? '-') }}
        </p>
    </div>

    {{-- Informasi Kelas --}}
    <table class="info-table">
        <tr>
            <td width="20%"><strong>Nama Kelas</strong></td>
            <td width="2%">:</td>
            <td>{{ $kelas->nama_kelas }}</td>
        </tr>

        <tr>
            <td><strong>Wali Kelas</strong></td>
            <td>:</td>
            <td>{{ $kelas->guru->user->nama ?? 'Belum Ditentukan' }}</td>
        </tr>

        <tr>
            <td><strong>Total Siswa</strong></td>
            <td>:</td>
            <td>{{ $siswa->count() }} Siswa</td>
        </tr>
    </table>

    {{-- Ringkasan --}}
    <table class="summary">
        <thead>
            <tr>
                <th>Total Hadir</th>
                <th>Total Sakit</th>
                <th>Total Izin</th>
                <th>Total Alfa</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>{{ $total_stats['hadir'] }}</td>
                <td>{{ $total_stats['sakit'] }}</td>
                <td>{{ $total_stats['izin'] }}</td>
                <td>{{ $total_stats['alfa'] }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Tabel Detail --}}
    <table class="main-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">NIS</th>
                <th>Nama Siswa</th>
                 <th width="8%">JK</th>
                <th width="10%">Alfa</th>
                <th width="10%">Sakit</th>
                <th width="10%">Izin</th>
                <th width="10%">Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach($siswa as $index => $item)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td class="center">{{ $item->nis }}</td>
                <td>{{ $item->user?->nama ?? '-' }}</td>
                <td class="center">{{ $item->jenis_kelamin == 'L' ? 'L' : 'P' }}</td>
                <td class="center">{{ $item->alfa_count }}</td>
                <td class="center">{{ $item->sakit_count }}</td>
                <td class="center">{{ $item->izin_count }}</td>
                <td class="center">
                    {{ $item->alfa_count + $item->sakit_count + $item->izin_count }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <table class="footer">
        <tr>
            <td width="50%"></td>
            <td width="50%">
                Metro, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                <br><br>
                Mengetahui,
                <br><br><br><br><br>

                <strong>
                    {{ $kelas->guru->user->nama ?? 'Wali Kelas' }}
                </strong>
            </td>
        </tr>
    </table>

</body>
</html>