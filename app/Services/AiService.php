<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Data\DataKaryawan;
use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\WilayahKerja;
use App\Models\DataMaster\Perusahaan;
use App\Models\DataMaster\KontrakKerja;

class AiService
{
    private string $model   = 'qwen2.5:7b';
    private string $baseUrl = 'http://localhost:11434/api/generate';

    public function tanya(string $pertanyaan): string
    {
        // 1. Tentukan intent dari pertanyaan
        $intent = $this->parseIntent($pertanyaan);

        // 2. Ambil data dari DB berdasarkan intent
        $data = $this->ambilData($intent, $pertanyaan);

        // 3. Ubah data jadi jawaban natural
        return $this->buatJawaban($pertanyaan, $data);
    }

    // ─── Intent Parser ───────────────────────────────────────────────────────

    private function parseIntent(string $pertanyaan): string
    {
        $prompt = <<<PROMPT
Kamu adalah sistem klasifikasi intent untuk CRM karyawan.
Dari pertanyaan berikut, pilih SATU intent yang paling sesuai.

Daftar intent:
- total_karyawan         : menanyakan jumlah total karyawan
- karyawan_per_departemen: menanyakan karyawan berdasarkan departemen
- karyawan_per_wilayah   : menanyakan karyawan berdasarkan wilayah kerja
- karyawan_per_perusahaan: menanyakan karyawan berdasarkan perusahaan/cabang
- karyawan_per_kontrak   : menanyakan karyawan berdasarkan jenis kontrak
- cari_karyawan          : mencari karyawan tertentu berdasarkan nama/NRK/NIK
- karyawan_aktif         : menanyakan jumlah atau daftar karyawan aktif saja
- karyawan_gender        : menanyakan berdasarkan jenis kelamin

Pertanyaan: "{$pertanyaan}"

Jawab HANYA dengan satu kata intent di atas, tanpa penjelasan lain.
PROMPT;

        $response = $this->callOllama($prompt);
        $intent   = strtolower(trim($response));

        // Fallback jika jawaban tidak dikenal
        $validIntents = [
            'total_karyawan', 'karyawan_per_departemen', 'karyawan_per_wilayah',
            'karyawan_per_perusahaan', 'karyawan_per_kontrak', 'cari_karyawan',
            'karyawan_aktif', 'karyawan_gender',
        ];

        return in_array($intent, $validIntents) ? $intent : 'total_karyawan';
    }

    // ─── Data Fetcher ─────────────────────────────────────────────────────────

    private function ambilData(string $intent, string $pertanyaan): array
    {
        return match ($intent) {

            'total_karyawan' => [
                'intent' => 'total_karyawan',
                'total'  => DataKaryawan::count(),
                'aktif'  => DataKaryawan::where('sts_kry', 'AKTIF')->count(),
            ],

            'karyawan_aktif' => [
                'intent' => 'karyawan_aktif',
                'total'  => DataKaryawan::where('sts_kry', 'AKTIF')->count(),
                'list'   => DataKaryawan::where('sts_kry', 'AKTIF')
                    ->select('nama', 'nrk', 'jabatan')
                    ->limit(10)
                    ->get()
                    ->toArray(),
            ],

            'karyawan_per_departemen' => [
                'intent' => 'karyawan_per_departemen',
                'data'   => DataKaryawan::query()
                    ->join('103_dm_departemen as dep', 'dep.id', '=', '201_dm_data_karyawan.departemen')
                    ->selectRaw('dep.nama_dep, dep.singkatan_dep, COUNT(*) as total')
                    ->groupBy('dep.id', 'dep.nama_dep', 'dep.singkatan_dep')
                    ->orderByDesc('total')
                    ->get()
                    ->toArray(),
            ],

            'karyawan_per_wilayah' => [
                'intent' => 'karyawan_per_wilayah',
                'data'   => DataKaryawan::query()
                    ->join('105_dm_wilayah_kerja as wil', 'wil.id', '=', '201_dm_data_karyawan.wilker')
                    ->selectRaw('wil.nama_wil, COUNT(*) as total')
                    ->groupBy('wil.id', 'wil.nama_wil')
                    ->orderByDesc('total')
                    ->get()
                    ->toArray(),
            ],

            'karyawan_per_perusahaan' => [
                'intent' => 'karyawan_per_perusahaan',
                'data'   => DataKaryawan::query()
                    ->join('101_dm_perusahaan as prs', 'prs.id', '=', '201_dm_data_karyawan.perusahaan')
                    ->selectRaw('prs.nama_prs1, COUNT(*) as total')
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

            'karyawan_gender' => [
                'intent' => 'karyawan_gender',
                'laki'   => DataKaryawan::where('sex', 'L')->count(),
                'wanita' => DataKaryawan::where('sex', 'P')->count(),
            ],

            'cari_karyawan' => [
                'intent'  => 'cari_karyawan',
                'keyword' => $pertanyaan,
                'hasil'   => DataKaryawan::search($pertanyaan)
                    ->with(['departemenRelation', 'wilayahKerja'])
                    ->limit(5)
                    ->get()
                    ->map(fn($k) => [
                        'nama'       => $k->nama,
                        'nrk'        => $k->nrk,
                        'jabatan'    => $k->jabatan,
                        'departemen' => $k->departemenRelation?->nama_dep,
                        'wilayah'    => $k->wilayahKerja?->nama_wil,
                        'status'     => $k->sts_kry,
                    ])
                    ->toArray(),
            ],

            default => ['intent' => 'tidak_dikenal'],
        };
    }

    // ─── Jawaban Natural ──────────────────────────────────────────────────────

    private function buatJawaban(string $pertanyaan, array $data): string
    {
        $dataJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $prompt = <<<PROMPT
Kamu adalah asisten CRM yang membantu menjawab pertanyaan tentang data karyawan.
Jawab dengan Bahasa Indonesia yang natural, singkat, dan informatif.
Gunakan data yang diberikan, jangan mengarang.
Jika data kosong, katakan datanya belum tersedia.

Pertanyaan: "{$pertanyaan}"

Data dari database:
{$dataJson}

Berikan jawaban yang ringkas dan mudah dibaca. Jika ada angka atau list, tampilkan dengan rapi.
PROMPT;

        return $this->callOllama($prompt);
    }

    // ─── Ollama HTTP Call ─────────────────────────────────────────────────────

    private function callOllama(string $prompt): string
    {
        try {
            $response = Http::timeout(60)->post($this->baseUrl, [
                'model'  => $this->model,
                'prompt' => $prompt,
                'stream' => false,
                'options' => [
                    'temperature' => 0.3, // rendah = jawaban lebih konsisten
                    'num_predict' => 500,
                ],
            ]);

            return $response->json('response') ?? 'Maaf, tidak ada respons dari AI.';

        } catch (\Exception $e) {
            return 'Maaf, AI sedang tidak dapat diakses. Silakan coba lagi.';
        }
    }
}