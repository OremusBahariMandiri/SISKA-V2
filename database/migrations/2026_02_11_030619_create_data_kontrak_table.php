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
        Schema::create('202_dm_data_kontrak', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // Ensure InnoDB for foreign keys
            $table->id();
            $table->string('id_kode')->nullable();

            // Foreign key columns (NO unique constraint)
            $table->unsignedBigInteger('id_data_kry');

            // Data Kontrak (Editable)
            $table->string('id_data_ktr')->unique()->nullable();
            $table->string('no_srt_ktr')->nullable();
            $table->string('tgl_srt_ktr')->nullable();
            $table->unsignedBigInteger('id_ktr'); // relation master ktr
            $table->string('tgl_awl_ktr')->nullable();
            $table->string('ktg_ktk')->nullable();
            $table->string('tgl_akhir_ktr')->nullable();
            $table->string('durasi_ktr')->nullable();
            $table->string('tgl_pgt_ktr')->nullable();
            $table->string('durasi_pgt')->nullable();
            $table->unsignedBigInteger('id_prsh')->nullable(); // relation master prsh
            $table->string('file_doc_ktr')->nullable();
            $table->string('sts_srt_ktr')->nullable();
            $table->string('tgl_sr_na')->nullable();
            $table->string('ket_sr_na')->nullable();

            // Pendidikan (Editable)
            $table->string('id_pendidikan')->unique()->nullable();
            $table->string('jenjang_skl')->nullable();
            $table->string('institusi_skl')->nullable();
            $table->string('skt_inst_skl')->nullable();
            $table->string('kota_skl')->nullable();
            $table->string('fakultas_skl')->nullable();
            $table->string('jurusan_skl')->nullable();
            $table->string('gelar_skl')->nullable();
            $table->date('tgl_lulus_skl')->nullable();

            // Karir (Editable)
            $table->string('id_karir')->unique()->nullable();
            $table->unsignedBigInteger('id_departemen')->nullable(); // relation master departemen
            $table->unsignedBigInteger('id_wilker')->nullable(); // relation master wilker
            $table->text('tugas')->nullable();

            // User tracking
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        // Add all foreign keys AFTER table creation
        Schema::table('202_dm_data_kontrak', function (Blueprint $table) {
            $table->foreign('id_data_kry')
                ->references('id')
                ->on('201_dm_data_karyawan')
                ->onDelete('cascade');

            $table->foreign('id_ktr')
                ->references('id')
                ->on('104_dm_kontrak')
                ->onDelete('cascade');

            $table->foreign('id_prsh')
                ->references('id')
                ->on('101_dm_perusahaan')
                ->onDelete('set null');

            $table->foreign('id_departemen')
                ->references('id')
                ->on('103_dm_departemen')
                ->onDelete('set null');

            $table->foreign('id_wilker')
                ->references('id')
                ->on('102_dm_wilker')
                ->onDelete('set null');

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
            $table->index('id_ktr');
            $table->index('id_prsh');
            $table->index('id_departemen');
            $table->index('id_wilker');
            $table->index('created_by');
            $table->index('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('202_dm_data_kontrak');
    }
};