<?php

namespace App\Models\Data;

use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\KontrakKerja;
use App\Models\DataMaster\Perusahaan;
use App\Models\DataMaster\WilayahKerja;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataKaryawan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '201_dm_data_karyawan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kode',
        'idkry',
        'tgl_masuk',
        'nrk',
        'nik',
        'nama',
        'tpt_lahir',
        'tgl_lahir',
        'sex',
        'agama',
        'kewarganegaraan',
        'sts_nikah',
        'sts_keluarga',
        'jml_anak',
        'tlp1',
        'tlp2',
        'email1',
        'email2',
        'instagram',
        'facebook',
        'foto_dokumen',
        // Alamat
        'alamat_ktp',
        'rt_rw_ktp',
        'kel_ktp',
        'kec_ktp',
        'kota_ktp',
        'prov_ktp',
        'kd_pos_ktp',
        'alamat_dom',
        'rt_rw_dom',
        'kel_dom',
        'kec_dom',
        'kota_dom',
        'prov_dom',
        'kd_pos_dom',
        // Pendidikan
        'id_pendidikan',
        'jenjang_skl',
        'institusi_skl',
        'skt_inst_skl', //1
        'kota_skl',
        'fakultas_skl',
        'jurusan_skl',
        'gelar_skl',
        'tgl_lulus_skl',
        // Kontrak Kerja
        'idktr',
        'sts_ktr',
        'skt_sts_ktr', //2
        'tgl_awal_ktr',
        'tgl_akhir_ktr',
        'durasi_ktr',
        'perusahaan',
        'skt_prs', //3
        // Jenjang Karir
        'id_karir',
        'departemen',
        'skt_dep', //4
        'jabatan',
        'skt_jbt', //5
        'tugas',
        'unit_krj',
        'skt_wil_krj', //6
        'wilker',
        // Hubungan Industrial
        'id_hubin',
        'sts_kry',
        'skt_sts_kry', //7
        'tgl_phk',
        'ket_phk',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_masuk' => 'date',
        'tgl_lahir' => 'date',
        'tgl_lulus_skl' => 'date',
        'tgl_awal_ktr' => 'date',
        'tgl_akhir_ktr' => 'date',
        'tgl_phk' => 'date',
        'jml_anak' => 'integer',
        'durasi_ktr' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the company that owns the employee.
     * Foreign key: perusahaan (column in data_karyawan table)
     * References: id (column in perusahaan table)
     */
    public function perusahaanRelation(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan', 'id');
    }

    public function unitKerjaRelation(): BelongsTo
    {
        return $this->belongsTo(WilayahKerja::class, 'unit_krj', 'id');
    }

    /**
     * Get the department that owns the employee.
     * Foreign key: departemen (column in data_karyawan table)
     * References: id (column in departemen table)
     */
    public function departemenRelation(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'departemen', 'id');
    }

    /**
     * Get the work area that owns the employee.
     * Foreign key: wilker (column in data_karyawan table)
     * References: id (column in wilayah_kerja table)
     */
    public function wilayahKerjaRelation(): BelongsTo
    {
        return $this->belongsTo(WilayahKerja::class, 'wilker', 'id');
    }

    public function kontrakRelation(): BelongsTo
    {
        return $this->belongsTo(KontrakKerja::class, 'sts_ktr', 'id');
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
     * Get the employee's full KTP address.
     *
     * @return string
     */
    public function getFullKtpAddressAttribute(): string
    {
        if (!$this->alamat_ktp) return '';

        $address = $this->alamat_ktp;

        if ($this->rt_rw_ktp) {
            $address .= ', RT/RW ' . $this->rt_rw_ktp;
        }

        if ($this->kel_ktp) {
            $address .= ', ' . $this->kel_ktp;
        }

        if ($this->kec_ktp) {
            $address .= ', ' . $this->kec_ktp;
        }

        if ($this->kota_ktp) {
            $address .= ', ' . $this->kota_ktp;
        }

        if ($this->prov_ktp) {
            $address .= ', ' . $this->prov_ktp;
        }

        if ($this->kd_pos_ktp) {
            $address .= ' ' . $this->kd_pos_ktp;
        }

        return $address;
    }

    /**
     * Get the employee's full domicile address.
     *
     * @return string
     */
    public function getFullDomAddressAttribute(): string
    {
        if (!$this->alamat_dom) return '';

        $address = $this->alamat_dom;

        if ($this->rt_rw_dom) {
            $address .= ', RT/RW ' . $this->rt_rw_dom;
        }

        if ($this->kel_dom) {
            $address .= ', ' . $this->kel_dom;
        }

        if ($this->kec_dom) {
            $address .= ', ' . $this->kec_dom;
        }

        if ($this->kota_dom) {
            $address .= ', ' . $this->kota_dom;
        }

        if ($this->prov_dom) {
            $address .= ', ' . $this->prov_dom;
        }

        if ($this->kd_pos_dom) {
            $address .= ' ' . $this->kd_pos_dom;
        }

        return $address;
    }

    /**
     * Get the employee's primary phone number.
     *
     * @return string|null
     */
    public function getPrimaryPhoneAttribute(): ?string
    {
        return $this->tlp1 ?: $this->tlp2;
    }

    /**
     * Get the employee's primary email.
     *
     * @return string|null
     */
    public function getPrimaryEmailAttribute(): ?string
    {
        return $this->email1 ?: $this->email2;
    }

    /**
     * Get the employee's age.
     *
     * @return int|null
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->tgl_lahir) return null;

        return $this->tgl_lahir->diffInYears(now());
    }

    /**
     * Get the employee's work duration in years.
     *
     * @return int|null
     */
    public function getWorkDurationAttribute(): ?int
    {
        if (!$this->tgl_masuk) return null;

        return $this->tgl_masuk->diffInYears(now());
    }

    /**
     * Check if employee contract is active.
     *
     * @return bool
     */
    public function getIsContractActiveAttribute(): bool
    {
        if (!$this->tgl_awal_ktr || !$this->tgl_akhir_ktr) return false;

        $now = now();
        return $now->between($this->tgl_awal_ktr, $this->tgl_akhir_ktr);
    }

    /**
     * Scope a query to only include active employees.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('sts_kry', 'AKTIF');
    }

    /**
     * Scope a query to only include employees by gender.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $gender
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByGender($query, $gender)
    {
        return $query->where('sex', $gender);
    }

    /**
     * Scope a query to only include employees by department.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $departmentId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('departemen', $departmentId);
    }

    /**
     * Scope a query to only include employees by work area.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $workAreaId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByWorkArea($query, $workAreaId)
    {
        return $query->where('wilker', $workAreaId);
    }

    /**
     * Scope a query to search employees by name or NRK.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama', 'like', '%' . $search . '%')
            ->orWhere('nrk', 'like', '%' . $search . '%')
            ->orWhere('nik', 'like', '%' . $search . '%');
    }
}
