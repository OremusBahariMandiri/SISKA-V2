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
        Schema::create('101_dm_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_prs')->unique();
            $table->string('nama_prs1');
            $table->string('nama_prs2')->nullable();
            $table->text('alamat_prs');
            $table->string('rt_rw_prs')->nullable();
            $table->string('kel_prs')->nullable();
            $table->string('kec_prs')->nullable();
            $table->string('kota_prs');
            $table->string('prov_prs');
            $table->string('kd_pos_prs', 5)->nullable();
            $table->string('tlp1', 20)->nullable();
            $table->string('tlp2', 20)->nullable();
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('web')->nullable();
            $table->date('tgl_pendirian')->nullable();
            $table->string('bidang_ush')->nullable();
            $table->string('ijin_ush')->nullable();
            $table->string('golongan_ush')->nullable();
            $table->string('dirut')->nullable();
            $table->string('direktur')->nullable();
            $table->string('komisaris_utm')->nullable();
            $table->string('komisaris1')->nullable();
            $table->string('komisaris2')->nullable();
            $table->string('komisaris3')->nullable();
            
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('101_dm_perusahaan');
    }
};
