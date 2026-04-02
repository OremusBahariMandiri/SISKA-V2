<?php
// File: app/Observers/DataKontrakObserver.php

namespace App\Observers;

use App\Models\Data\DataKontrak;
use App\Models\Data\DataKaryawan;
use Illuminate\Support\Facades\Log;

class DataKontrakObserver
{
    /**
     * Handle the DataKontrak "created" event.
     * Sinkronisasi ke DataKaryawan setelah kontrak baru dibuat
     */
    public function created(DataKontrak $dataKontrak)
    {
        $this->syncToDataKaryawan($dataKontrak);
    }

    /**
     * Handle the DataKontrak "updated" event.
     * Sinkronisasi ke DataKaryawan setelah kontrak diupdate
     */
    public function updated(DataKontrak $dataKontrak)
    {
        $this->syncToDataKaryawan($dataKontrak);
    }

    /**
     * Sinkronisasi data kontrak ke tabel DataKaryawan
     * Hanya update jika kontrak ini adalah yang AKTIF dan TERBARU
     */
    private function syncToDataKaryawan(DataKontrak $dataKontrak)
    {
        try {
            // Hanya sync jika status kontrak AKTIF
            if ($dataKontrak->sts_srt_ktr !== 'AKTIF') {
                Log::info("DataKontrak #{$dataKontrak->id} tidak di-sync karena status bukan AKTIF");
                return;
            }

            // Cari karyawan terkait
            $karyawan = DataKaryawan::find($dataKontrak->id_data_kry);

            if (!$karyawan) {
                Log::warning("Karyawan dengan ID {$dataKontrak->id_data_kry} tidak ditemukan");
                return;
            }

            // Cek apakah ini kontrak aktif TERBARU untuk karyawan ini
            $latestActiveContract = DataKontrak::where('id_data_kry', $dataKontrak->id_data_kry)
                ->where('sts_srt_ktr', 'AKTIF')
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            // Hanya update jika kontrak ini adalah yang terbaru
            if ($latestActiveContract && $latestActiveContract->id === $dataKontrak->id) {

                // Update data kontrak di tabel DataKaryawan
                $karyawan->update([
                    // Kontrak Kerja
                    'sts_ktr' => $dataKontrak->id_ktr, // ID jenis kontrak
                    'tgl_awal_ktr' => $dataKontrak->tgl_awl_ktr,
                    'tgl_akhir_ktr' => $dataKontrak->tgl_akhir_ktr,
                    'durasi_ktr' => $dataKontrak->durasi_ktr,
                    'perusahaan' => $dataKontrak->id_prsh, // ID perusahaan

                    // Pendidikan (jika ada di kontrak)
                    'jenjang_skl' => $dataKontrak->jenjang_skl ?? $karyawan->jenjang_skl,
                    'institusi_skl' => $dataKontrak->institusi_skl ?? $karyawan->institusi_skl,
                    'skt_inst_skl' => $dataKontrak->skt_inst_skl ?? $karyawan->skt_inst_skl,
                    'kota_skl' => $dataKontrak->kota_skl ?? $karyawan->kota_skl,
                    'fakultas_skl' => $dataKontrak->fakultas_skl ?? $karyawan->fakultas_skl,
                    'jurusan_skl' => $dataKontrak->jurusan_skl ?? $karyawan->jurusan_skl,
                    'gelar_skl' => $dataKontrak->gelar_skl ?? $karyawan->gelar_skl,
                    'tgl_lulus_skl' => $dataKontrak->tgl_lulus_skl ?? $karyawan->tgl_lulus_skl,

                    // Karir (jika ada di kontrak)
                    'departemen' => $dataKontrak->id_departemen ?? $karyawan->departemen,
                    'unit_krj' => $dataKontrak->id_wilker ?? $karyawan->unit_krj,
                    'tugas' => $dataKontrak->tugas ?? $karyawan->tugas,

                    // Metadata
                    'updated_by' => $dataKontrak->updated_by ?? $dataKontrak->created_by,
                ]);

                Log::info("DataKaryawan #{$karyawan->id} berhasil di-sync dengan DataKontrak #{$dataKontrak->id}");
            } else {
                Log::info("DataKontrak #{$dataKontrak->id} tidak di-sync karena bukan kontrak terbaru");
            }

        } catch (\Exception $e) {
            Log::error("Error sinkronisasi DataKontrak #{$dataKontrak->id} ke DataKaryawan: " . $e->getMessage());
        }
    }

    /**
     * Handle the DataKontrak "deleted" event.
     * Jika kontrak yang aktif dihapus, cari kontrak aktif lainnya untuk di-sync
     */
    public function deleted(DataKontrak $dataKontrak)
    {
        try {
            // Jika yang dihapus adalah kontrak AKTIF, cari kontrak aktif lainnya
            if ($dataKontrak->sts_srt_ktr === 'AKTIF') {
                $nextActiveContract = DataKontrak::where('id_data_kry', $dataKontrak->id_data_kry)
                    ->where('sts_srt_ktr', 'AKTIF')
                    ->where('id', '!=', $dataKontrak->id)
                    ->orderBy('created_at', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($nextActiveContract) {
                    $this->syncToDataKaryawan($nextActiveContract);
                    Log::info("DataKaryawan di-sync dengan kontrak aktif berikutnya: #{$nextActiveContract->id}");
                } else {
                    // Jika tidak ada kontrak aktif lagi, kosongkan data kontrak di DataKaryawan
                    $karyawan = DataKaryawan::find($dataKontrak->id_data_kry);
                    if ($karyawan) {
                        $karyawan->update([
                            'tgl_awal_ktr' => null,
                            'tgl_akhir_ktr' => null,
                            'durasi_ktr' => null,
                        ]);
                        Log::info("Data kontrak di DataKaryawan #{$karyawan->id} dikosongkan (tidak ada kontrak aktif)");
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Error handling deleted DataKontrak #{$dataKontrak->id}: " . $e->getMessage());
        }
    }
}