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
            $table->string('jns_kd')->nullable()->after('ket_phk');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('sts_kd')->nullable()->after('jns_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('nik_kd')->nullable()->after('sts_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('nama_kd')->nullable()->after('nik_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('tpt_lhr_kd')->nullable()->after('nama_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('tgl_lhr_kd')->nullable()->after('tpt_lhr_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('sex_kd')->nullable()->after('tgl_lhr_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('agama_kd')->nullable()->after('sex_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('sts_nikah_kd')->nullable()->after('agama_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('telp1_kd')->nullable()->after('sts_nikah_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('telp2_kd')->nullable()->after('telp1_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->text('alamat_kd')->nullable()->after('telp2_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('rt_rw_kd')->nullable()->after('alamat_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('kel_kd')->nullable()->after('rt_rw_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('kec_kd')->nullable()->after('kel_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('kota_kd')->nullable()->after('kec_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('prov_kd')->nullable()->after('kota_kd');
        });

        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->string('kd_pos_kd')->nullable()->after('prov_kd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('jns_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('sts_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('nik_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('nama_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('tpt_lhr_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('tgl_lhr_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('sex_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('agama_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('sts_nikah_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('telp1_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('telp2_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('alamat_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('rt_rw_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('kel_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('kec_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('kota_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('prov_kd');
        });
        Schema::table('201_dm_data_karyawan', function (Blueprint $table) {
            $table->dropColumn('kd_pos_kd');
        });
    }
};
