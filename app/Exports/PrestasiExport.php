<?php

namespace App\Exports;

use App\Models\Prestasi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PrestasiExport implements FromView, WithDrawings, ShouldAutoSize
{
    public function view(): View
    {
        $prestasi = Prestasi::with([
            'siswa.user',
            'siswa.kelas'
        ])->get();

        return view(
            'admin.prestasi.rekap-excel',
            compact('prestasi')
        );
    }

    public function drawings()
    {
        $logoMuh = new Drawing();
        $logoMuh->setName('Logo Muhammadiyah');
        $logoMuh->setPath(public_path('img/logo-muh.png'));
        $logoMuh->setHeight(90);
        $logoMuh->setCoordinates('A2');

        $logoSekolah = new Drawing();
        $logoSekolah->setName('Logo Sekolah');
        $logoSekolah->setPath(public_path('img/logo-smk2.png'));
        $logoSekolah->setHeight(90);
        $logoSekolah->setCoordinates('G2');

        return [$logoMuh, $logoSekolah];
    }
}