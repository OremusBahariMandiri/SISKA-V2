<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
    use HasFactory;

    protected $table = '002_dm_user_access';

    protected $fillable = [
        'id_kode_a01',
        'menu_acs',
        'tambah_acs',
        'ubah_acs',
        'hapus_acs',
        'download_acs',
        'detail_acs',
        'monitoring_acs',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_kode_a01', 'id_kode');
    }
}