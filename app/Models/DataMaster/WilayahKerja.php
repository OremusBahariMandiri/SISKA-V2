<?php

namespace App\Models\DataMaster;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WilayahKerja extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '102_dm_wilker';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kode',
        'kode_wk',
        'wilayah_krj',
        'skt_wilker',
        'area_krj',
        'singkatan_wk',
        'alamat_wk',
        'rt_rw_wk',
        'kel_wk',
        'kec_wk',
        'kota_wk',
        'prov_wk',
        'kd_pos_wk',
        'tlp1',
        'tlp2',
        'email1',
        'email2',
        'instagram',
        'facebook',
        'foto_dokumen',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the work area's full address.
     *
     * @return string
     */
    public function getFullAddressAttribute(): string
    {
        $address = $this->alamat_wk;

        if ($this->rt_rw_wk) {
            $address .= ', RT/RW ' . $this->rt_rw_wk;
        }

        if ($this->kel_wk) {
            $address .= ', ' . $this->kel_wk;
        }

        if ($this->kec_wk) {
            $address .= ', ' . $this->kec_wk;
        }

        $address .= ', ' . $this->kota_wk . ', ' . $this->prov_wk;

        if ($this->kd_pos_wk) {
            $address .= ' ' . $this->kd_pos_wk;
        }

        return $address;
    }

    /**
     * Get the work area's primary phone number.
     *
     * @return string|null
     */
    public function getPrimaryPhoneAttribute(): ?string
    {
        return $this->tlp1 ?: $this->tlp2;
    }

    /**
     * Get the work area's primary email.
     *
     * @return string|null
     */
    public function getPrimaryEmailAttribute(): ?string
    {
        return $this->email1 ?: $this->email2;
    }

    /**
     * Scope a query to only include work areas in a specific city.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $city
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInCity($query, $city)
    {
        return $query->where('kota_wk', 'like', '%' . $city . '%');
    }

    /**
     * Scope a query to only include work areas in a specific province.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $province
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInProvince($query, $province)
    {
        return $query->where('prov_wk', 'like', '%' . $province . '%');
    }

    /**
     * Scope a query to only include work areas by region.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $region
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRegion($query, $region)
    {
        return $query->where('wilayah_krj', 'like', '%' . $region . '%');
    }

    /**
     * Scope a query to only include work areas by area.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $area
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByArea($query, $area)
    {
        return $query->where('area_krj', 'like', '%' . $area . '%');
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

    public function scopeSortByCode($query)
    {
        return $query->orderByRaw('CAST(kode_wk AS UNSIGNED) ASC');
    }

    /**
     * Scope a query to sort by region and code ascending.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortByCodeAndRegion($query)
    {
        return $query->orderByRaw('CAST(kode_wk AS UNSIGNED) ASC')
                    ->orderBy('wilayah_krj', 'ASC')
                    ->orderBy('area_krj', 'ASC');
    }
}
