<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserAccess;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = '001_dm_users';

    protected $fillable = [
        'id_kode',
        'nik_kry',
        'nama_kry',
        'departemen_kry',
        'jabatan_kry',
        'wilker_kry',
        'password_kry',
        'is_admin',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password_kry',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'is_admin' => 'boolean',
    ];

    /**
     * Boot model
     */
    protected static function boot()
    {
        parent::boot();

        // Set timezone untuk Carbon dalam model
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
    }

    /**
     * Mutator untuk password - otomatis hash password saat disimpan
     */
    public function setPasswordKryAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password_kry'] = Hash::make($value);
        }
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_kry;
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'nik_kry';
    }

    /**
     * Get email attribute (untuk kompatibilitas dengan sistem tracking)
     */
    public function getEmailAttribute()
    {
        // Jika tidak ada email, generate dari NIK
        return $this->nik_kry . '@company.local';
    }

    public function userAccess()
    {
        return $this->hasMany(UserAccess::class, 'id_kode_a01', 'id_kode');
    }

    /**
     * Memeriksa apakah user memiliki akses ke menu tertentu
     *
     * @param string $menu Nama menu
     * @param string $action Nama aksi (tambah, ubah, hapus, dll)
     * @return bool
     */
    public function hasAccess($menu, $action = null)
    {
        // Admin memiliki semua akses
        if ($this->is_admin) {
            return true;
        }

        // Cek akses berdasarkan menu dan action
        foreach ($this->userAccess as $access) {
            if ($access->menu_acs == $menu) {
                if ($action === null) {
                    return true; // Hanya cek menu tanpa action
                }

                // Cek akses spesifik
                $actionField = $action . '_acs';
                return isset($access->$actionField) && $access->$actionField;
            }
        }

        return false;
    }

    public function isAdmin()
    {
        return (bool) $this->is_admin;
    }

    /**
     * Get user's full info for tracking
     */
    public function getTrackingData()
    {
        return [
            'user_id' => $this->id,
            'nik_kry' => $this->nik_kry,
            'nama_kry' => $this->nama_kry,
            'departemen_kry' => $this->departemen_kry,
            'jabatan_kry' => $this->jabatan_kry,
            'wilker_kry' => $this->wilker_kry,
            'is_admin' => $this->is_admin,
            'email' => $this->email,
        ];
    }

    /**
     * Get user's display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->nama_kry . ' (' . $this->nik_kry . ')';
    }

    /**
     * Get user's department and position
     */
    public function getPositionAttribute()
    {
        return $this->jabatan_kry . ' - ' . $this->departemen_kry;
    }

    /**
     * Format created_at untuk WIB
     */
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at ?
            Carbon::parse($this->created_at)->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s') :
            null;
    }

    /**
     * Format updated_at untuk WIB
     */
    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updated_at ?
            Carbon::parse($this->updated_at)->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s') :
            null;
    }

    /**
     * Override untuk sensitive fields tracking
     */
    protected function getSensitiveFields()
    {
        return [
            'password_kry',
            'remember_token',
        ];
    }
}