<?php
// File: app/Console/Commands/SyncKontrakToKaryawan.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Data\DataKaryawan;
use App\Models\Data\DataKontrak;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncKontrakToKaryawan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kontrak:sync-to-karyawan
                            {--dry-run : Jalankan tanpa menyimpan perubahan}
                            {--employee-id= : Sync hanya untuk karyawan tertentu}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi data kontrak aktif terbaru dari DataKontrak ke DataKaryawan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $employeeId = $this->option('employee-id');

        $this->info('========================================');
        $this->info('Sinkronisasi Data Kontrak ke Karyawan');
        $this->info('========================================');

        if ($isDryRun) {
            $this->warn('MODE: DRY RUN (tidak ada data yang akan diubah)');
        }

        // Query untuk mendapatkan kontrak aktif terbaru per karyawan
        $query = DataKontrak::select('202_dm_data_kontrak.*')
            ->whereIn('id', function($subquery) {
                $subquery->select(DB::raw('MAX(id)'))
                    ->from('202_dm_data_kontrak')
                    ->where('sts_srt_ktr', 'AKTIF')
                    ->groupBy('id_data_kry');
            })
            ->with(['kontrakKerja', 'perusahaan', 'departemen', 'wilayahKerja']);

        // Filter by specific employee if provided
        if ($employeeId) {
            $query->where('id_data_kry', $employeeId);
            $this->info("Filter: Hanya karyawan ID #{$employeeId}");
        }

        $activeContracts = $query->get();

        $this->info("Ditemukan {$activeContracts->count()} kontrak aktif terbaru untuk di-sync");
        $this->newLine();

        if ($activeContracts->isEmpty()) {
            $this->warn('Tidak ada data yang perlu di-sync.');
            return 0;
        }

        // Konfirmasi jika bukan dry-run
        if (!$isDryRun && !$this->confirm('Apakah Anda yakin ingin melanjutkan sinkronisasi?', true)) {
            $this->info('Sinkronisasi dibatalkan.');
            return 0;
        }

        $successCount = 0;
        $failCount = 0;
        $skipCount = 0;

        // Progress bar
        $bar = $this->output->createProgressBar($activeContracts->count());
        $bar->start();

        foreach ($activeContracts as $kontrak) {
            try {
                // Cari karyawan
                $karyawan = DataKaryawan::find($kontrak->id_data_kry);

                if (!$karyawan) {
                    $this->newLine();
                    $this->warn("⚠ Karyawan ID #{$kontrak->id_data_kry} tidak ditemukan. Skip.");
                    $skipCount++;
                    $bar->advance();
                    continue;
                }

                // Data yang akan di-update
                $updateData = [
                    // Kontrak Kerja
                    'sts_ktr' => $kontrak->id_ktr,
                    'tgl_awal_ktr' => $kontrak->tgl_awl_ktr,
                    'tgl_akhir_ktr' => $kontrak->tgl_akhir_ktr,
                    'durasi_ktr' => $kontrak->durasi_ktr,
                    'perusahaan' => $kontrak->id_prsh,
                ];

                // Update Pendidikan jika ada di kontrak dan belum ada di karyawan
                if ($kontrak->jenjang_skl && !$karyawan->jenjang_skl) {
                    $updateData['jenjang_skl'] = $kontrak->jenjang_skl;
                }
                if ($kontrak->institusi_skl && !$karyawan->institusi_skl) {
                    $updateData['institusi_skl'] = $kontrak->institusi_skl;
                }
                if ($kontrak->skt_inst_skl && !$karyawan->skt_inst_skl) {
                    $updateData['skt_inst_skl'] = $kontrak->skt_inst_skl;
                }
                if ($kontrak->kota_skl && !$karyawan->kota_skl) {
                    $updateData['kota_skl'] = $kontrak->kota_skl;
                }
                if ($kontrak->fakultas_skl && !$karyawan->fakultas_skl) {
                    $updateData['fakultas_skl'] = $kontrak->fakultas_skl;
                }
                if ($kontrak->jurusan_skl && !$karyawan->jurusan_skl) {
                    $updateData['jurusan_skl'] = $kontrak->jurusan_skl;
                }
                if ($kontrak->gelar_skl && !$karyawan->gelar_skl) {
                    $updateData['gelar_skl'] = $kontrak->gelar_skl;
                }
                if ($kontrak->tgl_lulus_skl && !$karyawan->tgl_lulus_skl) {
                    $updateData['tgl_lulus_skl'] = $kontrak->tgl_lulus_skl;
                }

                // Update Karir jika ada di kontrak dan belum ada di karyawan
                if ($kontrak->id_departemen && !$karyawan->departemen) {
                    $updateData['departemen'] = $kontrak->id_departemen;
                }
                if ($kontrak->id_wilker && !$karyawan->unit_krj) {
                    $updateData['unit_krj'] = $kontrak->id_wilker;
                }
                if ($kontrak->tugas && !$karyawan->tugas) {
                    $updateData['tugas'] = $kontrak->tugas;
                }

                // Metadata
                $updateData['updated_by'] = $kontrak->created_by;

                // Tampilkan preview jika dry-run
                if ($isDryRun) {
                    $this->newLine();
                    $this->info("📋 Preview Update - Karyawan: {$karyawan->nama} (NRK: {$karyawan->nrk})");
                    $this->table(
                        ['Field', 'Old Value', 'New Value'],
                        [
                            ['Jenis Kontrak', $karyawan->kontrakRelation->nama_ktr ?? '-', $kontrak->kontrakKerja->nama_ktr ?? '-'],
                            ['Perusahaan', $karyawan->perusahaanRelation->nama_prs1 ?? '-', $kontrak->perusahaan->nama_prs1 ?? '-'],
                            ['Tgl Mulai', $karyawan->tgl_awal_ktr ?? '-', $kontrak->tgl_awl_ktr ?? '-'],
                            ['Tgl Akhir', $karyawan->tgl_akhir_ktr ?? '-', $kontrak->tgl_akhir_ktr ?? '-'],
                            ['Durasi', $karyawan->durasi_ktr ?? '-', $kontrak->durasi_ktr ?? '-'],
                        ]
                    );
                } else {
                    // Update data
                    $karyawan->update($updateData);

                    Log::info("Sync Success: Karyawan #{$karyawan->id} ({$karyawan->nama}) dari Kontrak #{$kontrak->id}");
                }

                $successCount++;

            } catch (\Exception $e) {
                $this->newLine();
                $this->error("❌ Error sync Kontrak #{$kontrak->id}: " . $e->getMessage());
                Log::error("Sync Error: Kontrak #{$kontrak->id} - " . $e->getMessage());
                $failCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('========================================');
        $this->info('SUMMARY');
        $this->info('========================================');
        $this->info("✅ Berhasil: {$successCount}");
        if ($failCount > 0) {
            $this->error("❌ Gagal: {$failCount}");
        }
        if ($skipCount > 0) {
            $this->warn("⚠ Dilewati: {$skipCount}");
        }
        $this->info('========================================');

        if ($isDryRun) {
            $this->newLine();
            $this->warn('Ini adalah DRY RUN. Tidak ada data yang diubah.');
            $this->info('Jalankan tanpa --dry-run untuk menyimpan perubahan:');
            $this->comment('php artisan kontrak:sync-to-karyawan');
        }

        return 0;
    }
}