@extends('layouts.app')

@section('title', 'Data Dokumen')

@section('content')
    <div class="container-fluid dataDokumenPage">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-alt me-2"></i>Data Dokumen</span>
                        <div>
                            <button type="button" class="btn btn-light me-2" id="summaryButton">
                                <i class="fas fa-chart-pie me-1"></i> Ringkasan
                            </button>
                            <button type="button" class="btn btn-light me-2" id="filterButton">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <button type="button" class="btn btn-light me-2" id="exportButton">
                                <i class="fas fa-download me-1"></i> Export
                            </button>
                            @if (auth()->user()->is_admin || ($userPermissions['tambah'] ?? false))
                                <a href="{{ route('data-dokumen.create') }}" class="btn btn-light">
                                    <i class="fas fa-plus-circle me-1"></i> Tambah
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3 document-status-summary">
                            <span id="expiredDocumentsBadge" class="badge bg-danger me-2" style="font-size: 0.9rem;">
                                <i class="fas fa-exclamation-circle me-1"></i> Dokumen Expired :
                                <span id="expiredDocumentsCount">{{ $expiredDocumentsCount }}</span>
                            </span>
                            <span id="warningDocumentsBadge" class="badge text-dark me-2"
                                style="font-size: 0.9rem; background-color:#ffff66">
                                <i class="fas fa-exclamation-triangle me-1"></i>Dokumen Akan Expired :
                                <span id="warningDocumentsCount">{{ $expiringDocumentsCount }}</span>
                            </span>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Active Filter Display -->
                        @if (
                            !empty($currentFilters['status']) ||
                                !empty($currentFilters['jenis_dokumen']) ||
                                !empty($currentFilters['nama']) ||
                                !empty($currentFilters['nrk']) ||
                                !empty($currentFilters['no_dokumen']) ||
                                !empty($currentFilters['departemen']) ||
                                !empty($currentFilters['jabatan']) ||
                                !empty($currentFilters['perusahaan']) ||
                                !empty($currentFilters['jenis_kelamin']) ||
                                !empty($currentFilters['wilker']) ||
                                !empty($currentFilters['unit_kerja']))
                            <div class="alert alert-info" role="alert" id="filterActiveAlert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Filter Aktif:</strong>

                                @if (!empty($currentFilters['status']))
                                    Status: <span class="badge bg-primary">{{ $currentFilters['status'] }}</span>
                                @endif

                                @if (!empty($currentFilters['jenis_dokumen']))
                                    @php
                                        $selectedDokumen = $dokumenTypes
                                            ->where('id', $currentFilters['jenis_dokumen'])
                                            ->first();
                                    @endphp
                                    @if ($selectedDokumen)
                                        Jenis Dokumen: <span
                                            class="badge bg-success">{{ $selectedDokumen->nama_dok }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['perusahaan']))
                                    @php
                                        $selectedPerusahaan = $perusahaans
                                            ->where('id', $currentFilters['perusahaan'])
                                            ->first();
                                    @endphp
                                    @if ($selectedPerusahaan)
                                        Perusahaan: <span
                                            class="badge bg-warning">{{ $selectedPerusahaan->nama_prs2 }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['nama']))
                                    Nama: <span class="badge bg-primary">{{ $currentFilters['nama'] }}</span>
                                @endif

                                @if (!empty($currentFilters['nrk']))
                                    NRK: <span class="badge bg-primary">{{ $currentFilters['nrk'] }}</span>
                                @endif

                                @if (!empty($currentFilters['no_dokumen']))
                                    No Dokumen: <span class="badge bg-info">{{ $currentFilters['no_dokumen'] }}</span>
                                @endif

                                @if (!empty($currentFilters['departemen']))
                                    @php
                                        $selectedDepartemen = $departemenOptions
                                            ->where('id', $currentFilters['departemen'])
                                            ->first();
                                    @endphp
                                    @if ($selectedDepartemen)
                                        Departemen: <span class="badge bg-info">{{ $selectedDepartemen->nama_dep }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['jabatan']))
                                    @php
                                        $selectedJabatan = $jabatanOptions
                                            ->where('id', $currentFilters['jabatan'])
                                            ->first();
                                    @endphp
                                    @if ($selectedJabatan)
                                        Jabatan: <span class="badge bg-warning">{{ $selectedJabatan->nama_jbt }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['jenis_kelamin']))
                                    Jenis Kelamin: <span
                                        class="badge bg-dark">{{ $jenisKelaminOptions[$currentFilters['jenis_kelamin']] ?? $currentFilters['jenis_kelamin'] }}</span>
                                @endif

                                @if (!empty($currentFilters['wilker']))
                                    Wilayah Kerja: <span class="badge bg-danger">{{ $currentFilters['wilker'] }}</span>
                                @endif

                                @if (!empty($currentFilters['unit_kerja']))
                                    @php
                                        $selectedUnitKerja = $unitKerjaOptions
                                            ->where('id', $currentFilters['unit_kerja'])
                                            ->first();
                                    @endphp
                                    @if ($selectedUnitKerja)
                                        Unit Kerja: <span class="badge bg-danger">{{ $selectedUnitKerja->area_krj }}</span>
                                    @endif
                                @endif

                                <a href="{{ route('data-dokumen.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Reset Filter
                                </a>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="dataDokumenTable" class="table table-bordered table-striped data-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="1%" class="text-center">NO</th>
                                        <th width="3%" class="text-center">NRK</th>
                                        <th width="5%" class="text-center">NAMA</th>
                                        <th width="2%" class="text-center">UMR</th>
                                        <th width="1%" class="text-center">SEX</th>
                                        <th width="2%" class="text-center">FOTO</th>
                                        <th width="3%" class="text-center">TGL MSK</th>
                                        <th width="2%" class="text-center">MKR</th>
                                        <th width="2%" class="text-center">SKL</th>
                                        <th width="2%" class="text-center">DEP</th>
                                        <th width="2%" class="text-center">JBT</th>
                                        <th width="2%" class="text-center">WLK</th>
                                        <th width="2%" class="text-center">PRS</th>
                                        <th width="2%" class="text-center">JML</th>
                                        <th width="3%" class="text-center no-wrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataDokumens as $dokumen)
                                        @php
                                            // Get related data
                                            $karyawan = $dokumen->karyawan;
                                            $departemen = $karyawan ? $karyawan->departemenRelation : null;
                                            $wilayah = $karyawan ? $karyawan->wilayahKerjaRelation : null;
                                            $perusahaan = $karyawan ? $karyawan->perusahaanRelation : null;

                                            // Calculate age
                                            $age = null;
                                            if ($karyawan && $karyawan->tgl_lahir) {
                                                $birthDate = \Carbon\Carbon::parse($karyawan->tgl_lahir);
                                                $age = $birthDate->age;
                                            }

                                            // Calculate work duration
                                            $workDuration = null;
                                            if ($karyawan && $karyawan->tgl_masuk) {
                                                $startWork = \Carbon\Carbon::parse($karyawan->tgl_masuk);
                                                $workDurationYears = $startWork->diffInYears(\Carbon\Carbon::now());
                                                $workDurationMonths =
                                                    $startWork->diffInMonths(\Carbon\Carbon::now()) % 12;
                                                $workDuration = $workDurationYears . 'th';
                                                if ($workDurationMonths > 0) {
                                                    $workDuration .= ' ' . $workDurationMonths . 'bl';
                                                }
                                            }
                                        @endphp
                                        <tr data-employee-id="{{ $dokumen->id_data_kry }}"
                                            data-document-status="{{ $dokumen->sts_dok }}"
                                            data-tgl-peringatan="{{ $dokumen->tgl_pgt_dok }}"
                                            data-tgl-akhir="{{ $dokumen->tgl_akr_dok }}"
                                            data-jns-msb="{{ $dokumen->jns_msb_dok }}">

                                            <!-- NO -->
                                            <td class="text-center">{{ $loop->iteration }}</td>

                                            <!-- NRK -->
                                            <td>
                                                <span class="fw-bold">{{ $karyawan->nrk ?? '-' }}</span></br>
                                                <span class="text-muted">{{ $karyawan->nik ?? '-' }}</span>
                                            </td>

                                            <!-- NAMA -->
                                            <td>
                                                <div class="fw-bold">{{ $karyawan->nama ?? '-' }}</div>
                                            </td>


                                            <!-- UMUR -->
                                            <td class="text-center">
                                                @if ($age)
                                                    <span><small>{{ $age }} th</small></span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- JK (Jenis Kelamin) -->
                                            <td class="text-center">
                                                @if ($karyawan && $karyawan->sex)
                                                    <span>
                                                        {{ $karyawan->sex == 'LAKI-LAKI' ? 'L' : 'P' }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- FOTO -->
                                            <td class="text-center">
                                                @if ($karyawan && $karyawan->foto_dokumen)
                                                    @php
                                                        $fileExtension = pathinfo(
                                                            storage_path('app/public/' . $karyawan->foto_dokumen),
                                                            PATHINFO_EXTENSION,
                                                        );
                                                        $isImage = in_array(strtolower($fileExtension), [
                                                            'jpg',
                                                            'jpeg',
                                                            'png',
                                                            'gif',
                                                        ]);
                                                    @endphp

                                                    @if ($isImage)
                                                        <img src="{{ asset('storage/' . $karyawan->foto_dokumen) }}"
                                                            class="employee-photo rounded-circle"
                                                            alt="Foto {{ $karyawan->nama }}"
                                                            style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;">
                                                    @else
                                                        <div class="employee-photo-placeholder">
                                                            {{ substr($karyawan->nama, 0, 1) }}
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="employee-photo-placeholder">
                                                        {{ substr($karyawan->nama ?? 'U', 0, 1) }}
                                                    </div>
                                                @endif
                                            </td>

                                            <!-- TGL MASUK -->
                                            <td class="text-center">
                                                {{ $karyawan && $karyawan->tgl_masuk ? $karyawan->tgl_masuk->format('d-m-Y') : '-' }}
                                            </td>

                                            <!-- MASA KERJA -->
                                            <td class="text-center">
                                                @if ($workDuration)
                                                    <span>{{ $workDuration }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- PENDIDIKAN -->
                                            <td class="text-center">
                                                <span>{{ $karyawan->jenjang_skl ?? '-' }}</span>
                                            </td>

                                            <!-- DEPARTEMEN -->
                                            <td class="text-center">
                                                @if ($departemen)
                                                    <span>{{ $departemen->singkatan_dep }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- JABATAN -->
                                            <td class="text-center">
                                                <span>{{ $departemen->singkatan_jbt ?? '-' }}</span>
                                            </td>

                                            <!-- WILKER -->
                                            <td class="text-center">
                                                <span>{{ $wilayah->singkatan_wk ?? '-' }}</span>
                                            </td>

                                            <!-- PERUSAHAAN -->
                                            <td class="text-center">
                                                <span>{{ $perusahaan->nama_prs2 ?? '-' }}</span>
                                            </td>

                                            <td class="text-center">
                                                @php
                                                    $documentCount = $documentCounts[$dokumen->id_data_kry] ?? [
                                                        'total' => 0,
                                                        'active' => 0,
                                                        'non_active' => 0,
                                                    ];
                                                @endphp
                                                <div class="document-count-display">
                                                    <span class="badge bg-primary" title="Total Dokumen">
                                                        <i class="fas fa-file-alt me-1"></i>{{ $documentCount['total'] }}
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- AKSI -->
                                            <td class="text-center no-wrap">
                                                <div class="btn-group" role="group" aria-label="Actions">
                                                    @if (auth()->user()->is_admin || ($userPermissions['detail'] ?? false))
                                                        <a href="{{ route('data-dokumen.show', $dokumen->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                            title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->is_admin || ($userPermissions['ubah'] ?? false))
                                                        <a href="{{ route('data-dokumen.edit', $dokumen->id) }}"
                                                            class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                            title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal (tetap sama seperti sebelumnya) -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-filter me-2"></i>Filter Data Dokumen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="GET" action="{{ route('data-dokumen.index') }}">
                        <div class="row mb-3">
                            <!-- Filter sama seperti sebelumnya -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_status" class="form-label fw-bold">Status Dokumen</label>
                                    <div style="flex: 1">
                                        <select class="form-select select2" id="filter_status" name="filter_status">
                                            <option value="">Semua Status</option>
                                            @foreach ($statusOptions as $value => $label)
                                                <option value="{{ $value }}"
                                                    {{ $currentFilters['status'] == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_jabatan" class="form-label fw-bold">Jabatan</label>
                                    <div style="flex: 1">
                                        <select class="form-select select2" id="filter_jabatan" name="filter_jabatan">
                                            <option value="">Semua Jabatan</option>
                                            @foreach ($jabatanOptions as $jabatan)
                                                <option value="{{ $jabatan->id }}"
                                                    {{ $currentFilters['jabatan'] == $jabatan->id ? 'selected' : '' }}>
                                                    {{ $jabatan->nama_jbt }} @if ($jabatan->singkatan_jbt)
                                                        ({{ $jabatan->singkatan_jbt }})
                                                    @endif - {{ $jabatan->nama_dep }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_jenis_dokumen" class="form-label fw-bold">Jenis Dokumen</label>
                                    <div style="flex: 1">
                                        <select class="form-select select2" id="filter_jenis_dokumen"
                                            name="filter_jenis_dokumen">
                                            <option value="">Semua Jenis Dokumen</option>
                                            @foreach ($dokumenTypes as $dokumen)
                                                <option value="{{ $dokumen->id }}"
                                                    {{ $currentFilters['jenis_dokumen'] == $dokumen->id ? 'selected' : '' }}>
                                                    {{ $dokumen->nama_dok }} @if ($dokumen->singkatan_dok)
                                                        ({{ $dokumen->singkatan_dok }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_perusahaan" class="form-label fw-bold">Perusahaan</label>
                                    <div style="flex: 1">
                                        <select class="form-select select2" id="filter_perusahaan"
                                            name="filter_perusahaan">
                                            <option value="">Semua Perusahaan</option>
                                            @foreach ($perusahaans as $perusahaan)
                                                <option value="{{ $perusahaan->id }}"
                                                    {{ $currentFilters['perusahaan'] == $perusahaan->id ? 'selected' : '' }}>
                                                    {{ $perusahaan->nama_prs1 }} - {{ $perusahaan->nama_prs2 }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_wilker" class="form-label fw-bold">Wilayah Kerja</label>
                                    <div style="flex: 1">
                                        <select class="form-select select2" id="filter_wilker" name="filter_wilker">
                                            <option value="">Semua Wilayah Kerja</option>
                                            @foreach ($wilayahKerjaOptions as $wilker)
                                                <option value="{{ $wilker->wilayah_krj }}"
                                                    {{ $currentFilters['wilker'] == $wilker->wilayah_krj ? 'selected' : '' }}>
                                                    {{ $wilker->wilayah_krj }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_jenis_kelamin" class="form-label fw-bold">Jenis Kelamin</label>
                                    <div style="flex: 1">
                                        <select class="form-select select2" id="filter_jenis_kelamin"
                                            name="filter_jenis_kelamin">
                                            <option value="">Semua Jenis Kelamin</option>
                                            @foreach ($jenisKelaminOptions as $value => $label)
                                                <option value="{{ $value }}"
                                                    {{ $currentFilters['jenis_kelamin'] == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_unit_kerja" class="form-label fw-bold">Area Kerja</label>
                                    <div style="flex: 1">
                                        <select class="form-select select2" id="filter_unit_kerja"
                                            name="filter_unit_kerja">
                                            <option value="">Semua Area Kerja</option>
                                            @foreach ($unitKerjaOptions as $unitKerja)
                                                <option value="{{ $unitKerja->id }}"
                                                    {{ $currentFilters['unit_kerja'] == $unitKerja->id ? 'selected' : '' }}>
                                                    {{ $unitKerja->area_krj }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_nama" class="form-label fw-bold">Nama Karyawan</label>
                                    <input type="text" class="form-control" id="filter_nama" name="filter_nama"
                                        placeholder="Cari nama karyawan..." value="{{ $currentFilters['nama'] ?? '' }}">
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_departemen" class="form-label fw-bold">Departemen</label>
                                    <div style="flex: 1">
                                        <select class="form-select select2" id="filter_departemen"
                                            name="filter_departemen">
                                            <option value="">Semua Departemen</option>
                                            @foreach ($departemenOptions as $departemen)
                                                <option value="{{ $departemen->id }}"
                                                    {{ $currentFilters['departemen'] == $departemen->id ? 'selected' : '' }}>
                                                    {{ $departemen->nama_dep }} @if ($departemen->singkatan_dep)
                                                        ({{ $departemen->singkatan_dep }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_nrk" class="form-label fw-bold">NRK</label>
                                    <input type="text" class="form-control" id="filter_nrk" name="filter_nrk"
                                        placeholder="Cari NRK karyawan..." value="{{ $currentFilters['nrk'] ?? '' }}">
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_no_dokumen" class="form-label fw-bold">No Dokumen</label>
                                    <input type="text" class="form-control" id="filter_no_dokumen"
                                        name="filter_no_dokumen" placeholder="Cari nomor dokumen..."
                                        value="{{ $currentFilters['no_dokumen'] ?? '' }}">
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="col-md-12 mt-3 justify-content-center">
                                    <div class="form-group w-100 text-end">
                                        <button type="button" class="btn btn-secondary me-2" id="resetFilter">
                                            <i class="fas fa-redo me-1"></i>Reset Semua Filter
                                        </button>
                                        <button type="button" class="btn btn-primary" id="applyFilter">
                                            <i class="fas fa-search me-1"></i>Terapkan Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal (tetap sama) -->
    <div class="modal fade" id="exportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-download me-2"></i>Export Data</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Pilih format export yang diinginkan:</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        <small>Export akan menggunakan filter yang sedang aktif</small>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-success" id="exportExcel">
                            <i class="fas fa-file-excel me-2"></i>Export ke Excel (.xlsx)
                        </button>
                        <button type="button" class="btn btn-outline-danger" id="exportPDF">
                            <i class="fas fa-file-pdf me-2"></i>Export ke PDF
                        </button>
                        <button type="button" class="btn btn-outline-info" id="exportCSV">
                            <i class="fas fa-file-csv me-2"></i>Export ke CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Modal (tetap sama) -->
    <div class="modal fade" id="summaryModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-chart-pie me-2"></i>Ringkasan Data Dokumen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Overall Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-primary shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                                    <h6 class="text-muted mb-2">Total Dokumen</h6>
                                    <h2 class="fw-bold text-primary" id="totalDokumen">{{ $dataDokumens->count() }}
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h6 class="text-muted mb-2">Dokumen Aktif</h6>
                                    <h2 class="fw-bold text-success" id="totalAktif">
                                        {{ $dataDokumens->where('sts_dok', 'AKTIF')->count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-hourglass-half fa-3x text-warning mb-3"></i>
                                    <h6 class="text-muted mb-2">Akan Expired</h6>
                                    <h2 class="fw-bold text-warning" id="totalExpiring">
                                        {{ $expiringDocumentsCount }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-danger shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                                    <h6 class="text-muted mb-2">Expired</h6>
                                    <h2 class="fw-bold text-danger" id="totalExpired">{{ $expiredDocumentsCount }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Document Type -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-file-alt me-2 text-primary"></i>Berdasarkan Jenis
                            Dokumen
                        </h5>
                        <div class="row">
                            @foreach ($dokumenTypes as $dokumen)
                                @php
                                    $count = $dataDokumens->where('id_dokumen', $dokumen->id)->count();
                                @endphp
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card border-left-primary shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="mb-0 fw-bold text-truncate"
                                                        title="{{ $dokumen->nama_dok }}" style="max-width: 150px;">
                                                        {{ Str::limit($dokumen->singkatan_dok ?? $dokumen->nama_dok, 20) }}
                                                    </p>
                                                </div>
                                                <div class="text-end">
                                                    <h3 class="fw-bold text-primary mb-0">{{ $count }}</h3>
                                                </div>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    style="width: {{ $dataDokumens->count() > 0 ? ($count / $dataDokumens->count()) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Gender -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-users me-2 text-warning"></i>Berdasarkan Jenis Kelamin
                        </h5>
                        <div class="row">
                            @foreach ($jenisKelaminOptions as $gender => $genderLabel)
                                @php
                                    $count = $dataKaryawans->where('sex', $gender)->count();
                                @endphp
                                <div class="col-md-6 mb-3">
                                    <div class="card border-left-warning shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-muted mb-1 small">
                                                        @if ($gender == 'LAKI-LAKI')
                                                            <i class="fas fa-mars text-primary"></i>
                                                        @else
                                                            <i class="fas fa-venus text-danger"></i>
                                                        @endif
                                                    </h6>
                                                    <p class="mb-0 fw-bold">{{ $genderLabel }}</p>
                                                </div>
                                                <div class="text-end">
                                                    <h3 class="fw-bold text-warning mb-0">{{ $count }}</h3>
                                                </div>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                    style="width: {{ $dataKaryawans->count() > 0 ? ($count / $dataKaryawans->count()) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        /* ===== CARD STYLING ===== */
        .dataDokumenPage .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .border-left-primary {
            border-left: 4px solid #0d6efd !important;
        }

        .border-left-success {
            border-left: 4px solid #198754 !important;
        }

        .border-left-info {
            border-left: 4px solid #0dcaf0 !important;
        }

        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }

        .border-left-secondary {
            border-left: 4px solid #6c757d !important;
        }

        /* ===== TABLE STYLING ===== */
        .table th {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            font-weight: 600;
            font-size: 0.75rem;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
            border-color: #dee2e6;
            font-size: 0.75rem;
        }

        .no-wrap {
            white-space: nowrap !important;
            min-width: 120px !important;
        }

        /* ===== EMPLOYEE PHOTO STYLING ===== */
        .employee-photo {
            width: 40px !important;
            height: 40px !important;
            object-fit: cover;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .employee-photo:hover {
            transform: scale(1.15);
            border-color: #0d6efd;
            box-shadow: 0 3px 6px rgba(13, 110, 253, 0.3);
            z-index: 10;
        }

        .employee-photo-placeholder {
            width: 40px !important;
            height: 40px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
            margin: 0 auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .employee-photo-placeholder:hover {
            transform: scale(1.15);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
        }

        /* ===== SELECT2 CUSTOM STYLING ===== */
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            z-index: 1070 !important;
        }

        .modal .select2-container {
            z-index: 1070 !important;
        }

        .modal .select2-dropdown {
            z-index: 1071 !important;
        }

        .select2-container--open .select2-dropdown {
            z-index: 1071 !important;
        }

        /* ===== BADGE STYLING ===== */
        .badge-lg {
            font-size: 0.75em;
            font-weight: 600;
            padding: 0.4em 0.8em;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3em;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #filterActiveAlert {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* ===== DOCUMENT STATUS SUMMARY BADGES ===== */
        .document-status-summary {
            margin-bottom: 1rem;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            border-left: 4px solid #0d6efd;
        }

        .document-status-summary .badge {
            font-size: 0.9rem !important;
            padding: 0.5em 0.75em;
            margin-right: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* ===== HOVER EFFECT FOR TABLE ROWS ===== */
        .dataDokumenPage #dataDokumenTable tbody tr {
            transition: all 0.2s ease;
        }

        .dataDokumenPage #dataDokumenTable tbody tr:hover {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            cursor: pointer;
            position: relative;
            z-index: 1;
        }

        /* Flash effect when hovering */
        @keyframes flashBorder {
            0% {
                box-shadow: 0 0 0 rgba(13, 110, 253, 0);
            }

            50% {
                box-shadow: 0 0 8px rgba(13, 110, 253, 0.5);
            }

            100% {
                box-shadow: 0 0 0 rgba(13, 110, 253, 0);
            }
        }

        .dataDokumenPage #dataDokumenTable tbody tr.row-hover-active {
            animation: flashBorder 1s ease infinite;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.7rem;
            }

            .badge-lg {
                font-size: 0.6em;
                padding: 0.25em 0.5em;
            }

            .btn {
                font-size: 0.65rem;
                padding: 0.2rem 0.4rem;
            }

            .table th {
                font-size: 0.7rem;
            }

            .table td {
                font-size: 0.7rem;
            }

            .employee-photo,
            .employee-photo-placeholder {
                width: 32px !important;
                height: 32px !important;
                font-size: 0.8rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            console.log('Document system initializing...');

            function initializeSelect2InModal() {
                $('#filterModal .select2').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }

                    $(this).select2({
                        theme: 'bootstrap-5',
                        dropdownParent: $('#filterModal'),
                        width: '100%',
                        placeholder: $(this).find('option:first').text() || 'Pilih...',
                        allowClear: true,
                        language: {
                            noResults: function() {
                                return "Tidak ada hasil ditemukan";
                            },
                            searching: function() {
                                return "Mencari...";
                            },
                            inputTooShort: function() {
                                return "Ketik untuk mencari...";
                            }
                        }
                    });
                });
            }

            $('#filterModal').on('shown.bs.modal', function() {
                initializeSelect2InModal();
            });

            $('#filterModal').on('hidden.bs.modal', function() {
                $('#filterModal .select2').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });
            });

            $('#filterButton').click(function() {
                $('#filterModal').modal('show');
            });

            $('#applyFilter').click(function() {
                $('#filterForm').submit();
            });

            $('#resetFilter').click(function() {
                $('#filter_nama').val('');
                $('#filter_nrk').val('');
                $('#filter_no_dokumen').val('');
                $('#filter_status').val('').trigger('change');
                $('#filter_jenis_dokumen').val('').trigger('change');
                $('#filter_perusahaan').val('').trigger('change');
                $('#filter_departemen').val('').trigger('change');
                $('#filter_jabatan').val('').trigger('change');
                $('#filter_jenis_kelamin').val('').trigger('change');
                $('#filter_wilker').val('').trigger('change');
                $('#filter_unit_kerja').val('').trigger('change');

                initializeSelect2InModal();
            });

            if ($.fn.DataTable.isDataTable('#dataDokumenTable')) {
                $('#dataDokumenTable').DataTable().destroy();
            }

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            $(document).on('click', '.employee-photo', function() {
                const imgSrc = $(this).attr('src');
                const employeeName = $(this).attr('alt');

                Swal.fire({
                    title: employeeName,
                    imageUrl: imgSrc,
                    imageAlt: employeeName,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: '600px',
                    customClass: {
                        image: 'img-fluid rounded'
                    }
                });
            });

            var table = $('#dataDokumenTable').DataTable(
        );

            $('#filterButton').on('click', function() {
                $('#filterModal').modal('show');
            });

            $('#applyFilter').on('click', function() {
                $('#filterForm').submit();
            });

            $('#resetFilter').on('click', function() {
                $('#filterForm')[0].reset();
                window.location.href = "{{ route('data-dokumen.index') }}";
            });

            $('#exportButton').on('click', function() {
                $('#exportModal').modal('show');
            });

            $('#exportExcel').click(function() {
                var formData = $('#filterForm').serialize();
                var exportUrl = "{{ route('data-dokumen.index') }}?export=excel&" + formData;
                window.location.href = exportUrl;
            });

            $('#summaryButton').on('click', function() {
                $('#summaryModal').modal('show');
            });

            $('#dataDokumenTable tbody').on('mouseenter', 'tr', function() {
                $(this).addClass('row-hover-active');
            }).on('mouseleave', 'tr', function() {
                $(this).removeClass('row-hover-active');
            });

            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 5000);

            console.log('Document system initialization complete!');
        });
    </script>
@endpush