<?php
// File: app/Console/Commands/SyncJenjangKarirToKaryawan.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Data\DataKaryawan;
use App\Models\Data\DataJenjangKarir;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncJenjangKarirToKaryawan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jenjang-karir:sync-to-karyawan
                            {--dry-run : Jalankan tanpa menyimpan perubahan}
                            {--employee-id= : Sync hanya untuk karyawan tertentu}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi data jenjang karir terbaru dari DataJenjangKarir ke DataKaryawan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $employeeId = $this->option('employee-id');

        $this->info('========================================');
        $this->info('Sinkronisasi Data Jenjang Karir ke Karyawan');
        $this->info('========================================');

        if ($isDryRun) {
            $this->warn('MODE: DRY RUN (tidak ada data yang akan diubah)');
        }

        // Query untuk mendapatkan jenjang karir terbaru per karyawan
        $query = DataJenjangKarir::select('204_dm_data_jenjang_karir.*')
            ->whereIn('id', function($subquery) {
                $subquery->select(DB::raw('MAX(id)'))
                    ->from('204_dm_data_jenjang_karir')
                    ->groupBy('id_karyawan');
            })
            ->with(['departemen', 'wilayahKerja']);

        // Filter by specific employee if provided
        if ($employeeId) {
            $query->where('id_karyawan', $employeeId);
            $this->info("Filter: Hanya karyawan ID #{$employeeId}");
        }

        $latestCareers = $query->get();

        $this->info("Ditemukan {$latestCareers->count()} jenjang karir terbaru untuk di-sync");
        $this->newLine();

        if ($latestCareers->isEmpty()) {
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
        $bar = $this->output->createProgressBar($latestCareers->count());
        $bar->start();

        foreach ($latestCareers as $career) {
            try {
                // Cari karyawan
                $karyawan = DataKaryawan::find($career->id_karyawan);

                if (!$karyawan) {
                    $this->newLine();
                    $this->warn("⚠ Karyawan ID #{$career->id_karyawan} tidak ditemukan. Skip.");
                    $skipCount++;
                    $bar->advance();
                    continue;
                }

                // Data yang akan di-update
                $updateData = [
                    // Jenjang Karir
                    'departemen' => $career->id_departemen,
                    'skt_dep' => optional($career->departemen)->singkatan_dep,
                    'jabatan' => optional($career->departemen)->nama_dep,
                    'skt_jbt' => optional($career->departemen)->singkatan_jbt,
                    'wilker' => optional($career->wilayahKerja)->wilayah_krj,
                    'unit_krj' => $career->id_wilayah_kerja,
                    'skt_wil_krj' => optional($career->wilayahKerja)->singkatan_wk,
                    'tugas' => $career->tugas,

                    // Metadata
                    'updated_by' => $career->created_by,
                ];

                // Tampilkan preview jika dry-run
                if ($isDryRun) {
                    $this->newLine();
                    $this->info("📋 Preview Update - Karyawan: {$karyawan->nama} (NRK: {$karyawan->nrk})");
                    $this->table(
                        ['Field', 'Old Value', 'New Value'],
                        [
                            ['Departemen', $karyawan->departemenRelation->nama_dep ?? '-', $career->departemen->nama_dep ?? '-'],
                            ['Jabatan', $karyawan->jabatan ?? '-', $career->departemen->nama_jbt ?? '-'],
                            ['Wilayah Kerja', $karyawan->wilker ?? '-', $career->wilayahKerja->wilayah_krj ?? '-'],
                            ['Unit Kerja', $karyawan->unitKerjaRelation->area_krj ?? '-', $career->wilayahKerja->area_krj ?? '-'],
                            ['Tgl TTD', '-', $career->tgl_ttd ?? '-'],
                        ]
                    );
                } else {
                    // Update data
                    $karyawan->update($updateData);

                    Log::info("Sync Success: Karyawan #{$karyawan->id} ({$karyawan->nama}) dari JenjangKarir #{$career->id}");
                }

                $successCount++;

            } catch (\Exception $e) {
                $this->newLine();
                $this->error("❌ Error sync JenjangKarir #{$career->id}: " . $e->getMessage());
                Log::error("Sync Error: JenjangKarir #{$career->id} - " . $e->getMessage());
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
            $this->comment('php artisan jenjang-karir:sync-to-karyawan');
        }

        return 0;
    }
}