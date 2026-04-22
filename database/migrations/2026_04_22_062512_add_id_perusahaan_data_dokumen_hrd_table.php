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
        Schema::table('206_dm_data_dok_hrd', function (Blueprint $table) {
            // tambah kolom setelah id_dokumen_hrd (opsional positioning)
            $table->unsignedBigInteger('id_perusahaan')->nullable()->after('id_dokumen_hrd');

            // foreign key
            $table->foreign('id_perusahaan')
                ->references('id')
                ->on('101_dm_perusahaan')
                ->onDelete('cascade'); // atau set null kalau mau lebih aman
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('206_dm_data_dok_hrd', function (Blueprint $table) {
            $table->dropForeign(['id_perusahaan']);
            $table->dropColumn('id_perusahaan');
        });
    }
};
