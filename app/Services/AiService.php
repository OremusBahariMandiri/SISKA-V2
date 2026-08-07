<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Data\DataKaryawan;

class AiService
{
    private string $model   = 'qwen2.5:7b';
    private string $baseUrl = 'http://localhost:11434/api/generate';

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
- ulang_tahun_dekat        : karyawan yang akan ulang tahun dalam waktu dekat
- ulang_tahun_hari_ini     : karyawan yang ulang tahun hari ini
- masa_kerja_terlama       : karyawan dengan masa kerja paling lama
- masa_kerja_terpendek     : karyawan dengan masa kerja paling pendek atau paling baru bergabung
- karyawan_baru            : karyawan yang baru bergabung dalam 3 bulan terakhir
- kontrak_terdahulu        : karyawan dengan tanggal kontrak paling awal
- kontrak_akan_berakhir    : karyawan yang kontraknya akan segera habis dalam 30 hari
- kontrak_sudah_berakhir   : karyawan yang kontraknya sudah habis
- usia_tertua              : karyawan dengan usia paling tua
- usia_termuda             : karyawan dengan usia paling muda
- rata_rata_usia           : rata-rata usia karyawan
- rata_rata_masa_kerja     : rata-rata masa kerja karyawan
- karyawan_per_jabatan     : karyawan berdasarkan jabatan
- karyawan_per_jurusan     : karyawan berdasarkan jurusan atau bidang pendidikan
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
            'ulang_tahun_dekat', 'ulang_tahun_hari_ini',
            'masa_kerja_terlama', 'masa_kerja_terpendek', 'karyawan_baru',
            'kontrak_terdahulu', 'kontrak_akan_berakhir', 'kontrak_sudah_berakhir',
            'usia_tertua', 'usia_termuda', 'rata_rata_usia', 'rata_rata_masa_kerja',
            'karyawan_per_jabatan', 'karyawan_per_jurusan', 'cari_karyawan',
        ];

        return in_array($intent, $validIntents) ? $intent : 'total_karyawan';
    }

    // =========================================================================
    // DATA FETCHER
    // =========================================================================

    private function ambilData(string $intent, string $pertanyaan): array
    {
        return match ($intent) {

            // -----------------------------------------------------------------
            // TOTAL & STATUS
            // -----------------------------------------------------------------

            'total_karyawan' => [
                'intent' => 'total_karyawan',
                'total'  => DataKaryawan::count(),
                'aktif'  => DataKaryawan::where('sts_kry', 'AKTIF')->count(),
                'nonaktif' => DataKaryawan::where('sts_kry', '!=', 'AKTIF')->count(),
            ],

            'karyawan_aktif' => [
                'intent' => 'karyawan_aktif',
                'total'  => DataKaryawan::where('sts_kry', 'AKTIF')->count(),
                'data'   => DataKaryawan::where('sts_kry', 'AKTIF')
                    ->selectRaw("nama, nrk, jabatan, DATE_FORMAT(tgl_masuk, '%d %M %Y') as tgl_masuk_fmt,
                        TIMESTAMPDIFF(YEAR, tgl_masuk, CURDATE()) as tahun_kerja")
                    ->orderBy('tgl_masuk')
                    ->limit(15)
                    ->get()
                    ->toArray(),
            ],

            'karyawan_nonaktif' => [
                'intent' => 'karyawan_nonaktif',
                'total'  => DataKaryawan::where('sts_kry', '!=', 'AKTIF')->count(),
                'data'   => DataKaryawan::where('sts_kry', '!=', 'AKTIF')
                    ->selectRaw("nama, nrk, jabatan, sts_kry,
                        DATE_FORMAT(tgl_phk, '%d %M %Y') as tgl_phk_fmt, ket_phk")
                    ->orderByDesc('tgl_phk')
                    ->limit(15)
                    ->get()
                    ->toArray(),
            ],

            // -----------------------------------------------------------------
            // PER KATEGORI
            // -----------------------------------------------------------------

            'karyawan_per_departemen' => [
                'intent' => 'karyawan_per_departemen',
                'data'   => DataKaryawan::query()
                    ->join('103_dm_departemen as dep', 'dep.id', '=', '201_dm_data_karyawan.departemen')
                    ->selectRaw('dep.nama_dep, dep.singkatan_dep, COUNT(*) as total,
                        SUM(CASE WHEN sts_kry = "AKTIF" THEN 1 ELSE 0 END) as aktif')
                    ->groupBy('dep.id', 'dep.nama_dep', 'dep.singkatan_dep')
                    ->orderByDesc('total')
                    ->get()
                    ->toArray(),
            ],

            'karyawan_per_wilayah' => [
                'intent' => 'karyawan_per_wilayah',
                'data'   => DataKaryawan::query()
                    ->join('105_dm_wilayah_kerja as wil', 'wil.id', '=', '201_dm_data_karyawan.wilker')
                    ->selectRaw('wil.nama_wil, COUNT(*) as total,
                        SUM(CASE WHEN sts_kry = "AKTIF" THEN 1 ELSE 0 END) as aktif')
                    ->groupBy('wil.id', 'wil.nama_wil')
                    ->orderByDesc('total')
                    ->get()
                    ->toArray(),
            ],

            'karyawan_per_perusahaan' => [
                'intent' => 'karyawan_per_perusahaan',
                'data'   => DataKaryawan::query()
                    ->join('101_dm_perusahaan as prs', 'prs.id', '=', '201_dm_data_karyawan.perusahaan')
                    ->selectRaw('prs.nama_prs1, COUNT(*) as total,
                        SUM(CASE WHEN sts_kry = "AKTIF" THEN 1 ELSE 0 END) as aktif')
                    ->groupBy('prs.id', 'prs.nama_prs1')
                    ->orderByDesc('total')
                    ->get()
                    ->toArray(),
            ],

            'karyawan_per_kontrak' => [
                'intent' => 'karyawan_per_kontrak',
                'data'   => DataKaryawan::query()
                    ->join('104_dm_kontrak as ktr', 'ktr.id', '=', '201_dm_data_karyawan.sts_ktr')
                    ->selectRaw('ktr.nama_ktr, ktr.singkatan_ktr, COUNT(*) as total')
                    ->groupBy('ktr.id', 'ktr.nama_ktr', 'ktr.singkatan_ktr')
                    ->orderByDesc('total')
                    ->get()
                    ->toArray(),
            ],

            'karyawan_per_kota' => [
                'intent' => 'karyawan_per_kota',
                'berdasarkan' => 'kota domisili',
                'data'   => DataKaryawan::query()
                    ->selectRaw('kota_dom as kota, COUNT(*) as total')
                    ->whereNotNull('kota_dom')
                    ->where('kota_dom', '!=', '')
                    ->groupBy('kota_dom')
                    ->orderByDesc('total')
                    ->get()
                    ->toArray(),
            ],

            'karyawan_per_agama' => [
                'intent' => 'karyawan_per_agama',
                'data'   => DataKaryawan::query()
                    ->selectRaw('agama, COUNT(*) as total')
                    ->whereNotNull('agama')
                    ->groupBy('agama')
                    ->orderByDesc('total')
                    ->get()
                    ->toArray(),
            ],

            'karyawan_gender' => [
                'intent'     => 'karyawan_gender',
                'laki_laki'  => DataKaryawan::where('sex', 'L')->count(),
                'perempuan'  => DataKaryawan::where('sex', 'P')->count(),
                'laki_aktif' => DataKaryawan::where('sex', 'L')->where('sts_kry', 'AKTIF')->count(),
                'puan_aktif' => DataKaryawan::where('sex', 'P')->where('sts_kry', 'AKTIF')->count(),
            ],

            'karyawan_per_status_nikah' => [
                'intent' => 'karyawan_per_status_nikah',
                'data'   => DataKaryawan::query()
                    ->selectRaw('sts_nikah as status, COUNT(*) as total')
                    ->whereNotNull('sts_nikah')
                    ->groupBy('sts_nikah')
                    ->orderByDesc('total')
                    ->get()
                    ->toArray(),
            ],

            'karyawan_per_pendidikan' => [
                'intent' => 'karyawan_per_pendidikan',
                'data'   => DataKaryawan::query()
                    ->selectRaw('jenjang_skl as jenjang, COUNT(*) as total')
                    ->whereNotNull('jenjang_skl')
                    ->where('jenjang_skl', '!=', '')
                    ->groupBy('jenjang_skl')
                    ->orderByDesc('total')
                    ->get()
                    ->toArray(),
            ],

            'karyawan_per_jabatan' => [
                'intent' => 'karyawan_per_jabatan',
                'data'   => DataKaryawan::query()
                    ->selectRaw('jabatan, COUNT(*) as total')
                    ->whereNotNull('jabatan')
                    ->where('jabatan', '!=', '')
                    ->groupBy('jabatan')
                    ->orderByDesc('total')
                    ->limit(20)
                    ->get()
                    ->toArray(),
            ],

            'karyawan_per_jurusan' => [
                'intent' => 'karyawan_per_jurusan',
                'data'   => DataKaryawan::query()
                    ->selectRaw('jurusan_skl as jurusan, jenjang_skl as jenjang, COUNT(*) as total')
                    ->whereNotNull('jurusan_skl')
                    ->where('jurusan_skl', '!=', '')
                    ->groupBy('jurusan_skl', 'jenjang_skl')
                    ->orderByDesc('total')
                    ->limit(20)
                    ->get()
                    ->toArray(),
            ],

            // -----------------------------------------------------------------
            // ULANG TAHUN
            // -----------------------------------------------------------------

            'ulang_tahun_hari_ini' => [
                'intent'   => 'ulang_tahun_hari_ini',
                'hari_ini' => now()->translatedFormat('d F Y'),
                'data'     => DataKaryawan::query()
                    ->whereNotNull('tgl_lahir')
                    ->whereRaw("DATE_FORMAT(tgl_lahir, '%m-%d') = ?", [now()->format('m-d')])
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_lahir, '%d %M') as tgl_lahir_fmt,
                        TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) as usia")
                    ->get()
                    ->toArray(),
            ],

            'ulang_tahun_dekat' => (function () {
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
                                DATE(CONCAT(YEAR(CURDATE()), '-', LPAD(MONTH(tgl_lahir),2,'0'), '-', LPAD(DAY(tgl_lahir),2,'0'))) >= CURDATE(),
                                DATE(CONCAT(YEAR(CURDATE()), '-', LPAD(MONTH(tgl_lahir),2,'0'), '-', LPAD(DAY(tgl_lahir),2,'0'))),
                                DATE(CONCAT(YEAR(CURDATE())+1, '-', LPAD(MONTH(tgl_lahir),2,'0'), '-', LPAD(DAY(tgl_lahir),2,'0')))
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
            })(),

            // -----------------------------------------------------------------
            // MASA KERJA
            // -----------------------------------------------------------------

            'masa_kerja_terlama' => [
                'intent'   => 'masa_kerja_terlama',
                'hari_ini' => now()->translatedFormat('d F Y'),
                'data'     => DataKaryawan::query()
                    ->whereNotNull('tgl_masuk')
                    ->where('sts_kry', 'AKTIF')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_masuk, '%d %M %Y') as tgl_masuk_fmt,
                        TIMESTAMPDIFF(YEAR, tgl_masuk, CURDATE()) as tahun_kerja,
                        TIMESTAMPDIFF(MONTH, tgl_masuk, CURDATE()) % 12 as bulan_kerja")
                    ->orderBy('tgl_masuk', 'ASC')
                    ->limit(10)
                    ->get()
                    ->map(fn($k) => array_merge($k->toArray(), [
                        'masa_kerja' => $k->tahun_kerja . ' tahun ' . $k->bulan_kerja . ' bulan',
                    ]))
                    ->toArray(),
            ],

            'masa_kerja_terpendek' => [
                'intent'   => 'masa_kerja_terpendek',
                'hari_ini' => now()->translatedFormat('d F Y'),
                'keterangan' => 'Karyawan aktif yang paling baru bergabung',
                'data'     => DataKaryawan::query()
                    ->whereNotNull('tgl_masuk')
                    ->where('sts_kry', 'AKTIF')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_masuk, '%d %M %Y') as tgl_masuk_fmt,
                        TIMESTAMPDIFF(YEAR, tgl_masuk, CURDATE()) as tahun_kerja,
                        TIMESTAMPDIFF(MONTH, tgl_masuk, CURDATE()) % 12 as bulan_kerja")
                    ->orderBy('tgl_masuk', 'DESC')
                    ->limit(10)
                    ->get()
                    ->map(fn($k) => array_merge($k->toArray(), [
                        'masa_kerja' => $k->tahun_kerja . ' tahun ' . $k->bulan_kerja . ' bulan',
                    ]))
                    ->toArray(),
            ],

            'karyawan_baru' => [
                'intent'  => 'karyawan_baru',
                'periode' => '3 bulan terakhir',
                'data'    => DataKaryawan::query()
                    ->whereNotNull('tgl_masuk')
                    ->where('tgl_masuk', '>=', now()->subMonths(3)->toDateString())
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_masuk, '%d %M %Y') as tgl_masuk_fmt,
                        DATEDIFF(CURDATE(), tgl_masuk) as hari_bergabung")
                    ->orderBy('tgl_masuk', 'DESC')
                    ->get()
                    ->toArray(),
            ],

            'rata_rata_masa_kerja' => [
                'intent'         => 'rata_rata_masa_kerja',
                'rata_rata_bulan'=> DataKaryawan::where('sts_kry', 'AKTIF')
                    ->whereNotNull('tgl_masuk')
                    ->selectRaw('AVG(TIMESTAMPDIFF(MONTH, tgl_masuk, CURDATE())) as avg_bulan')
                    ->value('avg_bulan'),
                'keterangan'     => 'Rata-rata dihitung dari seluruh karyawan aktif',
            ],

            // -----------------------------------------------------------------
            // KONTRAK
            // -----------------------------------------------------------------

            'kontrak_terdahulu' => [
                'intent' => 'kontrak_terdahulu',
                'keterangan' => 'Karyawan dengan tanggal mulai kontrak paling awal',
                'data'   => DataKaryawan::query()
                    ->whereNotNull('tgl_awal_ktr')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_awal_ktr, '%d %M %Y') as awal_kontrak,
                        DATE_FORMAT(tgl_akhir_ktr, '%d %M %Y') as akhir_kontrak,
                        DATEDIFF(tgl_akhir_ktr, tgl_awal_ktr) as durasi_hari")
                    ->orderBy('tgl_awal_ktr', 'ASC')
                    ->limit(10)
                    ->get()
                    ->toArray(),
            ],

            'kontrak_akan_berakhir' => [
                'intent'  => 'kontrak_akan_berakhir',
                'periode' => '30 hari ke depan',
                'data'    => DataKaryawan::query()
                    ->whereNotNull('tgl_akhir_ktr')
                    ->whereBetween('tgl_akhir_ktr', [
                        now()->toDateString(),
                        now()->addDays(30)->toDateString(),
                    ])
                    ->where('sts_kry', 'AKTIF')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_awal_ktr, '%d %M %Y') as awal_kontrak,
                        DATE_FORMAT(tgl_akhir_ktr, '%d %M %Y') as akhir_kontrak,
                        DATEDIFF(tgl_akhir_ktr, CURDATE()) as sisa_hari")
                    ->orderBy('tgl_akhir_ktr', 'ASC')
                    ->get()
                    ->toArray(),
            ],

            'kontrak_sudah_berakhir' => [
                'intent'  => 'kontrak_sudah_berakhir',
                'keterangan' => 'Karyawan aktif yang tanggal akhir kontraknya sudah lewat',
                'data'    => DataKaryawan::query()
                    ->whereNotNull('tgl_akhir_ktr')
                    ->where('tgl_akhir_ktr', '<', now()->toDateString())
                    ->where('sts_kry', 'AKTIF')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_akhir_ktr, '%d %M %Y') as akhir_kontrak,
                        DATEDIFF(CURDATE(), tgl_akhir_ktr) as hari_lewat")
                    ->orderBy('tgl_akhir_ktr', 'ASC')
                    ->get()
                    ->toArray(),
            ],

            // -----------------------------------------------------------------
            // USIA
            // -----------------------------------------------------------------

            'usia_tertua' => [
                'intent' => 'usia_tertua',
                'data'   => DataKaryawan::query()
                    ->whereNotNull('tgl_lahir')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_lahir, '%d %M %Y') as tgl_lahir_fmt,
                        TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) as usia")
                    ->orderBy('tgl_lahir', 'ASC')
                    ->limit(10)
                    ->get()
                    ->toArray(),
            ],

            'usia_termuda' => [
                'intent' => 'usia_termuda',
                'data'   => DataKaryawan::query()
                    ->whereNotNull('tgl_lahir')
                    ->selectRaw("nama, nrk, jabatan,
                        DATE_FORMAT(tgl_lahir, '%d %M %Y') as tgl_lahir_fmt,
                        TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) as usia")
                    ->orderBy('tgl_lahir', 'DESC')
                    ->limit(10)
                    ->get()
                    ->toArray(),
            ],

            'rata_rata_usia' => [
                'intent'      => 'rata_rata_usia',
                'rata_rata'   => round(DataKaryawan::whereNotNull('tgl_lahir')
                    ->selectRaw('AVG(TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE())) as avg_usia')
                    ->value('avg_usia'), 1),
                'usia_min'    => DataKaryawan::whereNotNull('tgl_lahir')
                    ->selectRaw('MIN(TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE())) as min_usia')
                    ->value('min_usia'),
                'usia_max'    => DataKaryawan::whereNotNull('tgl_lahir')
                    ->selectRaw('MAX(TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE())) as max_usia')
                    ->value('max_usia'),
                'keterangan'  => 'Dihitung dari seluruh karyawan yang memiliki data tanggal lahir',
            ],

            // -----------------------------------------------------------------
            // PENCARIAN NAMA / NRK / NIK
            // -----------------------------------------------------------------

            'cari_karyawan' => (function () use ($pertanyaan) {
                // Ekstrak kata kunci — buang kata umum
                $stopwords = ['siapa', 'cari', 'tampilkan', 'data', 'karyawan', 'yang', 'bernama', 'dengan', 'nrk', 'nik'];
                $kata      = strtolower($pertanyaan);
                foreach ($stopwords as $sw) {
                    $kata = str_replace($sw, '', $kata);
                }
                $keyword = trim($kata);

                return [
                    'intent'   => 'cari_karyawan',
                    'keyword'  => $keyword,
                    'hasil'    => DataKaryawan::query()
                        ->where(function ($q) use ($keyword) {
                            $q->where('nama', 'like', "%{$keyword}%")
                              ->orWhere('nrk',  'like', "%{$keyword}%")
                              ->orWhere('nik',  'like', "%{$keyword}%");
                        })
                        ->selectRaw("nama, nrk, nik, jabatan, sts_kry,
                            DATE_FORMAT(tgl_masuk, '%d %M %Y') as tgl_masuk_fmt,
                            TIMESTAMPDIFF(YEAR, tgl_masuk, CURDATE()) as tahun_kerja,
                            kota_dom")
                        ->limit(10)
                        ->get()
                        ->toArray(),
                ];
            })(),

            // -----------------------------------------------------------------
            // DEFAULT
            // -----------------------------------------------------------------

            default => [
                'intent'     => 'tidak_dikenal',
                'total'      => DataKaryawan::count(),
                'aktif'      => DataKaryawan::where('sts_kry', 'AKTIF')->count(),
                'keterangan' => 'Pertanyaan tidak dikenali, menampilkan ringkasan umum',
            ],
        };
    }

    // =========================================================================
    // BUAT JAWABAN NATURAL
    // =========================================================================

    private function buatJawaban(string $pertanyaan, array $data): string
    {
        $dataJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $prompt = <<<PROMPT
Kamu adalah asisten HRD bernama SISKA yang membantu menjawab pertanyaan tentang data karyawan.
Gunakan Bahasa Indonesia yang natural, hangat, dan profesional.
Jawab berdasarkan data yang diberikan saja — jangan mengarang atau menambah informasi.
Jika data kosong atau tidak ada, sampaikan dengan sopan.
Jika ada daftar karyawan, tampilkan dengan format yang rapi dan mudah dibaca.
Tambahkan insight singkat jika relevan (misal: "Artinya 1 dari 4 karyawan akan berulang tahun bulan ini").

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
                    'options' => [
                        'temperature' => 0.3,
                        'num_predict' => 600,
                    ],
                ]);

            if ($response->failed()) {
                Log::error('Ollama HTTP error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return 'Maaf, server AI mengembalikan error (HTTP ' . $response->status() . '). Coba lagi beberapa saat.';
            }

            return $response->json('response') ?? 'Maaf, tidak ada respons dari AI.';

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Ollama tidak bisa dihubungi', ['error' => $e->getMessage()]);
            return 'Maaf, tidak bisa terhubung ke server AI. Pastikan Ollama sedang berjalan di server.';

        } catch (\Exception $e) {
            Log::error('Ollama unexpected error', ['error' => $e->getMessage()]);
            return 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }
}