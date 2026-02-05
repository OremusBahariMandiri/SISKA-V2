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
        Schema::create('103_dm_departemen', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dep')->unique();
            $table->string('nama_dep');
            $table->string('singkatan_dep')->nullable();
            $table->string('nama_jbt');
            $table->string('singkatan_jbt')->nullable();
            
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
        Schema::dropIfExists('103_dm_departemen');
    }
};
