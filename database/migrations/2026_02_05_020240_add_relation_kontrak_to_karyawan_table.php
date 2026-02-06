<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            // jika sudah ada, drop dulu
            if (Schema::hasColumn('201_dm_data_karyawan', 'sts_ktr')) {
                $table->dropColumn('sts_ktr');
            }
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            // HARUS BIGINT UNSIGNED
            $table->unsignedBigInteger('sts_ktr')->nullable()->after('idktr');
        });

        // bersihkan data tidak valid
        DB::statement('
            UPDATE 201_dm_data_karyawan
            SET sts_ktr = NULL
            WHERE sts_ktr IS NOT NULL
            AND sts_ktr NOT IN (SELECT id FROM 104_dm_kontrak)
        ');

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->foreign('sts_ktr')
                ->references('id')
                ->on('104_dm_kontrak')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropForeign(['sts_ktr']);
            $table->dropColumn('sts_ktr');
        });
    }
};