@extends('layouts.app')

@section('title', 'Edit Data Dokumen')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-alt me-2"></i>Edit Data Dokumen</span>
                        <a href="{{ route('data-dokumen.index') }}" class="btn btn-light btn-sm">
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

                        <!-- Nav tabs for form sections -->
                        <ul class="nav nav-tabs mb-4" id="formTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="karyawan-tab" data-bs-toggle="tab"
                                    data-bs-target="#karyawan" type="button" role="tab" aria-controls="karyawan"
                                    aria-selected="true">
                                    <i class="fas fa-user me-1"></i> Data Karyawan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="dokumen-tab" data-bs-toggle="tab" data-bs-target="#dokumen"
                                    type="button" role="tab" aria-controls="dokumen" aria-selected="false">
                                    <i class="fas fa-file-alt me-1"></i> Data Dokumen
                                    <span class="badge bg-primary ms-1"
                                        id="dokumenCount">{{ $allDocuments->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="kontrak-tab" data-bs-toggle="tab" data-bs-target="#kontrak"
                                    type="button" role="tab" aria-controls="kontrak" aria-selected="false">
                                    <i class="fas fa-file-contract me-1"></i> Kontrak Kerja
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="karir-tab" data-bs-toggle="tab" data-bs-target="#karir"
                                    type="button" role="tab" aria-controls="karir" aria-selected="false">
                                    <i class="fas fa-briefcase me-1"></i> Jenjang Karir
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="hubin-tab" data-bs-toggle="tab" data-bs-target="#hubin"
                                    type="button" role="tab" aria-controls="hubin" aria-selected="false">
                                    <i class="fas fa-user-check me-1"></i> Hubungan Industrial
                                </button>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!-- Tab 1: Data Karyawan -->
                            <div class="tab-pane fade show active" id="karyawan" role="tabpanel"
                                aria-labelledby="karyawan-tab">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-user me-2"></i>Informasi Karyawan
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Foto Karyawan -->
                                            <div class="col-md-3 text-center mb-4">
                                                <div class="employee-photo-container">
                                                    @if ($dataDokumen->karyawan && $dataDokumen->karyawan->foto_dokumen)
                                                        <img src="{{ asset('storage/' . $dataDokumen->karyawan->foto_dokumen) }}"
                                                            alt="Foto {{ $dataDokumen->karyawan->nama ?? 'Karyawan' }}"
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
                                                                value="{{ $dataDokumen->karyawan->nama ?? '-' }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">NIK</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataDokumen->karyawan->nik ?? '-' }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">NRK</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataDokumen->karyawan->nrk ?? '-' }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">Jenis Kelamin</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataDokumen->karyawan->sex ?? '-' }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">Tempat, Tanggal Lahir</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataDokumen->karyawan->tpt_lahir ?? '' }}{{ $dataDokumen->karyawan->tgl_lahir ? ', ' . $dataDokumen->karyawan->tgl_lahir->format('d-m-Y') : '' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">Telepon</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataDokumen->karyawan->tlp1 ?? '-' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">Status Kawin</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataDokumen->karyawan->sts_nikah ?? '-' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">Jumlah Anak</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataDokumen->karyawan->jml_anak ?? '-' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">Email</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataDokumen->karyawan->email1 ?? '-' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Data Dokumen (CRUD Table) -->
                            <div class="tab-pane fade" id="dokumen" role="tabpanel" aria-labelledby="dokumen-tab">
                                <div class="card border-primary mb-4">
                                    <div
                                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-file-alt me-2"></i>Manajemen Dokumen Karyawan
                                        </h5>
                                        <button type="button" class="btn btn-light btn-sm" id="addDocumentBtn">
                                            <i class="fas fa-plus me-1"></i> Tambah Dokumen
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="documentsTable"
                                                class="table table-bordered table-striped data-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="3%" class="text-center">No</th>
                                                        <th width="10%" class="text-center">No. Dok</th>
                                                        <th width="10%" class="text-center">Kode Dok</th>
                                                        <th width="10%" class="text-center">Kategori</th>
                                                        <th width="12%" class="text-center">Jenis Dok</th>
                                                        <th width="8%" class="text-center">Tgl Terbit</th>
                                                        <th width="8%" class="text-center">Tgl Akhir</th>
                                                        <th width="6%" class="text-center">Durasi</th>
                                                        <th width="8%" class="text-center">Tgl Pgt</th>
                                                        <th width="8%" class="text-center">Peringatan</th>
                                                        <th width="8%" class="text-center">Status</th>
                                                        <th width="6%" class="text-center">Create</th>
                                                        <th width="6%" class="text-center">Update</th>
                                                        <th width="10%" class="text-center no-wrap">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="documentsTableBody">
                                                    @foreach ($allDocuments as $index => $document)
                                                        <tr data-document-id="{{ $document->id }}"
                                                            data-document-status="{{ $document->sts_dok }}"
                                                            data-tgl-peringatan="{{ $document->tgl_pgt_dok }}"
                                                            data-tgl-akhir="{{ $document->tgl_akr_dok }}"
                                                            data-jns-msb="{{ $document->jns_msb_dok }}">
                                                            <td class="text-center">{{ $index + 1 }}</td>
                                                            <td class="text-center">
                                                                <small
                                                                    class="fw-bold">{{ $document->no_dok ?: '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <span
                                                                    class="fw-bold">{{ $document->dokumenKaryawan->kode_dok_kry ?? ($document->kode_dok_kry ?? '-') }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ $document->dokumenKaryawan->ktg_dok_kry ?? ($document->ktg_dok_kry ?? '-') }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ $document->dokumenKaryawan->jns_dok_kry ?? ($document->jns_dok_kry ?? '-') }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $document->tgl_ttd ? \Carbon\Carbon::parse($document->tgl_ttd)->format('d-m-Y') : '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $document->tgl_akr_dok ? \Carbon\Carbon::parse($document->tgl_akr_dok)->format('d-m-Y') : '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                <span>{{ $document->msb_dok ? $document->msb_dok . ' bln' : '-' }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $document->tgl_pgt_dok ? \Carbon\Carbon::parse($document->tgl_pgt_dok)->format('d-m-Y') : '-' }}
                                                            </td>
                                                            <!-- Warning Column (Will be calculated by JS) -->
                                                            <td class="text-center sisa-peringatan-col">
                                                                <span class="badge bg-secondary">Loading...</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span
                                                                    class="badge {{ $document->sts_dok === 'AKTIF' ? 'bg-success' : 'bg-secondary' }}">
                                                                    {{ $document->sts_dok }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ $document->creator ? $document->creator->nama_kry : '-' }}</small>
                                                                <br><small
                                                                    class="text-muted">{{ $document->created_at ? $document->created_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ $document->updater ? $document->updater->nama_kry : '-' }}</small>
                                                                <br><small
                                                                    class="text-muted">{{ $document->updated_at ? $document->updated_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td class="text-center no-wrap">
                                                                <div class="btn-group" role="group"
                                                                    aria-label="Actions">
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-info show-document-btn"
                                                                        data-document-id="{{ $document->id }}"
                                                                        data-mode="view" data-bs-toggle="tooltip"
                                                                        title="Detail">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-warning edit-document-btn"
                                                                        data-document-id="{{ $document->id }}"
                                                                        data-mode="edit" data-bs-toggle="tooltip"
                                                                        title="Edit">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger delete-document-btn"
                                                                        data-document-id="{{ $document->id }}"
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

                            <!-- Tab 3: Kontrak Kerja -->
                            <div class="tab-pane fade" id="kontrak" role="tabpanel" aria-labelledby="kontrak-tab">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-file-contract me-2"></i>Data
                                            Kontrak Kerja</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data berikut akan otomatis terisi berdasarkan karyawan yang dipilih.
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Perusahaan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataDokumen->karyawan->perusahaanRelation->nama_prs1 ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Status Kontrak</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataDokumen->karyawan->kontrakRelation->nama_ktr ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal Awal Kontrak</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataDokumen->karyawan->tgl_awal_ktr ? $dataDokumen->karyawan->tgl_awal_ktr->format('d-m-Y') : '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal Akhir Kontrak</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataDokumen->karyawan->tgl_akhir_ktr ? $dataDokumen->karyawan->tgl_akhir_ktr->format('d-m-Y') : '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Durasi Kontrak (Bulan)</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataDokumen->karyawan->durasi_ktr ? $dataDokumen->karyawan->durasi_ktr . ' bulan' : '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 4: Jenjang Karir -->
                            <div class="tab-pane fade" id="karir" role="tabpanel" aria-labelledby="karir-tab">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-briefcase me-2"></i>Jenjang Karir
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data berikut akan otomatis terisi berdasarkan karyawan yang dipilih.
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Departemen</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataDokumen->karyawan->departemenRelation->nama_dep ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jabatan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataDokumen->karyawan->departemenRelation->nama_jbt ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Wilayah Kerja</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataDokumen->karyawan->wilayahKerjaRelation->wilayah_krj ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Unit Kerja</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataDokumen->karyawan->unitKerjaRelation->area_krj ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tugas & Tanggung Jawab</label>
                                                    <textarea class="form-control" rows="4" readonly>{{ $dataDokumen->karyawan->tugas ?? '-' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 5: Hubungan Industrial -->
                            <div class="tab-pane fade" id="hubin" role="tabpanel" aria-labelledby="hubin-tab">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-user-check me-2"></i>Hubungan
                                            Industrial</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data berikut bersifat read-only dan diambil dari data karyawan yang terpilih.
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal Masuk</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataDokumen->karyawan->tgl_masuk ? $dataDokumen->karyawan->tgl_masuk->format('d-m-Y') : '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Status Karyawan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataDokumen->karyawan->sts_kry ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal PHK</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataDokumen->karyawan->tgl_phk ? $dataDokumen->karyawan->tgl_phk->format('d-m-Y') : '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Keterangan PHK</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataDokumen->karyawan->ket_phk ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Global action buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('data-dokumen.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Kembali
                            </a>
                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-danger" id="deleteAllDocumentsBtn"
                                        data-employee-id="{{ $dataDokumen->id }}"
                                        data-employee-name="{{ $dataDokumen->karyawan->nama ?? 'N/A' }}">
                                        <i class="fas fa-trash me-1"></i> Hapus Semua Dokumen
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Modal (Unified: Create/Edit/Show) -->
    <div class="modal fade" id="documentModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" id="documentModalHeader">
                    <h5 class="modal-title text-white" id="documentModalTitle">Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="documentForm">
                        <input type="hidden" id="document_id" name="document_id">
                        <input type="hidden" name="employee_id" value="{{ $dataDokumen->id_data_kry }}">

                        <!-- Mode Indicator -->
                        <div class="alert alert-info" id="modeIndicator" style="display: none;">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="modeText">Mode Detail - Data hanya dapat dilihat</span>
                        </div>

                        <!-- Informasi Umum Dokumen -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary">
                                <h6 class="mb-0 text-white"><i class="fas fa-file-alt me-2"></i>Informasi Umum Dokumen
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Kategori Dokumen -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="modal_ktg_dok_kry" class="form-label fw-bold">Kategori Dokumen
                                                <span class="text-danger" id="required_ktg">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-folder"></i></span>
                                                <div style="flex: 1">
                                                    <select class="form-select select2" id="modal_ktg_dok_kry"
                                                        name="ktg_dok_kry">
                                                        <option value="">Pilih Kategori</option>
                                                        @php
                                                            $categoriesWithKode = $dokumenTypes
                                                                ->groupBy('ktg_dok_kry')
                                                                ->map(function ($items) {
                                                                    return $items->min('kode_dok_kry');
                                                                })
                                                                ->sortKeys();
                                                            $categories = $categoriesWithKode->keys();
                                                        @endphp
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category }}">{{ $category }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Jenis Dokumen -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="modal_jns_dok_kry" class="form-label fw-bold">Jenis Dokumen
                                                <span class="text-danger" id="required_jns">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                <div style="flex: 1">
                                                    <select class="form-select select2" id="modal_jns_dok_kry"
                                                        name="jns_dok_kry" disabled>
                                                        <option value="">Pilih Jenis Dokumen</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kode Dokumen (Auto) -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="modal_kode_dok_kry" class="form-label fw-bold">Kode Dokumen
                                                <span class="text-danger" id="required_kode">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                <input type="text" class="form-control bg-light"
                                                    id="modal_kode_dok_kry" placeholder="Otomatis terisi" readonly>
                                            </div>
                                            <div class="form-text text-muted">
                                                <i class="fas fa-info-circle me-1"></i>Kode otomatis berdasarkan jenis
                                                dokumen
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden fields -->
                                    <input type="hidden" id="modal_id_dokumen" name="id_dokumen">

                                    <!-- No Dokumen -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_no_dok" class="form-label fw-bold">No. Dokumen</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                <input type="text" class="form-control auto-uppercase"
                                                    id="modal_no_dok" name="no_dok">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tanggal TTD -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_tgl_ttd" class="form-label fw-bold">Tanggal
                                                TTD/Terbit</label>
                                            <input type="date" class="form-control" id="modal_tgl_ttd"
                                                name="tgl_ttd">
                                        </div>
                                    </div>

                                    <!-- File Dokumen -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_file_dok" class="form-label fw-bold">File Dokumen</label>
                                            <input type="file" class="form-control" id="modal_file_dok"
                                                name="file_dok" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                            <div class="form-text text-muted">Format: PDF, DOC, DOCX, JPG, PNG.
                                            </div>
                                            <div id="existing_file_info" style="display: none;" class="mt-2">
                                                <small class="text-success">
                                                    <i class="fas fa-file-check me-1"></i>
                                                    File sudah ada: <a href="#" id="existing_file_link"
                                                        target="_blank">Lihat Dokumen</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Dokumen -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_sts_dok" class="form-label fw-bold">Status Dokumen
                                                <span class="text-danger" id="required_sts">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                <div style="flex: 1">
                                                    <select class="form-select select2" id="modal_sts_dok"
                                                        name="sts_dok">
                                                        <option value="">Pilih Status</option>
                                                        <option value="AKTIF">AKTIF</option>
                                                        <option value="NON-AKTIF">NON-AKTIF</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Periode Dokumen -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary">
                                <h6 class="mb-0 text-white"><i class="fas fa-calendar-alt me-2"></i>Periode Dokumen</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Jenis Masa Berlaku -->
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="modal_jns_msb_dok" class="form-label fw-bold">Jenis Masa
                                                Berlaku</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="fas fa-hourglass-half"></i></span>
                                                <div style="flex: 1">
                                                    <select class="form-select select2" id="modal_jns_msb_dok"
                                                        name="jns_msb_dok">
                                                        <option value="">Pilih Jenis Masa Berlaku</option>
                                                        <option value="TETAP">TETAP</option>
                                                        <option value="PERPANJANGAN">PERPANJANGAN</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tanggal Akhir Berlaku -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_tgl_akr_dok" class="form-label fw-bold">Tanggal Akhir
                                                Berlaku</label>
                                            <input type="date" class="form-control" id="modal_tgl_akr_dok"
                                                name="tgl_akr_dok">
                                        </div>
                                    </div>

                                    <!-- Masa Berlaku (Bulan) -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_msb_dok" class="form-label fw-bold">Masa Berlaku
                                                (Bulan)</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                <input type="number" class="form-control" id="modal_msb_dok"
                                                    name="msb_dok" min="1" readonly>
                                            </div>
                                            <div class="form-text text-muted">
                                                <i class="fas fa-info-circle me-1"></i>Otomatis dihitung dari tanggal TTD
                                                ke tanggal akhir berlaku
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tanggal Pengingat -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_tgl_pgt_dok" class="form-label fw-bold">Tanggal
                                                Pengingat</label>
                                            <input type="date" class="form-control" id="modal_tgl_pgt_dok"
                                                name="tgl_pgt_dok">
                                            <div class="form-text text-muted">
                                                <i class="fas fa-bell me-1"></i>Tanggal untuk memulai pengingat
                                                perpanjangan
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Durasi Pengingat (Hari) -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_durasi_pgt" class="form-label fw-bold">Durasi Pengingat
                                                (Hari)</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                                <input type="number" class="form-control" id="modal_durasi_pgt"
                                                    name="durasi_pgt" min="1" readonly>
                                            </div>
                                            <div class="form-text text-muted">
                                                <i class="fas fa-info-circle me-1"></i>Otomatis dihitung dari tanggal
                                                pengingat ke tanggal akhir berlaku
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan Tambahan -->
                        <div class="card border-primary">
                            <div class="card-header bg-primary">
                                <h6 class="mb-0 text-white"><i class="fas fa-comment-alt me-2"></i>Keterangan Tambahan
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Keterangan -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_ket_dok" class="form-label fw-bold">Keterangan</label>
                                            <textarea class="form-control auto-uppercase" id="modal_ket_dok" name="ket_dok" rows="3"></textarea>
                                        </div>
                                    </div>

                                    <!-- Catatan -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_ctt_dok" class="form-label fw-bold">Catatan</label>
                                            <textarea class="form-control auto-uppercase" id="modal_ctt_dok" name="ctt_dok" rows="3"></textarea>
                                        </div>
                                    </div>

                                    <!-- Tanggal Non Aktif (conditional) -->
                                    <div class="col-md-6" id="modal_field_tgl_na" style="display: none;">
                                        <div class="form-group mb-3">
                                            <label for="modal_tgl_dok_na" class="form-label fw-bold">Tanggal Status Non
                                                Aktif</label>
                                            <input type="date" class="form-control" id="modal_tgl_dok_na"
                                                name="tgl_dok_na">
                                        </div>
                                    </div>

                                    <!-- Keterangan Non Aktif (conditional) -->
                                    <div class="col-md-6" id="modal_field_ket_na" style="display: none;">
                                        <div class="form-group mb-3">
                                            <label for="modal_ket_dok_na" class="form-label fw-bold">Keterangan Non
                                                Aktif</label>
                                            <textarea class="form-control auto-uppercase" id="modal_ket_dok_na" name="ket_dok_na" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" id="documentModalFooter">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <span id="closeButtonText">Batal</span>
                    </button>
                    <button type="button" class="btn btn-primary" id="saveDocumentBtn">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                    <button type="button" class="btn btn-warning" id="editDocumentBtn" style="display: none;">
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteDocumentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus dokumen ini?</p>
                    <p class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteDocument">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete All Documents Modal -->
    <div class="modal fade" id="deleteAllDocumentsModal" tabindex="-1">
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
                    <p>Apakah Anda yakin ingin menghapus <strong>semua data dokumen</strong> untuk karyawan
                        <strong>{{ $dataDokumen->karyawan->nama ?? 'N/A' }}</strong>?
                    </p>
                    <p class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Ini akan menghapus seluruh riwayat dokumen karyawan tersebut.
                        Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteAllDocumentsForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="confirmDeleteAllBtn">
                            <i class="fas fa-trash me-1"></i>Hapus Semua Dokumen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <style>
        /* ===== CARD STYLING ===== */
        .card-header {
            font-weight: 600;
        }

        .form-label {
            margin-bottom: 0.3rem;
        }

        .card {
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* ===== EMPLOYEE PHOTO STYLING ===== */
        .employee-photo-container {
            position: relative;
            width: 100%;
            max-width: 250px;
            margin: 0 auto;
        }

        .employee-photo {
            width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: cover;
            border: 3px solid #0d6efd;
        }

        .default-avatar {
            width: 100%;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            border: 2px dashed #dee2e6;
        }

        /* ===== TABLE STYLING ===== */
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

        /* ===== HIGHLIGHT ROWS STYLING ===== */
        table#documentsTable tbody tr.highlight-red {
            background-color: #fc0000 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        table#documentsTable tbody tr.highlight-yellow {
            background-color: #ffff00 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        table#documentsTable tbody tr.highlight-orange {
            background-color: #00e013 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        table#documentsTable tbody tr.highlight-gray {
            background-color: #cccccc !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        /* Ensure hover states don't override highlight colors */
        table#documentsTable tbody tr.highlight-red:hover {
            background-color: #ff3333 !important;
        }

        table#documentsTable tbody tr.highlight-yellow:hover {
            background-color: #ffff66 !important;
        }

        table#documentsTable tbody tr.highlight-orange:hover {
            background-color: #33ff33 !important;
        }

        table#documentsTable tbody tr.highlight-gray:hover {
            background-color: #dddddd !important;
        }

        /* Override Bootstrap's striped table styles */
        .table-striped>tbody>tr:nth-of-type(odd).highlight-red,
        .table-striped>tbody>tr:nth-of-type(even).highlight-red {
            background-color: #fc0000 !important;
        }

        .table-striped>tbody>tr:nth-of-type(odd).highlight-yellow,
        .table-striped>tbody>tr:nth-of-type(even).highlight-yellow {
            background-color: #ffff00 !important;
        }

        .table-striped>tbody>tr:nth-of-type(odd).highlight-orange,
        .table-striped>tbody>tr:nth-of-type(even).highlight-orange {
            background-color: #00e013 !important;
        }

        .table-striped>tbody>tr:nth-of-type(odd).highlight-gray,
        .table-striped>tbody>tr:nth-of-type(even).highlight-gray {
            background-color: #cccccc !important;
        }

        /* ===== INI YANG KURANG — WAJIB DITAMBAHKAN ===== */
        /* Memastikan warna background tr diturunkan ke td di dalamnya */
        table#documentsTable tbody tr.highlight-red>td,
        table#documentsTable tbody tr.highlight-yellow>td,
        table#documentsTable tbody tr.highlight-orange>td,
        table#documentsTable tbody tr.highlight-gray>td {
            background-color: inherit !important;
        }

        /* ===== HOVER EFFECT FOR TABLE ROWS ===== */
        #documentsTable tbody tr {
            transition: all 0.2s ease;
        }

        #documentsTable tbody tr:hover {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            cursor: pointer;
            position: relative;
            z-index: 1;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            #documentsTable {
                font-size: 0.75rem;
            }

            #documentsTable th,
            #documentsTable td {
                font-size: 0.7rem;
                padding: 0.25rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        // Store all document types data for cascading
        const allDokumenTypes = @json($dokumenTypes);

        $(document).ready(function() {
            let currentDocumentId = null;
            let currentMode = 'create'; // 'create', 'edit', 'view'

            console.log('📦 Document Types loaded:', allDokumenTypes.length);

            // ===== ROW WARNING CALCULATION =====
            function calculateRowWarning(row) {
                const tglPeringatan = row.data('tgl-peringatan');
                const documentStatus = row.data('document-status');
                const tglAkhir = row.data('tgl-akhir');
                const jnsMsb = row.data('jns-msb'); // ← PENTING: Pastikan data attribute ini ada di <tr>

                console.log('Calculating for document row:', {
                    'tgl-peringatan': tglPeringatan,
                    'document-status': documentStatus,
                    'tgl-akhir': tglAkhir,
                    'jns-msb': jnsMsb
                });

                // Skip NON-AKTIF documents - Abu-abu
                if (documentStatus === 'NON-AKTIF') {
                    return {
                        text: 'NON-AKTIF',
                        badgeClass: 'bg-secondary',
                        priority: 6,
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

            function applyDocumentRowProcessing() {
                console.log('Applying document row processing...');

                let expiredCount = 0;
                let warningCount = 0;

                // Reset all highlighting
                $('#documentsTable tbody tr').removeClass(
                    'highlight-red highlight-yellow highlight-orange highlight-gray');

                $('#documentsTable tbody tr').each(function() {
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

                    row.data('priority', warningData.priority);
                });

                console.log('Statistics updated - Expired:', expiredCount, 'Warning:', warningCount);
            }

            // ===== DATATABLES INITIALIZATION =====
            if ($.fn.DataTable.isDataTable('#documentsTable')) {
                $('#documentsTable').DataTable().destroy();
            }

            var documentTable = $('#documentsTable').DataTable({
                responsive: true,
                destroy: true,
                language: {
                    "emptyTable": "Tidak ada data dokumen",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ dokumen",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 dokumen",
                    "lengthMenu": "Tampilkan _MENU_ dokumen",
                    "search": "Cari dokumen:",
                    "zeroRecords": "Tidak ditemukan dokumen yang sesuai",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                columnDefs: [{
                    orderable: false,
                    targets: [13]
                }],
                order: [
                    [0, 'asc']
                ],
                pageLength: 10,
                drawCallback: function() {
                    this.api().column(0, {
                        page: 'current'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                    applyDocumentRowProcessing();
                }
            });

            // ===== MODAL MODE MANAGEMENT =====
            function setModalMode(mode) {
                currentMode = mode;
                console.log('Setting modal mode to:', mode);

                const header = $('#documentModalHeader');
                const modeIndicator = $('#modeIndicator');
                const saveBtn = $('#saveDocumentBtn');
                const editBtn = $('#editDocumentBtn');
                const closeText = $('#closeButtonText');

                const formElements = $('#documentForm input, #documentForm select, #documentForm textarea');
                const requiredSpans = $('.text-danger[id^="required_"]');

                switch (mode) {
                    case 'create':
                        header.removeClass('bg-info bg-warning').addClass('bg-primary');
                        $('#documentModalTitle').text('Tambah Dokumen Baru');
                        modeIndicator.hide();
                        saveBtn.show().html('<i class="fas fa-save me-1"></i>Simpan');
                        editBtn.hide();
                        closeText.text('Batal');

                        formElements.prop('disabled', false).prop('readonly', false);
                        $('#modal_msb_dok, #modal_durasi_pgt, #modal_kode_dok_kry').prop('readonly', true);
                        requiredSpans.show();
                        break;

                    case 'edit':
                        header.removeClass('bg-primary bg-primary').addClass('bg-primary');
                        $('#documentModalTitle').text('Edit Dokumen');
                        modeIndicator.hide();
                        saveBtn.show().html('<i class="fas fa-save me-1"></i>Update');
                        editBtn.hide();
                        closeText.text('Batal');

                        formElements.prop('disabled', false).prop('readonly', false);
                        $('#modal_msb_dok, #modal_durasi_pgt, #modal_kode_dok_kry').prop('readonly', true);
                        requiredSpans.show();
                        break;

                    case 'view':
                        header.removeClass('bg-primary bg-warning').addClass('bg-primary');
                        $('#documentModalTitle').text('Detail Dokumen');
                        modeIndicator.show().find('#modeText').text('Mode Detail - Data hanya dapat dilihat');
                        saveBtn.hide();
                        editBtn.show();
                        closeText.text('Tutup');

                        formElements.prop('disabled', true).prop('readonly', true);
                        $('#modal_file_dok').prop('disabled', true);
                        requiredSpans.hide();
                        break;
                }
            }

            // ===== INITIALIZE SELECT2 IN MODAL =====
            function initializeSelect2InDocumentModal() {
                $('#documentModal .select2').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }

                    $(this).select2({
                        theme: 'bootstrap-5',
                        dropdownParent: $('#documentModal'),
                        width: '100%',
                        placeholder: $(this).find('option:first').text() || 'Pilih...',
                        allowClear: true
                    });
                });
            }

            $('#documentModal').on('shown.bs.modal', function() {
                initializeSelect2InDocumentModal();
            });

            $('#documentModal').on('hidden.bs.modal', function() {
                $('#documentModal .select2').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });
            });

            // ===== EVENT HANDLERS =====
            $('#addDocumentBtn').on('click', function() {
                resetDocumentModal();
                setModalMode('create');
                $('#documentModal').modal('show');
            });

            $(document).on('click', '.show-document-btn', function() {
                const documentId = $(this).data('document-id');
                currentDocumentId = documentId;
                setModalMode('view');
                loadDocumentDataToModal(documentId);
                $('#documentModal').modal('show');
            });

            $(document).on('click', '.edit-document-btn', function() {
                const documentId = $(this).data('document-id');
                currentDocumentId = documentId;
                setModalMode('edit');
                loadDocumentDataToModal(documentId);
                $('#documentModal').modal('show');
            });

            $('#editDocumentBtn').on('click', function() {
                if (currentDocumentId) {
                    setModalMode('edit');
                }
            });

            $(document).on('click', '.delete-document-btn', function() {
                currentDocumentId = $(this).data('document-id');
                $('#deleteDocumentModal').modal('show');
            });

            // ===== CASCADING SELECT =====
            $('#modal_ktg_dok_kry').on('change', function() {
                if (currentMode === 'view') return;

                const selectedCategory = $(this).val();
                console.log('📂 Selected Category:', selectedCategory);

                $('#modal_jns_dok_kry').val('').trigger('change').prop('disabled', true);
                $('#modal_kode_dok_kry').val('');
                $('#modal_id_dokumen').val('');

                if (selectedCategory) {
                    const filteredByCategory = allDokumenTypes.filter(doc =>
                        doc.ktg_dok_kry === selectedCategory
                    );

                    const sortedDocs = filteredByCategory.sort((a, b) => {
                        return (a.kode_dok_kry || '').localeCompare(b.kode_dok_kry || '');
                    });

                    const uniqueJenisMap = new Map();
                    sortedDocs.forEach(doc => {
                        if (doc.jns_dok_kry && !uniqueJenisMap.has(doc.jns_dok_kry)) {
                            uniqueJenisMap.set(doc.jns_dok_kry, doc.kode_dok_kry);
                        }
                    });

                    const uniqueJenis = Array.from(uniqueJenisMap.entries())
                        .sort((a, b) => a[1].localeCompare(b[1]))
                        .map(entry => entry[0]);

                    $('#modal_jns_dok_kry').empty().append('<option value="">Pilih Jenis Dokumen</option>');
                    uniqueJenis.forEach(jenis => {
                        $('#modal_jns_dok_kry').append(
                            `<option value="${jenis}">${jenis}</option>`);
                    });

                    $('#modal_jns_dok_kry').prop('disabled', false);
                }
            });

            $('#modal_jns_dok_kry').on('change', function() {
                if (currentMode === 'view') return;

                const selectedCategory = $('#modal_ktg_dok_kry').val();
                const selectedJenis = $(this).val();

                $('#modal_kode_dok_kry').val('');
                $('#modal_id_dokumen').val('');

                if (selectedJenis) {
                    const matchedDocuments = allDokumenTypes
                        .filter(doc =>
                            doc.ktg_dok_kry === selectedCategory &&
                            doc.jns_dok_kry === selectedJenis
                        )
                        .sort((a, b) => (a.kode_dok_kry || '').localeCompare(b.kode_dok_kry || ''));

                    if (matchedDocuments.length > 0) {
                        const matchedDocument = matchedDocuments[0];

                        $('#modal_kode_dok_kry').val(matchedDocument.kode_dok_kry);
                        $('#modal_id_dokumen').val(matchedDocument.id);

                        $('#modal_kode_dok_kry').addClass('employee-validation-success');
                        setTimeout(() => {
                            $('#modal_kode_dok_kry').removeClass('employee-validation-success');
                        }, 2000);
                    }
                }
            });

            // ===== CONDITIONAL FIELDS =====
            $('#modal_jns_msb_dok').on('change', function() {
                if (currentMode === 'view') return;

                const jnsMsbDok = $(this).val();
                const fieldsTglAkhir = $('#modal_tgl_akr_dok');
                const fieldsMsb = $('#modal_msb_dok');
                const fieldsTglPengingat = $('#modal_tgl_pgt_dok');
                const fieldsDurasiPengingat = $('#modal_durasi_pgt');

                if (jnsMsbDok === 'TETAP') {
                    fieldsTglAkhir.prop('disabled', true).val('');
                    fieldsMsb.prop('disabled', true).val('');
                    fieldsTglPengingat.prop('disabled', true).val('');
                    fieldsDurasiPengingat.prop('disabled', true).val('');
                } else {
                    fieldsTglAkhir.prop('disabled', false);
                    fieldsMsb.prop('disabled', false);
                    fieldsTglPengingat.prop('disabled', false);
                    fieldsDurasiPengingat.prop('disabled', false);
                }
            });

            $('#modal_sts_dok').on('change', function() {
                if (currentMode === 'view') return;

                const stsDok = $(this).val();

                if (stsDok === 'NON-AKTIF') {
                    $('#modal_field_tgl_na, #modal_field_ket_na').show();
                } else {
                    $('#modal_field_tgl_na, #modal_field_ket_na').hide();
                    $('#modal_tgl_dok_na, #modal_ket_dok_na').val('');
                }
            });

            // ===== DATE CALCULATIONS =====
            $('#modal_tgl_ttd, #modal_tgl_akr_dok').on('change', function() {
                if (currentMode === 'view') return;
                if ($('#modal_jns_msb_dok').val() === 'TETAP') return;

                const signatureDate = $('#modal_tgl_ttd').val();
                const expiryDate = $('#modal_tgl_akr_dok').val();

                if (signatureDate && expiryDate) {
                    const start = new Date(signatureDate);
                    const end = new Date(expiryDate);
                    if (end > start) {
                        const months = (end.getFullYear() - start.getFullYear()) * 12 +
                            (end.getMonth() - start.getMonth());
                        $('#modal_msb_dok').val(months > 0 ? months : '');
                    }
                }
            });

            $('#modal_tgl_pgt_dok').on('change', function() {
                if (currentMode === 'view') return;

                const reminderDate = $(this).val();
                const expiryDate = $('#modal_tgl_akr_dok').val();

                if (reminderDate && expiryDate) {
                    const reminder = new Date(reminderDate);
                    const end = new Date(expiryDate);
                    if (reminder < end) {
                        const timeDiff = end.getTime() - reminder.getTime();
                        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                        $('#modal_durasi_pgt').val(daysDiff > 0 ? daysDiff : '');
                    }
                }
            });

            // ===== SAVE DOCUMENT =====
            $('#saveDocumentBtn').on('click', function() {
                if (currentMode === 'view') return;

                const formData = new FormData($('#documentForm')[0]);

                // Validate required fields
                const requiredFields = ['id_dokumen', 'sts_dok'];
                let isValid = true;
                let missingFields = [];

                requiredFields.forEach(field => {
                    const value = formData.get(field);
                    if (!value || value.trim() === '') {
                        isValid = false;
                        missingFields.push(field);
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        title: 'Form Tidak Lengkap!',
                        text: 'Field yang wajib diisi: ' + missingFields.join(', '),
                        icon: 'warning'
                    });
                    return;
                }

                if (currentMode === 'edit' && currentDocumentId) {
                    formData.append('_method', 'PUT');
                }

                const url = currentMode === 'edit' ?
                    `/data-dokumen/documents/${currentDocumentId}` :
                    '{{ route('data-dokumen.documents.store') }}';

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
                        $('#saveDocumentBtn').prop('disabled', true).text('Menyimpan...');
                    },
                    success: function(response) {
                        $('#saveDocumentBtn').prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i>Simpan');

                        if (response.success) {
                            $('#documentModal').modal('hide');
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
                        $('#saveDocumentBtn').prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i>Simpan');

                        let errorMessage = 'Terjadi kesalahan saat menyimpan dokumen.';

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON?.errors || {};
                            errorMessage = 'Terjadi kesalahan validasi:\n';
                            Object.keys(errors).forEach(key => {
                                errorMessage += `- ${key}: ${errors[key].join(', ')}\n`;
                            });
                        } else if (xhr.responseJSON?.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error'
                        });
                    }
                });
            });

            // ===== DELETE DOCUMENT =====
            $('#confirmDeleteDocument').on('click', function() {
                if (!currentDocumentId) return;

                $.ajax({
                    url: `/data-dokumen/documents/${currentDocumentId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#deleteDocumentModal').modal('hide');
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
                        $('#deleteDocumentModal').modal('hide');
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON?.message ||
                                'Gagal menghapus dokumen.',
                            icon: 'error'
                        });
                    }
                });
            });

            // ===== DELETE ALL DOCUMENTS =====
            $('#deleteAllDocumentsBtn').on('click', function() {
                const employeeId = $(this).data('employee-id');
                const employeeName = $(this).data('employee-name');

                const deleteUrl = `/data-dokumen/${employeeId}`;
                $('#deleteAllDocumentsForm').attr('action', deleteUrl);

                $('#deleteAllDocumentsModal').modal('show');
            });

            $('#deleteAllDocumentsForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const actionUrl = form.attr('action');

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda benar-benar yakin? Semua data dokumen akan dihapus permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: actionUrl,
                            type: 'POST',
                            data: form.serialize(),
                            success: function(response) {
                                $('#deleteAllDocumentsModal').modal('hide');
                                Swal.fire({
                                    title: 'Berhasil Dihapus!',
                                    text: 'Semua data dokumen karyawan telah dihapus.',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('data-dokumen.index') }}";
                                });
                            },
                            error: function(xhr) {
                                $('#deleteAllDocumentsModal').modal('hide');
                                Swal.fire({
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message ||
                                        'Terjadi kesalahan saat menghapus data.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            // ===== HELPER FUNCTIONS =====
            function resetDocumentModal() {
                $('#documentForm')[0].reset();
                $('#document_id').val('');
                $('#modal_field_tgl_na, #modal_field_ket_na').hide();
                $('#existing_file_info').hide();
                currentDocumentId = null;
            }

            function loadDocumentDataToModal(documentId) {
                $.ajax({
                    url: `/data-dokumen/documents/${documentId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const doc = response.data;

                            $('#document_id').val(doc.id);

                            // Set kategori → jenis → kode secara berurutan
                            if (doc.ktg_dok_kry) {
                                $('#modal_ktg_dok_kry').val(doc.ktg_dok_kry).trigger('change');

                                setTimeout(() => {
                                    if (doc.jns_dok_kry) {
                                        $('#modal_jns_dok_kry').val(doc.jns_dok_kry).trigger(
                                            'change');

                                        setTimeout(() => {
                                            $('#modal_id_dokumen').val(doc.id_dokumen ||
                                                '');
                                            $('#modal_kode_dok_kry').val(doc
                                                .kode_dok_kry || '');
                                        }, 200);
                                    }
                                }, 200);
                            }

                            // Set semua field lain — tanggal sudah format Y-m-d dari controller
                            $('#modal_no_dok').val(doc.no_dok || '');
                            $('#modal_tgl_ttd').val(doc.tgl_ttd || '');
                            $('#modal_tgl_akr_dok').val(doc.tgl_akr_dok || '');
                            $('#modal_msb_dok').val(doc.msb_dok || '');
                            $('#modal_tgl_pgt_dok').val(doc.tgl_pgt_dok || '');
                            $('#modal_durasi_pgt').val(doc.durasi_pgt || '');
                            $('#modal_ket_dok').val(doc.ket_dok || '');
                            $('#modal_ctt_dok').val(doc.ctt_dok || '');
                            $('#modal_tgl_dok_na').val(doc.tgl_dok_na || '');
                            $('#modal_ket_dok_na').val(doc.ket_dok_na || '');

                            // Trigger change SETELAH nilai di-set agar conditional fields muncul
                            $('#modal_jns_msb_dok').val(doc.jns_msb_dok || '').trigger('change');
                            $('#modal_sts_dok').val(doc.sts_dok || '').trigger('change');

                            // Refresh tampilan Select2
                            $('#modal_ktg_dok_kry, #modal_jns_dok_kry, #modal_jns_msb_dok, #modal_sts_dok')
                                .trigger('change.select2');

                            // Handle file existing
                            if (doc.file_dok) {
                                $('#existing_file_info').show();
                                $('#existing_file_link').attr('href', '/storage/' + doc.file_dok);
                            } else {
                                $('#existing_file_info').hide();
                            }
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal memuat data dokumen: ' + (xhr.responseJSON?.message ||
                                'Network error'),
                            icon: 'error'
                        });
                    }
                });
            }

            // Auto-uppercase
            document.querySelectorAll('input.auto-uppercase, textarea.auto-uppercase').forEach(function(element) {
                if (element.type === 'text' || element.tagName.toLowerCase() === 'textarea') {
                    element.addEventListener('input', function() {
                        this.value = this.value.toUpperCase();
                    });
                }
            });

            // Apply initial processing
            setTimeout(function() {
                applyDocumentRowProcessing();
            }, 1000);

            console.log('✅ Edit Data Dokumen initialized!');
        });
    </script>
@endpush
