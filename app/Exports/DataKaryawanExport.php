<?php

namespace App\Exports;

use App\Models\Data\DataKaryawan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DataKaryawanExport implements FromView, WithTitle, WithStyles, ShouldAutoSize, WithEvents
{
    protected $dataKaryawans;
    protected $filter;

    public function __construct($dataKaryawans, $filter = null)
    {
        $this->dataKaryawans = $dataKaryawans;
        $this->filter = $filter;
    }

    public function view(): View
    {
        return view('data.data-karyawan.export-data-karyawan', [
            'dataKaryawans' => $this->dataKaryawans,
            'filter' => $this->filter
        ]);
    }

    public function title(): string
    {
        return 'Data Karyawan';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as headers
            1 => ['font' => ['bold' => true, 'size' => 12]],

            // Add borders to all cells
            'A1:BA' . (count($this->dataKaryawans) + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Set background color for header row
                $event->sheet->getStyle('A1:BA1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('4a6fdc');

                // Set text color for header row
                $event->sheet->getStyle('A1:BA1')->getFont()->getColor()
                    ->setRGB('FFFFFF');

                // Add filter buttons to headers
                $event->sheet->setAutoFilter('A1:BA1');

                // Freeze the first row
                $event->sheet->freezePane('A2');

                // Apply row color highlighting based on employee status
                $rowIndex = 2; // Start from row 2 (after header)

                foreach ($this->dataKaryawans as $karyawan) {
                    $rowColor = null;

                    // Color based on employee status
                    if ($karyawan->sts_kry == 'AKTIF') {
                        $rowColor = 'D4EDDA'; // Light green for active
                    } elseif ($karyawan->sts_kry == 'CALON') {
                        $rowColor = 'FFF3CD'; // Light yellow for candidate
                    } elseif ($karyawan->sts_kry == 'NON-AKTIF') {
                        $rowColor = 'F8D7DA'; // Light red for inactive
                    }

                    // Apply color to row if available
                    if ($rowColor) {
                        $event->sheet->getStyle('A' . $rowIndex . ':BA' . $rowIndex)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB($rowColor);
                    }

                    $rowIndex++;
                }

                // Add filter information section if available
                if ($this->filter) {
                    $filterRowStart = count($this->dataKaryawans) + 3;

                    // Set title for filter info
                    $event->sheet->setCellValue('A' . $filterRowStart, 'Informasi Filter yang Diterapkan:');
                    $event->sheet->mergeCells('A' . $filterRowStart . ':B' . $filterRowStart);
                    $event->sheet->getStyle('A' . $filterRowStart . ':B' . $filterRowStart)->getFont()->setBold(true);

                    $currentRow = $filterRowStart + 1;

                    // Add filter details
                    $filterItems = [
                        'nrk' => 'NRK',
                        'nama' => 'Nama',
                        'nik' => 'NIK',
                        'sex' => 'Jenis Kelamin',
                        'agama' => 'Agama',
                        'sts_nikah' => 'Status Nikah',
                        'perusahaan' => 'Perusahaan',
                        'departemen' => 'Departemen',
                        'jabatan' => 'Jabatan',
                        'wilker' => 'Wilayah Kerja',
                        'sts_kry' => 'Status Karyawan',
                        'tgl_masuk_from' => 'Tanggal Masuk (Dari)',
                        'tgl_masuk_to' => 'Tanggal Masuk (Sampai)',
                    ];

                    foreach ($filterItems as $key => $label) {
                        if (isset($this->filter[$key]) && $this->filter[$key]) {
                            $event->sheet->setCellValue('A' . $currentRow, $label);
                            $event->sheet->setCellValue('B' . $currentRow, $this->filter[$key]);
                            $currentRow++;
                        }
                    }

                    // Add export date
                    $event->sheet->setCellValue('A' . $currentRow, 'Tanggal Export');
                    $event->sheet->setCellValue('B' . $currentRow, now()->format('d/m/Y H:i:s'));

                    // Add borders to filter info section
                    $event->sheet->getStyle('A' . $filterRowStart . ':B' . $currentRow)->getBorders()
                        ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
            },
        ];
    }
}