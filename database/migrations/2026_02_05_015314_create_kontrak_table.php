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
        Schema::create('104_dm_kontrak', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode')->unique();
            $table->string('kode_ktr')->unique();
            $table->string('nama_ktr');
            $table->string('singkatan_ktr')->nullable();

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
        Schema::dropIfExists('104_dm_kontrak');
    }
};
