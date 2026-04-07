@extends('layouts.app')

@section('title', 'Edit Data Gaji')

@section('content')
    <div class="container">
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

                        <!-- Nav tabs for form sections -->
                        <ul class="nav nav-tabs mb-4" id="formTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="karyawan-tab" data-bs-toggle="tab"
                                    data-bs-target="#karyawan" type="button" role="tab">
                                    <i class="fas fa-user me-1"></i> Data Karyawan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="gaji-tab" data-bs-toggle="tab" data-bs-target="#gaji"
                                    type="button" role="tab">
                                    <i class="fas fa-money-bill-wave me-1"></i> Data Gaji
                                    <span class="badge bg-primary ms-1" id="gajiCount">{{ $allGajis->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pendidikan-tab" data-bs-toggle="tab"
                                    data-bs-target="#pendidikan" type="button" role="tab">
                                    <i class="fas fa-graduation-cap me-1"></i> Pendidikan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="karir-tab" data-bs-toggle="tab" data-bs-target="#karir"
                                    type="button" role="tab">
                                    <i class="fas fa-briefcase me-1"></i> Jenjang Karir
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="hubin-tab" data-bs-toggle="tab" data-bs-target="#hubin"
                                    type="button" role="tab">
                                    <i class="fas fa-user-check me-1"></i> Hubungan Industrial
                                </button>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!-- Tab 1: Data Karyawan -->
                            <div class="tab-pane fade show active" id="karyawan" role="tabpanel">
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

                            <!-- Tab 2: Data Gaji (CRUD Table) -->
                            <div class="tab-pane fade" id="gaji" role="tabpanel">
                                <div class="card border-primary mb-4">
                                    <div
                                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-money-bill-wave me-2"></i>Manajemen Data Gaji Karyawan
                                        </h5>
                                        <button type="button" class="btn btn-light btn-sm" id="addSalaryBtn">
                                            <i class="fas fa-plus me-1"></i> Tambah Data Gaji
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="salariesTable"
                                                class="table table-bordered table-striped data-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="3%" class="text-center">No</th>
                                                        <th width="10%" class="text-center">ID Gaji</th>
                                                        <th width="10%" class="text-center">Gaji Pokok</th>
                                                        <th width="10%" class="text-center">Total Pendapatan</th>
                                                        <th width="10%" class="text-center">Total Potongan</th>
                                                        <th width="10%" class="text-center">Gaji Diterima</th>
                                                        <th width="8%" class="text-center">Status</th>
                                                        <th width="6%" class="text-center">Create</th>
                                                        <th width="6%" class="text-center">Update</th>
                                                        <th width="10%" class="text-center no-wrap">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="salariesTableBody">
                                                    @foreach ($allGajis as $index => $salary)
                                                        <tr data-salary-id="{{ $salary->id }}"
                                                            data-salary-status="{{ $salary->sts_data_gaji ?? 'AKTIF' }}">
                                                            <td class="text-center">{{ $index + 1 }}</td>
                                                            <td class="text-center">
                                                                <small class="fw-bold">{{ $salary->id_gaji ?: '-' }}</small>
                                                            </td>
                                                            <td class="text-end">
                                                                Rp {{ number_format($salary->gj_pokok ?? 0, 0, ',', '.') }}
                                                            </td>
                                                            <td class="text-end">
                                                                Rp {{ number_format($salary->ttl_pendapatan ?? 0, 0, ',', '.') }}
                                                            </td>
                                                            <td class="text-end">
                                                                Rp {{ number_format($salary->ttl_potongan ?? 0, 0, ',', '.') }}
                                                            </td>
                                                            <td class="text-end fw-bold text-success">
                                                                Rp {{ number_format($salary->ttl_terima_gaji ?? 0, 0, ',', '.') }}
                                                            </td>
                                                            <td class="text-center">
                                                                @if(($salary->sts_data_gaji ?? 'AKTIF') === 'AKTIF')
                                                                    <span class="badge bg-success">AKTIF</span>
                                                                @else
                                                                    <span class="badge bg-secondary">NON-AKTIF</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ $salary->creator ? $salary->creator->nama_kry : '-' }}</small>
                                                                <br><small
                                                                    class="text-muted">{{ $salary->created_at ? $salary->created_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ $salary->updater ? $salary->updater->nama_kry : '-' }}</small>
                                                                <br><small
                                                                    class="text-muted">{{ $salary->updated_at ? $salary->updated_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td class="text-center no-wrap">
                                                                <div class="btn-group" role="group">
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-info show-salary-btn"
                                                                        data-salary-id="{{ $salary->id }}"
                                                                        data-bs-toggle="tooltip" title="Detail">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-warning edit-salary-btn"
                                                                        data-salary-id="{{ $salary->id }}"
                                                                        data-bs-toggle="tooltip" title="Edit">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger delete-salary-btn"
                                                                        data-salary-id="{{ $salary->id }}"
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

                            <!-- Tab 3: Pendidikan -->
                            <div class="tab-pane fade" id="pendidikan" role="tabpanel">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-graduation-cap me-2"></i>Pendidikan
                                            Terakhir</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data berikut bersifat read-only dan diambil dari data karyawan.
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
                                                        value="{{ $dataGaji->karyawan->institusi_skl ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Fakultas</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->fakultas_skl ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jurusan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->jurusan_skl ?? '-' }}" readonly>
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

                            <!-- Tab 4: Jenjang Karir -->
                            <div class="tab-pane fade" id="karir" role="tabpanel">
                                <div class="card border-warning mb-4">
                                    <div class="card-header bg-warning bg-opacity-25">
                                        <h5 class="mb-0 text-dark"><i class="fas fa-briefcase me-2"></i>Jenjang Karir
                                            Saat Ini</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data berikut bersifat read-only dan diambil dari data karyawan.
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Departemen</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->departemenRelation->nama_dep ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Singkatan Dep.</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->skt_dep ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jabatan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->departemenRelation->nama_jbt ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Singkatan Jbt.</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->skt_jbt ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Wilayah Kerja</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->wilayahKerjaRelation->wilayah_krj ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Unit Kerja</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->unitKerjaRelation->area_krj ?? '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">SKT Wilayah Kerja</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->skt_wil_krj ?? '-' }}" readonly>
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

                            <!-- Tab 5: Hubungan Industrial -->
                            <div class="tab-pane fade" id="hubin" role="tabpanel">
                                <div class="card border-info mb-4">
                                    <div class="card-header bg-info bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-user-check me-2"></i>Hubungan
                                            Industrial</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data berikut bersifat read-only dan diambil dari data karyawan.
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
                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-danger" id="deleteAllSalariesBtn"
                                        data-employee-id="{{ $dataGaji->id }}"
                                        data-employee-name="{{ $dataGaji->karyawan->nama ?? 'N/A' }}">
                                        <i class="fas fa-trash me-1"></i> Hapus Semua Data Gaji
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary Modal (Create/Edit/Show) -->
    <div class="modal fade" id="salaryModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" id="salaryModalHeader">
                    <h5 class="modal-title text-white" id="salaryModalTitle">Data Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="salaryForm">
                        <input type="hidden" id="salary_id" name="salary_id">
                        <input type="hidden" name="employee_id" value="{{ $dataGaji->id_karyawan }}">

                        <!-- Mode Indicator -->
                        <div class="alert alert-info" id="modeIndicator" style="display: none;">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="modeText">Mode Detail - Data hanya dapat dilihat</span>
                        </div>

                        <!-- ===== ROW UTAMA: Pendapatan (Kiri) | Potongan (Kanan) ===== -->
                        <div class="row">

                            <!-- ===== KOLOM KIRI: Pendapatan Tetap + Tidak Tetap ===== -->
                            <div class="col-md-6">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-wallet me-2"></i>Pendapatan</h5>
                                    </div>
                                    <div class="card-body">

                                        <!-- Pendapatan Tetap -->
                                        <div class="mb-4">
                                            <h6 class="text-success fw-bold mb-3 pb-2 border-bottom">
                                                <i class="fas fa-check-circle me-2"></i>Pendapatan Tetap
                                            </h6>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_gj_pokok" class="col-sm-5 col-form-label">Gaji Pokok</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_gj_pokok"
                                                            name="gj_pokok" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_tunjab" class="col-sm-5 col-form-label">Tunjangan Jabatan</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_tunjab"
                                                            name="tunjab" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_tunkom" class="col-sm-5 col-form-label">Tunjangan Komunikasi</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_tunkom"
                                                            name="tunkom" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_fot" class="col-sm-5 col-form-label">Fix Over Time (FOT)</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_fot"
                                                            name="fot" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_tunmal" class="col-sm-5 col-form-label">Tunjangan Kemahalan</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_tunmal"
                                                            name="tunmal" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label class="col-sm-5 col-form-label fw-bold">Jumlah Pendapatan Tetap</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-end"
                                                            id="modal_total_pendapatan_tetap" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Pendapatan Tidak Tetap -->
                                        <div>
                                            <h6 class="text-warning fw-bold mb-3 pb-2 border-bottom">
                                                <i class="fas fa-coins me-2"></i>Pendapatan Tidak Tetap
                                            </h6>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_lbr_harian" class="col-sm-5 col-form-label">Lembur Harian</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_lbr_harian"
                                                            name="lbr_harian" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_lbr_perjam" class="col-sm-5 col-form-label">Lembur Per Jam</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_lbr_perjam"
                                                            name="lbr_perjam" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_tukin" class="col-sm-5 col-form-label">Tunjangan Kinerja</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_tukin"
                                                            name="tukin" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_insentif" class="col-sm-5 col-form-label">Insentif</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_insentif"
                                                            name="insentif" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_bonus" class="col-sm-5 col-form-label">Bonus</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_bonus"
                                                            name="bonus" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_thr" class="col-sm-5 col-form-label">Tunjangan Hari Raya (THR)</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_thr"
                                                            name="thr" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label class="col-sm-5 col-form-label fw-bold">Jumlah Pendapatan Tidak Tetap</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-end"
                                                            id="modal_total_pendapatan_tidak_tetap" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- ===== KOLOM KANAN: Potongan ===== -->
                            <div class="col-md-6">
                                <div class="card border-danger mb-4" style="height: 950px">
                                    <div class="card-header bg-danger bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-minus-circle me-2"></i>Potongan</h5>
                                    </div>
                                    <div class="card-body">

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_bpjs_tkj" class="col-sm-5 col-form-label">BPJS Naker</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_bpjs_tkj"
                                                        name="bpjs_tkj" value="0" min="0" step="100">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_bpjs_kes" class="col-sm-5 col-form-label">BPJS Kesehatan</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_bpjs_kes"
                                                        name="bpjs_kes" value="0" min="0" step="100">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_iuran_koperasi" class="col-sm-5 col-form-label">Iuran Wajib Koperasi</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_iuran_koperasi"
                                                        name="iuran_koperasi" value="0" min="0" step="100">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_tps_kry" class="col-sm-5 col-form-label">Tabungan Pensiun</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_tps_kry"
                                                        name="tps_kry" value="0" min="0" step="100">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_pjk_pkp" class="col-sm-5 col-form-label">Pajak PKP</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_pjk_pkp"
                                                        name="pjk_pkp" value="0" min="0" step="100">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_pjk_pph" class="col-sm-5 col-form-label">Pajak PPh</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_pjk_pph"
                                                        name="pjk_pph" value="0" min="0" step="100">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_ptg_thr" class="col-sm-5 col-form-label">Potongan THR</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_ptg_thr"
                                                        name="ptg_thr" value="0" min="0" step="100">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_pjm_kop" class="col-sm-5 col-form-label">Pinjaman Koperasi</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_pjm_kop"
                                                        name="pjm_kop" value="0" min="0" step="100">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_dda_sanksi" class="col-sm-5 col-form-label">Denda Sanksi</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_dda_sanksi"
                                                        name="dda_sanksi" value="0" min="0" step="100">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label class="col-sm-5 col-form-label fw-bold">Jumlah Potongan</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" class="form-control fw-bold text-end"
                                                        id="modal_total_potongan" readonly>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>{{-- end row utama --}}

                        <!-- ===== RINGKASAN GAJI ===== -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-calculator me-2"></i>Ringkasan Gaji</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Total Pendapatan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-end"
                                                            id="modal_total_pendapatan" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Total Potongan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-end"
                                                            id="modal_total_potongan_summary" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Gaji Diterima</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-end"
                                                            id="modal_gaji_bersih" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== BEBAN TANGGUNGAN PERUSAHAAN ===== -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-building me-2"></i>Beban Tanggungan Perusahaan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="modal_bpjs_tkj_prs" class="form-label fw-bold">BPJS Naker</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_bpjs_tkj_prs"
                                                            name="bpjs_tkj_prs" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="modal_bpjs_kes_prs" class="form-label fw-bold">BPJS Kes</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_bpjs_kes_prs"
                                                            name="bpjs_kes_prs" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="modal_tps_prs" class="form-label fw-bold">Tabungan Pensiun</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_tps_prs"
                                                            name="tps_prs" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="modal_askes_prs" class="form-label fw-bold">Asuransi Kesehatan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_askes_prs"
                                                            name="askes_prs" value="0" min="0" step="100">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== STATUS DATA GAJI ===== -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-secondary">
                                    <div class="card-header bg-secondary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-info-circle me-2"></i>Status Data Gaji</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="modal_sts_data_gaji" class="form-label fw-bold">
                                                        Status Data Gaji <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                        <select class="form-select" id="modal_sts_data_gaji" name="sts_data_gaji">
                                                            <option value="AKTIF">AKTIF</option>
                                                            <option value="NON-AKTIF">NON-AKTIF</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="modal_field_tgl_na" style="display: none;">
                                                <div class="form-group mb-3">
                                                    <label for="modal_tgl_na_gaji" class="form-label fw-bold">Tanggal Status Non Aktif</label>
                                                    <input type="date" class="form-control" id="modal_tgl_na_gaji" name="tgl_na_gaji">
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="modal_field_ket_na" style="display: none;">
                                                <div class="form-group mb-3">
                                                    <label for="modal_ket_na_gaji" class="form-label fw-bold">Keterangan Non Aktif</label>
                                                    <textarea class="form-control auto-uppercase" id="modal_ket_na_gaji"
                                                        name="ket_na_gaji" rows="1"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer" id="salaryModalFooter">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <span id="closeButtonText">Batal</span>
                    </button>
                    <button type="button" class="btn btn-primary" id="saveSalaryBtn">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                    <button type="button" class="btn btn-warning" id="editSalaryBtn" style="display: none;">
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteSalaryModal" tabindex="-1">
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
                    <button type="button" class="btn btn-danger" id="confirmDeleteSalary">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete All Salaries Modal -->
    <div class="modal fade" id="deleteAllSalariesModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus Semua Data Gaji</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan!</strong> Tindakan ini akan menghapus <strong>SEMUA</strong> data gaji karyawan.
                    </div>
                    <p>Apakah Anda yakin ingin menghapus <strong>semua data gaji</strong> untuk karyawan
                        <strong>{{ $dataGaji->karyawan->nama ?? 'N/A' }}</strong>?
                    </p>
                    <p class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteAllSalariesForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="confirmDeleteAllBtn">
                            <i class="fas fa-trash me-1"></i>Hapus Semua Data Gaji
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <style>
        .card-header {
            font-weight: 600;
        }

        .form-label {
            margin-bottom: 0.3rem;
        }

        .card {
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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

        .employee-photo-container {
            position: relative;
            width: 100%;
            max-width: 250px;
            margin: 0 auto;
        }

        #modal_total_pendapatan_tetap {
            text-align: right;
            padding-right: 24px !important;
        }
        #modal_total_pendapatan_tidak_tetap {
            text-align: right;
            padding-right: 24px !important;
        }
        #modal_total_potongan {
            text-align: right;
            padding-right: 24px !important;
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

        @media (max-width: 768px) {
            #salariesTable {
                font-size: 0.75rem;
            }

            #salariesTable th,
            #salariesTable td {
                font-size: 0.7rem;
                padding: 0.25rem;
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let currentSalaryId = null;
            let currentMode = 'create'; // 'create', 'edit', 'view'

            // ===== DATATABLES INITIALIZATION =====
            if ($.fn.DataTable.isDataTable('#salariesTable')) {
                $('#salariesTable').DataTable().destroy();
            }

            var salaryTable = $('#salariesTable').DataTable({
                responsive: true,
                destroy: true,
                language: {
                    "emptyTable": "Tidak ada data gaji",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ data keseluruhan)",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "search": "Cari data:",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                columnDefs: [{
                    orderable: false,
                    targets: [9]
                }],
                order: [[0, 'asc']],
                pageLength: 10,
                drawCallback: function() {
                    this.api().column(0, {
                        page: 'current'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
            });

            // ===== SALARY CALCULATION FUNCTIONS =====
            function formatRupiah(num) {
                return new Intl.NumberFormat('id-ID').format(Math.round(num || 0));
            }

            function getVal(id) {
                return parseFloat($('#' + id).val() || 0) || 0;
            }

            function hitungSemua() {
                const tetap = getVal('modal_gj_pokok') + getVal('modal_tunjab') + getVal('modal_tunkom') +
                              getVal('modal_fot') + getVal('modal_tunmal');

                const tidakTetap = getVal('modal_lbr_harian') + getVal('modal_lbr_perjam') + getVal('modal_tukin') +
                                   getVal('modal_insentif') + getVal('modal_bonus') + getVal('modal_thr');

                const potongan = getVal('modal_bpjs_tkj') + getVal('modal_bpjs_kes') + getVal('modal_iuran_koperasi') +
                                 getVal('modal_tps_kry') + getVal('modal_pjk_pkp') + getVal('modal_pjk_pph') +
                                 getVal('modal_ptg_thr') + getVal('modal_pjm_kop') + getVal('modal_dda_sanksi');

                const totalPendapatan = tetap + tidakTetap;
                const gajiBersih = totalPendapatan - potongan;

                $('#modal_total_pendapatan_tetap').val(formatRupiah(tetap));
                $('#modal_total_pendapatan_tidak_tetap').val(formatRupiah(tidakTetap));
                $('#modal_total_potongan').val(formatRupiah(potongan));
                $('#modal_total_pendapatan').val(formatRupiah(totalPendapatan));
                $('#modal_total_potongan_summary').val(formatRupiah(potongan));
                $('#modal_gaji_bersih').val(formatRupiah(gajiBersih));
            }

            // Attach calculation to all number inputs in modal
            $('#salaryModal input[type="number"]').on('input', hitungSemua);

            // ===== MODAL MODE MANAGEMENT =====
            function setModalMode(mode) {
                currentMode = mode;
                const header = $('#salaryModalHeader');
                const modeIndicator = $('#modeIndicator');
                const saveBtn = $('#saveSalaryBtn');
                const editBtn = $('#editSalaryBtn');
                const closeText = $('#closeButtonText');

                const formElements = $('#salaryForm input, #salaryForm select, #salaryForm textarea');

                switch (mode) {
                    case 'create':
                        header.removeClass('bg-info bg-warning').addClass('bg-primary');
                        $('#salaryModalTitle').text('Tambah Data Gaji Baru');
                        modeIndicator.hide();
                        saveBtn.show().html('<i class="fas fa-save me-1"></i>Simpan');
                        editBtn.hide();
                        closeText.text('Batal');
                        formElements.prop('disabled', false).prop('readonly', false);
                        // Keep summary fields readonly
                        $('#modal_total_pendapatan_tetap, #modal_total_pendapatan_tidak_tetap, #modal_total_potongan, #modal_total_pendapatan, #modal_total_potongan_summary, #modal_gaji_bersih').prop('readonly', true);
                        break;

                    case 'edit':
                        header.removeClass('bg-info bg-warning').addClass('bg-primary');
                        $('#salaryModalTitle').text('Edit Data Gaji');
                        modeIndicator.hide();
                        saveBtn.show().html('<i class="fas fa-save me-1"></i>Update');
                        editBtn.hide();
                        closeText.text('Batal');
                        formElements.prop('disabled', false).prop('readonly', false);
                        $('#modal_total_pendapatan_tetap, #modal_total_pendapatan_tidak_tetap, #modal_total_potongan, #modal_total_pendapatan, #modal_total_potongan_summary, #modal_gaji_bersih').prop('readonly', true);
                        break;

                    case 'view':
                        header.removeClass('bg-primary bg-warning').addClass('bg-info');
                        $('#salaryModalTitle').text('Detail Data Gaji');
                        modeIndicator.show().find('#modeText').text('Mode Detail - Data hanya dapat dilihat');
                        saveBtn.hide();
                        editBtn.show();
                        closeText.text('Tutup');
                        formElements.prop('disabled', true).prop('readonly', true);
                        break;
                }
            }

            // ===== STATUS CHANGE HANDLER =====
            $('#modal_sts_data_gaji').on('change', function() {
                if (currentMode === 'view') return;

                const status = $(this).val();
                if (status === 'NON-AKTIF') {
                    $('#modal_field_tgl_na, #modal_field_ket_na').show();
                } else {
                    $('#modal_field_tgl_na, #modal_field_ket_na').hide();
                    $('#modal_tgl_na_gaji, #modal_ket_na_gaji').val('');
                }
            });

            // ===== EVENT HANDLERS =====
            $('#addSalaryBtn').on('click', function() {
                resetSalaryModal();
                setModalMode('create');
                $('#salaryModal').modal('show');
            });

            $(document).on('click', '.show-salary-btn', function() {
                const salaryId = $(this).data('salary-id');
                currentSalaryId = salaryId;
                setModalMode('view');
                loadSalaryDataToModal(salaryId);
                $('#salaryModal').modal('show');
            });

            $(document).on('click', '.edit-salary-btn', function() {
                const salaryId = $(this).data('salary-id');
                currentSalaryId = salaryId;
                setModalMode('edit');
                loadSalaryDataToModal(salaryId);
                $('#salaryModal').modal('show');
            });

            $('#editSalaryBtn').on('click', function() {
                if (currentSalaryId) {
                    setModalMode('edit');
                }
            });

            $(document).on('click', '.delete-salary-btn', function() {
                currentSalaryId = $(this).data('salary-id');
                $('#deleteSalaryModal').modal('show');
            });

            // ===== SAVE SALARY =====
            $('#saveSalaryBtn').on('click', function() {
                if (currentMode === 'view') return;

                const formData = new FormData($('#salaryForm')[0]);

                if (currentMode === 'edit' && currentSalaryId) {
                    formData.append('_method', 'PUT');
                }

                const url = currentMode === 'edit' ?
                    `/data-gaji/salaries/${currentSalaryId}` :
                    '{{ route('data-gaji.salaries.store') }}';

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
                        $('#saveSalaryBtn').prop('disabled', true).text('Menyimpan...');
                    },
                    success: function(response) {
                        $('#saveSalaryBtn').prop('disabled', false).html('<i class="fas fa-save me-1"></i>Simpan');
                        if (response.success) {
                            $('#salaryModal').modal('hide');
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
                        $('#saveSalaryBtn').prop('disabled', false).html('<i class="fas fa-save me-1"></i>Simpan');

                        let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                        if (xhr.responseJSON?.message) {
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

            // ===== DELETE SALARY =====
            $('#confirmDeleteSalary').on('click', function() {
                if (!currentSalaryId) return;

                $.ajax({
                    url: `/data-gaji/salaries/${currentSalaryId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#deleteSalaryModal').modal('hide');
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
                        $('#deleteSalaryModal').modal('hide');
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal menghapus data gaji.',
                            icon: 'error'
                        });
                    }
                });
            });

            // ===== DELETE ALL SALARIES =====
            $('#deleteAllSalariesBtn').on('click', function() {
                const employeeId = $(this).data('employee-id');
                const deleteUrl = `/data-gaji/${employeeId}`;
                $('#deleteAllSalariesForm').attr('action', deleteUrl);
                $('#deleteAllSalariesModal').modal('show');
            });

            $('#deleteAllSalariesForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const actionUrl = form.attr('action');

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda benar-benar yakin? Semua data gaji akan dihapus permanen!',
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
                                $('#deleteAllSalariesModal').modal('hide');
                                Swal.fire({
                                    title: 'Berhasil Dihapus!',
                                    text: 'Semua data gaji karyawan telah dihapus.',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = "{{ route('data-gaji.index') }}";
                                });
                            },
                            error: function(xhr) {
                                $('#deleteAllSalariesModal').modal('hide');
                                Swal.fire({
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus data.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            // ===== HELPER FUNCTIONS =====
            function resetSalaryModal() {
                $('#salaryForm')[0].reset();
                $('#salary_id').val('');
                $('#modal_field_tgl_na, #modal_field_ket_na').hide();
                currentSalaryId = null;

                // Reset all number inputs to 0
                $('#salaryModal input[type="number"]').val(0);
                // Reset status to AKTIF
                $('#modal_sts_data_gaji').val('AKTIF');

                hitungSemua();
            }

            function loadSalaryDataToModal(salaryId) {
                $.ajax({
                    url: `/data-gaji/salaries/${salaryId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const salary = response.data;

                            // Populate all salary fields
                            $('#salary_id').val(salary.id);
                            $('#modal_gj_pokok').val(salary.gj_pokok || 0);
                            $('#modal_tunjab').val(salary.tunjab || 0);
                            $('#modal_tunkom').val(salary.tunkom || 0);
                            $('#modal_fot').val(salary.fot || 0);
                            $('#modal_tunmal').val(salary.tunmal || 0);

                            $('#modal_lbr_harian').val(salary.lbr_harian || 0);
                            $('#modal_lbr_perjam').val(salary.lbr_perjam || 0);
                            $('#modal_tukin').val(salary.tukin || 0);
                            $('#modal_insentif').val(salary.insentif || 0);
                            $('#modal_bonus').val(salary.bonus || 0);
                            $('#modal_thr').val(salary.thr || 0);

                            $('#modal_bpjs_tkj').val(salary.bpjs_tkj || 0);
                            $('#modal_bpjs_kes').val(salary.bpjs_kes || 0);
                            $('#modal_iuran_koperasi').val(salary.iuran_koperasi || 0);
                            $('#modal_tps_kry').val(salary.tps_kry || 0);
                            $('#modal_pjk_pkp').val(salary.pjk_pkp || 0);
                            $('#modal_pjk_pph').val(salary.pjk_pph || 0);
                            $('#modal_ptg_thr').val(salary.ptg_thr || 0);
                            $('#modal_pjm_kop').val(salary.pjm_kop || 0);
                            $('#modal_dda_sanksi').val(salary.dda_sanksi || 0);

                            $('#modal_bpjs_tkj_prs').val(salary.bpjs_tkj_prs || 0);
                            $('#modal_bpjs_kes_prs').val(salary.bpjs_kes_prs || 0);
                            $('#modal_tps_prs').val(salary.tps_prs || 0);
                            $('#modal_askes_prs').val(salary.askes_prs || 0);

                            $('#modal_sts_data_gaji').val(salary.sts_data_gaji || 'AKTIF');
                            $('#modal_tgl_na_gaji').val(salary.tgl_na_gaji || '');
                            $('#modal_ket_na_gaji').val(salary.ket_na_gaji || '');

                            // Trigger status change
                            $('#modal_sts_data_gaji').trigger('change');

                            // Calculate totals
                            hitungSemua();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal memuat data gaji.',
                            icon: 'error'
                        });
                    }
                });
            }

            // Modal reset when hidden
            $('#salaryModal').on('hidden.bs.modal', function() {
                resetSalaryModal();
            });
        });
    </script>
@endpush