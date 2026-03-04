@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-user-plus me-2"></i>Tambah Karyawan</span>
                        <a href="{{ route('data-karyawan.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('data-karyawan.store') }}" method="POST" id="karyawanForm"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            <input type="hidden" class="form-control" id="id_kode" name="id_kode"
                                value="{{ old('id_kode', $newId) }}" readonly>

                            <!-- Nav tabs for form sections -->
                            <ul class="nav nav-tabs mb-4" id="formTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="biodata-tab" data-bs-toggle="tab"
                                        data-bs-target="#biodata" type="button" role="tab" aria-controls="biodata"
                                        aria-selected="true">
                                        <i class="fas fa-id-card me-1"></i> Data Pribadi
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="alamat-tab" data-bs-toggle="tab" data-bs-target="#alamat"
                                        type="button" role="tab" aria-controls="alamat" aria-selected="false">
                                        <i class="fas fa-home me-1"></i> Alamat
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
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="kontak-darurat-tab" data-bs-toggle="tab"
                                        data-bs-target="#kontak-darurat" type="button" role="tab"
                                        aria-controls="kontak-darurat" aria-selected="false">
                                        <i class="fas fa-phone-square-alt me-1"></i> Kontak Darurat
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- Data Pribadi Only -->
                                <div class="tab-pane fade show active" id="biodata" role="tabpanel"
                                    aria-labelledby="biodata-tab">
                                    <!-- Data Pribadi -->
                                    <div class="card border-secondary mb-4">
                                        <div class="card-header bg-secondary bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i class="fas fa-id-card me-2"></i>Data Pribadi
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Foto Karyawan - Kiri -->
                                                <div class="col-md-3 text-center mb-4">
                                                    <div class="employee-photo-container">
                                                        <div class="photo-upload-box">
                                                            <img id="photo_preview" src="" alt="Preview Foto"
                                                                class="img-fluid rounded shadow employee-photo"
                                                                style="display: none;">
                                                            <div id="photo_placeholder"
                                                                class="default-avatar rounded shadow">
                                                                <i class="fas fa-user-circle fa-8x text-secondary"></i>
                                                                <p class="mt-3 mb-0 text-muted">
                                                                    <small>Unggah Foto</small>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-3 mt-3">
                                                            <label for="foto_dokumen" class="form-label fw-bold">
                                                                <i class="fas fa-camera me-1"></i>Foto Karyawan
                                                            </label>
                                                            <input type="file" class="form-control" id="foto_dokumen"
                                                                name="foto_dokumen"
                                                                accept="image/jpeg,image/png,image/jpg">
                                                            <div class="form-text text-muted">
                                                                <i class="fas fa-info-circle me-1"></i>JPG, PNG (Max 2MB)
                                                            </div>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger mt-2 w-100"
                                                                id="removePhotoBtn" style="display: none;">
                                                                <i class="fas fa-trash me-1"></i>Hapus Foto
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Form Data Pribadi - Kanan -->
                                                <div class="col-md-9">
                                                    <div class="row">
                                                        {{-- NIK --}}
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label for="nik" class="form-label fw-bold">NIK KTP
                                                                    <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-id-card-alt"></i></span>
                                                                    <input type="text"
                                                                        class="form-control no-uppercase" id="nik"
                                                                        name="nik" value="{{ old('nik') }}"
                                                                        minlength="16" maxlength="16"
                                                                        data-required="true">
                                                                </div>
                                                                <div class="form-text text-muted">16 digit angka NIK KTP
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- Nama Lengkap --}}
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label for="nama" class="form-label fw-bold">Nama
                                                                    Lengkap <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-user"></i></span>
                                                                    <input type="text"
                                                                        class="form-control auto-uppercase" id="nama"
                                                                        name="nama" value="{{ old('nama') }}"
                                                                        data-required="true">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- Tempat Lahir --}}
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label for="tpt_lahir" class="form-label fw-bold">Tempat
                                                                    Lahir <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-map-marker-alt"></i></span>
                                                                    <input type="text"
                                                                        class="form-control auto-uppercase" id="tpt_lahir"
                                                                        name="tpt_lahir" value="{{ old('tpt_lahir') }}"
                                                                        data-required="true">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- Tgl Lahir --}}
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label for="tgl_lahir" class="form-label fw-bold">
                                                                    <i class="fas fa-calendar-alt me-1"></i>Tanggal Lahir
                                                                    <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="date" class="form-control" id="tgl_lahir"
                                                                    name="tgl_lahir" value="{{ old('tgl_lahir') }}"
                                                                    data-required="true">
                                                                <div id="usiaInfo" class="form-text text-muted mt-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- Sex --}}
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label for="sex" class="form-label fw-bold">Jenis
                                                                    Kelamin <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-venus-mars"></i></span>
                                                                    <div style="flex: 1">
                                                                        <select class="form-select select2" id="sex"
                                                                            name="sex" data-required="true">
                                                                            <option value="">Pilih Jenis Kelamin
                                                                            </option>
                                                                            <option value="LAKI-LAKI"
                                                                                {{ old('sex') == 'LAKI-LAKI' ? 'selected' : '' }}>
                                                                                LAKI-LAKI</option>
                                                                            <option value="PEREMPUAN"
                                                                                {{ old('sex') == 'PEREMPUAN' ? 'selected' : '' }}>
                                                                                PEREMPUAN</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- Agama --}}
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label for="agama" class="form-label fw-bold">Agama
                                                                    <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-pray"></i></span>
                                                                    <div style="flex: 1">
                                                                        <select class="form-select select2" id="agama"
                                                                            name="agama" data-required="true">
                                                                            <option value="">Pilih Agama</option>
                                                                            <option value="ISLAM"
                                                                                {{ old('agama') == 'ISLAM' ? 'selected' : '' }}>
                                                                                ISLAM</option>
                                                                            <option value="KRISTEN"
                                                                                {{ old('agama') == 'KRISTEN' ? 'selected' : '' }}>
                                                                                KRISTEN</option>
                                                                            <option value="KATOLIK"
                                                                                {{ old('agama') == 'KATOLIK' ? 'selected' : '' }}>
                                                                                KATOLIK</option>
                                                                            <option value="HINDU"
                                                                                {{ old('agama') == 'HINDU' ? 'selected' : '' }}>
                                                                                HINDU</option>
                                                                            <option value="BUDDHA"
                                                                                {{ old('agama') == 'BUDDHA' ? 'selected' : '' }}>
                                                                                BUDDHA</option>
                                                                            <option value="KONGHUCU"
                                                                                {{ old('agama') == 'KONGHUCU' ? 'selected' : '' }}>
                                                                                KONGHUCU</option>
                                                                            <option value="LAINNYA"
                                                                                {{ old('agama') == 'LAINNYA' ? 'selected' : '' }}>
                                                                                LAINNYA</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- KWN --}}
                                                        <div class="col-md-12">
                                                            <div class="form-group mb-3">
                                                                <label for="kewarganegaraan"
                                                                    class="form-label fw-bold">Kewarganegaraan</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-flag"></i></span>
                                                                    <input type="text"
                                                                        class="form-control auto-uppercase"
                                                                        id="kewarganegaraan" name="kewarganegaraan"
                                                                        value="{{ old('kewarganegaraan', 'INDONESIA') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border-secondary mb-4">
                                        <div class="card-header bg-secondary bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i class="fas fa-id-card me-2"></i>Status Keluarga
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="sts_nikah" class="form-label fw-bold">Status
                                                            Pernikahan <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-heart"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="sts_nikah"
                                                                    name="sts_nikah" data-required="true">
                                                                    <option value="">Pilih Status</option>
                                                                    <option value="BELUM KAWIN"
                                                                        {{ old('sts_nikah') == 'BELUM KAWIN' ? 'selected' : '' }}>
                                                                        BELUM KAWIN</option>
                                                                    <option value="KAWIN"
                                                                        {{ old('sts_nikah') == 'KAWIN' ? 'selected' : '' }}>
                                                                        KAWIN</option>
                                                                    <option value="CERAI HIDUP"
                                                                        {{ old('sts_nikah') == 'CERAI HIDUP' ? 'selected' : '' }}>
                                                                        CERAI HIDUP</option>
                                                                    <option value="CERAI MATI"
                                                                        {{ old('sts_nikah') == 'CERAI MATI' ? 'selected' : '' }}>
                                                                        CERAI MATI</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="sts_keluarga" class="form-label fw-bold">Status dalam
                                                            Keluarga</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-user-friends"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="sts_keluarga"
                                                                    name="sts_keluarga">
                                                                    <option value="">Pilih Status</option>
                                                                    <option value="SUAMI"
                                                                        {{ old('sts_keluarga') == 'SUAMI' ? 'selected' : '' }}>
                                                                        SUAMI</option>
                                                                    <option value="ISTRI"
                                                                        {{ old('sts_keluarga') == 'ISTRI' ? 'selected' : '' }}>
                                                                        ISTRI</option>
                                                                    <option value="BAPAK"
                                                                        {{ old('sts_keluarga') == 'BAPAK' ? 'selected' : '' }}>
                                                                        BAPAK</option>
                                                                    <option value="IBU"
                                                                        {{ old('sts_keluarga') == 'IBU' ? 'selected' : '' }}>
                                                                        IBU</option>
                                                                    <option value="ANAK"
                                                                        {{ old('sts_keluarga') == 'ANAK' ? 'selected' : '' }}>
                                                                        ANAK</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="jml_anak" class="form-label fw-bold">Jumlah
                                                            Anak</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-baby"></i></span>
                                                            <input type="number" class="form-control" id="jml_anak"
                                                                name="jml_anak" value="{{ old('jml_anak', 0) }}"
                                                                min="0" max="20">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border-secondary mb-4">
                                        <div class="card-header bg-secondary bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i class="fas fa-id-card me-2"></i>Informasi
                                                Kontak
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tlp1" class="form-label fw-bold">No. Telepon Utama
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-phone"></i></span>
                                                            <input type="text" class="form-control no-uppercase"
                                                                id="tlp1" name="tlp1"
                                                                value="{{ old('tlp1') }}" data-required="true">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tlp2" class="form-label fw-bold">No. Telepon
                                                            Alternatif</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-mobile-alt"></i></span>
                                                            <input type="text" class="form-control no-uppercase"
                                                                id="tlp2" name="tlp2"
                                                                value="{{ old('tlp2') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="email1" class="form-label fw-bold">Email
                                                            Utama</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-envelope"></i></span>
                                                            <input type="email" class="form-control no-uppercase"
                                                                id="email1" name="email1"
                                                                value="{{ old('email1') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="email2" class="form-label fw-bold">Email
                                                            Alternatif</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-envelope"></i></span>
                                                            <input type="email" class="form-control no-uppercase"
                                                                id="email2" name="email2"
                                                                value="{{ old('email2') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="instagram"
                                                            class="form-label fw-bold">Instagram</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fab fa-instagram"></i></span>
                                                            <input type="text" class="form-control no-uppercase"
                                                                id="instagram" name="instagram"
                                                                value="{{ old('instagram') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="facebook" class="form-label fw-bold">Facebook</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fab fa-facebook"></i></span>
                                                            <input type="text" class="form-control no-uppercase"
                                                                id="facebook" name="facebook"
                                                                value="{{ old('facebook') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- Tab Alamat (KTP dan Domisili) -->
                                <div class="tab-pane fade" id="alamat" role="tabpanel" aria-labelledby="alamat-tab">
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i class="fas fa-id-card me-2"></i>Alamat KTP</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                {{-- Provinsi KTP --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="prov_ktp" class="form-label fw-bold">Provinsi <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-globe-asia"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="prov_ktp"
                                                                    name="prov_ktp" data-required="true">
                                                                    <option value="">Pilih Provinsi</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kelurahan/Desa KTP --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kel_ktp"
                                                            class="form-label fw-bold">Kelurahan/Desa</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-building"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="kel_ktp"
                                                                    name="kel_ktp" disabled>
                                                                    <option value="">Pilih Kelurahan/Desa</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- ======== --}}

                                                {{-- Kota/Kabupaten KTP --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kota_ktp" class="form-label fw-bold">Kota/Kabupaten
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="kota_ktp"
                                                                    name="kota_ktp" data-required="true" disabled>
                                                                    <option value="">Pilih Kota/Kabupaten</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- RT/RW KTP --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="rt_rw_ktp" class="form-label fw-bold">RT/RW</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-home"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="rt_rw_ktp" name="rt_rw_ktp"
                                                                value="{{ old('rt_rw_ktp') }}" placeholder="001/002">
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- ======== --}}

                                                {{-- Kecamatan KTP --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kec_ktp" class="form-label fw-bold">Kecamatan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-city"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="kec_ktp"
                                                                    name="kec_ktp" disabled>
                                                                    <option value="">Pilih Kecamatan</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kode Pos KTP --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kd_pos_ktp" class="form-label fw-bold">Kode
                                                            Pos</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-mail-bulk"></i></span>
                                                            <input type="text" class="form-control no-uppercase"
                                                                id="kd_pos_ktp" name="kd_pos_ktp"
                                                                value="{{ old('kd_pos_ktp') }}" maxlength="5"
                                                                pattern="[0-9]{5}">
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="row">
                                                {{-- Alamat Lengkap KTP --}}
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="alamat_ktp" class="form-label fw-bold">Alamat Lengkap
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-marked-alt"></i></span>
                                                            <textarea class="form-control auto-uppercase" id="alamat_ktp" name="alamat_ktp" rows="3"
                                                                data-required="true">{{ old('alamat_ktp') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border-primary mb-4">
                                        <div
                                            class="card-header bg-primary bg-opacity-25 d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 text-white"><i class="fas fa-house-user me-2"></i>Alamat
                                                Domisili
                                            </h5>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="samaWithKtp">
                                                <label class="form-check-label" for="samaWithKtp">
                                                    <small class="text-white">Sama dengan alamat KTP</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                {{-- Provinsi Domisili --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="prov_dom" class="form-label fw-bold">Provinsi <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-globe-asia"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="prov_dom"
                                                                    name="prov_dom" data-required="true">
                                                                    <option value="">Pilih Provinsi</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kelurahan/Desa Domisili --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kel_dom"
                                                            class="form-label fw-bold">Kelurahan/Desa</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-building"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="kel_dom"
                                                                    name="kel_dom" disabled>
                                                                    <option value="">Pilih Kelurahan/Desa</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- ======== --}}

                                                {{-- Kota/Kabupaten Domisili --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kota_dom" class="form-label fw-bold">Kota/Kabupaten
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="kota_dom"
                                                                    name="kota_dom" data-required="true" disabled>
                                                                    <option value="">Pilih Kota/Kabupaten</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- RT/RW Domisili --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="rt_rw_dom" class="form-label fw-bold">RT/RW</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-home"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="rt_rw_dom" name="rt_rw_dom"
                                                                value="{{ old('rt_rw_dom') }}" placeholder="001/002">
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- ======== --}}

                                                {{-- Kecamatan Domisili --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kec_dom" class="form-label fw-bold">Kecamatan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-city"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="kec_dom"
                                                                    name="kec_dom" disabled>
                                                                    <option value="">Pilih Kecamatan</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kode Pos Domisili --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kd_pos_dom" class="form-label fw-bold">Kode
                                                            Pos</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-mail-bulk"></i></span>
                                                            <input type="text" class="form-control no-uppercase"
                                                                id="kd_pos_dom" name="kd_pos_dom"
                                                                value="{{ old('kd_pos_dom') }}" maxlength="5"
                                                                pattern="[0-9]{5}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                {{-- Alamat Lengkap Domisili --}}
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="alamat_dom" class="form-label fw-bold">Alamat Lengkap
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-marked-alt"></i></span>
                                                            <textarea class="form-control auto-uppercase" id="alamat_dom" name="alamat_dom" rows="3"
                                                                data-required="true">{{ old('alamat_dom') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pendidikan -->
                                <div class="tab-pane fade" id="pendidikan" role="tabpanel"
                                    aria-labelledby="pendidikan-tab">
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i
                                                    class="fas fa-graduation-cap me-2"></i>Pendidikan
                                                Terakhir</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
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
                                                                        {{ old('jenjang_skl') == '-' ? 'selected' : '' }}>
                                                                        -
                                                                    </option>
                                                                    <option value="SD"
                                                                        {{ old('jenjang_skl') == 'SD' ? 'selected' : '' }}>
                                                                        SD
                                                                    </option>
                                                                    <option value="SMP"
                                                                        {{ old('jenjang_skl') == 'SMP' ? 'selected' : '' }}>
                                                                        SMP
                                                                    </option>
                                                                    <option value="SMA"
                                                                        {{ old('jenjang_skl') == 'SMA' ? 'selected' : '' }}>
                                                                        SMA</option>
                                                                    <option value="SMK"
                                                                        {{ old('jenjang_skl') == 'SMK' ? 'selected' : '' }}>
                                                                        SMK</option>
                                                                    <option value="D1"
                                                                        {{ old('jenjang_skl') == 'D1' ? 'selected' : '' }}>
                                                                        D1
                                                                    </option>
                                                                    <option value="D2"
                                                                        {{ old('jenjang_skl') == 'D2' ? 'selected' : '' }}>
                                                                        D2
                                                                    </option>
                                                                    <option value="D3"
                                                                        {{ old('jenjang_skl') == 'D3' ? 'selected' : '' }}>
                                                                        D3
                                                                    </option>
                                                                    <option value="D4"
                                                                        {{ old('jenjang_skl') == 'D4' ? 'selected' : '' }}>
                                                                        D4</option>
                                                                    <option value="S1"
                                                                        {{ old('jenjang_skl') == 'S1' ? 'selected' : '' }}>
                                                                        S1</option>
                                                                    <option value="S2"
                                                                        {{ old('jenjang_skl') == 'S2' ? 'selected' : '' }}>
                                                                        S2
                                                                    </option>
                                                                    <option value="S3"
                                                                        {{ old('jenjang_skl') == 'S3' ? 'selected' : '' }}>
                                                                        S3
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                {{-- Institusi --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="institusi_skl" class="form-label fw-bold">Nama
                                                            Institusi</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-university"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="institusi_skl" name="institusi_skl"
                                                                value="{{ old('institusi_skl') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kota --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kota_skl" class="form-label fw-bold">Kota</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-marker-alt"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="kota_skl" name="kota_skl"
                                                                value="{{ old('kota_skl') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- SKT --}}
                                                {{-- <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="skt_inst_skl" class="form-label fw-bold">SKT</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-building-columns"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="skt_inst_skl" name="skt_inst_skl"
                                                                value="{{ old('skt_inst_skl') }}">
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                {{-- Fakultas --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="fakultas_skl"
                                                            class="form-label fw-bold">Fakultas</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-building-columns"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="fakultas_skl" name="fakultas_skl"
                                                                value="{{ old('fakultas_skl') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Gelar --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="gelar_skl" class="form-label fw-bold">Gelar</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-medal"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="gelar_skl" name="gelar_skl"
                                                                value="{{ old('gelar_skl') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Jurusan --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="jurusan_skl"
                                                            class="form-label fw-bold">Jurusan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-book-open"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="jurusan_skl" name="jurusan_skl"
                                                                value="{{ old('jurusan_skl') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Tgl Lulus --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_lulus_skl" class="form-label fw-bold">
                                                            <i class="fas fa-calendar-check me-1"></i>Tanggal Lulus
                                                        </label>
                                                        <input type="date" class="form-control" id="tgl_lulus_skl"
                                                            name="tgl_lulus_skl" value="{{ old('tgl_lulus_skl') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kontrak Kerja -->
                                <div class="tab-pane fade" id="kontrak" role="tabpanel" aria-labelledby="kontrak-tab">
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i class="fas fa-file-contract me-2"></i>Informasi
                                                Kontrak
                                                Kerja</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                {{-- Status KTR --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="sts_ktr" class="form-label fw-bold">Status
                                                            Kontrak</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-clipboard-check"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="sts_ktr"
                                                                    name="sts_ktr">
                                                                    <option value="">Pilih Kontrak</option>
                                                                    @foreach ($kontraks as $ktr)
                                                                        <option value="{{ $ktr->id }}"
                                                                            {{ old('singkatan_ktr') == $ktr->id ? 'selected' : '' }}>
                                                                            {{ $ktr->nama_ktr }} -
                                                                            {{ $ktr->singkatan_ktr }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- SKT KTR --}}
                                                {{-- <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="skt_sts_ktr" class="form-label fw-bold">SKT Kontrak</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-marker-alt"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="skt_sts_ktr" name="skt_sts_ktr"
                                                                value="{{ old('skt_sts_ktr') }}">
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                {{-- Perusahaan --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="perusahaan"
                                                            class="form-label fw-bold">Perusahaan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-building"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="perusahaan"
                                                                    name="perusahaan">
                                                                    <option value="">Pilih Perusahaan</option>
                                                                    @foreach ($perusahaans as $perusahaan)
                                                                        <option value="{{ $perusahaan->id }}"
                                                                            {{ old('perusahaan') == $perusahaan->id ? 'selected' : '' }}>
                                                                            {{ $perusahaan->nama_prs1 }} -
                                                                            {{ $perusahaan->nama_prs2 }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- SKT Prs --}}
                                                {{-- <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="skt_prs" class="form-label fw-bold">SKT Perusahaan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-marker-alt"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="skt_prs" name="skt_prs"
                                                                value="{{ old('skt_prs') }}">
                                                        </div>
                                                    </div>
                                                </div> --}}
                                            </div>

                                            <div class="row">
                                                {{-- Tgl Mulai KTR --}}
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_awal_ktr" class="form-label fw-bold">
                                                            <i class="fas fa-calendar-plus me-1"></i>Tanggal Mulai Kontrak
                                                        </label>
                                                        <input type="date" class="form-control" id="tgl_awal_ktr"
                                                            name="tgl_awal_ktr" value="{{ old('tgl_awal_ktr') }}">
                                                    </div>
                                                </div>
                                                {{-- Tgl Akhir KTR --}}
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_akhir_ktr" class="form-label fw-bold">
                                                            <i class="fas fa-calendar-minus me-1"></i>Tanggal Akhir Kontrak
                                                        </label>
                                                        <input type="date" class="form-control" id="tgl_akhir_ktr"
                                                            name="tgl_akhir_ktr" value="{{ old('tgl_akhir_ktr') }}">
                                                    </div>
                                                </div>
                                                {{-- Durasi KTR --}}
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="durasi_ktr" class="form-label fw-bold">Durasi Kontrak
                                                            (Bulan)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-clock"></i></span>
                                                            <input type="number" class="form-control" id="durasi_ktr"
                                                                name="durasi_ktr" value="{{ old('durasi_ktr') }}"
                                                                min="1" max="60" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Jenjang Karir -->
                                <div class="tab-pane fade" id="karir" role="tabpanel" aria-labelledby="karir-tab">
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i class="fas fa-briefcase me-2"></i>Informasi
                                                Jenjang
                                                Karir</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                {{-- departemen --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="departemen"
                                                            class="form-label fw-bold">Departemen</label>

                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-sitemap"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="departemen"
                                                                    name="departemen">
                                                                    <option value="">Pilih Departemen</option>
                                                                    @foreach ($departemens as $departemen)
                                                                        <option value="{{ $departemen->nama_dep }}"
                                                                            {{ old('departemen') == $departemen->nama_dep ? 'selected' : '' }}>
                                                                            {{ $departemen->nama_dep }} -
                                                                            {{ $departemen->singkatan_dep }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- jabatan --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="jabatan" class="form-label fw-bold">Jabatan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-user-tie"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="jabatan"
                                                                    name="jabatan" disabled>
                                                                    <option value="">Pilih Jabatan</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- SKT DEP --}}
                                                {{-- <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="skt_dep" class="form-label fw-bold">SKT
                                                            Departemen</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-marker-alt"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="skt_dep" name="skt_dep"
                                                                value="{{ old('skt_dep') }}">
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                {{-- SKT Jabatan --}}
                                                {{-- <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="skt_jbt" class="form-label fw-bold">SKT
                                                            Jabatan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-marker-alt"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="skt_jbt" name="skt_jbt"
                                                                value="{{ old('skt_jbt') }}">
                                                        </div>
                                                    </div>
                                                </div> --}}
                                            </div>

                                            <div class="row">
                                                {{-- wilker --}}
                                                {{-- wilker --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="wilker" class="form-label fw-bold">Wilayah
                                                            Kerja</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="wilker"
                                                                    name="wilker">
                                                                    <option value="">Pilih Wilayah Kerja</option>
                                                                    @foreach ($wilayahKerjas as $wilayah)
                                                                        <option value="{{ $wilayah->wilayah_krj }}"
                                                                            {{ old('wilker') == $wilayah->wilayah_krj ? 'selected' : '' }}>
                                                                            {{ $wilayah->wilayah_krj }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- unit krj --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="unit_krj" class="form-label fw-bold">Area
                                                            Kerja</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-users-cog"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="unit_krj"
                                                                    name="unit_krj" disabled>
                                                                    <option value="">Pilih Unit Kerja</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="text" class="form-control auto-uppercase"
                                                    id="skt_wil_krj" name="skt_wil_krj" value="{{ old('skt_wil_krj') }}"
                                                    hidden>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="tugas" class="form-label fw-bold">Tugas &
                                                                Tanggung
                                                                Jawab</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-tasks"></i></span>
                                                                <textarea class="form-control auto-uppercase" id="tugas" name="tugas" rows="4">{{ old('tugas') }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hubungan Industrial -->
                                <div class="tab-pane fade" id="hubin" role="tabpanel" aria-labelledby="hubin-tab">
                                    <!-- Status Karyawan -->
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i class="fas fa-user-check me-2"></i>Status
                                                Hubungan
                                                Industrial</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                {{-- NRK --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="nrk" class="form-label fw-bold">NRK</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-id-badge"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="nrk" name="nrk"
                                                                value="{{ old('nrk') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Tanggal Masuk --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_masuk" class="form-label fw-bold">
                                                            <i class="fas fa-calendar-plus me-1"></i>Tanggal Masuk
                                                        </label>
                                                        <input type="date" class="form-control" id="tgl_masuk"
                                                            name="tgl_masuk" value="{{ old('tgl_masuk') }}">
                                                    </div>
                                                </div>
                                                {{-- Status Karyawan --}}
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="sts_kry" class="form-label fw-bold">Status
                                                            Karyawan
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-user-check"></i></span>
                                                            <select class="form-select" id="sts_kry" name="sts_kry"
                                                                data-required="true">
                                                                <option value="">Pilih Status</option>
                                                                <option value="CALON"
                                                                    {{ old('sts_kry') == 'CALON' ? 'selected' : '' }}>
                                                                    CALON
                                                                </option>
                                                                <option value="AKTIF"
                                                                    {{ old('sts_kry') == 'AKTIF' ? 'selected' : '' }}>
                                                                    AKTIF
                                                                </option>
                                                                <option value="NON-AKTIF"
                                                                    {{ old('sts_kry') == 'NON-AKTIF' ? 'selected' : '' }}>
                                                                    NON-AKTIF</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- SKT Status Kry --}}
                                                {{-- <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="skt_sts_kry" class="form-label fw-bold">SKT
                                                            Status Karyawan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-marker-alt"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="skt_sts_kry" name="skt_sts_kry"
                                                                value="{{ old('skt_sts_kry') }}">
                                                        </div>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status PHK (jika diperlukan) -->
                                    <div class="card border-danger mb-4" id="phkCard" style="display: none;">
                                        <div class="card-header bg-danger bg-opacity-25">
                                            <h5 class="mb-0"><i class="fas fa-user-times me-2"></i>Informasi PHK
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_phk" class="form-label fw-bold">
                                                            <i class="fas fa-calendar-minus me-1"></i>Tanggal PHK
                                                        </label>
                                                        <input type="date" class="form-control" id="tgl_phk"
                                                            name="tgl_phk" value="{{ old('tgl_phk') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="ket_phk" class="form-label fw-bold">Keterangan
                                                            PHK</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-comment-alt"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="ket_phk" name="ket_phk"
                                                                value="{{ old('ket_phk') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="kontak-darurat" role="tabpanel"
                                    aria-labelledby="kontak-darurat-tab">
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i
                                                    class="fas fa-phone-square-alt me-2"></i>Informasi Kontak Darurat</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                {{-- Jenis Kontak Darurat --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="jns_kd" class="form-label fw-bold">Jenis
                                                            Kontak</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-user-friends"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="jns_kd"
                                                                    name="jns_kd">
                                                                    <option value="">Pilih Jenis Kontak</option>
                                                                    <option value="ORANG TUA"
                                                                        {{ old('jns_kd') == 'ORANG TUA' ? 'selected' : '' }}>
                                                                        ORANG TUA</option>
                                                                    <option value="SUAMI"
                                                                        {{ old('jns_kd') == 'SUAMI' ? 'selected' : '' }}>
                                                                        SUAMI</option>
                                                                    <option value="ISTRI"
                                                                        {{ old('jns_kd') == 'ISTRI' ? 'selected' : '' }}>
                                                                        ISTRI</option>
                                                                    <option value="SAUDARA KANDUNG"
                                                                        {{ old('jns_kd') == 'SAUDARA KANDUNG' ? 'selected' : '' }}>
                                                                        SAUDARA KANDUNG</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Status Kontak --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="sts_kd" class="form-label fw-bold">Status
                                                            Kontak</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-check-circle"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="sts_kd"
                                                                    name="sts_kd">
                                                                    <option value="">Pilih Status</option>
                                                                    <option value="BAPAK"
                                                                        {{ old('sts_kd') == 'BAPAK' ? 'selected' : '' }}>
                                                                        BAPAK</option>
                                                                    <option value="IBU"
                                                                        {{ old('sts_kd') == 'IBU' ? 'selected' : '' }}>
                                                                        IBU</option>
                                                                    <option value="SUAMI"
                                                                        {{ old('sts_kd') == 'SUAMI' ? 'selected' : '' }}>
                                                                        SUAMI</option>
                                                                    <option value="ISTRI"
                                                                        {{ old('sts_kd') == 'ISTRI' ? 'selected' : '' }}>
                                                                        ISTRI</option>
                                                                    <option value="KAKAK"
                                                                        {{ old('sts_kd') == 'KAKAK' ? 'selected' : '' }}>
                                                                        KAKAK</option>
                                                                    <option value="ADIK"
                                                                        {{ old('sts_kd') == 'ADIK' ? 'selected' : '' }}>
                                                                        ADIK</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                {{-- Nama Kontak Darurat --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="nama_kd" class="form-label fw-bold">Nama
                                                            Lengkap</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-user"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="nama_kd" name="nama_kd"
                                                                value="{{ old('nama_kd') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- NIK Kontak Darurat --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="nik_kd" class="form-label fw-bold">NIK</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-id-card-alt"></i></span>
                                                            <input type="text" class="form-control no-uppercase"
                                                                id="nik_kd" name="nik_kd"
                                                                value="{{ old('nik_kd') }}" minlength="16"
                                                                maxlength="16">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                {{-- Tempat Lahir --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tpt_lhr_kd" class="form-label fw-bold">Tempat
                                                            Lahir</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-marker-alt"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="tpt_lhr_kd" name="tpt_lhr_kd"
                                                                value="{{ old('tpt_lhr_kd') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Tanggal Lahir --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_lhr_kd" class="form-label fw-bold">
                                                            <i class="fas fa-calendar-alt me-1"></i>Tanggal Lahir
                                                        </label>
                                                        <input type="date" class="form-control" id="tgl_lhr_kd"
                                                            name="tgl_lhr_kd" value="{{ old('tgl_lhr_kd') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                {{-- Jenis Kelamin --}}
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="sex_kd" class="form-label fw-bold">Jenis
                                                            Kelamin</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-venus-mars"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="sex_kd"
                                                                    name="sex_kd">
                                                                    <option value="">Pilih Jenis Kelamin</option>
                                                                    <option value="LAKI-LAKI"
                                                                        {{ old('sex_kd') == 'LAKI-LAKI' ? 'selected' : '' }}>
                                                                        LAKI-LAKI</option>
                                                                    <option value="PEREMPUAN"
                                                                        {{ old('sex_kd') == 'PEREMPUAN' ? 'selected' : '' }}>
                                                                        PEREMPUAN</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Agama --}}
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="agama_kd" class="form-label fw-bold">Agama</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-pray"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="agama_kd"
                                                                    name="agama_kd">
                                                                    <option value="">Pilih Agama</option>
                                                                    <option value="ISLAM"
                                                                        {{ old('agama_kd') == 'ISLAM' ? 'selected' : '' }}>
                                                                        ISLAM</option>
                                                                    <option value="KRISTEN"
                                                                        {{ old('agama_kd') == 'KRISTEN' ? 'selected' : '' }}>
                                                                        KRISTEN</option>
                                                                    <option value="KATOLIK"
                                                                        {{ old('agama_kd') == 'KATOLIK' ? 'selected' : '' }}>
                                                                        KATOLIK</option>
                                                                    <option value="HINDU"
                                                                        {{ old('agama_kd') == 'HINDU' ? 'selected' : '' }}>
                                                                        HINDU</option>
                                                                    <option value="BUDDHA"
                                                                        {{ old('agama_kd') == 'BUDDHA' ? 'selected' : '' }}>
                                                                        BUDDHA</option>
                                                                    <option value="KONGHUCU"
                                                                        {{ old('agama_kd') == 'KONGHUCU' ? 'selected' : '' }}>
                                                                        KONGHUCU</option>
                                                                    <option value="LAINNYA"
                                                                        {{ old('agama_kd') == 'LAINNYA' ? 'selected' : '' }}>
                                                                        LAINNYA</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Status Nikah --}}
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="sts_nikah_kd" class="form-label fw-bold">Status
                                                            Pernikahan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-heart"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="sts_nikah_kd"
                                                                    name="sts_nikah_kd">
                                                                    <option value="">Pilih Status</option>
                                                                    <option value="BELUM KAWIN"
                                                                        {{ old('sts_nikah_kd') == 'BELUM KAWIN' ? 'selected' : '' }}>
                                                                        BELUM KAWIN</option>
                                                                    <option value="KAWIN"
                                                                        {{ old('sts_nikah_kd') == 'KAWIN' ? 'selected' : '' }}>
                                                                        KAWIN</option>
                                                                    <option value="CERAI HIDUP"
                                                                        {{ old('sts_nikah_kd') == 'CERAI HIDUP' ? 'selected' : '' }}>
                                                                        CERAI HIDUP</option>
                                                                    <option value="CERAI MATI"
                                                                        {{ old('sts_nikah_kd') == 'CERAI MATI' ? 'selected' : '' }}>
                                                                        CERAI MATI</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                {{-- Telepon 1 --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="telp1_kd" class="form-label fw-bold">No. Telepon
                                                            Utama</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-phone"></i></span>
                                                            <input type="text" class="form-control no-uppercase"
                                                                id="telp1_kd" name="telp1_kd"
                                                                value="{{ old('telp1_kd') }}">
                                                        </div>
                                                    </div>
                                                </div>



                                                {{-- Telepon 2 --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="telp2_kd" class="form-label fw-bold">No. Telepon
                                                            Alternatif</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-mobile-alt"></i></span>
                                                            <input type="text" class="form-control no-uppercase"
                                                                id="telp2_kd" name="telp2_kd"
                                                                value="{{ old('telp2_kd') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i
                                                    class="fas fa-phone-square-alt me-2"></i>Alamat Kontak Darurat</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                {{-- Provinsi --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="prov_kd"
                                                            class="form-label fw-bold">Provinsi</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-globe-asia"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="prov_kd"
                                                                    name="prov_kd">
                                                                    <option value="">Pilih Provinsi</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kelurahan --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kel_kd"
                                                            class="form-label fw-bold">Kelurahan/Desa</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-building"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="kel_kd"
                                                                    name="kel_kd" disabled>
                                                                    <option value="">Pilih Kelurahan/Desa</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                {{-- Kota --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kota_kd"
                                                            class="form-label fw-bold">Kota/Kabupaten</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="kota_kd"
                                                                    name="kota_kd" disabled>
                                                                    <option value="">Pilih Kota/Kabupaten</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- RT/RW --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="rt_rw_kd" class="form-label fw-bold">RT/RW</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-home"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="rt_rw_kd" name="rt_rw_kd"
                                                                value="{{ old('rt_rw_kd') }}" placeholder="001/002">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                {{-- Kecamatan --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kec_kd"
                                                            class="form-label fw-bold">Kecamatan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-city"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="kec_kd"
                                                                    name="kec_kd" disabled>
                                                                    <option value="">Pilih Kecamatan</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kode Pos --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kd_pos_kd" class="form-label fw-bold">Kode
                                                            Pos</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-mail-bulk"></i></span>
                                                            <input type="text" class="form-control no-uppercase"
                                                                id="kd_pos_kd" name="kd_pos_kd"
                                                                value="{{ old('kd_pos_kd') }}" maxlength="5"
                                                                pattern="[0-9]{5}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                {{-- Alamat Lengkap --}}
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="alamat_kd" class="form-label fw-bold">Alamat
                                                            Lengkap</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-marked-alt"></i></span>
                                                            <textarea class="form-control auto-uppercase" id="alamat_kd" name="alamat_kd" rows="3">{{ old('alamat_kd') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Submit Button - Always visible at bottom -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <a href="{{ route('data-karyawan.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                    <i class="fas fa-save me-2"></i> Simpan Data Karyawan
                                </button>
                            </div>
                        </form>
                    </div>
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

        /* ===== EMPLOYEE PHOTO STYLING ===== */
        .employee-photo-container {
            position: relative;
            width: 100%;
            max-width: 250px;
            margin: 0 auto;
        }

        .photo-upload-box {
            position: relative;
            width: 100%;
            margin-bottom: 1rem;
        }

        .employee-photo {
            width: 100%;
            height: auto;
            max-height: 300px;
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
            height: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            border: 2px dashed #dee2e6;
            transition: all 0.3s ease;
        }

        .default-avatar:hover {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }

        .default-avatar:hover i {
            transform: scale(1.1);
            color: #0d6efd !important;
        }

        .default-avatar i {
            transition: all 0.3s ease;
        }

        .default-avatar p {
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .employee-photo-container {
                max-width: 200px;
            }

            .default-avatar {
                height: 200px;
            }

            .default-avatar i {
                font-size: 6rem !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/region-api.js'])


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Auto-uppercase functionality
            document.querySelectorAll('.auto-uppercase').forEach(function(element) {
                element.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            });

            // Calculate age
            document.getElementById('tgl_lahir').addEventListener('change', function() {
                const birthDate = new Date(this.value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                document.getElementById('usiaInfo').textContent =
                    this.value ? `Usia: ${age} tahun` : '';
            });

            // Copy KTP address to domicile
            document.getElementById('samaWithKtp').addEventListener('change', function() {
                if (this.checked) {
                    document.getElementById('alamat_dom').value = document.getElementById('alamat_ktp')
                        .value;
                    document.getElementById('rt_rw_dom').value = document.getElementById('rt_rw_ktp').value;
                    document.getElementById('kel_dom').value = document.getElementById('kel_ktp').value;
                    document.getElementById('kec_dom').value = document.getElementById('kec_ktp').value;
                    document.getElementById('kota_dom').value = document.getElementById('kota_ktp').value;
                    document.getElementById('prov_dom').value = document.getElementById('prov_ktp').value;
                    document.getElementById('kd_pos_dom').value = document.getElementById('kd_pos_ktp')
                        .value;
                }
            });

            // Handle Departemen Change - Load Jabatan
            $('#departemen').on('change', function() {
                const namaDep = $(this).val();
                const jabatanSelect = $('#jabatan');

                if (namaDep) {
                    // Show loading indicator
                    jabatanSelect.prop('disabled', true);
                    jabatanSelect.html('<option value="">Loading...</option>');

                    // AJAX request
                    $.ajax({
                        url: `/data-karyawan/jabatan/${encodeURIComponent(namaDep)}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                // Clear and populate jabatan options
                                jabatanSelect.html('<option value="">Pilih Jabatan</option>');

                                response.data.forEach(function(jabatan) {
                                    const displayText = jabatan.singkatan_jbt ?
                                        `${jabatan.nama_jbt} (${jabatan.singkatan_jbt})` :
                                        jabatan.nama_jbt;

                                    jabatanSelect.append(
                                        `<option value="${jabatan.id}">${displayText}</option>`
                                    );
                                });

                                jabatanSelect.prop('disabled', false);

                                // Reinitialize select2 if needed
                                if (jabatanSelect.hasClass('select2-hidden-accessible')) {
                                    jabatanSelect.select2('destroy');
                                }
                                jabatanSelect.select2({
                                    theme: 'bootstrap-5'
                                });

                                // Optional: Show notification
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Data jabatan berhasil dimuat',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            jabatanSelect.html('<option value="">Pilih Jabatan</option>');
                            jabatanSelect.prop('disabled', false);

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Gagal memuat data jabatan',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });

                            console.error('Error:', error);
                        }
                    });
                } else {
                    // Clear jabatan if no department selected
                    jabatanSelect.html('<option value="">Pilih Jabatan</option>');
                    jabatanSelect.prop('disabled', true);
                }
            });


            // Handle Wilayah Kerja Change - Load Unit Kerja
            // Handle Wilayah Kerja Change - Load Unit Kerja
            $('#wilker').on('change', function() {
                const wilayahKrj = $(this).val();
                const unitSelect = $('#unit_krj');
                const sktInput = $('#skt_wil_krj');

                if (wilayahKrj) {
                    // Show loading indicator
                    unitSelect.prop('disabled', true);
                    unitSelect.html('<option value="">Loading...</option>');
                    sktInput.val(''); // Clear SKT

                    // AJAX request
                    $.ajax({
                        url: `/data-karyawan/unit-kerja/${encodeURIComponent(wilayahKrj)}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                // Clear and populate unit kerja options
                                unitSelect.html('<option value="">Pilih Unit Kerja</option>');

                                response.data.forEach(function(unit) {
                                    const displayText = unit.singkatan_wk ?
                                        `${unit.area_krj}` :
                                        unit.area_krj;

                                    unitSelect.append(
                                        `<option value="${unit.id}" data-singkatan="${unit.singkatan_wk || ''}">${displayText}</option>`
                                    );
                                });

                                unitSelect.prop('disabled', false);

                                // Reinitialize select2
                                if (unitSelect.hasClass('select2-hidden-accessible')) {
                                    unitSelect.select2('destroy');
                                }
                                unitSelect.select2({
                                    theme: 'bootstrap-5'
                                });

                                // Show notification
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Data unit kerja berhasil dimuat',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            unitSelect.html('<option value="">Pilih Unit Kerja</option>');
                            unitSelect.prop('disabled', false);

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Gagal memuat data unit kerja',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });

                            console.error('Error:', error);
                        }
                    });
                } else {
                    // Clear unit kerja if no wilayah selected
                    unitSelect.html('<option value="">Pilih Unit Kerja</option>');
                    unitSelect.prop('disabled', true);
                    sktInput.val('');
                }
            });

            // Handle Unit Kerja Change - Auto-fill SKT
            $('#unit_krj').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const singkatan = selectedOption.data('singkatan');
                const sktInput = $('#skt_wil_krj');

                if (singkatan) {
                    sktInput.val(singkatan);
                } else {
                    sktInput.val('');
                }
            });


            // Show/hide PHK card based on status
            document.getElementById('sts_kry').addEventListener('change', function() {
                const phkCard = document.getElementById('phkCard');
                if (this.value === 'NON-AKTIF') {
                    phkCard.style.display = 'block';
                } else {
                    phkCard.style.display = 'none';
                    document.getElementById('tgl_phk').value = '';
                    document.getElementById('ket_phk').value = '';
                }
            });

            // Calculate contract duration
            function calculateContractDuration() {
                const startDate = document.getElementById('tgl_awal_ktr').value;
                const endDate = document.getElementById('tgl_akhir_ktr').value;

                if (startDate && endDate) {
                    const start = new Date(startDate);
                    const end = new Date(endDate);
                    const months = (end.getFullYear() - start.getFullYear()) * 12 + (end.getMonth() - start
                        .getMonth());
                    document.getElementById('durasi_ktr').value = months > 0 ? months : '';
                }
            }

            document.getElementById('tgl_awal_ktr').addEventListener('change', calculateContractDuration);
            document.getElementById('tgl_akhir_ktr').addEventListener('change', calculateContractDuration);

            // NIK validation
            document.getElementById('nik').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, ''); // Only numbers
                if (this.value.length > 16) {
                    this.value = this.value.slice(0, 16);
                }
            });

            // Phone number validation
            document.querySelectorAll('#tlp1, #tlp2').forEach(function(element) {
                element.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9+\-\s]/g, '');
                });
            });

            // IMPROVED Form validation before submit with SweetAlert2
            document.getElementById('karyawanForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Always prevent default to check validation first

                const requiredFields = this.querySelectorAll('[data-required="true"]');
                let missingFields = [];
                let firstInvalidField = null;

                // Clear previous error states
                requiredFields.forEach(function(field) {
                    field.classList.remove('is-invalid');
                });

                // Check all required fields
                requiredFields.forEach(function(field) {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        missingFields.push(field.previousElementSibling?.textContent?.replace('*',
                            '').trim() || field.name);
                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                    }
                });

                if (missingFields.length > 0) {
                    // Create list of missing fields
                    let fieldsList = '<ul class="text-start mb-0">';
                    missingFields.forEach(function(fieldName) {
                        fieldsList += '<li>' + fieldName + '</li>';
                    });
                    fieldsList += '</ul>';

                    // Show SweetAlert2 warning
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        html: '<p class="mb-2">Mohon lengkapi field berikut yang wajib diisi:</p>' +
                            fieldsList,
                        confirmButtonText: 'OK, Saya Mengerti',
                        confirmButtonColor: '#0d6efd',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
                    });

                    // Find and activate the first tab with error
                    if (firstInvalidField) {
                        const tabPane = firstInvalidField.closest('.tab-pane');
                        if (tabPane) {
                            const tabId = tabPane.id;
                            const tabButton = document.querySelector(`[data-bs-target="#${tabId}"]`);
                            if (tabButton) {
                                // Add error indicator to tab
                                tabButton.classList.add('has-error');
                                const tab = new bootstrap.Tab(tabButton);
                                tab.show();

                                // Scroll to first invalid field
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
                    // All validations passed, submit the form
                    this.submit();
                }
            });

            // Remove error class when field is filled
            document.querySelectorAll('[data-required="true"]').forEach(function(field) {
                field.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');

                        // Remove error indicator from tab if all fields in tab are valid
                        const tabPane = this.closest('.tab-pane');
                        if (tabPane) {
                            const invalidFieldsInTab = tabPane.querySelectorAll('.is-invalid');
                            if (invalidFieldsInTab.length === 0) {
                                const tabId = tabPane.id;
                                const tabButton = document.querySelector(
                                    `[data-bs-target="#${tabId}"]`);
                                if (tabButton) {
                                    tabButton.classList.remove('has-error');
                                }
                            }
                        }
                    }
                });
            });

            document.getElementById('foto_dokumen').addEventListener('change', function(e) {
                const file = e.target.files[0];

                if (file) {
                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!validTypes.includes(file.type)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Format File Salah',
                            text: 'Hanya file JPG, JPEG, atau PNG yang diperbolehkan',
                            confirmButtonColor: '#dc3545'
                        });
                        this.value = '';
                        return;
                    }

                    // Validate file size (max 2MB)
                    const maxSize = 2 * 1024 * 1024; // 2MB in bytes
                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Terlalu Besar',
                            text: 'Ukuran file maksimal 2MB',
                            confirmButtonColor: '#dc3545'
                        });
                        this.value = '';
                        return;
                    }

                    // Create preview
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const photoPreview = document.getElementById('photo_preview');
                        const photoPlaceholder = document.getElementById('photo_placeholder');
                        const removeBtn = document.getElementById('removePhotoBtn');

                        photoPreview.src = event.target.result;
                        photoPreview.style.display = 'block';
                        photoPreview.classList.add('loaded');
                        photoPlaceholder.style.display = 'none';
                        removeBtn.style.display = 'block';

                        console.log('✅ Photo preview loaded successfully');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Remove photo button handler
            document.getElementById('removePhotoBtn').addEventListener('click', function() {
                const photoInput = document.getElementById('foto_dokumen');
                const photoPreview = document.getElementById('photo_preview');
                const photoPlaceholder = document.getElementById('photo_placeholder');
                const removeBtn = document.getElementById('removePhotoBtn');

                // Confirm before removing
                Swal.fire({
                    title: 'Hapus Foto?',
                    text: 'Anda yakin ingin menghapus foto yang sudah dipilih?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        photoInput.value = '';
                        photoPreview.src = '';
                        photoPreview.style.display = 'none';
                        photoPreview.classList.remove('loaded');
                        photoPlaceholder.style.display = 'flex';
                        removeBtn.style.display = 'none';

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Foto berhasil dihapus',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });

                        console.log('✅ Photo removed successfully');
                    }
                });
            });




        });
    </script>
@endpush
