<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
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

    /**
     * Hitung priority & status baris berdasarkan logika yang sama dengan index (JS).
     *
     * Priority:
     *   1 = expired / terlambat
     *   2 = urgent  (≤ 7 hari)
     *   3 = warning (≤ 30 hari)
     *   4 = safe / tetap-aktif / no-reminder-tetap
     *   5 = no_reminder (bukan TETAP)
     *  10 = NON-AKTIF  → selalu paling bawah
     *
     * Status:
     *   expired | urgent | warning | safe | tetap_aktif | no_reminder | non_active
     */
    protected function getRowMeta($kontrak): array
    {
        $contractStatus = $kontrak->sts_srt_ktr ?? '';
        $tglPeringatan  = $kontrak->tgl_pgt_ktr  ?? '';
        $tglAkhir       = $kontrak->tgl_akhir_ktr ?? '';
        $ktgKtk         = $kontrak->ktg_ktk       ?? '';

        // --- NON-AKTIF → selalu paling bawah ---
        if ($contractStatus === 'NON-AKTIF') {
            return ['priority' => 10, 'status' => 'non_active'];
        }

        // --- AKTIF + tidak ada pengingat + tidak ada tgl akhir = TETAP ---
        if (
            $contractStatus === 'AKTIF'
            && empty($tglPeringatan)
            && empty($tglAkhir)
        ) {
            return ['priority' => 4, 'status' => 'tetap_aktif'];
        }

        // --- Tidak ada tanggal pengingat ---
        if (empty($tglPeringatan)) {
            // Jika kategori TETAP, perlakukan seperti aman (no highlight)
            if ($ktgKtk === 'TETAP') {
                return ['priority' => 4, 'status' => 'tetap_aktif'];
            }
            return ['priority' => 5, 'status' => 'no_reminder'];
        }

        // --- Hitung selisih hari dari hari ini ke tgl_pgt_ktr ---
        $today        = Carbon::now()->startOfDay();
        $reminderDate = Carbon::parse($tglPeringatan)->startOfDay();
        $diffDays     = $reminderDate->diffInDays($today, false); // positif = reminder sudah lewat

        // diffInDays dengan false: positif jika $reminderDate < $today (terlambat)
        $daysLeft = $today->diffInDays($reminderDate, false); // positif = masih ada sisa hari

        if ($daysLeft < 0) {
            // Terlambat (reminder sudah lewat)
            return ['priority' => 1, 'status' => 'expired'];
        } elseif ($daysLeft === 0) {
            // Hari ini
            return ['priority' => 1, 'status' => 'expired'];
        } elseif ($daysLeft <= 7) {
            return ['priority' => 2, 'status' => 'urgent'];
        } elseif ($daysLeft <= 30) {
            return ['priority' => 3, 'status' => 'warning'];
        } else {
            return ['priority' => 4, 'status' => 'safe'];
        }
    }

    public function collection()
    {
        // Urutkan: NON-AKTIF paling bawah, lainnya berdasarkan priority naik
        $sorted = $this->dataKontraks->sortBy(function ($kontrak) {
            return $this->getRowMeta($kontrak)['priority'];
        })->values();

        return $sorted->map(function ($kontrak, $index) {
            $karyawan    = $kontrak->karyawan    ?? null;
            $kontrakKerja = $kontrak->kontrakKerja ?? null;
            $perusahaan  = $kontrak->perusahaan  ?? null;
            $departemen  = $kontrak->departemen  ?? null;
            $wilayahKerja = $kontrak->wilayahKerja ?? null;

            return [
                'No'                      => $index + 1,
                'Nama Karyawan'           => $karyawan ? $karyawan->nama : '-',
                'NRK'                     => $karyawan ? $karyawan->nrk  : '-',
                'NIK'                     => $karyawan ? $karyawan->nik  : '-',
                'No Kontrak'              => $kontrak->no_srt_ktr ?? '-',
                'Tanggal Surat Kontrak'   => $kontrak->tgl_srt_ktr
                    ? Carbon::parse($kontrak->tgl_srt_ktr)->format('d/m/Y') : '-',
                'Jenis Kontrak'           => $kontrakKerja ? $kontrakKerja->nama_ktr : '-',
                'Kategori Kontrak'        => $kontrak->ktg_ktk ?? '-',
                'Tanggal Mulai'           => $kontrak->tgl_awl_ktr
                    ? Carbon::parse($kontrak->tgl_awl_ktr)->format('d/m/Y') : '-',
                'Tanggal Berakhir'        => $kontrak->tgl_akhir_ktr
                    ? Carbon::parse($kontrak->tgl_akhir_ktr)->format('d/m/Y') : '-',
                'Durasi Kontrak (Bulan)'  => $kontrak->durasi_ktr ?? '-',
                'Tanggal Pengingat'       => $kontrak->tgl_pgt_ktr
                    ? Carbon::parse($kontrak->tgl_pgt_ktr)->format('d/m/Y') : '-',
                'Status Kontrak'          => $kontrak->sts_srt_ktr ?? '-',
                'Perusahaan'              => $perusahaan
                    ? $perusahaan->nama_prs1 . ' - ' . $perusahaan->nama_prs2 : '-',
                'Departemen'              => $departemen  ? $departemen->nama_dep       : '-',
                'Wilayah Kerja'           => $wilayahKerja ? $wilayahKerja->wilayah_krj : '-',
                'Tugas'                   => $kontrak->tugas ?? '-',
                'Jenjang Pendidikan'      => $kontrak->jenjang_skl ?? '-',
                'Institusi'               => $kontrak->institusi_skl ?? '-',
                'Jurusan'                 => $kontrak->jurusan_skl ?? '-',
                'Tanggal Lulus'           => $kontrak->tgl_lulus_skl
                    ? Carbon::parse($kontrak->tgl_lulus_skl)->format('d/m/Y') : '-',
                'Keterangan Non-Aktif'    => $kontrak->ket_sr_na ?? '-',
                'Tanggal Non-Aktif'       => $kontrak->tgl_sr_na
                    ? Carbon::parse($kontrak->tgl_sr_na)->format('d/m/Y') : '-',
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
            'Keterangan Non-Aktif', 'Tanggal Non-Aktif',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = 'W'; // kolom terakhir (23 kolom)

        // ── Header row styling ──────────────────────────────────────────
        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
        ]);

        // ── Warna legend (kolom tambahan setelah data, opsional) ─────────
        // (tidak diperlukan, tapi bisa ditambah jika mau)

        // ── Urutkan kontrak persis seperti di collection() ───────────────
        $sorted = $this->dataKontraks->sortBy(function ($kontrak) {
            return $this->getRowMeta($kontrak)['priority'];
        })->values();

        foreach ($sorted as $index => $kontrak) {
            $rowIndex = $index + 2; // +2: baris pertama = header
            $meta     = $this->getRowMeta($kontrak);

            /*
             * Mapping status → warna background (sama persis dengan CSS di index):
             *   expired    → merah    #FC0000  (highlight-red)
             *   urgent     → kuning   #FFFF00  (highlight-yellow)
             *   warning    → hijau    #00E013  (highlight-orange — sesuai CSS index)
             *   safe       → tidak ada warna (putih)
             *   tetap_aktif→ tidak ada warna (putih)
             *   no_reminder→ abu-abu  #CCCCCC  (highlight-gray, kecuali TETAP)
             *   non_active → abu-abu  #CCCCCC  (highlight-gray)
             */
            $fillColor = null;

            switch ($meta['status']) {
                case 'expired':
                    $fillColor = 'FC0000'; // merah
                    break;
                case 'urgent':
                    $fillColor = 'FFFF00'; // kuning
                    break;
                case 'warning':
                    $fillColor = '00E013'; // hijau (sesuai highlight-orange di CSS)
                    break;
                case 'safe':
                case 'tetap_aktif':
                    $fillColor = null;     // putih / tidak diwarnai
                    break;
                case 'no_reminder':
                    // Abu-abu hanya jika bukan TETAP (sudah ditangani di getRowMeta,
                    // tapi double-check di sini untuk keamanan)
                    $ktgKtk    = $kontrak->ktg_ktk ?? '';
                    $fillColor = ($ktgKtk === 'TETAP') ? null : 'CCCCCC';
                    break;
                case 'non_active':
                    $fillColor = 'CCCCCC'; // abu-abu
                    break;
            }

            if ($fillColor !== null) {
                $sheet->getStyle('A' . $rowIndex . ':' . $lastCol . $rowIndex)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($fillColor);
            }

            // Border tipis setiap baris data
            $sheet->getStyle('A' . $rowIndex . ':' . $lastCol . $rowIndex)
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
        }

        // ── Freeze pane & tinggi header ──────────────────────────────────
        $sheet->freezePane('A2');
        $sheet->getRowDimension(1)->setRowHeight(20);

        // ── Legend section di bawah data ────────────────────────────────
        $totalRows  = $sorted->count() + 2; // +1 header +1 spasi
        $legendData = [
            ['FC0000', 'Expired / Terlambat (tanggal pengingat sudah lewat)'],
            ['FFFF00', 'Urgent (sisa ≤ 7 hari)'],
            ['00E013', 'Peringatan (sisa 8–30 hari)'],
            ['FFFFFF', 'Aman (sisa > 30 hari)'],
            ['CCCCCC', 'NON-AKTIF / Tidak ada pengingat (bukan TETAP)'],
        ];

        $sheet->setCellValue('A' . $totalRows, 'Keterangan Warna:');
        $sheet->getStyle('A' . $totalRows)->getFont()->setBold(true);

        foreach ($legendData as $i => [$color, $label]) {
            $r = $totalRows + 1 + $i;
            $sheet->setCellValue('A' . $r, '');
            $sheet->setCellValue('B' . $r, $label);
            $sheet->getStyle('A' . $r)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB($color);
            if ($color === 'FFFFFF') {
                $sheet->getStyle('A' . $r)->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
            }
        }

        return [];
    }
}