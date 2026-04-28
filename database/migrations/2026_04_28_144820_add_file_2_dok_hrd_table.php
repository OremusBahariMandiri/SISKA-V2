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
        Schema::table('206_dm_data_dok_hrd', function (Blueprint $table) {
            // Tambah field status data gaji
            $table->string('file_dok_2')
                ->after('file_dok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('206_dm_data_dok_hrd', function (Blueprint $table) {
            $table->dropColumn('file_dok_2');
        });
    }
};
