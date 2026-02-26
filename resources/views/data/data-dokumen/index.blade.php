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
                                !empty($currentFilters['jenis_kelamin']))
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

                                @if (!empty($currentFilters['nama']))
                                    Nama: <span class="badge bg-primary">{{ $currentFilters['nama'] }}</span>
                                @endif

                                @if (!empty($currentFilters['nrk']))
                                    NRK: <span class="badge bg-primary">{{ $currentFilters['nrk'] }}</span>
                                @endif

                                @if (!empty($currentFilters['no_dokumen']))
                                    No Dokumen: <span class="badge bg-info">{{ $currentFilters['no_dokumen'] }}</span>
                                @endif

                                @if (!empty($currentFilters['jenis_kelamin']))
                                    Jenis Kelamin: <span
                                        class="badge bg-dark">{{ $jenisKelaminOptions[$currentFilters['jenis_kelamin']] ?? $currentFilters['jenis_kelamin'] }}</span>
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
                                        <th width="2%" class="text-center">JML</th>
                                        <th width="2%" class="text-center">UMR</th>
                                        <th width="1%" class="text-center">SEX</th>
                                        <th width="2%" class="text-center">FOTO</th>
                                        <th width="3%" class="text-center">TGL MSK</th>
                                        <th width="2%" class="text-center">MKR</th>
                                        <th width="3%" class="text-center">JNS DOK</th>
                                        <th width="4%" class="text-center">NO DOK</th>
                                        <th width="3%" class="text-center">TGL TTD</th>
                                        <th width="2%" class="text-center">JNS MSB</th>
                                        <th width="3%" class="text-center">TGL AKR</th>
                                        <th width="2%" class="text-center">MSB</th>
                                        <th width="3%" class="text-center">TGL PGT</th>
                                        <th width="1%" class="text-center">PERINGATAN</th>
                                        <th width="1%" class="text-center">FILE</th>
                                        <th width="2%" class="text-center">STS</th>
                                        <th width="3%" class="text-center">TGL NA</th>
                                        <th width="4%" class="text-center">KET</th>
                                        <th width="4%" class="text-center">CREATE</th>
                                        <th width="4%" class="text-center">UPDATE</th>
                                        <th width="3%" class="text-center no-wrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataDokumens as $dokumen)
                                        @php
                                            // Get related data
                                            $karyawan = $dokumen->karyawan;
                                            $dokumenType = $dokumen->dokumenKaryawan;

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
                                                <span class="fw-bold">{{ $karyawan->nrk ?? '-' }}</span>
                                                <span class="text-muted">{{ $karyawan->nik ?? '-' }}</span>
                                            </td>

                                            <!-- NAMA -->
                                            <td>
                                                <div class="fw-bold">{{ $karyawan->nama ?? '-' }}</div>
                                            </td>

                                            <!-- JUMLAH DOKUMEN -->
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

                                            <!-- JENIS DOKUMEN -->
                                            <td class="text-center">
                                                <span>{{ $dokumenType->singkatan_dok ?? '-' }}</span>
                                            </td>

                                            <!-- NO DOKUMEN -->
                                            <td class="text-center">
                                                <small>{{ $dokumen->no_dok ?? '-' }}</small>
                                            </td>

                                            <!-- TGL TANDA TANGAN -->
                                            <td class="text-center">
                                                {{ $dokumen->tgl_ttd ? date('d-m-Y', strtotime($dokumen->tgl_ttd)) : '-' }}
                                            </td>

                                            <!-- JENIS MASA BERLAKU -->
                                            <td class="text-center">
                                                <span>{{ $dokumen->jns_msb_dok ?? '-' }}</span>
                                            </td>

                                            <!-- TGL AKHIR -->
                                            <td class="text-center">
                                                {{ $dokumen->tgl_akr_dok ? date('d-m-Y', strtotime($dokumen->tgl_akr_dok)) : '-' }}
                                            </td>

                                            <!-- MASA BERLAKU -->
                                            <td class="text-center">
                                                @if ($dokumen->msb_dok)
                                                    <span>{{ $dokumen->msb_dok }} bln</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- TGL PERINGATAN -->
                                            <td class="text-center">
                                                {{ $dokumen->tgl_pgt_dok ? date('d-m-Y', strtotime($dokumen->tgl_pgt_dok)) : '-' }}
                                            </td>

                                            <!-- PERINGATAN (Will be calculated by JS) -->
                                            <td class="text-center sisa-peringatan-col">
                                                <span>Loading...</span>
                                            </td>

                                            <!-- FILE -->
                                            <td class="text-center">
                                                @if ($dokumen->file_dok)
                                                    <a href="{{ asset('storage/' . $dokumen->file_dok) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="tooltip" title="Lihat File">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <!-- STATUS -->
                                            <td class="text-center">
                                                {{ $dokumen->sts_dok }}
                                            </td>

                                            <!-- TGL NON AKTIF -->
                                            <td class="text-center">
                                                {{ $dokumen->tgl_dok_na ? date('d-m-Y', strtotime($dokumen->tgl_dok_na)) : '-' }}
                                            </td>

                                            <!-- KETERANGAN -->
                                            <td>
                                                <small>{{ Str::limit($dokumen->ket_dok ?? '-', 30) }}</small>
                                            </td>

                                            <!-- CREATED BY -->
                                            <td>{{ $dokumen->creator ? $dokumen->creator->nama_kry : '-' }}
                                                <span
                                                    style="font-size: 11px">{{ $dokumen->created_at ? $dokumen->created_at->format('d/m/y H:i') : '-' }}</span>
                                            </td>

                                            <!-- UPDATED BY -->
                                            <td>{{ $dokumen->updater ? $dokumen->updater->nama_kry : '-' }}
                                                <span
                                                    style="font-size: 11px">{{ $dokumen->updated_at ? $dokumen->updated_at->format('d/m/y H:i') : '-' }}</span>
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

    <!-- Filter Modal -->
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
                            <!-- Status Dokumen -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_status" class="form-label fw-bold">Status Dokumen</label>
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

                            <!-- Jenis Dokumen -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_jenis_dokumen" class="form-label fw-bold">Jenis Dokumen</label>
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

                            <!-- Nama Karyawan -->
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_nama" class="form-label fw-bold">Nama Karyawan</label>
                                    <input type="text" class="form-control" id="filter_nama" name="filter_nama"
                                        placeholder="Cari nama karyawan..." value="{{ $currentFilters['nama'] ?? '' }}">
                                </div>
                            </div>

                            <!-- NRK -->
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_nrk" class="form-label fw-bold">NRK</label>
                                    <input type="text" class="form-control" id="filter_nrk" name="filter_nrk"
                                        placeholder="Cari NRK karyawan..." value="{{ $currentFilters['nrk'] ?? '' }}">
                                </div>
                            </div>

                            <!-- No Dokumen -->
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_no_dokumen" class="form-label fw-bold">No Dokumen</label>
                                    <input type="text" class="form-control" id="filter_no_dokumen"
                                        name="filter_no_dokumen" placeholder="Cari nomor dokumen..."
                                        value="{{ $currentFilters['no_dokumen'] ?? '' }}">
                                </div>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_jenis_kelamin" class="form-label fw-bold">Jenis Kelamin</label>
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

    <!-- Export Modal -->
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus Semua Dokumen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan!</strong> Tindakan ini akan menghapus <strong>SEMUA</strong> dokumen karyawan.
                    </div>
                    <p>Apakah Anda yakin ingin menghapus <strong>semua data dokumen</strong> untuk karyawan <strong
                            id="employeeName"></strong>?</p>
                    <p class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Ini akan menghapus seluruh riwayat dokumen karyawan tersebut.
                        Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Hapus Semua Dokumen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Modal -->
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

        /* ===== HIGHLIGHT ROWS STYLING ===== */
        .dataDokumenPage table#dataDokumenTable tbody tr.highlight-red {
            background-color: #fc0000 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        .dataDokumenPage table#dataDokumenTable tbody tr.highlight-yellow {
            background-color: #ffff00 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        .dataDokumenPage table#dataDokumenTable tbody tr.highlight-orange {
            background-color: #00e013 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        .dataDokumenPage table#dataDokumenTable tbody tr.highlight-gray {
            background-color: #cccccc !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        /* Ensure hover states don't override highlight colors */
        .dataDokumenPage table#dataDokumenTable tbody tr.highlight-red:hover {
            background-color: #ff3333 !important;
        }

        .dataDokumenPage table#dataDokumenTable tbody tr.highlight-yellow:hover {
            background-color: #ffff66 !important;
        }

        .dataDokumenPage table#dataDokumenTable tbody tr.highlight-orange:hover {
            background-color: #33ff33 !important;
        }

        .dataDokumenPage table#dataDokumenTable tbody tr.highlight-gray:hover {
            background-color: #dddddd !important;
        }

        /* Override Bootstrap's striped table styles */
        .dataDokumenPage .table-striped>tbody>tr:nth-of-type(odd).highlight-red,
        .dataDokumenPage .table-striped>tbody>tr:nth-of-type(even).highlight-red {
            background-color: #fc0000 !important;
        }

        .dataDokumenPage .table-striped>tbody>tr:nth-of-type(odd).highlight-yellow,
        .dataDokumenPage .table-striped>tbody>tr:nth-of-type(even).highlight-yellow {
            background-color: #ffff00 !important;
        }

        .dataDokumenPage .table-striped>tbody>tr:nth-of-type(odd).highlight-orange,
        .dataDokumenPage .table-striped>tbody>tr:nth-of-type(even).highlight-orange {
            background-color: #00e013 !important;
        }

        .dataDokumenPage .table-striped>tbody>tr:nth-of-type(odd).highlight-gray,
        .dataDokumenPage .table-striped>tbody>tr:nth-of-type(even).highlight-gray {
            background-color: #cccccc !important;
        }

        /* ===== INI YANG KURANG — WAJIB DITAMBAHKAN ===== */
        /* Memastikan warna background tr diturunkan ke td di dalamnya */
        .dataDokumenPage table#dataDokumenTable tbody tr.highlight-red>td,
        .dataDokumenPage table#dataDokumenTable tbody tr.highlight-yellow>td,
        .dataDokumenPage table#dataDokumenTable tbody tr.highlight-orange>td,
        .dataDokumenPage table#dataDokumenTable tbody tr.highlight-gray>td {
            background-color: inherit !important;
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
                $('#filter_jenis_kelamin').val('').trigger('change');

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

            function calculateRowWarning(row) {
                const tglPeringatan = row.data('tgl-peringatan');
                const documentStatus = row.data('document-status');
                const tglAkhir = row.data('tgl-akhir');
                const jnsMsb = row.data('jns-msb'); // ← TAMBAHKAN INI

                console.log('Calculating for row:', {
                    'tgl-peringatan': tglPeringatan,
                    'document-status': documentStatus,
                    'tgl-akhir': tglAkhir,
                    'jns-msb': jnsMsb // ← TAMBAHKAN INI
                });

                // Skip NON-AKTIF documents - Abu-abu
                if (documentStatus === 'NON-AKTIF') {
                    return {
                        text: 'NON-AKTIF',
                        badgeClass: 'bg-secondary',
                        priority: 10, // ← UBAH dari 10 agar selalu paling bawah
                        status: 'non_active'
                    };
                }

                // Skip non-active documents (EXPIRED, PENDING, etc)
                if (documentStatus !== 'AKTIF') {
                    return {
                        text: 'Dokumen Tidak Aktif',
                        badgeClass: 'bg-secondary',
                        priority: 5,
                        status: 'inactive'
                    };
                }

                // PERBAIKAN: Handle dokumen TETAP yang AKTIF
                if (jnsMsb === 'TETAP') {
                    // Dokumen TETAP yang AKTIF = "Tidak Ada Pengingat" tapi TIDAK abu-abu
                    return {
                        text: 'Tidak Ada Pengingat',
                        badgeClass: 'bg-secondary',
                        priority: 5,
                        status: 'tetap_no_reminder' // ← Status khusus untuk TETAP
                    };
                }

                // If no reminder date for PERPANJANGAN documents, abu-abu
                if (!tglPeringatan || tglPeringatan === '') {
                    return {
                        text: 'Tidak Ada Pengingat',
                        badgeClass: 'bg-secondary',
                        priority: 5,
                        status: 'no_reminder'
                    };
                }

                // Calculate days difference using moment (untuk PERPANJANGAN dengan pengingat)
                const today = moment().startOf('day');
                const reminderDate = moment(tglPeringatan);
                const diffDays = reminderDate.diff(today, 'days');

                console.log('Date calculation:', {
                    today: today.format('YYYY-MM-DD'),
                    reminderDate: reminderDate.format('YYYY-MM-DD'),
                    diffDays: diffDays
                });

                let result = {
                    text: '',
                    badgeClass: '',
                    priority: 4,
                    status: 'normal'
                };

                if (diffDays < 0) {
                    // Expired - reminder date has passed
                    result.text = 'Terlambat ' + Math.abs(diffDays) + ' hari';
                    result.badgeClass = 'bg-danger';
                    result.priority = 1;
                    result.status = 'expired';
                } else if (diffDays === 0) {
                    // Today
                    result.text = 'HARI INI';
                    result.badgeClass = 'bg-danger';
                    result.priority = 1;
                    result.status = 'expired';
                } else if (diffDays <= 7) {
                    // Urgent - within 7 days
                    result.text = diffDays + ' Hr lg';
                    result.badgeClass = 'bg-warning text-dark';
                    result.priority = 2;
                    result.status = 'urgent';
                } else if (diffDays <= 30) {
                    // Warning - within 30 days
                    result.text = diffDays + ' Hr lg';
                    result.badgeClass = 'bg-info';
                    result.priority = 3;
                    result.status = 'warning';
                } else {
                    // Safe - more than 30 days
                    result.text = diffDays + ' Hr lg';
                    result.badgeClass = 'bg-success';
                    result.priority = 4;
                    result.status = 'safe';
                }

                console.log('Warning result:', result);
                return result;
            }

            function applyRowProcessing() {
                console.log('Applying row processing...');

                let expiredCount = 0;
                let warningCount = 0;

                // Reset all highlighting
                $('#dataDokumenTable tbody tr').removeClass(
                    'highlight-red highlight-yellow highlight-orange highlight-gray');

                $('#dataDokumenTable tbody tr').each(function() {
                    const row = $(this);
                    const warningData = calculateRowWarning(row);

                    const peringatanCol = row.find('.sisa-peringatan-col');
                    peringatanCol.html('<span>' + warningData.text + '</span>');

                    // Apply row highlighting
                    switch (warningData.status) {
                        case 'expired':
                            row.addClass('highlight-red');
                            expiredCount++;
                            break;
                        case 'urgent':
                            row.addClass('highlight-yellow');
                            warningCount++;
                            break;
                        case 'warning':
                            row.addClass('highlight-orange');
                            warningCount++;
                            break;
                        case 'safe':
                            // No highlighting for safe status - tetap putih
                            break;
                        case 'tetap_no_reminder':
                            // PERBAIKAN: Dokumen TETAP AKTIF tidak diberi highlighting - tetap putih
                            break;
                        case 'non_active':
                            // Abu-abu untuk NON-AKTIF
                            row.addClass('highlight-gray');
                            break;
                        case 'no_reminder':
                            // Abu-abu untuk PERPANJANGAN tanpa pengingat
                            row.addClass('highlight-gray');
                            break;
                        default:
                            // Inactive documents - abu-abu
                            row.addClass('highlight-gray');
                            break;
                    }

                    // Store priority for sorting
                    row.data('priority', warningData.priority);
                });

                console.log('Statistics updated - Expired:', expiredCount, 'Warning:', warningCount);
            }

            function getRowPriority(row) {
                return $(row).data('priority') || 5;
            }

            $.fn.dataTable.ext.order['dom-priority'] = function(settings, col) {
                return this.api().column(col, {
                    order: 'index'
                }).nodes().map(function(td, i) {
                    return getRowPriority($(td).closest('tr'));
                });
            };

            $('#dataDokumenTable tbody tr').each(function() {
                const row = $(this);
                const warningData = calculateRowWarning(row);
                row.data('priority', warningData.priority);
            });

            var table = $('#dataDokumenTable').DataTable({
                responsive: true,
                destroy: true,
                language: {
                    "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "loadingRecords": "Sedang memuat...",
                    "processing": "Sedang memproses...",
                    "search": "Cari:",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                columnDefs: [{
                    targets: 0,
                    orderDataType: 'dom-priority'
                }, {
                    orderable: false,
                    targets: [23]
                }, {
                    responsivePriority: 1,
                    targets: [23]
                }, {
                    responsivePriority: 2,
                    targets: [0, 1, 2]
                }, {
                    responsivePriority: 3,
                    targets: [18, 16]
                }],
                order: [
                    [0, 'asc']
                ],
                drawCallback: function() {
                    var api = this.api();
                    var startIndex = api.page.info().start;

                    api.column(0, {
                        page: 'current'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = startIndex + i + 1;
                    });

                    applyRowProcessing();
                },
                initComplete: function() {
                    console.log('DataTable initialized, applying initial processing...');
                    applyRowProcessing();
                }
            });

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

            $(document).on('click', '.delete-confirm', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                var deleteUrl = "{{ route('data-dokumen.destroy', ':id') }}".replace(':id', id);
                $('#deleteForm').attr('action', deleteUrl);
                $('#employeeName').text(name);

                $('#deleteConfirmationModal').modal('show');
            });

            table.on('draw.dt', function() {
                applyRowProcessing();
            });

            $('#dataDokumenTable tbody').on('mouseenter', 'tr', function() {
                $(this).addClass('row-hover-active');
            }).on('mouseleave', 'tr', function() {
                $(this).removeClass('row-hover-active');
            });

            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 5000);

            setTimeout(function() {
                console.log('Force applying initial processing...');
                applyRowProcessing();
            }, 1000);

            console.log('Document system initialization complete!');
        });
    </script>
@endpush
