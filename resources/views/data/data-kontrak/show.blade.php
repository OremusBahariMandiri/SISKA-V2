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
                            @if(isset($userPermissions['ubah']) && $userPermissions['ubah'])
                                <a href="{{ route('data-kontrak.edit', $dataKontrak->id) }}" class="btn btn-warning btn-sm me-2">
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
                                        @if($dataKontrak->karyawan)
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Nama Lengkap</label>
                                                        <input type="text" class="form-control fw-bold" value="{{ $dataKontrak->karyawan->nama ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">NIK</label>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->karyawan->nik ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">NRK</label>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->karyawan->nrk ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tempat Lahir</label>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->karyawan->tpt_lahir ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tanggal Lahir</label>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->karyawan->tgl_lahir ? $dataKontrak->karyawan->tgl_lahir->format('d-m-Y') : '' }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Jenis Kelamin</label>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->karyawan->sex ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Telepon</label>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->karyawan->tlp1 ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Status Kawin</label>
                                                        <input type="sts_nikah" class="form-control" value="{{ $dataKontrak->karyawan->sts_nikah ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Jumlah Anak</label>
                                                        <input type="jml_anak" class="form-control" value="{{ $dataKontrak->karyawan->jml_anak ?? '' }}" disabled>
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

                            <!-- Tab 2: Data Kontrak -->
                            <div class="tab-pane fade" id="kontrak" role="tabpanel" aria-labelledby="kontrak-tab">

                                <!-- Informasi Umum Kontrak -->
                                <div class="card border-secondary mb-4">
                                    <div class="card-header bg-secondary bg-opacity-25">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-file-contract me-2"></i>Informasi Umum Kontrak
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">No. Surat Kontrak</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->no_srt_ktr ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal Surat Kontrak</label>
                                                    <input type="text" class="form-control" value="{{ $dataKontrak->tgl_srt_ktr ? \Carbon\Carbon::parse($dataKontrak->tgl_srt_ktr)->format('d-m-Y') : '' }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Status Kontrak</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-file-signature"></i></span>
                                                        <input type="text" class="form-control fw-bold" value="{{ $dataKontrak->kontrakKerja->nama_ktr ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Singkatan</label>
                                                    <input type="text" class="form-control" value="{{ $dataKontrak->kontrakKerja->singkatan_ktr ?? '' }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Perusahaan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                        <input type="text" class="form-control fw-bold" value="{{ $dataKontrak->perusahaan->nama_prs1 ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Singkatan</label>
                                                    <input type="text" class="form-control" value="{{ $dataKontrak->perusahaan->nama_prs2 ?? '' }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Keterangan Kontrak</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                        <input type="text" class="form-control
                                                            @if($dataKontrak->ktg_ktk == 'TETAP') text-success fw-bold
                                                            @elseif($dataKontrak->ktg_ktk == 'TIDAK TETAP') text-warning fw-bold
                                                            @endif"
                                                            value="{{ $dataKontrak->ktg_ktk ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Periode Kontrak -->
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-calendar-alt me-2"></i>Periode Kontrak
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal Mulai Kontrak</label>
                                                    <input type="text" class="form-control fw-bold text-success" value="{{ $dataKontrak->tgl_awl_ktr ? \Carbon\Carbon::parse($dataKontrak->tgl_awl_ktr)->format('d-m-Y') : '' }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal Akhir Kontrak</label>
                                                    <input type="text" class="form-control fw-bold text-danger" value="{{ $dataKontrak->tgl_akhir_ktr ? \Carbon\Carbon::parse($dataKontrak->tgl_akhir_ktr)->format('d-m-Y') : '' }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Durasi Kontrak (Bulan)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->durasi_ktr ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal Pengingat</label>
                                                    <input type="text" class="form-control" value="{{ $dataKontrak->tgl_pgt_ktr ? \Carbon\Carbon::parse($dataKontrak->tgl_pgt_ktr)->format('d-m-Y') : '' }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Durasi Pengingat (Hari)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->durasi_pgt ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Status Kontrak Current -->
                                        @if($dataKontrak->tgl_awl_ktr && $dataKontrak->tgl_akhir_ktr)
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="alert
                                                        @if(\Carbon\Carbon::parse($dataKontrak->tgl_akhir_ktr)->isPast()) alert-danger
                                                        @elseif(\Carbon\Carbon::parse($dataKontrak->tgl_akhir_ktr)->diffInDays() <= 30) alert-warning
                                                        @else alert-success @endif
                                                    ">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        <strong>Status Kontrak Saat Ini:</strong>
                                                        @if(\Carbon\Carbon::parse($dataKontrak->tgl_akhir_ktr)->isPast())
                                                            Kontrak telah berakhir pada {{ \Carbon\Carbon::parse($dataKontrak->tgl_akhir_ktr)->format('d-m-Y') }}
                                                        @elseif(\Carbon\Carbon::parse($dataKontrak->tgl_akhir_ktr)->diffInDays() <= 30)
                                                            Kontrak akan berakhir dalam {{ \Carbon\Carbon::parse($dataKontrak->tgl_akhir_ktr)->diffInDays() }} hari
                                                        @else
                                                            Kontrak masih aktif, berakhir pada {{ \Carbon\Carbon::parse($dataKontrak->tgl_akhir_ktr)->format('d-m-Y') }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Status dan Dokumen -->
                                <div class="card border-success mb-4">
                                    <div class="card-header bg-success bg-opacity-25">
                                        <h5 class="mb-0 text-white">
                                            <i class="fas fa-file-alt me-2"></i>Status dan Dokumen
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Status Surat Kontrak</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                        <input type="text" class="form-control
                                                            @if($dataKontrak->sts_srt_ktr == 'AKTIF') text-success fw-bold
                                                            @elseif($dataKontrak->sts_srt_ktr == 'NON-AKTIF') text-danger fw-bold
                                                            @endif"
                                                            value="{{ $dataKontrak->sts_srt_ktr ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">File Dokumen Kontrak</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                        @if($dataKontrak->file_doc_ktr)
                                                            <input type="text" class="form-control text-primary" value="Dokumen tersedia" disabled>
                                                            <a href="{{ asset('storage/' . $dataKontrak->file_doc_ktr) }}" target="_blank" class="btn btn-outline-primary">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        @else
                                                            <input type="text" class="form-control text-muted" value="Tidak ada dokumen" disabled>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @if($dataKontrak->sts_srt_ktr == 'NON-AKTIF')
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tanggal Status Non Aktif</label>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->tgl_sr_na ? \Carbon\Carbon::parse($dataKontrak->tgl_sr_na)->format('d-m-Y') : '' }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Keterangan Non Aktif</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-comment-alt"></i></span>
                                                            <textarea class="form-control" rows="3" disabled>{{ $dataKontrak->ket_sr_na ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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
                                                        <span class="input-group-text"><i class="fas fa-level-up-alt"></i></span>
                                                        <input type="text" class="form-control fw-bold text-primary" value="{{ $dataKontrak->jenjang_skl ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal Lulus</label>
                                                    <input type="text" class="form-control" value="{{ $dataKontrak->tgl_lulus_skl ? \Carbon\Carbon::parse($dataKontrak->tgl_lulus_skl)->format('d-m-Y') : '' }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Nama Institusi</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->institusi_skl ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">SKT Institusi</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-certificate"></i></span>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->skt_inst_skl ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Fakultas</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-building-columns"></i></span>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->fakultas_skl ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jurusan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-book-open"></i></span>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->jurusan_skl ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Kota</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->kota_skl ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Gelar</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-medal"></i></span>
                                                        <input type="text" class="form-control" value="{{ $dataKontrak->gelar_skl ?? '' }}" disabled>
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
                                                        <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                                                        <input type="text" class="form-control fw-bold" value="{{ $dataKontrak->departemen->nama_dep ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Singkatan Dep.</label>
                                                    <input type="text" class="form-control" value="{{ $dataKontrak->departemen->singkatan_dep ?? '' }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jabatan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                        <input type="text" class="form-control fw-bold" value="{{ $dataKontrak->departemen->nama_jbt ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Singkatan Jbt.</label>
                                                    <input type="text" class="form-control" value="{{ $dataKontrak->departemen->singkatan_jbt ?? '' }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Wilayah Kerja</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                        <input type="text" class="form-control fw-bold" value="{{ $dataKontrak->wilayahKerja->wilayah_krj ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Area Kerja</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-users-cog"></i></span>
                                                        <input type="text" class="form-control fw-bold" value="{{ $dataKontrak->wilayahKerja->area_krj ?? '' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">SKT Wilayah Kerja</label>
                                                    <input type="text" class="form-control" value="{{ $dataKontrak->wilayahKerja->singkatan_wk ?? '' }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tugas & Tanggung Jawab</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                                                        <textarea class="form-control" rows="4" disabled>{{ $dataKontrak->tugas ?? '' }}</textarea>
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
                                        @if($dataKontrak->karyawan)
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
                                                            @if($dataKontrak->karyawan->sts_kry == 'AKTIF')
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
                                                            @if($dataKontrak->karyawan->tgl_phk)
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
                            <a href="{{ route('data-kontrak.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                            </a>

                            <div>
                                @if(isset($userPermissions['ubah']) && $userPermissions['ubah'])
                                    <a href="{{ route('data-kontrak.edit', $dataKontrak->id) }}" class="btn btn-warning btn-lg me-2">
                                        <i class="fas fa-edit me-2"></i> Edit Data
                                    </a>
                                @endif

                                @if($dataKontrak->file_doc_ktr)
                                    <a href="{{ asset('storage/' . $dataKontrak->file_doc_ktr) }}" target="_blank" class="btn btn-info btn-lg">
                                        <i class="fas fa-download me-2"></i> Unduh Dokumen
                                    </a>
                                @endif
                            </div>
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

        /* Print-friendly styles */
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ Data Kontrak Show page loaded');

            // Auto print functionality if needed
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === '1') {
                setTimeout(() => {
                    window.print();
                }, 1000);
            }
        });
    </script>
@endpush