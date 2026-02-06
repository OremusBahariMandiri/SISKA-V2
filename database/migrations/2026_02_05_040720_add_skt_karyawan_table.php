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
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('skt_inst_skl')->nullable()->after('institusi_skl');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('skt_sts_ktr')->nullable()->after('sts_ktr');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('skt_prs')->nullable()->after('perusahaan');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('skt_dep')->nullable()->after('departemen');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('skt_jbt')->nullable()->after('jabatan');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('skt_wil_krj')->nullable()->after('unit_krj');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('skt_sts_kry')->nullable()->after('sts_kry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('skt_inst_skl');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('skt_sts_ktr');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('skt_prs');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('skt_dep');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('skt_jbt');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('skt_wil_krj');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('skt_sts_kry');
        });
    }
};
