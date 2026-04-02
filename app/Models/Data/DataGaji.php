<?php

namespace App\Models\Data;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataGaji extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '205_dm_data_gaji';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_karyawan',
        'id_kode',

        // Pendapatan Tetap
        'id_gaji',          // IdGj - Id Dokumen Karyawan
        'gj_pokok',         // GjPokok - Gaji Pokok
        'tunjab',           // Tunjab - Tunjangan Jabatan
        'tunkom',           // Tunkom - Tunjangan Komunikasi
        'fot',              // Fot - Fix Over Time
        'tunmal',           // Tunmal - Tunjangan Kemahalan
        'ttl_pendapatan_ttp',   // Total Pendapatan Tetap (stored)

        // Pendapatan Tidak Tetap
        'id_lembur',        // IdLbr - Id Lembur
        'lbr_harian',       // LbrHarian - Lembur Harian
        'lbr_perjam',       // LbrPerJam - Lembur Per Jam

        'id_tukin',         // IdTukin - Id Tukin
        'tukin',            // Tukin - Tunjangan Kinerja

        'id_insentif',      // IdInsentif - Id Insentif
        'insentif',         // Insentif - Insentif

        'id_bonus',         // IdBonus - Id Bonus
        'bonus',            // Bonus - Bonus

        'id_thr',           // IdThr - Id Thr
        'thr',              // Thr - Tunjangan Hari Raya
        'ttl_pendapatan_tdk_ttp',   // Total Pendapatan Tidak Tetap (stored)
        'ttl_pendapatan',           // Total Pendapatan (stored)

        // Potongan
        'id_bpjs_tkj',      // IdBpjsTkj - Id BPJS Tenaga Kerja
        'bpjs_tkj',         // BpjsTkj - BPJS Tenaga Kerja Karyawan

        'id_bpjs_kes',      // IdBpjsKes - Id BPJS Kesehatan
        'bpjs_kes',         // BpjsKes - BPJS Kesehatan Karyawan

        'id_kop',           // IdKop - Id Koperasi
        'iuran_koperasi',   // IuranKoperasi - Iuran Wajib Koperasi

        'id_tps',           // IdTps - Id Tabungan Pensiun
        'tps_kry',          // TpsKry - Tabungan Pensiun Karyawan

        'id_pjk_pph',       // IdPjkPph - Id Pajak PPh
        'pjk_pkp',          // PjkPkp - Pajak PKP
        'pjk_pph',          // PjkPph - Pajak PPh

        'id_ptg_thr',       // IdPtgThr - Id Potongan THR
        'ptg_thr',          // PtgThr - Potongan THR

        'id_pjm_kop',       // IdPjmKop - Id Pinjaman Koperasi
        'pjm_kop',          // PjmKop - Pinjaman Koperasi

        'id_dda_sanksi',    // IdDdaSanksi - Id Denda Sanksi
        'dda_sanksi',       // DdaSanksi - Denda Sanksi
        'ttl_potongan',     // Total Potongan (stored)

        // Beban Tanggungan Perusahaan
        'bpjs_tkj_prs',     // BpjsTkjPrs - BPJS Tenaga Kerja Perusahaan
        'bpjs_kes_prs',     // BpjsKesPrs - BPJS Kesehatan Perusahaan
        'tps_prs',          // TpsPrs - Tabungan Pensiun Perusahaan

        'id_askes_prs',     // IdAskesPrs - Id Asuransi Kesehatan Perusahaan
        'askes_prs',        // AskesPrs - Asuransi Kesehatan Perusahaan
        'ttl_terima_gaji',  // Total Terima Gaji / Take Home Pay (stored)

        // Informasi
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'gj_pokok'              => 'decimal:2',
        'tunjab'                => 'decimal:2',
        'tunkom'                => 'decimal:2',
        'fot'                   => 'decimal:2',
        'tunmal'                => 'decimal:2',
        'ttl_pendapatan_ttp'    => 'decimal:2',
        'lbr_harian'            => 'decimal:2',
        'lbr_perjam'            => 'decimal:2',
        'tukin'                 => 'decimal:2',
        'insentif'              => 'decimal:2',
        'bonus'                 => 'decimal:2',
        'thr'                   => 'decimal:2',
        'ttl_pendapatan_tdk_ttp' => 'decimal:2',
        'ttl_pendapatan'        => 'decimal:2',
        'bpjs_tkj'              => 'decimal:2',
        'bpjs_kes'              => 'decimal:2',
        'iuran_koperasi'        => 'decimal:2',
        'tps_kry'               => 'decimal:2',
        'pjk_pkp'               => 'decimal:2',
        'pjk_pph'               => 'decimal:2',
        'ptg_thr'               => 'decimal:2',
        'pjm_kop'               => 'decimal:2',
        'dda_sanksi'            => 'decimal:2',
        'ttl_potongan'          => 'decimal:2',
        'bpjs_tkj_prs'          => 'decimal:2',
        'bpjs_kes_prs'          => 'decimal:2',
        'tps_prs'               => 'decimal:2',
        'askes_prs'             => 'decimal:2',
        'ttl_terima_gaji'       => 'decimal:2',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
    ];

    // =========================================================================
    // RELATIONS
    // =========================================================================

    /**
     * Get the employee that owns the salary record.
     */
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(DataKaryawan::class, 'id_karyawan', 'id');
    }

    /**
     * Get the creator user.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id_kode');
    }

    /**
     * Get the updater user.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_kode');
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get total pendapatan tetap (computed, tidak dari kolom stored).
     */
    public function getTotalPendapatanTetapAttribute(): float
    {
        return (float) ($this->gj_pokok ?? 0)
            + (float) ($this->tunjab ?? 0)
            + (float) ($this->tunkom ?? 0)
            + (float) ($this->fot ?? 0)
            + (float) ($this->tunmal ?? 0);
    }

    /**
     * Get total pendapatan tidak tetap (computed, tidak dari kolom stored).
     */
    public function getTotalPendapatanTidakTetapAttribute(): float
    {
        return (float) ($this->lbr_harian ?? 0)
            + (float) ($this->lbr_perjam ?? 0)
            + (float) ($this->tukin ?? 0)
            + (float) ($this->insentif ?? 0)
            + (float) ($this->bonus ?? 0)
            + (float) ($this->thr ?? 0);
    }

    /**
     * Get total potongan karyawan (computed, tidak dari kolom stored).
     */
    public function getTotalPotonganAttribute(): float
    {
        return (float) ($this->bpjs_tkj ?? 0)
            + (float) ($this->bpjs_kes ?? 0)
            + (float) ($this->iuran_koperasi ?? 0)
            + (float) ($this->tps_kry ?? 0)
            + (float) ($this->pjk_pkp ?? 0)
            + (float) ($this->pjk_pph ?? 0)
            + (float) ($this->ptg_thr ?? 0)
            + (float) ($this->pjm_kop ?? 0)
            + (float) ($this->dda_sanksi ?? 0);
    }

    /**
     * Get total beban tanggungan perusahaan (computed).
     */
    public function getTotalBebanPerusahaanAttribute(): float
    {
        return (float) ($this->bpjs_tkj_prs ?? 0)
            + (float) ($this->bpjs_kes_prs ?? 0)
            + (float) ($this->tps_prs ?? 0)
            + (float) ($this->askes_prs ?? 0);
    }

    /**
     * Get gaji bersih / take home pay (computed, tidak dari kolom stored).
     */
    public function getGajiBersihAttribute(): float
    {
        return $this->total_pendapatan_tetap
            + $this->total_pendapatan_tidak_tetap
            - $this->total_potongan;
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope to search by employee data or salary ID.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('id_gaji', 'like', '%' . $search . '%')
                ->orWhere('id_kode', 'like', '%' . $search . '%')
                ->orWhereHas('karyawan', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('nrk', 'like', '%' . $search . '%')
                        ->orWhere('nik', 'like', '%' . $search . '%');
                });
        });
    }

    /**
     * Scope to filter by employee.
     */
    public function scopeByKaryawan($query, int $idKaryawan)
    {
        return $query->where('id_karyawan', $idKaryawan);
    }

    // =========================================================================
    // STATIC METHODS
    // =========================================================================

    /**
     * Get salary statistics for dashboard.
     */
    public static function getSalaryStatistics(): array
    {
        return [
            'total_karyawan'    => self::distinct('id_karyawan')->count('id_karyawan'),
            'rata_gaji_pokok'   => self::avg('gj_pokok'),
            'total_gaji_pokok'  => self::sum('gj_pokok'),
            'total_thr'         => self::sum('thr'),
            'total_bonus'       => self::sum('bonus'),
        ];
    }
}