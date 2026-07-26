
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
    @page {
        margin: 1.2cm;
    }

    body {
        font-family: Helvetica, sans-serif;
        color: #000;
        font-size: 11pt;
        background: #fff;
    }

    /* =========================
        HEADER
    ========================= */

    .judul {
        text-align: center;
        margin-bottom: 15px;
    }

    .judul h1 {
        margin: 0;
        font-size: 24px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .judul p {
        margin-top: 6px;
        font-size: 13px;
    }

    hr {
        border: none;
        border-top: 2px solid #000;
        margin: 15px 0 20px;
    }

    /* =========================
        INFO
    ========================= */

    .card-info {
        border: 1px solid #000;
        padding: 12px;
        margin-bottom: 20px;
    }

    .card-info table {
        width: 100%;
        border-collapse: collapse;
    }

    .card-info td {
        padding: 4px 6px;
        font-size: 11pt;
        vertical-align: top;
    }

    .label {
        width: 150px;
        font-weight: bold;
    }

    /* =========================
        TABEL
    ========================= */

    .main-table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-header {
        background: #fff;
        border: 1px solid #000;
        text-align: center;
        font-weight: bold;
        font-size: 12pt;
        padding: 10px;
    }

    .sub-header th {
        background: #fff;
        border: 1px solid #000;
        text-align: center;
        font-weight: bold;
        padding: 8px;
    }

    .main-table td {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
        font-size: 11pt;
    }

    .hari-column {
        font-weight: bold;
        vertical-align: middle;
    }

    .text-left {
        text-align: left;
    }

    .footer {
        margin-top: 40px;
        text-align: right;
        font-size: 11pt;
    }
    </style>
</head>

<body>

<div class="judul">
    <h1>JADWAL PELAKSANAAN UJIAN</h1>
    <p>
        Tahun Ajaran {{ $tahun_ajaran }}
        Semester {{ ucfirst(strtolower($semester)) }}
    </p>
</div>

<hr>

<div class="card-info">
    <table>
        <tr>
            <td class="label">Nama Siswa</td>
            <td>: {{ ucwords(strtolower($siswa->user->nama ?? '-')) }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td>: {{ $stats['nama_kelas'] }}</td>
        </tr>
        <tr>
            <td class="label">Tahun Ajaran</td>
            <td>: {{ $tahun_ajaran }} ({{ ucfirst(strtolower($semester)) }})</td>
        </tr>
    </table>
</div>

<table class="main-table">
    <thead>

        <tr>
            <th colspan="5" class="table-header">
                JADWAL PELAKSANAAN UJIAN
            </th>
        </tr>

        <tr class="sub-header">
            <th width="20%">Hari</th>
            <th width="20%">Waktu</th>
            <th width="25%">Mata Pelajaran</th>
            <th width="25%">Pengawas</th>
            <th width="10%">Ruangan</th>
        </tr>

    </thead>

    <tbody>

        @foreach($jadwalUjian as $hari => $items)
            @foreach($items as $index => $item)

            <tr>

                @if($index == 0)
                    <td rowspan="{{ count($items) }}" class="hari-column">
                        {{ $hari }}
                    </td>
                @endif

                <td>
                    {{ date('H:i', strtotime($item->jam_mulai)) }}
                    -
                    {{ date('H:i', strtotime($item->jam_selesai)) }}
                </td>

                <td class="text-left">
                    {{ $item->mapel->nama_mapel ?? '-' }}
                </td>

                <td class="text-left">
                    {{ $item->pengawas->user->nama ?? '-' }}
                </td>

                <td>
                    {{ strtoupper($item->ruangan ?? '-') }}
                </td>

            </tr>

            @endforeach
        @endforeach

    </tbody>
</table>

</body>
</html>