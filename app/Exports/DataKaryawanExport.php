<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class DataKaryawanExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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
            return [
                'No' => $index + 1,
                'Tanggal Masuk' => $karyawan->tgl_masuk ? Carbon::parse($karyawan->tgl_masuk)->format('d/m/Y') : '-',
                'NRK' => $karyawan->nrk ?? '-',
                'NIK' => $karyawan->nik,
                'Nama' => $karyawan->nama,
                'Tempat Lahir' => $karyawan->tpt_lahir,
                'Tanggal Lahir' => $karyawan->tgl_lahir ? Carbon::parse($karyawan->tgl_lahir)->format('d/m/Y') : '-',
                'Jenis Kelamin' => $karyawan->sex,
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
                'Status Kontrak' => $karyawan->sts_ktr ?? '-',
                'Tanggal Awal Kontrak' => $karyawan->tgl_awal_ktr ? Carbon::parse($karyawan->tgl_awal_ktr)->format('d/m/Y') : '-',
                'Tanggal Akhir Kontrak' => $karyawan->tgl_akhir_ktr ? Carbon::parse($karyawan->tgl_akhir_ktr)->format('d/m/Y') : '-',
                'Durasi Kontrak' => $karyawan->durasi_ktr,

                // Career
                'Departemen' => $karyawan->departemenRelation->nama_dep ?? '-',
                'Jabatan' => $karyawan->jabatan ?? '-',
                'Wilayah Kerja' => $karyawan->wilayahKerjaRelation->wilayah_krj ?? '-',
                'Unit Kerja' => $karyawan->unit_krj,
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
        // This corresponds to the keys in the collection method
        return [
            'No', 'Tanggal Masuk', 'NRK', 'NIK', 'Nama',
            'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin',
            'Agama', 'Kewarganegaraan', 'Status Nikah',
            'Status Keluarga', 'Jumlah Anak', 'Telepon 1',
            'Telepon 2', 'Email 1', 'Email 2', 'Instagram',
            'Facebook', 'Provinsi KTP', 'Kota KTP',
            'Kecamatan KTP', 'Kelurahan KTP', 'RT/RW KTP',
            'Kode Pos KTP', 'Alamat KTP', 'Provinsi DOM',
            'Kota DOM', 'Kecamatan DOM', 'Kelurahan DOM',
            'RT/RW DOM', 'Kode Pos DOM', 'Alamat DOM',
            'Jenjang', 'Institusi', 'Kota', 'Fakultas',
            'Jurusan', 'Gelar', 'Tanggal Lulus', 'Perusahaan',
            'Status Kontrak', 'Tanggal Awal Kontrak',
            'Tanggal Akhir Kontrak', 'Durasi Kontrak',
            'Departemen', 'Jabatan', 'Wilayah Kerja',
            'Unit Kerja', 'Tugas', 'Status Karyawan',
            'Tanggal PHK', 'Keterangan PHK'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as headers
            1 => ['font' => ['bold' => true]],
        ];
    }
}