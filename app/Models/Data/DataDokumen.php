<?php

namespace App\Models\Data;

use App\Models\DataMaster\DokumenKaryawan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection;

class DataDokumen extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '203_dm_data_dokumen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kode',
        'id_data_kry', // relasi ke data karyawan
        'id_dok_kry', // auto generate unique
        'kode_dok_kry', // kode dokumen
        'id_dokumen', // relasi ke tabel master dokumen
        'ket_dok', // keterangan dokumen
        'ctt_dok', // catatan dokumen
        'no_dok', // nomor dokumen
        'tgl_ttd', // tanggal terbit dokumen
        'jns_msb_dok', // jenis masa berlaku ( tetap / perpanjangan)
        'tgl_akr_dok', // tanggal akhir dokumen
        'msb_dok', // masa berlaku dokumen (tgl_akr_dok - tgl_ttd)
        'tgl_pgt_dok', // tanggal peringatan dokumen
        'durasi_pgt', // durasi peringatan (tgl_pgt ke tgl akhir dok)
        'file_dok', // file dokumen
        'sts_dok', // status dokumen (Aktif / Non Aktif)
        'tgl_dok_na', // tgl surat non aktif
        'ket_dok_na', // keterangan dok non aktif
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
        'tgl_pgt_dok' => 'date',
        'tgl_dok_na' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the employee that owns the document.
     */
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(DataKaryawan::class, 'id_data_kry', 'id');
    }

    /**
     * Get the document type.
     */
    public function dokumenKaryawan(): BelongsTo
    {
        return $this->belongsTo(DokumenKaryawan::class, 'id_dokumen', 'id');
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
     * Get all documents for the same employee
     */
    public function allEmployeeDocuments(): Collection
    {
        return self::where('id_data_kry', $this->id_data_kry)
            ->with(['dokumenKaryawan'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get latest document for employee (for main display)
     */
    public function getLatestDocumentAttribute()
    {
        return self::where('id_data_kry', $this->id_data_kry)
            ->with(['dokumenKaryawan'])
            ->orderBy('tgl_ttd', 'desc')
            ->first();
    }

    /**
     * Get active documents for employee
     */
    public function getActiveDocumentsAttribute()
    {
        return self::where('id_data_kry', $this->id_data_kry)
            ->where('sts_dok', 'AKTIF')
            ->with(['dokumenKaryawan'])
            ->orderBy('tgl_ttd', 'desc')
            ->get();
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
            return true; // If no expiry date, consider active if status is AKTIF
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
     * Scope to get unique employees (group by id_data_kry)
     * This will return only one record per employee (latest by created_at)
     */
    public function scopeUniqueEmployees($query)
    {
        return $query->select('*')
            ->whereIn('id', function($subquery) {
                $subquery->select(\DB::raw('MAX(id)'))
                    ->from('203_dm_data_dokumen')
                    ->groupBy('id_data_kry');
            });
    }

    /**
     * Scope to get employee's latest document data for display
     */
    public function scopeEmployeeLatestData($query)
    {
        return $query->select('*')
            ->whereIn('id', function($subquery) {
                $subquery->select(\DB::raw('MAX(id)'))
                    ->from('203_dm_data_dokumen')
                    ->groupBy('id_data_kry');
            });
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
        return $query->where('id_dokumen', $documentTypeId);
    }

    /**
     * Scope a query to filter by validity period type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $validityType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByValidityType($query, $validityType)
    {
        return $query->where('jns_msb_dok', $validityType);
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
            $q->where('id_dok_kry', 'like', '%' . $search . '%')
                ->orWhere('kode_dok_kry', 'like', '%' . $search . '%')
                ->orWhere('no_dok', 'like', '%' . $search . '%')
                ->orWhere('no_srt_ktr', 'like', '%' . $search . '%')
                ->orWhereHas('karyawan', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('nrk', 'like', '%' . $search . '%')
                        ->orWhere('nik', 'like', '%' . $search . '%');
                })
                ->orWhereHas('dokumenKaryawan', function ($q) use ($search) {
                    $q->where('nama_dok', 'like', '%' . $search . '%');
                });
        });
    }

    /**
     * Scope to sort by signature date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortBySignatureDate($query, $direction = 'desc')
    {
        return $query->orderBy('tgl_ttd', $direction);
    }

    /**
     * Scope to sort by expiry date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortByExpiryDate($query, $direction = 'desc')
    {
        return $query->orderBy('tgl_akr_dok', $direction);
    }

    /**
     * Get document reminder status based on reminder date
     */
    public function getDocumentReminderStatusAttribute()
    {
        if (!$this->tgl_pgt_dok) {
            return null;
        }

        try {
            $reminderDate = \Carbon\Carbon::parse($this->tgl_pgt_dok);
            $today = \Carbon\Carbon::now()->startOf('day');
            $diffDays = $reminderDate->diffInDays($today, false);

            if ($diffDays < 0) {
                return [
                    'status' => 'overdue',
                    'days' => abs($diffDays),
                    'message' => abs($diffDays) . ' hari terlambat',
                    'priority' => 1,
                    'class' => 'danger'
                ];
            } elseif ($diffDays === 0) {
                return [
                    'status' => 'today',
                    'days' => 0,
                    'message' => 'Hari ini',
                    'priority' => 1,
                    'class' => 'warning'
                ];
            } elseif ($diffDays <= 7) {
                return [
                    'status' => 'urgent',
                    'days' => $diffDays,
                    'message' => $diffDays . ' hari lagi',
                    'priority' => 2,
                    'class' => 'warning'
                ];
            } elseif ($diffDays <= 30) {
                return [
                    'status' => 'warning',
                    'days' => $diffDays,
                    'message' => $diffDays . ' hari lagi',
                    'priority' => 3,
                    'class' => 'info'
                ];
            } else {
                return [
                    'status' => 'safe',
                    'days' => $diffDays,
                    'message' => $diffDays . ' hari lagi',
                    'priority' => 4,
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
        // Check document status first (highest priority for inactive documents)
        if (in_array($this->sts_dok, ['NON-AKTIF', 'EXPIRED'])) {
            return 5; // Lowest priority (gray)
        }

        // Check if document is expired based on expiry date
        if ($this->tgl_akr_dok) {
            try {
                $expiryDate = \Carbon\Carbon::parse($this->tgl_akr_dok);
                $today = \Carbon\Carbon::now()->startOf('day');

                if ($expiryDate->isBefore($today)) {
                    return 1; // Highest priority (red - expired)
                }
            } catch (\Exception $e) {
                // Handle invalid date format
            }
        }

        // Check reminder status
        $reminderStatus = $this->document_reminder_status;
        if ($reminderStatus) {
            return $reminderStatus['priority'];
        }

        // Check expiry date for warning (within 30 days)
        if ($this->tgl_akr_dok) {
            try {
                $expiryDate = \Carbon\Carbon::parse($this->tgl_akr_dok);
                $today = \Carbon\Carbon::now()->startOf('day');
                $diffDays = $expiryDate->diffInDays($today, false);

                if ($diffDays > 0 && $diffDays <= 30) {
                    return 2; // Warning priority (yellow)
                }
            } catch (\Exception $e) {
                // Handle invalid date format
            }
        }

        return 4; // Normal priority (no special highlighting)
    }

    /**
     * Get document highlight class for row styling
     */
    public function getDocumentHighlightClassAttribute()
    {
        $priority = $this->document_priority;

        switch ($priority) {
            case 1:
                return 'highlight-red'; // Expired/overdue
            case 2:
                return 'highlight-yellow'; // Urgent (within 7 days or expiry within 30 days)
            case 3:
                return 'highlight-orange'; // Warning (within 30 days)
            case 5:
                return 'highlight-gray'; // Inactive documents
            default:
                return ''; // No highlighting
        }
    }

    /**
     * Get formatted reminder text with badge class
     */
    public function getFormattedReminderTextAttribute()
    {
        $reminderStatus = $this->document_reminder_status;

        if ($reminderStatus) {
            return [
                'text' => $reminderStatus['message'],
                'class' => 'bg-' . $reminderStatus['class']
            ];
        }

        // Fallback to expiry date if no reminder date
        if ($this->tgl_akr_dok) {
            try {
                $expiryDate = \Carbon\Carbon::parse($this->tgl_akr_dok);
                $today = \Carbon\Carbon::now()->startOf('day');
                $diffDays = $expiryDate->diffInDays($today, false);

                if ($diffDays < 0) {
                    return [
                        'text' => abs($diffDays) . ' hari lewat',
                        'class' => 'bg-danger'
                    ];
                } elseif ($diffDays === 0) {
                    return [
                        'text' => 'Hari ini',
                        'class' => 'bg-warning text-dark'
                    ];
                } elseif ($diffDays <= 30) {
                    return [
                        'text' => $diffDays . ' hari',
                        'class' => 'bg-warning text-dark'
                    ];
                } else {
                    return [
                        'text' => $diffDays . ' hari',
                        'class' => 'bg-success'
                    ];
                }
            } catch (\Exception $e) {
                return [
                    'text' => '-',
                    'class' => 'bg-secondary'
                ];
            }
        }

        return [
            'text' => '-',
            'class' => 'bg-secondary'
        ];
    }

    /**
     * Scope untuk documents yang akan expired berdasarkan reminder date
     */
    public function scopeReminderExpiringSoon($query, $days = 30)
    {
        return $query->whereNotNull('tgl_pgt_dok')
            ->whereRaw('STR_TO_DATE(tgl_pgt_dok, "%Y-%m-%d") BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)', [$days]);
    }

    /**
     * Scope untuk documents yang reminder date sudah lewat
     */
    public function scopeReminderOverdue($query)
    {
        return $query->whereNotNull('tgl_pgt_dok')
            ->whereRaw('STR_TO_DATE(tgl_pgt_dok, "%Y-%m-%d") < CURDATE()');
    }

    /**
     * Scope untuk mengurutkan berdasarkan prioritas document
     */
    public function scopeOrderByPriority($query)
    {
        return $query->selectRaw('
            *,
            CASE
                WHEN sts_dok IN ("NON-AKTIF", "EXPIRED") THEN 5
                WHEN tgl_akr_dok IS NOT NULL AND STR_TO_DATE(tgl_akr_dok, "%Y-%m-%d") < CURDATE() THEN 1
                WHEN tgl_pgt_dok IS NOT NULL AND STR_TO_DATE(tgl_pgt_dok, "%Y-%m-%d") < CURDATE() THEN 1
                WHEN tgl_pgt_dok IS NOT NULL AND STR_TO_DATE(tgl_pgt_dok, "%Y-%m-%d") BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN 2
                WHEN tgl_pgt_dok IS NOT NULL AND STR_TO_DATE(tgl_pgt_dok, "%Y-%m-%d") BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 3
                WHEN tgl_akr_dok IS NOT NULL AND STR_TO_DATE(tgl_akr_dok, "%Y-%m-%d") BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 2
                ELSE 4
            END as document_priority
        ')->orderBy('document_priority', 'asc');
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
                $query->whereNotNull('tgl_pgt_dok')
                    ->where('tgl_pgt_dok', '<', $now->format('Y-m-d'))
                    ->orWhere(function ($q) use ($now) {
                        $q->whereNotNull('tgl_akr_dok')
                            ->where('tgl_akr_dok', '<', $now->format('Y-m-d'));
                    });
            })->count(),
            'warning' => self::where(function ($query) use ($now) {
                $query->whereNotNull('tgl_pgt_dok')
                    ->whereBetween('tgl_pgt_dok', [
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

    /**
     * Get documents grouped by type for employee
     */
    public function getDocumentsByTypeAttribute()
    {
        return self::where('id_data_kry', $this->id_data_kry)
            ->with(['dokumenKaryawan'])
            ->get()
            ->groupBy('id_dokumen');
    }

    /**
     * Check if document needs renewal based on reminder date
     */
    public function getNeedsRenewalAttribute(): bool
    {
        if (!$this->tgl_pgt_dok) {
            return false;
        }

        try {
            $reminderDate = \Carbon\Carbon::parse($this->tgl_pgt_dok);
            return now()->greaterThanOrEqualTo($reminderDate);
        } catch (\Exception $e) {
            return false;
        }
    }
}
