<?php
// File: app/Observers/DataJenjangKarirObserver.php

namespace App\Observers;

use App\Models\Data\DataJenjangKarir;
use App\Models\Data\DataKaryawan;
use Illuminate\Support\Facades\Log;

class DataJenjangKarirObserver
{
    /**
     * Handle the DataJenjangKarir "created" event.
     * Sinkronisasi ke DataKaryawan setelah jenjang karir baru dibuat
     */
    public function created(DataJenjangKarir $dataJenjangKarir)
    {
        $this->syncToDataKaryawan($dataJenjangKarir);
    }

    /**
     * Handle the DataJenjangKarir "updated" event.
     * Sinkronisasi ke DataKaryawan setelah jenjang karir diupdate
     */
    public function updated(DataJenjangKarir $dataJenjangKarir)
    {
        $this->syncToDataKaryawan($dataJenjangKarir);
    }

    /**
     * Sinkronisasi data jenjang karir ke tabel DataKaryawan
     * Hanya update jika jenjang karir ini adalah yang TERBARU
     */
    private function syncToDataKaryawan(DataJenjangKarir $dataJenjangKarir)
    {
        try {
            // Cari karyawan terkait
            $karyawan = DataKaryawan::find($dataJenjangKarir->id_karyawan);

            if (!$karyawan) {
                Log::warning("Karyawan dengan ID {$dataJenjangKarir->id_karyawan} tidak ditemukan");
                return;
            }

            // Cek apakah ini jenjang karir TERBARU untuk karyawan ini
            // Berdasarkan tanggal TTD terbaru dan ID terbesar
            $latestCareer = DataJenjangKarir::where('id_karyawan', $dataJenjangKarir->id_karyawan)
                ->orderBy('tgl_ttd', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            // Hanya update jika jenjang karir ini adalah yang terbaru
            if ($latestCareer && $latestCareer->id === $dataJenjangKarir->id) {

                // Update data karir di tabel DataKaryawan
                $karyawan->update([
                    // Jenjang Karir - field departemen di DataKaryawan menyimpan ID departemen
                    'departemen' => $dataJenjangKarir->id_departemen,
                    'skt_dep' => optional($dataJenjangKarir->departemen)->singkatan_dep,

                    // Jabatan - field jabatan di DataKaryawan menyimpan nama departemen (untuk backward compatibility)
                    'jabatan' => optional($dataJenjangKarir->departemen)->nama_dep,
                    'skt_jbt' => optional($dataJenjangKarir->departemen)->singkatan_jbt,

                    // Wilayah Kerja
                    'wilker' => optional($dataJenjangKarir->wilayahKerja)->wilayah_krj,
                    'unit_krj' => $dataJenjangKarir->id_wilayah_kerja,
                    'skt_wil_krj' => optional($dataJenjangKarir->wilayahKerja)->singkatan_wk,

                    // Tugas
                    'tugas' => $dataJenjangKarir->tugas,

                    // Metadata
                    'updated_by' => $dataJenjangKarir->updated_by ?? $dataJenjangKarir->created_by,
                ]);

                Log::info("DataKaryawan #{$karyawan->id} berhasil di-sync dengan DataJenjangKarir #{$dataJenjangKarir->id}");
            } else {
                Log::info("DataJenjangKarir #{$dataJenjangKarir->id} tidak di-sync karena bukan jenjang karir terbaru");
            }

        } catch (\Exception $e) {
            Log::error("Error sinkronisasi DataJenjangKarir #{$dataJenjangKarir->id} ke DataKaryawan: " . $e->getMessage());
        }
    }

    /**
     * Handle the DataJenjangKarir "deleted" event.
     * Jika jenjang karir terbaru dihapus, cari jenjang karir berikutnya untuk di-sync
     */
    public function deleted(DataJenjangKarir $dataJenjangKarir)
    {
        try {
            // Cari jenjang karir berikutnya (berdasarkan tanggal TTD)
            $nextCareer = DataJenjangKarir::where('id_karyawan', $dataJenjangKarir->id_karyawan)
                ->where('id', '!=', $dataJenjangKarir->id)
                ->orderBy('tgl_ttd', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            if ($nextCareer) {
                $this->syncToDataKaryawan($nextCareer);
                Log::info("DataKaryawan di-sync dengan jenjang karir berikutnya: #{$nextCareer->id}");
            } else {
                // Jika tidak ada jenjang karir lagi, kosongkan data karir di DataKaryawan
                $karyawan = DataKaryawan::find($dataJenjangKarir->id_karyawan);
                if ($karyawan) {
                    $karyawan->update([
                        'departemen' => null,
                        'skt_dep' => null,
                        'jabatan' => null,
                        'skt_jbt' => null,
                        'wilker' => null,
                        'unit_krj' => null,
                        'skt_wil_krj' => null,
                        'tugas' => null,
                    ]);
                    Log::info("Data karir di DataKaryawan #{$karyawan->id} dikosongkan (tidak ada jenjang karir)");
                }
            }
        } catch (\Exception $e) {
            Log::error("Error handling deleted DataJenjangKarir #{$dataJenjangKarir->id}: " . $e->getMessage());
        }
    }
}