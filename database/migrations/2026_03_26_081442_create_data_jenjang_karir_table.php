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
        Schema::create('204_dm_data_jenjang_karir', function (Blueprint $table) {
            $table->id();
            $table->string('id_jenjang_karir')->unique();
            $table->unsignedBigInteger('id_karyawan')->nullable();
            $table->unsignedBigInteger('id_dokumen_karyawan')->nullable();
            $table->string('no_jk')->nullable();
            $table->date('tgl_ttd')->nullable();
            $table->unsignedBigInteger('id_departemen')->nullable();
            $table->unsignedBigInteger('id_wilayah_kerja')->nullable();
            $table->text('tugas')->nullable();
            $table->string('file_dokumen')->nullable();
            $table->text('id_kontrak_kerja')->nullable();
            $table->timestamps();

            $table->foreign('id_karyawan')->references('id')->on('201_dm_data_karyawan')->onDelete('set null');
            $table->foreign('id_dokumen_karyawan')->references('id')->on('105_dm_dok_kry')->onDelete('set null');
            $table->foreign('id_departemen')->references('id')->on('103_dm_departemen')->onDelete('set null');
            $table->foreign('id_wilayah_kerja')->references('id')->on('102_dm_wilker')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('204_dm_data_jenjang_karir');
    }
};
