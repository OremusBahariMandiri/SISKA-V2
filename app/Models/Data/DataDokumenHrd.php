<?php

namespace App\Models\Data;

use App\Models\DataMaster\DokumenHrd;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\DataMaster\Perusahaan;

class DataDokumenHrd extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '206_dm_data_dok_hrd';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kode',
        'id_dokumen_hrd',
        'id_perusahaan',
        'ket_dok_hrd',
        'no_dok_hrd',
        'tgl_ttd',
        'jns_msb_dok',
        'tgl_akr_dok',
        'msb_dok',
        'tgl_prt_dok',
        'durasi_pgt',
        'file_dok',
        'catatan_dok_hrd',
        'file_dok_2',
        'sts_dok',
        'tgl_dok_na',
        'ket_dok_na',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_ttd' => 'date',
        'tgl_akr_dok' => 'date',
        'tgl_prt_dok' => 'date',
        'tgl_dok_na' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the document type.
     */
    public function dokumenHrd(): BelongsTo
    {
        return $this->belongsTo(DokumenHrd::class, 'id_dokumen_hrd', 'id');
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

    /**
     * Get extension duration in months.
     *
     * @return int|null
     */
    public function getExtensionDurationMonthsAttribute(): ?int
    {
        return $this->durasi_pgt ? (int) $this->durasi_pgt : null;
    }

    /**
     * Check if document is active.
     *
     * @return bool
     */
    public function getIsActiveAttribute(): bool
    {
        if ($this->sts_dok !== 'AKTIF') {
            return false;
        }

        if (!$this->tgl_akr_dok) {
            return true;
        }

        try {
            $endDate = \Carbon\Carbon::parse($this->tgl_akr_dok);
            return now()->lessThanOrEqualTo($endDate);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if document has expired.
     *
     * @return bool
     */
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->tgl_akr_dok) {
            return false;
        }

        try {
            $endDate = \Carbon\Carbon::parse($this->tgl_akr_dok);
            return now()->greaterThan($endDate);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get days until document expires.
     *
     * @return int|null
     */
    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->tgl_akr_dok) {
            return null;
        }

        try {
            $endDate = \Carbon\Carbon::parse($this->tgl_akr_dok);
            return now()->diffInDays($endDate, false);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get document validity period in months.
     *
     * @return int|null
     */
    public function getValidityPeriodMonthsAttribute(): ?int
    {
        return $this->msb_dok ? (int) $this->msb_dok : null;
    }

    /**
     * Scope a query to only include active documents.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('sts_dok', 'AKTIF');
    }

    /**
     * Scope a query to only include expired documents.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('tgl_akr_dok')
            ->whereRaw('STR_TO_DATE(tgl_akr_dok, "%Y-%m-%d") < CURDATE()');
    }

    /**
     * Scope a query to only include documents expiring soon.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereNotNull('tgl_akr_dok')
            ->whereRaw('STR_TO_DATE(tgl_akr_dok, "%Y-%m-%d") BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)', [$days]);
    }

    /**
     * Scope a query to filter by document type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $documentTypeId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDocumentType($query, $documentTypeId)
    {
        return $query->where('id_dokumen_hrd', $documentTypeId);
    }

    /**
     * Scope a query to search by document data.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('no_dok_hrd', 'like', '%' . $search . '%')
                ->orWhere('ket_dok_hrd', 'like', '%' . $search . '%')
                ->orWhereHas('dokumenHrd', function ($q) use ($search) {
                    $q->where('ktg_dok_hrd', 'like', '%' . $search . '%')
                        ->orWhere('jns_dok_hrd', 'like', '%' . $search . '%');
                });
        });
    }

    /**
     * Get document reminder status based on reminder date
     * PERBAIKAN: Logika perhitungan selisih hari yang benar
     */
    public function getDocumentReminderStatusAttribute()
    {
        if (!$this->tgl_prt_dok) {
            return null;
        }

        try {
            $reminderDate = \Carbon\Carbon::parse($this->tgl_prt_dok)->startOfDay();
            $today = \Carbon\Carbon::now()->startOfDay();

            // PERBAIKAN: Hitung selisih dari today ke reminderDate
            // Jika hasilnya positif = masih di masa depan (belum terlambat)
            // Jika hasilnya negatif = sudah lewat (terlambat)
            $diffDays = $today->diffInDays($reminderDate, false);

            // Jika tanggal peringatan sudah lewat (diffDays negatif)
            if ($diffDays < 0) {
                return [
                    'status' => 'overdue',
                    'days' => abs($diffDays),
                    'message' => abs($diffDays) . ' hari terlambat',
                    'priority' => 1,
                    'class' => 'danger'
                ];
            }
            // Jika tanggal peringatan hari ini
            elseif ($diffDays === 0) {
                return [
                    'status' => 'today',
                    'days' => 0,
                    'message' => 'Hari ini',
                    'priority' => 1,
                    'class' => 'danger'
                ];
            }
            // Jika tanggal peringatan 1-7 hari ke depan
            elseif ($diffDays > 0 && $diffDays <= 7) {
                return [
                    'status' => 'urgent',
                    'days' => $diffDays,
                    'message' => $diffDays . ' hari lagi',
                    'priority' => 2,
                    'class' => 'warning'
                ];
            }
            // Jika tanggal peringatan 8-30 hari ke depan
            elseif ($diffDays > 7 && $diffDays <= 30) {
                return [
                    'status' => 'warning',
                    'days' => $diffDays,
                    'message' => $diffDays . ' hari lagi',
                    'priority' => 3,
                    'class' => 'success'
                ];
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get document priority for sorting
     */
    public function getDocumentPriorityAttribute()
    {
        if (in_array($this->sts_dok, ['NON-AKTIF', 'EXPIRED'])) {
            return 5;
        }

        if ($this->tgl_akr_dok) {
            try {
                $expiryDate = \Carbon\Carbon::parse($this->tgl_akr_dok);
                $today = \Carbon\Carbon::now()->startOf('day');

                if ($expiryDate->isBefore($today)) {
                    return 1;
                }
            } catch (\Exception $e) {
                // Handle invalid date
            }
        }

        $reminderStatus = $this->document_reminder_status;
        if ($reminderStatus) {
            return $reminderStatus['priority'];
        }

        if ($this->tgl_akr_dok) {
            try {
                $expiryDate = \Carbon\Carbon::parse($this->tgl_akr_dok);
                $today = \Carbon\Carbon::now()->startOf('day');
                $diffDays = $expiryDate->diffInDays($today, false);

                if ($diffDays > 0 && $diffDays <= 30) {
                    return 2;
                }
            } catch (\Exception $e) {
                // Handle invalid date
            }
        }

        return 4;
    }

    /**
     * Check if document needs renewal based on reminder date
     */
    public function getNeedsRenewalAttribute(): bool
    {
        if (!$this->tgl_prt_dok) {
            return false;
        }

        try {
            $reminderDate = \Carbon\Carbon::parse($this->tgl_prt_dok);
            return now()->greaterThanOrEqualTo($reminderDate);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get document statistics for dashboard
     */
    public static function getDocumentStatistics()
    {
        $now = \Carbon\Carbon::now();

        return [
            'total' => self::count(),
            'active' => self::where('sts_dok', 'AKTIF')->count(),
            'expired' => self::where(function ($query) use ($now) {
                $query->whereNotNull('tgl_prt_dok')
                    ->where('tgl_prt_dok', '<', $now->format('Y-m-d'))
                    ->orWhere(function ($q) use ($now) {
                        $q->whereNotNull('tgl_akr_dok')
                            ->where('tgl_akr_dok', '<', $now->format('Y-m-d'));
                    });
            })->count(),
            'warning' => self::where(function ($query) use ($now) {
                $query->whereNotNull('tgl_prt_dok')
                    ->whereBetween('tgl_prt_dok', [
                        $now->format('Y-m-d'),
                        $now->copy()->addDays(30)->format('Y-m-d')
                    ])
                    ->orWhere(function ($q) use ($now) {
                        $q->whereNotNull('tgl_akr_dok')
                            ->whereBetween('tgl_akr_dok', [
                                $now->format('Y-m-d'),
                                $now->copy()->addDays(30)->format('Y-m-d')
                            ]);
                    });
            })->count(),
        ];
    }
    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id');
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('id_perusahaan', $companyId);
    }
}
