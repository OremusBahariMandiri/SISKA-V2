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
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="3%" class="text-center">No</th>
                                                        <th width="10%" class="text-center">ID Gaji</th>
                                                        <th width="12%" class="text-center">Gaji Pokok</th>
                                                        <th width="12%" class="text-center">Total Pendapatan</th>
                                                        <th width="12%" class="text-center">Total Potongan</th>
                                                        <th width="12%" class="text-center">Gaji Bersih</th>
                                                        <th width="8%" class="text-center">Status</th>
                                                        <th width="9%" class="text-center">Create</th>
                                                        <th width="9%" class="text-center">Update</th>
                                                        <th width="4%" class="text-center">Aksi</th>
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
                                                                <button type="button" class="btn btn-sm btn-info show-salary-btn"
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
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->jenjang_skl ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Tanggal Lulus</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->tgl_lulus_skl ? $dataGaji->karyawan->tgl_lulus_skl->format('d-m-Y') : '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Nama Institusi</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->institusi_skl ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Fakultas</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->fakultas_skl ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Jurusan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->jurusan_skl ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Kota</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->kota_skl ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Gelar</label>
                                                    <input type="text" class="form-control"
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
                                    <div class="card-header bg-warning bg-opacity-25">
                                        <h5 class="mb-0 text-dark">
                                            <i class="fas fa-briefcase me-2"></i>Jenjang Karir Saat Ini
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($dataGaji->karyawan)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Departemen</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->departemenRelation->nama_dep ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Singkatan Dep.</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->skt_dep ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Jabatan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->departemenRelation->nama_jbt ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Singkatan Jbt.</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->skt_jbt ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">Wilayah Kerja</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->wilayahKerjaRelation->wilayah_krj ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">Unit Kerja</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->unitKerjaRelation->area_krj ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">SKT Wilayah Kerja</label>
                                                    <input type="text" class="form-control"
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
                                    <div class="card-header bg-info bg-opacity-25">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-user-check me-2"></i>Hubungan Industrial
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($dataGaji->karyawan)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Tanggal Masuk</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->tgl_masuk ? $dataGaji->karyawan->tgl_masuk->format('d-m-Y') : '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Status Karyawan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->sts_kry ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Tanggal PHK</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->tgl_phk ? $dataGaji->karyawan->tgl_phk->format('d-m-Y') : '-' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Keterangan PHK</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->karyawan->ket_phk ?? '-' }}" readonly>
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

    <!-- Salary Detail Modal (Sama seperti di Edit) -->
    <div class="modal fade" id="salaryModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary" id="salaryModalHeader">
                    <h5 class="modal-title text-white" id="salaryModalTitle">Detail Data Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="salaryForm">
                        <input type="hidden" id="salary_id" name="salary_id">

                        <!-- Mode Indicator -->
                        <div class="alert alert-info" id="modeIndicator">
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
                                                            name="gj_pokok" value="0" min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_tunjab" class="col-sm-5 col-form-label">Tunjangan Jabatan</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_tunjab"
                                                            name="tunjab" value="0" min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_tunkom" class="col-sm-5 col-form-label">Tunjangan Komunikasi</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_tunkom"
                                                            name="tunkom" value="0" min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_fot" class="col-sm-5 col-form-label">Fix Over Time (FOT)</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_fot"
                                                            name="fot" value="0" min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_tunmal" class="col-sm-5 col-form-label">Tunjangan Kemahalan</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_tunmal"
                                                            name="tunmal" value="0" min="0" step="100" readonly>
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
                                                            name="lbr_harian" value="0" min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_lbr_perjam" class="col-sm-5 col-form-label">Lembur Per Jam</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_lbr_perjam"
                                                            name="lbr_perjam" value="0" min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_tukin" class="col-sm-5 col-form-label">Tunjangan Kinerja</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_tukin"
                                                            name="tukin" value="0" min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_insentif" class="col-sm-5 col-form-label">Insentif</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_insentif"
                                                            name="insentif" value="0" min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_bonus" class="col-sm-5 col-form-label">Bonus</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_bonus"
                                                            name="bonus" value="0" min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_thr" class="col-sm-5 col-form-label">Tunjangan Hari Raya (THR)</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_thr"
                                                            name="thr" value="0" min="0" step="100" readonly>
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
                                                        name="bpjs_tkj" value="0" min="0" step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_bpjs_kes" class="col-sm-5 col-form-label">BPJS Kesehatan</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_bpjs_kes"
                                                        name="bpjs_kes" value="0" min="0" step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_iuran_koperasi" class="col-sm-5 col-form-label">Iuran Wajib Koperasi</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_iuran_koperasi"
                                                        name="iuran_koperasi" value="0" min="0" step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_tps_kry" class="col-sm-5 col-form-label">Tabungan Pensiun</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_tps_kry"
                                                        name="tps_kry" value="0" min="0" step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_pjk_pkp" class="col-sm-5 col-form-label">Pajak PKP</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_pjk_pkp"
                                                        name="pjk_pkp" value="0" min="0" step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_pjk_pph" class="col-sm-5 col-form-label">Pajak PPh</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_pjk_pph"
                                                        name="pjk_pph" value="0" min="0" step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_ptg_thr" class="col-sm-5 col-form-label">Potongan THR</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_ptg_thr"
                                                        name="ptg_thr" value="0" min="0" step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_pjm_kop" class="col-sm-5 col-form-label">Pinjaman Koperasi</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_pjm_kop"
                                                        name="pjm_kop" value="0" min="0" step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_dda_sanksi" class="col-sm-5 col-form-label">Denda Sanksi</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end" id="modal_dda_sanksi"
                                                        name="dda_sanksi" value="0" min="0" step="100" readonly>
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
                                                            name="bpjs_tkj_prs" value="0" min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="modal_bpjs_kes_prs" class="form-label fw-bold">BPJS Kes</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_bpjs_kes_prs"
                                                            name="bpjs_kes_prs" value="0" min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="modal_tps_prs" class="form-label fw-bold">Tabungan Pensiun</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_tps_prs"
                                                            name="tps_prs" value="0" min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="modal_askes_prs" class="form-label fw-bold">Asuransi Kesehatan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end" id="modal_askes_prs"
                                                            name="askes_prs" value="0" min="0" step="100" readonly>
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
                                                        Status Data Gaji
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                        <input type="text" class="form-control" id="modal_sts_data_gaji" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="modal_tgl_na_gaji" class="form-label fw-bold">Tanggal Status Non Aktif</label>
                                                    <input type="text" class="form-control" id="modal_tgl_na_gaji" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="modal_ket_na_gaji" class="form-label fw-bold">Keterangan Non Aktif</label>
                                                    <textarea class="form-control" id="modal_ket_na_gaji" rows="1" readonly></textarea>
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
        #modal_total_pendapatan_tetap,
        #modal_total_pendapatan_tidak_tetap,
        #modal_total_potongan {
            text-align: right;
            padding-right: 24px !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let currentSalaryId = null;

            // CSRF Token
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

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

            // VIEW SALARY DETAIL
            $(document).on('click', '.show-salary-btn', function() {
                const salaryId = $(this).data('salary-id');
                currentSalaryId = salaryId;
                resetSalaryModal();
                loadSalaryDataToModal(salaryId);
                $('#salaryModal').modal('show');
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

            // ===== HELPER FUNCTIONS =====
            function resetSalaryModal() {
                $('#salaryForm')[0].reset();
                $('#salary_id').val('');
                currentSalaryId = null;

                // Reset all number inputs to 0
                $('#salaryModal input[type="number"]').val(0);

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