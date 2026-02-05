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
        Schema::create('102_dm_wilker', function (Blueprint $table) {
            $table->id();
            $table->string('kode_wk')->unique();
            $table->string('wilayah_krj');
            $table->string('area_krj');
            $table->string('singkatan_wk')->nullable();
            $table->text('alamat_wk');
            $table->string('rt_rw_wk')->nullable();
            $table->string('kel_wk')->nullable();
            $table->string('kec_wk')->nullable();
            $table->string('kota_wk');
            $table->string('prov_wk');
            $table->string('kd_pos_wk', 5)->nullable();
            $table->string('tlp1', 20)->nullable();
            $table->string('tlp2', 20)->nullable();
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('foto_dokumen')->nullable();

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
        Schema::dropIfExists('102_dm_wilker');
    }
};
