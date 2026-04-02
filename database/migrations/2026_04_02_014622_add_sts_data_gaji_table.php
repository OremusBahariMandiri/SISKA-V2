<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('205_dm_data_gaji', function (Blueprint $table) {
            // Tambah field status data gaji
            $table->string('sts_data_gaji')
                ->after('ttl_terima_gaji');

            $table->date('tgl_na_gaji')
                ->nullable()
                ->after('sts_data_gaji');

            // Tambah field keterangan non-aktif
            $table->text('ket_na_gaji')
                ->nullable()
                ->after('tgl_na_gaji');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('205_dm_data_gaji', function (Blueprint $table) {
            $table->dropColumn(['sts_data_gaji', 'tgl_na_gaji', 'ket_na_gaji']);
        });
    }
};