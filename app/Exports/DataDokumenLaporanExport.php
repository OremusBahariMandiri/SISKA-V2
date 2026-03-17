<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DataDokumenLaporanExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, ShouldAutoSize
{
    protected $dataDokumens;
    protected $filters;

    public function __construct($dataDokumens, $filters = [])
    {
        $this->dataDokumens = $dataDokumens;
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->dataDokumens;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NO',
            'NRK',
            'NIK',
            'NAMA KARYAWAN',
            'JENIS KELAMIN',
            'JENIS DOKUMEN',
            'KODE DOKUMEN',
            'NO DOKUMEN',
            'TANGGAL TERBIT',
            'TANGGAL AKHIR',
            'TANGGAL PERINGATAN',
            'STATUS DOKUMEN',
            'DEPARTEMEN',
            'JABATAN',
            'WILAYAH KERJA',
            'UNIT KERJA',
            'PERUSAHAAN',
            'KETERANGAN',
        ];
    }

    /**
     * @var mixed $dokumen
     */
    public function map($dokumen): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        $karyawan = $dokumen->karyawan;
        $departemen = $karyawan ? $karyawan->departemenRelation : null;
        $wilayah = $karyawan ? $karyawan->wilayahKerjaRelation : null;
        $perusahaan = $karyawan ? $karyawan->perusahaanRelation : null;
        $dokumenType = $dokumen->dokumenType;

        return [
            $rowNumber,
            $karyawan->nrk ?? '-',
            $karyawan->nik ?? '-',
            $karyawan->nama ?? '-',
            $karyawan->sex ?? '-',
            $dokumenType->jns_dok_kry ?? '-',
            $dokumenType->kode_dok_kry ?? '-',
            $dokumen->no_dok ?? '-',
            $dokumen->tgl_awal_dok ? $dokumen->tgl_awal_dok->format('d-m-Y') : '-',
            $dokumen->tgl_akr_dok ? $dokumen->tgl_akr_dok->format('d-m-Y') : '-',
            $dokumen->tgl_pgt_dok ? $dokumen->tgl_pgt_dok->format('d-m-Y') : '-',
            $dokumen->sts_dok ?? '-',
            $departemen->nama_dep ?? '-',
            $departemen->nama_jbt ?? '-',
            $wilayah->wilayah_krj ?? '-',
            $wilayah->area_krj ?? '-',
            $perusahaan->nama_prs1 ?? '-',
            $dokumen->ket_dok ?? '-',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Pelaporan Dokumen';
    }

    /**
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:R1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0d6efd'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Data rows styling
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A2:R' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Center align for specific columns
        $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E2:E' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I2:K' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L2:L' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set row height
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Freeze first row
        $sheet->freezePane('A2');

        return [];
    }
}