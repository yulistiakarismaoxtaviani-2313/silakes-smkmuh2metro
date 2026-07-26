<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Presensi Siswa</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
            color:#000;
        }

        .header{
            text-align:center;
            margin-bottom:20px;
        }

        .header h2{
            margin:0;
            font-size:18px;
        }

        .header p{
            margin:3px 0;
        }

        .info{
            margin-bottom:20px;
        }

        .info table{
            width:100%;
        }

        .info td{
            padding:4px;
        }

        table.rekap{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
        }

        table.rekap th,
        table.rekap td{
            border:1px solid #000;
            padding:8px;
            text-align:center;
        }

        table.rekap th{
            background:#f1f1f1;
        }

        .persentase{
            margin-top:20px;
            padding:10px;
            border:1px solid #000;
            font-weight:bold;
        }

        .footer{
            margin-top:50px;
            text-align:right;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>REKAP PRESENSI SISWA</h2>
        <p>Tahun Ajaran {{ $tahunAjaran->tahun_ajaran }} | Semester {{ ucfirst($semester->nama_semester) }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="120">Nama</td>
                <td width="10">:</td>
                <td>{{ $siswa->user->nama }}</td>
            </tr>

            <tr>
                <td>NIS</td>
                <td>:</td>
                <td>{{ $siswa->nis }}</td>
            </tr>

            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <table class="rekap">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Hadir</th>
                <th>Alfa</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>% Hadir</th>
            </tr>
        </thead>

        <tbody>
            @foreach($rekap_bulanan as $bulan => $data)
            <tr>
                <td>{{ $bulan }}</td>
                <td>{{ $data['hadir'] }}</td>
                <td>{{ $data['alfa'] }}</td>
                <td>{{ $data['izin'] }}</td>
                <td>{{ $data['sakit'] }}</td>
                <td>
                    {{ $data['total'] > 0
                        ? number_format(($data['hadir'] / $data['total']) * 100, 0)
                        : 0 }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="persentase">
        Persentase Kehadiran Semester :
        {{ $rekap_total['total'] > 0
            ? number_format(($rekap_total['hadir'] / $rekap_total['total']) * 100, 1)
            : 0 }}%
    </div>

    <div class="footer">
        <p>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <br><br><br>

        <p><strong>{{ $siswa->user->nama }}</strong></p>
    </div>

</body>
</html>