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
        Schema::table('102_dm_wilker', function (Blueprint $table) {
            $table->string('id_kode')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('102_dm_wilker', function (Blueprint $table) {
            $table->dropColumn('id_kode');
        });
    }
};
