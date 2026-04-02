@extends('layouts.app')

@section('title', 'Edit Data Gaji')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-money-bill-wave me-2"></i>Edit Data Gaji</span>
                        <a href="{{ route('data-gaji.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-exclamation-triangle me-2"></i>Terdapat kesalahan:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Nav Tabs -->
                        <ul class="nav nav-tabs mb-4" id="formTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="karyawan-tab" data-bs-toggle="tab"
                                    data-bs-target="#karyawan" type="button" role="tab" aria-selected="true">
                                    <i class="fas fa-user me-1"></i> Data Karyawan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="data-gaji-tab" data-bs-toggle="tab"
                                    data-bs-target="#data-gaji" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-money-bill-wave me-1"></i> Manajemen Gaji
                                    <span class="badge bg-primary ms-1"
                                        id="gajiCount">{{ $allGajis->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pendidikan-tab" data-bs-toggle="tab"
                                    data-bs-target="#pendidikan" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-graduation-cap me-1"></i> Pendidikan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="karir-tab" data-bs-toggle="tab" data-bs-target="#karir"
                                    type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-briefcase me-1"></i> Jenjang Karir
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="hubin-tab" data-bs-toggle="tab" data-bs-target="#hubin"
                                    type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-user-check me-1"></i> Hubungan Industrial
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content">

                            <!-- ===== TAB 1: DATA KARYAWAN ===== -->
                            <div class="tab-pane fade show active" id="karyawan" role="tabpanel">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-user me-2"></i>Informasi Karyawan
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Foto Karyawan -->
                                            <div class="col-md-3 text-center mb-4">
                                                <div class="employee-photo-container">
                                                    @if ($dataGaji->karyawan && $dataGaji->karyawan->foto_dokumen)
                                                        <img src="{{ asset('storage/' . $dataGaji->karyawan->foto_dokumen) }}"
                                                            alt="Foto {{ $dataGaji->karyawan->nama ?? 'Karyawan' }}"
                                                            class="img-fluid rounded shadow employee-photo"
                                                            onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                                    @else
                                                        <div class="default-avatar rounded shadow">
                                                            <i class="fas fa-user-circle fa-8x text-secondary"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Informasi Karyawan -->
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <div class="form-group">
                                                            <label class="form-label fw-bold">Nama Lengkap</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->nama ?? '-' }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">NIK</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->nik ?? '-' }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">NRK</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->nrk ?? '-' }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">Jenis Kelamin</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->sex ?? '-' }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">Tempat, Tanggal Lahir</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->tpt_lahir ?? '' }}{{ $dataGaji->karyawan->tgl_lahir ? ', ' . $dataGaji->karyawan->tgl_lahir->format('d-m-Y') : '' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">Telepon</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->tlp1 ?? '-' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">Status Kawin</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->sts_nikah ?? '-' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">Jumlah Anak</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->jml_anak ?? '-' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">Email</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->email1 ?? '-' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== TAB 2: MANAJEMEN GAJI (CRUD TABLE) ===== -->
                            <div class="tab-pane fade" id="data-gaji" role="tabpanel">
                                <div class="card border-primary mb-4">
                                    <div
                                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-money-bill-wave me-2"></i>Manajemen Data Gaji Karyawan
                                        </h5>
                                        <button type="button" class="btn btn-light btn-sm" id="addGajiBtn">
                                            <i class="fas fa-plus me-1"></i> Tambah Data Gaji
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="gajiTable"
                                                class="table table-bordered table-striped data-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="3%" class="text-center">No</th>
                                                        <th width="8%" class="text-center">ID Gaji</th>
                                                        <th width="10%" class="text-center">Gaji Pokok</th>
                                                        <th width="10%" class="text-center">Total Pendapatan Tetap</th>
                                                        <th width="12%" class="text-center">Total Pendapatan Tdk Tetap</th>
                                                        <th width="10%" class="text-center">Total Potongan</th>
                                                        <th width="10%" class="text-center">Gaji Bersih</th>
                                                        <th width="8%" class="text-center">Created</th>
                                                        <th width="8%" class="text-center">Updated</th>
                                                        <th width="12%" class="text-center no-wrap">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="gajiTableBody">
                                                    @foreach ($allGajis as $index => $gaji)
                                                        <tr data-gaji-id="{{ $gaji->id }}">
                                                            <td class="text-center">{{ $index + 1 }}</td>
                                                            <td class="text-center">
                                                                <small class="fw-bold">{{ $gaji->id_gaji ?? '-' }}</small>
                                                            </td>
                                                            <td class="text-end">
                                                                Rp {{ number_format($gaji->gj_pokok ?? 0, 0, ',', '.') }}
                                                            </td>
                                                            <td class="text-end">
                                                                Rp {{ number_format($gaji->ttl_pendapatan_ttp ?? 0, 0, ',', '.') }}
                                                            </td>
                                                            <td class="text-end">
                                                                Rp {{ number_format($gaji->ttl_pendapatan_tdk_ttp ?? 0, 0, ',', '.') }}
                                                            </td>
                                                            <td class="text-end">
                                                                Rp {{ number_format($gaji->ttl_potongan ?? 0, 0, ',', '.') }}
                                                            </td>
                                                            <td class="text-end text-primary fw-bold">
                                                                Rp {{ number_format($gaji->ttl_terima_gaji ?? 0, 0, ',', '.') }}
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ $gaji->creator ? $gaji->creator->nama_kry : '-' }}</small>
                                                                <br><small
                                                                    class="text-muted">{{ $gaji->created_at ? $gaji->created_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ $gaji->updater ? $gaji->updater->nama_kry : '-' }}</small>
                                                                <br><small
                                                                    class="text-muted">{{ $gaji->updated_at ? $gaji->updated_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td class="text-center no-wrap">
                                                                <div class="btn-group" role="group"
                                                                    aria-label="Actions">
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-info show-gaji-btn"
                                                                        data-gaji-id="{{ $gaji->id }}"
                                                                        data-mode="view" data-bs-toggle="tooltip"
                                                                        title="Detail">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-warning edit-gaji-btn"
                                                                        data-gaji-id="{{ $gaji->id }}"
                                                                        data-mode="edit" data-bs-toggle="tooltip"
                                                                        title="Edit">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger delete-gaji-btn"
                                                                        data-gaji-id="{{ $gaji->id }}"
                                                                        data-bs-toggle="tooltip" title="Hapus">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
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

                            <!-- ===== TAB 3: PENDIDIKAN ===== -->
                            <div class="tab-pane fade" id="pendidikan" role="tabpanel">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-graduation-cap me-2"></i>Pendidikan
                                            Terakhir</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data berikut diambil dari profil karyawan dan bersifat read-only.
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jenjang Pendidikan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->jenjang_skl ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal Lulus</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->tgl_lulus_skl ? $dataGaji->karyawan->tgl_lulus_skl->format('d-m-Y') : '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Nama Institusi</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->institusi_skl ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Fakultas</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->fakultas_skl ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jurusan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->jurusan_skl ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Kota</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->kota_skl ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Gelar</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->gelar_skl ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== TAB 4: JENJANG KARIR ===== -->
                            <div class="tab-pane fade" id="karir" role="tabpanel">
                                <div class="card border-warning mb-4">
                                    <div class="card-header bg-warning bg-opacity-25">
                                        <h5 class="mb-0 text-dark"><i class="fas fa-briefcase me-2"></i>Jenjang Karir
                                            Saat Ini</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data berikut diambil dari profil karyawan dan bersifat read-only.
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Departemen</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->departemenRelation?->nama_dep ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Singkatan Dep.</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->departemenRelation?->singkatan_dep ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jabatan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->departemenRelation?->nama_jbt ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Singkatan Jbt.</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->departemenRelation?->singkatan_jbt ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Wilayah Kerja</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->wilayahKerjaRelation?->wilayah_krj ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Unit Kerja</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->unitKerjaRelation?->area_krj ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">SKT Wilayah Kerja</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->unitKerjaRelation?->singkatan_wk ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tugas & Tanggung Jawab</label>
                                                    <textarea class="form-control" rows="4" readonly>{{ $dataGaji->karyawan->tugas ?? '-' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== TAB 5: HUBUNGAN INDUSTRIAL ===== -->
                            <div class="tab-pane fade" id="hubin" role="tabpanel">
                                <div class="card border-info mb-4">
                                    <div class="card-header bg-info bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-user-check me-2"></i>Hubungan
                                            Industrial</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data berikut diambil dari profil karyawan dan bersifat read-only.
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal Masuk</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->tgl_masuk ? $dataGaji->karyawan->tgl_masuk->format('d-m-Y') : '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Status Karyawan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->sts_kry ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal PHK</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->tgl_phk ? $dataGaji->karyawan->tgl_phk->format('d-m-Y') : '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Keterangan PHK</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->ket_phk ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Global action buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('data-gaji.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gaji Modal (Unified: Create/Edit/Show) -->
    <div class="modal fade" id="gajiModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" id="gajiModalHeader">
                    <h5 class="modal-title text-white" id="gajiModalTitle">Data Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="gajiForm">
                        <input type="hidden" id="gaji_id" name="gaji_id">
                        <input type="hidden" name="employee_id" value="{{ $dataGaji->id_karyawan }}">

                        <!-- Mode Indicator -->
                        <div class="alert alert-info" id="modeIndicator" style="display: none;">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="modeText">Mode Detail - Data hanya dapat dilihat</span>
                        </div>

                        <div class="row">
                            <!-- Kolom Kiri: Pendapatan Tetap & Tidak Tetap -->
                            <div class="col-md-6">
                                <!-- Pendapatan Tetap -->
                                <div class="card border-success mb-4">
                                    <div class="card-header bg-success bg-opacity-25">
                                        <h6 class="mb-0 text-white"><i class="fas fa-wallet me-2"></i>Pendapatan Tetap
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-2">
                                            <label for="modal_gj_pokok" class="form-label fw-bold">Gaji Pokok</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_gj_pokok" name="gj_pokok" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_tunjab" class="form-label fw-bold">Tunjangan
                                                Jabatan</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_tunjab" name="tunjab" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_tunkom" class="form-label fw-bold">Tunjangan
                                                Komunikasi</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_tunkom" name="tunkom" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_fot" class="form-label fw-bold">Fix Over Time
                                                (FOT)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_fot" name="fot" value="0" min="0" step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_tunmal" class="form-label fw-bold">Tunjangan
                                                Kemahalan</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_tunmal" name="tunmal" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label fw-bold text-success">Total Pendapatan
                                                Tetap</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" class="form-control fw-bold text-success"
                                                    id="modal_total_ttp" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pendapatan Tidak Tetap -->
                                <div class="card border-warning mb-4">
                                    <div class="card-header bg-warning bg-opacity-25">
                                        <h6 class="mb-0 text-dark"><i class="fas fa-coins me-2"></i>Pendapatan Tidak
                                            Tetap</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-2">
                                            <label for="modal_lbr_harian" class="form-label fw-bold">Lembur
                                                Harian</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_lbr_harian" name="lbr_harian" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_lbr_perjam" class="form-label fw-bold">Lembur Per
                                                Jam</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_lbr_perjam" name="lbr_perjam" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_tukin" class="form-label fw-bold">Tunjangan
                                                Kinerja</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_tukin" name="tukin" value="0" min="0" step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_insentif" class="form-label fw-bold">Insentif</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_insentif" name="insentif" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_bonus" class="form-label fw-bold">Bonus</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_bonus" name="bonus" value="0" min="0" step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_thr" class="form-label fw-bold">Tunjangan Hari Raya
                                                (THR)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_thr" name="thr" value="0" min="0" step="100">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label fw-bold text-warning">Total Pendapatan Tidak
                                                Tetap</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" class="form-control fw-bold text-warning"
                                                    id="modal_total_tdk_ttp" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Potongan & Beban Perusahaan -->
                            <div class="col-md-6">
                                <!-- Potongan -->
                                <div class="card border-danger mb-4">
                                    <div class="card-header bg-danger bg-opacity-25">
                                        <h6 class="mb-0 text-white"><i class="fas fa-minus-circle me-2"></i>Potongan
                                        </h6>
                                    </div>
                                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                        <div class="form-group mb-2">
                                            <label for="modal_bpjs_tkj" class="form-label fw-bold">BPJS TK
                                                Karyawan</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_bpjs_tkj" name="bpjs_tkj" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_bpjs_kes" class="form-label fw-bold">BPJS Kesehatan
                                                Karyawan</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_bpjs_kes" name="bpjs_kes" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_iuran_koperasi" class="form-label fw-bold">Iuran Wajib
                                                Koperasi</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_iuran_koperasi" name="iuran_koperasi" value="0"
                                                    min="0" step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_tps_kry" class="form-label fw-bold">Tabungan Pensiun
                                                Karyawan</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_tps_kry" name="tps_kry" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_pjk_pkp" class="form-label fw-bold">Pajak PKP</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_pjk_pkp" name="pjk_pkp" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_pjk_pph" class="form-label fw-bold">Pajak PPh</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_pjk_pph" name="pjk_pph" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_ptg_thr" class="form-label fw-bold">Potongan THR</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_ptg_thr" name="ptg_thr" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_pjm_kop" class="form-label fw-bold">Pinjaman
                                                Koperasi</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_pjm_kop" name="pjm_kop" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="modal_dda_sanksi" class="form-label fw-bold">Denda
                                                Sanksi</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_dda_sanksi" name="dda_sanksi" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Beban Perusahaan -->
                                <div class="card border-info">
                                    <div class="card-header bg-info bg-opacity-25">
                                        <h6 class="mb-0 text-white"><i class="fas fa-building me-2"></i>Beban Tanggungan
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-2">
                                            <label for="modal_bpjs_tkj_prs" class="form-label fw-bold">BPJS TK
                                                Perusahaan</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_bpjs_tkj_prs" name="bpjs_tkj_prs" value="0"
                                                    min="0" step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_bpjs_kes_prs" class="form-label fw-bold">BPJS Kesehatan
                                                Perusahaan</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_bpjs_kes_prs" name="bpjs_kes_prs" value="0"
                                                    min="0" step="100">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="modal_tps_prs" class="form-label fw-bold">Tabungan Pensiun
                                                Perusahaan</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_tps_prs" name="tps_prs" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="modal_askes_prs" class="form-label fw-bold">Asuransi
                                                Kesehatan Perusahaan</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control salary-input"
                                                    id="modal_askes_prs" name="askes_prs" value="0" min="0"
                                                    step="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary">
                                        <h6 class="mb-0 text-white"><i class="fas fa-calculator me-2"></i>Ringkasan
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label fw-bold">Total Pendapatan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-success"
                                                            id="modal_total_pendapatan" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label fw-bold">Total Potongan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-danger"
                                                            id="modal_total_potongan" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label fw-bold">Gaji Bersih (Take Home
                                                        Pay)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-primary fs-5"
                                                            id="modal_gaji_bersih" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" id="gajiModalFooter">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <span id="closeButtonText">Batal</span>
                    </button>
                    <button type="button" class="btn btn-primary" id="saveGajiBtn">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                    <button type="button" class="btn btn-warning" id="editGajiBtn" style="display: none;">
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteGajiModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data gaji ini?</p>
                    <p class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteGaji">Hapus</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .card-header {
            font-weight: 600;
        }

        .form-label {
            margin-bottom: 0.3rem;
            font-size: 0.875rem;
        }

        .card {
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .employee-photo-container {
            position: relative;
            width: 100%;
            max-width: 200px;
            margin: 0 auto;
        }

        .employee-photo {
            width: 100%;
            height: auto;
            max-height: 250px;
            object-fit: cover;
            border: 3px solid #0d6efd;
        }

        .default-avatar {
            width: 100%;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            border: 2px dashed #dee2e6;
        }

        .table th {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            font-weight: 600;
            font-size: 0.875rem;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
            border-color: #dee2e6;
            font-size: 0.875rem;
        }

        .btn-group {
            display: flex;
            gap: 2px;
        }

        .badge {
            font-size: 0.75rem;
        }

        .is-invalid {
            border-color: #dc3545 !important;
            background-color: rgba(220, 53, 69, 0.05);
        }

        /* Modal styling */
        #gajiModal .form-group {
            margin-bottom: 0.75rem;
        }

        #gajiModal .form-label {
            font-size: 0.8rem;
        }

        #gajiModal input[type="number"] {
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .table {
                font-size: 0.75rem;
            }

            .btn-group .btn {
                padding: 0.125rem 0.25rem;
                font-size: 0.7rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let currentGajiId = null;
            let currentMode = 'create';

            // Format Rupiah
            function formatRupiah(num) {
                return new Intl.NumberFormat('id-ID').format(Math.round(num || 0));
            }

            function getVal(id) {
                return parseFloat($(id).val() || 0) || 0;
            }

            // Calculate salary totals
            function hitungGaji() {
                const ttp = getVal('#modal_gj_pokok') + getVal('#modal_tunjab') + getVal('#modal_tunkom') +
                    getVal('#modal_fot') + getVal('#modal_tunmal');

                const tdkTtp = getVal('#modal_lbr_harian') + getVal('#modal_lbr_perjam') + getVal(
                    '#modal_tukin') + getVal('#modal_insentif') + getVal('#modal_bonus') + getVal(
                    '#modal_thr');

                const potongan = getVal('#modal_bpjs_tkj') + getVal('#modal_bpjs_kes') + getVal(
                    '#modal_iuran_koperasi') + getVal('#modal_tps_kry') + getVal('#modal_pjk_pkp') +
                    getVal('#modal_pjk_pph') + getVal('#modal_ptg_thr') + getVal('#modal_pjm_kop') +
                    getVal('#modal_dda_sanksi');

                const totalPendapatan = ttp + tdkTtp;
                const gajiBersih = totalPendapatan - potongan;

                $('#modal_total_ttp').val(formatRupiah(ttp));
                $('#modal_total_tdk_ttp').val(formatRupiah(tdkTtp));
                $('#modal_total_pendapatan').val(formatRupiah(totalPendapatan));
                $('#modal_total_potongan').val(formatRupiah(potongan));
                $('#modal_gaji_bersih').val(formatRupiah(gajiBersih));
            }

            // Salary input change
            $(document).on('input', '.salary-input', function() {
                hitungGaji();
            });

            // Set modal mode
            function setModalMode(mode) {
                currentMode = mode;
                const header = $('#gajiModalHeader');
                const modeIndicator = $('#modeIndicator');
                const saveBtn = $('#saveGajiBtn');
                const editBtn = $('#editGajiBtn');
                const formInputs = $('#gajiForm input, #gajiForm select');

                switch (mode) {
                    case 'create':
                        header.removeClass('bg-info').addClass('bg-primary');
                        $('#gajiModalTitle').text('Tambah Data Gaji Baru');
                        modeIndicator.hide();
                        saveBtn.show().html('<i class="fas fa-save me-1"></i>Simpan');
                        editBtn.hide();
                        formInputs.prop('disabled', false);
                        break;

                    case 'edit':
                        header.removeClass('bg-info').addClass('bg-primary');
                        $('#gajiModalTitle').text('Edit Data Gaji');
                        modeIndicator.hide();
                        saveBtn.show().html('<i class="fas fa-save me-1"></i>Update');
                        editBtn.hide();
                        formInputs.prop('disabled', false);
                        break;

                    case 'view':
                        header.removeClass('bg-primary').addClass('bg-info');
                        $('#gajiModalTitle').text('Detail Data Gaji');
                        modeIndicator.show();
                        saveBtn.hide();
                        editBtn.show();
                        formInputs.prop('disabled', true);
                        break;
                }
            }

            // Add Gaji Button
            $('#addGajiBtn').on('click', function() {
                resetGajiForm();
                setModalMode('create');
                $('#gajiModal').modal('show');
            });

            // Show Gaji Button
            $(document).on('click', '.show-gaji-btn', function() {
                const gajiId = $(this).data('gaji-id');
                currentGajiId = gajiId;
                setModalMode('view');
                loadGajiData(gajiId);
                $('#gajiModal').modal('show');
            });

            // Edit Gaji Button
            $(document).on('click', '.edit-gaji-btn', function() {
                const gajiId = $(this).data('gaji-id');
                currentGajiId = gajiId;
                setModalMode('edit');
                loadGajiData(gajiId);
                $('#gajiModal').modal('show');
            });

            // Edit button in view mode
            $('#editGajiBtn').on('click', function() {
                if (currentGajiId) {
                    setModalMode('edit');
                }
            });

            // Delete Gaji Button
            $(document).on('click', '.delete-gaji-btn', function() {
                currentGajiId = $(this).data('gaji-id');
                $('#deleteGajiModal').modal('show');
            });

            // Save Gaji
            $('#saveGajiBtn').on('click', function() {
                const formData = new FormData($('#gajiForm')[0]);

                if (currentMode === 'edit' && currentGajiId) {
                    formData.append('_method', 'PUT');
                }

                const url = currentMode === 'edit' ?
                    `/data-gaji/gajis/${currentGajiId}` :
                    '{{ route('data-gaji.gajis.store') }}';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#saveGajiBtn').prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...');
                    },
                    success: function(response) {
                        $('#saveGajiBtn').prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i>Simpan');

                        if (response.success) {
                            $('#gajiModal').modal('hide');
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        $('#saveGajiBtn').prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i>Simpan');

                        let errorMsg = 'Terjadi kesalahan saat menyimpan data';
                        if (xhr.responseJSON?.message) {
                            errorMsg = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            title: 'Error!',
                            text: errorMsg,
                            icon: 'error'
                        });
                    }
                });
            });

            // Delete confirmation
            $('#confirmDeleteGaji').on('click', function() {
                if (!currentGajiId) return;

                $.ajax({
                    url: `/data-gaji/gajis/${currentGajiId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#deleteGajiModal').modal('hide');
                        if (response.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        $('#deleteGajiModal').modal('hide');
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Gagal menghapus data',
                            icon: 'error'
                        });
                    }
                });
            });

            // Load Gaji Data
            function loadGajiData(gajiId) {
                $.ajax({
                    url: `/data-gaji/gajis/${gajiId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success && response.data) {
                            const data = response.data;

                            $('#gaji_id').val(data.id);
                            $('#modal_gj_pokok').val(data.gj_pokok || 0);
                            $('#modal_tunjab').val(data.tunjab || 0);
                            $('#modal_tunkom').val(data.tunkom || 0);
                            $('#modal_fot').val(data.fot || 0);
                            $('#modal_tunmal').val(data.tunmal || 0);
                            $('#modal_lbr_harian').val(data.lbr_harian || 0);
                            $('#modal_lbr_perjam').val(data.lbr_perjam || 0);
                            $('#modal_tukin').val(data.tukin || 0);
                            $('#modal_insentif').val(data.insentif || 0);
                            $('#modal_bonus').val(data.bonus || 0);
                            $('#modal_thr').val(data.thr || 0);
                            $('#modal_bpjs_tkj').val(data.bpjs_tkj || 0);
                            $('#modal_bpjs_kes').val(data.bpjs_kes || 0);
                            $('#modal_iuran_koperasi').val(data.iuran_koperasi || 0);
                            $('#modal_tps_kry').val(data.tps_kry || 0);
                            $('#modal_pjk_pkp').val(data.pjk_pkp || 0);
                            $('#modal_pjk_pph').val(data.pjk_pph || 0);
                            $('#modal_ptg_thr').val(data.ptg_thr || 0);
                            $('#modal_pjm_kop').val(data.pjm_kop || 0);
                            $('#modal_dda_sanksi').val(data.dda_sanksi || 0);
                            $('#modal_bpjs_tkj_prs').val(data.bpjs_tkj_prs || 0);
                            $('#modal_bpjs_kes_prs').val(data.bpjs_kes_prs || 0);
                            $('#modal_tps_prs').val(data.tps_prs || 0);
                            $('#modal_askes_prs').val(data.askes_prs || 0);

                            hitungGaji();
                        }
                    }
                });
            }

            // Reset form
            function resetGajiForm() {
                $('#gajiForm')[0].reset();
                $('#gaji_id').val('');
                currentGajiId = null;
                hitungGaji();
            }

            // Modal hidden
            $('#gajiModal').on('hidden.bs.modal', function() {
                resetGajiForm();
            });

            console.log('Data Gaji Edit Modal initialized!');
        });
    </script>
@endpush