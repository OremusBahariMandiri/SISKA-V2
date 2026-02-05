<?php

namespace App\Models\DataMaster;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '103_dm_departemen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_dep',
        'nama_dep',
        'singkatan_dep',
        'nama_jbt',
        'singkatan_jbt',
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
     * Get the department name with abbreviation if available.
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->singkatan_dep
            ? "{$this->nama_dep} ({$this->singkatan_dep})"
            : $this->nama_dep;
    }

    /**
     * Get the position name with abbreviation if available.
     *
     * @return string
     */
    public function getDisplayPositionAttribute(): string
    {
        return $this->singkatan_jbt
            ? "{$this->nama_jbt} ({$this->singkatan_jbt})"
            : $this->nama_jbt;
    }

    /**
     * Scope a query to search departments by name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByName($query, $name)
    {
        return $query->where('nama_dep', 'like', '%' . $name . '%')
                    ->orWhere('singkatan_dep', 'like', '%' . $name . '%');
    }

    /**
     * Scope a query to search departments by position.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $position
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByPosition($query, $position)
    {
        return $query->where('nama_jbt', 'like', '%' . $position . '%')
                    ->orWhere('singkatan_jbt', 'like', '%' . $position . '%');
    }

    /**
     * Scope a query to find by department code.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $code
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('kode_dep', $code);
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