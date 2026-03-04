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

class CustomDataKaryawanExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected $dataKaryawans;
    protected $selectedFields;
    protected $filter;

    // Field mapping untuk header dan data
    protected $fieldMap = [
        'no' => 'No',
        'tgl_masuk' => 'Tanggal Masuk',
        'masa_kerja' => 'Masa Kerja',
        'nrk' => 'NRK',
        'nik' => 'NIK',
        'nama' => 'Nama',
        'tpt_lahir' => 'Tempat Lahir',
        'tgl_lahir' => 'Tanggal Lahir',
        'umur' => 'Umur',
        'sex' => 'Jenis Kelamin',
        'agama' => 'Agama',
        'kewarganegaraan' => 'Kewarganegaraan',
        'sts_nikah' => 'Status Nikah',
        'sts_keluarga' => 'Status Keluarga',
        'jml_anak' => 'Jumlah Anak',
        'tlp1' => 'Telepon 1',
        'tlp2' => 'Telepon 2',
        'email1' => 'Email 1',
        'email2' => 'Email 2',
        'instagram' => 'Instagram',
        'facebook' => 'Facebook',
        'prov_ktp' => 'Provinsi KTP',
        'kota_ktp' => 'Kota KTP',
        'kec_ktp' => 'Kecamatan KTP',
        'kel_ktp' => 'Kelurahan KTP',
        'rt_rw_ktp' => 'RT/RW KTP',
        'kd_pos_ktp' => 'Kode Pos KTP',
        'alamat_ktp' => 'Alamat KTP',
        'prov_dom' => 'Provinsi DOM',
        'kota_dom' => 'Kota DOM',
        'kec_dom' => 'Kecamatan DOM',
        'kel_dom' => 'Kelurahan DOM',
        'rt_rw_dom' => 'RT/RW DOM',
        'kd_pos_dom' => 'Kode Pos DOM',
        'alamat_dom' => 'Alamat DOM',
        'jenjang_skl' => 'Jenjang',
        'institusi_skl' => 'Institusi',
        'kota_skl' => 'Kota',
        'fakultas_skl' => 'Fakultas',
        'jurusan_skl' => 'Jurusan',
        'gelar_skl' => 'Gelar',
        'tgl_lulus_skl' => 'Tanggal Lulus',
        'perusahaan' => 'Perusahaan',
        'singkatan_perusahaan' => 'Singkatan Perusahaan',
        'sts_ktr' => 'Status Kontrak',
        'singkatan_kontrak' => 'Singkatan Kontrak',
        'tgl_awal_ktr' => 'Tanggal Awal Kontrak',
        'tgl_akhir_ktr' => 'Tanggal Akhir Kontrak',
        'durasi_ktr' => 'Durasi Kontrak',
        'departemen' => 'Departemen',
        'singkatan_departemen' => 'Singkatan Departemen',
        'jabatan' => 'Jabatan',
        'singkatan_jabatan' => 'Singkatan Jabatan',
        'wilker' => 'Wilayah Kerja',
        'singkatan_wilker' => 'Singkatan Wilayah Kerja',
        'unit_krj' => 'Area Kerja',
        'singkatan_unit_kerja' => 'Singkatan Area Kerja',
        'tugas' => 'Tugas',
        'sts_kry' => 'Status Karyawan',
        'tgl_phk' => 'Tanggal PHK',
        'ket_phk' => 'Keterangan PHK',
    ];

    public function __construct($dataKaryawans, $selectedFields, $filter = null)
    {
        $this->dataKaryawans = $dataKaryawans;
        $this->selectedFields = $selectedFields;
        $this->filter = $filter;
    }

    public function collection()
    {
        return $this->dataKaryawans->map(function ($karyawan, $index) {
            $row = [];

            foreach ($this->selectedFields as $field) {
                $row[] = $this->getFieldValue($karyawan, $field, $index);
            }

            return $row;
        });
    }

    protected function getFieldValue($karyawan, $field, $index)
    {
        switch ($field) {
            case 'no':
                return $index + 1;

            case 'tgl_masuk':
                return $karyawan->tgl_masuk ? Carbon::parse($karyawan->tgl_masuk)->format('d/m/Y') : '-';

            case 'masa_kerja':
                if (!$karyawan->tgl_masuk) return '-';
                $joinDate = Carbon::parse($karyawan->tgl_masuk);
                $now = Carbon::now();
                $diffInDays = $joinDate->diffInDays($now);
                $years = floor($diffInDays / 365);
                $months = floor(($diffInDays % 365) / 30);
                $days = $diffInDays - ($years * 365) - ($months * 30);

                $result = '';
                if ($years > 0) $result .= $years . ' tahun ';
                if ($months > 0) $result .= $months . ' bulan ';
                if ($days > 0) $result .= $days . ' hari';

                return trim($result) ?: '-';

            case 'nrk':
                return $karyawan->nrk ?? '-';

            case 'nik':
                return $karyawan->nik;

            case 'nama':
                return $karyawan->nama;

            case 'tpt_lahir':
                return $karyawan->tpt_lahir;

            case 'tgl_lahir':
                return $karyawan->tgl_lahir ? Carbon::parse($karyawan->tgl_lahir)->format('d/m/Y') : '-';

            case 'umur':
                if (!$karyawan->tgl_lahir) return '-';
                $birthDate = Carbon::parse($karyawan->tgl_lahir);
                $age = $birthDate->age;
                return $age . ' tahun';

                case 'sex':
                    return strtoupper($karyawan->sex) === 'LAKI-LAKI' ? 'L' : 'P';

            case 'agama':
                return $karyawan->agama;

            case 'kewarganegaraan':
                return $karyawan->kewarganegaraan ?? 'INDONESIA';

            case 'sts_nikah':
                return $karyawan->sts_nikah;

            case 'sts_keluarga':
                return $karyawan->sts_keluarga ?? '-';

            case 'jml_anak':
                return $karyawan->jml_anak ?? 0;

            case 'tlp1':
                return $karyawan->tlp1;

            case 'tlp2':
                return $karyawan->tlp2 ?? '-';

            case 'email1':
                return $karyawan->email1 ?? '-';

            case 'email2':
                return $karyawan->email2 ?? '-';

            case 'instagram':
                return $karyawan->instagram ?? '-';

            case 'facebook':
                return $karyawan->facebook ?? '-';

            // KTP Address
            case 'prov_ktp':
                return $karyawan->prov_ktp;
            case 'kota_ktp':
                return $karyawan->kota_ktp;
            case 'kec_ktp':
                return $karyawan->kec_ktp;
            case 'kel_ktp':
                return $karyawan->kel_ktp;
            case 'rt_rw_ktp':
                return $karyawan->rt_rw_ktp;
            case 'kd_pos_ktp':
                return $karyawan->kd_pos_ktp;
            case 'alamat_ktp':
                return $karyawan->alamat_ktp;

            // Domicile Address
            case 'prov_dom':
                return $karyawan->prov_dom;
            case 'kota_dom':
                return $karyawan->kota_dom;
            case 'kec_dom':
                return $karyawan->kec_dom;
            case 'kel_dom':
                return $karyawan->kel_dom;
            case 'rt_rw_dom':
                return $karyawan->rt_rw_dom;
            case 'kd_pos_dom':
                return $karyawan->kd_pos_dom;
            case 'alamat_dom':
                return $karyawan->alamat_dom;

            // Education
            case 'jenjang_skl':
                return $karyawan->jenjang_skl ?? '-';
            case 'institusi_skl':
                return $karyawan->institusi_skl ?? '-';
            case 'kota_skl':
                return $karyawan->kota_skl ?? '-';
            case 'fakultas_skl':
                return $karyawan->fakultas_skl ?? '-';
            case 'jurusan_skl':
                return $karyawan->jurusan_skl ?? '-';
            case 'gelar_skl':
                return $karyawan->gelar_skl ?? '-';
            case 'tgl_lulus_skl':
                return $karyawan->tgl_lulus_skl ? Carbon::parse($karyawan->tgl_lulus_skl)->format('d/m/Y') : '-';

            // Employment
            case 'perusahaan':
                return $karyawan->perusahaanRelation->nama_prs1 ?? '-';
            case 'singkatan_perusahaan':
                return $karyawan->perusahaanRelation->nama_prs2 ?? '-';
            case 'sts_ktr':
                return $karyawan->kontrakRelation->nama_ktr ?? '-';
            case 'singkatan_kontrak':
                return $karyawan->kontrakRelation->singkatan_ktr ?? '-';
            case 'tgl_awal_ktr':
                return $karyawan->tgl_awal_ktr ? Carbon::parse($karyawan->tgl_awal_ktr)->format('d/m/Y') : '-';
            case 'tgl_akhir_ktr':
                return $karyawan->tgl_akhir_ktr ? Carbon::parse($karyawan->tgl_akhir_ktr)->format('d/m/Y') : '-';
            case 'durasi_ktr':
                return $karyawan->durasi_ktr;

            // Career
            case 'departemen':
                return $karyawan->jabatan ?? $karyawan->departemenRelation->nama_dep ?? '-';
            case 'singkatan_departemen':
                return $karyawan->departemenRelation->singkatan_dep ?? '-';
            case 'jabatan':
                return $karyawan->departemenRelation->nama_jbt
                    ?? $karyawan->departemenRelation->nama_dep
                    ?? '-';
            case 'singkatan_jabatan':
                return $karyawan->departemenRelation->singkatan_jbt ?? '-';
            case 'wilker':
                return $karyawan->wilayahKerjaRelation->wilayah_krj
                    ?? $karyawan->unitKerjaRelation->wilayah_krj
                    ?? '-';
            case 'singkatan_wilker':
                return $karyawan->wilayahKerjaRelation->skt_wilker
                    ?? $karyawan->unitKerjaRelation->skt_wilker
                    ?? '-';
            case 'unit_krj':
                return $karyawan->unitKerjaRelation->area_krj ?? '-';
            case 'singkatan_unit_kerja':
                return $karyawan->unitKerjaRelation->singkatan_wk ?? '-';
            case 'tugas':
                return $karyawan->tugas;

            // Employment Status
            case 'sts_kry':
                return $karyawan->sts_kry;
            case 'tgl_phk':
                return $karyawan->tgl_phk ? Carbon::parse($karyawan->tgl_phk)->format('d/m/Y') : '-';
            case 'ket_phk':
                return $karyawan->ket_phk;

            default:
                return '-';
        }
    }

    public function headings(): array
    {
        $headings = [];

        foreach ($this->selectedFields as $field) {
            $headings[] = $this->fieldMap[$field] ?? $field;
        }

        return $headings;
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
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Find columns that need text formatting
                $textFormatColumns = [];
                foreach ($this->selectedFields as $index => $field) {
                    if (in_array($field, ['nrk', 'nik', 'tlp1', 'tlp2'])) {
                        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
                        $textFormatColumns[] = $columnLetter;
                    }
                }

                // Apply text format to specific columns
                foreach ($textFormatColumns as $column) {
                    $sheet->getStyle($column . '2:' . $column . $highestRow)
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_TEXT);

                    // Set explicit string type for each cell
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $value = $sheet->getCell($column . $row)->getValue();
                        if ($value && $value !== '-') {
                            $sheet->setCellValueExplicit($column . $row, $value, DataType::TYPE_STRING);
                        }
                    }
                }
            },
        ];
    }
}