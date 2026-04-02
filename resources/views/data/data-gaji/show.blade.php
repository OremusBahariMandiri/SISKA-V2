@extends('layouts.app')

@section('title', 'Detail Data Gaji')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-eye me-2"></i>Detail Data Gaji</span>
                        <div>
                            @if (isset($userPermissions['ubah']) && $userPermissions['ubah'])
                                <a href="{{ route('data-gaji.edit', $dataGaji->id) }}" class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            @endif
                            <a href="{{ route('data-gaji.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Nav tabs for different sections -->
                        <ul class="nav nav-tabs mb-4" id="detailTabs" role="tablist">
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
                                    <span class="badge bg-primary ms-1">{{ $allGajis->count() }}</span>
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
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="sistem-tab" data-bs-toggle="tab" data-bs-target="#sistem"
                                    type="button" role="tab">
                                    <i class="fas fa-cog me-1"></i> Info Sistem
                                </button>
                            </li>
                        </ul>

                        <!-- Tab content -->
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
                                        @if ($dataGaji->karyawan)
                                            <div class="row">
                                                <!-- Foto Karyawan -->
                                                <div class="col-md-3 text-center mb-4">
                                                    <div class="employee-photo-container">
                                                        @if($dataGaji->karyawan->foto_dokumen)
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
                                                            <label class="form-label fw-bold">Nama Lengkap</label>
                                                            <input type="text" class="form-control fw-bold"
                                                                value="{{ $dataGaji->karyawan->nama ?? '' }}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">NIK</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->nik ?? '' }}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">NRK</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->nrk ?? '' }}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">Jenis Kelamin</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->sex ?? '' }}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">Tempat, Tanggal Lahir</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->tpt_lahir ?? '' }}{{ $dataGaji->karyawan->tgl_lahir ? ', ' . $dataGaji->karyawan->tgl_lahir->format('d-m-Y') : '' }}"
                                                                readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">Telepon</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->tlp1 ?? '' }}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">Status Kawin</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->sts_nikah ?? '' }}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">Jumlah Anak</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->jml_anak ?? '' }}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">Email</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataGaji->karyawan->email1 ?? '' }}" readonly>
                                                        </div>
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

                            <!-- Tab 2: Data Gaji (Display All Salaries) -->
                            <div class="tab-pane fade" id="gaji" role="tabpanel">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-money-bill-wave me-2"></i>Riwayat Gaji Karyawan
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="salariesTable">
                                                <thead class="table-info">
                                                    <tr>
                                                        <th width="3%">No</th>
                                                        <th width="10%">ID Gaji</th>
                                                        <th width="12%">Gaji Pokok</th>
                                                        <th width="12%">Total Pendapatan</th>
                                                        <th width="12%">Total Potongan</th>
                                                        <th width="12%">Gaji Bersih</th>
                                                        <th width="8%">Status</th>
                                                        <th width="9%">Create</th>
                                                        <th width="9%">Update</th>
                                                        <th width="4%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($allGajis as $index => $salary)
                                                        <tr>
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
                                                                <small class="d-block">{{ $salary->creator ? $salary->creator->nama_kry : '-' }}</small>
                                                                <small class="text-muted">{{ $salary->created_at ? $salary->created_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <small class="d-block">{{ $salary->updater ? $salary->updater->nama_kry : '-' }}</small>
                                                                <small class="text-muted">{{ $salary->updated_at ? $salary->updated_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-sm btn-info view-salary-btn"
                                                                    data-salary-id="{{ $salary->id }}"
                                                                    data-bs-toggle="tooltip" title="Lihat Detail">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="10" class="text-center text-muted">
                                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                                <br>Tidak ada data gaji
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
                            <div class="tab-pane fade" id="pendidikan" role="tabpanel">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-graduation-cap me-2"></i>Pendidikan Terakhir
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($dataGaji->karyawan)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Jenjang Pendidikan</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="fas fa-level-up-alt"></i></span>
                                                        <input type="text" class="form-control fw-bold text-primary"
                                                            value="{{ $dataGaji->karyawan->jenjang_skl ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Tanggal Lulus</label>
                                                    <input type="text" class="form-control mb-3"
                                                        value="{{ $dataGaji->karyawan->tgl_lulus_skl ? $dataGaji->karyawan->tgl_lulus_skl->format('d-m-Y') : '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Nama Institusi</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataGaji->karyawan->institusi_skl ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Fakultas</label>
                                                    <input type="text" class="form-control mb-3"
                                                        value="{{ $dataGaji->karyawan->fakultas_skl ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Jurusan</label>
                                                    <input type="text" class="form-control mb-3"
                                                        value="{{ $dataGaji->karyawan->jurusan_skl ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Gelar</label>
                                                    <input type="text" class="form-control mb-3"
                                                        value="{{ $dataGaji->karyawan->gelar_skl ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Data pendidikan tidak tersedia.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 4: Jenjang Karir -->
                            <div class="tab-pane fade" id="karir" role="tabpanel">
                                <div class="card border-warning mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-briefcase me-2"></i>Jenjang Karir
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($dataGaji->karyawan)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Departemen</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                                                        <input type="text" class="form-control fw-bold"
                                                            value="{{ $dataGaji->karyawan->departemenRelation->nama_dep ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Jabatan</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                        <input type="text" class="form-control fw-bold"
                                                            value="{{ $dataGaji->karyawan->departemenRelation->nama_jbt ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">Wilayah Kerja</label>
                                                    <input type="text" class="form-control mb-3"
                                                        value="{{ $dataGaji->karyawan->wilayahKerjaRelation->wilayah_krj ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">Unit Kerja</label>
                                                    <input type="text" class="form-control mb-3"
                                                        value="{{ $dataGaji->karyawan->unitKerjaRelation->area_krj ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">SKT Wilayah</label>
                                                    <input type="text" class="form-control mb-3"
                                                        value="{{ $dataGaji->karyawan->skt_wil_krj ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">Tugas & Tanggung Jawab</label>
                                                    <textarea class="form-control" rows="4" readonly>{{ $dataGaji->karyawan->tugas ?? '-' }}</textarea>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Data karir tidak tersedia.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 5: Hubungan Industrial -->
                            <div class="tab-pane fade" id="hubin" role="tabpanel">
                                <div class="card border-info mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-user-check me-2"></i>Hubungan Industrial
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($dataGaji->karyawan)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold text-muted">Tanggal Masuk</label>
                                                    <p class="form-control-plaintext fw-bold text-success">
                                                        {{ $dataGaji->karyawan->tgl_masuk ? $dataGaji->karyawan->tgl_masuk->format('d-m-Y') : '-' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold text-muted">Status Karyawan</label>
                                                    <p class="form-control-plaintext">
                                                        @if ($dataGaji->karyawan->sts_kry == 'AKTIF')
                                                            <span class="badge bg-success fs-6">{{ $dataGaji->karyawan->sts_kry }}</span>
                                                        @elseif($dataGaji->karyawan->sts_kry == 'NON-AKTIF')
                                                            <span class="badge bg-danger fs-6">{{ $dataGaji->karyawan->sts_kry }}</span>
                                                        @else
                                                            <span class="badge bg-secondary fs-6">{{ $dataGaji->karyawan->sts_kry ?? '-' }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold text-muted">Tanggal PHK</label>
                                                    <p class="form-control-plaintext">
                                                        {{ $dataGaji->karyawan->tgl_phk ? $dataGaji->karyawan->tgl_phk->format('d-m-Y') : '-' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold text-muted">Keterangan PHK</label>
                                                    <p class="form-control-plaintext">{{ $dataGaji->karyawan->ket_phk ?? '-' }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Data hubungan industrial tidak tersedia.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 6: Info Sistem -->
                            <div class="tab-pane fade" id="sistem" role="tabpanel">
                                <div class="card border-secondary mb-4">
                                    <div class="card-header bg-secondary bg-opacity-25">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-cog me-2"></i>Informasi Sistem
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-muted">ID Data Gaji</label>
                                                <p class="form-control-plaintext font-monospace">{{ $dataGaji->id_gaji ?? $dataGaji->id }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-muted">ID Karyawan</label>
                                                <p class="form-control-plaintext font-monospace">{{ $dataGaji->id_karyawan ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-muted">Dibuat Pada</label>
                                                <p class="form-control-plaintext">
                                                    {{ $dataGaji->created_at ? $dataGaji->created_at->format('d-m-Y H:i:s') : '-' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-muted">Dibuat Oleh</label>
                                                <p class="form-control-plaintext">{{ $dataGaji->creator->nama_kry ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-muted">Diperbarui Pada</label>
                                                <p class="form-control-plaintext">
                                                    {{ $dataGaji->updated_at ? $dataGaji->updated_at->format('d-m-Y H:i:s') : '-' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-muted">Diperbarui Oleh</label>
                                                <p class="form-control-plaintext">{{ $dataGaji->updater->nama_kry ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action buttons at the bottom -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('data-gaji.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                            <div>
                                @if (auth()->user()->is_admin || (isset($userPermissions['hapus']) && $userPermissions['hapus']))
                                    <button type="button" class="btn btn-danger" id="deleteAllSalariesBtn"
                                        data-employee-id="{{ $dataGaji->id }}"
                                        data-employee-name="{{ $dataGaji->karyawan->nama ?? 'N/A' }}">
                                        <i class="fas fa-trash me-1"></i> Hapus Semua Data Gaji
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary Detail View Modal -->
    <div class="modal fade" id="salaryDetailModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-money-bill-wave me-2"></i>Detail Data Gaji
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="salaryDetailContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat detail gaji...</p>
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

    <!-- Delete All Salaries Modal -->
    <div class="modal fade" id="deleteAllSalariesModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus Semua Data Gaji
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <p>Apakah Anda yakin ingin menghapus <strong>semua data gaji</strong> untuk karyawan:</p>
                    <p class="fs-5 fw-bold text-danger" id="deleteEmployeeName">-</p>
                    <p class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Ini akan menghapus seluruh riwayat gaji karyawan tersebut.
                        Data yang sudah dihapus <strong>tidak dapat dikembalikan</strong>.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteAllBtn">
                        <i class="fas fa-trash me-1"></i>Hapus Semua Data Gaji
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card-header { font-weight: 600; }
        .form-control-plaintext {
            background: none;
            border: none;
            padding: 0.375rem 0;
            font-size: 0.95rem;
            color: #212529 !important;
        }
        .form-label { margin-bottom: 0.3rem; font-size: 0.85rem; }
        .card { margin-bottom: 1rem; transition: all 0.3s; }
        .card:hover { box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); }
        .badge { font-size: 0.8rem; }
        .badge.fs-6 { font-size: 0.9rem !important; }
        .font-monospace {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
            font-size: 0.9rem;
            background-color: #f8f9fa;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
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
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // CSRF Token
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // VIEW SALARY DETAIL
            $(document).on('click', '.view-salary-btn', function() {
                const salaryId = $(this).data('salary-id');
                $('#salaryDetailModal').modal('show');
                $('#salaryDetailContent').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary"></div>
                        <p class="mt-2">Memuat detail gaji...</p>
                    </div>
                `);

                $.ajax({
                    url: `/data-gaji/salaries/${salaryId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            displaySalaryDetail(response.data);
                        }
                    },
                    error: function() {
                        $('#salaryDetailContent').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Terjadi kesalahan saat memuat detail gaji.
                            </div>
                        `);
                    }
                });
            });

            // DELETE ALL SALARIES
            $('#deleteAllSalariesBtn').on('click', function() {
                const employeeId = $(this).data('employee-id');
                const employeeName = $(this).data('employee-name');
                $('#confirmDeleteAllBtn').data('employee-id', employeeId);
                $('#deleteEmployeeName').text(employeeName);
                $('#deleteAllSalariesModal').modal('show');
            });

            $('#confirmDeleteAllBtn').on('click', function() {
                const employeeId = $(this).data('employee-id');
                $('#deleteAllSalariesModal').modal('hide');

                Swal.fire({
                    title: 'Konfirmasi Akhir',
                    html: 'Data yang dihapus <strong>tidak dapat dikembalikan</strong>.<br>Lanjutkan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/data-gaji/${employeeId}`,
                            type: 'POST',
                            data: { _method: 'DELETE' },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil Dihapus!',
                                    text: 'Semua data gaji telah dihapus.',
                                    icon: 'success',
                                    timer: 2000
                                }).then(() => window.location.href = "{{ route('data-gaji.index') }}");
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON?.message || 'Gagal menghapus data.', 'error');
                            }
                        });
                    }
                });
            });

            // DISPLAY SALARY DETAIL
            function displaySalaryDetail(salary) {
                const html = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-success mb-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-wallet me-2"></i>Pendapatan Tetap</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr><td>Gaji Pokok</td><td class="text-end">Rp ${formatRupiah(salary.gj_pokok)}</td></tr>
                                        <tr><td>Tunjangan Jabatan</td><td class="text-end">Rp ${formatRupiah(salary.tunjab)}</td></tr>
                                        <tr><td>Tunjangan Komunikasi</td><td class="text-end">Rp ${formatRupiah(salary.tunkom)}</td></tr>
                                        <tr><td>FOT</td><td class="text-end">Rp ${formatRupiah(salary.fot)}</td></tr>
                                        <tr><td>Tunjangan Kemahalan</td><td class="text-end">Rp ${formatRupiah(salary.tunmal)}</td></tr>
                                        <tr class="fw-bold"><td>Total</td><td class="text-end text-success">Rp ${formatRupiah(salary.ttl_pendapatan_ttp)}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-warning mb-3">
                                <div class="card-header bg-warning">
                                    <h6 class="mb-0"><i class="fas fa-coins me-2"></i>Pendapatan Tidak Tetap</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr><td>Lembur Harian</td><td class="text-end">Rp ${formatRupiah(salary.lbr_harian)}</td></tr>
                                        <tr><td>Lembur Per Jam</td><td class="text-end">Rp ${formatRupiah(salary.lbr_perjam)}</td></tr>
                                        <tr><td>Tunjangan Kinerja</td><td class="text-end">Rp ${formatRupiah(salary.tukin)}</td></tr>
                                        <tr><td>Insentif</td><td class="text-end">Rp ${formatRupiah(salary.insentif)}</td></tr>
                                        <tr><td>Bonus</td><td class="text-end">Rp ${formatRupiah(salary.bonus)}</td></tr>
                                        <tr><td>THR</td><td class="text-end">Rp ${formatRupiah(salary.thr)}</td></tr>
                                        <tr class="fw-bold"><td>Total</td><td class="text-end text-warning">Rp ${formatRupiah(salary.ttl_pendapatan_tdk_ttp)}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card border-danger mb-3">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0"><i class="fas fa-minus-circle me-2"></i>Potongan</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr><td>BPJS TK</td><td class="text-end">Rp ${formatRupiah(salary.bpjs_tkj)}</td></tr>
                                                <tr><td>BPJS Kesehatan</td><td class="text-end">Rp ${formatRupiah(salary.bpjs_kes)}</td></tr>
                                                <tr><td>Iuran Koperasi</td><td class="text-end">Rp ${formatRupiah(salary.iuran_koperasi)}</td></tr>
                                                <tr><td>Tabungan Pensiun</td><td class="text-end">Rp ${formatRupiah(salary.tps_kry)}</td></tr>
                                                <tr><td>Pajak PKP</td><td class="text-end">Rp ${formatRupiah(salary.pjk_pkp)}</td></tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr><td>Pajak PPh</td><td class="text-end">Rp ${formatRupiah(salary.pjk_pph)}</td></tr>
                                                <tr><td>Potongan THR</td><td class="text-end">Rp ${formatRupiah(salary.ptg_thr)}</td></tr>
                                                <tr><td>Pinjaman Koperasi</td><td class="text-end">Rp ${formatRupiah(salary.pjm_kop)}</td></tr>
                                                <tr><td>Denda Sanksi</td><td class="text-end">Rp ${formatRupiah(salary.dda_sanksi)}</td></tr>
                                                <tr class="fw-bold"><td>Total Potongan</td><td class="text-end text-danger">Rp ${formatRupiah(salary.ttl_potongan)}</td></tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Ringkasan</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr class="fw-bold fs-5">
                                            <td>Total Pendapatan</td>
                                            <td class="text-end text-success">Rp ${formatRupiah(salary.ttl_pendapatan)}</td>
                                        </tr>
                                        <tr class="fw-bold fs-5">
                                            <td>Total Potongan</td>
                                            <td class="text-end text-danger">Rp ${formatRupiah(salary.ttl_potongan)}</td>
                                        </tr>
                                        <tr class="fw-bold fs-4 border-top border-2">
                                            <td>Gaji Bersih (Take Home Pay)</td>
                                            <td class="text-end text-primary">Rp ${formatRupiah(salary.ttl_terima_gaji)}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#salaryDetailContent').html(html);
            }

            function formatRupiah(num) {
                return new Intl.NumberFormat('id-ID').format(Math.round(num || 0));
            }
        });
    </script>
@endpush