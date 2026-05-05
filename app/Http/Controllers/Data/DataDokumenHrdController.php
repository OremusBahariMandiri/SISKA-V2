<?php

namespace App\Http\Controllers\Data;

use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Data\DataDokumenHrd;
use App\Models\DataMaster\DokumenHrd;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\DataMaster\Perusahaan;

class DataDokumenHrdController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:data-dokumen-hrd')->only('index');
        $this->middleware('check.access:data-dokumen-hrd,detail')->only('show');
        $this->middleware('check.access:data-dokumen-hrd,tambah')->only('create', 'store');
        $this->middleware('check.access:data-dokumen-hrd,ubah')->only('edit', 'update');
        $this->middleware('check.access:data-dokumen-hrd,hapus')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = DataDokumenHrd::with([
            'dokumenHrd',
            'perusahaan',
            'creator',
            'updater'
        ]);

        // Filter Status
        if ($request->has('filter_status') && !empty($request->filter_status)) {
            $query->where('sts_dok', $request->filter_status);
        }

        // Filter Kategori Dokumen
        if ($request->has('filter_kategori') && !empty($request->filter_kategori)) {
            $query->whereHas('dokumenHrd', function ($q) use ($request) {
                $q->where('ktg_dok_hrd', $request->filter_kategori);
            });
        }

        // Filter Jenis Dokumen
        if ($request->has('filter_jenis') && !empty($request->filter_jenis)) {
            $query->whereHas('dokumenHrd', function ($q) use ($request) {
                $q->where('jns_dok_hrd', $request->filter_jenis);
            });
        }

        // Filter Nomor Dokumen
        if ($request->has('filter_no_dokumen') && !empty($request->filter_no_dokumen)) {
            $query->where('no_dok_hrd', 'LIKE', '%' . $request->filter_no_dokumen . '%');
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        $dataDokumenHrds = $query->orderBy('created_at', 'desc')->get();

        // Calculate expired and expiring documents count
        $today = \Carbon\Carbon::now()->startOf('day')->format('Y-m-d');

        $expiredDocumentsCount = DataDokumenHrd::where('sts_dok', 'AKTIF')
            ->whereNotNull('tgl_prt_dok')
            ->whereRaw("STR_TO_DATE(tgl_prt_dok, '%Y-%m-%d') <= ?", [$today])
            ->count();

        $expiringDocumentsCount = DataDokumenHrd::where('sts_dok', 'AKTIF')
            ->whereNotNull('tgl_prt_dok')
            ->whereRaw("STR_TO_DATE(tgl_prt_dok, '%Y-%m-%d') > ?", [$today])
            ->whereRaw("STR_TO_DATE(tgl_prt_dok, '%Y-%m-%d') <= DATE_ADD(?, INTERVAL 30 DAY)", [$today])
            ->count();

        // Get master data for filter dropdowns
        $dokumenTypes = DokumenHrd::orderBy('kode_dok_hrd', 'asc')->get();

        // Status options
        $statusOptions = [
            'AKTIF' => 'AKTIF',
            'NON-AKTIF' => 'NON-AKTIF',
        ];

        // Get unique categories
        $kategoriOptions = DokumenHrd::select('ktg_dok_hrd')
            ->distinct()
            ->orderBy('ktg_dok_hrd')
            ->pluck('ktg_dok_hrd', 'ktg_dok_hrd');

        // Get unique jenis
        $jenisOptions = DokumenHrd::select('jns_dok_hrd')
            ->distinct()
            ->orderBy('jns_dok_hrd')
            ->pluck('jns_dok_hrd', 'jns_dok_hrd');

        // Get user permissions
        $userPermissions = [];
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->is_admin) {
                $userPermissions = [
                    'tambah' => true,
                    'ubah' => true,
                    'hapus' => true,
                    'download' => true,
                    'detail' => true,
                ];
            } else {
                $access = $user->userAccess()->where('menu_acs', 'data-dokumen-hrd')->first();
                if ($access) {
                    $userPermissions = [
                        'tambah' => (bool)$access->tambah_acs,
                        'ubah' => (bool)$access->ubah_acs,
                        'hapus' => (bool)$access->hapus_acs,
                        'download' => (bool)$access->download_acs,
                        'detail' => (bool)$access->detail_acs,
                    ];
                }
            }
        }

        // Store current filters
        $currentFilters = [
            'status' => $request->filter_status ?? '',
            'kategori' => $request->filter_kategori ?? '',
            'jenis' => $request->filter_jenis ?? '',
            'no_dokumen' => $request->filter_no_dokumen ?? '',
        ];

        return view('data.data-dokumen-hrd.index', compact(
            'dataDokumenHrds',
            'userPermissions',
            'dokumenTypes',
            'statusOptions',
            'kategoriOptions',
            'jenisOptions',
            'expiredDocumentsCount',
            'expiringDocumentsCount',
            'currentFilters'
        ));
    }

    public function create()
    {
        $dokumenTypes = DokumenHrd::select(
            'id',
            'ktg_dok_hrd',
            'jns_dok_hrd',
            'kode_dok_hrd'
        )
            ->orderBy('kode_dok_hrd')
            ->get();

        // grouping kategori → jenis
        $grouped = $dokumenTypes->groupBy('ktg_dok_hrd');

        // jika ada kebutuhan id baru
        $newId = DokumenHrd::max('id') + 1;

        // Tambahkan data perusahaan
        $perusahaans = Perusahaan::orderBy('nama_prs1', 'asc')->get();

        return view('data.data-dokumen-hrd.create', compact(
            'dokumenTypes',
            'grouped',
            'newId',
            'perusahaans'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dokumen_hrd' => 'required|exists:106_dm_dok_hrd,id',
            'id_perusahaan' => 'nullable|exists:101_dm_perusahaan,id',
            'no_dok_hrd' => 'nullable|string|max:100|unique:206_dm_data_dok_hrd,no_dok_hrd',
            'tgl_ttd' => 'nullable|date',
            'jns_msb_dok' => 'nullable|string|max:50',
            'tgl_akr_dok' => 'nullable|date|after_or_equal:tgl_ttd',
            'msb_dok' => 'nullable|integer|min:1',
            'tgl_prt_dok' => 'nullable|date|before_or_equal:tgl_akr_dok',
            'durasi_pgt' => 'nullable|integer',
            'ket_dok_hrd' => 'nullable|string|max:255',
            'catatan_dok_hrd' => 'nullable|string',
            'sts_dok' => 'required|in:AKTIF,NON-AKTIF,EXPIRED,PENDING',
            'tgl_dok_na' => 'nullable|date',
            'ket_dok_na' => 'nullable|string|max:255',
            'file_dok' => 'nullable|file|mimes:pdf',
            'file_dok_2' => 'nullable|file|mimes:doc,docx,xls,xlsx',
        ]);

        // Generate ID
        $id_kode = empty($request->id_kode)
            ? $this->generateId('206', '206_dm_data_dok_hrd')
            : $request->id_kode;

        // Handle file_dok upload (PDF only)
        $fileDok = null;
        if ($request->hasFile('file_dok')) {
            $file = $request->file('file_dok');
            $dokumenType = DokumenHrd::findOrFail($request->id_dokumen_hrd);

            $jenisDokumen = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $dokumenType->jns_dok_hrd ?? 'Dokumen'));
            $noDokumen = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $request->no_dok_hrd ?? 'NoDoc'));
            $extension = $file->getClientOriginalExtension();

            $fileName = $jenisDokumen . '_' . $noDokumen . '_PDF_' . time() . '.' . $extension;

            $file->storeAs('public/dokumen/dokumen-hrd', $fileName);
            $fileDok = 'dokumen/dokumen-hrd/' . $fileName;
        }

        // Handle file_dok_2 upload (DOC/DOCX/XLS/XLSX only)
        $fileDok2 = null;
        if ($request->hasFile('file_dok_2')) {
            $file2 = $request->file('file_dok_2');
            $dokumenType = DokumenHrd::findOrFail($request->id_dokumen_hrd);

            $jenisDokumen = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $dokumenType->jns_dok_hrd ?? 'Dokumen'));
            $noDokumen = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $request->no_dok_hrd ?? 'NoDoc'));
            $extension = $file2->getClientOriginalExtension();

            $fileName2 = $jenisDokumen . '_' . $noDokumen . '_DOC_' . time() . '.' . $extension;

            $file2->storeAs('public/dokumen/dokumen-hrd', $fileName2);
            $fileDok2 = 'dokumen/dokumen-hrd/' . $fileName2;
        }

        // Calculate masa berlaku (months)
        $msb_dok = $request->msb_dok;
        if (!$msb_dok && $request->tgl_ttd && $request->tgl_akr_dok) {
            $signatureDate = \Carbon\Carbon::parse($request->tgl_ttd);
            $expiryDate = \Carbon\Carbon::parse($request->tgl_akr_dok);
            $msb_dok = $signatureDate->diffInMonths($expiryDate);
        }

        // Calculate durasi peringatan (days)
        $durasi_pgt = $request->durasi_pgt;
        if (!$durasi_pgt && $request->tgl_prt_dok) {
            $today = \Carbon\Carbon::now()->startOfDay();
            $reminderDate = \Carbon\Carbon::parse($request->tgl_prt_dok)->startOfDay();
            $durasi_pgt = $today->diffInDays($reminderDate, false);
        }

        DataDokumenHrd::create([
            'id_kode' => $id_kode,
            'id_dokumen_hrd' => $request->id_dokumen_hrd,
            'id_perusahaan' => $request->id_perusahaan,
            'no_dok_hrd' => $request->no_dok_hrd,
            'tgl_ttd' => $request->tgl_ttd,
            'jns_msb_dok' => $request->jns_msb_dok,
            'tgl_akr_dok' => $request->tgl_akr_dok,
            'msb_dok' => $msb_dok,
            'tgl_prt_dok' => $request->tgl_prt_dok,
            'durasi_pgt' => $durasi_pgt,
            'file_dok' => $fileDok,
            'file_dok_2' => $fileDok2,
            'sts_dok' => $request->sts_dok,
            'ket_dok_hrd' => $request->ket_dok_hrd,
            'catatan_dok_hrd' => $request->catatan_dok_hrd,
            'tgl_dok_na' => $request->tgl_dok_na,
            'ket_dok_na' => $request->ket_dok_na,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('data-dokumen-hrd.index')
            ->with('success', 'Data dokumen HRD berhasil dibuat.');
    }

    public function show($id)
    {
        $dataDokumenHrd = DataDokumenHrd::with([
            'dokumenHrd',
            'creator',
            'updater'
        ])->findOrFail($id);

        // Get user permissions
        $userPermissions = [];
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->is_admin) {
                $userPermissions = [
                    'tambah' => true,
                    'ubah' => true,
                    'hapus' => true,
                    'download' => true,
                    'detail' => true,
                ];
            } else {
                $access = $user->userAccess()->where('menu_acs', 'data-dokumen-hrd')->first();
                if ($access) {
                    $userPermissions = [
                        'tambah' => (bool)$access->tambah_acs,
                        'ubah' => (bool)$access->ubah_acs,
                        'hapus' => (bool)$access->hapus_acs,
                        'download' => (bool)$access->download_acs,
                        'detail' => (bool)$access->detail_acs,
                    ];
                }
            }
        }

        return view('data.data-dokumen-hrd.show', compact('dataDokumenHrd', 'userPermissions'));
    }

    public function edit($id)
    {
        $dataDokumenHrd = DataDokumenHrd::with([
            'dokumenHrd',
            'perusahaan',
            'creator',
            'updater'
        ])->findOrFail($id);

        $dokumenTypes = DokumenHrd::select(
            'id',
            'ktg_dok_hrd',
            'jns_dok_hrd',
            'kode_dok_hrd'
        )
            ->orderBy('kode_dok_hrd')
            ->get();

        $grouped = $dokumenTypes->groupBy('ktg_dok_hrd');

        $perusahaans = Perusahaan::orderBy('nama_prs1', 'asc')->get();

        // permissions
        $userPermissions = [];
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->is_admin) {
                $userPermissions = [
                    'tambah' => true,
                    'ubah' => true,
                    'hapus' => true,
                    'download' => true,
                    'detail' => true,
                ];
            } else {
                $access = $user->userAccess()->where('menu_acs', 'data-dokumen-hrd')->first();
                if ($access) {
                    $userPermissions = [
                        'tambah' => (bool)$access->tambah_acs,
                        'ubah' => (bool)$access->ubah_acs,
                        'hapus' => (bool)$access->hapus_acs,
                        'download' => (bool)$access->download_acs,
                        'detail' => (bool)$access->detail_acs,
                    ];
                }
            }
        }

        return view('data.data-dokumen-hrd.edit', compact(
            'dataDokumenHrd',
            'dokumenTypes',
            'perusahaans',
            'grouped',
            'userPermissions'
        ));
    }

    public function update(Request $request, $id)
    {
        $dataDokumenHrd = DataDokumenHrd::findOrFail($id);

        $request->validate([
            'id_dokumen_hrd' => 'required|exists:106_dm_dok_hrd,id',
            'id_perusahaan' => 'nullable|exists:101_dm_perusahaan,id',
            'no_dok_hrd' => 'nullable|string|max:100|unique:206_dm_data_dok_hrd,no_dok_hrd,' . $id,
            'tgl_ttd' => 'nullable|date',
            'jns_msb_dok' => 'nullable|string|max:50',
            'tgl_akr_dok' => 'nullable|date|after_or_equal:tgl_ttd',
            'msb_dok' => 'nullable|integer|min:1',
            'tgl_prt_dok' => 'nullable|date|before_or_equal:tgl_akr_dok',
            'durasi_pgt' => 'nullable|integer',
            'ket_dok_hrd' => 'nullable|string|max:255',
            'catatan_dok_hrd' => 'nullable|string',
            'sts_dok' => 'required|in:AKTIF,NON-AKTIF,EXPIRED,PENDING',
            'tgl_dok_na' => 'nullable|date',
            'ket_dok_na' => 'nullable|string|max:255',
            'file_dok' => 'nullable|file|mimes:pdf',
            'file_dok_2' => 'nullable|file|mimes:doc,docx,xls,xlsx',
        ]);

        // Handle file_dok upload (PDF only)
        $fileDok = $dataDokumenHrd->file_dok;
        if ($request->hasFile('file_dok')) {
            // Delete old file
            if ($dataDokumenHrd->file_dok && Storage::exists('public/' . $dataDokumenHrd->file_dok)) {
                Storage::delete('public/' . $dataDokumenHrd->file_dok);
            }

            $file = $request->file('file_dok');
            $dokumenType = DokumenHrd::findOrFail($request->id_dokumen_hrd);

            $jenisDokumen = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $dokumenType->jns_dok_hrd ?? 'Dokumen'));
            $noDokumen = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $request->no_dok_hrd ?? 'NoDoc'));
            $extension = $file->getClientOriginalExtension();

            $fileName = $jenisDokumen . '_' . $noDokumen . '_PDF_' . time() . '.' . $extension;

            $file->storeAs('public/dokumen/dokumen-hrd', $fileName);
            $fileDok = 'dokumen/dokumen-hrd/' . $fileName;
        }

        // Handle file_dok_2 upload (DOC/DOCX/XLS/XLSX only)
        $fileDok2 = $dataDokumenHrd->file_dok_2;
        if ($request->hasFile('file_dok_2')) {
            // Delete old file
            if ($dataDokumenHrd->file_dok_2 && Storage::exists('public/' . $dataDokumenHrd->file_dok_2)) {
                Storage::delete('public/' . $dataDokumenHrd->file_dok_2);
            }

            $file2 = $request->file('file_dok_2');
            $dokumenType = DokumenHrd::findOrFail($request->id_dokumen_hrd);

            $jenisDokumen = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $dokumenType->jns_dok_hrd ?? 'Dokumen'));
            $noDokumen = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $request->no_dok_hrd ?? 'NoDoc'));
            $extension = $file2->getClientOriginalExtension();

            $fileName2 = $jenisDokumen . '_' . $noDokumen . '_DOC_' . time() . '.' . $extension;

            $file2->storeAs('public/dokumen/dokumen-hrd', $fileName2);
            $fileDok2 = 'dokumen/dokumen-hrd/' . $fileName2;
        }

        // Calculate masa berlaku (months)
        $msb_dok = $request->msb_dok;
        if (!$msb_dok && $request->tgl_ttd && $request->tgl_akr_dok) {
            $signatureDate = \Carbon\Carbon::parse($request->tgl_ttd);
            $expiryDate = \Carbon\Carbon::parse($request->tgl_akr_dok);
            $msb_dok = $signatureDate->diffInMonths($expiryDate);
        }

        // Calculate durasi peringatan (days)
        $durasi_pgt = $request->durasi_pgt;
        if (!$durasi_pgt && $request->tgl_prt_dok) {
            $today = \Carbon\Carbon::now()->startOfDay();
            $reminderDate = \Carbon\Carbon::parse($request->tgl_prt_dok)->startOfDay();
            $durasi_pgt = $today->diffInDays($reminderDate, false);
        }

        $dataDokumenHrd->update([
            'id_dokumen_hrd' => $request->id_dokumen_hrd,
            'id_perusahaan' => $request->id_perusahaan,
            'no_dok_hrd' => $request->no_dok_hrd,
            'tgl_ttd' => $request->tgl_ttd,
            'jns_msb_dok' => $request->jns_msb_dok,
            'tgl_akr_dok' => $request->tgl_akr_dok,
            'msb_dok' => $msb_dok,
            'tgl_prt_dok' => $request->tgl_prt_dok,
            'durasi_pgt' => $durasi_pgt,
            'file_dok' => $fileDok,
            'file_dok_2' => $fileDok2,
            'sts_dok' => $request->sts_dok,
            'ket_dok_hrd' => $request->ket_dok_hrd,
            'catatan_dok_hrd' => $request->catatan_dok_hrd,
            'tgl_dok_na' => $request->tgl_dok_na,
            'ket_dok_na' => $request->ket_dok_na,
            'updated_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('data-dokumen-hrd.index', $dataDokumenHrd->id)
            ->with('success', 'Data dokumen HRD berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $dataDokumenHrd = DataDokumenHrd::findOrFail($id);

            // Delete file_dok if exists
            if ($dataDokumenHrd->file_dok && Storage::exists('public/' . $dataDokumenHrd->file_dok)) {
                Storage::delete('public/' . $dataDokumenHrd->file_dok);
            }

            // Delete file_dok_2 if exists
            if ($dataDokumenHrd->file_dok_2 && Storage::exists('public/' . $dataDokumenHrd->file_dok_2)) {
                Storage::delete('public/' . $dataDokumenHrd->file_dok_2);
            }

            $dataDokumenHrd->delete();

            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data dokumen HRD berhasil dihapus.'
                ]);
            }

            return redirect()->route('data-dokumen-hrd.index')
                ->with('success', 'Data dokumen HRD berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('data-dokumen-hrd.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Get documents expiring soon for dashboard/notifications
     */
    public function getExpiringDocuments(Request $request)
    {
        $days = $request->get('days', 30);

        $expiring = DataDokumenHrd::with(['dokumenHrd'])
            ->expiringSoon($days)
            ->where('sts_dok', 'AKTIF')
            ->orderBy('tgl_akr_dok', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $expiring->count(),
            'data' => $expiring->map(function ($dokumen) {
                return [
                    'id' => $dokumen->id,
                    'document_type' => $dokumen->dokumenHrd->jns_dok_hrd ?? 'N/A',
                    'document_number' => $dokumen->no_dok_hrd ?? 'N/A',
                    'signature_date' => $dokumen->tgl_ttd,
                    'expiry_date' => $dokumen->tgl_akr_dok,
                    'days_until_expiry' => $dokumen->days_until_expiry,
                    'is_active' => $dokumen->is_active,
                    'is_expired' => $dokumen->is_expired,
                ];
            })
        ]);
    }
}