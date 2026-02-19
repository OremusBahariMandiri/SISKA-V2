<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class DataKontrakExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $dataKontraks;
    protected $filter;

    public function __construct($dataKontraks, $filter = null)
    {
        $this->dataKontraks = $dataKontraks;
        $this->filter = $filter;
    }

    public function collection()
    {
        return $this->dataKontraks->map(function ($kontrak, $index) {
            // Safely access related models with null coalescing
            $karyawan = $kontrak->karyawan ?? null;
            $kontrakKerja = $kontrak->kontrakKerja ?? null;
            $perusahaan = $kontrak->perusahaan ?? null;
            $departemen = $kontrak->departemen ?? null;
            $wilayahKerja = $kontrak->wilayahKerja ?? null;

            return [
                'No' => $index + 1,
                'Nama Karyawan' => $karyawan ? $karyawan->nama : '-',
                'NRK' => $karyawan ? $karyawan->nrk : '-',
                'NIK' => $karyawan ? $karyawan->nik : '-',

                // Contract Details
                'No Kontrak' => $kontrak->no_srt_ktr ?? '-',
                'Tanggal Surat Kontrak' => $kontrak->tgl_srt_ktr ? Carbon::parse($kontrak->tgl_srt_ktr)->format('d/m/Y') : '-',
                'Jenis Kontrak' => $kontrakKerja ? $kontrakKerja->nama_ktr : '-',
                'Kategori Kontrak' => $kontrak->ktg_ktk ?? '-',
                'Tanggal Mulai' => $kontrak->tgl_awl_ktr ? Carbon::parse($kontrak->tgl_awl_ktr)->format('d/m/Y') : '-',
                'Tanggal Berakhir' => $kontrak->tgl_akhir_ktr ? Carbon::parse($kontrak->tgl_akhir_ktr)->format('d/m/Y') : '-',
                'Durasi Kontrak (Bulan)' => $kontrak->durasi_ktr ?? '-',
                'Tanggal Pengingat' => $kontrak->tgl_pgt_ktr ? Carbon::parse($kontrak->tgl_pgt_ktr)->format('d/m/Y') : '-',
                'Status Kontrak' => $kontrak->sts_srt_ktr ?? '-',

                // Company Details
                'Perusahaan' => $perusahaan ? $perusahaan->nama_prs1 . ' - ' . $perusahaan->nama_prs2 : '-',

                // Career Details
                'Departemen' => $departemen ? $departemen->nama_dep : '-',
                'Wilayah Kerja' => $wilayahKerja ? $wilayahKerja->wilayah_krj : '-',
                'Tugas' => $kontrak->tugas ?? '-',

                // Education Details
                'Jenjang Pendidikan' => $kontrak->jenjang_skl ?? '-',
                'Institusi' => $kontrak->institusi_skl ?? '-',
                'Jurusan' => $kontrak->jurusan_skl ?? '-',
                'Tanggal Lulus' => $kontrak->tgl_lulus_skl ? Carbon::parse($kontrak->tgl_lulus_skl)->format('d/m/Y') : '-',

                // Additional Info
                'Keterangan Non-Aktif' => $kontrak->ket_sr_na ?? '-',
                'Tanggal Non-Aktif' => $kontrak->tgl_sr_na ? Carbon::parse($kontrak->tgl_sr_na)->format('d/m/Y') : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No', 'Nama Karyawan', 'NRK', 'NIK',
            'No Kontrak', 'Tanggal Surat Kontrak', 'Jenis Kontrak', 'Kategori Kontrak',
            'Tanggal Mulai', 'Tanggal Berakhir', 'Durasi Kontrak (Bulan)', 'Tanggal Pengingat',
            'Status Kontrak', 'Perusahaan', 'Departemen', 'Wilayah Kerja', 'Tugas',
            'Jenjang Pendidikan', 'Institusi', 'Jurusan', 'Tanggal Lulus',
            'Keterangan Non-Aktif', 'Tanggal Non-Aktif'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the first row (headers)
        $sheet->getStyle('1')->getFont()->setBold(true);

        // Add row coloring based on contract status and expiry
        foreach ($this->dataKontraks as $index => $kontrak) {
            $rowIndex = $index + 2; // +2 because Excel rows are 1-indexed and we have a header row

            // Determine row color based on contract priority
            $fillColor = null;
            switch ($kontrak->contract_priority) {
                case 1: // Expired/Overdue
                    $fillColor = 'F8D7DA'; // Light Red
                    break;
                case 2: // Urgent (within 7 days or end date within 30 days)
                    $fillColor = 'FFF3CD'; // Light Yellow
                    break;
                case 3: // Warning (within 30 days)
                    $fillColor = 'D1ECF1'; // Light Blue
                    break;
                case 5: // Inactive contracts
                    $fillColor = 'E2E3E5'; // Light Gray
                    break;
                default: // Normal contracts
                    $fillColor = 'D4EDDA'; // Light Green
            }

            if ($fillColor) {
                $sheet->getStyle('A' . $rowIndex . ':W' . $rowIndex)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($fillColor);
            }
        }

        return [];
    }
}