@extends('layouts.app')

@section('title', 'Detail Data Dokumen HRD')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-contract me-2"></i>Detail Data Dokumen HRD</span>
                        <div>
                            @if (auth()->user()->is_admin || (isset($userPermissions['ubah']) && $userPermissions['ubah']))
                                <a href="{{ route('data-dokumen-hrd.edit', $dataDokumenHrd->id) }}"
                                    class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            @endif
                            <a href="{{ route('data-dokumen-hrd.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Card 1: Informasi Umum Dokumen -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary bg-opacity-25">
                                <h5 class="mb-0 text-white">
                                    <i class="fas fa-file-alt me-2"></i>Informasi Umum Dokumen
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- No Dokumen -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">No. Dokumen</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->no_dok_hrd ?? '-' }}
                                        </p>
                                    </div>

                                    <!-- Perusahaan -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">No. Dokumen</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->perusahaan->nama_prs1 ?? '-' }}
                                        </p>
                                    </div>



                                    <!-- Kategori Dokumen -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold text-muted">Kategori Dokumen</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->dokumenHrd->ktg_dok_hrd ?? '-' }}
                                        </p>
                                    </div>

                                    <!-- Jenis Dokumen -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold text-muted">Jenis Dokumen</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->dokumenHrd->jns_dok_hrd ?? '-' }}
                                        </p>
                                    </div>

                                    <!-- Kode Dokumen -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold text-muted">Kode Dokumen</label>
                                        <p class="form-control-plaintext border-bottom">
                                            <span
                                                class="badge bg-primary">{{ $dataDokumenHrd->dokumenHrd->kode_dok_hrd ?? '-' }}</span>
                                        </p>
                                    </div>

                                    <!-- Keterangan -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Keterangan</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->ket_dok_hrd ?? '-' }}
                                        </p>
                                    </div>

                                    <!-- Tanggal TTD -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Tanggal TTD/Terbit</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->tgl_ttd ? $dataDokumenHrd->tgl_ttd->format('d-m-Y') : '-' }}
                                        </p>
                                    </div>

                                    <!-- File Dokumen -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">File Dokumen</label>
                                        <p class="form-control-plaintext border-bottom">
                                            @if ($dataDokumenHrd->file_dok)
                                                <a href="{{ asset('storage/' . $dataDokumenHrd->file_dok) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-file-pdf me-1"></i>Lihat File
                                                </a>
                                            @else
                                                <span class="text-muted">Tidak ada file</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">File Dokumen</label>
                                        <p class="form-control-plaintext border-bottom">
                                            @if ($dataDokumenHrd->file_dok_2)
                                                <a href="{{ asset('storage/' . $dataDokumenHrd->file_dok_2) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-file-pdf me-1"></i>Lihat File
                                                </a>
                                            @else
                                                <span class="text-muted">Tidak ada file</span>
                                            @endif
                                        </p>
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
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-bold text-muted">Jenis Masa Berlaku</label>
                                        <p class="form-control-plaintext border-bottom">
                                            @if ($dataDokumenHrd->jns_msb_dok === 'TETAP')
                                                <span class="badge bg-success">TETAP</span>
                                            @elseif ($dataDokumenHrd->jns_msb_dok === 'PERPANJANGAN')
                                                <span class="badge bg-info">PERPANJANGAN</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Tanggal Akhir Berlaku -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Tanggal Akhir Berlaku</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->tgl_akr_dok ? $dataDokumenHrd->tgl_akr_dok->format('d-m-Y') : '-' }}
                                        </p>
                                    </div>

                                    <!-- Masa Berlaku (Bulan) -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Masa Berlaku (Bulan)</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->msb_dok ? $dataDokumenHrd->msb_dok . ' bulan' : '-' }}
                                        </p>
                                    </div>

                                    <!-- Tanggal Peringatan -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Tanggal Peringatan</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->tgl_prt_dok ? $dataDokumenHrd->tgl_prt_dok->format('d-m-Y') : '-' }}
                                            @if ($dataDokumenHrd->tgl_prt_dok && $dataDokumenHrd->document_reminder_status)
                                                <br>
                                                <span
                                                    class="badge bg-{{ $dataDokumenHrd->document_reminder_status['class'] }} mt-1">
                                                    {{ $dataDokumenHrd->document_reminder_status['message'] }}
                                                </span>
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Durasi Peringatan (Hari) -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Durasi Peringatan (Hari)</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->durasi_pgt ? $dataDokumenHrd->durasi_pgt . ' hari' : '-' }}
                                        </p>
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
                                    <!-- Status Dokumen -->
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-bold text-muted">Status Dokumen</label>
                                        <p class="form-control-plaintext border-bottom">
                                            @if ($dataDokumenHrd->sts_dok === 'AKTIF')
                                                <span class="badge bg-success">AKTIF</span>
                                            @elseif ($dataDokumenHrd->sts_dok === 'NON-AKTIF')
                                                <span class="badge bg-secondary">NON-AKTIF</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $dataDokumenHrd->sts_dok }}</span>
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Tanggal Non Aktif -->
                                    @if ($dataDokumenHrd->sts_dok === 'NON-AKTIF' && $dataDokumenHrd->tgl_dok_na)
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted">Tanggal Status Non Aktif</label>
                                            <p class="form-control-plaintext border-bottom">
                                                {{ $dataDokumenHrd->tgl_dok_na ? $dataDokumenHrd->tgl_dok_na->format('d-m-Y') : '-' }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Keterangan Non Aktif -->
                                    @if ($dataDokumenHrd->sts_dok === 'NON-AKTIF' && $dataDokumenHrd->ket_dok_na)
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted">Keterangan Non Aktif</label>
                                            <p class="form-control-plaintext border-bottom">
                                                {{ $dataDokumenHrd->ket_dok_na ?? '-' }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Informasi Sistem -->
                        <div class="card border-secondary mb-4">
                            <div class="card-header bg-secondary bg-opacity-25">
                                <h5 class="mb-0 text-white">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Sistem
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- ID Kode -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">ID Kode</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->id_kode ?? '-' }}
                                        </p>
                                    </div>

                                    <!-- Dibuat Oleh -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Dibuat Oleh</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->creator->name ?? '-' }}
                                        </p>
                                    </div>

                                    <!-- Tanggal Dibuat -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Tanggal Dibuat</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->created_at ? $dataDokumenHrd->created_at->format('d-m-Y H:i:s') : '-' }}
                                        </p>
                                    </div>

                                    <!-- Diperbarui Oleh -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Diperbarui Oleh</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->updater->name ?? '-' }}
                                        </p>
                                    </div>

                                    <!-- Tanggal Diperbarui -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Tanggal Diperbarui</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $dataDokumenHrd->updated_at ? $dataDokumenHrd->updated_at->format('d-m-Y H:i:s') : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('data-dokumen-hrd.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                            @if (auth()->user()->is_admin || (isset($userPermissions['ubah']) && $userPermissions['ubah']))
                                <a href="{{ route('data-dokumen-hrd.edit', $dataDokumenHrd->id) }}"
                                    class="btn btn-warning btn-lg">
                                    <i class="fas fa-edit me-2"></i> Edit Data
                                </a>
                            @endif
                        </div>
                    </div>
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

        .form-control-plaintext {
            padding: 0.5rem 0;
            font-size: 1rem;
            color: #212529;
        }

        .border-bottom {
            border-bottom: 1px solid #dee2e6 !important;
        }
    </style>
@endpush
