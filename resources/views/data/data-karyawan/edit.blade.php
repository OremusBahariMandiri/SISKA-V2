@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-user-edit me-2"></i>Edit Karyawan</span>
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

                        <form action="{{ route('data-karyawan.update', $dataKaryawan->id) }}" method="POST"
                            id="karyawanForm" enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')
                            <input type="hidden" class="form-control" id="id_kode" name="id_kode"
                                value="{{ old('id_kode', $dataKaryawan->id_kode) }}" readonly>

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
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="nik" class="form-label fw-bold">NIK KTP <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-id-card-alt"></i></span>
                                                            <input type="text" class="form-control no-uppercase"
                                                                id="nik" name="nik"
                                                                value="{{ old('nik', $dataKaryawan->nik) }}"
                                                                minlength="16" maxlength="16" data-required="true">
                                                        </div>
                                                        <div class="form-text text-muted">16 digit angka NIK KTP</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="nama" class="form-label fw-bold">Nama Lengkap
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-user"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="nama" name="nama"
                                                                value="{{ old('nama', $dataKaryawan->nama) }}"
                                                                data-required="true">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tpt_lahir" class="form-label fw-bold">Tempat Lahir
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-marker-alt"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="tpt_lahir" name="tpt_lahir"
                                                                value="{{ old('tpt_lahir', $dataKaryawan->tpt_lahir) }}"
                                                                data-required="true">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_lahir" class="form-label fw-bold">
                                                            <i class="fas fa-calendar-alt me-1"></i>Tanggal Lahir
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="date" class="form-control" id="tgl_lahir"
                                                            name="tgl_lahir"
                                                            value="{{ old('tgl_lahir', $dataKaryawan->tgl_lahir ? \Carbon\Carbon::parse($dataKaryawan->tgl_lahir)->format('Y-m-d') : '') }}"
                                                            data-required="true">
                                                        <div id="usiaInfo" class="form-text text-muted mt-1"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="sex" class="form-label fw-bold">Jenis Kelamin
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-venus-mars"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="sex"
                                                                    name="sex" data-required="true">
                                                                    <option value="">Pilih Jenis Kelamin</option>
                                                                    <option value="LAKI-LAKI"
                                                                        {{ old('sex', $dataKaryawan->sex) == 'LAKI-LAKI' ? 'selected' : '' }}>
                                                                        LAKI-LAKI</option>
                                                                    <option value="PEREMPUAN"
                                                                        {{ old('sex', $dataKaryawan->sex) == 'PEREMPUAN' ? 'selected' : '' }}>
                                                                        PEREMPUAN</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="agama" class="form-label fw-bold">Agama <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-pray"></i></span>
                                                            <div style="flex: 1">
                                                                <select class="form-select select2" id="agama"
                                                                    name="agama" data-required="true">
                                                                    <option value="">Pilih Agama</option>
                                                                    <option value="ISLAM"
                                                                        {{ old('agama', $dataKaryawan->agama) == 'ISLAM' ? 'selected' : '' }}>
                                                                        ISLAM</option>
                                                                    <option value="KRISTEN"
                                                                        {{ old('agama', $dataKaryawan->agama) == 'KRISTEN' ? 'selected' : '' }}>
                                                                        KRISTEN</option>
                                                                    <option value="KATOLIK"
                                                                        {{ old('agama', $dataKaryawan->agama) == 'KATOLIK' ? 'selected' : '' }}>
                                                                        KATOLIK</option>
                                                                    <option value="HINDU"
                                                                        {{ old('agama', $dataKaryawan->agama) == 'HINDU' ? 'selected' : '' }}>
                                                                        HINDU</option>
                                                                    <option value="BUDDHA"
                                                                        {{ old('agama', $dataKaryawan->agama) == 'BUDDHA' ? 'selected' : '' }}>
                                                                        BUDDHA</option>
                                                                    <option value="KONGHUCU"
                                                                        {{ old('agama', $dataKaryawan->agama) == 'KONGHUCU' ? 'selected' : '' }}>
                                                                        KONGHUCU</option>
                                                                    <option value="LAINNYA"
                                                                        {{ old('agama', $dataKaryawan->agama) == 'LAINNYA' ? 'selected' : '' }}>
                                                                        LAINNYA</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="kewarganegaraan"
                                                            class="form-label fw-bold">Kewarganegaraan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-flag"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="kewarganegaraan" name="kewarganegaraan"
                                                                value="{{ old('kewarganegaraan', $dataKaryawan->kewarganegaraan ?? 'INDONESIA') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="foto_dokumen" class="form-label fw-bold">Unggah
                                                            Dokumen</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-file-pdf"></i></span>
                                                            <input type="file" class="form-control" id="foto_dokumen"
                                                                name="foto_dokumen">
                                                        </div>
                                                        <div class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>Format file: PDF, JPG,
                                                            PNG, DOC, DOCX
                                                        </div>
                                                        @if ($dataKaryawan->foto_dokumen)
                                                            <div class="mt-2">
                                                                <small class="text-success">
                                                                    <i class="fas fa-file-check me-1"></i>File saat ini:
                                                                    <a href="{{ Storage::url($dataKaryawan->foto_dokumen) }}"
                                                                        target="_blank">{{ basename($dataKaryawan->foto_dokumen) }}</a>
                                                                </small>
                                                            </div>
                                                        @endif
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
                                                                        {{ old('sts_nikah', $dataKaryawan->sts_nikah) == 'BELUM KAWIN' ? 'selected' : '' }}>
                                                                        BELUM KAWIN</option>
                                                                    <option value="KAWIN"
                                                                        {{ old('sts_nikah', $dataKaryawan->sts_nikah) == 'KAWIN' ? 'selected' : '' }}>
                                                                        KAWIN</option>
                                                                    <option value="CERAI HIDUP"
                                                                        {{ old('sts_nikah', $dataKaryawan->sts_nikah) == 'CERAI HIDUP' ? 'selected' : '' }}>
                                                                        CERAI HIDUP</option>
                                                                    <option value="CERAI MATI"
                                                                        {{ old('sts_nikah', $dataKaryawan->sts_nikah) == 'CERAI MATI' ? 'selected' : '' }}>
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
                                                                        {{ old('sts_keluarga', $dataKaryawan->sts_keluarga) == 'SUAMI' ? 'selected' : '' }}>
                                                                        SUAMI</option>
                                                                    <option value="ISTRI"
                                                                        {{ old('sts_keluarga', $dataKaryawan->sts_keluarga) == 'ISTRI' ? 'selected' : '' }}>
                                                                        ISTRI</option>
                                                                    <option value="BAPAK"
                                                                        {{ old('sts_keluarga', $dataKaryawan->sts_keluarga) == 'BAPAK' ? 'selected' : '' }}>
                                                                        BAPAK</option>
                                                                    <option value="IBU"
                                                                        {{ old('sts_keluarga', $dataKaryawan->sts_keluarga) == 'IBU' ? 'selected' : '' }}>
                                                                        IBU</option>
                                                                    <option value="ANAK"
                                                                        {{ old('sts_keluarga', $dataKaryawan->sts_keluarga) == 'ANAK' ? 'selected' : '' }}>
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
                                                                name="jml_anak"
                                                                value="{{ old('jml_anak', $dataKaryawan->jml_anak ?? 0) }}"
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
                                                                value="{{ old('tlp1', $dataKaryawan->tlp1) }}"
                                                                data-required="true">
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
                                                                value="{{ old('tlp2', $dataKaryawan->tlp2) }}">
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
                                                                value="{{ old('email1', $dataKaryawan->email1) }}">
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
                                                                value="{{ old('email2', $dataKaryawan->email2) }}">
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
                                                                value="{{ old('instagram', $dataKaryawan->instagram) }}">
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
                                                                value="{{ old('facebook', $dataKaryawan->facebook) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab Alamat (KTP dan Domisili) -->
                                <div class="tab-pane fade" id="alamat" role="tabpanel" aria-labelledby="alamat-tab">
                                    <!-- Alamat KTP -->
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
                                                                value="{{ old('rt_rw_ktp', $karyawan->rt_rw_ktp ?? '') }}"
                                                                placeholder="001/002">
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
                                                                value="{{ old('kd_pos_ktp', $karyawan->kd_pos_ktp ?? '') }}"
                                                                maxlength="5" pattern="[0-9]{5}">
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
                                                                data-required="true">{{ old('alamat_ktp', $karyawan->alamat_ktp ?? '') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- ALAMAT DOMISILI SECTION - EDIT VERSION --}}
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
                                                                value="{{ old('rt_rw_dom', $karyawan->rt_rw_dom ?? '') }}"
                                                                placeholder="001/002">
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
                                                                value="{{ old('kd_pos_dom', $karyawan->kd_pos_dom ?? '') }}"
                                                                maxlength="5" pattern="[0-9]{5}">
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
                                                                data-required="true">{{ old('alamat_dom', $karyawan->alamat_dom ?? '') }}</textarea>
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
                                                                    <option value="SD"
                                                                        {{ old('jenjang_skl', $dataKaryawan->jenjang_skl) == 'SD' ? 'selected' : '' }}>
                                                                        SD
                                                                    </option>
                                                                    <option value="SMP"
                                                                        {{ old('jenjang_skl', $dataKaryawan->jenjang_skl) == 'SMP' ? 'selected' : '' }}>
                                                                        SMP
                                                                    </option>
                                                                    <option value="SMA/SMK"
                                                                        {{ old('jenjang_skl', $dataKaryawan->jenjang_skl) == 'SMA/SMK' ? 'selected' : '' }}>
                                                                        SMA/SMK</option>
                                                                    <option value="D1"
                                                                        {{ old('jenjang_skl', $dataKaryawan->jenjang_skl) == 'D1' ? 'selected' : '' }}>
                                                                        D1
                                                                    </option>
                                                                    <option value="D2"
                                                                        {{ old('jenjang_skl', $dataKaryawan->jenjang_skl) == 'D2' ? 'selected' : '' }}>
                                                                        D2
                                                                    </option>
                                                                    <option value="D3"
                                                                        {{ old('jenjang_skl', $dataKaryawan->jenjang_skl) == 'D3' ? 'selected' : '' }}>
                                                                        D3
                                                                    </option>
                                                                    <option value="D4/S1"
                                                                        {{ old('jenjang_skl', $dataKaryawan->jenjang_skl) == 'D4/S1' ? 'selected' : '' }}>
                                                                        D4/S1</option>
                                                                    <option value="S2"
                                                                        {{ old('jenjang_skl', $dataKaryawan->jenjang_skl) == 'S2' ? 'selected' : '' }}>
                                                                        S2
                                                                    </option>
                                                                    <option value="S3"
                                                                        {{ old('jenjang_skl', $dataKaryawan->jenjang_skl) == 'S3' ? 'selected' : '' }}>
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
                                                                value="{{ old('institusi_skl', $dataKaryawan->institusi_skl) }}">
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
                                                                value="{{ old('kota_skl', $dataKaryawan->kota_skl) }}">
                                                        </div>
                                                    </div>
                                                </div>
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
                                                                value="{{ old('fakultas_skl', $dataKaryawan->fakultas_skl) }}">
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
                                                                value="{{ old('gelar_skl', $dataKaryawan->gelar_skl) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
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
                                                                value="{{ old('jurusan_skl', $dataKaryawan->jurusan_skl) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_lulus_skl" class="form-label fw-bold">
                                                            <i class="fas fa-calendar-check me-1"></i>Tanggal Lulus
                                                        </label>
                                                        <input type="date" class="form-control" id="tgl_lulus_skl"
                                                            name="tgl_lulus_skl"
                                                            value="{{ old('tgl_lulus_skl', $dataKaryawan->tgl_lulus_skl ? \Carbon\Carbon::parse($dataKaryawan->tgl_lulus_skl)->format('Y-m-d') : '') }}">
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
                                                                    <option value="">Pilih Status</option>
                                                                    <option value="PKWT"
                                                                        {{ old('sts_ktr', $dataKaryawan->sts_ktr) == 'PKWT' ? 'selected' : '' }}>
                                                                        PKWT
                                                                    </option>
                                                                    <option value="PKWTT"
                                                                        {{ old('sts_ktr', $dataKaryawan->sts_ktr) == 'PKWTT' ? 'selected' : '' }}>
                                                                        PKWTT
                                                                    </option>
                                                                    <option value="FREELANCE"
                                                                        {{ old('sts_ktr', $dataKaryawan->sts_ktr) == 'FREELANCE' ? 'selected' : '' }}>
                                                                        FREELANCE</option>
                                                                    <option value="MAGANG"
                                                                        {{ old('sts_ktr', $dataKaryawan->sts_ktr) == 'MAGANG' ? 'selected' : '' }}>
                                                                        MAGANG</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                                            {{ old('perusahaan', $dataKaryawan->perusahaan) == $perusahaan->id ? 'selected' : '' }}>
                                                                            {{ $perusahaan->nama_prs1 }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_awal_ktr" class="form-label fw-bold">
                                                            <i class="fas fa-calendar-plus me-1"></i>Tanggal Mulai Kontrak
                                                        </label>
                                                        <input type="date" class="form-control" id="tgl_awal_ktr"
                                                            name="tgl_awal_ktr"
                                                            value="{{ old('tgl_awal_ktr', $dataKaryawan->tgl_awal_ktr ? \Carbon\Carbon::parse($dataKaryawan->tgl_awal_ktr)->format('Y-m-d') : '') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_akhir_ktr" class="form-label fw-bold">
                                                            <i class="fas fa-calendar-minus me-1"></i>Tanggal Akhir Kontrak
                                                        </label>
                                                        <input type="date" class="form-control" id="tgl_akhir_ktr"
                                                            name="tgl_akhir_ktr"
                                                            value="{{ old('tgl_akhir_ktr', $dataKaryawan->tgl_akhir_ktr ? \Carbon\Carbon::parse($dataKaryawan->tgl_akhir_ktr)->format('Y-m-d') : '') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="durasi_ktr" class="form-label fw-bold">Durasi Kontrak
                                                            (Bulan)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-clock"></i></span>
                                                            <input type="number" class="form-control" id="durasi_ktr"
                                                                name="durasi_ktr"
                                                                value="{{ old('durasi_ktr', $dataKaryawan->durasi_ktr) }}"
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
                                                                        <option value="{{ $departemen->id }}"
                                                                            {{ old('departemen', $dataKaryawan->departemen) == $departemen->id ? 'selected' : '' }}>
                                                                            {{ $departemen->nama_dep }} -
                                                                            {{ $departemen->nama_jbt }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="jabatan" class="form-label fw-bold">Jabatan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-user-tie"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="jabatan" name="jabatan"
                                                                value="{{ old('jabatan', $dataKaryawan->jabatan) }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
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
                                                                        <option value="{{ $wilayah->id }}"
                                                                            {{ old('wilker', $dataKaryawan->wilker) == $wilayah->id ? 'selected' : '' }}>
                                                                            {{ $wilayah->wilayah_krj }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="unit_krj" class="form-label fw-bold">Unit
                                                            Kerja</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-users-cog"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="unit_krj" name="unit_krj"
                                                                value="{{ old('unit_krj', $dataKaryawan->unit_krj) }}">
                                                        </div>
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
                                                            <textarea class="form-control auto-uppercase" id="tugas" name="tugas" rows="4">{{ old('tugas', $dataKaryawan->tugas) }}</textarea>
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
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="sts_kry" class="form-label fw-bold">Status Karyawan
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-user-check"></i></span>
                                                            <select class="form-select" id="sts_kry" name="sts_kry"
                                                                data-required="true">
                                                                <option value="">Pilih Status</option>
                                                                <option value="CALON"
                                                                    {{ old('sts_kry', $dataKaryawan->sts_kry) == 'CALON' ? 'selected' : '' }}>
                                                                    CALON
                                                                </option>
                                                                <option value="AKTIF"
                                                                    {{ old('sts_kry', $dataKaryawan->sts_kry) == 'AKTIF' ? 'selected' : '' }}>
                                                                    AKTIF
                                                                </option>
                                                                <option value="NON-AKTIF"
                                                                    {{ old('sts_kry', $dataKaryawan->sts_kry) == 'NON-AKTIF' ? 'selected' : '' }}>
                                                                    NON-AKTIF</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_masuk" class="form-label fw-bold">
                                                            <i class="fas fa-calendar-plus me-1"></i>Tanggal Masuk
                                                        </label>
                                                        <input type="date" class="form-control" id="tgl_masuk"
                                                            name="tgl_masuk"
                                                            value="{{ old('tgl_masuk', $dataKaryawan->tgl_masuk ? \Carbon\Carbon::parse($dataKaryawan->tgl_masuk)->format('Y-m-d') : '') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="nrk" class="form-label fw-bold">NRK</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-id-badge"></i></span>
                                                            <input type="text" class="form-control auto-uppercase"
                                                                id="nrk" name="nrk"
                                                                value="{{ old('nrk', $dataKaryawan->nrk) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status PHK (jika diperlukan) -->
                                    <div class="card border-danger mb-4" id="phkCard"
                                        style="{{ old('sts_kry', $dataKaryawan->sts_kry) == 'NON-AKTIF' ? 'display: block;' : 'display: none;' }}">
                                        <div class="card-header bg-danger bg-opacity-25">
                                            <h5 class="mb-0"><i class="fas fa-user-times me-2"></i>Informasi PHK</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tgl_phk" class="form-label fw-bold">
                                                            <i class="fas fa-calendar-minus me-1"></i>Tanggal PHK
                                                        </label>
                                                        <input type="date" class="form-control" id="tgl_phk"
                                                            name="tgl_phk"
                                                            value="{{ old('tgl_phk', $dataKaryawan->tgl_phk ? \Carbon\Carbon::parse($dataKaryawan->tgl_phk)->format('Y-m-d') : '') }}">
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
                                                                value="{{ old('ket_phk', $dataKaryawan->ket_phk) }}">
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
                                    <i class="fas fa-save me-2"></i> Update Data Karyawan
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
    @vite(['resources/js/region-api.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ============ OPTIMIZED LOAD EXISTING DATA ============
            async function loadExistingAddressData() {
                try {
                    const addresses = {
                        ktp: {
                            prov: "{{ old('prov_ktp', $dataKaryawan->prov_ktp ?? '') }}",
                            kota: "{{ old('kota_ktp', $dataKaryawan->kota_ktp ?? '') }}",
                            kec: "{{ old('kec_ktp', $dataKaryawan->kec_ktp ?? '') }}",
                            kel: "{{ old('kel_ktp', $dataKaryawan->kel_ktp ?? '') }}"
                        },
                        dom: {
                            prov: "{{ old('prov_dom', $dataKaryawan->prov_dom ?? '') }}",
                            kota: "{{ old('kota_dom', $dataKaryawan->kota_dom ?? '') }}",
                            kec: "{{ old('kec_dom', $dataKaryawan->kec_dom ?? '') }}",
                            kel: "{{ old('kel_dom', $dataKaryawan->kel_dom ?? '') }}"
                        }
                    };

                    // Load both addresses in parallel
                    await Promise.all([
                        addresses.ktp.prov ? loadAddressDataOptimized('ktp', addresses.ktp) : Promise.resolve(),
                        addresses.dom.prov ? loadAddressDataOptimized('dom', addresses.dom) : Promise.resolve()
                    ]);

                } catch (error) {
                    console.error('Error loading address data:', error);
                }
            }

            async function loadAddressDataOptimized(type, address) {
                const selectors = {
                    prov: $(`#prov_${type}`),
                    kota: $(`#kota_${type}`),
                    kec: $(`#kec_${type}`),
                    kel: $(`#kel_${type}`)
                };

                // Set Provinsi
                if (address.prov) {
                    await waitForOptions(selectors.prov);
                    const provId = setSelectValue(selectors.prov, address.prov);

                    if (provId && address.kota) {
                        // Trigger change and wait for kota to load
                        selectors.prov.trigger('change');
                        await waitForOptions(selectors.kota, true);

                        const kotaId = setSelectValue(selectors.kota, address.kota);

                        if (kotaId && address.kec) {
                            // Trigger change and wait for kecamatan to load
                            selectors.kota.trigger('change');
                            await waitForOptions(selectors.kec, true);

                            const kecId = setSelectValue(selectors.kec, address.kec);

                            if (kecId && address.kel) {
                                // Trigger change and wait for kelurahan to load
                                selectors.kec.trigger('change');
                                await waitForOptions(selectors.kel, true);

                                setSelectValue(selectors.kel, address.kel);
                            }
                        }
                    }
                }
            }

            // Wait for select options to be populated
            function waitForOptions($select, waitForEnabled = false) {
                return new Promise((resolve) => {
                    const checkOptions = () => {
                        const hasOptions = $select.find('option').length > 1;
                        const isEnabled = !waitForEnabled || !$select.prop('disabled');

                        if (hasOptions && isEnabled) {
                            resolve();
                        } else {
                            setTimeout(checkOptions, 50); // Check every 50ms
                        }
                    };
                    checkOptions();
                });
            }

            // Set select value and return data-id
            function setSelectValue($select, value) {
                let dataId = null;

                $select.find('option').each(function() {
                    const $option = $(this);
                    if ($option.val() === value || $option.text().trim() === value) {
                        $select.val(value).trigger('change.select2');
                        dataId = $option.attr('data-id');
                        return false; // break
                    }
                });

                return dataId;
            }

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

                document.getElementById('usiaInfo').textContent = this.value ? `Usia: ${age} tahun` : '';
            });

            // Trigger age calculation on page load
            const tglLahir = document.getElementById('tgl_lahir');
            if (tglLahir.value) {
                tglLahir.dispatchEvent(new Event('change'));
            }

            // Copy KTP address to domicile
            document.getElementById('samaWithKtp').addEventListener('change', function() {
                if (this.checked) {
                    document.getElementById('alamat_dom').value = document.getElementById('alamat_ktp').value;
                    document.getElementById('rt_rw_dom').value = document.getElementById('rt_rw_ktp').value;
                    document.getElementById('kd_pos_dom').value = document.getElementById('kd_pos_ktp').value;
                }
            });

            // Departemen change handler
            $('#departemen').on('change', function() {
                const departemenId = $(this).val();
                const jabatanInput = $('#jabatan');

                if (departemenId) {
                    jabatanInput.prop('disabled', true).val('Loading...');

                    $.ajax({
                        url: `/departemen/${departemenId}/jabatan`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                jabatanInput.val(response.data.nama_jbt || '').prop('disabled', false);
                            }
                        },
                        error: function() {
                            jabatanInput.val('').prop('disabled', false);
                        }
                    });
                } else {
                    jabatanInput.val('').prop('disabled', false);
                }
            });

            // Wilker change handler
            $('#wilker').on('change', function() {
                const wilkerId = $(this).val();
                const unitInput = $('#unit_krj');

                if (wilkerId) {
                    unitInput.prop('disabled', true).val('Loading...');

                    $.ajax({
                        url: `/wilker/${wilkerId}/unitkrj`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                unitInput.val(response.datawilker.area_krj || '').prop('disabled', false);
                            }
                        },
                        error: function() {
                            unitInput.val('').prop('disabled', false);
                        }
                    });
                } else {
                    unitInput.val('').prop('disabled', false);
                }
            });

            // Show/hide PHK card
            document.getElementById('sts_kry').addEventListener('change', function() {
                const phkCard = document.getElementById('phkCard');
                phkCard.style.display = this.value === 'NON-AKTIF' ? 'block' : 'none';

                if (this.value !== 'NON-AKTIF') {
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
                    const months = (end.getFullYear() - start.getFullYear()) * 12 + (end.getMonth() - start.getMonth());
                    document.getElementById('durasi_ktr').value = months > 0 ? months : '';
                }
            }

            document.getElementById('tgl_awal_ktr').addEventListener('change', calculateContractDuration);
            document.getElementById('tgl_akhir_ktr').addEventListener('change', calculateContractDuration);

            // NIK validation
            document.getElementById('nik').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 16);
            });

            // Phone validation
            document.querySelectorAll('#tlp1, #tlp2').forEach(function(element) {
                element.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9+\-\s]/g, '');
                });
            });

            // Form validation
            document.getElementById('karyawanForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const requiredFields = this.querySelectorAll('[data-required="true"]');
                let missingFields = [];
                let firstInvalidField = null;

                requiredFields.forEach(field => field.classList.remove('is-invalid'));

                requiredFields.forEach(function(field) {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        missingFields.push(field.previousElementSibling?.textContent?.replace('*', '').trim() || field.name);
                        if (!firstInvalidField) firstInvalidField = field;
                    }
                });

                if (missingFields.length > 0) {
                    let fieldsList = '<ul class="text-start mb-0">';
                    missingFields.forEach(name => fieldsList += '<li>' + name + '</li>');
                    fieldsList += '</ul>';

                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        html: '<p class="mb-2">Mohon lengkapi field berikut:</p>' + fieldsList,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#0d6efd'
                    });

                    if (firstInvalidField) {
                        const tabPane = firstInvalidField.closest('.tab-pane');
                        if (tabPane) {
                            const tabButton = document.querySelector(`[data-bs-target="#${tabPane.id}"]`);
                            if (tabButton) {
                                tabButton.classList.add('has-error');
                                new bootstrap.Tab(tabButton).show();
                                setTimeout(() => firstInvalidField.scrollIntoView({behavior: 'smooth', block: 'center'}), 300);
                            }
                        }
                    }
                } else {
                    this.submit();
                }
            });

            // Remove error on input
            document.querySelectorAll('[data-required="true"]').forEach(function(field) {
                field.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');

                        const tabPane = this.closest('.tab-pane');
                        if (tabPane && !tabPane.querySelectorAll('.is-invalid').length) {
                            document.querySelector(`[data-bs-target="#${tabPane.id}"]`)?.classList.remove('has-error');
                        }
                    }
                });
            });

            // START LOADING - Wait minimal time for region API init
            setTimeout(() => loadExistingAddressData(), 800);
        });
    </script>
@endpush
