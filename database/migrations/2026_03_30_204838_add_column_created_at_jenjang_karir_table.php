<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('204_dm_data_jenjang_karir', function (Blueprint $table) {
            $table->string('created_by')->nullable()->after('id_kontrak_kerja');
            $table->string('updated_by')->nullable()->after('created_by');

            // Foreign key
            $table->foreign('created_by', 'fk_created_by_jenjang_karir')
                ->references('id_kode')
                ->on('001_dm_users')
                ->onDelete('set null');

            $table->foreign('updated_by', 'fk_updated_by_jenjang_karir')
                ->references('id_kode')
                ->on('001_dm_users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('204_dm_data_jenjang_karir', function (Blueprint $table) {
            // Drop foreign key dulu
            $table->dropForeign('fk_created_by_jenjang_karir');
            $table->dropForeign('fk_updated_by_jenjang_karir');

            // Baru drop kolom
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
};