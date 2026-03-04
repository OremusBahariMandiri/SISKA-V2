<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Carbon\Carbon;

class DataKaryawanExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected $dataKaryawans;
    protected $filter;

    public function __construct($dataKaryawans, $filter = null)
    {
        $this->dataKaryawans = $dataKaryawans;
        $this->filter = $filter;
    }

    public function collection()
    {
        return $this->dataKaryawans->map(function ($karyawan, $index) {
            // -------------------------------------------------------
            // CATATAN STRUKTUR DATABASE (akibat swap di controller):
            //   kolom `departemen`  => menyimpan ID dari tabel departemen
            //                         (foreign key -> departemenRelation)
            //   kolom `jabatan`     => menyimpan NAMA DEPARTEMEN (string)
            //                         bukan nama jabatan
            //
            //   Sehingga:
            //   - Nama Departemen  = $karyawan->jabatan  (string langsung)
            //   - Nama Jabatan     = $karyawan->departemenRelation->nama_jbt
            //
            //   Untuk Wilayah Kerja:
            //   kolom `wilker`     => menyimpan ID unit kerja (WilayahKerja)
            //                         -> wilayahKerjaRelation (BelongsTo WilayahKerja via 'wilker')
            //   kolom `unit_krj`   => menyimpan ID unit kerja
            //                         -> unitKerjaRelation   (BelongsTo WilayahKerja via 'unit_krj')
            //
            //   Wilayah Kerja (nama wilayah) ada di kolom `wilayah_krj`
            //   Unit Kerja   (nama area)     ada di kolom `area_krj`
            // -------------------------------------------------------

            // Ambil nama departemen: dari field `jabatan` (string), fallback ke relasi
            $namaDepartemen = $karyawan->jabatan ?? $karyawan->departemenRelation->nama_dep ?? '-';

            // Ambil nama jabatan: dari relasi departemenRelation (menggunakan ID di kolom `departemen`)
            $namaJabatan = $karyawan->departemenRelation->nama_jbt
                ?? $karyawan->departemenRelation->nama_dep
                ?? '-';

            // Ambil wilayah kerja: prioritas dari wilayahKerjaRelation (kolom `wilker`)
            // fallback ke unitKerjaRelation.wilayah_krj
            $namaWilayahKerja = $karyawan->wilayahKerjaRelation->wilayah_krj
                ?? $karyawan->unitKerjaRelation->wilayah_krj
                ?? '-';

            // Ambil unit kerja: dari unitKerjaRelation (kolom `unit_krj`)
            $namaUnitKerja = $karyawan->unitKerjaRelation->area_krj ?? '-';

            return [
                'No' => $index + 1,
                'Tanggal Masuk' => $karyawan->tgl_masuk ? Carbon::parse($karyawan->tgl_masuk)->format('d/m/Y') : '-',
                'Masa Kerja' => $karyawan->tgl_masuk
                    ? (function () use ($karyawan) {
                        $start = Carbon::parse($karyawan->tgl_masuk);
                        $now = Carbon::now();
                        $years = $start->diffInYears($now);
                        $months = $start->copy()->addYears($years)->diffInMonths($now);
                        if ($years > 0 && $months > 0) {
                            return $years . ' Tahun ' . $months . ' Bulan';
                        } elseif ($years > 0) {
                            return $years . ' Tahun';
                        } elseif ($months > 0) {
                            return $months . ' Bulan';
                        } else {
                            return $start->diffInDays($now) . ' Hari';
                        }
                    })()
                    : '-',
                'NRK' => $karyawan->nrk ?? '-',
                'NIK' => $karyawan->nik,
                'Nama' => $karyawan->nama,
                'Tempat Lahir' => $karyawan->tpt_lahir,
                'Tanggal Lahir' => $karyawan->tgl_lahir ? Carbon::parse($karyawan->tgl_lahir)->format('d/m/Y') : '-',
                'Umur' => $karyawan->tgl_lahir
                    ? Carbon::parse($karyawan->tgl_lahir)->age . ' tahun'
                    : '-',
                'Jenis Kelamin' => $karyawan->sex === 'LAKI-LAKI' ? 'L' : 'P',
                'Agama' => $karyawan->agama,
                'Kewarganegaraan' => $karyawan->kewarganegaraan ?? 'INDONESIA',
                'Status Nikah' => $karyawan->sts_nikah,
                'Status Keluarga' => $karyawan->sts_keluarga ?? '-',
                'Jumlah Anak' => $karyawan->jml_anak ?? 0,
                'Telepon 1' => $karyawan->tlp1,
                'Telepon 2' => $karyawan->tlp2 ?? '-',
                'Email 1' => $karyawan->email1 ?? '-',
                'Email 2' => $karyawan->email2 ?? '-',
                'Instagram' => $karyawan->instagram ?? '-',
                'Facebook' => $karyawan->facebook ?? '-',

                // KTP Address
                'Provinsi KTP' => $karyawan->prov_ktp,
                'Kota KTP' => $karyawan->kota_ktp,
                'Kecamatan KTP' => $karyawan->kec_ktp,
                'Kelurahan KTP' => $karyawan->kel_ktp,
                'RT/RW KTP' => $karyawan->rt_rw_ktp,
                'Kode Pos KTP' => $karyawan->kd_pos_ktp,
                'Alamat KTP' => $karyawan->alamat_ktp,

                // Domisili Address
                'Provinsi DOM' => $karyawan->prov_dom,
                'Kota DOM' => $karyawan->kota_dom,
                'Kecamatan DOM' => $karyawan->kec_dom,
                'Kelurahan DOM' => $karyawan->kel_dom,
                'RT/RW DOM' => $karyawan->rt_rw_dom,
                'Kode Pos DOM' => $karyawan->kd_pos_dom,
                'Alamat DOM' => $karyawan->alamat_dom,

                // Education
                'Jenjang' => $karyawan->jenjang_skl ?? '-',
                'Institusi' => $karyawan->institusi_skl ?? '-',
                'Kota' => $karyawan->kota_skl ?? '-',
                'Fakultas' => $karyawan->fakultas_skl ?? '-',
                'Jurusan' => $karyawan->jurusan_skl ?? '-',
                'Gelar' => $karyawan->gelar_skl ?? '-',
                'Tanggal Lulus' => $karyawan->tgl_lulus_skl ? Carbon::parse($karyawan->tgl_lulus_skl)->format('d/m/Y') : '-',

                // Work Contract
                'Perusahaan' => $karyawan->perusahaanRelation->nama_prs1 ?? '-',
                'Singkatan Perusahaan' => $karyawan->perusahaanRelation->nama_prs2 ?? '-',
                'Status Kontrak' => $karyawan->kontrakRelation->nama_ktr ?? '-',
                'Singkatan Kontrak' => $karyawan->kontrakRelation->singkatan_ktr ?? '-',
                'Tanggal Awal Kontrak' => $karyawan->tgl_awal_ktr ? Carbon::parse($karyawan->tgl_awal_ktr)->format('d/m/Y') : '-',
                'Tanggal Akhir Kontrak' => $karyawan->tgl_akhir_ktr ? Carbon::parse($karyawan->tgl_akhir_ktr)->format('d/m/Y') : '-',
                'Durasi Kontrak' => $karyawan->durasi_ktr,

                // Career
                // `jabatan` kolom di DB menyimpan string nama departemen (akibat swap di controller)
                // `departemenRelation` menggunakan kolom `departemen` (ID) -> menghasilkan nama jabatan
                'Departemen' => $namaDepartemen,
                'Singkatan Departemen' => $karyawan->departemenRelation->singkatan_dep ?? '-',
                'Jabatan' => $namaJabatan,
                'Singkatan Jabatan' => $karyawan->departemenRelation->singkatan_jbt ?? '-',
                'Wilayah Kerja' => $namaWilayahKerja,
                'Singkatan Wilayah Kerja' => $karyawan->wilayahKerjaRelation->skt_wilker
                    ?? $karyawan->unitKerjaRelation->skt_wilker
                    ?? '-',
                'Area Kerja' => $namaUnitKerja,
                'Singkatan Area Kerja' => $karyawan->unitKerjaRelation->singkatan_wk ?? '-',
                'Tugas' => $karyawan->tugas,

                // Employment Status
                'Status Karyawan' => $karyawan->sts_kry,
                'Tanggal PHK' => $karyawan->tgl_phk ? Carbon::parse($karyawan->tgl_phk)->format('d/m/Y') : '-',
                'Keterangan PHK' => $karyawan->ket_phk,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Masuk',
            'Masa Kerja',
            'NRK',
            'NIK',
            'Nama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Umur',
            'Jenis Kelamin',
            'Agama',
            'Kewarganegaraan',
            'Status Nikah',
            'Status Keluarga',
            'Jumlah Anak',
            'Telepon 1',
            'Telepon 2',
            'Email 1',
            'Email 2',
            'Instagram',
            'Facebook',
            'Provinsi KTP',
            'Kota KTP',
            'Kecamatan KTP',
            'Kelurahan KTP',
            'RT/RW KTP',
            'Kode Pos KTP',
            'Alamat KTP',
            'Provinsi DOM',
            'Kota DOM',
            'Kecamatan DOM',
            'Kelurahan DOM',
            'RT/RW DOM',
            'Kode Pos DOM',
            'Alamat DOM',
            'Jenjang',
            'Institusi',
            'Kota',
            'Fakultas',
            'Jurusan',
            'Gelar',
            'Tanggal Lulus',
            'Perusahaan',
            'Singkatan Perusahaan',
            'Status Kontrak',
            'Singkatan Kontrak',
            'Tanggal Awal Kontrak',
            'Tanggal Akhir Kontrak',
            'Durasi Kontrak',
            'Departemen',
            'Singkatan Departemen',
            'Jabatan',
            'Singkatan Jabatan',
            'Wilayah Kerja',
            'Singkatan Wilayah Kerja',
            'Unit Kerja',
            'Singkatan Unit Kerja',
            'Tugas',
            'Status Karyawan',
            'Tanggal PHK',
            'Keterangan PHK',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Set format TEXT untuk kolom D (NRK), E (NIK), P (Telepon 1), Q (Telepon 2)
                // Perhatikan: Kolom bergeser karena ada tambahan kolom Umur
                $sheet->getStyle('D2:D' . $highestRow)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->getStyle('E2:E' . $highestRow)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->getStyle('P2:P' . $highestRow)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->getStyle('Q2:Q' . $highestRow)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

                // Set explicit string type untuk setiap cell
                for ($row = 2; $row <= $highestRow; $row++) {
                    // NRK (D - bergeser dari C karena ada Masa Kerja)
                    $nrkValue = $sheet->getCell('D' . $row)->getValue();
                    if ($nrkValue && $nrkValue !== '-') {
                        $sheet->setCellValueExplicit('D' . $row, $nrkValue, DataType::TYPE_STRING);
                    }

                    // NIK (E - bergeser dari D)
                    $nikValue = $sheet->getCell('E' . $row)->getValue();
                    if ($nikValue && $nikValue !== '-') {
                        $sheet->setCellValueExplicit('E' . $row, $nikValue, DataType::TYPE_STRING);
                    }

                    // Telepon 1 (P - bergeser dari N karena ada Umur)
                    $tlp1Value = $sheet->getCell('P' . $row)->getValue();
                    if ($tlp1Value && $tlp1Value !== '-') {
                        $sheet->setCellValueExplicit('P' . $row, $tlp1Value, DataType::TYPE_STRING);
                    }

                    // Telepon 2 (Q - bergeser dari O)
                    $tlp2Value = $sheet->getCell('Q' . $row)->getValue();
                    if ($tlp2Value && $tlp2Value !== '-') {
                        $sheet->setCellValueExplicit('Q' . $row, $tlp2Value, DataType::TYPE_STRING);
                    }
                }
            },
        ];
    }
}
