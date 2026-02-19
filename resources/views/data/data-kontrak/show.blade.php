@extends('layouts.app')

@section('title', 'Detail Data Kontrak')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-eye me-2"></i>Detail Data Kontrak</span>
                        <div>
                            @if (isset($userPermissions['ubah']) && $userPermissions['ubah'])
                                <a href="{{ route('data-kontrak.edit', $dataKontrak->id) }}"
                                    class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            @endif
                            <a href="{{ route('data-kontrak.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Nav tabs for different sections -->
                        <ul class="nav nav-tabs mb-4" id="detailTabs" role="tablist">
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
                                    <span class="badge bg-primary ms-1">{{ $allContracts->count() }}</span>
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
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="sistem-tab" data-bs-toggle="tab" data-bs-target="#sistem"
                                    type="button" role="tab" aria-controls="sistem" aria-selected="false">
                                    <i class="fas fa-cog me-1"></i> Info Sistem
                                </button>
                            </li>
                        </ul>

                        <!-- Tab content -->
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
                                        @if ($dataKontrak->karyawan)
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Nama Lengkap</label>
                                                        <input type="text" class="form-control fw-bold"
                                                            value="{{ $dataKontrak->karyawan->nama ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">NIK</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataKontrak->karyawan->nik ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">NRK</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataKontrak->karyawan->nrk ?? '' }}" readonly>
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
                                                            value="{{ $dataKontrak->karyawan->sex ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Telepon</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataKontrak->karyawan->tlp1 ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Email</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataKontrak->karyawan->email1 ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Status Kawin</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataKontrak->karyawan->sts_nikah ?? '' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Jumlah Anak</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataKontrak->karyawan->jml_anak ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Data karyawan tidak ditemukan atau telah dihapus.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Data Kontrak (Display All Contracts) -->
                            <div class="tab-pane fade" id="kontrak" role="tabpanel" aria-labelledby="kontrak-tab">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-file-contract me-2"></i>Riwayat Kontrak Karyawan
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="contractsTable">
                                                <thead class="table-info">
                                                    <tr>
                                                        <th width="4%">No</th>
                                                        <th width="14%">No. Surat</th>
                                                        <th width="10%">Tgl Surat</th>
                                                        <th width="13%">Jenis Kontrak</th>
                                                        <th width="10%">Tgl Mulai</th>
                                                        <th width="10%">Tgl Akhir</th>
                                                        <th width="8%">Durasi</th>
                                                        <th width="9%">Status</th>
                                                        <th width="9%">Create</th>
                                                        <th width="9%">Update</th>
                                                        <th width="4%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="contractsTableBody">
                                                    @forelse ($allContracts as $index => $contract)
                                                        <tr data-contract-id="{{ $contract->id }}">
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $contract->no_srt_ktr ?: '-' }}</td>
                                                            <td>{{ $contract->tgl_srt_ktr ? \Carbon\Carbon::parse($contract->tgl_srt_ktr)->format('d-m-Y') : '-' }}
                                                            </td>
                                                            <td>
                                                                @if ($contract->kontrakKerja)
                                                                    <span
                                                                        class="badge bg-info">{{ $contract->kontrakKerja->singkatan_ktr }}</span>
                                                                @else
                                                                    <span class="badge bg-secondary">-</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $contract->tgl_awl_ktr ? \Carbon\Carbon::parse($contract->tgl_awl_ktr)->format('d-m-Y') : '-' }}
                                                            </td>
                                                            <td>{{ $contract->tgl_akhir_ktr ? \Carbon\Carbon::parse($contract->tgl_akhir_ktr)->format('d-m-Y') : '-' }}
                                                            </td>
                                                            <td>{{ $contract->durasi_ktr ? $contract->durasi_ktr . ' bulan' : '-' }}
                                                            </td>
                                                            <td>
                                                                @if ($contract->sts_srt_ktr == 'AKTIF')
                                                                    <span
                                                                        class="badge bg-success">{{ $contract->sts_srt_ktr }}</span>
                                                                @elseif($contract->sts_srt_ktr == 'NON-AKTIF')
                                                                    <span
                                                                        class="badge bg-secondary">{{ $contract->sts_srt_ktr }}</span>
                                                                @elseif($contract->sts_srt_ktr == 'EXPIRED')
                                                                    <span
                                                                        class="badge bg-danger">{{ $contract->sts_srt_ktr }}</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-warning text-dark">{{ $contract->sts_srt_ktr }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <small
                                                                    class="d-block">{{ $contract->creator ? $contract->creator->nama_kry : '-' }}</small>
                                                                <small
                                                                    class="text-muted">{{ $contract->created_at ? $contract->created_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td>
                                                                <small
                                                                    class="d-block">{{ $contract->updater ? $contract->updater->nama_kry : '-' }}</small>
                                                                <small
                                                                    class="text-muted">{{ $contract->updated_at ? $contract->updated_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-info view-contract-btn"
                                                                    data-contract-id="{{ $contract->id }}"
                                                                    data-bs-toggle="tooltip" title="Lihat Detail">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="11" class="text-center text-muted">
                                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                                <br>Tidak ada data kontrak
                                                            </td>
                                                        </tr>
                                                    @endforelse
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
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-graduation-cap me-2"></i>Pendidikan Terakhir
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jenjang Pendidikan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-level-up-alt"></i></span>
                                                        <input type="text" class="form-control fw-bold text-primary"
                                                            value="{{ $dataKontrak->jenjang_skl ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal Lulus</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->tgl_lulus_skl ? \Carbon\Carbon::parse($dataKontrak->tgl_lulus_skl)->format('d-m-Y') : '' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Nama Institusi</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-university"></i></span>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataKontrak->institusi_skl ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">SKT Institusi</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-certificate"></i></span>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataKontrak->skt_inst_skl ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Fakultas</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-building-columns"></i></span>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataKontrak->fakultas_skl ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jurusan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-book-open"></i></span>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataKontrak->jurusan_skl ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Kota</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-map-marker-alt"></i></span>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataKontrak->kota_skl ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Gelar</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-medal"></i></span>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataKontrak->gelar_skl ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 4: Jenjang Karir -->
                            <div class="tab-pane fade" id="karir" role="tabpanel" aria-labelledby="karir-tab">
                                <div class="card border-warning mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-briefcase me-2"></i>Jenjang Karir
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Departemen</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-sitemap"></i></span>
                                                        <input type="text" class="form-control fw-bold"
                                                            value="{{ $dataKontrak->departemen->nama_dep ?? '' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Singkatan Dep.</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->departemen->singkatan_dep ?? '' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jabatan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-user-tie"></i></span>
                                                        <input type="text" class="form-control fw-bold"
                                                            value="{{ $dataKontrak->departemen->nama_jbt ?? '' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Singkatan Jbt.</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->departemen->singkatan_jbt ?? '' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Wilayah Kerja</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                        <input type="text" class="form-control fw-bold"
                                                            value="{{ $dataKontrak->wilayahKerja->wilayah_krj ?? '' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Area Kerja</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-users-cog"></i></span>
                                                        <input type="text" class="form-control fw-bold"
                                                            value="{{ $dataKontrak->wilayahKerja->area_krj ?? '' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">SKT Wilayah Kerja</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataKontrak->wilayahKerja->singkatan_wk ?? '' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tugas & Tanggung Jawab</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                                                        <textarea class="form-control" rows="4" readonly>{{ $dataKontrak->tugas ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 5: Hubungan Industrial -->
                            <div class="tab-pane fade" id="hubin" role="tabpanel" aria-labelledby="hubin-tab">
                                <div class="card border-info mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-user-check me-2"></i>Hubungan Industrial
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($dataKontrak->karyawan)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-muted">Tanggal Masuk</label>
                                                        <p class="form-control-plaintext fw-bold text-success">
                                                            {{ $dataKontrak->karyawan->tgl_masuk ? $dataKontrak->karyawan->tgl_masuk->format('d-m-Y') : '-' }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-muted">Status Karyawan</label>
                                                        <p class="form-control-plaintext">
                                                            @if ($dataKontrak->karyawan->sts_kry == 'AKTIF')
                                                                <span class="badge bg-success fs-6">{{ $dataKontrak->karyawan->sts_kry }}</span>
                                                            @elseif($dataKontrak->karyawan->sts_kry == 'NON-AKTIF')
                                                                <span class="badge bg-danger fs-6">{{ $dataKontrak->karyawan->sts_kry }}</span>
                                                            @else
                                                                <span class="badge bg-secondary fs-6">{{ $dataKontrak->karyawan->sts_kry ?? '-' }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-muted">Tanggal PHK</label>
                                                        <p class="form-control-plaintext">
                                                            @if ($dataKontrak->karyawan->tgl_phk)
                                                                <span class="text-danger fw-bold">
                                                                    {{ $dataKontrak->karyawan->tgl_phk->format('d-m-Y') }}
                                                                </span>
                                                            @else
                                                                -
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-muted">Keterangan PHK</label>
                                                        <p class="form-control-plaintext">{{ $dataKontrak->karyawan->ket_phk ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Data karyawan tidak tersedia untuk menampilkan informasi hubungan industrial.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 6: Info Sistem -->
                            <div class="tab-pane fade" id="sistem" role="tabpanel" aria-labelledby="sistem-tab">
                                <div class="card border-secondary mb-4">
                                    <div class="card-header bg-secondary bg-opacity-25">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-cog me-2"></i>Informasi Sistem
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">ID Data Kontrak</label>
                                                    <p class="form-control-plaintext font-monospace">{{ $dataKontrak->id_data_ktr ?? $dataKontrak->id }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">ID Pendidikan</label>
                                                    <p class="form-control-plaintext font-monospace">{{ $dataKontrak->id_pendidikan ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">ID Karir</label>
                                                    <p class="form-control-plaintext font-monospace">{{ $dataKontrak->id_karir ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Dibuat Pada</label>
                                                    <p class="form-control-plaintext">
                                                        {{ $dataKontrak->created_at ? $dataKontrak->created_at->format('d-m-Y H:i:s') : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Dibuat Oleh</label>
                                                    <p class="form-control-plaintext">{{ $dataKontrak->creator->nama_kry ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Diperbarui Pada</label>
                                                    <p class="form-control-plaintext">
                                                        {{ $dataKontrak->updated_at ? $dataKontrak->updated_at->format('d-m-Y H:i:s') : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Diperbarui Oleh</label>
                                                    <p class="form-control-plaintext">{{ $dataKontrak->updater->nama_kry ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action buttons at the bottom -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('data-kontrak.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                            <div class="row">
                                <div class="col-12 text-end">
                                    @if (auth()->user()->is_admin || (isset($userPermissions['hapus']) && $userPermissions['hapus']))
                                        <button type="button" class="btn btn-danger" id="deleteAllContractsBtn"
                                            data-employee-id="{{ $dataKontrak->id }}"
                                            data-employee-name="{{ $dataKontrak->karyawan->nama ?? 'N/A' }}">
                                            <i class="fas fa-trash me-1"></i> Hapus Semua Kontrak
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contract Detail View Modal -->
    <div class="modal fade" id="contractDetailModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="contractDetailModalTitle">
                        <i class="fas fa-file-contract me-2"></i>Detail Kontrak
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="contractDetailContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat detail kontrak...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete All Contracts Modal -->
    <div class="modal fade" id="deleteAllContractsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus Semua Kontrak
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <p>Apakah Anda yakin ingin menghapus <strong>semua data kontrak</strong> untuk karyawan:</p>
                    <p class="fs-5 fw-bold text-danger" id="deleteEmployeeName">-</p>
                    <p class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Ini akan menghapus seluruh riwayat kontrak, pendidikan, dan karir karyawan tersebut.
                        Data yang sudah dihapus <strong>tidak dapat dikembalikan</strong>.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteAllBtn">
                        <i class="fas fa-trash me-1"></i>Hapus Semua Kontrak
                    </button>
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

        .form-control-plaintext {
            background: none;
            border: none;
            padding: 0.375rem 0;
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.5;
            color: #212529 !important;
        }

        .form-control-plaintext:focus {
            box-shadow: none;
            outline: none;
        }

        .form-label {
            margin-bottom: 0.3rem;
            font-size: 0.85rem;
        }

        .card {
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .badge {
            font-size: 0.8rem;
        }

        .badge.fs-6 {
            font-size: 0.9rem !important;
        }

        .nav-tabs .nav-link {
            border-radius: 0.5rem 0.5rem 0 0;
            margin-bottom: -1px;
        }

        .nav-tabs .nav-link.active {
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
            font-weight: 600;
        }

        .alert {
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .font-monospace {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
            font-size: 0.9rem;
            background-color: #f8f9fa;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
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

        .modal-xl {
            max-width: 1200px;
        }

        #contractDetailModal .card {
            margin-bottom: 1rem;
        }

        #contractDetailModal .card-header h6 {
            margin: 0;
            font-weight: 600;
        }

        @media print {
            .btn, .nav-tabs {
                display: none !important;
            }
            .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }
            .tab-content > .tab-pane {
                display: block !important;
                opacity: 1 !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <!-- Pastikan jQuery loaded -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 WAJIB ada di halaman ini -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Gunakan $(document).ready() bukan document.addEventListener agar jQuery sudah pasti tersedia
        $(document).ready(function () {
            console.log('✅ Data Kontrak Show page loaded');

            // ===== CSRF Token untuk AJAX =====
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // ===== Initialize tooltips =====
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // ===== VIEW CONTRACT DETAIL =====
            $(document).on('click', '.view-contract-btn', function () {
                const contractId = $(this).data('contract-id');

                $('#contractDetailModal').modal('show');
                $('#contractDetailContent').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat detail kontrak...</p>
                    </div>
                `);

                $.ajax({
                    url: `/data-kontrak/contracts/${contractId}`,
                    type: 'GET',
                    success: function (response) {
                        if (response.success) {
                            displayContractDetail(response.data);
                        } else {
                            showError('Gagal memuat detail kontrak: ' + response.message);
                        }
                    },
                    error: function (xhr) {
                        showError('Terjadi kesalahan saat memuat detail kontrak.');
                    }
                });
            });

            // ===== DELETE ALL CONTRACTS =====
            // Step 1: Klik button -> isi data ke modal -> tampilkan modal
            $('#deleteAllContractsBtn').on('click', function () {
                const employeeId   = $(this).data('employee-id');
                const employeeName = $(this).data('employee-name');

                // Simpan employee id ke button konfirmasi sebagai data attribute
                $('#confirmDeleteAllBtn').data('employee-id', employeeId);

                // Tampilkan nama karyawan di modal
                $('#deleteEmployeeName').text(employeeName);

                // Tampilkan modal
                $('#deleteAllContractsModal').modal('show');
            });

            // Step 2: Klik tombol konfirmasi di modal -> SweetAlert double confirm -> AJAX DELETE
            $('#confirmDeleteAllBtn').on('click', function () {
                const employeeId = $(this).data('employee-id');

                // Tutup modal bootstrap dulu
                $('#deleteAllContractsModal').modal('hide');

                // Double confirm dengan SweetAlert
                Swal.fire({
                    title: 'Konfirmasi Akhir',
                    html: 'Data yang dihapus <strong>tidak dapat dikembalikan</strong>.<br>Lanjutkan hapus semua kontrak?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus Semua!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then(function (result) {
                    if (result.isConfirmed) {
                        // Tampilkan loading
                        Swal.fire({
                            title: 'Menghapus data...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: function () {
                                Swal.showLoading();
                            }
                        });

                        // Kirim DELETE request via AJAX
                        $.ajax({
                            url: '/data-kontrak/' + employeeId,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: 'Berhasil Dihapus!',
                                    text: response.message || 'Semua data kontrak karyawan telah dihapus.',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(function () {
                                    window.location.href = "{{ route('data-kontrak.index') }}";
                                });
                            },
                            error: function (xhr) {
                                var msg = 'Terjadi kesalahan saat menghapus data.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    title: 'Error!',
                                    text: msg,
                                    icon: 'error'
                                });
                            }
                        });
                    } else {
                        // User batal, buka kembali modal bootstrap
                        $('#deleteAllContractsModal').modal('show');
                    }
                });
            });

            // ===== HELPER FUNCTIONS =====
            function displayContractDetail(contract) {
                var contractHtml = `
                    <div class="card border-secondary mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class="fas fa-file-contract me-2"></i>Informasi Umum Kontrak</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">No. Surat Kontrak</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                            <input type="text" class="form-control" value="${contract.no_srt_ktr || '-'}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Tanggal Surat Kontrak</label>
                                        <input type="text" class="form-control" value="${contract.tgl_srt_ktr ? formatDate(contract.tgl_srt_ktr) : '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Jenis Kontrak</label>
                                        <input type="text" class="form-control fw-bold" value="${(contract.kontrak_kerja && contract.kontrak_kerja.nama_ktr) ? contract.kontrak_kerja.nama_ktr : '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Perusahaan</label>
                                        <input type="text" class="form-control fw-bold" value="${(contract.perusahaan && contract.perusahaan.nama_prs1) ? contract.perusahaan.nama_prs1 : '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Keterangan Kontrak</label>
                                        <input type="text" class="form-control ${getKtgKtkClass(contract.ktg_ktk)}" value="${contract.ktg_ktk || '-'}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Periode Kontrak</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Tanggal Mulai</label>
                                        <input type="text" class="form-control fw-bold text-success" value="${contract.tgl_awl_ktr ? formatDate(contract.tgl_awl_ktr) : '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Tanggal Akhir</label>
                                        <input type="text" class="form-control fw-bold text-danger" value="${contract.tgl_akhir_ktr ? formatDate(contract.tgl_akhir_ktr) : '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Durasi (Bulan)</label>
                                        <input type="text" class="form-control" value="${contract.durasi_ktr || '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Tanggal Pengingat</label>
                                        <input type="text" class="form-control" value="${contract.tgl_pgt_ktr ? formatDate(contract.tgl_pgt_ktr) : '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Durasi Pengingat (Hari)</label>
                                        <input type="text" class="form-control" value="${contract.durasi_pgt || '-'}" readonly>
                                    </div>
                                </div>
                            </div>
                            ${generateContractStatusAlert(contract)}
                        </div>
                    </div>
                    <div class="card border-success mb-4">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Status dan Dokumen</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Status Surat Kontrak</label>
                                        <input type="text" class="form-control ${getStatusClass(contract.sts_srt_ktr)}" value="${contract.sts_srt_ktr || '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">File Dokumen</label>
                                        <div class="input-group">
                                            ${contract.file_doc_ktr
                                                ? `<input type="text" class="form-control text-primary" value="Dokumen tersedia" readonly>
                                                   <a href="/storage/${contract.file_doc_ktr}" target="_blank" class="btn btn-outline-primary"><i class="fas fa-download"></i></a>`
                                                : `<input type="text" class="form-control text-muted" value="Tidak ada dokumen" readonly>`
                                            }
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('#contractDetailContent').html(contractHtml);
            }

            function showError(message) {
                $('#contractDetailContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>${message}
                    </div>
                `);
            }

            function formatDate(dateString) {
                if (!dateString) return '-';
                try {
                    var date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
                } catch (e) {
                    return dateString;
                }
            }

            function getKtgKtkClass(ktgKtk) {
                if (ktgKtk === 'TETAP') return 'text-success fw-bold';
                if (ktgKtk === 'TIDAK TETAP') return 'text-warning fw-bold';
                return '';
            }

            function getStatusClass(status) {
                if (status === 'AKTIF') return 'text-success fw-bold';
                if (status === 'NON-AKTIF') return 'text-danger fw-bold';
                return '';
            }

            function generateContractStatusAlert(contract) {
                if (!contract.tgl_awl_ktr || !contract.tgl_akhir_ktr) return '';

                var today   = new Date();
                var endDate = new Date(contract.tgl_akhir_ktr);
                var daysDiff = Math.ceil((endDate - today) / (1000 * 3600 * 24));

                var alertClass  = 'alert-success';
                var statusText  = 'Kontrak masih aktif, berakhir pada ' + formatDate(contract.tgl_akhir_ktr);

                if (daysDiff < 0) {
                    alertClass = 'alert-danger';
                    statusText = 'Kontrak telah berakhir pada ' + formatDate(contract.tgl_akhir_ktr);
                } else if (daysDiff <= 30) {
                    alertClass = 'alert-warning';
                    statusText = 'Kontrak akan berakhir dalam ' + daysDiff + ' hari';
                }

                return `<div class="col-12 mt-2">
                            <div class="alert ${alertClass}">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Status:</strong> ${statusText}
                            </div>
                        </div>`;
            }

            // Auto print jika ada query param
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === '1') {
                setTimeout(function () { window.print(); }, 1000);
            }
        });
    </script>
@endpush