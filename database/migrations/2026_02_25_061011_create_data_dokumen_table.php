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
        Schema::create('203_dm_data_dokumen', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // Ensure InnoDB for foreign keys
            $table->id();
            $table->string('id_kode')->nullable();
            // Data Diri, Kontrak Kerja, Jenjang Karir, Hubin
            $table->unsignedBigInteger('id_data_kry'); // relasi data karyawan (identitas diri)
            // Data Dokumen
            $table->string('id_dok_kry')->unique()->nullable();
            $table->string('kode_dok_kry')->nullable();
            $table->unsignedBigInteger('id_dokumen');
            $table->string('ket_dok')->nullable();
            $table->string('ctt_dok')->nullable();
            $table->string('no_dok')->nullable();
            $table->string('tgl_ttd')->nullable();
            $table->string('jns_msb_dok')->nullable();
            $table->string('tgl_akr_dok')->nullable();
            $table->string('msb_dok')->nullable();
            $table->string('tgl_pgt_dok')->nullable();
            $table->string('durasi_pgt')->nullable();
            $table->string('file_dok')->nullable();
            $table->string('sts_dok')->nullable();
            $table->string('tgl_dok_na')->nullable();
            $table->string('ket_dok_na')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::table('203_dm_data_dokumen', function (Blueprint $table) {
            $table->foreign('id_data_kry')
                ->references('id')
                ->on('201_dm_data_karyawan')
                ->onDelete('cascade');

            $table->foreign('id_dokumen')
                ->references('id')
                ->on('105_dm_dok_kry')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id_kode')
                ->on('001_dm_users')
                ->onDelete('set null');

            $table->foreign('updated_by')
                ->references('id_kode')
                ->on('001_dm_users')
                ->onDelete('set null');

            // Performance indexes
            $table->index('id_data_kry');
            $table->index('created_by');
            $table->index('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('203_dm_data_dokumen');
    }
};
