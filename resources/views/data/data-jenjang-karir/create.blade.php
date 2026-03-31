@extends('layouts.app')

@section('title', 'Tambah Data Jenjang Karir')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-chart-line me-2"></i>Tambah Data Jenjang Karir</span>
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

                        <form action="{{ route('data-jenjang-karir.store') }}" method="POST" id="jenjangKarirForm"
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
                                    <button class="nav-link" id="jenjang-karir-tab" data-bs-toggle="tab"
                                        data-bs-target="#jenjang-karir" type="button" role="tab"
                                        aria-controls="jenjang-karir" aria-selected="false">
                                        <i class="fas fa-chart-line me-1"></i> Data Jenjang Karir
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

                                <!-- ===== TAB 1: DATA KARYAWAN ===== -->
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
                                                        <label for="id_karyawan" class="form-label fw-bold">Karyawan <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-users"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="id_karyawan"
                                                                    name="id_karyawan" data-required="true">
                                                                    <option value="">Pilih Karyawan</option>
                                                                    @foreach ($karyawans as $karyawan)
                                                                        <option value="{{ $karyawan->id }}"
                                                                            {{ old('id_karyawan') == $karyawan->id ? 'selected' : '' }}>
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
                                                                            <i class="fas fa-user-circle fa-8x text-secondary"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Informasi Karyawan — SAMA DENGAN DATA KONTRAK -->
                                                                <div class="col-md-9">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">NIK</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_nik" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">NRK</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_nrk" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Jenis Kelamin</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_sex" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Tempat Lahir</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_tpt_lahir" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Tanggal Lahir</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_tgl_lahir" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Telepon</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_tlp1" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Status Kawin</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_sts_nikah" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Jumlah Anak</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="emp_jml_anak" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Email</label>
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

                                <!-- ===== TAB 2: DATA JENJANG KARIR (form input) ===== -->
                                <div class="tab-pane fade" id="jenjang-karir" role="tabpanel"
                                    aria-labelledby="jenjang-karir-tab">

                                    <!-- Card 1: Informasi Umum -->
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white">
                                                <i class="fas fa-info-circle me-2"></i>Informasi Umum Jenjang Karir
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- No Jenjang Karir -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="no_jk" class="form-label fw-bold">No. Jenjang
                                                            Karir</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-file-alt"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="no_jk" name="no_jk"
                                                                value="{{ old('no_jk') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Tanggal TTD -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_ttd" class="form-label fw-bold">Tanggal Terbit
                                                            <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" id="tgl_ttd"
                                                            name="tgl_ttd" value="{{ old('tgl_ttd') }}"
                                                            data-required="true">
                                                    </div>
                                                </div>

                                                <!-- Kategori Dokumen -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="ktg_dokumen" class="form-label fw-bold">Kategori
                                                            Jenjang Karir</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-folder"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="ktg_dokumen"
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
                                                                        <option value="{{ $category }}"
                                                                            {{ old('ktg_dokumen') == $category ? 'selected' : '' }}>
                                                                            {{ $category }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Jenis Dokumen -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="id_dokumen_karyawan" class="form-label fw-bold">Jenis
                                                            Jenjang Karir</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-file-signature"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2"
                                                                    id="id_dokumen_karyawan" name="id_dokumen_karyawan"
                                                                    disabled>
                                                                    <option value="">Pilih Jenis</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- File Dokumen -->
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="file_dokumen" class="form-label fw-bold">File
                                                            Dokumen</label>
                                                        <input type="file" class="form-control" id="file_dokumen"
                                                            name="file_dokumen" accept=".pdf,.doc,.docx,.jpg,.png">
                                                        <div class="form-text text-muted">Format: PDF, DOC, DOCX, JPG, PNG
                                                            (Max: 5MB)</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 2: Departemen & Jabatan -->
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white">
                                                <i class="fas fa-sitemap me-2"></i>Departemen & Jabatan
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Departemen -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="departemen_nama" class="form-label fw-bold">Departemen
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-sitemap"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="departemen_nama"
                                                                    name="departemen_nama" data-required="true">
                                                                    <option value="">Pilih Departemen</option>
                                                                    @foreach ($departemens->groupBy('nama_dep') as $namaDep => $group)
                                                                        <option value="{{ $namaDep }}"
                                                                            data-singkatan="{{ $group->first()->singkatan_dep }}"
                                                                            {{ old('departemen_nama') == $namaDep ? 'selected' : '' }}>
                                                                            {{ $namaDep }} -
                                                                            {{ $group->first()->singkatan_dep }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Singkatan Departemen -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Singkatan Dep.</label>
                                                        <input type="text" class="form-control"
                                                            id="info_singkatan_dep" readonly>
                                                    </div>
                                                </div>

                                                <!-- Jabatan -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="id_departemen" class="form-label fw-bold">Jabatan
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-user-tie"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="id_departemen"
                                                                    name="id_departemen" disabled data-required="true">
                                                                    <option value="">Pilih Jabatan</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Singkatan Jabatan -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Singkatan Jbt.</label>
                                                        <input type="text" class="form-control"
                                                            id="info_singkatan_jbt" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 3: Wilayah Kerja -->
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white">
                                                <i class="fas fa-map-marked-alt me-2"></i>Wilayah Kerja
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Wilayah Kerja -->
                                                <div class="col-md-6">
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
                                                                            {{ old('wilayah_kerja_nama') == $wilayahKrj ? 'selected' : '' }}>
                                                                            {{ $wilayahKrj }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Singkatan Wilayah Kerja -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Singkatan Wilker</label>
                                                        <input type="text" class="form-control"
                                                            id="info_skt_wilker_display" readonly
                                                            placeholder="Pilih wilayah kerja terlebih dahulu">
                                                    </div>
                                                </div>

                                                <!-- Area Kerja -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="id_wilayah_kerja" class="form-label fw-bold">Area
                                                            Kerja</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-users-cog"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="id_wilayah_kerja"
                                                                    name="id_wilayah_kerja" disabled>
                                                                    <option value="">Pilih Area Kerja</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Singkatan Area Kerja -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Singkatan Area Kerja</label>
                                                        <input type="text" class="form-control" id="info_skt_area"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 4: Tugas & Tanggung Jawab -->
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white">
                                                <i class="fas fa-tasks me-2"></i>Tugas & Tanggung Jawab
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="tugas" class="form-label fw-bold">Deskripsi
                                                            Tugas</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-tasks"></i></span>
                                                            <textarea class="form-control auto-uppercase" id="tugas" name="tugas" rows="4"
                                                                placeholder="Masukkan deskripsi tugas dan tanggung jawab...">{{ old('tugas') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- ===== TAB 3: PENDIDIKAN — SAMA DENGAN DATA KONTRAK ===== -->
                                <div class="tab-pane fade" id="pendidikan" role="tabpanel"
                                    aria-labelledby="pendidikan-tab">
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i
                                                    class="fas fa-graduation-cap me-2"></i>Pendidikan Terakhir</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info" role="alert">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Data berikut akan otomatis terisi berdasarkan karyawan yang dipilih.
                                            </div>

                                            <div class="row">
                                                <!-- Jenjang -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="pend_jenjang_skl" class="form-label fw-bold">Jenjang
                                                            Pendidikan</label>
                                                        <input type="text" class="form-control"
                                                            id="pend_jenjang_skl" readonly>
                                                    </div>
                                                </div>

                                                <!-- Tanggal Lulus -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="pend_tgl_lulus_skl" class="form-label fw-bold">Tanggal
                                                            Lulus</label>
                                                        <input type="text" class="form-control"
                                                            id="pend_tgl_lulus_skl" readonly>
                                                    </div>
                                                </div>

                                                <!-- Institusi -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="pend_institusi_skl" class="form-label fw-bold">Nama
                                                            Institusi</label>
                                                        <input type="text" class="form-control"
                                                            id="pend_institusi_skl" readonly>
                                                    </div>
                                                </div>

                                                <!-- Fakultas -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="pend_fakultas_skl"
                                                            class="form-label fw-bold">Fakultas</label>
                                                        <input type="text" class="form-control"
                                                            id="pend_fakultas_skl" readonly>
                                                    </div>
                                                </div>

                                                <!-- Jurusan -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="pend_jurusan_skl"
                                                            class="form-label fw-bold">Jurusan</label>
                                                        <input type="text" class="form-control"
                                                            id="pend_jurusan_skl" readonly>
                                                    </div>
                                                </div>

                                                <!-- Kota -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="pend_kota_skl" class="form-label fw-bold">Kota</label>
                                                        <input type="text" class="form-control"
                                                            id="pend_kota_skl" readonly>
                                                    </div>
                                                </div>

                                                <!-- Gelar -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="pend_gelar_skl" class="form-label fw-bold">Gelar</label>
                                                        <input type="text" class="form-control"
                                                            id="pend_gelar_skl" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===== TAB 4: JENJANG KARIR (readonly dari data karyawan) — SAMA DENGAN DATA KONTRAK ===== -->
                                <div class="tab-pane fade" id="karir" role="tabpanel" aria-labelledby="karir-tab">
                                    <div class="card border-warning mb-4">
                                        <div class="card-header bg-warning bg-opacity-25">
                                            <h5 class="mb-0 text-dark"><i class="fas fa-briefcase me-2"></i>Jenjang Karir
                                                Saat Ini</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info" role="alert">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Data berikut akan otomatis terisi berdasarkan karyawan yang dipilih.
                                            </div>

                                            <div class="row">
                                                <!-- Departemen -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Departemen</label>
                                                        <input type="text" class="form-control"
                                                            id="karir_departemen_nama" readonly>
                                                    </div>
                                                </div>

                                                <!-- Singkatan Dep -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Singkatan Dep.</label>
                                                        <input type="text" class="form-control"
                                                            id="karir_skt_dep" readonly>
                                                    </div>
                                                </div>

                                                <!-- Jabatan -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Jabatan</label>
                                                        <input type="text" class="form-control"
                                                            id="karir_jabatan_nama" readonly>
                                                    </div>
                                                </div>

                                                <!-- Singkatan Jbt -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Singkatan Jbt.</label>
                                                        <input type="text" class="form-control"
                                                            id="karir_skt_jbt" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Wilayah Kerja -->
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Wilayah Kerja</label>
                                                        <input type="text" class="form-control" id="karir_wilker_nama"
                                                            readonly>
                                                    </div>
                                                </div>

                                                <!-- Unit Kerja -->
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Unit Kerja</label>
                                                        <input type="text" class="form-control"
                                                            id="karir_unit_krj_nama" readonly>
                                                    </div>
                                                </div>

                                                <!-- SKT Wilker -->
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">SKT Wilayah Kerja</label>
                                                        <input type="text" class="form-control" id="karir_skt_wilker"
                                                            readonly>
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

                                <!-- ===== TAB 5: HUBUNGAN INDUSTRIAL — SAMA DENGAN DATA KONTRAK ===== -->
                                <div class="tab-pane fade" id="hubin" role="tabpanel" aria-labelledby="hubin-tab">
                                    <div class="card border-info mb-4">
                                        <div class="card-header bg-info bg-opacity-25">
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
                                <a href="{{ route('data-jenjang-karir.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                    <i class="fas fa-save me-2"></i> Simpan Data Jenjang Karir
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />

    <style>
        .card-header { font-weight: 600; }
        .form-label { margin-bottom: 0.3rem; }
        .card { margin-bottom: 1rem; transition: all 0.3s ease; }
        .card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.15); transform: translateY(-2px); }

        .employee-photo-container { position: relative; width: 100%; max-width: 200px; margin: 0 auto; }
        .employee-photo { width: 100%; height: auto; max-height: 250px; object-fit: cover; border: 3px solid #0d6efd; }
        .employee-photo.loaded { animation: photoFadeIn 0.5s ease-in-out; }
        @keyframes photoFadeIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
        .default-avatar { width: 100%; height: 200px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; border: 2px dashed #dee2e6; }

        .employee-validation-error {
            border: 2px solid #dc3545 !important; border-radius: 0.375rem !important;
            background-color: rgba(248,215,218,0.3); animation: shake 0.8s ease-in-out;
        }
        @keyframes shake { 0%,20%,40%,60%,80% { transform: translateX(0); } 10%,30%,50%,70%,90% { transform: translateX(-10px); } }

        .employee-validation-success {
            border: 2px solid #198754 !important; border-radius: 0.375rem !important;
            background-color: rgba(25,135,84,0.1); animation: successPulse 1s ease-in-out;
        }
        @keyframes successPulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(25,135,84,0.7); }
            50% { transform: scale(1.02); box-shadow: 0 0 0 10px rgba(25,135,84,0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(25,135,84,0); }
        }

        .employee-loading { position: relative; }
        .employee-loading::after {
            content: ''; position: absolute; top: 50%; right: 35px; transform: translateY(-50%);
            width: 20px; height: 20px; border: 2px solid #f3f3f3; border-top: 2px solid #0d6efd;
            border-radius: 50%; animation: spin 1s linear infinite; z-index: 10;
        }
        @keyframes spin { 0% { transform: translateY(-50%) rotate(0deg); } 100% { transform: translateY(-50%) rotate(360deg); } }

        .is-invalid { border-color: #dc3545 !important; background-color: rgba(220,53,69,0.05); }

        .select2-container--default .select2-selection--single {
            height: 38px; border: 1px solid #ced4da; border-radius: 0.375rem;
        }
        .input-group .select2-container { flex: 1 1 auto; width: 1%; min-width: 0; }
        .input-group .select2-container .select2-selection { border-top-left-radius: 0; border-bottom-left-radius: 0; border-left: 0; }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
        const allDokumenKaryawan = @json($dokumenKaryawans);

        document.addEventListener('DOMContentLoaded', function () {
            console.log('🚀 Data Jenjang Karir Form Script Loading...');

            // Auto-uppercase
            document.querySelectorAll('input.auto-uppercase, textarea.auto-uppercase').forEach(function (el) {
                if (el.type === 'text' || el.tagName.toLowerCase() === 'textarea') {
                    el.addEventListener('input', function () { this.value = this.value.toUpperCase(); });
                }
            });

            // ===== CASCADING KATEGORI → JENIS DOKUMEN =====
            $('#ktg_dokumen').on('change', function () {
                const selectedCategory = $(this).val();
                $('#id_dokumen_karyawan').val('').trigger('change').prop('disabled', true);

                if (selectedCategory) {
                    const filtered = allDokumenKaryawan
                        .filter(doc => doc.ktg_dok_kry === selectedCategory)
                        .sort((a, b) => (a.kode_dok_kry || '').localeCompare(b.kode_dok_kry || ''));

                    $('#id_dokumen_karyawan').empty().append('<option value="">Pilih Jenis</option>');
                    filtered.forEach(doc => {
                        $('#id_dokumen_karyawan').append(`<option value="${doc.id}">${doc.jns_dok_kry}</option>`);
                    });

                    $('#id_dokumen_karyawan').prop('disabled', false);
                    if ($('#id_dokumen_karyawan').hasClass('select2-hidden-accessible')) {
                        $('#id_dokumen_karyawan').select2('destroy');
                    }
                    $('#id_dokumen_karyawan').select2({ theme: 'bootstrap-5' });
                }
            });

            // ===== EMPLOYEE VALIDATION ALERTS =====
            function showSuccessValidation(response) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Validasi Berhasil!',
                        html: `
                            <div class="text-center">
                                <h5 class="text-success mb-3">${response.employee_name}</h5>
                                <p class="mb-2"><strong>NRK:</strong> ${response.employee_nrk || 'Belum ada'}</p>
                                <div class="alert alert-success mt-3">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Karyawan belum memiliki data jenjang karir.<br>
                                    <strong>Proses dapat dilanjutkan.</strong>
                                </div>
                            </div>`,
                        confirmButtonText: 'Lanjutkan',
                        confirmButtonColor: '#198754',
                        timer: 4000,
                        timerProgressBar: true,
                        showClass: { popup: 'animate__animated animate__bounceIn' }
                    });
                }
            }

            function showErrorValidation(response) {
                if (typeof Swal !== 'undefined') {
                    const latestCareer = response.latest_career;
                    Swal.fire({
                        icon: 'error',
                        title: 'Karyawan Sudah Terdaftar!',
                        html: `
                            <div class="text-start">
                                <p><strong>Nama:</strong> ${response.employee_name}</p>
                                <p><strong>NRK:</strong> ${response.employee_nrk || 'N/A'}</p>
                                <p><strong>Total Jenjang Karir:</strong> ${response.existing_careers_count} record</p>
                                <hr>
                                <p class="mb-2"><strong>Jenjang Karir Terakhir:</strong></p>
                                <ul class="list-unstyled ms-3">
                                    <li>• <strong>No. JK:</strong> ${latestCareer.no_jk}</li>
                                    <li>• <strong>Departemen:</strong> ${latestCareer.departemen}</li>
                                    <li>• <strong>Jabatan:</strong> ${latestCareer.jabatan}</li>
                                    <li>• <strong>Wilayah Kerja:</strong> ${latestCareer.wilayah_kerja}</li>
                                    <li>• <strong>Tanggal TTD:</strong> ${latestCareer.tgl_ttd}</li>
                                </ul>
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Tidak dapat membuat jenjang karir baru!</strong><br>
                                    Gunakan fitur <strong>"Tambah Jenjang Karir"</strong> pada data yang sudah ada.
                                </div>
                            </div>`,
                        confirmButtonText: 'Pilih Karyawan Lain',
                        confirmButtonColor: '#dc3545',
                        showCancelButton: true,
                        cancelButtonText: 'Lihat Data Jenjang Karir',
                        cancelButtonColor: '#0d6efd',
                        allowOutsideClick: false,
                        width: '600px',
                        showClass: { popup: 'animate__animated animate__shakeX' }
                    }).then((result) => {
                        if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                            window.location.href = `/data-jenjang-karir?search=${encodeURIComponent(response.employee_name)}`;
                        }
                    });
                }
            }

            async function checkEmployeeExists(karyawanId) {
                try {
                    $('#id_karyawan').closest('.input-group').addClass('employee-loading');

                    const response = await $.ajax({
                        url: `/data-jenjang-karir/check-employee/${karyawanId}`,
                        type: 'GET', dataType: 'json', timeout: 10000
                    });

                    $('#id_karyawan').closest('.input-group').removeClass('employee-loading');

                    if (response.success === true && response.exists === false) {
                        showSuccessValidation(response);
                        $('#id_karyawan').closest('.input-group').find('.form-select').addClass('employee-validation-success');
                        setTimeout(() => {
                            $('#id_karyawan').closest('.input-group').find('.form-select').removeClass('employee-validation-success');
                        }, 4000);
                        return true;
                    } else if (response.success === false && response.exists === true) {
                        showErrorValidation(response);
                        $('#id_karyawan').closest('.input-group').find('.form-select').addClass('employee-validation-error');
                        setTimeout(() => {
                            $('#id_karyawan').closest('.input-group').find('.form-select').removeClass('employee-validation-error');
                        }, 3000);
                        $('#id_karyawan').val('').trigger('change.select2');
                        clearAllEmployeeFields();
                        return false;
                    }
                } catch (error) {
                    $('#id_karyawan').closest('.input-group').removeClass('employee-loading');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Data Sudah Terdaftar Dalam Sistem',
                            text: 'Silahkan cari pada halaman utama dan lakukan edit untuk melakukan perubahan data',
                            confirmButtonText: 'OK'
                        });
                    }
                    $('#id_karyawan').val('').trigger('change.select2');
                    clearAllEmployeeFields();
                    return false;
                }
            }

            // ===== LOAD EMPLOYEE DATA — field sama dengan Data Kontrak =====
            async function loadEmployeeData(karyawanId) {
                $('#employeeInfo').show();

                const loadingText = 'Loading...';
                $('#emp_nik, #emp_nrk, #emp_sex, #emp_tpt_lahir, #emp_tgl_lahir, #emp_tlp1, #emp_sts_nikah, #emp_jml_anak, #emp_email').val(loadingText);
                $('#hubin_tgl_masuk, #hubin_sts_kry, #hubin_tgl_phk, #hubin_ket_phk').val(loadingText);

                try {
                    const response = await $.ajax({
                        url: `/data-jenjang-karir/get-employee-data/${karyawanId}`,
                        type: 'GET', dataType: 'json', timeout: 15000
                    });

                    if (response.success && response.data) {
                        const emp = response.data;

                        // ── Tab 1: Informasi Karyawan ──
                        $('#emp_nik').val(emp.nik || '-');
                        $('#emp_nrk').val(emp.nrk || '-');
                        $('#emp_sex').val(emp.sex || '-');
                        $('#emp_tpt_lahir').val(emp.tpt_lahir || '-');
                        $('#emp_tgl_lahir').val(emp.tgl_lahir_formatted || '-');
                        $('#emp_tlp1').val(emp.tlp1 || '-');
                        $('#emp_sts_nikah').val(emp.sts_nikah || '-');
                        $('#emp_jml_anak').val(emp.jml_anak || '-');
                        $('#emp_email').val(emp.email1 || '-');

                        // Foto
                        if (emp.foto_dokumen) {
                            $('#emp_foto').attr('src', `/storage/${emp.foto_dokumen}`).show().addClass('loaded');
                            $('#emp_foto_placeholder').hide();
                        } else {
                            $('#emp_foto').hide().removeClass('loaded');
                            $('#emp_foto_placeholder').show();
                        }
                        $('#emp_foto').off('error').on('error', function () {
                            $(this).hide().removeClass('loaded');
                            $('#emp_foto_placeholder').show();
                        });

                        // ── Tab 3: Pendidikan ──
                        $('#pend_jenjang_skl').val(emp.jenjang_skl || '-');
                        $('#pend_tgl_lulus_skl').val(emp.tgl_lulus_skl_formatted || '-');
                        $('#pend_institusi_skl').val(emp.institusi_skl || '-');
                        $('#pend_fakultas_skl').val(emp.fakultas_skl || '-');
                        $('#pend_jurusan_skl').val(emp.jurusan_skl || '-');
                        $('#pend_kota_skl').val(emp.kota_skl || '-');
                        $('#pend_gelar_skl').val(emp.gelar_skl || '-');

                        // ── Tab 4: Jenjang Karir (posisi saat ini) ──
                        $('#karir_departemen_nama').val(emp.departemen || '-');
                        $('#karir_skt_dep').val(emp.skt_dep || '-');
                        $('#karir_jabatan_nama').val(emp.jabatan || '-');
                        $('#karir_skt_jbt').val(emp.skt_jbt || '-');
                        $('#karir_wilker_nama').val(emp.wilker || '-');
                        $('#karir_unit_krj_nama').val(emp.unit_krj || '-');
                        $('#karir_skt_wilker').val(emp.skt_wil_krj || '-');
                        $('#karir_tugas').val(emp.tugas || '-');

                        // ── Tab 5: Hubungan Industrial ──
                        $('#hubin_tgl_masuk').val(emp.tgl_masuk_formatted || '-');
                        $('#hubin_sts_kry').val(emp.sts_kry || '-');
                        $('#hubin_tgl_phk').val(emp.tgl_phk_formatted || '-');
                        $('#hubin_ket_phk').val(emp.ket_phk || '-');
                    }
                } catch (error) {
                    console.error('❌ Employee data loading error:', error);
                    $('#emp_nik, #emp_nrk, #emp_sex, #emp_tpt_lahir, #emp_tgl_lahir, #emp_tlp1, #emp_sts_nikah, #emp_jml_anak, #emp_email').val('Error');
                    $('#hubin_tgl_masuk, #hubin_sts_kry, #hubin_tgl_phk, #hubin_ket_phk').val('Error');
                }
            }

            function clearAllEmployeeFields() {
                $('#employeeInfo').hide();

                // Tab 1
                $('#emp_nik, #emp_nrk, #emp_sex, #emp_tpt_lahir, #emp_tgl_lahir, #emp_tlp1, #emp_sts_nikah, #emp_jml_anak, #emp_email').val('');
                $('#emp_foto').hide().attr('src', '').removeClass('loaded');
                $('#emp_foto_placeholder').show();

                // Tab 3
                $('#pend_jenjang_skl, #pend_tgl_lulus_skl, #pend_institusi_skl, #pend_fakultas_skl, #pend_jurusan_skl, #pend_kota_skl, #pend_gelar_skl').val('');

                // Tab 4
                $('#karir_departemen_nama, #karir_skt_dep, #karir_jabatan_nama, #karir_skt_jbt, #karir_wilker_nama, #karir_unit_krj_nama, #karir_skt_wilker, #karir_tugas').val('');

                // Tab 5
                $('#hubin_tgl_masuk, #hubin_sts_kry, #hubin_tgl_phk, #hubin_ket_phk').val('');
            }

            // ===== EMPLOYEE SELECTION =====
            $('#id_karyawan').on('change', function () {
                const karyawanId = $(this).val();
                if (karyawanId) {
                    checkEmployeeExists(karyawanId).then(canProceed => {
                        if (canProceed) {
                            setTimeout(() => loadEmployeeData(karyawanId), 1000);
                        }
                    });
                } else {
                    clearAllEmployeeFields();
                }
            });

            // ===== DEPARTEMEN → JABATAN =====
            $('#departemen_nama').on('change', function () {
                const namaDep = $(this).val();
                const jabatanSelect = $('#id_departemen');

                if (namaDep) {
                    const singkatanDep = $(this).find('option:selected').data('singkatan') || '';
                    $('#info_singkatan_dep').val(singkatanDep);

                    jabatanSelect.prop('disabled', true).html('<option value="">Loading...</option>');

                    $.ajax({
                        url: `/data-jenjang-karir/get-jabatan-by-departemen/${encodeURIComponent(namaDep)}`,
                        type: 'GET', dataType: 'json',
                        success: function (response) {
                            if (response.success && response.data) {
                                jabatanSelect.html('<option value="">Pilih Jabatan</option>');
                                response.data.forEach(function (jabatan) {
                                    jabatanSelect.append(`<option value="${jabatan.id}" data-singkatan-jbt="${jabatan.singkatan_jbt || ''}">${jabatan.nama_jbt}</option>`);
                                });
                                jabatanSelect.prop('disabled', false);
                                if (jabatanSelect.hasClass('select2-hidden-accessible')) jabatanSelect.select2('destroy');
                                jabatanSelect.select2({ theme: 'bootstrap-5' });
                            }
                        },
                        error: function () {
                            jabatanSelect.html('<option value="">Error loading</option>').prop('disabled', false);
                        }
                    });
                } else {
                    jabatanSelect.html('<option value="">Pilih Jabatan</option>').prop('disabled', true);
                    $('#info_singkatan_dep, #info_singkatan_jbt').val('');
                }
            });

            $('#id_departemen').on('change', function () {
                $('#info_singkatan_jbt').val($(this).find('option:selected').data('singkatan-jbt') || '');
            });

            // ===== WILAYAH KERJA → AREA KERJA =====
            $('#wilayah_kerja_nama').on('change', function () {
                const wilayahKrj = $(this).val();
                const areaSelect = $('#id_wilayah_kerja');
                $('#info_skt_wilker_display, #info_skt_area').val('');

                if (wilayahKrj) {
                    areaSelect.prop('disabled', true).html('<option value="">Loading...</option>');

                    $.ajax({
                        url: `/data-jenjang-karir/get-unit-kerja-by-wilayah/${encodeURIComponent(wilayahKrj)}`,
                        type: 'GET', dataType: 'json',
                        success: function (response) {
                            if (response.success && response.data && response.data.length > 0) {
                                areaSelect.html('<option value="">Pilih Area Kerja</option>');
                                response.data.forEach(function (area) {
                                    areaSelect.append(`<option value="${area.id}" data-singkatan-wk="${area.singkatan_wk || ''}">${area.area_krj}</option>`);
                                });
                                areaSelect.prop('disabled', false);
                                if (areaSelect.hasClass('select2-hidden-accessible')) areaSelect.select2('destroy');
                                areaSelect.select2({ theme: 'bootstrap-5' });
                                $('#info_skt_wilker_display').val(response.data[0].skt_wilker || '');
                            }
                        },
                        error: function () {
                            areaSelect.html('<option value="">Error loading</option>').prop('disabled', false);
                        }
                    });
                } else {
                    areaSelect.html('<option value="">Pilih Area Kerja</option>').prop('disabled', true);
                }
            });

            $('#id_wilayah_kerja').on('change', function () {
                $('#info_skt_area').val($(this).find('option:selected').data('singkatan-wk') || '');
            });

            // ===== FORM VALIDATION =====
            document.getElementById('jenjangKarirForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const requiredFields = this.querySelectorAll('[data-required="true"]:not(:disabled)');
                let missingFields = [];
                let firstInvalidField = null;

                this.querySelectorAll('[data-required="true"]').forEach(f => f.classList.remove('is-invalid'));

                requiredFields.forEach(function (field) {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        const label = field.closest('.form-group')?.querySelector('label')?.textContent?.replace('*', '').trim() || field.name;
                        missingFields.push(label);
                        if (!firstInvalidField) firstInvalidField = field;
                    }
                });

                if (missingFields.length > 0) {
                    let fieldsList = '<ul class="text-start mb-0">';
                    missingFields.forEach(f => fieldsList += '<li>' + f + '</li>');
                    fieldsList += '</ul>';

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Belum Lengkap',
                            html: '<p class="mb-2">Mohon lengkapi field berikut yang wajib diisi:</p>' + fieldsList,
                            confirmButtonText: 'OK, Saya Mengerti',
                            confirmButtonColor: '#0d6efd',
                        });
                    }

                    if (firstInvalidField) {
                        const tabPane = firstInvalidField.closest('.tab-pane');
                        if (tabPane) {
                            const tabButton = document.querySelector(`[data-bs-target="#${tabPane.id}"]`);
                            if (tabButton) {
                                new bootstrap.Tab(tabButton).show();
                                setTimeout(() => { firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' }); firstInvalidField.focus(); }, 300);
                            }
                        }
                    }
                    return false;
                } else {
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
                    submitBtn.disabled = true;
                    this.submit();
                }
            });

            // Initialize Select2
            $('.select2').select2({ theme: 'bootstrap-5' });
            console.log('✅ Data Jenjang Karir Form Script Loaded Successfully');
        });
    </script>
@endpush