@extends('layouts.app')

@section('title', 'Edit Data Kontrak')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-contract me-2"></i>Edit Data Kontrak</span>
                        <a href="{{ route('data-kontrak.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('data-kontrak.update', $dataKontrak->id) }}" method="POST" id="kontrakForm"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')
                            <input type="hidden" class="form-control" id="id_kode" name="id_kode"
                                value="{{ old('id_kode', $dataKontrak->id_kode ?? $dataKontrak->id) }}" readonly>

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
                                    <button class="nav-link" id="kontrak-tab" data-bs-toggle="tab" data-bs-target="#kontrak"
                                        type="button" role="tab" aria-controls="kontrak" aria-selected="false">
                                        <i class="fas fa-file-contract me-1"></i> Data Kontrak
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pendidikan-tab" data-bs-toggle="tab"
                                        data-bs-target="#pendidikan" type="button" role="tab"
                                        aria-controls="pendidikan" aria-selected="false">
                                        <i class="fas fa-graduation-cap me-1"></i> Pendidikan
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
                                            <h5 class="mb-0 text-white"><i class="fas fa-user me-2"></i>Pilih Data Karyawan
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="id_data_kry" class="form-label fw-bold">Karyawan <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-users"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="id_data_kry"
                                                                    name="id_data_kry" data-required="true">
                                                                    <option value="">Pilih Karyawan</option>
                                                                    @foreach ($karyawans as $karyawan)
                                                                        <option value="{{ $karyawan->id }}"
                                                                            {{ old('id_data_kry', $dataKontrak->id_data_kry) == $karyawan->id ? 'selected' : '' }}>
                                                                            {{ $karyawan->nama }} -
                                                                            {{ $karyawan->nrk ?? 'NRK Belum Ada' }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Employee Info Display (Auto-filled when selecting employee) -->
                                            <div id="employeeInfo" class="row" style="display: {{ $dataKontrak->id_data_kry ? 'block' : 'none' }};">
                                                <div class="col-md-12">
                                                    <div class="card bg-light border-0">
                                                        <div class="card-header bg-secondary text-white">
                                                            <h6 class="mb-0"><i
                                                                    class="fas fa-info-circle me-2"></i>Informasi Karyawan
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label fw-bold">NIK</label>
                                                                        <input type="text" class="form-control"
                                                                            id="emp_nik" value="{{ $dataKontrak->karyawan->nik ?? '-' }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label fw-bold">NRK</label>
                                                                        <input type="text" class="form-control"
                                                                            id="emp_nrk" value="{{ $dataKontrak->karyawan->nrk ?? '-' }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label fw-bold">Tempat
                                                                            Lahir</label>
                                                                        <input type="text" class="form-control"
                                                                            id="emp_tpt_lahir" value="{{ $dataKontrak->karyawan->tpt_lahir ?? '-' }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label fw-bold">Tanggal
                                                                            Lahir</label>
                                                                        <input type="text" class="form-control"
                                                                            id="emp_tgl_lahir" value="{{ $dataKontrak->karyawan->tgl_lahir ? $dataKontrak->karyawan->tgl_lahir->format('d-m-Y') : '-' }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label fw-bold">Jenis
                                                                            Kelamin</label>
                                                                        <input type="text" class="form-control"
                                                                            id="emp_sex" value="{{ $dataKontrak->karyawan->sex ?? '-' }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label fw-bold">Telepon</label>
                                                                        <input type="text" class="form-control"
                                                                            id="emp_tlp1" value="{{ $dataKontrak->karyawan->tlp1 ?? '-' }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label fw-bold">Email</label>
                                                                        <input type="text" class="form-control"
                                                                            id="emp_email1" value="{{ $dataKontrak->karyawan->email1 ?? '-' }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label fw-bold">Status
                                                                            Karyawan</label>
                                                                        <input type="text" class="form-control"
                                                                            id="emp_sts_kry" value="{{ $dataKontrak->karyawan->sts_kry ?? '-' }}" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab 2: Data Kontrak -->
                                <div class="tab-pane fade" id="kontrak" role="tabpanel" aria-labelledby="kontrak-tab">

                                    <!-- Card 1: Informasi Umum Kontrak -->
                                    <div class="card border-secondary mb-4">
                                        <div class="card-header bg-secondary bg-opacity-25">
                                            <h5 class="mb-0 text-white">
                                                <i class="fas fa-file-contract me-2"></i>Informasi Umum Kontrak
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- No Surat Kontrak -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="no_srt_ktr" class="form-label fw-bold">No. Surat
                                                            Kontrak</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-file-alt"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="no_srt_ktr" name="no_srt_ktr"
                                                                value="{{ old('no_srt_ktr', $dataKontrak->no_srt_ktr) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Tanggal Surat Kontrak -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_srt_ktr" class="form-label fw-bold">Tanggal Surat
                                                            Kontrak</label>
                                                        <input type="date" class="form-control" id="tgl_srt_ktr"
                                                            name="tgl_srt_ktr" value="{{ old('tgl_srt_ktr', $dataKontrak->tgl_srt_ktr) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="id_ktr" class="form-label fw-bold">Status Kontrak
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-file-signature"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="id_ktr"
                                                                    name="id_ktr" data-required="true">
                                                                    <option value="">Pilih Status Kontrak</option>
                                                                    @foreach ($kontrakTypes as $kontrak)
                                                                        <option value="{{ $kontrak->id }}"
                                                                            data-nama-ktr="{{ $kontrak->nama_ktr }}"
                                                                            data-singkatan-ktr="{{ $kontrak->singkatan_ktr }}"
                                                                            {{ old('id_ktr', $dataKontrak->id_ktr) == $kontrak->id ? 'selected' : '' }}>
                                                                            {{ $kontrak->nama_ktr }} -
                                                                            {{ $kontrak->singkatan_ktr }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Singkatan</label>
                                                        <input type="text" class="form-control"
                                                            id="info_singkatan_ktr" value="{{ $dataKontrak->kontrakKerja->singkatan_ktr ?? '' }}" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="id_prsh" class="form-label fw-bold">Perusahaan <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-building"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="id_prsh"
                                                                    name="id_prsh" data-required="true">
                                                                    <option value="">Pilih Perusahaan</option>
                                                                    @foreach ($perusahaans as $perusahaan)
                                                                        <option value="{{ $perusahaan->id }}"
                                                                            data-nama-prs1="{{ $perusahaan->nama_prs1 }}"
                                                                            data-nama-prs2="{{ $perusahaan->nama_prs2 }}"
                                                                            {{ old('id_prsh', $dataKontrak->id_prsh) == $perusahaan->id ? 'selected' : '' }}>
                                                                            {{ $perusahaan->nama_prs1 }} -
                                                                            {{ $perusahaan->nama_prs2 }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Singkatan</label>
                                                        <input type="text" class="form-control" id="info_nama_prs2"
                                                            value="{{ $dataKontrak->perusahaan->nama_prs2 ?? '' }}" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="ktg_ktk" class="form-label fw-bold">Keterangan
                                                            Kontrak <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-check-circle"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="ktg_ktk"
                                                                    name="ktg_ktk" data-required="true">
                                                                    <option value="">Pilih Status</option>
                                                                    <option value="TETAP"
                                                                        {{ old('ktg_ktk', $dataKontrak->ktg_ktk) == 'TETAP' ? 'selected' : '' }}>
                                                                        TETAP</option>
                                                                    <option value="TIDAK TETAP"
                                                                        {{ old('ktg_ktk', $dataKontrak->ktg_ktk) == 'TIDAK TETAP' ? 'selected' : '' }}>
                                                                        TIDAK TETAP</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--Periode Kontrak -->
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white">
                                                <i class="fas fa-calendar-alt me-2"></i>Periode Kontrak
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Tanggal Mulai -->
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_awl_ktr" class="form-label fw-bold">Tanggal Mulai
                                                            Kontrak <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" id="tgl_awl_ktr"
                                                            name="tgl_awl_ktr" value="{{ old('tgl_awl_ktr', $dataKontrak->tgl_awl_ktr) }}"
                                                            data-required="true">
                                                    </div>
                                                </div>

                                                <!-- Tanggal Akhir -->
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_akhir_ktr" class="form-label fw-bold">Tanggal
                                                            Akhir Kontrak <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" id="tgl_akhir_ktr"
                                                            name="tgl_akhir_ktr" value="{{ old('tgl_akhir_ktr', $dataKontrak->tgl_akhir_ktr) }}"
                                                            data-required="true">
                                                    </div>
                                                </div>

                                                <!-- Durasi -->
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="durasi_ktr" class="form-label fw-bold">Durasi Kontrak
                                                            (Bulan)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-clock"></i></span>
                                                            <input type="number" class="form-control" id="durasi_ktr"
                                                                name="durasi_ktr" value="{{ old('durasi_ktr', $dataKontrak->durasi_ktr) }}"
                                                                min="1" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Tanggal Pengingat -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_pgt_ktr" class="form-label fw-bold">Tanggal
                                                            Pengingat</label>
                                                        <input type="date" class="form-control" id="tgl_pgt_ktr"
                                                            name="tgl_pgt_ktr" value="{{ old('tgl_pgt_ktr', $dataKontrak->tgl_pgt_ktr) }}">
                                                    </div>
                                                </div>

                                                <!-- Durasi Pengingat -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="durasi_pgt" class="form-label fw-bold">Durasi
                                                            Pengingat (Hari)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-clock"></i></span>
                                                            <input type="number" class="form-control" id="durasi_pgt"
                                                                name="durasi_pgt" value="{{ old('durasi_pgt', $dataKontrak->durasi_pgt) }}"
                                                                min="1" readonly>
                                                        </div>
                                                        <div class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>Otomatis dihitung dari
                                                            tanggal pengingat ke tanggal akhir kontrak
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 6: Keterangan Tambahan -->
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">
                                                <i class="fas fa-comment-alt me-2"></i>Keterangan Tambahan
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Status Surat Kontrak -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="sts_srt_ktr" class="form-label fw-bold">Status Surat
                                                            Kontrak <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-check-circle"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="sts_srt_ktr"
                                                                    name="sts_srt_ktr" data-required="true">
                                                                    <option value="">Pilih Status</option>
                                                                    <option value="AKTIF"
                                                                        {{ old('sts_srt_ktr', $dataKontrak->sts_srt_ktr) == 'AKTIF' ? 'selected' : '' }}>
                                                                        AKTIF</option>
                                                                    <option value="NON-AKTIF"
                                                                        {{ old('sts_srt_ktr', $dataKontrak->sts_srt_ktr) == 'NON-AKTIF' ? 'selected' : '' }}>
                                                                        NON-AKTIF</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- File Dokumen Kontrak -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="file_doc_ktr" class="form-label fw-bold">File Dokumen
                                                            Kontrak</label>
                                                        <input type="file" class="form-control" id="file_doc_ktr"
                                                            name="file_doc_ktr" accept=".pdf,.doc,.docx,.jpg,.png">
                                                        <div class="form-text text-muted">
                                                            Format: PDF, DOC, DOCX, JPG, PNG
                                                            @if($dataKontrak->file_doc_ktr)
                                                                <br><strong>File saat ini:</strong>
                                                                <a href="{{ Storage::url($dataKontrak->file_doc_ktr) }}" target="_blank" class="text-primary">
                                                                    <i class="fas fa-download me-1"></i>Lihat File
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Tanggal Status NA -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_sr_na" class="form-label fw-bold">Tanggal Status
                                                            Non Aktif</label>
                                                        <input type="date" class="form-control" id="tgl_sr_na"
                                                            name="tgl_sr_na" value="{{ old('tgl_sr_na', $dataKontrak->tgl_sr_na) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="ket_sr_na" class="form-label fw-bold">Keterangan
                                                            Non Aktif</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-comment-alt"></i></span>
                                                            <textarea class="form-control auto-uppercase" id="ket_sr_na" name="ket_sr_na" rows="3" placeholder="">{{ old('ket_sr_na', $dataKontrak->ket_sr_na) }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- Tab 3: Pendidikan -->
                                <div class="tab-pane fade" id="pendidikan" role="tabpanel"
                                    aria-labelledby="pendidikan-tab">
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i
                                                    class="fas fa-graduation-cap me-2"></i>Pendidikan Terakhir</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Jenjang -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="jenjang_skl" class="form-label fw-bold">Jenjang
                                                            Pendidikan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-level-up-alt"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="jenjang_skl"
                                                                    name="jenjang_skl">
                                                                    <option value="">Pilih Jenjang</option>
                                                                    <option value="-"
                                                                        {{ old('jenjang_skl', $dataKontrak->jenjang_skl) == '-' ? 'selected' : '' }}>-
                                                                    </option>
                                                                    <option value="SD"
                                                                        {{ old('jenjang_skl', $dataKontrak->jenjang_skl) == 'SD' ? 'selected' : '' }}>
                                                                        SD</option>
                                                                    <option value="SMP"
                                                                        {{ old('jenjang_skl', $dataKontrak->jenjang_skl) == 'SMP' ? 'selected' : '' }}>
                                                                        SMP</option>
                                                                    <option value="SMA"
                                                                        {{ old('jenjang_skl', $dataKontrak->jenjang_skl) == 'SMA' ? 'selected' : '' }}>
                                                                        SMA</option>
                                                                    <option value="SMK"
                                                                        {{ old('jenjang_skl', $dataKontrak->jenjang_skl) == 'SMK' ? 'selected' : '' }}>
                                                                        SMK</option>
                                                                    <option value="D1"
                                                                        {{ old('jenjang_skl', $dataKontrak->jenjang_skl) == 'D1' ? 'selected' : '' }}>
                                                                        D1</option>
                                                                    <option value="D2"
                                                                        {{ old('jenjang_skl', $dataKontrak->jenjang_skl) == 'D2' ? 'selected' : '' }}>
                                                                        D2</option>
                                                                    <option value="D3"
                                                                        {{ old('jenjang_skl', $dataKontrak->jenjang_skl) == 'D3' ? 'selected' : '' }}>
                                                                        D3</option>
                                                                    <option value="D4"
                                                                        {{ old('jenjang_skl', $dataKontrak->jenjang_skl) == 'D4' ? 'selected' : '' }}>
                                                                        D4</option>
                                                                    <option value="S1"
                                                                        {{ old('jenjang_skl', $dataKontrak->jenjang_skl) == 'S1' ? 'selected' : '' }}>
                                                                        S1</option>
                                                                    <option value="S2"
                                                                        {{ old('jenjang_skl', $dataKontrak->jenjang_skl) == 'S2' ? 'selected' : '' }}>
                                                                        S2</option>
                                                                    <option value="S3"
                                                                        {{ old('jenjang_skl', $dataKontrak->jenjang_skl) == 'S3' ? 'selected' : '' }}>
                                                                        S3</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Tanggal Lulus -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_lulus_skl" class="form-label fw-bold">Tanggal
                                                            Lulus</label>
                                                        <input type="date" class="form-control" id="tgl_lulus_skl"
                                                            name="tgl_lulus_skl" value="{{ old('tgl_lulus_skl', $dataKontrak->tgl_lulus_skl ? $dataKontrak->tgl_lulus_skl->format('Y-m-d') : '') }}">
                                                    </div>
                                                </div>

                                                <!-- Institusi -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="institusi_skl" class="form-label fw-bold">Nama
                                                            Institusi</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-university"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="institusi_skl" name="institusi_skl"
                                                                value="{{ old('institusi_skl', $dataKontrak->institusi_skl) }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Fakultas -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="fakultas_skl"
                                                            class="form-label fw-bold">Fakultas</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-building-columns"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="fakultas_skl" name="fakultas_skl"
                                                                value="{{ old('fakultas_skl', $dataKontrak->fakultas_skl) }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- SKT Institusi -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="skt_inst_skl" class="form-label fw-bold">SKT
                                                            Institusi</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-certificate"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="skt_inst_skl" name="skt_inst_skl"
                                                                value="{{ old('skt_inst_skl', $dataKontrak->skt_inst_skl) }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Jurusan -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="jurusan_skl"
                                                            class="form-label fw-bold">Jurusan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-book-open"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="jurusan_skl" name="jurusan_skl"
                                                                value="{{ old('jurusan_skl', $dataKontrak->jurusan_skl) }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Kota -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kota_skl" class="form-label fw-bold">Kota</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-marker-alt"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="kota_skl" name="kota_skl"
                                                                value="{{ old('kota_skl', $dataKontrak->kota_skl) }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Gelar -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="gelar_skl" class="form-label fw-bold">Gelar</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-medal"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="gelar_skl" name="gelar_skl"
                                                                value="{{ old('gelar_skl', $dataKontrak->gelar_skl) }}">
                                                        </div>
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
                                            <div class="row">
                                                <!-- Departemen -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="departemen_nama"
                                                            class="form-label fw-bold">Departemen</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-sitemap"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="departemen_nama"
                                                                    name="departemen_nama">
                                                                    <option value="">Pilih Departemen</option>
                                                                    @foreach ($departemens->groupBy('nama_dep') as $namaDep => $group)
                                                                        <option value="{{ $namaDep }}"
                                                                            data-singkatan="{{ $group->first()->singkatan_dep }}"
                                                                            {{ old('departemen_nama', $dataKontrak->departemen->nama_dep ?? '') == $namaDep ? 'selected' : '' }}>
                                                                            {{ $namaDep }} -
                                                                            {{ $group->first()->singkatan_dep }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Singkatan
                                                            Dep.</label>
                                                        <input type="text" class="form-control"
                                                            id="info_singkatan_dep" value="{{ $dataKontrak->departemen->singkatan_dep ?? '' }}" readonly>
                                                    </div>
                                                </div>

                                                <!-- Jabatan -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="id_departemen"
                                                            class="form-label fw-bold">Jabatan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-user-tie"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="id_departemen"
                                                                    name="id_departemen">
                                                                    <option value="">Pilih Jabatan</option>
                                                                    @if($dataKontrak->departemen)
                                                                        <option value="{{ $dataKontrak->departemen->id }}" selected>
                                                                            {{ $dataKontrak->departemen->nama_jbt ?? 'N/A' }}
                                                                        </option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Singkatan
                                                            Jbt.</label>
                                                        <input type="text" class="form-control"
                                                            id="info_singkatan_jbt" value="{{ $dataKontrak->departemen->singkatan_jbt ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Wilayah Kerja -->
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="wilayah_kerja_nama" class="form-label fw-bold">Wilayah
                                                            Kerja</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2"
                                                                    id="wilayah_kerja_nama" name="wilayah_kerja_nama">
                                                                    <option value="">Pilih Wilayah Kerja</option>
                                                                    @foreach ($wilayahKerjas->groupBy('wilayah_krj') as $wilayahKrj => $group)
                                                                        <option value="{{ $wilayahKrj }}"
                                                                            {{ old('wilayah_kerja_nama', $dataKontrak->wilayahKerja->wilayah_krj ?? '') == $wilayahKrj ? 'selected' : '' }}>
                                                                            {{ $wilayahKrj }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Area Kerja -->
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="id_wilker" class="form-label fw-bold">Area
                                                            Kerja</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-users-cog"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="id_wilker"
                                                                    name="id_wilker">
                                                                    <option value="">Pilih Area Kerja</option>
                                                                    @if($dataKontrak->wilayahKerja)
                                                                        <option value="{{ $dataKontrak->wilayahKerja->id }}" selected>
                                                                            {{ $dataKontrak->wilayahKerja->area_krj ?? 'N/A' }}
                                                                        </option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- SKT Wilker (Auto-filled) -->
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">SKT Wilayah Kerja</label>
                                                        <input type="text" class="form-control" id="info_skt_wilker"
                                                            value="{{ $dataKontrak->wilayahKerja->singkatan_wk ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="tugas" class="form-label fw-bold">Tugas & Tanggung
                                                            Jawab</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-tasks"></i></span>
                                                            <textarea class="form-control auto-uppercase" id="tugas" name="tugas" rows="4">{{ old('tugas', $dataKontrak->tugas) }}</textarea>
                                                        </div>
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
                                                Data berikut akan otomatis terisi berdasarkan karyawan yang dipilih.
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tanggal Masuk</label>
                                                        <input type="text" class="form-control" id="hubin_tgl_masuk"
                                                            value="{{ $dataKontrak->karyawan->tgl_masuk ? $dataKontrak->karyawan->tgl_masuk->format('d-m-Y') : '-' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Status Karyawan</label>
                                                        <input type="text" class="form-control" id="hubin_sts_kry"
                                                            value="{{ $dataKontrak->karyawan->sts_kry ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tanggal PHK</label>
                                                        <input type="text" class="form-control" id="hubin_tgl_phk"
                                                            value="{{ $dataKontrak->karyawan->tgl_phk ? $dataKontrak->karyawan->tgl_phk->format('d-m-Y') : '-' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Keterangan PHK</label>
                                                        <input type="text" class="form-control" id="hubin_ket_phk"
                                                            value="{{ $dataKontrak->karyawan->ket_phk ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button - Always visible at bottom -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <a href="{{ route('data-kontrak.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-warning btn-lg" id="submitBtn">
                                    <i class="fas fa-save me-2"></i> Update Data Kontrak
                                </button>
                            </div>
                        </form>
                    </div>
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

        .text-danger {
            font-weight: bold;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .form-switch .form-check-input {
            width: 2.5em;
            height: 1.25em;
        }

        /* Select2 custom styling */
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 6px 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #495057;
            line-height: 26px;
            padding-left: 0;
            padding-right: 20px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
            right: 10px;
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #0d6efd;
            color: white;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }

        /* Input group with select2 */
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

        .input-group .select2-container--focus .select2-selection {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Error styling */
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .nav-link.has-error {
            color: #dc3545 !important;
            border-color: #dc3545 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Auto-uppercase functionality
            document.querySelectorAll('input[type="text"].auto-uppercase, textarea.auto-uppercase').forEach(
                function(element) {
                    element.addEventListener('input', function() {
                        this.value = this.value.toUpperCase();
                    });
                });

            // Initialize field visibility based on existing data - wait for DOM to be ready
            setTimeout(function() {
                handleKeteranganKontrakChange();
                handleStatusSuratKontrakChange();
            }, 100);

            // ===== CONDITIONAL FIELD VISIBILITY FUNCTIONS =====
            function handleKeteranganKontrakChange() {
                const ktgKtr = $('#ktg_ktk').val();
                console.log('🔄 Keterangan Kontrak changed to:', ktgKtr);

                const fieldsTanggalAkhir = $('#tgl_akhir_ktr');
                const fieldsDurasi = $('#durasi_ktr');
                const fieldsTanggalPengingat = $('#tgl_pgt_ktr');
                const fieldsDurasiPengingat = $('#durasi_pgt');

                if (ktgKtr === 'TETAP') {
                    fieldsTanggalAkhir.prop('disabled', true).val('').removeClass('is-invalid');
                    fieldsDurasi.prop('disabled', true).val('');
                    fieldsTanggalPengingat.prop('disabled', true).val('');
                    fieldsDurasiPengingat.prop('disabled', true).val('');

                    fieldsTanggalAkhir.closest('.form-group').addClass('opacity-50');
                    fieldsDurasi.closest('.form-group').addClass('opacity-50');
                    fieldsTanggalPengingat.closest('.form-group').addClass('opacity-50');
                    fieldsDurasiPengingat.closest('.form-group').addClass('opacity-50');

                    $('label[for="tgl_akhir_ktr"]').html(
                        'Tanggal Akhir Kontrak <small class="text-muted">(Tidak Berlaku untuk Kontrak Tetap)</small>'
                    );
                    $('label[for="durasi_ktr"]').html(
                        'Durasi Kontrak (Bulan) <small class="text-muted">(Tidak Berlaku)</small>');

                    console.log('✅ TETAP mode: hanya tanggal mulai aktif');

                } else if (ktgKtr === 'TIDAK TETAP') {
                    fieldsTanggalAkhir.prop('disabled', false);
                    fieldsDurasi.prop('disabled', false);
                    fieldsTanggalPengingat.prop('disabled', false);
                    fieldsDurasiPengingat.prop('disabled', false);

                    fieldsTanggalAkhir.closest('.form-group').removeClass('opacity-50');
                    fieldsDurasi.closest('.form-group').removeClass('opacity-50');
                    fieldsTanggalPengingat.closest('.form-group').removeClass('opacity-50');
                    fieldsDurasiPengingat.closest('.form-group').removeClass('opacity-50');

                    $('label[for="tgl_akhir_ktr"]').html(
                        'Tanggal Akhir Kontrak <span class="text-danger">*</span>');
                    $('label[for="durasi_ktr"]').html('Durasi Kontrak (Bulan)');

                    fieldsTanggalAkhir.attr('data-required', 'true');

                    console.log('✅ TIDAK TETAP mode: semua field periode aktif');

                } else {
                    fieldsTanggalAkhir.prop('disabled', false).removeAttr('data-required');
                    fieldsDurasi.prop('disabled', false);
                    fieldsTanggalPengingat.prop('disabled', false);
                    fieldsDurasiPengingat.prop('disabled', false);

                    $('.form-group').removeClass('opacity-50');

                    $('label[for="tgl_akhir_ktr"]').html('Tanggal Akhir Kontrak');
                    $('label[for="durasi_ktr"]').html('Durasi Kontrak (Bulan)');

                    console.log('✅ Default mode: semua field tersedia tapi tidak wajib');
                }
            }

            function handleStatusSuratKontrakChange() {
                const stsSrtKtr = $('#sts_srt_ktr').val();
                console.log('🔄 Status Surat Kontrak changed to:', stsSrtKtr);

                const fieldTglNonAktif = $('#tgl_sr_na').closest('.col-md-6');
                const fieldKetNonAktif = $('#ket_sr_na').closest('.col-md-6');

                if (stsSrtKtr === 'AKTIF') {
                    fieldTglNonAktif.hide();
                    fieldKetNonAktif.hide();

                    // DONT clear existing values on edit page, just hide them
                    $('#tgl_sr_na').removeClass('is-invalid').removeAttr('data-required');
                    $('#ket_sr_na').removeClass('is-invalid');

                    console.log('✅ AKTIF mode: field non aktif tersembunyi');

                } else if (stsSrtKtr === 'NON-AKTIF') {
                    fieldTglNonAktif.show();
                    fieldKetNonAktif.show();

                    console.log('✅ NON-AKTIF mode: field non aktif ditampilkan');

                } else {
                    fieldTglNonAktif.hide();
                    fieldKetNonAktif.hide();
                    $('#tgl_sr_na').val('').removeClass('is-invalid').removeAttr('data-required');
                    $('#ket_sr_na').val('').removeClass('is-invalid');

                    console.log('✅ Default mode: field non aktif tersembunyi');
                }
            }

            // ===== EVENT HANDLERS FOR CONDITIONAL FIELDS =====
            $('#ktg_ktk').on('change', function() {
                handleKeteranganKontrakChange();

                const ktgValue = $(this).val();
                if (ktgValue) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: `Mode ${ktgValue}: Field periode disesuaikan`,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            });

            $('#sts_srt_ktr').on('change', function() {
                handleStatusSuratKontrakChange();

                const stsValue = $(this).val();
                if (stsValue) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: `Status ${stsValue}: Field disesuaikan`,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            });

            // Initialize conditional fields on page load
            setTimeout(function() {
                handleKeteranganKontrakChange();
                handleStatusSuratKontrakChange();
            }, 100);

            // ===== AUTO-POPULATE FUNCTIONS FROM EMPLOYEE DATA =====
            function populateEducationData(employeeData) {
                console.log('📚 Populating education data:', employeeData);

                // Populate pendidikan fields
                $('#jenjang_skl').val(employeeData.jenjang_skl || '').trigger('change.select2');
                $('#institusi_skl').val(employeeData.institusi_skl || '');
                $('#skt_inst_skl').val(employeeData.skt_inst_skl || '');
                $('#kota_skl').val(employeeData.kota_skl || '');
                $('#fakultas_skl').val(employeeData.fakultas_skl || '');
                $('#jurusan_skl').val(employeeData.jurusan_skl || '');
                $('#gelar_skl').val(employeeData.gelar_skl || '');

                // Handle tanggal lulus dengan format yang benar
                if (employeeData.tgl_lulus_skl) {
                    $('#tgl_lulus_skl').val(employeeData.tgl_lulus_skl);
                }

                console.log('✅ Education data populated successfully');
            }

            async function populateCareerData(employeeData) {
                console.log('💼 Populating career data:', employeeData);

                try {
                    // Populate departemen first (from jabatan field in employee data)
                    if (employeeData.jabatan) {
                        console.log('🔧 Setting departemen to:', employeeData.jabatan);
                        $('#departemen_nama').val(employeeData.jabatan).trigger('change.select2');

                        // Set singkatan departemen
                        setTimeout(() => {
                            const selectedOption = $('#departemen_nama').find('option:selected');
                            const singkatanFromAttr = selectedOption.data('singkatan');
                            const singkatanFromEmp = employeeData.skt_dep;
                            const finalSingkatan = singkatanFromAttr || singkatanFromEmp || '';
                            $('#info_singkatan_dep').val(finalSingkatan);
                            console.log('✅ Final Singkatan Dep set:', finalSingkatan);
                        }, 200);

                        // Wait then load jabatan based on departemen
                        setTimeout(async () => {
                            if (employeeData.departemen) {
                                console.log('🔧 Loading jabatan for departemen ID:', employeeData
                                    .departemen);
                                await loadJabatanForEmployee(employeeData.jabatan, employeeData
                                    .departemen);
                            }
                        }, 600);
                    }

                    // Populate wilayah kerja
                    if (employeeData.wilker) {
                        console.log('🔧 Setting wilayah kerja to:', employeeData.wilker);
                        $('#wilayah_kerja_nama').val(employeeData.wilker).trigger('change.select2');

                        // Wait then load unit kerja - FIX: gunakan unit_krj_id yang benar
                        setTimeout(async () => {
                            // Cek beberapa kemungkinan nama field dari employee data
                            const unitKrjId = employeeData.unit_krj_id || employeeData.unit_krj ||
                                employeeData.id_unit_krj;

                            if (unitKrjId) {
                                console.log('🔧 Loading unit kerja for ID:', unitKrjId);
                                await loadUnitKerjaForEmployee(employeeData.wilker, unitKrjId);
                            } else {
                                console.log(
                                    '⚠️ No unit_krj_id found in employee data, trying unit_krj name:',
                                    employeeData.unit_krj);
                                // Jika tidak ada ID, coba cari berdasarkan nama
                                await loadUnitKerjaForEmployee(employeeData.wilker, null);
                            }
                        }, 600);
                    }

                    // Populate tugas
                    $('#tugas').val(employeeData.tugas || '');

                    console.log('✅ Career data populated successfully');

                } catch (error) {
                    console.error('❌ Error populating career data:', error);
                }
            }

            async function loadJabatanForEmployee(namaDep, selectedJabatanId) {
                const jabatanSelect = $('#id_departemen');

                if (!namaDep) return;

                console.log('🔄 Loading jabatan for departemen:', namaDep, 'selected:', selectedJabatanId);
                jabatanSelect.prop('disabled', true).html('<option value="">Loading jabatan...</option>');

                try {
                    const response = await $.ajax({
                        url: `/data-kontrak/get-jabatan-by-departemen/${encodeURIComponent(namaDep)}`,
                        type: 'GET',
                        dataType: 'json',
                        timeout: 10000
                    });

                    if (response.success && response.data) {
                        jabatanSelect.html('<option value="">Pilih Jabatan</option>');

                        response.data.forEach(function(jabatan) {
                            const displayText = jabatan.singkatan_jbt ?
                                `${jabatan.nama_jbt} (${jabatan.singkatan_jbt})` :
                                jabatan.nama_jbt;

                            const selected = jabatan.id == selectedJabatanId ? 'selected' : '';
                            jabatanSelect.append(
                                `<option value="${jabatan.id}"
                    data-nama-jbt="${jabatan.nama_jbt || ''}"
                    data-singkatan-jbt="${jabatan.singkatan_jbt || ''}"
                    ${selected}
                >${displayText}</option>`
                            );
                        });

                        jabatanSelect.prop('disabled', false);

                        // Reinitialize Select2
                        if (jabatanSelect.hasClass('select2-hidden-accessible')) {
                            jabatanSelect.select2('destroy');
                        }
                        jabatanSelect.select2({
                            theme: 'bootstrap-5'
                        });

                        // Set singkatan jabatan if selected
                        if (selectedJabatanId) {
                            setTimeout(() => {
                                const selectedOption = jabatanSelect.find('option:selected');
                                const singkatanJbt = selectedOption.data('singkatan-jbt') || '';
                                $('#info_singkatan_jbt').val(singkatanJbt);
                                console.log('✅ Jabatan selected, singkatan set:', singkatanJbt);
                            }, 100);
                        }

                        console.log(`✅ ${response.data.length} jabatan loaded for employee`);
                    }
                } catch (error) {
                    console.error('❌ Error loading jabatan for employee:', error);
                    jabatanSelect.html('<option value="">Error loading jabatan</option>').prop('disabled',
                        false);
                }
            }

            async function loadUnitKerjaForEmployee(wilayahKrj, selectedUnitKrj) {
                const areaSelect = $('#id_wilker');

                if (!wilayahKrj) return;

                console.log('🔄 Loading unit kerja for wilayah:', wilayahKrj, 'selected:', selectedUnitKrj);
                areaSelect.prop('disabled', true).html('<option value="">Loading area kerja...</option>');

                try {
                    const response = await $.ajax({
                        url: `/data-kontrak/get-unit-kerja-by-wilayah/${encodeURIComponent(wilayahKrj)}`,
                        type: 'GET',
                        dataType: 'json',
                        timeout: 10000
                    });

                    if (response.success && response.data) {
                        areaSelect.html('<option value="">Pilih Area Kerja</option>');

                        response.data.forEach(function(area) {
                            const selected = area.id == selectedUnitKrj ? 'selected' : '';
                            areaSelect.append(
                                `<option value="${area.id}"
                        data-area-krj="${area.area_krj || ''}"
                        data-singkatan-wk="${area.singkatan_wk || ''}"
                        data-wilayah-krj="${area.wilayah_krj || ''}"
                        ${selected}
                    >${area.area_krj}</option>`
                            );
                        });

                        areaSelect.prop('disabled', false);

                        // Reinitialize Select2
                        if (areaSelect.hasClass('select2-hidden-accessible')) {
                            areaSelect.select2('destroy');
                        }
                        areaSelect.select2({
                            theme: 'bootstrap-5'
                        });

                        // Set singkatan wilker if selected
                        if (selectedUnitKrj) {
                            setTimeout(() => {
                                const selectedOption = areaSelect.find('option:selected');
                                const singkatanWk = selectedOption.data('singkatan-wk') || '';
                                $('#info_skt_wilker').val(singkatanWk);
                                console.log('✅ Unit kerja selected, singkatan set:', singkatanWk);
                            }, 100);
                        }

                        console.log(`✅ ${response.data.length} area kerja loaded for employee`);
                    }
                } catch (error) {
                    console.error('❌ Error loading unit kerja for employee:', error);
                    areaSelect.html('<option value="">Error loading area kerja</option>').prop('disabled',
                        false);
                }
            }

            // ===== EMPLOYEE SELECTION WITH AUTO-POPULATE =====
            $('#id_data_kry').on('change', function() {
                const karyawanId = $(this).val();
                console.log('🔍 Selected employee ID:', karyawanId);

                if (karyawanId) {
                    $('#employeeInfo').show();

                    // Show loading indicators
                    const loadingText = 'Loading...';
                    $('#emp_nik, #emp_nrk, #emp_tpt_lahir, #emp_tgl_lahir, #emp_sex, #emp_tlp1, #emp_email1, #emp_sts_kry')
                        .val(loadingText);

                    // AJAX request to get employee data
                    $.ajax({
                        url: `/data-kontrak/get-employee-data/${karyawanId}`,
                        type: 'GET',
                        dataType: 'json',
                        timeout: 15000,
                        success: function(response) {
                            console.log('✅ Employee data response:', response);

                            if (response.success && response.data) {
                                const emp = response.data;

                                // Fill employee basic info
                                $('#emp_nik').val(emp.nik || '-');
                                $('#emp_nrk').val(emp.nrk || '-');
                                $('#emp_tpt_lahir').val(emp.tpt_lahir || '-');
                                $('#emp_tgl_lahir').val(emp.tgl_lahir_formatted || '-');
                                $('#emp_sex').val(emp.sex || '-');
                                $('#emp_tlp1').val(emp.tlp1 || '-');
                                $('#emp_email1').val(emp.email1 || '-');
                                $('#emp_sts_kry').val(emp.sts_kry || '-');

                                // Auto-populate education data
                                populateEducationData(emp);

                                // Auto-populate career data
                                populateCareerData(emp);

                                // Fill hubungan industrial data
                                $('#hubin_tgl_masuk').val(emp.tgl_masuk_formatted || '-');
                                $('#hubin_sts_kry').val(emp.sts_kry || '-');
                                $('#hubin_tgl_phk').val(emp.tgl_phk_formatted || '-');
                                $('#hubin_ket_phk').val(emp.ket_phk || '-');

                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Data karyawan berhasil dimuat',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                        },
                        error: function(xhr) {
                            console.error('❌ Employee AJAX Error:', xhr);
                            $('#emp_nik, #emp_nrk, #emp_tpt_lahir, #emp_tgl_lahir, #emp_sex, #emp_tlp1, #emp_email1, #emp_sts_kry')
                                .val('Error');

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Gagal memuat data karyawan',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    });
                } else {
                    $('#employeeInfo').hide();
                    // Clear all fields when no employee selected
                    $('#employeeInfo input, #hubin_tgl_masuk, #hubin_sts_kry, #hubin_tgl_phk, #hubin_ket_phk').val('');

                    // Clear education fields
                    $('#jenjang_skl').val('').trigger('change.select2');
                    $('#institusi_skl, #skt_inst_skl, #kota_skl, #fakultas_skl, #jurusan_skl, #gelar_skl').val('');
                    $('#tgl_lulus_skl').val('');

                    // Clear career fields
                    clearCareerFields();
                }
            });

            function populateEducationData(employeeData) {
                $('#jenjang_skl').val(employeeData.jenjang_skl || '').trigger('change.select2');
                $('#institusi_skl').val(employeeData.institusi_skl || '');
                $('#skt_inst_skl').val(employeeData.skt_inst_skl || '');
                $('#kota_skl').val(employeeData.kota_skl || '');
                $('#fakultas_skl').val(employeeData.fakultas_skl || '');
                $('#jurusan_skl').val(employeeData.jurusan_skl || '');
                $('#gelar_skl').val(employeeData.gelar_skl || '');

                if (employeeData.tgl_lulus_skl) {
                    $('#tgl_lulus_skl').val(employeeData.tgl_lulus_skl);
                }
            }

            async function populateCareerData(employeeData) {
                try {
                    // Populate departemen
                    if (employeeData.jabatan) {
                        $('#departemen_nama').val(employeeData.jabatan).trigger('change.select2');

                        setTimeout(() => {
                            const selectedOption = $('#departemen_nama').find('option:selected');
                            const singkatanFromAttr = selectedOption.data('singkatan');
                            const singkatanFromEmp = employeeData.skt_dep;
                            const finalSingkatan = singkatanFromAttr || singkatanFromEmp || '';
                            $('#info_singkatan_dep').val(finalSingkatan);
                        }, 200);

                        // Load jabatan
                        setTimeout(async () => {
                            if (employeeData.departemen) {
                                await loadJabatanForEmployee(employeeData.jabatan, employeeData.departemen);
                            }
                        }, 600);
                    }

                    // Populate wilayah kerja
                    if (employeeData.wilker) {
                        $('#wilayah_kerja_nama').val(employeeData.wilker).trigger('change.select2');

                        setTimeout(async () => {
                            const unitKrjId = employeeData.unit_krj_id || employeeData.unit_krj || employeeData.id_unit_krj;
                            if (unitKrjId) {
                                await loadUnitKerjaForEmployee(employeeData.wilker, unitKrjId);
                            } else {
                                await loadUnitKerjaForEmployee(employeeData.wilker, null);
                            }
                        }, 600);
                    }

                    $('#tugas').val(employeeData.tugas || '');
                } catch (error) {
                    console.error('❌ Error populating career data:', error);
                }
            }

            function clearCareerFields() {
                $('#departemen_nama').val('').trigger('change.select2');
                $('#id_departemen').html('<option value="">Pilih Jabatan</option>').prop('disabled', true);
                $('#wilayah_kerja_nama').val('').trigger('change.select2');
                $('#id_wilker').html('<option value="">Pilih Area Kerja</option>').prop('disabled', true);
                $('#info_singkatan_dep, #info_singkatan_jbt, #info_skt_wilker').val('');
                $('#tugas').val('');
            }

            async function loadJabatanForEmployee(namaDep, selectedJabatanId) {
                const jabatanSelect = $('#id_departemen');

                if (!namaDep) return;

                jabatanSelect.prop('disabled', true).html('<option value="">Loading jabatan...</option>');

                try {
                    const response = await $.ajax({
                        url: `/data-kontrak/get-jabatan-by-departemen/${encodeURIComponent(namaDep)}`,
                        type: 'GET',
                        dataType: 'json',
                        timeout: 10000
                    });

                    if (response.success && response.data) {
                        jabatanSelect.html('<option value="">Pilih Jabatan</option>');

                        response.data.forEach(function(jabatan) {
                            const displayText = jabatan.singkatan_jbt ?
                                `${jabatan.nama_jbt} (${jabatan.singkatan_jbt})` :
                                jabatan.nama_jbt;

                            const selected = jabatan.id == selectedJabatanId ? 'selected' : '';
                            jabatanSelect.append(
                                `<option value="${jabatan.id}"
                                    data-nama-jbt="${jabatan.nama_jbt || ''}"
                                    data-singkatan-jbt="${jabatan.singkatan_jbt || ''}"
                                    ${selected}
                                >${displayText}</option>`
                            );
                        });

                        jabatanSelect.prop('disabled', false);

                        if (jabatanSelect.hasClass('select2-hidden-accessible')) {
                            jabatanSelect.select2('destroy');
                        }
                        jabatanSelect.select2({
                            theme: 'bootstrap-5'
                        });

                        if (selectedJabatanId) {
                            setTimeout(() => {
                                const selectedOption = jabatanSelect.find('option:selected');
                                const singkatanJbt = selectedOption.data('singkatan-jbt') || '';
                                $('#info_singkatan_jbt').val(singkatanJbt);
                            }, 100);
                        }
                    }
                } catch (error) {
                    console.error('❌ Error loading jabatan:', error);
                    jabatanSelect.html('<option value="">Error loading jabatan</option>').prop('disabled', false);
                }
            }

            async function loadUnitKerjaForEmployee(wilayahKrj, selectedUnitKrj) {
                const areaSelect = $('#id_wilker');

                if (!wilayahKrj) return;

                areaSelect.prop('disabled', true).html('<option value="">Loading area kerja...</option>');

                try {
                    const response = await $.ajax({
                        url: `/data-kontrak/get-unit-kerja-by-wilayah/${encodeURIComponent(wilayahKrj)}`,
                        type: 'GET',
                        dataType: 'json',
                        timeout: 10000
                    });

                    if (response.success && response.data) {
                        areaSelect.html('<option value="">Pilih Area Kerja</option>');

                        response.data.forEach(function(area) {
                            const selected = area.id == selectedUnitKrj ? 'selected' : '';
                            areaSelect.append(
                                `<option value="${area.id}"
                                    data-area-krj="${area.area_krj || ''}"
                                    data-singkatan-wk="${area.singkatan_wk || ''}"
                                    data-wilayah-krj="${area.wilayah_krj || ''}"
                                    ${selected}
                                >${area.area_krj}</option>`
                            );
                        });

                        areaSelect.prop('disabled', false);

                        if (areaSelect.hasClass('select2-hidden-accessible')) {
                            areaSelect.select2('destroy');
                        }
                        areaSelect.select2({
                            theme: 'bootstrap-5'
                        });

                        if (selectedUnitKrj) {
                            setTimeout(() => {
                                const selectedOption = areaSelect.find('option:selected');
                                const singkatanWk = selectedOption.data('singkatan-wk') || '';
                                $('#info_skt_wilker').val(singkatanWk);
                            }, 100);
                        }
                    }
                } catch (error) {
                    console.error('❌ Error loading unit kerja:', error);
                    areaSelect.html('<option value="">Error loading area kerja</option>').prop('disabled', false);
                }
            }

            // ===== HANDLERS FOR DROPDOWN CHANGES =====
            $('#id_ktr').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const singkatanKtr = selectedOption.data('singkatan-ktr') || '';
                $('#info_singkatan_ktr').val(singkatanKtr);
            });

            $('#id_prsh').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const namaPrs2 = selectedOption.data('nama-prs2') || '';
                $('#info_nama_prs2').val(namaPrs2);
            });

            $('#departemen_nama').on('change', function() {
                const namaDep = $(this).val();
                const jabatanSelect = $('#id_departemen');

                if (namaDep) {
                    const selectedOption = $(this).find('option:selected');
                    const singkatanDep = selectedOption.data('singkatan') || '';
                    $('#info_singkatan_dep').val(singkatanDep);

                    jabatanSelect.prop('disabled', true).html('<option value="">Loading...</option>');

                    $.ajax({
                        url: `/data-kontrak/get-jabatan-by-departemen/${encodeURIComponent(namaDep)}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success && response.data) {
                                jabatanSelect.html('<option value="">Pilih Jabatan</option>');

                                response.data.forEach(function(jabatan) {
                                    jabatanSelect.append(
                                        `<option value="${jabatan.id}"
                                            data-nama-jbt="${jabatan.nama_jbt || ''}"
                                            data-singkatan-jbt="${jabatan.singkatan_jbt || ''}"
                                        >${jabatan.nama_jbt}</option>`
                                    );
                                });

                                jabatanSelect.prop('disabled', false);

                                if (jabatanSelect.hasClass('select2-hidden-accessible')) {
                                    jabatanSelect.select2('destroy');
                                }
                                jabatanSelect.select2({
                                    theme: 'bootstrap-5'
                                });
                            }
                        },
                        error: function(xhr) {
                            jabatanSelect.html('<option value="">Error loading</option>').prop('disabled', false);
                        }
                    });
                } else {
                    jabatanSelect.html('<option value="">Pilih Jabatan</option>').prop('disabled', true);
                    $('#info_singkatan_dep, #info_singkatan_jbt').val('');
                }
            });

            $('#id_departemen').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const singkatanJbt = selectedOption.data('singkatan-jbt') || '';
                $('#info_singkatan_jbt').val(singkatanJbt);
            });

            $('#wilayah_kerja_nama').on('change', function() {
                const wilayahKrj = $(this).val();
                const areaSelect = $('#id_wilker');

                if (wilayahKrj) {
                    areaSelect.prop('disabled', true).html('<option value="">Loading...</option>');

                    $.ajax({
                        url: `/data-kontrak/get-unit-kerja-by-wilayah/${encodeURIComponent(wilayahKrj)}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success && response.data) {
                                areaSelect.html('<option value="">Pilih Area Kerja</option>');

                                response.data.forEach(function(area) {
                                    areaSelect.append(
                                        `<option value="${area.id}"
                                            data-area-krj="${area.area_krj || ''}"
                                            data-singkatan-wk="${area.singkatan_wk || ''}"
                                        >${area.area_krj}</option>`
                                    );
                                });

                                areaSelect.prop('disabled', false);

                                if (areaSelect.hasClass('select2-hidden-accessible')) {
                                    areaSelect.select2('destroy');
                                }
                                areaSelect.select2({
                                    theme: 'bootstrap-5'
                                });
                            }
                        },
                        error: function(xhr) {
                            areaSelect.html('<option value="">Error loading</option>').prop('disabled', false);
                        }
                    });
                } else {
                    areaSelect.html('<option value="">Pilih Area Kerja</option>').prop('disabled', true);
                    $('#info_skt_wilker').val('');
                }
            });

            $('#id_wilker').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const singkatanWk = selectedOption.data('singkatan-wk') || '';
                $('#info_skt_wilker').val(singkatanWk);
            });

            // ===== CONTRACT DURATION CALCULATION =====
            function calculateContractDuration() {
                if ($('#ktg_ktk').val() === 'TETAP') {
                    return;
                }

                const startDate = document.getElementById('tgl_awl_ktr').value;
                const endDate = document.getElementById('tgl_akhir_ktr').value;

                if (startDate && endDate) {
                    const start = new Date(startDate);
                    const end = new Date(endDate);

                    if (end > start) {
                        const months = (end.getFullYear() - start.getFullYear()) * 12 +
                            (end.getMonth() - start.getMonth());
                        document.getElementById('durasi_ktr').value = months > 0 ? months : '';
                    } else {
                        document.getElementById('durasi_ktr').value = '';
                    }
                }
            }

            function calculateReminderDuration() {
                const reminderDate = document.getElementById('tgl_pgt_ktr').value;
                const endDate = document.getElementById('tgl_akhir_ktr').value;

                if (reminderDate && endDate) {
                    const reminder = new Date(reminderDate);
                    const end = new Date(endDate);

                    if (reminder < end) {
                        const timeDiff = end.getTime() - reminder.getTime();
                        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                        document.getElementById('durasi_pgt').value = daysDiff > 0 ? daysDiff : '';
                    } else {
                        document.getElementById('durasi_pgt').value = '';
                        if (reminder >= end) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'warning',
                                title: 'Tanggal pengingat harus sebelum tanggal akhir kontrak',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    }
                } else {
                    document.getElementById('durasi_pgt').value = '';
                }
            }

            // Event listeners for calculations
            document.getElementById('tgl_awl_ktr').addEventListener('change', function() {
                calculateContractDuration();
                if (document.getElementById('tgl_pgt_ktr').value) {
                    calculateReminderDuration();
                }
            });

            document.getElementById('tgl_akhir_ktr').addEventListener('change', function() {
                calculateContractDuration();
                calculateReminderDuration();
            });

            document.getElementById('tgl_pgt_ktr').addEventListener('change', function() {
                const reminderDate = this.value;
                const endDate = document.getElementById('tgl_akhir_ktr').value;

                if (reminderDate && endDate) {
                    const reminder = new Date(reminderDate);
                    const end = new Date(endDate);

                    if (reminder >= end) {
                        this.value = '';
                        document.getElementById('durasi_pgt').value = '';
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Tanggal pengingat harus sebelum tanggal akhir kontrak',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } else {
                        calculateReminderDuration();
                    }
                } else {
                    calculateReminderDuration();
                }
            });

            // ===== FORM VALIDATION =====
            document.getElementById('kontrakForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const requiredFields = this.querySelectorAll('[data-required="true"]:not(:disabled)');
                let missingFields = [];
                let firstInvalidField = null;

                // Remove previous error highlighting
                this.querySelectorAll('[data-required="true"]').forEach(function(field) {
                    field.classList.remove('is-invalid');
                });

                document.querySelectorAll('.nav-link').forEach(function(tab) {
                    tab.classList.remove('has-error');
                });

                // Check required fields
                requiredFields.forEach(function(field) {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        const label = field.closest('.form-group')?.querySelector('label')
                            ?.textContent?.replace('*', '').replace(/\([^)]*\)/g, '').trim() ||
                            field.name;
                        missingFields.push(label);

                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                    }
                });

                if (missingFields.length > 0) {
                    let fieldsList = '<ul class="text-start mb-0">';
                    missingFields.forEach(function(fieldName) {
                        fieldsList += '<li>' + fieldName + '</li>';
                    });
                    fieldsList += '</ul>';

                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        html: '<p class="mb-2">Mohon lengkapi field berikut yang wajib diisi:</p>' +
                            fieldsList,
                        confirmButtonText: 'OK, Saya Mengerti',
                        confirmButtonColor: '#0d6efd',
                    });

                    // Navigate to tab with error and focus on first invalid field
                    if (firstInvalidField) {
                        const tabPane = firstInvalidField.closest('.tab-pane');
                        if (tabPane) {
                            const tabId = tabPane.id;
                            const tabButton = document.querySelector(`[data-bs-target="#${tabId}"]`);
                            if (tabButton) {
                                tabButton.classList.add('has-error');
                                const tab = new bootstrap.Tab(tabButton);
                                tab.show();

                                setTimeout(() => {
                                    firstInvalidField.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });
                                    firstInvalidField.focus();
                                }, 300);
                            }
                        }
                    }

                    return false;
                } else {
                    // Show loading state and submit
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengupdate...';
                    submitBtn.disabled = true;
                    this.submit();
                }
            });

            // Remove error highlighting when field is filled
            document.querySelectorAll('[data-required="true"]').forEach(function(field) {
                field.addEventListener('change', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            console.log('✅ Data Kontrak Edit JavaScript initialized');
        });
    </script>
@endpush