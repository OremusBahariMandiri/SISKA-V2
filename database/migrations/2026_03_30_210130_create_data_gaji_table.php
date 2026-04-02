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
        Schema::create('205_dm_data_gaji', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_karyawan')->nullable();
            $table->string('id_kode')->unique();
            // Pendapatan tetap
            $table->string('id_gaji')->unique();
            $table->string('tunjab')->nullable();
            $table->string('tunkom')->nullable();
            $table->string('fot')->nullable();
            $table->string('tunmal')->nullable();
            $table->string('ttl_pendapatan_ttp')->nullable();

            // Pendapatan Tidak tetap
            $table->string('id_lembur')->unique();
            $table->string('lbr_harian')->nullable();
            $table->string('lbr_perjam')->nullable();

            $table->string('id_tukin')->unique();
            $table->string('tukin')->nullable();

            $table->string('id_insentif')->unique();
            $table->string('insentif')->nullable();

            $table->string('id_bonus')->unique();
            $table->string('bonus')->nullable();

            $table->string('id_thr')->unique();
            $table->string('thr')->nullable();
            $table->string('ttl_pendapatan_tdk_ttp')->nullable();
            $table->string('ttl_pendapatan')->nullable();

            // Potongan
            $table->string('id_bpjs_tkj')->unique();
            $table->string('bpjs_tkj')->nullable();

            $table->string('id_bpjs_kes')->unique();
            $table->string('bpjs_kes')->nullable();

            $table->string('id_kop')->unique();
            $table->string('iuran_koperasi')->nullable();

            $table->string('id_tps')->unique();
            $table->string('tps_kry')->nullable();

            $table->string('id_pjk_pph')->unique();
            $table->string('pjk_pkp')->nullable();
            $table->string('pjk_pph')->nullable();

            $table->string('id_ptg_thr')->unique();
            $table->string('ptg_thr')->nullable();

            $table->string('id_pjm_kop')->unique();
            $table->string('pjm_kop')->nullable();

            $table->string('id_dda_sanksi')->unique();
            $table->string('dda_sanksi')->nullable();
            $table->string('ttl_potongan')->nullable();

            // Beban Tanggungan Perusahaan
            $table->string('bpjs_tkj_prs')->nullable();
            $table->string('bpjs_kes_prs')->nullable();
            $table->string('tps_prs')->nullable();


            $table->string('id_askes_prs')->unique();
            $table->string('askes_prs')->nullable();
            $table->string('ttl_terima_gaji')->nullable();

            // INFORMATIONS
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('id_karyawan')->references('id')->on('201_dm_data_karyawan')->onDelete('set null');
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
        Schema::dropIfExists('205_dm_data_gaji');
    }
};
