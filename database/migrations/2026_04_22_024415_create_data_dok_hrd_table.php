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
        Schema::create('206_dm_data_dok_hrd', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode')->nullable();
            $table->unsignedBigInteger('id_dokumen_hrd'); // relasi dokumen hrd ambil kode, kategori jenis
            $table->string('ket_dok_hrd')->nullable(); // keterangan dokumen
            $table->string('no_dok_hrd')->nullable(); // no dokumen
            $table->string('tgl_ttd')->nullable(); // tanggal terbit dokumen
            $table->string('jns_msb_dok')->nullable(); // jenis masa berlaku ( Tetap / Perpanjangan)
            $table->string('tgl_akr_dok')->nullable(); // Tanggal Akhir Dokumen
            $table->string('msb_dok')->nullable(); // Masa berlaku Dokumen (Selisih ttd - akr dok)
            $table->string('tgl_prt_dok')->nullable(); // Tgl Peringatan Dok
            $table->string('durasi_pgt')->nullable(); // Durasi Peringatan (Tgl Peringatan - tgl sistem)
            $table->string('file_dok')->nullable(); // file dokumen
            $table->string('sts_dok')->nullable(); // Status dokumen (Aktif / Nonaktif)
            $table->string('tgl_dok_na')->nullable(); // tgl dokumen nonaktif
            $table->string('ket_dok_na')->nullable(); // keterangan dokumen nonaktif
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('id_dokumen_hrd')
                ->references('id')
                ->on('106_dm_dok_hrd')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id_kode')
                ->on('001_dm_users')
                ->onDelete('set null');

            $table->foreign('updated_by')
                ->references('id_kode')
                ->on('001_dm_users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('206_dm_data_dok_hrd');
    }
};
