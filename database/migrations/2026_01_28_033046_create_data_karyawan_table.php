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
        Schema::create('201_dm_data_karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('idkry')->unique();
            $table->date('tgl_masuk')->nullable();
            $table->string('nrk')->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('nama');
            $table->string('tpt_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('sex', ['L', 'P'])->nullable();
            $table->string('agama')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->string('sts_nikah')->nullable();
            $table->string('sts_keluarga')->nullable();
            $table->integer('jml_anak')->nullable();
            $table->string('tlp1', 20)->nullable();
            $table->string('tlp2', 20)->nullable();
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('foto_dokumen')->nullable();

            // Alamat KTP
            $table->text('alamat_ktp')->nullable();
            $table->string('rt_rw_ktp')->nullable();
            $table->string('kel_ktp')->nullable();
            $table->string('kec_ktp')->nullable();
            $table->string('kota_ktp')->nullable();
            $table->string('prov_ktp')->nullable();
            $table->string('kd_pos_ktp', 5)->nullable();

            // Alamat Domisili
            $table->text('alamat_dom')->nullable();
            $table->string('rt_rw_dom')->nullable();
            $table->string('kel_dom')->nullable();
            $table->string('kec_dom')->nullable();
            $table->string('kota_dom')->nullable();
            $table->string('prov_dom')->nullable();
            $table->string('kd_pos_dom', 5)->nullable();

            // Pendidikan
            $table->string('id_pendidikan')->unique()->nullable();
            $table->string('jenjang_skl')->nullable();
            $table->string('institusi_skl')->nullable();
            $table->string('kota_skl')->nullable();
            $table->string('fakultas_skl')->nullable();
            $table->string('jurusan_skl')->nullable();
            $table->string('gelar_skl')->nullable();
            $table->date('tgl_lulus_skl')->nullable();

            // Kontrak
            $table->string('idktr')->unique()->nullable();
            $table->string('sts_ktr')->nullable();
            $table->date('tgl_awal_ktr')->nullable();
            $table->date('tgl_akhir_ktr')->nullable();
            $table->integer('durasi_ktr')->nullable();
            $table->unsignedBigInteger('perusahaan')->nullable();

            // Karir
            $table->string('id_karir')->unique()->nullable();
            $table->unsignedBigInteger('departemen')->nullable();
            $table->string('jabatan')->nullable();
            $table->text('tugas')->nullable();
            $table->string('unit_krj')->nullable();
            $table->unsignedBigInteger('wilker')->nullable();

            // Hubungan Industrial
            $table->string('id_hubin')->unique()->nullable();
            $table->string('sts_kry')->nullable();
            $table->date('tgl_phk')->nullable();
            $table->text('ket_phk')->nullable();

            // INFORMATIONS
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')
                ->references('id_kode')
                ->on('001_dm_users')
                ->onDelete('set null');
            $table->foreign('updated_by')
                ->references('id_kode')
                ->on('001_dm_users')
                ->onDelete('set null');

            // Foreign key constraints
            $table->foreign('perusahaan')->references('id')->on('101_dm_perusahaan')->onDelete('set null');
            $table->foreign('departemen')->references('id')->on('103_dm_departemen')->onDelete('set null');
            $table->foreign('wilker')->references('id')->on('102_dm_wilker')->onDelete('set null');

            // Indexes for better performance
            $table->index(['nama', 'nrk']);
            $table->index('nik');
            $table->index('tgl_masuk');
            $table->index('sts_kry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('201_dm_data_karyawan');
    }
};
