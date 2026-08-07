<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Data\DataKaryawan;

class AiService
{
    private string $model   = 'qwen2.5:7b';
    private string $baseUrl = 'http://localhost:11434/api/generate';

    // Nama tabel yang benar (sesuai model masing-masing)
    private const TBL_KARYAWAN   = '201_dm_data_karyawan';
    private const TBL_DEPARTEMEN = '103_dm_departemen';
    private const TBL_WILKER     = '102_dm_wilker';         // fix: bukan 105_dm_wilayah_kerja
    private const TBL_PERUSAHAAN = '101_dm_perusahaan';
    private const TBL_KONTRAK    = '104_dm_kontrak';

    public function tanya(string $pertanyaan): string
    {
        $intent = $this->parseIntent($pertanyaan);
        $data   = $this->ambilData($intent, $pertanyaan);
        return $this->buatJawaban($pertanyaan, $data);
    }

    // =========================================================================
    // INTENT PARSER
    // =========================================================================

    private function parseIntent(string $pertanyaan): string
    {
        $prompt = <<<PROMPT
Kamu adalah sistem klasifikasi intent untuk aplikasi HRD / CRM karyawan.
Dari pertanyaan berikut, pilih SATU intent yang paling sesuai.

Daftar intent:
- total_karyawan           : jumlah total seluruh karyawan
- karyawan_aktif           : karyawan dengan status aktif
- karyawan_nonaktif        : karyawan yang sudah tidak aktif / resign / PHK
- karyawan_per_departemen  : jumlah atau daftar karyawan per departemen
- karyawan_per_wilayah     : karyawan berdasarkan wilayah kerja
- karyawan_per_perusahaan  : karyawan berdasarkan perusahaan atau cabang
- karyawan_per_kontrak     : karyawan berdasarkan jenis kontrak (PKWT, PKWTT, dll)
- karyawan_per_kota        : karyawan berdasarkan kota domisili atau KTP
- karyawan_per_agama       : karyawan berdasarkan agama
- karyawan_gender          : jumlah karyawan laki-laki dan perempuan
- karyawan_per_status_nikah: karyawan berdasarkan status pernikahan
- karyawan_per_pendidikan  : karyawan berdasarkan jenjang pendidikan
- karyawan_per_jabatan     : karyawan berdasarkan jabatan
- karyawan_per_jurusan     : karyawan berdasarkan jurusan atau bidang pendidikan
- ulang_tahun_dekat        : karyawan yang akan ulang tahun dalam waktu dekat
- ulang_tahun_hari_ini     : karyawan yang ulang tahun hari ini
- masa_kerja_terlama       : karyawan dengan masa kerja paling lama
- masa_kerja_terpendek     : karyawan yang paling baru bergabung
- karyawan_baru            : karyawan yang baru bergabung dalam 3 bulan terakhir
- rata_rata_masa_kerja     : rata-rata masa kerja karyawan
- kontrak_terdahulu        : karyawan dengan tanggal kontrak paling awal
- kontrak_akan_berakhir    : karyawan yang kontraknya akan segera habis
- kontrak_sudah_berakhir   : karyawan yang kontraknya sudah habis
- usia_tertua              : karyawan dengan usia paling tua
- usia_termuda             : karyawan dengan usia paling muda
- rata_rata_usia           : rata-rata usia karyawan
- cari_karyawan            : mencari karyawan tertentu berdasarkan nama, NRK, atau NIK

Pertanyaan: "{$pertanyaan}"

Jawab HANYA dengan satu kata intent di atas, tanpa penjelasan lain.
PROMPT;

        $response = $this->callOllama($prompt);
        $intent   = strtolower(trim($response));

        $validIntents = [
            'total_karyawan', 'karyawan_aktif', 'karyawan_nonaktif',
            'karyawan_per_departemen', 'karyawan_per_wilayah', 'karyawan_per_perusahaan',
            'karyawan_per_kontrak', 'karyawan_per_kota', 'karyawan_per_agama',
            'karyawan_gender', 'karyawan_per_status_nikah', 'karyawan_per_pendidikan',
            'karyawan_per_jabatan', 'karyawan_per_jurusan',
            'ulang_tahun_dekat', 'ulang_tahun_hari_ini',
            'masa_kerja_terlama', 'masa_kerja_terpendek', 'karyawan_baru', 'rata_rata_masa_kerja',
            'kontrak_terdahulu', 'kontrak_akan_berakhir', 'kontrak_sudah_berakhir',
            'usia_tertua', 'usia_termuda', 'rata_rata_usia',
            'cari_karyawan',
        ];

        return in_array($intent, $validIntents) ? $intent : 'total_karyawan';
    }

    // =========================================================================
    // DATA FETCHER
    // =========================================================================

    private function ambilData(string $intent, string $pertanyaan): array
    {
        return match ($intent) {
            'total_karyawan'            => $this->getTotalKaryawan(),
            'karyawan_aktif'            => $this->getKaryawanAktif(),
            'karyawan_nonaktif'         => $this->getKaryawanNonaktif(),
            'karyawan_per_departemen'   => $this->getPerDepartemen(),
            'karyawan_per_wilayah'      => $this->getPerWilayah(),
            'karyawan_per_perusahaan'   => $this->getPerPerusahaan(),
            'karyawan_per_kontrak'      => $this->getPerKontrak(),
            'karyawan_per_kota'         => $this->getPerKota(),
            'karyawan_per_agama'        => $this->getPerAgama(),
            'karyawan_gender'           => $this->getGender(),
            'karyawan_per_status_nikah' => $this->getPerStatusNikah(),
            'karyawan_per_pendidikan'   => $this->getPerPendidikan(),
            'karyawan_per_jabatan'      => $this->getPerJabatan(),
            'karyawan_per_jurusan'      => $this->getPerJurusan(),
            'ulang_tahun_hari_ini'      => $this->getUlangTahunHariIni(),
            'ulang_tahun_dekat'         => $this->getUlangTahunDekat(),
            'masa_kerja_terlama'        => $this->getMasaKerjaTerlama(),
            'masa_kerja_terpendek'      => $this->getMasaKerjaTerpendek(),
            'karyawan_baru'             => $this->getKaryawanBaru(),
            'rata_rata_masa_kerja'      => $this->getRataRataMasaKerja(),
            'kontrak_terdahulu'         => $this->getKontrakTerdahulu(),
            'kontrak_akan_berakhir'     => $this->getKontrakAkanBerakhir(),
            'kontrak_sudah_berakhir'    => $this->getKontrakSudahBerakhir(),
            'usia_tertua'               => $this->getUsiaTertua(),
            'usia_termuda'              => $this->getUsiaTermuda(),
            'rata_rata_usia'            => $this->getRataRataUsia(),
            'cari_karyawan'             => $this->getCariKaryawan($pertanyaan),
            default                     => $this->getTotalKaryawan(),
        };
    }

    // =========================================================================
    // QUERY METHODS
    // =========================================================================

    private function getTotalKaryawan(): array
    {
        try {
            return [
                'intent'   => 'total_karyawan',
                'total'    => DataKaryawan::count(),
                'aktif'    => DataKaryawan::where('sts_kry', 'AKTIF')->count(),
                'nonaktif' => DataKaryawan::where('sts_kry', '!=', 'AKTIF')->count(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getTotalKaryawan: ' . $e->getMessage());
            return ['intent' => 'total_karyawan', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getKaryawanAktif(): array
    {
        try {
            return [
                'intent' => 'karyawan_aktif',
                'total'  => DataKaryawan::where('sts_kry', 'AKTIF')->count(),
                'data'   => DataKaryawan::where('sts_kry', 'AKTIF')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_masuk, '%d %M %Y') as tgl_masuk_fmt,
                        TIMESTAMPDIFF(YEAR, tgl_masuk, CURDATE()) as tahun_kerja")
                    ->orderBy('tgl_masuk')->limit(15)->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getKaryawanAktif: ' . $e->getMessage());
            return ['intent' => 'karyawan_aktif', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getKaryawanNonaktif(): array
    {
        try {
            return [
                'intent' => 'karyawan_nonaktif',
                'total'  => DataKaryawan::where('sts_kry', '!=', 'AKTIF')->count(),
                'data'   => DataKaryawan::where('sts_kry', '!=', 'AKTIF')
                    ->selectRaw("nama, nrk, jabatan, sts_kry,
                        DATE_FORMAT(tgl_phk, '%d %M %Y') as tgl_phk_fmt, ket_phk")
                    ->orderByDesc('tgl_phk')->limit(15)->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getKaryawanNonaktif: ' . $e->getMessage());
            return ['intent' => 'karyawan_nonaktif', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getPerDepartemen(): array
    {
        try {
            return [
                'intent' => 'karyawan_per_departemen',
                'data'   => DataKaryawan::query()
                    ->join(self::TBL_DEPARTEMEN . ' as dep', 'dep.id', '=', self::TBL_KARYAWAN . '.departemen')
                    ->selectRaw('dep.nama_dep, dep.singkatan_dep, COUNT(*) as total,
                        SUM(CASE WHEN sts_kry = "AKTIF" THEN 1 ELSE 0 END) as aktif')
                    ->groupBy('dep.id', 'dep.nama_dep', 'dep.singkatan_dep')
                    ->orderByDesc('total')->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getPerDepartemen: ' . $e->getMessage());
            return ['intent' => 'karyawan_per_departemen', 'error' => 'Gagal mengambil data departemen.'];
        }
    }

    private function getPerWilayah(): array
    {
        try {
            return [
                'intent' => 'karyawan_per_wilayah',
                'data'   => DataKaryawan::query()
                    ->join(self::TBL_WILKER . ' as wil', 'wil.id', '=', self::TBL_KARYAWAN . '.wilker')
                    ->selectRaw('wil.wilayah_krj as nama_wilayah, wil.area_krj, COUNT(*) as total,
                        SUM(CASE WHEN sts_kry = "AKTIF" THEN 1 ELSE 0 END) as aktif')
                    ->groupBy('wil.id', 'wil.wilayah_krj', 'wil.area_krj')
                    ->orderByDesc('total')->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getPerWilayah: ' . $e->getMessage());
            return ['intent' => 'karyawan_per_wilayah', 'error' => 'Gagal mengambil data wilayah kerja.'];
        }
    }

    private function getPerPerusahaan(): array
    {
        try {
            return [
                'intent' => 'karyawan_per_perusahaan',
                'data'   => DataKaryawan::query()
                    ->join(self::TBL_PERUSAHAAN . ' as prs', 'prs.id', '=', self::TBL_KARYAWAN . '.perusahaan')
                    ->selectRaw('prs.nama_prs1, COUNT(*) as total,
                        SUM(CASE WHEN sts_kry = "AKTIF" THEN 1 ELSE 0 END) as aktif')
                    ->groupBy('prs.id', 'prs.nama_prs1')
                    ->orderByDesc('total')->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getPerPerusahaan: ' . $e->getMessage());
            return ['intent' => 'karyawan_per_perusahaan', 'error' => 'Gagal mengambil data perusahaan.'];
        }
    }

    private function getPerKontrak(): array
    {
        try {
            return [
                'intent' => 'karyawan_per_kontrak',
                'data'   => DataKaryawan::query()
                    ->join(self::TBL_KONTRAK . ' as ktr', 'ktr.id', '=', self::TBL_KARYAWAN . '.sts_ktr')
                    ->selectRaw('ktr.nama_ktr, ktr.singkatan_ktr, COUNT(*) as total')
                    ->groupBy('ktr.id', 'ktr.nama_ktr', 'ktr.singkatan_ktr')
                    ->orderByDesc('total')->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getPerKontrak: ' . $e->getMessage());
            return ['intent' => 'karyawan_per_kontrak', 'error' => 'Gagal mengambil data kontrak.'];
        }
    }

    private function getPerKota(): array
    {
        try {
            return [
                'intent'      => 'karyawan_per_kota',
                'berdasarkan' => 'kota domisili',
                'data'        => DataKaryawan::query()
                    ->selectRaw('kota_dom as kota, COUNT(*) as total')
                    ->whereNotNull('kota_dom')->where('kota_dom', '!=', '')
                    ->groupBy('kota_dom')->orderByDesc('total')->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getPerKota: ' . $e->getMessage());
            return ['intent' => 'karyawan_per_kota', 'error' => 'Gagal mengambil data kota.'];
        }
    }

    private function getPerAgama(): array
    {
        try {
            return [
                'intent' => 'karyawan_per_agama',
                'data'   => DataKaryawan::query()
                    ->selectRaw('agama, COUNT(*) as total')
                    ->whereNotNull('agama')
                    ->groupBy('agama')->orderByDesc('total')->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getPerAgama: ' . $e->getMessage());
            return ['intent' => 'karyawan_per_agama', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getGender(): array
    {
        try {
            return [
                'intent'     => 'karyawan_gender',
                'laki_laki'  => DataKaryawan::where('sex', 'L')->count(),
                'perempuan'  => DataKaryawan::where('sex', 'P')->count(),
                'laki_aktif' => DataKaryawan::where('sex', 'L')->where('sts_kry', 'AKTIF')->count(),
                'puan_aktif' => DataKaryawan::where('sex', 'P')->where('sts_kry', 'AKTIF')->count(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getGender: ' . $e->getMessage());
            return ['intent' => 'karyawan_gender', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getPerStatusNikah(): array
    {
        try {
            return [
                'intent' => 'karyawan_per_status_nikah',
                'data'   => DataKaryawan::query()
                    ->selectRaw('sts_nikah as status, COUNT(*) as total')
                    ->whereNotNull('sts_nikah')
                    ->groupBy('sts_nikah')->orderByDesc('total')->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getPerStatusNikah: ' . $e->getMessage());
            return ['intent' => 'karyawan_per_status_nikah', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getPerPendidikan(): array
    {
        try {
            return [
                'intent' => 'karyawan_per_pendidikan',
                'data'   => DataKaryawan::query()
                    ->selectRaw('jenjang_skl as jenjang, COUNT(*) as total')
                    ->whereNotNull('jenjang_skl')->where('jenjang_skl', '!=', '')
                    ->groupBy('jenjang_skl')->orderByDesc('total')->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getPerPendidikan: ' . $e->getMessage());
            return ['intent' => 'karyawan_per_pendidikan', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getPerJabatan(): array
    {
        try {
            return [
                'intent' => 'karyawan_per_jabatan',
                'data'   => DataKaryawan::query()
                    ->selectRaw('jabatan, COUNT(*) as total')
                    ->whereNotNull('jabatan')->where('jabatan', '!=', '')
                    ->groupBy('jabatan')->orderByDesc('total')->limit(20)->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getPerJabatan: ' . $e->getMessage());
            return ['intent' => 'karyawan_per_jabatan', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getPerJurusan(): array
    {
        try {
            return [
                'intent' => 'karyawan_per_jurusan',
                'data'   => DataKaryawan::query()
                    ->selectRaw('jurusan_skl as jurusan, jenjang_skl as jenjang, COUNT(*) as total')
                    ->whereNotNull('jurusan_skl')->where('jurusan_skl', '!=', '')
                    ->groupBy('jurusan_skl', 'jenjang_skl')
                    ->orderByDesc('total')->limit(20)->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getPerJurusan: ' . $e->getMessage());
            return ['intent' => 'karyawan_per_jurusan', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getUlangTahunHariIni(): array
    {
        try {
            return [
                'intent'   => 'ulang_tahun_hari_ini',
                'hari_ini' => now()->translatedFormat('d F Y'),
                'data'     => DataKaryawan::query()
                    ->whereNotNull('tgl_lahir')
                    ->whereRaw("DATE_FORMAT(tgl_lahir, '%m-%d') = ?", [now()->format('m-d')])
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_lahir, '%d %M') as tgl_lahir_fmt,
                        TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) as usia")
                    ->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getUlangTahunHariIni: ' . $e->getMessage());
            return ['intent' => 'ulang_tahun_hari_ini', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getUlangTahunDekat(): array
    {
        try {
            $today     = now()->format('m-d');
            $nextMonth = now()->addDays(30)->format('m-d');

            $query = DataKaryawan::query()
                ->whereNotNull('tgl_lahir')
                ->selectRaw("nama, nrk, jabatan,
                    DATE_FORMAT(tgl_lahir, '%d %M') as tgl_lahir_fmt,
                    DATE_FORMAT(tgl_lahir, '%m-%d') as bday,
                    TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) + 1 as usia_nanti,
                    DATEDIFF(
                        IF(
                            DATE(CONCAT(YEAR(CURDATE()),'-',LPAD(MONTH(tgl_lahir),2,'0'),'-',LPAD(DAY(tgl_lahir),2,'0'))) >= CURDATE(),
                            DATE(CONCAT(YEAR(CURDATE()),'-',LPAD(MONTH(tgl_lahir),2,'0'),'-',LPAD(DAY(tgl_lahir),2,'0'))),
                            DATE(CONCAT(YEAR(CURDATE())+1,'-',LPAD(MONTH(tgl_lahir),2,'0'),'-',LPAD(DAY(tgl_lahir),2,'0')))
                        ),
                        CURDATE()
                    ) as sisa_hari");

            if ($today <= $nextMonth) {
                $query->havingRaw("bday BETWEEN ? AND ?", [$today, $nextMonth]);
            } else {
                $query->havingRaw("bday >= ? OR bday <= ?", [$today, $nextMonth]);
            }

            return [
                'intent'   => 'ulang_tahun_dekat',
                'periode'  => '30 hari ke depan',
                'hari_ini' => now()->translatedFormat('d F Y'),
                'data'     => $query->orderByRaw('sisa_hari ASC')->limit(15)->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getUlangTahunDekat: ' . $e->getMessage());
            return ['intent' => 'ulang_tahun_dekat', 'error' => 'Gagal mengambil data ulang tahun.'];
        }
    }

    private function getMasaKerjaTerlama(): array
    {
        try {
            return [
                'intent'     => 'masa_kerja_terlama',
                'keterangan' => 'Karyawan aktif dengan masa kerja paling lama',
                'data'       => DataKaryawan::query()
                    ->whereNotNull('tgl_masuk')->where('sts_kry', 'AKTIF')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_masuk, '%d %M %Y') as tgl_masuk_fmt,
                        TIMESTAMPDIFF(YEAR, tgl_masuk, CURDATE()) as tahun_kerja,
                        TIMESTAMPDIFF(MONTH, tgl_masuk, CURDATE()) % 12 as bulan_kerja")
                    ->orderBy('tgl_masuk', 'ASC')->limit(10)->get()
                    ->map(fn($k) => [
                        'nama'       => $k->nama,
                        'nrk'        => $k->nrk,
                        'jabatan'    => $k->jabatan,
                        'tgl_masuk'  => $k->tgl_masuk_fmt,
                        'masa_kerja' => $k->tahun_kerja . ' tahun ' . $k->bulan_kerja . ' bulan',
                    ])->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getMasaKerjaTerlama: ' . $e->getMessage());
            return ['intent' => 'masa_kerja_terlama', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getMasaKerjaTerpendek(): array
    {
        try {
            return [
                'intent'     => 'masa_kerja_terpendek',
                'keterangan' => 'Karyawan aktif yang paling baru bergabung',
                'data'       => DataKaryawan::query()
                    ->whereNotNull('tgl_masuk')->where('sts_kry', 'AKTIF')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_masuk, '%d %M %Y') as tgl_masuk_fmt,
                        TIMESTAMPDIFF(YEAR, tgl_masuk, CURDATE()) as tahun_kerja,
                        TIMESTAMPDIFF(MONTH, tgl_masuk, CURDATE()) % 12 as bulan_kerja")
                    ->orderBy('tgl_masuk', 'DESC')->limit(10)->get()
                    ->map(fn($k) => [
                        'nama'       => $k->nama,
                        'nrk'        => $k->nrk,
                        'jabatan'    => $k->jabatan,
                        'tgl_masuk'  => $k->tgl_masuk_fmt,
                        'masa_kerja' => $k->tahun_kerja . ' tahun ' . $k->bulan_kerja . ' bulan',
                    ])->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getMasaKerjaTerpendek: ' . $e->getMessage());
            return ['intent' => 'masa_kerja_terpendek', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getKaryawanBaru(): array
    {
        try {
            return [
                'intent'  => 'karyawan_baru',
                'periode' => '3 bulan terakhir',
                'data'    => DataKaryawan::query()
                    ->whereNotNull('tgl_masuk')
                    ->where('tgl_masuk', '>=', now()->subMonths(3)->toDateString())
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_masuk, '%d %M %Y') as tgl_masuk_fmt,
                        DATEDIFF(CURDATE(), tgl_masuk) as hari_bergabung")
                    ->orderBy('tgl_masuk', 'DESC')->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getKaryawanBaru: ' . $e->getMessage());
            return ['intent' => 'karyawan_baru', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getRataRataMasaKerja(): array
    {
        try {
            $avgBulan = DataKaryawan::where('sts_kry', 'AKTIF')->whereNotNull('tgl_masuk')
                ->selectRaw('AVG(TIMESTAMPDIFF(MONTH, tgl_masuk, CURDATE())) as avg_bulan')
                ->value('avg_bulan');
            return [
                'intent'    => 'rata_rata_masa_kerja',
                'rata_rata' => floor($avgBulan / 12) . ' tahun ' . round(fmod($avgBulan, 12)) . ' bulan',
                'keterangan'=> 'Dihitung dari seluruh karyawan aktif',
            ];
        } catch (\Exception $e) {
            Log::error('AI getRataRataMasaKerja: ' . $e->getMessage());
            return ['intent' => 'rata_rata_masa_kerja', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getKontrakTerdahulu(): array
    {
        try {
            return [
                'intent'     => 'kontrak_terdahulu',
                'keterangan' => 'Karyawan dengan tanggal mulai kontrak paling awal',
                'data'       => DataKaryawan::query()
                    ->whereNotNull('tgl_awal_ktr')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_awal_ktr, '%d %M %Y') as awal_kontrak,
                        DATE_FORMAT(tgl_akhir_ktr, '%d %M %Y') as akhir_kontrak,
                        DATEDIFF(IFNULL(tgl_akhir_ktr, CURDATE()), tgl_awal_ktr) as durasi_hari")
                    ->orderBy('tgl_awal_ktr', 'ASC')->limit(10)->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getKontrakTerdahulu: ' . $e->getMessage());
            return ['intent' => 'kontrak_terdahulu', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getKontrakAkanBerakhir(): array
    {
        try {
            return [
                'intent'  => 'kontrak_akan_berakhir',
                'periode' => '30 hari ke depan',
                'data'    => DataKaryawan::query()
                    ->whereNotNull('tgl_akhir_ktr')
                    ->whereBetween('tgl_akhir_ktr', [now()->toDateString(), now()->addDays(30)->toDateString()])
                    ->where('sts_kry', 'AKTIF')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_awal_ktr, '%d %M %Y') as awal_kontrak,
                        DATE_FORMAT(tgl_akhir_ktr, '%d %M %Y') as akhir_kontrak,
                        DATEDIFF(tgl_akhir_ktr, CURDATE()) as sisa_hari")
                    ->orderBy('tgl_akhir_ktr', 'ASC')->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getKontrakAkanBerakhir: ' . $e->getMessage());
            return ['intent' => 'kontrak_akan_berakhir', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getKontrakSudahBerakhir(): array
    {
        try {
            return [
                'intent'     => 'kontrak_sudah_berakhir',
                'keterangan' => 'Karyawan aktif yang kontraknya sudah lewat — perlu tindak lanjut HRD',
                'data'       => DataKaryawan::query()
                    ->whereNotNull('tgl_akhir_ktr')
                    ->where('tgl_akhir_ktr', '<', now()->toDateString())
                    ->where('sts_kry', 'AKTIF')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_akhir_ktr, '%d %M %Y') as akhir_kontrak,
                        DATEDIFF(CURDATE(), tgl_akhir_ktr) as hari_lewat")
                    ->orderBy('tgl_akhir_ktr', 'ASC')->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getKontrakSudahBerakhir: ' . $e->getMessage());
            return ['intent' => 'kontrak_sudah_berakhir', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getUsiaTertua(): array
    {
        try {
            return [
                'intent' => 'usia_tertua',
                'data'   => DataKaryawan::query()->whereNotNull('tgl_lahir')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_lahir, '%d %M %Y') as tgl_lahir_fmt,
                        TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) as usia")
                    ->orderBy('tgl_lahir', 'ASC')->limit(10)->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getUsiaTertua: ' . $e->getMessage());
            return ['intent' => 'usia_tertua', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getUsiaTermuda(): array
    {
        try {
            return [
                'intent' => 'usia_termuda',
                'data'   => DataKaryawan::query()->whereNotNull('tgl_lahir')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_lahir, '%d %M %Y') as tgl_lahir_fmt,
                        TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) as usia")
                    ->orderBy('tgl_lahir', 'DESC')->limit(10)->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getUsiaTermuda: ' . $e->getMessage());
            return ['intent' => 'usia_termuda', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getRataRataUsia(): array
    {
        try {
            return [
                'intent'     => 'rata_rata_usia',
                'rata_rata'  => round(DataKaryawan::whereNotNull('tgl_lahir')
                    ->selectRaw('AVG(TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE())) as v')->value('v'), 1) . ' tahun',
                'termuda'    => DataKaryawan::whereNotNull('tgl_lahir')
                    ->selectRaw('MIN(TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE())) as v')->value('v') . ' tahun',
                'tertua'     => DataKaryawan::whereNotNull('tgl_lahir')
                    ->selectRaw('MAX(TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE())) as v')->value('v') . ' tahun',
                'keterangan' => 'Dihitung dari seluruh karyawan yang memiliki data tanggal lahir',
            ];
        } catch (\Exception $e) {
            Log::error('AI getRataRataUsia: ' . $e->getMessage());
            return ['intent' => 'rata_rata_usia', 'error' => 'Gagal mengambil data.'];
        }
    }

    private function getCariKaryawan(string $pertanyaan): array
    {
        try {
            $stopwords = ['siapa', 'cari', 'tampilkan', 'data', 'karyawan', 'yang',
                          'bernama', 'dengan', 'nrk', 'nik', 'nama', 'adalah', 'ada'];
            $kata = strtolower($pertanyaan);
            foreach ($stopwords as $sw) {
                $kata = str_replace($sw, '', $kata);
            }
            $keyword = trim($kata);

            return [
                'intent'  => 'cari_karyawan',
                'keyword' => $keyword,
                'hasil'   => DataKaryawan::query()
                    ->where(function ($q) use ($keyword) {
                        $q->where('nama', 'like', "%{$keyword}%")
                          ->orWhere('nrk',  'like', "%{$keyword}%")
                          ->orWhere('nik',  'like', "%{$keyword}%");
                    })
                    ->selectRaw("nama, nrk, nik, jabatan, sts_kry, kota_dom,
                        DATE_FORMAT(tgl_masuk, '%d %M %Y') as tgl_masuk_fmt,
                        TIMESTAMPDIFF(YEAR, tgl_masuk, CURDATE()) as tahun_kerja")
                    ->limit(10)->get()->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('AI getCariKaryawan: ' . $e->getMessage());
            return ['intent' => 'cari_karyawan', 'error' => 'Gagal melakukan pencarian.'];
        }
    }

    // =========================================================================
    // BUAT JAWABAN NATURAL
    // =========================================================================

    private function buatJawaban(string $pertanyaan, array $data): string
    {
        if (isset($data['error'])) {
            return 'Maaf, terjadi masalah saat mengambil data: ' . $data['error'];
        }

        $dataJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $prompt = <<<PROMPT
Kamu adalah asisten HRD bernama SISKA yang membantu menjawab pertanyaan tentang data karyawan.
Gunakan Bahasa Indonesia yang natural, hangat, dan profesional.
Jawab berdasarkan data yang diberikan saja — jangan mengarang atau menambah informasi.
Jika data kosong atau tidak ada, sampaikan dengan sopan.
Jika ada daftar karyawan, tampilkan dengan format yang rapi dan mudah dibaca.
Tambahkan insight singkat jika relevan.

Pertanyaan: "{$pertanyaan}"

Data dari database:
{$dataJson}

Jawaban:
PROMPT;

        return $this->callOllama($prompt);
    }

    // =========================================================================
    // OLLAMA HTTP CALL
    // =========================================================================

    private function callOllama(string $prompt): string
    {
        try {
            $response = Http::timeout(90)
                ->retry(2, 1000)
                ->post($this->baseUrl, [
                    'model'   => $this->model,
                    'prompt'  => $prompt,
                    'stream'  => false,
                    'options' => ['temperature' => 0.3, 'num_predict' => 600],
                ]);

            if ($response->failed()) {
                Log::error('Ollama HTTP error', ['status' => $response->status()]);
                return 'Maaf, server AI mengembalikan error (HTTP ' . $response->status() . ').';
            }

            return $response->json('response') ?? 'Maaf, tidak ada respons dari AI.';

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Ollama tidak bisa dihubungi: ' . $e->getMessage());
            return 'Maaf, tidak bisa terhubung ke server AI. Pastikan Ollama sedang berjalan.';
        } catch (\Exception $e) {
            Log::error('Ollama unexpected error: ' . $e->getMessage());
            return 'Terjadi kesalahan tidak terduga. Silakan coba lagi.';
        }
    }
}