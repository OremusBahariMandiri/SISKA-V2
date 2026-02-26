@extends('layouts.app')

@section('title', 'Tambah Data Dokumen')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-alt me-2"></i>Tambah Data Dokumen</span>
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

                        <form action="{{ route('data-dokumen.store') }}" method="POST" id="dokumenForm"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            <input type="hidden" class="form-control" id="id_kode" name="id_kode"
                                value="{{ old('id_kode', $newId) }}" readonly>

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
                                                                            {{ old('id_data_kry') == $karyawan->id ? 'selected' : '' }}>
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

                                            <!-- Employee Info Display -->
                                            <div id="employeeInfo" class="row" style="display: none;">
                                                <div class="col-md-12">
                                                    <div class="card bg-light border-0">
                                                        <div class="card-header bg-primary text-white">
                                                            <h6 class="mb-0"><i
                                                                    class="fas fa-info-circle me-2"></i>Informasi Karyawan
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <!-- Foto Karyawan -->
                                                                <div class="col-md-3 text-center mb-4">
                                                                    <div class="employee-photo-container">
                                                                        <img id="emp_foto" src=""
                                                                            alt="Foto Karyawan"
                                                                            class="img-fluid rounded shadow employee-photo"
                                                                            style="display: none;">
                                                                        <div id="emp_foto_placeholder"
                                                                            class="default-avatar rounded shadow">
                                                                            <i
                                                                                class="fas fa-user-circle fa-8x text-secondary"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Informasi Karyawan -->
                                                                <div class="col-md-9">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label
                                                                                    class="form-label fw-bold">NIK</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_nik" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label
                                                                                    class="form-label fw-bold">NRK</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_nrk" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Jenis
                                                                                    Kelamin</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_sex" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Tempat
                                                                                    Lahir</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_tpt_lahir" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Tanggal
                                                                                    Lahir</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_tgl_lahir" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label
                                                                                    class="form-label fw-bold">Telepon</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_tlp1" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Status
                                                                                    Kawin</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_sts_nikah" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Jumlah
                                                                                    Anak</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_jml_anak" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label
                                                                                    class="form-label fw-bold">Email</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_email" readonly>
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
                                    </div>
                                </div>

                                <!-- Tab 2: Data Dokumen -->
                                <div class="tab-pane fade" id="dokumen" role="tabpanel" aria-labelledby="dokumen-tab">

                                    <!-- Card 1: Informasi Umum Dokumen -->
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white">
                                                <i class="fas fa-file-alt me-2"></i>Informasi Umum Dokumen
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Kategori Dokumen -->
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="ktg_dok_kry" class="form-label fw-bold">Kategori
                                                            Dokumen
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-folder"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="ktg_dok_kry"
                                                                    name="ktg_dok_kry" data-required="true">
                                                                    <option value="">Pilih Kategori</option>
                                                                    @php
                                                                        $categories = $dokumenTypes
                                                                            ->unique('ktg_dok_kry')
                                                                            ->pluck('ktg_dok_kry')
                                                                            ->sort();
                                                                    @endphp
                                                                    @foreach ($categories as $category)
                                                                        <option value="{{ $category }}"
                                                                            {{ old('ktg_dok_kry') == $category ? 'selected' : '' }}>
                                                                            {{ $category }}
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
                                                        <label for="jns_dok_kry" class="form-label fw-bold">Jenis Dokumen
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-tag"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="jns_dok_kry"
                                                                    name="jns_dok_kry" data-required="true" disabled>
                                                                    <option value="">Pilih Jenis Dokumen</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Kode Dokumen -->
                                                <!-- Kode Dokumen - AUTO GENERATED (Read-only display) -->
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="kode_dok_kry_display" class="form-label fw-bold">Kode
                                                            Dokumen
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-barcode"></i></span>
                                                            <input type="text" class="form-control bg-light"
                                                                id="kode_dok_kry_display" placeholder="Otomatis terisi"
                                                                readonly>
                                                        </div>
                                                        <div class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>Kode otomatis
                                                            berdasarkan jenis dokumen
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Hidden fields untuk database -->
                                                <input type="hidden" id="id_dokumen" name="id_dokumen"
                                                    value="{{ old('id_dokumen') }}">
                                                <input type="hidden" id="kode_dok_kry" name="kode_dok_kry"
                                                    value="{{ old('kode_dok_kry') }}">

                                                <!-- No Dokumen -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="no_dok" class="form-label fw-bold">No.
                                                            Dokumen</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-hashtag"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="no_dok" name="no_dok"
                                                                value="{{ old('no_dok') }}"
                                                                placeholder="Masukkan nomor dokumen">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Tanggal TTD -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_ttd" class="form-label fw-bold">Tanggal
                                                            TTD/Terbit</label>
                                                        <input type="date" class="form-control" id="tgl_ttd"
                                                            name="tgl_ttd" value="{{ old('tgl_ttd') }}">
                                                    </div>
                                                </div>

                                                <!-- File Dokumen -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="file_dok" class="form-label fw-bold">File
                                                            Dokumen</label>
                                                        <input type="file" class="form-control" id="file_dok"
                                                            name="file_dok" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                        <div class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>Format: PDF, DOC, DOCX,
                                                            JPG, PNG (Max: 5MB)
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Status Dokumen -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="sts_dok" class="form-label fw-bold">Status Dokumen
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-check-circle"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="sts_dok"
                                                                    name="sts_dok" data-required="true">
                                                                    <option value="">Pilih Status</option>
                                                                    <option value="AKTIF"
                                                                        {{ old('sts_dok') == 'AKTIF' ? 'selected' : '' }}>
                                                                        AKTIF</option>
                                                                    <option value="NON-AKTIF"
                                                                        {{ old('sts_dok') == 'NON-AKTIF' ? 'selected' : '' }}>
                                                                        NON-AKTIF</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 2: Periode Dokumen -->
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white">
                                                <i class="fas fa-calendar-alt me-2"></i>Periode Dokumen
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Jenis Masa Berlaku -->
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="jns_msb_dok" class="form-label fw-bold">Jenis Masa
                                                            Berlaku</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-hourglass-half"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="jns_msb_dok"
                                                                    name="jns_msb_dok">
                                                                    <option value="">Pilih Jenis Masa Berlaku
                                                                    </option>
                                                                    <option value="TETAP"
                                                                        {{ old('jns_msb_dok') == 'TETAP' ? 'selected' : '' }}>
                                                                        TETAP</option>
                                                                    <option value="PERPANJANGAN"
                                                                        {{ old('jns_msb_dok') == 'PERPANJANGAN' ? 'selected' : '' }}>
                                                                        PERPANJANGAN</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>Pilih TETAP untuk
                                                            dokumen tanpa masa berlaku (KTP, Ijazah, dll)
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Tanggal Akhir Berlaku -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_akr_dok" class="form-label fw-bold">Tanggal Akhir
                                                            Berlaku</label>
                                                        <input type="date" class="form-control" id="tgl_akr_dok"
                                                            name="tgl_akr_dok" value="{{ old('tgl_akr_dok') }}">
                                                    </div>
                                                </div>

                                                <!-- Masa Berlaku (Bulan) -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="msb_dok" class="form-label fw-bold">Masa Berlaku
                                                            (Bulan)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-clock"></i></span>
                                                            <input type="number" class="form-control" id="msb_dok"
                                                                name="msb_dok" value="{{ old('msb_dok') }}"
                                                                min="1" readonly>
                                                        </div>
                                                        <div class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>Otomatis dihitung dari
                                                            tanggal TTD ke tanggal akhir berlaku
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Tanggal Pengingat -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_pgt_dok" class="form-label fw-bold">Tanggal
                                                            Pengingat</label>
                                                        <input type="date" class="form-control" id="tgl_pgt_dok"
                                                            name="tgl_pgt_dok" value="{{ old('tgl_pgt_dok') }}">
                                                        <div class="form-text text-muted">
                                                            <i class="fas fa-bell me-1"></i>Tanggal untuk memulai pengingat
                                                            perpanjangan
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Durasi Pengingat (Hari) -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="durasi_pgt" class="form-label fw-bold">Durasi
                                                            Pengingat (Hari)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-bell"></i></span>
                                                            <input type="number" class="form-control" id="durasi_pgt"
                                                                name="durasi_pgt" value="{{ old('durasi_pgt') }}"
                                                                min="1" readonly>
                                                        </div>
                                                        <div class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>Otomatis dihitung dari
                                                            tanggal pengingat ke tanggal akhir berlaku
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 3: Keterangan Tambahan -->
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white">
                                                <i class="fas fa-comment-alt me-2"></i>Keterangan Tambahan
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Keterangan -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="ket_dok"
                                                            class="form-label fw-bold">Keterangan</label>
                                                        <textarea class="form-control auto-uppercase" id="ket_dok" name="ket_dok" rows="3"
                                                            placeholder="Keterangan tambahan dokumen">{{ old('ket_dok') }}</textarea>
                                                    </div>
                                                </div>

                                                <!-- Catatan -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="ctt_dok" class="form-label fw-bold">Catatan</label>
                                                        <textarea class="form-control auto-uppercase" id="ctt_dok" name="ctt_dok" rows="3"
                                                            placeholder="Catatan internal dokumen">{{ old('ctt_dok') }}</textarea>
                                                    </div>
                                                </div>

                                                <!-- Tanggal Non Aktif (conditional) -->
                                                <div class="col-md-6" id="field_tgl_dok_na" style="display: none;">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_dok_na" class="form-label fw-bold">Tanggal Status
                                                            Non Aktif</label>
                                                        <input type="date" class="form-control" id="tgl_dok_na"
                                                            name="tgl_dok_na" value="{{ old('tgl_dok_na') }}">
                                                    </div>
                                                </div>

                                                <!-- Keterangan Non Aktif (conditional) -->
                                                <div class="col-md-6" id="field_ket_dok_na" style="display: none;">
                                                    <div class="form-group mb-3">
                                                        <label for="ket_dok_na" class="form-label fw-bold">Keterangan Non
                                                            Aktif</label>
                                                        <textarea class="form-control auto-uppercase" id="ket_dok_na" name="ket_dok_na" rows="3"
                                                            placeholder="Alasan status non aktif">{{ old('ket_dok_na') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- Tab 3: Kontrak Kerja (dari data karyawan) -->
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
                                                            id="kontrak_perusahaan_nama" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Status Kontrak</label>
                                                        <input type="text" class="form-control"
                                                            id="kontrak_sts_ktr_nama" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tanggal Awal Kontrak</label>
                                                        <input type="text" class="form-control"
                                                            id="kontrak_tgl_awal_ktr" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tanggal Akhir Kontrak</label>
                                                        <input type="text" class="form-control"
                                                            id="kontrak_tgl_akhir_ktr" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Durasi Kontrak (Bulan)</label>
                                                        <input type="text" class="form-control"
                                                            id="kontrak_durasi_ktr" readonly>
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
                                                            id="karir_departemen_nama" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Jabatan</label>
                                                        <input type="text" class="form-control"
                                                            id="karir_jabatan_nama" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Wilayah Kerja</label>
                                                        <input type="text" class="form-control" id="karir_wilker_nama"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Unit Kerja</label>
                                                        <input type="text" class="form-control"
                                                            id="karir_unit_krj_nama" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tugas & Tanggung Jawab</label>
                                                        <textarea class="form-control" id="karir_tugas" rows="4" readonly></textarea>
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
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Status Karyawan</label>
                                                        <input type="text" class="form-control" id="hubin_sts_kry"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tanggal PHK</label>
                                                        <input type="text" class="form-control" id="hubin_tgl_phk"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Keterangan PHK</label>
                                                        <input type="text" class="form-control" id="hubin_ket_phk"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <a href="{{ route('data-dokumen.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                    <i class="fas fa-save me-2"></i> Simpan Data Dokumen
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
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />

    <style>
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
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        /* Select2 Custom Styling */
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 6px 12px;
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

        /* Employee Validation Styling */
        .employee-validation-error {
            border: 2px solid #dc3545 !important;
            border-radius: 0.375rem !important;
            background-color: rgba(248, 215, 218, 0.3);
            animation: shake 0.8s ease-in-out;
        }

        @keyframes shake {

            0%,
            20%,
            40%,
            60%,
            80% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-10px);
            }
        }

        .employee-validation-success {
            border: 2px solid #198754 !important;
            border-radius: 0.375rem !important;
            background-color: rgba(25, 135, 84, 0.1);
            animation: successPulse 1s ease-in-out;
        }

        @keyframes successPulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
            }

            50% {
                transform: scale(1.02);
                box-shadow: 0 0 0 10px rgba(25, 135, 84, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
            }
        }

        /* Loading state */
        .employee-loading {
            position: relative;
        }

        .employee-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 35px;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #0d6efd;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            z-index: 10;
        }

        @keyframes spin {
            0% {
                transform: translateY(-50%) rotate(0deg);
            }

            100% {
                transform: translateY(-50%) rotate(360deg);
            }
        }

        /* Form Validation */
        .is-invalid {
            border-color: #dc3545 !important;
            background-color: rgba(220, 53, 69, 0.05);
        }

        .nav-link.has-error {
            color: #dc3545 !important;
            border-color: #dc3545 !important;
            background-color: rgba(220, 53, 69, 0.1);
        }

        /* Employee Photo Styling */
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

        .employee-photo.loaded {
            animation: photoFadeIn 0.5s ease-in-out;
        }

        @keyframes photoFadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
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

        .default-avatar i {
            transition: all 0.3s ease;
        }

        .default-avatar:hover i {
            transform: scale(1.1);
            color: #0d6efd !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
        // Store all document types data for cascading
        const allDokumenTypes = @json($dokumenTypes);

        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Data Dokumen Form Script Loading...');
            console.log('📦 Total Dokumen Types:', allDokumenTypes.length);

            // Auto-uppercase functionality - FIXED VERSION
            document.querySelectorAll('input.auto-uppercase, textarea.auto-uppercase').forEach(
                function(element) {
                    // Only transform text inputs and textareas, skip checkboxes/radios
                    if (element.type === 'text' || element.tagName.toLowerCase() === 'textarea') {
                        element.addEventListener('input', function() {
                            this.value = this.value.toUpperCase();
                        });
                    }
                });

            // ===== CASCADING SELECT: KATEGORI → JENIS → KODE =====

            // When Kategori changes
            // ===== CASCADING SELECT: KATEGORI → JENIS → KODE (AUTO) =====

            // When Kategori changes
            // ===== CASCADING SELECT: KATEGORI → JENIS → KODE (AUTO) =====

            // When Kategori changes
            $('#ktg_dok_kry').on('change', function() {
                const selectedCategory = $(this).val();
                console.log('📂 Selected Category:', selectedCategory);

                // Reset dependent fields
                $('#jns_dok_kry').val('').trigger('change').prop('disabled', true);
                $('#kode_dok_kry_display').val('');
                $('#id_dokumen').val('');
                $('#kode_dok_kry').val('');

                if (selectedCategory) {
                    // Filter documents by category
                    const filteredByCategory = allDokumenTypes.filter(doc =>
                        doc.ktg_dok_kry === selectedCategory
                    );

                    console.log('📋 Documents in category:', filteredByCategory.length);

                    // Sort by kode_dok_kry first, then get unique jenis_dok_kry
                    const sortedDocs = filteredByCategory.sort((a, b) => {
                        return (a.kode_dok_kry || '').localeCompare(b.kode_dok_kry || '');
                    });

                    // Get unique jenis_dok_kry while maintaining kode order
                    const uniqueJenisMap = new Map();
                    sortedDocs.forEach(doc => {
                        if (doc.jns_dok_kry && !uniqueJenisMap.has(doc.jns_dok_kry)) {
                            uniqueJenisMap.set(doc.jns_dok_kry, doc.kode_dok_kry);
                        }
                    });

                    // Convert to array and sort by kode
                    const uniqueJenis = Array.from(uniqueJenisMap.entries())
                        .sort((a, b) => a[1].localeCompare(b[1]))
                        .map(entry => entry[0]);

                    console.log('📋 Sorted Jenis by Kode:', uniqueJenis);

                    // Populate Jenis Dokumen dropdown
                    $('#jns_dok_kry').empty().append('<option value="">Pilih Jenis Dokumen</option>');
                    uniqueJenis.forEach(jenis => {
                        $('#jns_dok_kry').append(`<option value="${jenis}">${jenis}</option>`);
                    });

                    $('#jns_dok_kry').prop('disabled', false);
                }
            });

            // When Jenis changes - AUTO FILL Kode Dokumen
            $('#jns_dok_kry').on('change', function() {
                const selectedCategory = $('#ktg_dok_kry').val();
                const selectedJenis = $(this).val();
                console.log('🏷️ Selected Jenis:', selectedJenis);

                // Reset kode fields
                $('#kode_dok_kry_display').val('');
                $('#id_dokumen').val('');
                $('#kode_dok_kry').val('');

                if (selectedJenis) {
                    // Find ALL matching documents and sort by kode
                    const matchedDocuments = allDokumenTypes
                        .filter(doc =>
                            doc.ktg_dok_kry === selectedCategory &&
                            doc.jns_dok_kry === selectedJenis
                        )
                        .sort((a, b) => (a.kode_dok_kry || '').localeCompare(b.kode_dok_kry || ''));

                    if (matchedDocuments.length > 0) {
                        // Take the first one (smallest kode) as default
                        const matchedDocument = matchedDocuments[0];

                        console.log('✅ Auto-filling Kode:', matchedDocument.kode_dok_kry);
                        console.log('📊 Total matched documents:', matchedDocuments.length);

                        // Auto-fill the display field (read-only)
                        $('#kode_dok_kry_display').val(matchedDocument.kode_dok_kry);

                        // Set hidden fields for form submission
                        $('#id_dokumen').val(matchedDocument.id);
                        $('#kode_dok_kry').val(matchedDocument.kode_dok_kry);

                        // Visual feedback - success animation
                        $('#kode_dok_kry_display').addClass('employee-validation-success');
                        setTimeout(() => {
                            $('#kode_dok_kry_display').removeClass('employee-validation-success');
                        }, 2000);

                        console.log('📝 Form values set:');
                        console.log('   - ID Dokumen:', matchedDocument.id);
                        console.log('   - Kode Dokumen:', matchedDocument.kode_dok_kry);

                        // If multiple documents exist, show info
                        if (matchedDocuments.length > 1) {
                            console.log('ℹ️ Multiple codes available:',
                                matchedDocuments.map(d => d.kode_dok_kry).join(', '));
                        }
                    } else {
                        console.warn('⚠️ No matching document found');
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'warning',
                            title: 'Kode dokumen tidak ditemukan',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                }
            });

            // When Kode is selected - This is the final selection that sets id_dokumen
            $('#kode_dok_display').on('change', function() {
                const selectedId = $(this).val();
                const selectedKode = $(this).find('option:selected').data('kode');

                if (selectedId) {
                    // Set hidden fields for form submission
                    $('#id_dokumen').val(selectedId);
                    $('#kode_dok_kry').val(selectedKode);

                    console.log('✅ Final Selection:');
                    console.log('   - ID Dokumen:', selectedId);
                    console.log('   - Kode Dokumen:', selectedKode);
                } else {
                    $('#id_dokumen').val('');
                    $('#kode_dok_kry').val('');
                }
            });

            // ===== CONDITIONAL FIELD VISIBILITY =====
            function handleStatusDokumenChange() {
                const stsDok = $('#sts_dok').val();

                if (stsDok === 'NON-AKTIF') {
                    $('#field_tgl_dok_na').show();
                    $('#field_ket_dok_na').show();
                } else {
                    $('#field_tgl_dok_na').hide();
                    $('#field_ket_dok_na').hide();
                    $('#tgl_dok_na').val('');
                    $('#ket_dok_na').val('');
                }
            }

            function handleJenisMasaBerlakuChange() {
                const jnsMsbDok = $('#jns_msb_dok').val();
                const fieldsTglAkhir = $('#tgl_akr_dok');
                const fieldsMsb = $('#msb_dok');
                const fieldsTglPengingat = $('#tgl_pgt_dok');
                const fieldsDurasiPengingat = $('#durasi_pgt');

                if (jnsMsbDok === 'TETAP') {
                    fieldsTglAkhir.prop('disabled', true).val('');
                    fieldsMsb.prop('disabled', true).val('');
                    fieldsTglPengingat.prop('disabled', true).val('');
                    fieldsDurasiPengingat.prop('disabled', true).val('');

                    fieldsTglAkhir.closest('.form-group').addClass('opacity-50');
                    fieldsMsb.closest('.form-group').addClass('opacity-50');
                    fieldsTglPengingat.closest('.form-group').addClass('opacity-50');
                    fieldsDurasiPengingat.closest('.form-group').addClass('opacity-50');
                } else {
                    fieldsTglAkhir.prop('disabled', false);
                    fieldsMsb.prop('disabled', false);
                    fieldsTglPengingat.prop('disabled', false);
                    fieldsDurasiPengingat.prop('disabled', false);

                    $('.form-group').removeClass('opacity-50');
                }
            }

            // ===== VALIDATION ALERTS =====
            function showSuccessValidation(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Validasi Berhasil!',
                    html: `
                        <div class="text-center">
                            <h5 class="text-success mb-3">${response.employee_name}</h5>
                            <p class="mb-2"><strong>NRK:</strong> ${response.employee_nrk || 'Belum ada'}</p>
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-check-circle me-2"></i>
                                Karyawan belum memiliki data dokumen.<br>
                                <strong>Proses dapat dilanjutkan.</strong>
                            </div>
                        </div>
                    `,
                    confirmButtonText: 'Lanjutkan',
                    confirmButtonColor: '#198754',
                    timer: 4000,
                    timerProgressBar: true
                });
            }

            function showErrorValidation(response) {
                const latestDocument = response.latest_document;
                Swal.fire({
                    icon: 'error',
                    title: 'Karyawan Sudah Terdaftar!',
                    html: `
                        <div class="text-start">
                            <p><strong>Nama:</strong> ${response.employee_name}</p>
                            <p><strong>NRK:</strong> ${response.employee_nrk || 'N/A'}</p>
                            <p><strong>Total Dokumen:</strong> ${response.existing_documents_count} record</p>
                            <hr>
                            <p class="mb-2"><strong>Dokumen Terakhir:</strong></p>
                            <ul class="list-unstyled ms-3">
                                <li>• <strong>Jenis:</strong> ${latestDocument.document_type}</li>
                                <li>• <strong>No. Dokumen:</strong> ${latestDocument.document_number}</li>
                                <li>• <strong>Status:</strong> <span class="badge bg-${latestDocument.status === 'AKTIF' ? 'success' : 'secondary'}">${latestDocument.status}</span></li>
                                <li>• <strong>Dibuat:</strong> ${latestDocument.created_at}</li>
                            </ul>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Tidak dapat membuat dokumen baru!</strong><br>
                                Gunakan fitur <strong>"Tambah Dokumen"</strong> pada data yang sudah ada.
                            </div>
                        </div>
                    `,
                    confirmButtonText: 'Pilih Karyawan Lain',
                    confirmButtonColor: '#dc3545',
                    showCancelButton: true,
                    cancelButtonText: 'Lihat Data Dokumen',
                    cancelButtonColor: '#0d6efd',
                    allowOutsideClick: false,
                    width: '600px'
                }).then((result) => {
                    if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href =
                            `/data-dokumen?search=${encodeURIComponent(response.employee_name)}`;
                    }
                });
            }

            // ===== EMPLOYEE VALIDATION =====
            async function checkEmployeeExists(karyawanId) {
                try {
                    $('#id_data_kry').closest('.input-group').addClass('employee-loading');

                    const response = await $.ajax({
                        url: `/data-dokumen/check-employee/${karyawanId}`,
                        type: 'GET',
                        dataType: 'json',
                        timeout: 10000
                    });

                    $('#id_data_kry').closest('.input-group').removeClass('employee-loading');

                    if (response.success === true && response.exists === false) {
                        showSuccessValidation(response);
                        $('#id_data_kry').closest('.input-group').find('.form-select').addClass(
                            'employee-validation-success');
                        setTimeout(() => {
                            $('#id_data_kry').closest('.input-group').find('.form-select').removeClass(
                                'employee-validation-success');
                        }, 4000);
                        return true;
                    } else if (response.success === false && response.exists === true) {
                        showErrorValidation(response);
                        $('#id_data_kry').closest('.input-group').find('.form-select').addClass(
                            'employee-validation-error');
                        setTimeout(() => {
                            $('#id_data_kry').closest('.input-group').find('.form-select').removeClass(
                                'employee-validation-error');
                        }, 3000);
                        $('#id_data_kry').val('').trigger('change.select2');
                        clearAllEmployeeFields();
                        return false;
                    }
                } catch (error) {
                    console.error('❌ Validation Error:', error);
                    $('#id_data_kry').closest('.input-group').removeClass('employee-loading');

                    Swal.fire({
                        icon: 'error',
                        title: 'Data Sudah Terdaftar Dalam Sistem',
                        text: 'Silahkan cari pada halaman utama dan lakukan edit untuk melakukan perubahan data kontrak',
                        confirmButtonText: 'OK'
                    });

                    $('#id_data_kry').val('').trigger('change.select2');
                    clearAllEmployeeFields();
                    return false;
                }
            }

            // ===== LOAD EMPLOYEE DATA =====
            async function loadEmployeeData(karyawanId) {
                $('#employeeInfo').show();

                try {
                    const response = await $.ajax({
                        url: `/data-dokumen/get-employee-data/${karyawanId}`,
                        type: 'GET',
                        dataType: 'json',
                        timeout: 15000
                    });

                    if (response.success && response.data) {
                        const emp = response.data;

                        // Tab 1: Basic Info
                        $('#emp_nik').val(emp.nik || '-');
                        $('#emp_nrk').val(emp.nrk || '-');
                        $('#emp_tpt_lahir').val(emp.tpt_lahir || '-');
                        $('#emp_tgl_lahir').val(emp.tgl_lahir_formatted || '-');
                        $('#emp_sex').val(emp.sex || '-');
                        $('#emp_tlp1').val(emp.tlp1 || '-');
                        $('#emp_sts_nikah').val(emp.sts_nikah || '-');
                        $('#emp_jml_anak').val(emp.jml_anak || '-');
                        $('#emp_email').val(emp.email1 || '-');

                        // Photo
                        if (emp.foto_dokumen) {
                            $('#emp_foto').attr('src', `/storage/${emp.foto_dokumen}`).show().addClass(
                                'loaded');
                            $('#emp_foto_placeholder').hide();
                        } else {
                            $('#emp_foto').hide();
                            $('#emp_foto_placeholder').show();
                        }

                        // Tab 3: Kontrak Kerja
                        $('#kontrak_perusahaan_nama').val(emp.perusahaan_nama || '-');
                        $('#kontrak_sts_ktr_nama').val(emp.kontrak_nama || '-');
                        $('#kontrak_tgl_awal_ktr').val(emp.tgl_awal_ktr_formatted || '-');
                        $('#kontrak_tgl_akhir_ktr').val(emp.tgl_akhir_ktr_formatted || '-');
                        $('#kontrak_durasi_ktr').val(emp.durasi_ktr ? emp.durasi_ktr + ' bulan' : '-');

                        // Tab 4: Jenjang Karir
                        $('#karir_departemen_nama').val(emp.departemen_nama || '-');
                        $('#karir_jabatan_nama').val(emp.jabatan_nama || '-');
                        $('#karir_wilker_nama').val(emp.wilker_nama || '-');
                        $('#karir_unit_krj_nama').val(emp.unit_krj_nama || '-');
                        $('#karir_tugas').val(emp.tugas || '-');

                        // Tab 5: Hubungan Industrial
                        $('#hubin_tgl_masuk').val(emp.tgl_masuk_formatted || '-');
                        $('#hubin_sts_kry').val(emp.sts_kry || '-');
                        $('#hubin_tgl_phk').val(emp.tgl_phk_formatted || '-');
                        $('#hubin_ket_phk').val(emp.ket_phk || '-');
                    }
                } catch (error) {
                    console.error('❌ Load Employee Error:', error);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Gagal memuat data karyawan',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            }

            // ===== CLEAR EMPLOYEE FIELDS =====
            function clearAllEmployeeFields() {
                $('#employeeInfo').hide();
                $('#employeeInfo input, #employeeInfo textarea').val('');
                $('#emp_foto').hide().attr('src', '');
                $('#emp_foto_placeholder').show();
            }

            // ===== EMPLOYEE SELECTION =====
            $('#id_data_kry').on('change', function() {
                const karyawanId = $(this).val();
                if (karyawanId) {
                    checkEmployeeExists(karyawanId).then(canProceed => {
                        if (canProceed) {
                            setTimeout(() => {
                                loadEmployeeData(karyawanId);
                            }, 1000);
                        }
                    });
                } else {
                    clearAllEmployeeFields();
                }
            });

            // ===== EVENT HANDLERS =====
            $('#jns_msb_dok').on('change', handleJenisMasaBerlakuChange);
            $('#sts_dok').on('change', handleStatusDokumenChange);

            // ===== DATE CALCULATIONS =====
            function calculateValidityPeriod() {
                if ($('#jns_msb_dok').val() === 'TETAP') return;

                const signatureDate = $('#tgl_ttd').val();
                const expiryDate = $('#tgl_akr_dok').val();

                if (signatureDate && expiryDate) {
                    const start = new Date(signatureDate);
                    const end = new Date(expiryDate);

                    if (end > start) {
                        const months = (end.getFullYear() - start.getFullYear()) * 12 +
                            (end.getMonth() - start.getMonth());
                        $('#msb_dok').val(months > 0 ? months : '');
                    }
                }
            }

            function calculateReminderDuration() {
                const reminderDate = $('#tgl_pgt_dok').val();
                const expiryDate = $('#tgl_akr_dok').val();

                if (reminderDate && expiryDate) {
                    const reminder = new Date(reminderDate);
                    const expiry = new Date(expiryDate);

                    if (reminder < expiry) {
                        const timeDiff = expiry.getTime() - reminder.getTime();
                        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                        $('#durasi_pgt').val(daysDiff > 0 ? daysDiff : '');
                    }
                }
            }

            $('#tgl_ttd').on('change', calculateValidityPeriod);
            $('#tgl_akr_dok').on('change', function() {
                calculateValidityPeriod();
                calculateReminderDuration();
            });
            $('#tgl_pgt_dok').on('change', calculateReminderDuration);

            // ===== FORM VALIDATION =====
            $('#dokumenForm').on('submit', function(e) {
                e.preventDefault();

                const requiredFields = this.querySelectorAll('[data-required="true"]:not(:disabled)');
                let missingFields = [];

                requiredFields.forEach(function(field) {
                    field.classList.remove('is-invalid');
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        const label = field.closest('.form-group')?.querySelector('label')
                            ?.textContent?.replace('*', '').trim();
                        missingFields.push(label);
                    }
                });

                if (missingFields.length > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        html: '<p>Mohon lengkapi field berikut:</p><ul>' +
                            missingFields.map(f => '<li>' + f + '</li>').join('') + '</ul>',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                $('#submitBtn').html('<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...').prop(
                    'disabled', true);
                this.submit();
            });

            // ===== INITIALIZE =====
            $('.select2').select2({
                theme: 'bootstrap-5'
            });

            setTimeout(function() {
                handleJenisMasaBerlakuChange();
                handleStatusDokumenChange();
            }, 100);

            console.log('✅ Data Dokumen Form Script Loaded');
        });
    </script>
@endpush
