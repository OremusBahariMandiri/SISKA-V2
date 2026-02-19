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
                                <button class="nav-link" id="kontrak-tab" data-bs-toggle="tab" data-bs-target="#kontrak"
                                    type="button" role="tab" aria-controls="kontrak" aria-selected="false">
                                    <i class="fas fa-file-contract me-1"></i> Data Kontrak
                                    <span class="badge bg-primary ms-1"
                                        id="kontrakCount">{{ $allContracts->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pendidikan-tab" data-bs-toggle="tab"
                                    data-bs-target="#pendidikan" type="button" role="tab" aria-controls="pendidikan"
                                    aria-selected="false">
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
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-user me-2"></i>Informasi Karyawan
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Nama Lengkap</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->karyawan->nama ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">NIK</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->karyawan->nik ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">NRK</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->karyawan->nrk ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tempat, Tanggal Lahir</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->karyawan->tpt_lahir ?? '' }}{{ $dataKontrak->karyawan->tgl_lahir ? ', ' . $dataKontrak->karyawan->tgl_lahir->format('d-m-Y') : '' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jenis Kelamin</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->karyawan->sex ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Telepon</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->karyawan->tlp1 ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Email</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->karyawan->email1 ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Status Kawin</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->karyawan->sts_nikah ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jumlah Anak</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->karyawan->jml_anak ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Tab 2: Data Kontrak (CRUD Table) -->
                            <div class="tab-pane fade" id="kontrak" role="tabpanel" aria-labelledby="kontrak-tab">
                                <div class="card border-primary mb-4">
                                    <div
                                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-file-contract me-2"></i>Manajemen Kontrak Karyawan
                                        </h5>
                                        <button type="button" class="btn btn-light btn-sm" id="addContractBtn">
                                            <i class="fas fa-plus me-1"></i> Tambah Kontrak
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="contractsTable"
                                                class="table table-bordered table-striped data-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="3%" class="text-center">No</th>
                                                        <th width="10%" class="text-center">No. Surat</th>
                                                        <th width="8%" class="text-center">Tgl Surat</th>
                                                        <th width="12%" class="text-center">Jenis Kontrak</th>
                                                        <th width="8%" class="text-center">Tgl Mulai</th>
                                                        <th width="8%" class="text-center">Tgl Akhir</th>
                                                        <th width="6%" class="text-center">Durasi</th>
                                                        <th width="8%" class="text-center">Tgl Pengingat</th>
                                                        <th width="10%" class="text-center">Peringatan</th>
                                                        <th width="10%" class="text-center">Ket Kontrak</th>
                                                        <th width="8%" class="text-center">Status</th>
                                                        <th width="6%" class="text-center">Create</th>
                                                        <th width="6%" class="text-center">Update</th>
                                                        <th width="10%" class="text-center no-wrap">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="contractsTableBody">
                                                    @foreach ($allContracts as $index => $contract)
                                                        <tr data-contract-id="{{ $contract->id }}"
                                                            data-contract-status="{{ $contract->sts_srt_ktr }}"
                                                            data-tgl-peringatan="{{ $contract->tgl_pgt_ktr }}"
                                                            data-tgl-akhir="{{ $contract->tgl_akhir_ktr }}"
                                                            data-ktg-ktk="{{ $contract->ktg_ktk }}">
                                                            <td class="text-center">{{ $index + 1 }}</td>
                                                            <td class="text-center">
                                                                <small
                                                                    class="fw-bold">{{ $contract->no_srt_ktr ?: '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $contract->tgl_srt_ktr ? \Carbon\Carbon::parse($contract->tgl_srt_ktr)->format('d-m-Y') : '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                <span>{{ $contract->kontrakKerja->singkatan_ktr }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $contract->tgl_awl_ktr ? \Carbon\Carbon::parse($contract->tgl_awl_ktr)->format('d-m-Y') : '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $contract->tgl_akhir_ktr ? \Carbon\Carbon::parse($contract->tgl_akhir_ktr)->format('d-m-Y') : '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                <span>{{ $contract->durasi_ktr }} bln</span>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $contract->tgl_pgt_ktr ? \Carbon\Carbon::parse($contract->tgl_pgt_ktr)->format('d-m-Y') : '-' }}
                                                            </td>
                                                            <!-- Warning Column (Will be calculated by JS) -->
                                                            <td class="text-center sisa-peringatan-col">
                                                                <span class="badge bg-secondary">Loading...</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span>{{ $contract->ktg_ktk }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span>{{ $contract->sts_srt_ktr }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ $contract->creator ? $contract->creator->nama_kry : '-' }}</small>
                                                                <br><small
                                                                    class="text-muted">{{ $contract->created_at ? $contract->created_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ $contract->updater ? $contract->updater->nama_kry : '-' }}</small>
                                                                <br><small
                                                                    class="text-muted">{{ $contract->updated_at ? $contract->updated_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td class="text-center no-wrap">
                                                                <div class="btn-group" role="group"
                                                                    aria-label="Actions">
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-info show-contract-btn"
                                                                        data-contract-id="{{ $contract->id }}"
                                                                        data-mode="view" data-bs-toggle="tooltip"
                                                                        title="Detail">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-warning edit-contract-btn"
                                                                        data-contract-id="{{ $contract->id }}"
                                                                        data-mode="edit" data-bs-toggle="tooltip"
                                                                        title="Edit">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger delete-contract-btn"
                                                                        data-contract-id="{{ $contract->id }}"
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
                            <div class="tab-pane fade" id="pendidikan" role="tabpanel" aria-labelledby="pendidikan-tab">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-graduation-cap me-2"></i>Pendidikan
                                            Terakhir</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('data-kontrak.update', $dataKontrak->id) }}"
                                            method="POST" id="educationForm">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="update_type" value="education">

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
                                                                        {{ old('jenjang_skl', $dataKontrak->jenjang_skl) == '-' ? 'selected' : '' }}>
                                                                        -
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
                                                            name="tgl_lulus_skl"
                                                            value="{{ old('tgl_lulus_skl', $dataKontrak->tgl_lulus_skl ? $dataKontrak->tgl_lulus_skl->format('Y-m-d') : '') }}">
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

                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-2"></i> Update Pendidikan
                                                </button>
                                            </div>
                                        </form>
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
                                        <form action="{{ route('data-kontrak.update', $dataKontrak->id) }}"
                                            method="POST" id="careerForm">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="update_type" value="career">

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
                                                            id="info_singkatan_dep"
                                                            value="{{ $dataKontrak->departemen->singkatan_dep ?? '' }}"
                                                            readonly>
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
                                                                    @if ($dataKontrak->departemen)
                                                                        <option value="{{ $dataKontrak->departemen->id }}"
                                                                            selected>
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
                                                            id="info_singkatan_jbt"
                                                            value="{{ $dataKontrak->departemen->singkatan_jbt ?? '' }}"
                                                            readonly>
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
                                                                    @if ($dataKontrak->wilayahKerja)
                                                                        <option
                                                                            value="{{ $dataKontrak->wilayahKerja->id }}"
                                                                            selected>
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
                                                            value="{{ $dataKontrak->wilayahKerja->singkatan_wk ?? '' }}"
                                                            readonly>
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

                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-2"></i> Update Karir
                                                </button>
                                            </div>
                                        </form>
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
                                                        value="{{ $dataKontrak->karyawan->tgl_masuk ? $dataKontrak->karyawan->tgl_masuk->format('d-m-Y') : '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Status Karyawan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->karyawan->sts_kry ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal PHK</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->karyawan->tgl_phk ? $dataKontrak->karyawan->tgl_phk->format('d-m-Y') : '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Keterangan PHK</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->karyawan->ket_phk ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Global action buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('data-kontrak.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Kembali
                            </a>
                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-danger" id="deleteAllContractsBtn"
                                        data-employee-id="{{ $dataKontrak->id }}"
                                        data-employee-name="{{ $dataKontrak->karyawan->nama ?? 'N/A' }}">
                                        <i class="fas fa-trash me-1"></i> Hapus Semua Kontrak
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contract Modal -->
    <!-- Contract Modal (Unified: Create/Edit/Show) -->
    <div class="modal fade" id="contractModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" id="contractModalHeader">
                    <h5 class="modal-title text-white" id="contractModalTitle">Kontrak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="contractForm">
                        <input type="hidden" id="contract_id" name="contract_id">
                        <input type="hidden" name="employee_id" value="{{ $dataKontrak->id_data_kry }}">

                        <!-- Mode Indicator -->
                        <div class="alert alert-info" id="modeIndicator" style="display: none;">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="modeText">Mode Detail - Data hanya dapat dilihat</span>
                        </div>

                        <!-- Informasi Umum Kontrak -->
                        <div class="card border-secondary mb-4">
                            <div class="card-header bg-primary">
                                <h6 class="mb-0 text-white"><i class="fas fa-file-contract me-2"></i>Informasi Umum
                                    Kontrak</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- No Surat Kontrak -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_no_srt_ktr" class="form-label fw-bold">No. Surat
                                                Kontrak</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                <input type="text" class="form-control auto-uppercase"
                                                    id="modal_no_srt_ktr" name="no_srt_ktr">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tanggal Surat Kontrak -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_tgl_srt_ktr" class="form-label fw-bold">Tanggal Surat
                                                Kontrak</label>
                                            <input type="date" class="form-control" id="modal_tgl_srt_ktr"
                                                name="tgl_srt_ktr">
                                        </div>
                                    </div>

                                    <!-- Status Kontrak -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_id_ktr" class="form-label fw-bold">Status Kontrak <span
                                                    class="text-danger" id="required_id_ktr">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="fas fa-file-signature"></i></span>
                                                <div style="flex: 1">
                                                    <select class="form-select select2" id="modal_id_ktr" name="id_ktr">
                                                        <option value="">Pilih Status Kontrak</option>
                                                        @foreach ($kontrakTypes as $kontrak)
                                                            <option value="{{ $kontrak->id }}"
                                                                data-nama-ktr="{{ $kontrak->nama_ktr }}"
                                                                data-singkatan-ktr="{{ $kontrak->singkatan_ktr }}">
                                                                {{ $kontrak->nama_ktr }} - {{ $kontrak->singkatan_ktr }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Perusahaan -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_id_prsh" class="form-label fw-bold">Perusahaan <span
                                                    class="text-danger" id="required_id_prsh">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                <div style="flex: 1">
                                                    <select class="form-select select2" id="modal_id_prsh"
                                                        name="id_prsh">
                                                        <option value="">Pilih Perusahaan</option>
                                                        @foreach ($perusahaans as $perusahaan)
                                                            <option value="{{ $perusahaan->id }}"
                                                                data-nama-prs1="{{ $perusahaan->nama_prs1 }}"
                                                                data-nama-prs2="{{ $perusahaan->nama_prs2 }}">
                                                                {{ $perusahaan->nama_prs1 }} -
                                                                {{ $perusahaan->nama_prs2 }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Keterangan Kontrak -->
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="modal_ktg_ktk" class="form-label fw-bold">Keterangan Kontrak <span
                                                    class="text-danger" id="required_ktg_ktk">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                <div style="flex: 1">
                                                    <select class="form-select select2" id="modal_ktg_ktk"
                                                        name="ktg_ktk">
                                                        <option value="">Pilih Status</option>
                                                        <option value="TETAP">TETAP</option>
                                                        <option value="TIDAK TETAP">TIDAK TETAP</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Periode Kontrak -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary">
                                <h6 class="mb-0 text-white"><i class="fas fa-calendar-alt me-2"></i>Periode Kontrak</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Tanggal Mulai -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="modal_tgl_awl_ktr" class="form-label fw-bold">Tanggal Mulai
                                                Kontrak <span class="text-danger" id="required_tgl_awl">*</span></label>
                                            <input type="date" class="form-control" id="modal_tgl_awl_ktr"
                                                name="tgl_awl_ktr">
                                        </div>
                                    </div>

                                    <!-- Tanggal Akhir -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="modal_tgl_akhir_ktr" class="form-label fw-bold"
                                                id="modal_label_tgl_akhir">Tanggal Akhir Kontrak <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="modal_tgl_akhir_ktr"
                                                name="tgl_akhir_ktr">
                                        </div>
                                    </div>

                                    <!-- Durasi -->
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="modal_durasi_ktr" class="form-label fw-bold"
                                                id="modal_label_durasi">Durasi Kontrak (Bulan)</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                <input type="number" class="form-control" id="modal_durasi_ktr"
                                                    name="durasi_ktr" min="1" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tanggal Pengingat -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_tgl_pgt_ktr" class="form-label fw-bold">Tanggal
                                                Pengingat</label>
                                            <input type="date" class="form-control" id="modal_tgl_pgt_ktr"
                                                name="tgl_pgt_ktr">
                                        </div>
                                    </div>

                                    <!-- Durasi Pengingat -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_durasi_pgt" class="form-label fw-bold">Durasi Pengingat
                                                (Hari)</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                <input type="number" class="form-control" id="modal_durasi_pgt"
                                                    name="durasi_pgt" min="1" readonly>
                                            </div>
                                            <div class="form-text text-muted">
                                                <i class="fas fa-info-circle me-1"></i>Otomatis dihitung dari tanggal
                                                pengingat ke tanggal akhir kontrak
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
                                    <!-- Status Surat Kontrak -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_sts_srt_ktr" class="form-label fw-bold">Status Surat Kontrak
                                                <span class="text-danger" id="required_sts_srt">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                <div style="flex: 1">
                                                    <select class="form-select select2" id="modal_sts_srt_ktr"
                                                        name="sts_srt_ktr">
                                                        <option value="">Pilih Status</option>
                                                        <option value="AKTIF">AKTIF</option>
                                                        <option value="NON-AKTIF">NON-AKTIF</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- File Dokumen Kontrak -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_file_doc_ktr" class="form-label fw-bold">File Dokumen
                                                Kontrak</label>
                                            <input type="file" class="form-control" id="modal_file_doc_ktr"
                                                name="file_doc_ktr" accept=".pdf,.doc,.docx,.jpg,.png">
                                            <div class="form-text text-muted">Format: PDF, DOC, DOCX, JPG, PNG</div>
                                            <!-- Show existing file -->
                                            <div id="existing_file_info" style="display: none;" class="mt-2">
                                                <small class="text-success">
                                                    <i class="fas fa-file-check me-1"></i>
                                                    File sudah ada: <a href="#" id="existing_file_link"
                                                        target="_blank">Lihat Dokumen</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tanggal Status NA -->
                                    <div class="col-md-6" id="modal_field_tgl_na" style="display: none;">
                                        <div class="form-group mb-3">
                                            <label for="modal_tgl_sr_na" class="form-label fw-bold">Tanggal Status Non
                                                Aktif</label>
                                            <input type="date" class="form-control" id="modal_tgl_sr_na"
                                                name="tgl_sr_na">
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="modal_field_ket_na" style="display: none;">
                                        <div class="form-group mb-3">
                                            <label for="modal_ket_sr_na" class="form-label fw-bold">Keterangan Non
                                                Aktif</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-comment-alt"></i></span>
                                                <textarea class="form-control auto-uppercase" id="modal_ket_sr_na" name="ket_sr_na" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" id="contractModalFooter">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <span id="closeButtonText">Batal</span>
                    </button>
                    <button type="button" class="btn btn-primary" id="saveContractBtn">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                    <button type="button" class="btn btn-warning" id="editContractBtn" style="display: none;">
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteContractModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kontrak ini?</p>
                    <p class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteContract">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteAllContractsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus Semua Kontrak</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan!</strong> Tindakan ini akan menghapus <strong>SEMUA</strong> kontrak karyawan.
                    </div>
                    <p>Apakah Anda yakin ingin menghapus <strong>semua data kontrak</strong> untuk karyawan
                        <strong>{{ $dataKontrak->karyawan->nama ?? 'N/A' }}</strong>?
                    </p>
                    <p class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Ini akan menghapus seluruh riwayat kontrak, pendidikan, dan karir karyawan tersebut.
                        Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteAllContractsForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="confirmDeleteAllBtn">
                            <i class="fas fa-trash me-1"></i>Hapus Semua Kontrak
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

        /* Table styling */
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

        /* Badge styling */
        .badge {
            font-size: 0.75rem;
        }

        /* Opacity for disabled fields */
        .opacity-50 {
            opacity: 0.5;
        }

        /* Contract status summary badges */
        .contract-status-summary {
            margin-bottom: 1rem;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            border-left: 4px solid #0d6efd;
        }

        .contract-status-summary .badge {
            font-size: 0.9rem !important;
            padding: 0.5em 0.75em;
            margin-right: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* ===== HIGHLIGHT ROWS STYLING (COPIED FROM INDEX) ===== */
        /* ===== HIGHLIGHT ROWS STYLING - UPDATE UNTUK NON-AKTIF ===== */
        table#contractsTable tbody tr.highlight-red {
            background-color: #fc0000 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        table#contractsTable tbody tr.highlight-yellow {
            background-color: #ffff00 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        table#contractsTable tbody tr.highlight-orange {
            background-color: #00e013 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        table#contractsTable tbody tr.highlight-gray {
            background-color: #cccccc !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        /* Hover effects */
        table#contractsTable tbody tr.highlight-red:hover {
            background-color: #ff3333 !important;
        }

        table#contractsTable tbody tr.highlight-yellow:hover {
            background-color: #ffff66 !important;
        }

        table#contractsTable tbody tr.highlight-orange:hover {
            background-color: #33ff33 !important;
        }

        table#contractsTable tbody tr.highlight-gray:hover {
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

        /* Memastikan kolom tabel tetap terlihat meskipun dalam baris yang di-highlight */
        table#contractsTable tbody tr.highlight-red>td,
        table#contractsTable tbody tr.highlight-yellow>td,
        table#contractsTable tbody tr.highlight-orange>td,
        table#contractsTable tbody tr.highlight-gray>td {
            background-color: inherit !important;
        }

        /* Hover effect for table rows */
        #contractsTable tbody tr {
            transition: all 0.2s ease;
        }

        #contractsTable tbody tr:hover {
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

        #contractsTable tbody tr.row-hover-active {
            animation: flashBorder 1s ease infinite;
        }

        /* ===== RESPONSIVE TABLE STYLING ===== */
        #contractsTable {
            font-size: 0.875rem;
        }

        #contractsTable th {
            font-size: 0.8rem;
            white-space: nowrap;
            vertical-align: middle;
        }

        #contractsTable td {
            font-size: 0.8rem;
            vertical-align: middle;
        }

        /* Dropdown responsivity for DataTables */
        .dataTables_wrapper .dataTables_length select {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        /* Show contract modal styling */
        #showContractContent .info-group {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            border-left: 4px solid #0d6efd;
        }

        #showContractContent .info-group h6 {
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        #showContractContent .info-item {
            margin-bottom: 0.5rem;
        }

        #showContractContent .info-label {
            font-weight: 600;
            color: #495057;
            display: inline-block;
            min-width: 150px;
        }

        #showContractContent .info-value {
            color: #212529;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            #contractsTable {
                font-size: 0.75rem;
            }

            #contractsTable th,
            #contractsTable td {
                font-size: 0.7rem;
                padding: 0.25rem;
            }

            .btn-group .btn {
                padding: 0.125rem 0.25rem;
                font-size: 0.7rem;
            }

            .badge {
                font-size: 0.6rem;
                padding: 0.2em 0.4em;
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
    <!-- Moment.js for date calculations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        $(document).ready(function() {
            // Variables for contract management
            let currentContractId = null;
            let currentMode = 'create'; // 'create', 'edit', 'view'

            // ===== ROW HIGHLIGHTING (sama seperti sebelumnya) =====
            function calculateRowWarning(row) {
                const tglPeringatan = row.data('tgl-peringatan');
                const contractStatus = row.data('contract-status');
                const tglAkhir = row.data('tgl-akhir');
                const ktgKtk = row.data('ktg-ktk');

                console.log('Calculating for contract row:', {
                    'tgl-peringatan': tglPeringatan,
                    'contract-status': contractStatus,
                    'tgl-akhir': tglAkhir,
                    'ktg-ktk': ktgKtk
                });

                // Skip NON-AKTIF contracts - Abu-abu
                if (contractStatus === 'NON-AKTIF') {
                    return {
                        text: 'NON-AKTIF',
                        badgeClass: 'bg-secondary',
                        priority: 6,
                        status: 'non_active'
                    };
                }

                // Skip non-active contracts (EXPIRED, PENDING, etc)
                if (contractStatus !== 'AKTIF') {
                    return {
                        text: 'Kontrak Tidak Aktif',
                        badgeClass: 'bg-secondary',
                        priority: 5,
                        status: 'inactive'
                    };
                }

                // PERBAIKAN: Handle kontrak TETAP yang AKTIF
                if (ktgKtk === 'TETAP') {
                    // Kontrak TETAP yang AKTIF = "Tidak Ada Pengingat" tapi TIDAK abu-abu
                    return {
                        text: 'Tidak Ada Pengingat',
                        badgeClass: 'bg-secondary',
                        priority: 5,
                        status: 'tetap_no_reminder' // Status khusus untuk TETAP
                    };
                }

                // If no reminder date for TIDAK TETAP contracts, abu-abu
                if (!tglPeringatan || tglPeringatan === '') {
                    return {
                        text: 'Tidak Ada Pengingat',
                        badgeClass: 'bg-secondary',
                        priority: 5,
                        status: 'no_reminder'
                    };
                }

                // Calculate days difference using moment (untuk TIDAK TETAP dengan pengingat)
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

            function applyContractRowProcessing() {
                let expiredCount = 0;
                let warningCount = 0;

                $('#contractsTable tbody tr').removeClass(
                    'highlight-red highlight-yellow highlight-orange highlight-gray');

                $('#contractsTable tbody tr').each(function() {
                    const row = $(this);
                    const warningData = calculateRowWarning(row);
                    const peringatanCol = row.find('.sisa-peringatan-col');
                    peringatanCol.html('<span>' + warningData
                        .text + '</span>');

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
                            // PERBAIKAN: Kontrak TETAP AKTIF tidak diberi highlighting - tetap putih
                            break;
                        case 'non_active':
                            // Abu-abu untuk NON-AKTIF
                            row.addClass('highlight-gray');
                            break;
                        case 'no_reminder':
                            // Abu-abu untuk TIDAK TETAP tanpa pengingat
                            row.addClass('highlight-gray');
                            break;
                        default:
                            // Inactive contracts - abu-abu
                            row.addClass('highlight-gray');
                            break;
                    }

                    row.data('priority', warningData.priority);
                });

                $('#expiredContractsCount').text(expiredCount);
                $('#warningContractsCount').text(warningCount);

                console.log('Statistics updated - Expired:', expiredCount, 'Warning:', warningCount);
            }

            // ===== DATATABLES INITIALIZATION (sama seperti sebelumnya) =====
            if ($.fn.DataTable.isDataTable('#contractsTable')) {
                $('#contractsTable').DataTable().destroy();
            }

            var contractTable = $('#contractsTable').DataTable({
                responsive: true,
                destroy: true,
                language: {
                    "emptyTable": "Tidak ada data kontrak",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ kontrak",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 kontrak",
                    "infoFiltered": "(disaring dari _MAX_ kontrak keseluruhan)",
                    "lengthMenu": "Tampilkan _MENU_ kontrak",
                    "search": "Cari kontrak:",
                    "zeroRecords": "Tidak ditemukan kontrak yang sesuai",
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
                }, {
                    responsivePriority: 1,
                    targets: [13]
                }, {
                    responsivePriority: 2,
                    targets: [0, 1, 3, 10]
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
                    applyContractRowProcessing();
                }
            });

            // ===== MODAL MODE MANAGEMENT =====
            function setModalMode(mode) {
                currentMode = mode;
                console.log('Setting modal mode to:', mode);

                // Reset modal
                const header = $('#contractModalHeader');
                const modeIndicator = $('#modeIndicator');
                const saveBtn = $('#saveContractBtn');
                const editBtn = $('#editContractBtn');
                const closeText = $('#closeButtonText');
                const auditSection = $('#audit_trail_section');

                // Get all form elements
                const formElements = $('#contractForm input, #contractForm select, #contractForm textarea');
                const requiredSpans = $('.text-danger[id^="required_"]');

                switch (mode) {
                    case 'create':
                        header.removeClass('bg-info bg-warning').addClass('bg-primary');
                        $('#contractModalTitle').text('Tambah Kontrak Baru');
                        modeIndicator.hide();
                        saveBtn.show().html('<i class="fas fa-save me-1"></i>Simpan');
                        editBtn.hide();
                        closeText.text('Batal');
                        auditSection.hide();

                        // Enable all form elements
                        formElements.prop('disabled', false).prop('readonly', false);
                        $('#modal_durasi_ktr, #modal_durasi_pgt').prop('readonly',
                            true); // These should always be readonly
                        requiredSpans.show();
                        break;

                    case 'edit':
                        header.removeClass('bg-primary bg-primary').addClass('bg-primary');
                        $('#contractModalTitle').text('Edit Kontrak');
                        modeIndicator.hide();
                        saveBtn.show().html('<i class="fas fa-save me-1"></i>Update');
                        editBtn.hide();
                        closeText.text('Batal');
                        auditSection.hide();

                        // Enable all form elements
                        formElements.prop('disabled', false).prop('readonly', false);
                        $('#modal_durasi_ktr, #modal_durasi_pgt').prop('readonly', true);
                        requiredSpans.show();
                        break;

                    case 'view':
                        header.removeClass('bg-primary bg-warning').addClass('bg-primary');
                        $('#contractModalTitle').text('Detail Kontrak');
                        modeIndicator.show().find('#modeText').text('Mode Detail - Data hanya dapat dilihat');
                        saveBtn.hide();
                        editBtn.show();
                        closeText.text('Tutup');
                        auditSection.show();

                        // Disable all form elements
                        formElements.prop('disabled', true).prop('readonly', true);
                        $('#modal_file_doc_ktr').prop('disabled', true); // File input juga disabled
                        requiredSpans.hide();
                        break;
                }
            }

            // Function to initialize Select2 in contract modal
            function initializeSelect2InContractModal() {
                $('#contractModal .select2').each(function() {
                    // Destroy existing Select2 instance if any
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }

                    // Initialize Select2 with proper configuration
                    $(this).select2({
                        theme: 'bootstrap-5',
                        dropdownParent: $('#contractModal'),
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

            // Initialize Select2 when modal is opened
            $('#contractModal').on('shown.bs.modal', function() {
                initializeSelect2InContractModal();
            });

            // Cleanup when modal is closed
            $('#contractModal').on('hidden.bs.modal', function() {
                $('#contractModal .select2').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });
            });

            // ===== EVENT HANDLERS =====

            // Add Contract Button
            $('#addContractBtn').on('click', function() {
                console.log('Add contract button clicked');
                resetContractModal();
                setModalMode('create');
                $('#contractModal').modal('show');
            });

            // Show Contract Button
            $(document).on('click', '.show-contract-btn', function() {
                const contractId = $(this).data('contract-id');
                currentContractId = contractId;
                setModalMode('view');
                loadContractDataToModal(contractId);
                $('#contractModal').modal('show');
            });

            // Edit Contract Button
            $(document).on('click', '.edit-contract-btn', function() {
                const contractId = $(this).data('contract-id');
                currentContractId = contractId;
                setModalMode('edit');
                loadContractDataToModal(contractId);
                $('#contractModal').modal('show');
            });

            // Edit button in view mode
            $('#editContractBtn').on('click', function() {
                if (currentContractId) {
                    setModalMode('edit');
                    // Fields are already populated, just need to enable them
                }
            });

            // Delete Contract Button
            $(document).on('click', '.delete-contract-btn', function() {
                currentContractId = $(this).data('contract-id');
                $('#deleteContractModal').modal('show');
            });



            // ===== MODAL FIELD CONDITIONAL LOGIC (sama seperti sebelumnya) =====
            $('#modal_ktg_ktk').on('change', function() {
                // Skip if in view mode
                if (currentMode === 'view') return;

                const ktgKtr = $(this).val();
                const fieldsTanggalAkhir = $('#modal_tgl_akhir_ktr');
                const fieldsDurasi = $('#modal_durasi_ktr');
                const fieldsTanggalPengingat = $('#modal_tgl_pgt_ktr');
                const fieldsDurasiPengingat = $('#modal_durasi_pgt');

                if (ktgKtr === 'TETAP') {
                    fieldsTanggalAkhir.prop('disabled', true).val('').prop('required', false);
                    fieldsDurasi.prop('disabled', true).val('');
                    fieldsTanggalPengingat.prop('disabled', true).val('');
                    fieldsDurasiPengingat.prop('disabled', true).val('');

                    $('#modal_label_tgl_akhir').html(
                        'Tanggal Akhir Kontrak <small class="text-muted">(Tidak Berlaku untuk Kontrak Tetap)</small>'
                    );
                    $('#modal_label_durasi').html(
                        'Durasi Kontrak (Bulan) <small class="text-muted">(Tidak Berlaku)</small>');
                } else if (ktgKtr === 'TIDAK TETAP') {
                    fieldsTanggalAkhir.prop('disabled', false).prop('required', true);
                    fieldsDurasi.prop('disabled', false);
                    fieldsTanggalPengingat.prop('disabled', false);
                    fieldsDurasiPengingat.prop('disabled', false);

                    $('#modal_label_tgl_akhir').html(
                        'Tanggal Akhir Kontrak <span class="text-danger">*</span>');
                    $('#modal_label_durasi').html('Durasi Kontrak (Bulan)');
                } else {
                    fieldsTanggalAkhir.prop('disabled', false).prop('required', false);
                    fieldsDurasi.prop('disabled', false);
                    fieldsTanggalPengingat.prop('disabled', false);
                    fieldsDurasiPengingat.prop('disabled', false);

                    $('#modal_label_tgl_akhir').html('Tanggal Akhir Kontrak');
                    $('#modal_label_durasi').html('Durasi Kontrak (Bulan)');
                }
            });

            $('#modal_sts_srt_ktr').on('change', function() {
                // Skip if in view mode
                if (currentMode === 'view') return;

                const stsSrtKtr = $(this).val();

                if (stsSrtKtr === 'NON-AKTIF') {
                    $('#modal_field_tgl_na, #modal_field_ket_na').show();
                } else {
                    $('#modal_field_tgl_na, #modal_field_ket_na').hide();
                    $('#modal_tgl_sr_na, #modal_ket_sr_na').val('');
                }
            });

            // Duration calculations (sama seperti sebelumnya tapi tambahkan check mode)
            $('#modal_tgl_awl_ktr, #modal_tgl_akhir_ktr').on('change', function() {
                if (currentMode === 'view') return;
                calculateModalContractDuration();
            });

            $('#modal_tgl_pgt_ktr').on('change', function() {
                if (currentMode === 'view') return;
                calculateModalReminderDuration();
            });

            function calculateModalContractDuration() {
                if ($('#modal_ktg_ktk').val() === 'TETAP') return;

                const startDate = $('#modal_tgl_awl_ktr').val();
                const endDate = $('#modal_tgl_akhir_ktr').val();

                if (startDate && endDate) {
                    const start = new Date(startDate);
                    const end = new Date(endDate);
                    if (end > start) {
                        const months = (end.getFullYear() - start.getFullYear()) * 12 + (end.getMonth() - start
                            .getMonth());
                        $('#modal_durasi_ktr').val(months > 0 ? months : '');
                    } else {
                        $('#modal_durasi_ktr').val('');
                    }
                }
            }

            function calculateModalReminderDuration() {
                const reminderDate = $('#modal_tgl_pgt_ktr').val();
                const endDate = $('#modal_tgl_akhir_ktr').val();

                if (reminderDate && endDate) {
                    const reminder = new Date(reminderDate);
                    const end = new Date(endDate);
                    if (reminder < end) {
                        const timeDiff = end.getTime() - reminder.getTime();
                        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                        $('#modal_durasi_pgt').val(daysDiff > 0 ? daysDiff : '');
                    } else {
                        $('#modal_durasi_pgt').val('');
                    }
                } else {
                    $('#modal_durasi_pgt').val('');
                }
            }

            // ===== SAVE CONTRACT (sama seperti sebelumnya tapi tambahkan check mode) =====
            $('#saveContractBtn').on('click', function() {
                if (currentMode === 'view') return; // Safety check

                const formData = new FormData($('#contractForm')[0]);

                // Validate required fields
                const requiredFields = ['id_ktr', 'id_prsh', 'tgl_awl_ktr', 'sts_srt_ktr', 'ktg_ktk'];
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
                        text: 'Field berikut harus diisi: ' + missingFields.join(', '),
                        icon: 'warning'
                    });
                    return;
                }

                if (currentMode === 'edit' && currentContractId) {
                    formData.append('_method', 'PUT');
                }

                const url = currentMode === 'edit' ?
                    `/data-kontrak/contracts/${currentContractId}` :
                    '{{ route('data-kontrak.contracts.store') }}';

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
                        $('#saveContractBtn').prop('disabled', true).text('Menyimpan...');
                    },
                    success: function(response) {
                        $('#saveContractBtn').prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i>Simpan');

                        if (response.success) {
                            $('#contractModal').modal('hide');
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
                        $('#saveContractBtn').prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i>Simpan');

                        // Handle errors (sama seperti sebelumnya)
                        if (xhr.status === 400 && xhr.responseJSON?.message) {
                            const errorData = xhr.responseJSON;
                            let errorMessage = errorData.message;
                            if (errorData.existing_contract) {
                                errorMessage += '\n\nKontrak aktif yang sudah ada:';
                                errorMessage += '\n- No. Surat: ' + (errorData.existing_contract
                                    .no_srt_ktr || 'N/A');
                                errorMessage += '\n- Mulai: ' + (errorData.existing_contract
                                    .tgl_awl_ktr || 'N/A');
                                errorMessage += '\n- Berakhir: ' + (errorData.existing_contract
                                    .tgl_akhir_ktr || 'N/A');
                            }
                            Swal.fire({
                                title: 'Validasi Error!',
                                text: errorMessage,
                                icon: 'warning'
                            });
                        } else if (xhr.status === 422) {
                            const errors = xhr.responseJSON?.errors || {};
                            let errorMessage = 'Terjadi kesalahan validasi:\n';
                            Object.keys(errors).forEach(key => {
                                errorMessage += `- ${key}: ${errors[key].join(', ')}\n`;
                            });
                            Swal.fire({
                                title: 'Error Validasi!',
                                text: errorMessage,
                                icon: 'error'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan: ' + (xhr.responseJSON
                                    ?.message || 'Unknown error'),
                                icon: 'error'
                            });
                        }
                    }
                });
            });

            // ===== HELPER FUNCTIONS =====
            function resetContractModal() {
                $('#contractForm')[0].reset();
                $('#contract_id').val('');
                $('#modal_field_tgl_na, #modal_field_ket_na').hide();
                $('#existing_file_info').hide();
                currentContractId = null;
            }

            function loadContractDataToModal(contractId) {
                console.log('Loading contract data for ID:', contractId);

                $.ajax({
                    url: `/data-kontrak/contracts/${contractId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const contract = response.data;

                            // Populate all fields
                            $('#contract_id').val(contract.id);
                            $('#modal_no_srt_ktr').val(contract.no_srt_ktr || '');
                            $('#modal_tgl_srt_ktr').val(contract.tgl_srt_ktr || '');
                            $('#modal_id_ktr').val(contract.id_ktr || '');
                            $('#modal_id_prsh').val(contract.id_prsh || '');
                            $('#modal_tgl_awl_ktr').val(contract.tgl_awl_ktr || '');
                            $('#modal_tgl_akhir_ktr').val(contract.tgl_akhir_ktr || '');
                            $('#modal_ktg_ktk').val(contract.ktg_ktk || '');
                            $('#modal_tgl_pgt_ktr').val(contract.tgl_pgt_ktr || '');
                            $('#modal_sts_srt_ktr').val(contract.sts_srt_ktr || '');
                            $('#modal_tgl_sr_na').val(contract.tgl_sr_na || '');
                            $('#modal_ket_sr_na').val(contract.ket_sr_na || '');
                            $('#modal_durasi_ktr').val(contract.durasi_ktr || '');
                            $('#modal_durasi_pgt').val(contract.durasi_pgt || '');

                            // Show existing file info
                            if (contract.file_doc_ktr) {
                                $('#existing_file_info').show();
                                $('#existing_file_link').attr('href', '/storage/' + contract
                                    .file_doc_ktr);
                            } else {
                                $('#existing_file_info').hide();
                            }

                            // Populate audit trail (for view mode)
                            $('#created_by_display').val(contract.creator?.nama_kry || '-');
                            $('#created_at_display').val(contract.created_at ? moment(contract
                                .created_at).format('DD/MM/YY HH:mm') : '-');
                            $('#updated_by_display').val(contract.updater?.nama_kry || '-');
                            $('#updated_at_display').val(contract.updated_at ? moment(contract
                                .updated_at).format('DD/MM/YY HH:mm') : '-');

                            // Trigger changes for conditional fields
                            $('#modal_ktg_ktk').trigger('change');
                            $('#modal_sts_srt_ktr').trigger('change');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal memuat data kontrak: ' + (xhr.responseJSON?.message ||
                                'Network error'),
                            icon: 'error'
                        });
                    }
                });
            }

            // ===== DELETE CONTRACT (sama seperti sebelumnya) =====
            $('#confirmDeleteContract').on('click', function() {
                if (!currentContractId) return;

                $.ajax({
                    url: `/data-kontrak/contracts/${currentContractId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#deleteContractModal').modal('hide');
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
                        $('#deleteContractModal').modal('hide');
                        if (xhr.status === 400 && xhr.responseJSON?.message) {
                            Swal.fire({
                                title: 'Tidak Dapat Menghapus!',
                                text: xhr.responseJSON.message,
                                icon: 'warning'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Gagal menghapus kontrak.',
                                icon: 'error'
                            });
                        }
                    }
                });
            });

            $('#deleteAllContractsBtn').on('click', function() {
                const employeeId = $(this).data('employee-id');
                const employeeName = $(this).data('employee-name');

                // Set form action URL
                const deleteUrl = `/data-kontrak/${employeeId}`;
                $('#deleteAllContractsForm').attr('action', deleteUrl);

                // Update employee name in modal
                $('#deleteAllContractsModal .modal-body strong:last').text(employeeName);

                // Show modal
                $('#deleteAllContractsModal').modal('show');
            });

            $('#deleteAllContractsForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const actionUrl = form.attr('action');

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda benar-benar yakin? Semua data kontrak akan dihapus permanen!',
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
                                $('#deleteAllContractsModal').modal('hide');
                                Swal.fire({
                                    title: 'Berhasil Dihapus!',
                                    text: 'Semua data kontrak karyawan telah dihapus.',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('data-kontrak.index') }}";
                                });
                            },
                            error: function(xhr) {
                                $('#deleteAllContractsModal').modal('hide');
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

            // Modal reset when hidden
            $('#contractModal').on('hidden.bs.modal', function() {
                resetContractModal();
            });

            // Apply initial processing
            setTimeout(function() {
                applyContractRowProcessing();
            }, 1000);

            console.log('Enhanced unified contract modal initialized!');
        });
    </script>
@endpush
