@extends('layouts.app')

@section('title', 'Detail Data Dokumen')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-eye me-2"></i>Detail Data Dokumen</span>
                        <div>
                            @if (isset($userPermissions['ubah']) && $userPermissions['ubah'])
                                <a href="{{ route('data-dokumen.edit', $dataDokumen->id) }}"
                                    class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            @endif
                            <a href="{{ route('data-dokumen.index') }}" class="btn btn-light btn-sm">
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
                                <button class="nav-link" id="dokumen-tab" data-bs-toggle="tab" data-bs-target="#dokumen"
                                    type="button" role="tab" aria-controls="dokumen" aria-selected="false">
                                    <i class="fas fa-file-alt me-1"></i> Data Dokumen
                                    <span class="badge bg-primary ms-1">{{ $allDocuments->count() }}</span>
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
                                        @if ($dataDokumen->karyawan)
                                            <div class="row">
                                                <!-- Foto Karyawan -->
                                                <div class="col-md-3 text-center mb-4">
                                                    <div class="employee-photo-container">
                                                        @if ($dataDokumen->karyawan->foto_dokumen)
                                                            <img src="{{ asset('storage/' . $dataDokumen->karyawan->foto_dokumen) }}"
                                                                alt="Foto {{ $dataDokumen->karyawan->nama ?? 'Karyawan' }}"
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
                                                            <div class="form-group">
                                                                <label class="form-label fw-bold">Nama Lengkap</label>
                                                                <input type="text" class="form-control fw-bold"
                                                                    value="{{ $dataDokumen->karyawan->nama ?? '' }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label fw-bold">NIK</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $dataDokumen->karyawan->nik ?? '' }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label fw-bold">NRK</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $dataDokumen->karyawan->nrk ?? '' }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label fw-bold">Jenis Kelamin</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $dataDokumen->karyawan->sex ?? '' }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label fw-bold">Tempat, Tanggal
                                                                    Lahir</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $dataDokumen->karyawan->tpt_lahir ?? '' }}{{ $dataDokumen->karyawan->tgl_lahir ? ', ' . $dataDokumen->karyawan->tgl_lahir->format('d-m-Y') : '' }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label fw-bold">Telepon</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $dataDokumen->karyawan->tlp1 ?? '' }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label fw-bold">Status Kawin</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $dataDokumen->karyawan->sts_nikah ?? '' }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label fw-bold">Jumlah Anak</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $dataDokumen->karyawan->jml_anak ?? '' }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label fw-bold">Email</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $dataDokumen->karyawan->email1 ?? '' }}"
                                                                    readonly>
                                                            </div>
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

                            <!-- Tab 2: Data Dokumen (Display All Documents) -->
                            <div class="tab-pane fade" id="dokumen" role="tabpanel" aria-labelledby="dokumen-tab">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-file-alt me-2"></i>Riwayat Dokumen Karyawan
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="documentsTable">
                                                <thead class="table-info">
                                                    <tr>
                                                        <th width="3%">No</th>
                                                        <th width="10%">Kategori</th>
                                                        <th width="12%">Jenis Dokumen</th>
                                                        <th width="10%">Kode</th>
                                                        <th width="10%">No. Dokumen</th>
                                                        <th width="8%">Tgl TTD</th>
                                                        <th width="8%">Tgl Akhir</th>
                                                        <th width="6%">Durasi</th>
                                                        <th width="8%">Status</th>
                                                        <th width="9%">Create</th>
                                                        <th width="9%">Update</th>
                                                        <th width="4%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="documentsTableBody">
                                                    @forelse ($allDocuments as $index => $document)
                                                        <tr data-document-id="{{ $document->id }}">
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                <small>{{ $document->dokumenKaryawan->ktg_dok_kry ?? ($document->ktg_dok_kry ?? '-') }}</small>
                                                            </td>
                                                            <td>
                                                                <small>{{ $document->dokumenKaryawan->jns_dok_kry ?? ($document->jns_dok_kry ?? '-') }}</small>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-info">{{ $document->dokumenKaryawan->kode_dok_kry ?? ($document->kode_dok_kry ?? '-') }}</span>
                                                            </td>
                                                            <td>{{ $document->no_dok ?: '-' }}</td>
                                                            <td>{{ $document->tgl_ttd ? \Carbon\Carbon::parse($document->tgl_ttd)->format('d-m-Y') : '-' }}
                                                            </td>
                                                            <td>{{ $document->tgl_akr_dok ? \Carbon\Carbon::parse($document->tgl_akr_dok)->format('d-m-Y') : '-' }}
                                                            </td>
                                                            <td>{{ $document->msb_dok ? $document->msb_dok . ' bln' : '-' }}
                                                            </td>
                                                            <td>
                                                                @if ($document->sts_dok == 'AKTIF')
                                                                    <span
                                                                        class="badge bg-success">{{ $document->sts_dok }}</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-secondary">{{ $document->sts_dok }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <small
                                                                    class="d-block">{{ $document->creator ? $document->creator->nama_kry : '-' }}</small>
                                                                <small
                                                                    class="text-muted">{{ $document->created_at ? $document->created_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td>
                                                                <small
                                                                    class="d-block">{{ $document->updater ? $document->updater->nama_kry : '-' }}</small>
                                                                <small
                                                                    class="text-muted">{{ $document->updated_at ? $document->updated_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-info view-document-btn"
                                                                    data-document-id="{{ $document->id }}"
                                                                    data-bs-toggle="tooltip" title="Lihat Detail">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="12" class="text-center text-muted">
                                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                                <br>Tidak ada data dokumen
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 3: Kontrak Kerja -->
                            <div class="tab-pane fade" id="kontrak" role="tabpanel" aria-labelledby="kontrak-tab">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-file-contract me-2"></i>Data Kontrak Kerja
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data berikut diambil dari data karyawan yang terpilih.
                                        </div>

                                        @if ($dataDokumen->karyawan)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Perusahaan</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataDokumen->karyawan->perusahaanRelation->nama_prs1 ?? '-' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Status Kontrak</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataDokumen->karyawan->kontrakRelation->nama_ktr ?? '-' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tanggal Awal Kontrak</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataDokumen->karyawan->tgl_awal_ktr ? $dataDokumen->karyawan->tgl_awal_ktr->format('d-m-Y') : '-' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tanggal Akhir Kontrak</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataDokumen->karyawan->tgl_akhir_ktr ? $dataDokumen->karyawan->tgl_akhir_ktr->format('d-m-Y') : '-' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Durasi Kontrak (Bulan)</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataDokumen->karyawan->durasi_ktr ? $dataDokumen->karyawan->durasi_ktr . ' bulan' : '-' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Data karyawan tidak tersedia.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 4: Jenjang Karir -->
                            <div class="tab-pane fade" id="karir" role="tabpanel" aria-labelledby="karir-tab">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-briefcase me-2"></i>Jenjang Karir
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Data berikut diambil dari data karyawan yang terpilih.
                                        </div>

                                        @if ($dataDokumen->karyawan)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Departemen</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-sitemap"></i></span>
                                                            <input type="text" class="form-control fw-bold"
                                                                value="{{ $dataDokumen->karyawan->departemenRelation->nama_dep ?? '-' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Jabatan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-user-tie"></i></span>
                                                            <input type="text" class="form-control fw-bold"
                                                                value="{{ $dataDokumen->karyawan->departemenRelation->nama_jbt ?? '-' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Wilayah Kerja</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map"></i></span>
                                                            <input type="text" class="form-control fw-bold"
                                                                value="{{ $dataDokumen->karyawan->wilayahKerjaRelation->wilayah_krj ?? '-' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Unit Kerja</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-users-cog"></i></span>
                                                            <input type="text" class="form-control fw-bold"
                                                                value="{{ $dataDokumen->karyawan->unitKerjaRelation->area_krj ?? '-' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tugas & Tanggung Jawab</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-tasks"></i></span>
                                                            <textarea class="form-control" rows="4" readonly>{{ $dataDokumen->karyawan->tugas ?? '-' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Data karyawan tidak tersedia.
                                            </div>
                                        @endif
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
                                        @if ($dataDokumen->karyawan)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-muted">Tanggal Masuk</label>
                                                        <p class="form-control-plaintext fw-bold text-success">
                                                            {{ $dataDokumen->karyawan->tgl_masuk ? $dataDokumen->karyawan->tgl_masuk->format('d-m-Y') : '-' }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-muted">Status
                                                            Karyawan</label>
                                                        <p class="form-control-plaintext">
                                                            @if ($dataDokumen->karyawan->sts_kry == 'AKTIF')
                                                                <span
                                                                    class="badge bg-success fs-6">{{ $dataDokumen->karyawan->sts_kry }}</span>
                                                            @elseif($dataDokumen->karyawan->sts_kry == 'NON-AKTIF')
                                                                <span
                                                                    class="badge bg-danger fs-6">{{ $dataDokumen->karyawan->sts_kry }}</span>
                                                            @else
                                                                <span
                                                                    class="badge bg-secondary fs-6">{{ $dataDokumen->karyawan->sts_kry ?? '-' }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-muted">Tanggal PHK</label>
                                                        <p class="form-control-plaintext">
                                                            @if ($dataDokumen->karyawan->tgl_phk)
                                                                <span class="text-danger fw-bold">
                                                                    {{ $dataDokumen->karyawan->tgl_phk->format('d-m-Y') }}
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
                                                        <p class="form-control-plaintext">
                                                            {{ $dataDokumen->karyawan->ket_phk ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Data karyawan tidak tersedia untuk menampilkan informasi hubungan
                                                industrial.
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
                                                    <label class="form-label fw-bold text-muted">ID Data Dokumen</label>
                                                    <p class="form-control-plaintext font-monospace">
                                                        {{ $dataDokumen->id_dok_kry ?? $dataDokumen->id }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">ID Kode</label>
                                                    <p class="form-control-plaintext font-monospace">
                                                        {{ $dataDokumen->id_kode ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Dibuat Pada</label>
                                                    <p class="form-control-plaintext">
                                                        {{ $dataDokumen->created_at ? $dataDokumen->created_at->format('d-m-Y H:i:s') : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Dibuat Oleh</label>
                                                    <p class="form-control-plaintext">
                                                        {{ $dataDokumen->creator->nama_kry ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Diperbarui Pada</label>
                                                    <p class="form-control-plaintext">
                                                        {{ $dataDokumen->updated_at ? $dataDokumen->updated_at->format('d-m-Y H:i:s') : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Diperbarui Oleh</label>
                                                    <p class="form-control-plaintext">
                                                        {{ $dataDokumen->updater->nama_kry ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action buttons at the bottom -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('data-dokumen.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                            <div class="row">
                                <div class="col-12 text-end">
                                    @if (auth()->user()->is_admin || (isset($userPermissions['hapus']) && $userPermissions['hapus']))
                                        <button type="button" class="btn btn-danger" id="deleteAllDocumentsBtn"
                                            data-employee-id="{{ $dataDokumen->id_data_kry }}"
                                            data-employee-name="{{ $dataDokumen->karyawan->nama ?? 'N/A' }}">
                                            <i class="fas fa-trash me-1"></i> Hapus Semua Dokumen
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

    <!-- Document Detail View Modal -->
    <div class="modal fade" id="documentDetailModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="documentDetailModalTitle">
                        <i class="fas fa-file-alt me-2"></i>Detail Dokumen
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="documentDetailContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat detail dokumen...</p>
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

    <!-- Delete All Documents Modal -->
    <div class="modal fade" id="deleteAllDocumentsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus Semua Dokumen
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <p>Apakah Anda yakin ingin menghapus <strong>semua dokumen</strong> untuk karyawan:</p>
                    <p class="fs-5 fw-bold text-danger" id="deleteEmployeeName">-</p>
                    <p class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Ini akan menghapus seluruh riwayat dokumen karyawan tersebut.
                        Data yang sudah dihapus <strong>tidak dapat dikembalikan</strong>.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteAllBtn">
                        <i class="fas fa-trash me-1"></i>Hapus Semua Dokumen
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

        #documentDetailModal .card {
            margin-bottom: 1rem;
        }

        #documentDetailModal .card-header h6 {
            margin: 0;
            font-weight: 600;
        }

        /* Employee Photo Styling */
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

        @media print {

            .btn,
            .nav-tabs {
                display: none !important;
            }

            .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }

            .tab-content>.tab-pane {
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
        $(document).ready(function() {
            console.log('✅ Data Dokumen Show page loaded');

            // ===== CSRF Token untuk AJAX =====
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // ===== Initialize tooltips =====
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // ===== VIEW DOCUMENT DETAIL =====
            $(document).on('click', '.view-document-btn', function() {
                const documentId = $(this).data('document-id');

                $('#documentDetailModal').modal('show');
                $('#documentDetailContent').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat detail dokumen...</p>
                    </div>
                `);

                $.ajax({
                    url: `/data-dokumen/documents/${documentId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            displayDocumentDetail(response.data);
                        } else {
                            showError('Gagal memuat detail dokumen: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        showError('Terjadi kesalahan saat memuat detail dokumen.');
                    }
                });
            });

            // ===== DELETE ALL DOCUMENTS =====
            // Step 1: Klik button -> isi data ke modal -> tampilkan modal
            $('#deleteAllDocumentsBtn').on('click', function() {
                const employeeId = $(this).data('employee-id');
                const employeeName = $(this).data('employee-name');

                // Simpan employee id ke button konfirmasi sebagai data attribute
                $('#confirmDeleteAllBtn').data('employee-id', employeeId);

                // Tampilkan nama karyawan di modal
                $('#deleteEmployeeName').text(employeeName);

                // Tampilkan modal
                $('#deleteAllDocumentsModal').modal('show');
            });

            // Step 2: Klik tombol konfirmasi di modal -> SweetAlert double confirm -> AJAX DELETE
            $('#confirmDeleteAllBtn').on('click', function() {
                const employeeId = $(this).data('employee-id');

                // Tutup modal bootstrap dulu
                $('#deleteAllDocumentsModal').modal('hide');

                // Double confirm dengan SweetAlert
                Swal.fire({
                    title: 'Konfirmasi Akhir',
                    html: 'Data yang dihapus <strong>tidak dapat dikembalikan</strong>.<br>Lanjutkan hapus semua dokumen?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus Semua!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then(function(result) {
                    if (result.isConfirmed) {
                        // Tampilkan loading
                        Swal.fire({
                            title: 'Menghapus data...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: function() {
                                Swal.showLoading();
                            }
                        });

                        // Kirim DELETE request via AJAX
                        $.ajax({
                            url: '/data-dokumen/delete-all/' + employeeId,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil Dihapus!',
                                    text: response.message ||
                                        'Semua dokumen karyawan telah dihapus.',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(function() {
                                    window.location.href =
                                        "{{ route('data-dokumen.index') }}";
                                });
                            },
                            error: function(xhr) {
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
                        $('#deleteAllDocumentsModal').modal('show');
                    }
                });
            });

            // ===== HELPER FUNCTIONS =====
            function displayDocumentDetail(document) {
                var documentHtml = `
                    <div class="card border-secondary mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Umum Dokumen</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Kategori Dokumen</label>
                                        <input type="text" class="form-control fw-bold text-primary"
                                            value="${(document.dokumen_karyawan && document.dokumen_karyawan.ktg_dok_kry) ? document.dokumen_karyawan.ktg_dok_kry : (document.ktg_dok_kry || '-')}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Jenis Dokumen</label>
                                        <input type="text" class="form-control fw-bold text-primary"
                                            value="${(document.dokumen_karyawan && document.dokumen_karyawan.jns_dok_kry) ? document.dokumen_karyawan.jns_dok_kry : (document.jns_dok_kry || '-')}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Kode Dokumen</label>
                                        <input type="text" class="form-control fw-bold"
                                            value="${(document.dokumen_karyawan && document.dokumen_karyawan.kode_dok_kry) ? document.dokumen_karyawan.kode_dok_kry : (document.kode_dok_kry || '-')}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">No. Dokumen</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                            <input type="text" class="form-control" value="${document.no_dok || '-'}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Status Dokumen</label>
                                        <input type="text" class="form-control ${getStatusClass(document.sts_dok)}" value="${document.sts_dok || '-'}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Periode Dokumen</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Tanggal Penandatanganan</label>
                                        <input type="text" class="form-control fw-bold text-success"
                                            value="${document.tgl_ttd ? formatDate(document.tgl_ttd) : '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Jenis Masa Berlaku</label>
                                        <input type="text" class="form-control"
                                            value="${document.jns_msb_dok || '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Tanggal Akhir</label>
                                        <input type="text" class="form-control fw-bold text-danger"
                                            value="${document.tgl_akr_dok ? formatDate(document.tgl_akr_dok) : '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Masa Berlaku (Bulan)</label>
                                        <input type="text" class="form-control"
                                            value="${document.msb_dok ? document.msb_dok + ' bulan' : '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Tanggal Pengingat</label>
                                        <input type="text" class="form-control"
                                            value="${document.tgl_pgt_dok ? formatDate(document.tgl_pgt_dok) : '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Durasi Pengingat (Hari)</label>
                                        <input type="text" class="form-control"
                                            value="${document.durasi_pgt || '-'}" readonly>
                                    </div>
                                </div>
                            </div>
                            ${generateDocumentStatusAlert(document)}
                        </div>
                    </div>
                    <div class="card border-info mb-4">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Keterangan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Keterangan Dokumen</label>
                                        <textarea class="form-control" rows="2" readonly>${document.ket_dok || '-'}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Catatan Dokumen</label>
                                        <textarea class="form-control" rows="2" readonly>${document.ctt_dok || '-'}</textarea>
                                    </div>
                                </div>
                                ${document.sts_dok === 'NON-AKTIF' ? `
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">Tanggal Non-Aktif</label>
                                            <input type="text" class="form-control text-danger fw-bold"
                                                value="${document.tgl_dok_na ? formatDate(document.tgl_dok_na) : '-'}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">Keterangan Non-Aktif</label>
                                            <input type="text" class="form-control"
                                                value="${document.ket_dok_na || '-'}" readonly>
                                        </div>
                                    </div>
                                    ` : ''}
                            </div>
                        </div>
                    </div>
                    <div class="card border-success mb-4">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-file-pdf me-2"></i>File Dokumen</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">File Dokumen</label>
                                        <div class="input-group">
                                            ${document.file_dok
                                                ? `<input type="text" class="form-control text-primary fw-bold" value="Dokumen tersedia" readonly>
                                                       <a href="/storage/${document.file_dok}" target="_blank" class="btn btn-outline-primary">
                                                           <i class="fas fa-download me-1"></i>Download
                                                       </a>`
                                                : `<input type="text" class="form-control text-muted" value="Tidak ada dokumen" readonly>`
                                            }
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('#documentDetailContent').html(documentHtml);
            }

            function showError(message) {
                $('#documentDetailContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>${message}
                    </div>
                `);
            }

            function formatDate(dateString) {
                if (!dateString) return '-';
                try {
                    var date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                } catch (e) {
                    return dateString;
                }
            }

            function getStatusClass(status) {
                if (status === 'AKTIF') return 'text-success fw-bold';
                if (status === 'NON-AKTIF') return 'text-danger fw-bold';
                return '';
            }

            function generateDocumentStatusAlert(document) {
                if (!document.tgl_pgt_dok || !document.tgl_akr_dok || document.sts_dok !== 'AKTIF') {
                    return '';
                }

                if (document.jns_msb_dok === 'TETAP') {
                    return `<div class="col-12 mt-2">
                                <div class="alert alert-info">
                                    <i class="fas fa-infinity me-2"></i>
                                    <strong>Status:</strong> Dokumen dengan masa berlaku TETAP
                                </div>
                            </div>`;
                }

                var today = new Date();
                var reminderDate = new Date(document.tgl_pgt_dok);
                var endDate = new Date(document.tgl_akr_dok);
                var daysDiff = Math.ceil((endDate - today) / (1000 * 3600 * 24));
                var reminderDiff = Math.ceil((reminderDate - today) / (1000 * 3600 * 24));

                var alertClass = 'alert-success';
                var statusText = 'Dokumen masih aktif, berakhir pada ' + formatDate(document.tgl_akr_dok);

                if (daysDiff < 0) {
                    alertClass = 'alert-danger';
                    statusText = 'Dokumen telah kadaluarsa pada ' + formatDate(document.tgl_akr_dok);
                } else if (reminderDiff <= 0) {
                    alertClass = 'alert-danger';
                    statusText = 'Peringatan! Dokumen akan berakhir dalam ' + daysDiff + ' hari';
                } else if (daysDiff <= 7) {
                    alertClass = 'alert-warning';
                    statusText = 'Perhatian! Dokumen akan berakhir dalam ' + daysDiff + ' hari (Urgent)';
                } else if (daysDiff <= 30) {
                    alertClass = 'alert-warning';
                    statusText = 'Dokumen akan berakhir dalam ' + daysDiff + ' hari';
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
                setTimeout(function() {
                    window.print();
                }, 1000);
            }
        });
    </script>
@endpush
