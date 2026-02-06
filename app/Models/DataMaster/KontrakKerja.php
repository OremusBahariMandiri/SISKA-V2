<?php

namespace App\Models\DataMaster;

use App\Models\Data\DataKaryawan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontrakKerja extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '104_dm_kontrak';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'id_kode',
        'kode_ktr',
        'nama_ktr',
        'singkatan_ktr',
        'created_by',
        'updated_by',
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
     * Get the contract's display name.
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->kode_kontrak . ' - ' . $this->nama_kontrak;
    }

    /**
     * Get the contract's full information.
     *
     * @return string
     */
    public function getFullInfoAttribute(): string
    {
        $info = $this->nama_kontrak . ' (' . $this->kode_kontrak . ')';

        if ($this->singkatan_ktr) {
            $info .= ' - ' . $this->singkatan_ktr;
        }

        return $info;
    }

    /**
     * Scope a query to search contracts by name or code.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('kode_kontrak', 'like', '%' . $search . '%')
              ->orWhere('nama_kontrak', 'like', '%' . $search . '%');
        });
    }

    /**
     * Scope a query to only include active contracts.
     * You can customize this based on your business logic.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        // Customize based on your logic for active contracts
        return $query->whereNotNull('id');
    }

    /**
     * Get the creator user.
     */
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

    /**
     * Get all employees with this contract status.
     */
    public function karyawan()
    {
        return $this->hasMany(DataKaryawan::class, 'sts_ktr', 'id');
    }

    /**
     * Get the count of employees with this contract.
     *
     * @return int
     */
    public function getKaryawanCountAttribute(): int
    {
        return $this->karyawan()->count();
    }

    /**
     * Check if the contract has any employees.
     *
     * @return bool
     */
    public function hasKaryawan(): bool
    {
        return $this->karyawan()->exists();
    }
}