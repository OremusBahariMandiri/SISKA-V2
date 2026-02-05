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
        Schema::create('001_dm_users', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode')->unique();
            $table->string('nik_kry');
            $table->string('nama_kry');
            $table->string('departemen_kry');
            $table->string('jabatan_kry');
            $table->string('wilker_kry');
            $table->string('password_kry');
            $table->boolean('is_admin')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('001_dm_users');
    }
};
