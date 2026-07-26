<table>

    <tr>
        <td colspan="8" align="center">
            <strong>REKAP PRESENSI KELAS SISWA</strong>
        </td>
    </tr>

    <tr>
        <td colspan="8" align="center">
            <strong>SMK MUHAMMADIYAH 2 METRO</strong>
        </td>
    </tr>

    <tr>
        <td colspan="8" align="center">
            TAHUN PELAJARAN :
            {{ strtoupper($kelas->tahunAjaran->tahun_ajaran ?? '-') }}
            | SEMESTER :
            {{ strtoupper($kelas->semester->nama_semester ?? '-') }}
        </td>
    </tr>

    <tr></tr>

    <tr>
        <td><strong>Nama Kelas</strong></td>
        <td>:</td>
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

    <tr></tr>

    {{-- Ringkasan --}}
    <tr>
        <th style="border:1px solid black;">Total Hadir</th>
        <th style="border:1px solid black;">Total Sakit</th>
        <th style="border:1px solid black;">Total Izin</th>
        <th style="border:1px solid black;">Total Alfa</th>
    </tr>

    <tr>
        <td style="border:1px solid black;">{{ $total_stats['hadir'] }}</td>
        <td style="border:1px solid black;">{{ $total_stats['sakit'] }}</td>
        <td style="border:1px solid black;">{{ $total_stats['izin'] }}</td>
        <td style="border:1px solid black;">{{ $total_stats['alfa'] }}</td>
    </tr>

    <tr></tr>
    <tr></tr>

    {{-- Detail --}}
    <tr>
        <th style="border:1px solid black;">No</th>
        <th style="border:1px solid black;">NIS</th>
        <th style="border:1px solid black;">Nama Siswa</th>
        <th style="border:1px solid black;">JK</th>
        <th style="border:1px solid black;">Alfa</th>
        <th style="border:1px solid black;">Sakit</th>
        <th style="border:1px solid black;">Izin</th>
        <th style="border:1px solid black;">Total</th>
    </tr>

    @foreach($siswa as $index => $item)
    <tr>
        <td style="border:1px solid black;">{{ $index + 1 }}</td>
        <td style="border:1px solid black;">{{ $item->nis }}</td>
        <td style="border:1px solid black;">{{ $item->user?->nama ?? '-' }}</td>
        <td style="border:1px solid black;">
            {{ $item->jenis_kelamin == 'L' ? 'L' : 'P' }}
        </td>
        <td style="border:1px solid black;">{{ $item->alfa_count }}</td>
        <td style="border:1px solid black;">{{ $item->sakit_count }}</td>
        <td style="border:1px solid black;">{{ $item->izin_count }}</td>
        <td style="border:1px solid black;">
            {{ $item->alfa_count + $item->sakit_count + $item->izin_count }}
        </td>
    </tr>
    @endforeach

</table>