<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jadwal Pelajaran</title>
    <style>
        body { 
            font-family: Helvetica, sans-serif; 
            text-transform: uppercase; 
            font-size: 11pt; 
            color: #000; 
        }

        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #000; 
            padding-bottom: 10px; 
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }

        th { 
            background-color: #fff; 
            color: #000; 
            padding: 10px; 
            border: 1px solid #000; 
            font-weight: normal;
        }

        td { 
            padding: 8px; 
            border: 1px solid #000; 
            text-align: center; 
        }

        .text-left { text-align: left; }
        
        .col-hari { width: 15%; }
        .col-waktu { width: 20%; }
        .col-mapel { width: 35%; }
        .col-guru { width: 30%; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Jadwal Pelajaran</h2>
        <p>Kelas: {{ $stats['nama_kelas'] }} | TA: {{ $stats['tahun_ajaran'] }} | Semester: {{ $stats['semester'] }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-hari">Hari</th>
                <th class="col-waktu">Waktu</th>
                <th class="col-mapel">Mata Pelajaran</th>
                <th class="col-guru">Guru</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwalPelajaran as $hari => $items)
                @foreach($items as $index => $item)
                <tr>
                    @if($index == 0)
                        <td rowspan="{{ count($items) }}" class="col-hari">{{ $hari }}</td>
                    @endif
                    <td class="col-waktu">
                        {{ date('H:i', strtotime($item->jam_mulai)) }} - {{ date('H:i', strtotime($item->jam_selesai)) }}
                    </td>
                    <td class="col-mapel text-left"> 
                        @if($item->jenis == 'istirahat' || $item->jenis == 'non_kbm') 
                            {{ strtoupper($item->kegiatan_kustom) }}
                        @else
                            {{ strtoupper($item->mapel->nama_mapel ?? '-') }}
                        @endif
                    </td>
                    <td class="col-guru text-left"> 
                        @if($item->jenis == 'kbm')
                            {{ strtoupper($item->guru->user->nama ?? '-') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>