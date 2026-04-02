<?php

namespace App\Models\Data;

use App\Models\DataMaster\Departemen;
use App\Models\DataMaster\DokumenKaryawan;
use App\Models\DataMaster\WilayahKerja;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection;

class DataJenjangKarir extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '204_dm_data_jenjang_karir';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_jenjang_karir',
        'id_karyawan',
        'id_dokumen_karyawan',
        'no_jk',
        'tgl_ttd',
        'id_departemen',
        'id_wilayah_kerja',
        'tugas',
        'file_dokumen',
        'id_kontrak_kerja',
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the employee that owns the career record.
     */
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(DataKaryawan::class, 'id_karyawan', 'id');
    }

    /**
     * Get the employee document.
     */
    public function dokumenKaryawan(): BelongsTo
    {
        return $this->belongsTo(DokumenKaryawan::class, 'id_dokumen_karyawan', 'id');
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
        return $this->belongsTo(WilayahKerja::class, 'id_wilayah_kerja', 'id');
    }

    /**
     * Get all career records for the same employee
     */
    public function allEmployeeCareers(): Collection
    {
        return self::where('id_karyawan', $this->id_karyawan)
            ->with(['departemen', 'wilayahKerja', 'dokumenKaryawan'])
            ->orderBy('tgl_ttd', 'desc')
            ->get();
    }

    /**
     * Get latest career record for employee
     */
    public function getLatestCareerAttribute()
    {
        return self::where('id_karyawan', $this->id_karyawan)
            ->with(['departemen', 'wilayahKerja', 'dokumenKaryawan'])
            ->orderBy('tgl_ttd', 'desc')
            ->first();
    }

    /**
     * Get career position display.
     *
     * @return string|null
     */
    public function getCareerDisplayAttribute(): ?string
    {
        $display = [];

        if ($this->departemen) {
            $display[] = $this->departemen->nama_departemen ?? 'Departemen';
        }

        if ($this->wilayahKerja) {
            $display[] = $this->wilayahKerja->nama_wilker ?? 'Wilayah Kerja';
        }

        return !empty($display) ? implode(' - ', $display) : null;
    }

    /**
     * Scope to get unique employees (group by id_karyawan)
     */
    public function scopeUniqueEmployees($query)
    {
        return $query->select('*')
            ->whereIn('id', function ($subquery) {
                $subquery->select(\DB::raw('MAX(id)'))
                    ->from('204_dm_data_jenjang_karir')
                    ->groupBy('id_karyawan');
            });
    }

    /**
     * Scope to filter by department.
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
     * Scope to filter by work area.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $workAreaId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByWorkArea($query, $workAreaId)
    {
        return $query->where('id_wilayah_kerja', $workAreaId);
    }

    /**
     * Scope to search by employee data or career number.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('no_jk', 'like', '%' . $search . '%')
                ->orWhere('id_jenjang_karir', 'like', '%' . $search . '%')
                ->orWhereHas('karyawan', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('nrk', 'like', '%' . $search . '%')
                        ->orWhere('nik', 'like', '%' . $search . '%');
                });
        });
    }

    /**
     * Scope to sort by signing date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortBySigningDate($query, $direction = 'desc')
    {
        return $query->orderBy('tgl_ttd', $direction);
    }

    /**
     * Scope to filter by date range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $startDate
     * @param  string  $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tgl_ttd', [$startDate, $endDate]);
    }

    /**
     * Get career statistics for dashboard
     */
    public static function getCareerStatistics()
    {
        $now = \Carbon\Carbon::now();
        $thisYear = $now->year;
        $thisMonth = $now->month;

        return [
            'total' => self::uniqueEmployees()->count(),
            'this_year' => self::whereYear('tgl_ttd', $thisYear)->count(),
            'this_month' => self::whereYear('tgl_ttd', $thisYear)
                ->whereMonth('tgl_ttd', $thisMonth)
                ->count(),
            'by_department' => self::selectRaw('id_departemen, COUNT(*) as total')
                ->whereNotNull('id_departemen')
                ->groupBy('id_departemen')
                ->with('departemen')
                ->get()
                ->map(function ($item) {
                    return [
                        'department' => $item->departemen->nama_departemen ?? 'Unknown',
                        'total' => $item->total
                    ];
                }),
            'by_work_area' => self::selectRaw('id_wilayah_kerja, COUNT(*) as total')
                ->whereNotNull('id_wilayah_kerja')
                ->groupBy('id_wilayah_kerja')
                ->with('wilayahKerja')
                ->get()
                ->map(function ($item) {
                    return [
                        'work_area' => $item->wilayahKerja->nama_wilker ?? 'Unknown',
                        'total' => $item->total
                    ];
                }),
        ];
    }

    /**
     * Get career progression for an employee
     *
     * @return Collection
     */
    public function getCareerProgressionAttribute(): Collection
    {
        return self::where('id_karyawan', $this->id_karyawan)
            ->with(['departemen', 'wilayahKerja'])
            ->orderBy('tgl_ttd', 'asc')
            ->get();
    }

    /**
     * Check if employee has document file
     *
     * @return bool
     */
    public function hasDocumentFile(): bool
    {
        return !empty($this->file_dokumen) && file_exists(storage_path('app/public/' . $this->file_dokumen));
    }

    /**
     * Get document file path
     *
     * @return string|null
     */
    public function getDocumentPathAttribute(): ?string
    {
        if ($this->hasDocumentFile()) {
            return asset('storage/' . $this->file_dokumen);
        }

        return null;
    }

    /**
     * Scope to get records with documents
     */
    public function scopeWithDocuments($query)
    {
        return $query->whereNotNull('file_dokumen');
    }

    /**
     * Scope to get recent career changes
     */
    public function scopeRecentChanges($query, $days = 30)
    {
        return $query->where('tgl_ttd', '>=', now()->subDays($days));
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
