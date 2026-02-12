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

class DataKontrak extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '202_dm_data_kontrak';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Foreign Keys
        'id_kode',

        // tampilan TAB 1 (Data Karyawan)
        'id_data_kry', // field pilih data kry (relation)

        // tampilan TAB 2 (Data Kontrak)
        // Data Kontrak
        'id_data_ktr', // unique incerement 1,2,3,4
        'no_srt_ktr',
        'tgl_srt_ktr',
        'id_ktr', // relation model master kontrak
        'tgl_awl_ktr',
        'tgl_akhir_ktr',
        'durasi_ktr',
        'tgl_pgt_ktr',
        'durasi_pgt',
        'ktg_ktk',
        'id_prsh', // field pilih perusahaan (relation)
        'file_doc_ktr',
        'sts_srt_ktr',
        // keterangan non aktif
        'tgl_sr_na',
        'ket_sr_na',

        // tampilan TAB 3 (Data Pendidikan)
        // Pendidikan
        'id_pendidikan', // unique incerement 1,2,3,4
        'jenjang_skl',
        'institusi_skl',
        'skt_inst_skl',
        'kota_skl',
        'fakultas_skl',
        'jurusan_skl',
        'gelar_skl',
        'tgl_lulus_skl',

        // tampilan TAB 4 (Data Karir)
        // Karir
        'id_karir', // unique incerement 1,2,3,4
        'id_departemen', // field pilih departemen (relation)
        'id_wilker', // field pilih wilker (relation)
        'tugas',

        // User Tracking
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_lahir' => 'date',
        'tgl_lulus_skl' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the employee that owns the contract.
     */
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(DataKaryawan::class, 'id_data_kry', 'id');
    }

    /**
     * Get the contract type.
     */
    public function kontrakKerja(): BelongsTo
    {
        return $this->belongsTo(KontrakKerja::class, 'id_ktr', 'id');
    }

    /**
     * Get the company.
     */
    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class, 'id_prsh', 'id');
    }

    /**
     * Get the department.
     */
    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'id_departemen', 'id');
    }

    /**
     * Get the work area.
     */
    public function wilayahKerja(): BelongsTo
    {
        return $this->belongsTo(WilayahKerja::class, 'id_wilker', 'id');
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
     * Get contract duration in months.
     *
     * @return int|null
     */
    public function getContractDurationMonthsAttribute(): ?int
    {
        return $this->durasi_ktr ? (int) $this->durasi_ktr : null;
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
     * Check if contract is active.
     *
     * @return bool
     */
    public function getIsActiveAttribute(): bool
    {
        if (!$this->tgl_awl_ktr || !$this->tgl_akhir_ktr) {
            return false;
        }

        try {
            $startDate = \Carbon\Carbon::parse($this->tgl_awl_ktr);
            $endDate = \Carbon\Carbon::parse($this->tgl_akhir_ktr);
            $now = now();

            return $now->between($startDate, $endDate);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if contract has expired.
     *
     * @return bool
     */
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->tgl_akhir_ktr) {
            return false;
        }

        try {
            $endDate = \Carbon\Carbon::parse($this->tgl_akhir_ktr);
            return now()->greaterThan($endDate);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get days until contract expires.
     *
     * @return int|null
     */
    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->tgl_akhir_ktr) {
            return null;
        }

        try {
            $endDate = \Carbon\Carbon::parse($this->tgl_akhir_ktr);
            return now()->diffInDays($endDate, false);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get employee's education level display.
     *
     * @return string|null
     */
    public function getEducationDisplayAttribute(): ?string
    {
        if (!$this->jenjang_skl) {
            return null;
        }

        $display = $this->jenjang_skl;

        if ($this->jurusan_skl) {
            $display .= ' - ' . $this->jurusan_skl;
        }

        if ($this->institusi_skl) {
            $display .= ' (' . $this->institusi_skl . ')';
        }

        return $display;
    }

    /**
     * Scope a query to only include active contracts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('sts_srt_ktr', 'AKTIF');
    }

    /**
     * Scope a query to only include expired contracts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('tgl_akhir_ktr')
            ->whereRaw('STR_TO_DATE(tgl_akhir_ktr, "%Y-%m-%d") < CURDATE()');
    }

    /**
     * Scope a query to only include contracts expiring soon.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereNotNull('tgl_akhir_ktr')
            ->whereRaw('STR_TO_DATE(tgl_akhir_ktr, "%Y-%m-%d") BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)', [$days]);
    }

    /**
     * Scope a query to filter by contract type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $contractTypeId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByContractType($query, $contractTypeId)
    {
        return $query->where('id_ktr', $contractTypeId);
    }

    /**
     * Scope a query to filter by company.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $companyId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCompany($query, $companyId)
    {
        return $query->where('id_prsh', $companyId);
    }

    /**
     * Scope a query to filter by department.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $departmentId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('id_departemen', $departmentId);
    }

    /**
     * Scope a query to filter by work area.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $workAreaId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByWorkArea($query, $workAreaId)
    {
        return $query->where('id_wilker', $workAreaId);
    }

    /**
     * Scope a query to filter by education level.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $educationLevel
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByEducationLevel($query, $educationLevel)
    {
        return $query->where('jenjang_skl', $educationLevel);
    }

    /**
     * Scope a query to search by employee data.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('no_srt_ktr', 'like', '%' . $search . '%')
                ->orWhere('id_pendidikan', 'like', '%' . $search . '%')
                ->orWhere('id_karir', 'like', '%' . $search . '%')
                ->orWhereHas('karyawan', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('nrk', 'like', '%' . $search . '%')
                        ->orWhere('nik', 'like', '%' . $search . '%');
                });
        });
    }

    /**
     * Scope to sort by contract start date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortByStartDate($query, $direction = 'desc')
    {
        return $query->orderBy('tgl_awl_ktr', $direction);
    }

    /**
     * Scope to sort by contract end date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortByEndDate($query, $direction = 'desc')
    {
        return $query->orderBy('tgl_akhir_ktr', $direction);
    }
    public function getContractReminderStatusAttribute()
    {
        if (!$this->tgl_pgt_ktr) {
            return null;
        }

        try {
            $reminderDate = \Carbon\Carbon::parse($this->tgl_pgt_ktr);
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
     * Get contract priority for sorting (similar to dokLegal)
     */
    public function getContractPriorityAttribute()
    {
        // Check contract status first (highest priority for inactive contracts)
        if (in_array($this->sts_srt_ktr, ['NON-AKTIF', 'EXPIRED'])) {
            return 5; // Lowest priority (gray)
        }

        // Check if contract is expired based on end date
        if ($this->tgl_akhir_ktr) {
            try {
                $endDate = \Carbon\Carbon::parse($this->tgl_akhir_ktr);
                $today = \Carbon\Carbon::now()->startOf('day');

                if ($endDate->isBefore($today)) {
                    return 1; // Highest priority (red - expired)
                }
            } catch (\Exception $e) {
                // Handle invalid date format
            }
        }

        // Check reminder status
        $reminderStatus = $this->contract_reminder_status;
        if ($reminderStatus) {
            return $reminderStatus['priority'];
        }

        // Check end date for warning (within 30 days)
        if ($this->tgl_akhir_ktr) {
            try {
                $endDate = \Carbon\Carbon::parse($this->tgl_akhir_ktr);
                $today = \Carbon\Carbon::now()->startOf('day');
                $diffDays = $endDate->diffInDays($today, false);

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
     * Get contract highlight class for row styling
     */
    public function getContractHighlightClassAttribute()
    {
        $priority = $this->contract_priority;

        switch ($priority) {
            case 1:
                return 'highlight-red'; // Expired/overdue
            case 2:
                return 'highlight-yellow'; // Urgent (within 7 days or end date within 30 days)
            case 3:
                return 'highlight-orange'; // Warning (within 30 days)
            case 5:
                return 'highlight-gray'; // Inactive contracts
            default:
                return ''; // No highlighting
        }
    }

    /**
     * Get formatted reminder text with badge class
     */
    public function getFormattedReminderTextAttribute()
    {
        $reminderStatus = $this->contract_reminder_status;

        if ($reminderStatus) {
            return [
                'text' => $reminderStatus['message'],
                'class' => 'bg-' . $reminderStatus['class']
            ];
        }

        // Fallback to end date if no reminder date
        if ($this->tgl_akhir_ktr) {
            try {
                $endDate = \Carbon\Carbon::parse($this->tgl_akhir_ktr);
                $today = \Carbon\Carbon::now()->startOf('day');
                $diffDays = $endDate->diffInDays($today, false);

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
     * Scope untuk contract yang akan expired berdasarkan reminder date
     */
    public function scopeReminderExpiringSoon($query, $days = 30)
    {
        return $query->whereNotNull('tgl_pgt_ktr')
            ->whereRaw('STR_TO_DATE(tgl_pgt_ktr, "%Y-%m-%d") BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)', [$days]);
    }

    /**
     * Scope untuk contract yang reminder date sudah lewat
     */
    public function scopeReminderOverdue($query)
    {
        return $query->whereNotNull('tgl_pgt_ktr')
            ->whereRaw('STR_TO_DATE(tgl_pgt_ktr, "%Y-%m-%d") < CURDATE()');
    }

    /**
     * Scope untuk mengurutkan berdasarkan prioritas contract
     */
    public function scopeOrderByPriority($query)
    {
        return $query->selectRaw('
        *,
        CASE
            WHEN sts_srt_ktr IN ("NON-AKTIF", "EXPIRED") THEN 5
            WHEN tgl_akhir_ktr IS NOT NULL AND STR_TO_DATE(tgl_akhir_ktr, "%Y-%m-%d") < CURDATE() THEN 1
            WHEN tgl_pgt_ktr IS NOT NULL AND STR_TO_DATE(tgl_pgt_ktr, "%Y-%m-%d") < CURDATE() THEN 1
            WHEN tgl_pgt_ktr IS NOT NULL AND STR_TO_DATE(tgl_pgt_ktr, "%Y-%m-%d") BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN 2
            WHEN tgl_pgt_ktr IS NOT NULL AND STR_TO_DATE(tgl_pgt_ktr, "%Y-%m-%d") BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 3
            WHEN tgl_akhir_ktr IS NOT NULL AND STR_TO_DATE(tgl_akhir_ktr, "%Y-%m-%d") BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 2
            ELSE 4
        END as contract_priority
    ')->orderBy('contract_priority', 'asc');
    }

    /**
     * Get contract statistics for dashboard
     */
    public static function getContractStatistics()
    {
        $now = \Carbon\Carbon::now();

        return [
            'total' => self::count(),
            'active' => self::where('sts_srt_ktr', 'AKTIF')->count(),
            'expired' => self::where(function ($query) use ($now) {
                $query->whereNotNull('tgl_pgt_ktr')
                    ->where('tgl_pgt_ktr', '<', $now->format('Y-m-d'))
                    ->orWhere(function ($q) use ($now) {
                        $q->whereNotNull('tgl_akhir_ktr')
                            ->where('tgl_akhir_ktr', '<', $now->format('Y-m-d'));
                    });
            })->count(),
            'warning' => self::where(function ($query) use ($now) {
                $query->whereNotNull('tgl_pgt_ktr')
                    ->whereBetween('tgl_pgt_ktr', [
                        $now->format('Y-m-d'),
                        $now->addDays(30)->format('Y-m-d')
                    ])
                    ->orWhere(function ($q) use ($now) {
                        $q->whereNotNull('tgl_akhir_ktr')
                            ->whereBetween('tgl_akhir_ktr', [
                                $now->format('Y-m-d'),
                                $now->addDays(30)->format('Y-m-d')
                            ]);
                    });
            })->count(),
        ];
    }
}
