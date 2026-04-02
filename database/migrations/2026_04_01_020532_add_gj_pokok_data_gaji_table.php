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
        Schema::table('205_dm_data_gaji', function (Blueprint $table) {
            $table->string('gj_pokok')->nullable()->after('id_gaji');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('205_dm_data_gaji', function (Blueprint $table) {
            $table->dropColumn('gj_pokok');
        });
    }
};
