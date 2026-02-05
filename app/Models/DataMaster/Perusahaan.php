<?php

namespace App\Models\DataMaster;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '101_dm_perusahaan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kode',
        'kode_prs',
        'nama_prs1',
        'nama_prs2',
        'alamat_prs',
        'rt_rw_prs',
        'kel_prs',
        'kec_prs',
        'kota_prs',
        'prov_prs',
        'kd_pos_prs',
        'tlp1',
        'tlp2',
        'email1',
        'email2',
        'instagram',
        'facebook',
        'web',
        'tgl_pendirian',
        'bidang_ush',
        'ijin_ush',
        'golongan_ush',
        'dirut',
        'direktur',
        'komisaris_utm',
        'komisaris1',
        'komisaris2',
        'komisaris3',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_pendirian' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the company's full address.
     *
     * @return string
     */
    public function getFullAddressAttribute(): string
    {
        $address = $this->alamat_prs;

        if ($this->rt_rw_prs) {
            $address .= ', RT/RW ' . $this->rt_rw_prs;
        }

        if ($this->kel_prs) {
            $address .= ', ' . $this->kel_prs;
        }

        if ($this->kec_prs) {
            $address .= ', ' . $this->kec_prs;
        }

        $address .= ', ' . $this->kota_prs . ', ' . $this->prov_prs;

        if ($this->kd_pos_prs) {
            $address .= ' ' . $this->kd_pos_prs;
        }

        return $address;
    }

    /**
     * Get the company's primary phone number.
     *
     * @return string|null
     */
    public function getPrimaryPhoneAttribute(): ?string
    {
        return $this->tlp1 ?: $this->tlp2;
    }

    /**
     * Get the company's primary email.
     *
     * @return string|null
     */
    public function getPrimaryEmailAttribute(): ?string
    {
        return $this->email1 ?: $this->email2;
    }

    /**
     * Scope a query to only include companies in a specific city.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $city
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInCity($query, $city)
    {
        return $query->where('kota_prs', 'like', '%' . $city . '%');
    }

    /**
     * Scope a query to only include companies in a specific province.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $province
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInProvince($query, $province)
    {
        return $query->where('prov_prs', 'like', '%' . $province . '%');
    }

    /**
     * Scope a query to only include companies with specific business field.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $businessField
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByBusinessField($query, $businessField)
    {
        return $query->where('bidang_ush', 'like', '%' . $businessField . '%');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_kode');
    }

    /**
     * Get the updater user.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_kode');
    }
}
