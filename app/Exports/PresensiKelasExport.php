<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PresensiKelasExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $kelas;
    protected $siswa;
    protected $total_stats;

    public function __construct($kelas, $siswa, $total_stats)
    {
        $this->kelas = $kelas;
        $this->siswa = $siswa;
        $this->total_stats = $total_stats;
    }

    public function view(): View
    {
        return view(
            'admin.rekap.excel',
            [
                'kelas' => $this->kelas,
                'siswa' => $this->siswa,
                'total_stats' => $this->total_stats,
            ]
        );
    }
    public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {

            $sheet = $event->sheet;

            $sheet->mergeCells('A1:H1');
            $sheet->mergeCells('A2:H2');
            $sheet->mergeCells('A3:H3');

            $sheet->getStyle('A1:H3')->getFont()->setBold(true);

            $sheet->getStyle('A1:H3')->getAlignment()
                ->setHorizontal(
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                );

            $sheet->getStyle('A12:H12')->getFont()->setBold(true);

            $sheet->getStyle('A12:H12')->getFill()
                ->setFillType(
                    \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
                )
                ->getStartColor()
                ->setARGB('D9D9D9');
        },
    ];
}
}