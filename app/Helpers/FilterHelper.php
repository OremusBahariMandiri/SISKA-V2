<?php

namespace App\Helpers;

use App\Models\DataMaster\Perusahaan;
use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\KontrakKerja;
use App\Models\DataMaster\WilayahKerja;
use Illuminate\Support\Facades\DB;

class FilterHelper
{
    // ===== DATA KONTRAK =====
    public static function getFilterDataOptions(): array
    {
        return [
            [
                'name' => 'status',
                'label' => 'Status Karyawan',
                'type' => 'select',
                'placeholder' => 'Semua Status',
                'data' => [
                    ['value' => 'AKTIF',     'label' => 'AKTIF'],
                    ['value' => 'NON-AKTIF', 'label' => 'NON-AKTIF'],
                ],
            ],
            [
                'name' => 'jabatan',
                'label' => 'Jabatan',
                'type' => 'select',
                'placeholder' => 'Semua Jabatan',
                'data' => Departemen::orderByRaw('CAST(kode_dep AS UNSIGNED) ASC')
                    ->get()
                    ->map(fn($j) => [
                        'value' => $j->id,
                        'label' => $j->nama_jbt . ($j->singkatan_jbt ? " ({$j->singkatan_jbt})" : '') . " - {$j->nama_dep}",
                    ])->toArray(),
            ],
            [
                'name' => 'perusahaan',
                'label' => 'Perusahaan',
                'type' => 'select',
                'placeholder' => 'Semua Perusahaan',
                'data' => Perusahaan::orderBy('nama_prs1')->get()
                    ->map(fn($p) => [
                        'value' => $p->id,
                        'label' => "{$p->nama_prs1} - {$p->nama_prs2}",
                    ])->toArray(),
            ],
            [
                'name' => 'kontrak',
                'label' => 'Jenis Kontrak',
                'type' => 'select',
                'placeholder' => 'Semua Kontrak',
                'data' => KontrakKerja::orderBy('kode_ktr')->get()
                    ->map(fn($k) => [
                        'value' => $k->id,
                        'label' => $k->nama_ktr . ($k->singkatan_ktr ? " ({$k->singkatan_ktr})" : ''),
                    ])->toArray(),
            ],
            [
                'name' => 'wilker',
                'label' => 'Wilayah Kerja',
                'type' => 'select',
                'placeholder' => 'Semua Wilayah Kerja',
                'data' => WilayahKerja::select('wilayah_krj')->groupBy('wilayah_krj')->orderBy('wilayah_krj')->get()
                    ->map(fn($w) => [
                        'value' => $w->wilayah_krj,
                        'label' => $w->wilayah_krj,
                    ])->toArray(),
            ],
            [
                'name' => 'jenis_kelamin',
                'label' => 'Jenis Kelamin',
                'type' => 'select',
                'placeholder' => 'Semua Jenis Kelamin',
                'data' => [
                    ['value' => 'LAKI-LAKI', 'label' => 'Laki-laki'],
                    ['value' => 'PEREMPUAN', 'label' => 'Perempuan'],
                ],
            ],
            [
                'name' => 'unit_kerja',
                'label' => 'Area Kerja',
                'type' => 'select',
                'placeholder' => 'Semua Area Kerja',
                'data' => WilayahKerja::orderBy('area_krj')->get()
                    ->map(fn($u) => [
                        'value' => $u->id,
                        'label' => $u->area_krj,
                    ])->toArray(),
            ],
            [
                'name' => 'departemen',
                'label' => 'Departemen',
                'type' => 'select',
                'placeholder' => 'Semua Departemen',
                'data' => Departemen::select('nama_dep', 'singkatan_dep', DB::raw('MIN(id) as id'))
                    ->groupBy('nama_dep', 'singkatan_dep')
                    ->orderByRaw('MIN(CAST(kode_dep AS UNSIGNED)) ASC')
                    ->get()
                    ->map(fn($d) => [
                        'value' => $d->id,
                        'label' => $d->nama_dep . ($d->singkatan_dep ? " ({$d->singkatan_dep})" : ''),
                    ])->toArray(),
            ],
            [
                'name' => 'nama',
                'label' => 'Nama Karyawan',
                'type' => 'text',
                'placeholder' => 'Cari nama karyawan...',
            ],
            [
                'name' => 'nrk',
                'label' => 'NRK',
                'type' => 'text',
                'placeholder' => 'Cari NRK karyawan...',
            ],
        ];
    }

    // ===== MENU LAIN (contoh untuk Data Karyawan) =====
    // public static function getDataKaryawanFilterOptions(): array { ... }
}