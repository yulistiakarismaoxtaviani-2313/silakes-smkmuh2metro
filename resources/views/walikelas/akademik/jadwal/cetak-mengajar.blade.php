
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
        background-color: #fff;
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
        INFO GURU
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
        color: #000;
        text-align: center;
        font-weight: bold;
        font-size: 12pt;
        padding: 10px;
        border: 1px solid #000;
    }

    .sub-header th {
        background: #fff;
        color: #000;
        font-weight: bold;
        text-align: center;
        padding: 8px;
        border: 1px solid #000;
    }

    .main-table td {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
        font-size: 11pt;
    }

    .day-column {
        font-weight: bold;
        vertical-align: middle;
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
    <h1>JADWAL MENGAJAR GURU</h1>
    <p>
        Tahun Ajaran {{ $tahun_ajaran }}
        Semester {{ ucfirst(strtolower($semester)) }}
    </p>
</div>

<hr>

<div class="card-info">
    <table>
        <tr>
            <td class="label">Nama Guru</td>
            <td>: {{ ucwords(strtolower($guru->user->nama)) }}</td>
        </tr>
        <tr>
    <td class="label">Mata Pelajaran</td>
    <td>: {{ $guru->mapel->pluck('nama_mapel')->implode(', ') ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tahun Ajaran</td>
            <td>: {{ $tahun_ajaran }} ({{ $semester }})</td>
        </tr>
        <tr>
            <td class="label">Total Mengajar</td>
            <td>: {{ $total_jam }} Jam</td>
        </tr>
    </table>
</div>

<table class="main-table">
    <thead>
        <tr>
            <th colspan="4" class="table-header">
                JADWAL MENGAJAR
            </th>
        </tr>
        <tr class="sub-header">
            <th width="15%">Hari</th>
            <th width="25%">Waktu</th>
            <th width="20%">Kelas</th>
            <th width="40%">Mata Pelajaran</th>
        </tr>
    </thead>

    <tbody>
        @foreach($jadwal as $hari => $sessions)
            @foreach($sessions as $index => $s)
                <tr>

                    @if($index == 0)
                        <td rowspan="{{ $sessions->count() }}" class="day-column">
                            {{ strtoupper($hari) }}
                        </td>
                    @endif

                    <td>
                        {{ \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') }}
                        -
                        {{ \Carbon\Carbon::parse($s->jam_selesai)->format('H:i') }}
                    </td>

                    <td>
                        {{ $s->kelas->nama_kelas ?? '-' }}
                    </td>

                    <td>
                        {{ $s->mapel->nama_mapel ?? '-' }}
                    </td>

                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

</body>
</html>