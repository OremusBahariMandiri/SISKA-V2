@extends('layouts.app')

@section('title', 'Edit Data Jenjang Karir')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-chart-line me-2"></i>Edit Data Jenjang Karir</span>
                        <a href="{{ route('data-jenjang-karir.index') }}" class="btn btn-light btn-sm">
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

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs mb-4" id="formTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="karyawan-tab" data-bs-toggle="tab"
                                    data-bs-target="#karyawan" type="button" role="tab">
                                    <i class="fas fa-user me-1"></i> Data Karyawan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="jenjang-karir-tab" data-bs-toggle="tab"
                                    data-bs-target="#jenjang-karir" type="button" role="tab">
                                    <i class="fas fa-chart-line me-1"></i> Data Jenjang Karir
                                    <span class="badge bg-primary ms-1" id="karirCount">{{ $allCareers->count() }}</span>
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

                            <!-- ===== TAB 1: DATA KARYAWAN (read-only) ===== -->
                            <div class="tab-pane fade show active" id="karyawan" role="tabpanel">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-user me-2"></i>Informasi Karyawan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Foto -->
                                            <div class="col-md-3 text-center mb-4">
                                                <div class="employee-photo-container">
                                                    @if ($dataJenjangKarir->karyawan && $dataJenjangKarir->karyawan->foto_dokumen)
                                                        <img src="{{ asset('storage/' . $dataJenjangKarir->karyawan->foto_dokumen) }}"
                                                            alt="Foto {{ $dataJenjangKarir->karyawan->nama ?? 'Karyawan' }}"
                                                            class="img-fluid rounded shadow employee-photo"
                                                            onerror="this.style.display='none'; document.getElementById('foto_placeholder').style.display='flex'">
                                                        <div id="foto_placeholder" class="default-avatar rounded shadow"
                                                            style="display: none;">
                                                            <i class="fas fa-user-circle fa-8x text-secondary"></i>
                                                        </div>
                                                    @else
                                                        <div class="default-avatar rounded shadow">
                                                            <i class="fas fa-user-circle fa-8x text-secondary"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Info -->
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold">Nama Lengkap</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataJenjangKarir->karyawan->nama ?? '-' }}"
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold">NIK</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataJenjangKarir->karyawan->nik ?? '-' }}" readonly>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold">NRK</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataJenjangKarir->karyawan->nrk ?? '-' }}" readonly>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold">Jenis Kelamin</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataJenjangKarir->karyawan->sex ?? '-' }}" readonly>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold">Tempat, Tanggal Lahir</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataJenjangKarir->karyawan->tpt_lahir ?? '' }}{{ $dataJenjangKarir->karyawan->tgl_lahir ? ', ' . $dataJenjangKarir->karyawan->tgl_lahir->format('d-m-Y') : '' }}"
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold">Telepon</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataJenjangKarir->karyawan->tlp1 ?? '-' }}"
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold">Status Kawin</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataJenjangKarir->karyawan->sts_nikah ?? '-' }}"
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold">Jumlah Anak</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataJenjangKarir->karyawan->jml_anak ?? '-' }}"
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold">Email</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataJenjangKarir->karyawan->email1 ?? '-' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== TAB 2: DATA JENJANG KARIR (CRUD table) ===== -->
                            <div class="tab-pane fade" id="jenjang-karir" role="tabpanel">
                                <div class="card border-primary mb-4">
                                    <div
                                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Manajemen Jenjang Karir
                                            Karyawan</h5>
                                        <button type="button" class="btn btn-light btn-sm" id="addCareerBtn">
                                            <i class="fas fa-plus me-1"></i> Tambah Jenjang Karir
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="careersTable"
                                                class="table table-bordered table-striped data-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="3%" class="text-center">No</th>
                                                        <th width="10%" class="text-center">No. JK</th>
                                                        <th width="10%" class="text-center">Tgl Terbit</th>
                                                        <th width="10%" class="text-center">Kategori</th>
                                                        <th width="12%" class="text-center">Jenis Dokumen</th>
                                                        <th width="14%" class="text-center">Departemen</th>
                                                        <th width="12%" class="text-center">Jabatan</th>
                                                        <th width="10%" class="text-center">Wilayah Kerja</th>
                                                        <th width="7%" class="text-center">Create</th>
                                                        <th width="7%" class="text-center">Update</th>
                                                        <th width="10%" class="text-center no-wrap">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="careersTableBody">
                                                    @foreach ($allCareers as $index => $career)
                                                        <tr data-career-id="{{ $career->id }}">
                                                            <td class="text-center">{{ $index + 1 }}</td>
                                                            <td class="text-center">
                                                                <small class="fw-bold">{{ $career->no_jk ?: '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $career->tgl_ttd ? \Carbon\Carbon::parse($career->tgl_ttd)->format('d-m-Y') : '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ optional($career->dokumenKaryawan)->ktg_dok_kry ?? '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ optional($career->dokumenKaryawan)->jns_dok_kry ?? '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ optional($career->departemen)->nama_dep ?? '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ optional($career->departemen)->nama_jbt ?? '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ optional($career->wilayahKerja)->wilayah_krj ?? '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ $career->created_at ? $career->created_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ $career->updated_at ? $career->updated_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td class="text-center no-wrap">
                                                                <div class="btn-group" role="group">
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-info show-career-btn"
                                                                        data-career-id="{{ $career->id }}"
                                                                        title="Detail">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-warning edit-career-btn"
                                                                        data-career-id="{{ $career->id }}"
                                                                        title="Edit">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger delete-career-btn"
                                                                        data-career-id="{{ $career->id }}"
                                                                        title="Hapus">
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

                            <!-- ===== TAB 3: PENDIDIKAN (editable form) ===== -->
                            <div class="tab-pane fade" id="pendidikan" role="tabpanel">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-graduation-cap me-2"></i>Pendidikan
                                            Terakhir</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data pendidikan diambil langsung dari data karyawan dan bersifat read-only di
                                            sini.
                                            Untuk mengubah, silakan edit di halaman Data Karyawan.
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Jenjang Pendidikan</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $dataJenjangKarir->karyawan->jenjang_skl ?? '-' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Tanggal Lulus</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $dataJenjangKarir->karyawan->tgl_lulus_skl ? $dataJenjangKarir->karyawan->tgl_lulus_skl->format('d-m-Y') : '-' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Nama Institusi</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $dataJenjangKarir->karyawan->institusi_skl ?? '-' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Fakultas</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $dataJenjangKarir->karyawan->fakultas_skl ?? '-' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Jurusan</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $dataJenjangKarir->karyawan->jurusan_skl ?? '-' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Kota</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $dataJenjangKarir->karyawan->kota_skl ?? '-' }}" readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Gelar</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $dataJenjangKarir->karyawan->gelar_skl ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== TAB 4: JENJANG KARIR SAAT INI (read-only dari data karyawan) ===== -->
                            <div class="tab-pane fade" id="karir" role="tabpanel">
                                <div class="card border-warning mb-4">
                                    <div class="card-header bg-warning bg-opacity-25">
                                        <h5 class="mb-0 text-dark"><i class="fas fa-briefcase me-2"></i>Jenjang Karir Saat
                                            Ini</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data posisi saat ini diambil dari data karyawan dan bersifat read-only.
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Departemen</label>
                                                <input type="text" class="form-control"
                                                    value="{{ optional($dataJenjangKarir->karyawan->departemenRelation)->nama_dep ?? ($dataJenjangKarir->karyawan->departemen ?? '-') }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Singkatan Dep.</label>
                                                <input type="text" class="form-control"
                                                    value="{{ optional($dataJenjangKarir->karyawan->departemenRelation)->singkatan_dep ?? ($dataJenjangKarir->karyawan->skt_dep ?? '-') }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Jabatan</label>
                                                <input type="text" class="form-control"
                                                    value="{{ optional($dataJenjangKarir->karyawan->departemenRelation)->nama_jbt ?? ($dataJenjangKarir->karyawan->jabatan ?? '-') }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Singkatan Jbt.</label>
                                                <input type="text" class="form-control"
                                                    value="{{ optional($dataJenjangKarir->karyawan->departemenRelation)->singkatan_jbt ?? ($dataJenjangKarir->karyawan->skt_jbt ?? '-') }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold">Wilayah Kerja</label>
                                                <input type="text" class="form-control"
                                                    value="{{ optional($dataJenjangKarir->karyawan->wilayahKerjaRelation)->wilayah_krj ?? ($dataJenjangKarir->karyawan->wilker ?? '-') }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold">Unit Kerja</label>
                                                <input type="text" class="form-control"
                                                    value="{{ optional($dataJenjangKarir->karyawan->unitKerjaRelation)->area_krj ?? ($dataJenjangKarir->karyawan->unit_krj ?? '-') }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold">SKT Wilayah Kerja</label>
                                                <input type="text" class="form-control"
                                                    value="{{ optional($dataJenjangKarir->karyawan->unitKerjaRelation)->singkatan_wk ?? ($dataJenjangKarir->karyawan->skt_wil_krj ?? '-') }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold">Tugas & Tanggung Jawab</label>
                                                <textarea class="form-control" rows="4" readonly>{{ $dataJenjangKarir->karyawan->tugas ?? '-' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== TAB 5: HUBUNGAN INDUSTRIAL (read-only) ===== -->
                            <div class="tab-pane fade" id="hubin" role="tabpanel">
                                <div class="card border-info mb-4">
                                    <div class="card-header bg-info bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-user-check me-2"></i>Hubungan
                                            Industrial</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data berikut bersifat read-only dan diambil dari data karyawan yang terpilih.
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Tanggal Masuk</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $dataJenjangKarir->karyawan->tgl_masuk ? $dataJenjangKarir->karyawan->tgl_masuk->format('d-m-Y') : '-' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Status Karyawan</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $dataJenjangKarir->karyawan->sts_kry ?? '-' }}" readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Tanggal PHK</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $dataJenjangKarir->karyawan->tgl_phk ? $dataJenjangKarir->karyawan->tgl_phk->format('d-m-Y') : '-' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Keterangan PHK</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $dataJenjangKarir->karyawan->ket_phk ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- end tab-content --}}

                        <!-- Global action buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('data-jenjang-karir.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Kembali
                            </a>
                            <button type="button" class="btn btn-danger" id="deleteAllCareersBtn"
                                data-employee-id="{{ $dataJenjangKarir->id }}"
                                data-employee-name="{{ $dataJenjangKarir->karyawan->nama ?? 'N/A' }}">
                                <i class="fas fa-trash me-1"></i> Hapus Semua Jenjang Karir
                            </button>
                        </div>

                    </div>{{-- end card-body --}}
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL: Tambah / Edit / Detail Jenjang Karir ===== --}}
    <div class="modal fade" id="careerModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary" id="careerModalHeader">
                    <h5 class="modal-title text-white" id="careerModalTitle">Jenjang Karir</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="careerForm" enctype="multipart/form-data">
                        <input type="hidden" id="career_id" name="career_id">
                        <input type="hidden" name="employee_id" value="{{ $dataJenjangKarir->id_karyawan }}">

                        <div class="alert alert-info" id="modeIndicator" style="display: none;">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="modeText">Mode Detail - Data hanya dapat dilihat</span>
                        </div>

                        <!-- Informasi Umum -->
                        <div class="card border-secondary mb-4">
                            <div class="card-header bg-primary">
                                <h6 class="mb-0 text-white"><i class="fas fa-info-circle me-2"></i>Informasi Umum Jenjang
                                    Karir</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_no_jk" class="form-label fw-bold">No. Jenjang Karir</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                <input type="text" class="form-control auto-uppercase"
                                                    id="modal_no_jk" name="no_jk">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_tgl_ttd" class="form-label fw-bold">
                                                Tanggal Terbit <span class="text-danger required-mark">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="modal_tgl_ttd"
                                                name="tgl_ttd">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_ktg_dokumen" class="form-label fw-bold">Kategori Jenjang
                                                Karir</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-folder"></i></span>
                                                <div style="flex: 1">
                                                    <select class="form-select select2-modal" id="modal_ktg_dokumen"
                                                        name="ktg_dokumen">
                                                        <option value="">Pilih Kategori</option>
                                                        @php
                                                            $categories = $dokumenKaryawans
                                                                ->unique('ktg_dok_kry')
                                                                ->pluck('ktg_dok_kry')
                                                                ->filter()
                                                                ->sort();
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
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_id_dokumen_karyawan" class="form-label fw-bold">Jenis
                                                Jenjang Karir</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="fas fa-file-signature"></i></span>
                                                <div style="flex: 1">
                                                    <select class="form-select select2-modal"
                                                        id="modal_id_dokumen_karyawan" name="id_dokumen_karyawan"
                                                        disabled>
                                                        <option value="">Pilih Jenis</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="modal_file_dokumen" class="form-label fw-bold">File
                                                Dokumen</label>
                                            <input type="file" class="form-control" id="modal_file_dokumen"
                                                name="file_dokumen" accept=".pdf,.doc,.docx,.jpg,.png">
                                            <div class="form-text text-muted">Format: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)
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
                                </div>
                            </div>
                        </div>

                        <!-- Departemen & Jabatan -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary">
                                <h6 class="mb-0 text-white"><i class="fas fa-sitemap me-2"></i>Departemen & Jabatan</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_departemen_nama" class="form-label fw-bold">
                                                Departemen <span class="text-danger required-mark">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                                                <div style="flex: 1">
                                                    <select class="form-select select2-modal" id="modal_departemen_nama"
                                                        name="departemen_nama">
                                                        <option value="">Pilih Departemen</option>
                                                        @foreach ($departemens->groupBy('nama_dep') as $namaDep => $group)
                                                            <option value="{{ $namaDep }}"
                                                                data-singkatan="{{ $group->first()->singkatan_dep }}">
                                                                {{ $namaDep }} - {{ $group->first()->singkatan_dep }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">Singkatan Dep.</label>
                                            <input type="text" class="form-control" id="modal_info_singkatan_dep"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_id_departemen" class="form-label fw-bold">
                                                Jabatan <span class="text-danger required-mark">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                <div style="flex: 1">
                                                    <select class="form-select select2-modal" id="modal_id_departemen"
                                                        name="id_departemen" disabled>
                                                        <option value="">Pilih Jabatan</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">Singkatan Jbt.</label>
                                            <input type="text" class="form-control" id="modal_info_singkatan_jbt"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Wilayah Kerja -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary">
                                <h6 class="mb-0 text-white"><i class="fas fa-map-marked-alt me-2"></i>Wilayah Kerja</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_wilayah_kerja_nama" class="form-label fw-bold">Wilayah
                                                Kerja</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                <div style="flex: 1">
                                                    <select class="form-select select2-modal"
                                                        id="modal_wilayah_kerja_nama" name="wilayah_kerja_nama">
                                                        <option value="">Pilih Wilayah Kerja</option>
                                                        @foreach ($wilayahKerjas->groupBy('wilayah_krj') as $wilayahKrj => $group)
                                                            <option value="{{ $wilayahKrj }}">{{ $wilayahKrj }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">Singkatan Wilker</label>
                                            <input type="text" class="form-control" id="modal_info_skt_wilker_display"
                                                readonly placeholder="Pilih wilayah kerja terlebih dahulu">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_id_wilayah_kerja" class="form-label fw-bold">Area
                                                Kerja</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-users-cog"></i></span>
                                                <div style="flex: 1">
                                                    <select class="form-select select2-modal" id="modal_id_wilayah_kerja"
                                                        name="id_wilayah_kerja" disabled>
                                                        <option value="">Pilih Area Kerja</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">Singkatan Area Kerja</label>
                                            <input type="text" class="form-control" id="modal_info_skt_area" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tugas -->
                        <div class="card border-primary mb-0">
                            <div class="card-header bg-primary">
                                <h6 class="mb-0 text-white"><i class="fas fa-tasks me-2"></i>Tugas & Tanggung Jawab</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="modal_tugas" class="form-label fw-bold">Deskripsi Tugas</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                                        <textarea class="form-control auto-uppercase" id="modal_tugas" name="tugas" rows="4"
                                            placeholder="Masukkan deskripsi tugas dan tanggung jawab..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer" id="careerModalFooter">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <span id="closeButtonText">Batal</span>
                    </button>
                    <button type="button" class="btn btn-primary" id="saveCareerBtn">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                    <button type="button" class="btn btn-warning" id="editCareerBtn" style="display: none;">
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL: Hapus Satu ===== --}}
    <div class="modal fade" id="deleteCareerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data jenjang karir ini?</p>
                    <p class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteCareer">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL: Hapus Semua ===== --}}
    <div class="modal fade" id="deleteAllCareersModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus Semua Jenjang Karir</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan!</strong> Tindakan ini akan menghapus <strong>SEMUA</strong> data jenjang karir
                        karyawan.
                    </div>
                    <p>Apakah Anda yakin ingin menghapus <strong>semua data jenjang karir</strong> untuk karyawan
                        <strong>{{ $dataJenjangKarir->karyawan->nama ?? 'N/A' }}</strong>?
                    </p>
                    <p class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteAllCareersForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Hapus Semua
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

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 0.875rem;
            white-space: nowrap;
            vertical-align: middle;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .btn-group {
            display: flex;
            gap: 2px;
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .input-group .select2-container {
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
        }

        .input-group .select2-container .select2-selection {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-left: 0;
        }

        #careersTable tbody tr {
            transition: all 0.2s ease;
        }

        #careersTable tbody tr:hover {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            cursor: pointer;
            position: relative;
            z-index: 1;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Pass all dokumen karyawan to JS for cascading
        const allDokumenKaryawan = @json($dokumenKaryawans);

        $(document).ready(function() {

            let currentCareerId = null;
            let currentMode = 'create'; // 'create' | 'edit' | 'view'

            // ===== DATATABLES =====
            if ($.fn.DataTable.isDataTable('#careersTable')) {
                $('#careersTable').DataTable().destroy();
            }

            $('#careersTable').DataTable({
                responsive: true,
                destroy: true,
                language: {
                    emptyTable: 'Tidak ada data jenjang karir',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Menampilkan 0 data',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    search: 'Cari:',
                    zeroRecords: 'Tidak ditemukan data yang sesuai',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Selanjutnya',
                        previous: 'Sebelumnya'
                    }
                },
                columnDefs: [{
                    orderable: false,
                    targets: [10]
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
                }
            });

            // ===== SELECT2 IN MODAL =====
            function initSelect2InModal() {
                $('#careerModal .select2-modal').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) $(this).select2('destroy');
                    $(this).select2({
                        theme: 'bootstrap-5',
                        dropdownParent: $('#careerModal'),
                        width: '100%',
                        allowClear: true,
                    });
                });
            }

            function destroySelect2InModal() {
                $('#careerModal .select2-modal').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) $(this).select2('destroy');
                });
            }

            $('#careerModal').on('shown.bs.modal', function() {
                initSelect2InModal();
            });
            $('#careerModal').on('hidden.bs.modal', function() {
                destroySelect2InModal();
                resetCareerModal();
            });

            // ===== MODE MANAGEMENT =====
            function setModalMode(mode) {
                currentMode = mode;
                const formEls = $('#careerForm input, #careerForm select, #careerForm textarea');

                switch (mode) {
                    case 'create':
                        $('#careerModalTitle').text('Tambah Jenjang Karir Baru');
                        $('#modeIndicator').hide();
                        $('#saveCareerBtn').show().html('<i class="fas fa-save me-1"></i>Simpan');
                        $('#editCareerBtn').hide();
                        $('#closeButtonText').text('Batal');
                        formEls.prop('disabled', false).prop('readonly', false);
                        // Jabatan & area kerja tetap disabled sampai parent dipilih
                        $('#modal_id_departemen').prop('disabled', true);
                        $('#modal_id_wilayah_kerja').prop('disabled', true);
                        $('.required-mark').show();
                        break;

                    case 'edit':
                        $('#careerModalTitle').text('Edit Jenjang Karir');
                        $('#modeIndicator').hide();
                        $('#saveCareerBtn').show().html('<i class="fas fa-save me-1"></i>Update');
                        $('#editCareerBtn').hide();
                        $('#closeButtonText').text('Batal');
                        formEls.prop('disabled', false).prop('readonly', false);
                        $('.required-mark').show();
                        break;

                    case 'view':
                        $('#careerModalTitle').text('Detail Jenjang Karir');
                        $('#modeIndicator').show().find('#modeText').text('Mode Detail - Data hanya dapat dilihat');
                        $('#saveCareerBtn').hide();
                        $('#editCareerBtn').show();
                        $('#closeButtonText').text('Tutup');
                        formEls.prop('disabled', true).prop('readonly', true);
                        $('.required-mark').hide();
                        break;
                }
            }

            // ===== RESET MODAL =====
            function resetCareerModal() {
                $('#careerForm')[0].reset();
                $('#career_id').val('');
                $('#modal_id_departemen').html('<option value="">Pilih Jabatan</option>').prop('disabled', true);
                $('#modal_id_wilayah_kerja').html('<option value="">Pilih Area Kerja</option>').prop('disabled',
                    true);
                $('#modal_id_dokumen_karyawan').html('<option value="">Pilih Jenis</option>').prop('disabled',
                    true);
                $('#modal_info_singkatan_dep, #modal_info_singkatan_jbt, #modal_info_skt_wilker_display, #modal_info_skt_area')
                    .val('');
                $('#existing_file_info').hide();
                currentCareerId = null;
            }

            // ===== EVENT BUTTONS =====
            $('#addCareerBtn').on('click', function() {
                resetCareerModal();
                setModalMode('create');
                $('#careerModal').modal('show');
            });

            $(document).on('click', '.show-career-btn', function() {
                currentCareerId = $(this).data('career-id');
                setModalMode('view');
                loadCareerDataToModal(currentCareerId);
                $('#careerModal').modal('show');
            });

            $(document).on('click', '.edit-career-btn', function() {
                currentCareerId = $(this).data('career-id');
                setModalMode('edit');
                loadCareerDataToModal(currentCareerId);
                $('#careerModal').modal('show');
            });

            $('#editCareerBtn').on('click', function() {
                if (currentCareerId) setModalMode('edit');
            });

            $(document).on('click', '.delete-career-btn', function() {
                currentCareerId = $(this).data('career-id');
                $('#deleteCareerModal').modal('show');
            });

            // ===== CASCADING: KATEGORI → JENIS DOKUMEN =====
            $('#modal_ktg_dokumen').on('change', function() {
                if (currentMode === 'view') return;
                const selectedCategory = $(this).val();
                $('#modal_id_dokumen_karyawan').val('').prop('disabled', true);

                if (selectedCategory) {
                    const filtered = allDokumenKaryawan
                        .filter(doc => doc.ktg_dok_kry === selectedCategory)
                        .sort((a, b) => (a.kode_dok_kry || '').localeCompare(b.kode_dok_kry || ''));

                    if ($('#modal_id_dokumen_karyawan').hasClass('select2-hidden-accessible')) {
                        $('#modal_id_dokumen_karyawan').select2('destroy');
                    }
                    $('#modal_id_dokumen_karyawan').html('<option value="">Pilih Jenis</option>');
                    filtered.forEach(doc => {
                        $('#modal_id_dokumen_karyawan').append(
                            `<option value="${doc.id}">${doc.jns_dok_kry}</option>`);
                    });
                    $('#modal_id_dokumen_karyawan').prop('disabled', false).select2({
                        theme: 'bootstrap-5',
                        dropdownParent: $('#careerModal'),
                        width: '100%'
                    });
                }
            });

            // ===== CASCADING: DEPARTEMEN → JABATAN =====
            $('#modal_departemen_nama').on('change', function() {
                if (currentMode === 'view') return;
                const namaDep = $(this).val();
                const jabatanSelect = $('#modal_id_departemen');

                const singkatan = $(this).find('option:selected').data('singkatan') || '';
                $('#modal_info_singkatan_dep').val(singkatan);
                $('#modal_info_singkatan_jbt').val('');

                if (namaDep) {
                    jabatanSelect.prop('disabled', true).html('<option value="">Loading...</option>');
                    $.ajax({
                        url: `/data-jenjang-karir/get-jabatan-by-departemen/${encodeURIComponent(namaDep)}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success && response.data) {
                                jabatanSelect.html('<option value="">Pilih Jabatan</option>');
                                response.data.forEach(j => {
                                    jabatanSelect.append(
                                        `<option value="${j.id}" data-singkatan-jbt="${j.singkatan_jbt || ''}">${j.nama_jbt}</option>`
                                    );
                                });
                                jabatanSelect.prop('disabled', false);
                                if (jabatanSelect.hasClass('select2-hidden-accessible'))
                                    jabatanSelect.select2('destroy');
                                jabatanSelect.select2({
                                    theme: 'bootstrap-5',
                                    dropdownParent: $('#careerModal'),
                                    width: '100%'
                                });
                            }
                        },
                        error: function() {
                            jabatanSelect.html('<option value="">Error loading</option>').prop(
                                'disabled', false);
                        }
                    });
                } else {
                    jabatanSelect.html('<option value="">Pilih Jabatan</option>').prop('disabled', true);
                }
            });

            $('#modal_id_departemen').on('change', function() {
                $('#modal_info_singkatan_jbt').val($(this).find('option:selected').data('singkatan-jbt') ||
                    '');
            });

            // ===== CASCADING: WILAYAH → AREA KERJA =====
            $('#modal_wilayah_kerja_nama').on('change', function() {
                if (currentMode === 'view') return;
                const wilayahKrj = $(this).val();
                const areaSelect = $('#modal_id_wilayah_kerja');

                // Reset kedua singkatan saat wilayah berubah
                $('#modal_info_skt_wilker_display, #modal_info_skt_area').val('');

                if (wilayahKrj) {
                    areaSelect.prop('disabled', true).html('<option value="">Loading...</option>');

                    $.ajax({
                        url: `/data-jenjang-karir/get-unit-kerja-by-wilayah/${encodeURIComponent(wilayahKrj)}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success && response.data && response.data.length > 0) {
                                areaSelect.html('<option value="">Pilih Area Kerja</option>');
                                response.data.forEach(function(area) {
                                    // Simpan KEDUA singkatan di data attribute
                                    areaSelect.append(
                                        `<option value="${area.id}"
                                            data-singkatan-wk="${area.singkatan_wk || ''}"
                                            data-skt-wilker="${area.skt_wilker || ''}"
                                        >${area.area_krj}</option>`
                                    );
                                });
                                areaSelect.prop('disabled', false);
                                if (areaSelect.hasClass('select2-hidden-accessible')) areaSelect
                                    .select2('destroy');
                                areaSelect.select2({
                                    theme: 'bootstrap-5',
                                    dropdownParent: $('#careerModal'),
                                    width: '100%'
                                });

                                // ── Singkatan WILKER: ambil dari baris pertama (skt_wilker)
                                // sama persis dengan trigger di create blade
                                $('#modal_info_skt_wilker_display').val(response.data[0]
                                    .skt_wilker || '');
                            }
                        },
                        error: function() {
                            areaSelect.html('<option value="">Error loading</option>').prop(
                                'disabled', false);
                        }
                    });
                } else {
                    areaSelect.html('<option value="">Pilih Area Kerja</option>').prop('disabled', true);
                }
            });

            $('#modal_id_wilayah_kerja').on('change', function() {
                $('#modal_info_skt_area').val($(this).find('option:selected').data('singkatan-wk') || '');
            });

            // ===== LOAD CAREER DATA TO MODAL =====
            function loadCareerDataToModal(careerId) {
                $.ajax({
                    url: `/data-jenjang-karir/careers/${careerId}`,
                    type: 'GET',
                    success: function(response) {
                        if (!response.success) return;
                        const career = response.data;

                        // ── Field dasar ──────────────────────────────────────────────
                        $('#career_id').val(career.id);
                        $('#modal_no_jk').val(career.no_jk || '');
                        $('#modal_tgl_ttd').val(career.tgl_ttd || ''); // sudah Y-m-d dari controller
                        $('#modal_tugas').val(career.tugas || '');

                        // ── File ─────────────────────────────────────────────────────
                        if (career.file_dokumen) {
                            $('#existing_file_info').show();
                            $('#existing_file_link').attr('href', '/storage/' + career.file_dokumen);
                        } else {
                            $('#existing_file_info').hide();
                        }

                        // ── Kategori → Jenis Dokumen ─────────────────────────────────
                        const dokumen = career.dokumen_karyawan;
                        if (dokumen && dokumen.ktg_dok_kry) {
                            const ktg = dokumen.ktg_dok_kry;
                            $('#modal_ktg_dokumen').val(ktg).trigger('change.select2');

                            const filtered = allDokumenKaryawan
                                .filter(doc => doc.ktg_dok_kry === ktg)
                                .sort((a, b) => (a.kode_dok_kry || '').localeCompare(b.kode_dok_kry ||
                                    ''));

                            if ($('#modal_id_dokumen_karyawan').hasClass('select2-hidden-accessible')) {
                                $('#modal_id_dokumen_karyawan').select2('destroy');
                            }
                            $('#modal_id_dokumen_karyawan').html(
                                '<option value="">Pilih Jenis</option>');
                            filtered.forEach(doc => {
                                $('#modal_id_dokumen_karyawan').append(
                                    `<option value="${doc.id}">${doc.jns_dok_kry}</option>`);
                            });
                            $('#modal_id_dokumen_karyawan')
                                .prop('disabled', currentMode === 'view')
                                .val(career.id_dokumen_karyawan || '');

                            if (currentMode !== 'view') {
                                $('#modal_id_dokumen_karyawan').select2({
                                    theme: 'bootstrap-5',
                                    dropdownParent: $('#careerModal'),
                                    width: '100%'
                                });
                            }
                        }

                        // ── Departemen → Jabatan ─────────────────────────────────────
                        const dep = career.departemen;
                        if (dep && dep.nama_dep) {
                            $('#modal_departemen_nama').val(dep.nama_dep).trigger('change.select2');
                            $('#modal_info_singkatan_dep').val(dep.singkatan_dep || '');

                            $.ajax({
                                url: `/data-jenjang-karir/get-jabatan-by-departemen/${encodeURIComponent(dep.nama_dep)}`,
                                type: 'GET',
                                dataType: 'json',
                                success: function(res) {
                                    if (!res.success || !res.data) return;
                                    const jabSelect = $('#modal_id_departemen');
                                    jabSelect.html(
                                        '<option value="">Pilih Jabatan</option>');
                                    res.data.forEach(j => {
                                        jabSelect.append(
                                            `<option value="${j.id}" data-singkatan-jbt="${j.singkatan_jbt || ''}">${j.nama_jbt}</option>`
                                            );
                                    });
                                    jabSelect.prop('disabled', currentMode === 'view').val(
                                        career.id_departemen || '');

                                    const sktJbt = jabSelect.find('option:selected').data(
                                        'singkatan-jbt') || dep.singkatan_jbt || '';
                                    $('#modal_info_singkatan_jbt').val(sktJbt);

                                    if (currentMode !== 'view') {
                                        if (jabSelect.hasClass('select2-hidden-accessible'))
                                            jabSelect.select2('destroy');
                                        jabSelect.select2({
                                            theme: 'bootstrap-5',
                                            dropdownParent: $('#careerModal'),
                                            width: '100%'
                                        });
                                    }
                                }
                            });
                        }

                        // ── Wilayah Kerja → Area Kerja ───────────────────────────────
                        // Logika SAMA dengan trigger #modal_wilayah_kerja_nama di create blade:
                        // skt_wilker  = singkatan wilayah (dari baris pertama response)
                        // singkatan_wk = singkatan area kerja (dari option terpilih)
                        const wilker = career.wilayah_kerja;
                        if (wilker && wilker.wilayah_krj) {
                            const namaWilayah = wilker.wilayah_krj;
                            $('#modal_wilayah_kerja_nama').val(namaWilayah).trigger('change.select2');

                            // Reset dulu sebelum load
                            $('#modal_info_skt_wilker_display, #modal_info_skt_area').val('');

                            $.ajax({
                                url: `/data-jenjang-karir/get-unit-kerja-by-wilayah/${encodeURIComponent(namaWilayah)}`,
                                type: 'GET',
                                dataType: 'json',
                                success: function(res) {
                                    if (!res.success || !res.data || res.data.length === 0)
                                        return;

                                    const areaSelect = $('#modal_id_wilayah_kerja');
                                    areaSelect.html(
                                        '<option value="">Pilih Area Kerja</option>');
                                    res.data.forEach(a => {
                                        areaSelect.append(
                                            `<option value="${a.id}"
                                                data-singkatan-wk="${a.singkatan_wk || ''}"
                                                data-skt-wilker="${a.skt_wilker || ''}"
                                            >${a.area_krj}</option>`
                                        );
                                    });
                                    areaSelect.prop('disabled', currentMode === 'view').val(
                                        career.id_wilayah_kerja || '');

                                    // ── Singkatan WILKER: dari baris pertama (skt_wilker)
                                    // SAMA dengan: $('#info_skt_wilker_display').val(response.data[0].skt_wilker || '')
                                    // di create blade
                                    $('#modal_info_skt_wilker_display').val(res.data[0]
                                        .skt_wilker || '');

                                    // ── Singkatan AREA: dari option yang terpilih (singkatan_wk)
                                    const sktArea = areaSelect.find('option:selected').data(
                                        'singkatan-wk') || wilker.singkatan_wk || '';
                                    $('#modal_info_skt_area').val(sktArea);

                                    if (currentMode !== 'view') {
                                        if (areaSelect.hasClass(
                                            'select2-hidden-accessible')) areaSelect
                                            .select2('destroy');
                                        areaSelect.select2({
                                            theme: 'bootstrap-5',
                                            dropdownParent: $('#careerModal'),
                                            width: '100%'
                                        });
                                    }
                                }
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal memuat data jenjang karir.',
                            icon: 'error'
                        });
                    }
                });
            }

            // ===== SAVE (CREATE / UPDATE) =====
            $('#saveCareerBtn').on('click', function() {
                if (currentMode === 'view') return;

                // Validation
                const tglTtd = $('#modal_tgl_ttd').val();
                const idDep = $('#modal_id_departemen').val();
                if (!tglTtd || !idDep) {
                    Swal.fire({
                        title: 'Form Tidak Lengkap!',
                        text: 'Tanggal Terbit dan Jabatan wajib diisi.',
                        icon: 'warning'
                    });
                    return;
                }

                const formData = new FormData($('#careerForm')[0]);
                if (currentMode === 'edit' && currentCareerId) {
                    formData.append('_method', 'PUT');
                }

                const url = currentMode === 'edit' ?
                    `/data-jenjang-karir/careers/${currentCareerId}` :
                    '{{ route('data-jenjang-karir.careers.store') }}';

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
                        $('#saveCareerBtn').prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...');
                    },
                    success: function(response) {
                        $('#saveCareerBtn').prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i>Simpan');
                        if (response.success) {
                            $('#careerModal').modal('hide');
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        }
                    },
                    error: function(xhr) {
                        $('#saveCareerBtn').prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i>Simpan');
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat menyimpan.',
                            icon: 'error'
                        });
                    }
                });
            });

            // ===== DELETE ONE =====
            $('#confirmDeleteCareer').on('click', function() {
                if (!currentCareerId) return;
                $.ajax({
                    url: `/data-jenjang-karir/careers/${currentCareerId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#deleteCareerModal').modal('hide');
                            Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                })
                                .then(() => location.reload());
                        }
                    },
                    error: function(xhr) {
                        $('#deleteCareerModal').modal('hide');
                        Swal.fire({
                            title: xhr.status === 400 ? 'Tidak Dapat Menghapus!' :
                                'Error!',
                            text: xhr.responseJSON?.message || 'Gagal menghapus data.',
                            icon: xhr.status === 400 ? 'warning' : 'error'
                        });
                    }
                });
            });

            // ===== DELETE ALL =====
            $('#deleteAllCareersBtn').on('click', function() {
                const employeeId = $(this).data('employee-id');
                $('#deleteAllCareersForm').attr('action', `/data-jenjang-karir/${employeeId}`);
                $('#deleteAllCareersModal').modal('show');
            });

            $('#deleteAllCareersForm').on('submit', function(e) {
                e.preventDefault();
                const actionUrl = $(this).attr('action');
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda benar-benar yakin? Semua data jenjang karir akan dihapus permanen!',
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
                            data: $(this).serialize(),
                            success: function() {
                                $('#deleteAllCareersModal').modal('hide');
                                Swal.fire({
                                        title: 'Berhasil Dihapus!',
                                        text: 'Semua data jenjang karir telah dihapus.',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    })
                                    .then(() => window.location.href =
                                        '{{ route('data-jenjang-karir.index') }}');
                            },
                            error: function(xhr) {
                                $('#deleteAllCareersModal').modal('hide');
                                Swal.fire({
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message ||
                                        'Terjadi kesalahan.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            // Auto-uppercase for modal textareas
            $(document).on('input', '#careerForm .auto-uppercase', function() {
                this.value = this.value.toUpperCase();
            });

        }); // end ready
    </script>
@endpush
