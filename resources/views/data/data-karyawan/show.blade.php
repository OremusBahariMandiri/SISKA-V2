@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-user me-2"></i>Detail Karyawan</span>
                        <div>
                            @if (isset($userPermissions['ubah']) && $userPermissions['ubah'])
                                <a href="{{ route('data-karyawan.edit', $dataKaryawan->id) }}"
                                    class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            @endif
                            <a href="{{ route('data-karyawan.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Nav tabs for detail sections -->
                        <ul class="nav nav-tabs mb-4" id="detailTabs" role="tablist">
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
                                    data-bs-target="#pendidikan" type="button" role="tab" aria-controls="pendidikan"
                                    aria-selected="false">
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
                            <!-- Data Pribadi -->
                            <div class="tab-pane fade show active" id="biodata" role="tabpanel"
                                aria-labelledby="biodata-tab">
                                <!-- Data Pribadi -->
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-id-card me-2"></i>Data Pribadi</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">NIK KTP</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-id-card-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->nik ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Nama Lengkap</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-user text-primary me-2"></i>
                                                    {{ $dataKaryawan->nama ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Tempat Lahir</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->tpt_lahir ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Tanggal Lahir</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->tgl_lahir ? \Carbon\Carbon::parse($dataKaryawan->tgl_lahir)->format('d F Y') : '-' }}
                                                    @if ($dataKaryawan->tgl_lahir)
                                                        <span class="badge bg-info ms-2">
                                                            {{ \Carbon\Carbon::parse($dataKaryawan->tgl_lahir)->age }} tahun
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Jenis Kelamin</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-venus-mars text-primary me-2"></i>
                                                    {{ $dataKaryawan->sex ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Agama</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-pray text-primary me-2"></i>
                                                    {{ $dataKaryawan->agama ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-muted">Kewarganegaraan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-flag text-primary me-2"></i>
                                                    {{ $dataKaryawan->kewarganegaraan ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Keluarga -->
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-id-card me-2"></i>Status Keluarga
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Status Pernikahan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-heart text-primary me-2"></i>
                                                    {{ $dataKaryawan->sts_nikah ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Status dalam Keluarga</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-user-friends text-primary me-2"></i>
                                                    {{ $dataKaryawan->sts_keluarga ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Jumlah Anak</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-baby text-primary me-2"></i>
                                                    {{ $dataKaryawan->jml_anak ?? '0' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi Kontak -->
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-id-card me-2"></i>Informasi Kontak
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">No. Telepon Utama</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-phone text-primary me-2"></i>
                                                    {{ $dataKaryawan->tlp1 ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">No. Telepon Alternatif</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-mobile-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->tlp2 ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Email Utama</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-envelope text-primary me-2"></i>
                                                    {{ $dataKaryawan->email1 ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Email Alternatif</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-envelope text-primary me-2"></i>
                                                    {{ $dataKaryawan->email2 ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Instagram</label>
                                                <div class="detail-value">
                                                    <i class="fab fa-instagram text-primary me-2"></i>
                                                    {{ $dataKaryawan->instagram ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Facebook</label>
                                                <div class="detail-value">
                                                    <i class="fab fa-facebook text-primary me-2"></i>
                                                    {{ $dataKaryawan->facebook ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-muted">Dokumen</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-file-pdf text-primary me-2"></i>
                                                    @if ($dataKaryawan->foto_dokumen)
                                                        <a href="{{ Storage::url($dataKaryawan->foto_dokumen) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-download me-1"></i>Unduh Dokumen
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Alamat -->
                            <div class="tab-pane fade" id="alamat" role="tabpanel" aria-labelledby="alamat-tab">
                                <!-- Alamat KTP -->
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-id-card me-2"></i>Alamat KTP</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Provinsi</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-globe-asia text-primary me-2"></i>
                                                    {{ $dataKaryawan->prov_ktp ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kelurahan/Desa</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-building text-primary me-2"></i>
                                                    {{ $dataKaryawan->kel_ktp ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kota/Kabupaten</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map text-primary me-2"></i>
                                                    {{ $dataKaryawan->kota_ktp ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">RT/RW</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-home text-primary me-2"></i>
                                                    {{ $dataKaryawan->rt_rw_ktp ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kecamatan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-city text-primary me-2"></i>
                                                    {{ $dataKaryawan->kec_ktp ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kode Pos</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-mail-bulk text-primary me-2"></i>
                                                    {{ $dataKaryawan->kd_pos_ktp ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-muted">Alamat Lengkap</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map-marked-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->alamat_ktp ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alamat Domisili -->
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-house-user me-2"></i>Alamat Domisili
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Provinsi</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-globe-asia text-primary me-2"></i>
                                                    {{ $dataKaryawan->prov_dom ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kelurahan/Desa</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-building text-primary me-2"></i>
                                                    {{ $dataKaryawan->kel_dom ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kota/Kabupaten</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map text-primary me-2"></i>
                                                    {{ $dataKaryawan->kota_dom ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">RT/RW</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-home text-primary me-2"></i>
                                                    {{ $dataKaryawan->rt_rw_dom ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kecamatan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-city text-primary me-2"></i>
                                                    {{ $dataKaryawan->kec_dom ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kode Pos</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-mail-bulk text-primary me-2"></i>
                                                    {{ $dataKaryawan->kd_pos_dom ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-muted">Alamat Lengkap</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map-marked-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->alamat_dom ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pendidikan -->
                            <div class="tab-pane fade" id="pendidikan" role="tabpanel" aria-labelledby="pendidikan-tab">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-graduation-cap me-2"></i>Pendidikan
                                            Terakhir</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-muted">Jenjang Pendidikan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-level-up-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->jenjang_skl ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Nama Institusi</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-university text-primary me-2"></i>
                                                    {{ $dataKaryawan->institusi_skl ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kota</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->kota_skl ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Fakultas/SKT</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-building-columns text-primary me-2"></i>
                                                    {{ $dataKaryawan->fakultas_skl ?? ($dataKaryawan->skt_inst_skl ?? '-') }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Gelar</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-medal text-primary me-2"></i>
                                                    {{ $dataKaryawan->gelar_skl ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Jurusan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-book-open text-primary me-2"></i>
                                                    {{ $dataKaryawan->jurusan_skl ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Tanggal Lulus</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-calendar-check text-primary me-2"></i>
                                                    {{ $dataKaryawan->tgl_lulus_skl ? \Carbon\Carbon::parse($dataKaryawan->tgl_lulus_skl)->format('d F Y') : '-' }}
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
                                            Kontrak Kerja</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Status Kontrak</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-clipboard-check text-primary me-2"></i>
                                                    {{ $dataKaryawan->kontrakRelation && is_object($dataKaryawan->kontrakRelation) ? $dataKaryawan->kontrakRelation->singkatan_ktr : '-' }}
                                                    -
                                                    {{ $dataKaryawan->kontrakRelation && is_object($dataKaryawan->kontrakRelation) ? $dataKaryawan->kontrakRelation->nama_ktr : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Perusahaan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-building text-primary me-2"></i>
                                                    {{ $dataKaryawan->perusahaanRelation && is_object($dataKaryawan->perusahaanRelation) ? $dataKaryawan->perusahaanRelation->nama_prs1 : '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">SKT Status Kontrak</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->skt_sts_ktr ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">SKT Perusahaan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->skt_prs ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Tanggal Mulai Kontrak</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-calendar-plus text-primary me-2"></i>
                                                    {{ $dataKaryawan->tgl_awal_ktr ? \Carbon\Carbon::parse($dataKaryawan->tgl_awal_ktr)->format('d F Y') : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Tanggal Akhir Kontrak</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-calendar-minus text-primary me-2"></i>
                                                    {{ $dataKaryawan->tgl_akhir_ktr ? \Carbon\Carbon::parse($dataKaryawan->tgl_akhir_ktr)->format('d F Y') : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Durasi Kontrak</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-clock text-primary me-2"></i>
                                                    {{ $dataKaryawan->durasi_ktr ? $dataKaryawan->durasi_ktr . ' Bulan' : '-' }}
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
                                        <h5 class="mb-0 text-white"><i class="fas fa-briefcase me-2"></i>Informasi Jenjang
                                            Karir</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Departemen</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-sitemap text-primary me-2"></i>
                                                    {{ $dataKaryawan->departemenRelation && is_object($dataKaryawan->departemenRelation) ? $dataKaryawan->departemenRelation->singkatan_dep : '-' }}
                                                    -
                                                    {{ $dataKaryawan->departemenRelation && is_object($dataKaryawan->departemenRelation) ? $dataKaryawan->departemenRelation->nama_dep : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Jabatan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-user-tie text-primary me-2"></i>
                                                    {{ $dataKaryawan->departemenRelation && is_object($dataKaryawan->departemenRelation) ? $dataKaryawan->departemenRelation->singkatan_jbt : '-' }}
                                                    -
                                                    {{ $dataKaryawan->departemenRelation && is_object($dataKaryawan->departemenRelation) ? $dataKaryawan->departemenRelation->nama_jbt : '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">SKT Departemen</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->skt_dep ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">SKT Jabatan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->skt_jbt ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Wilayah Kerja</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map text-primary me-2"></i>
                                                    {{ $dataKaryawan->wilker ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Area Kerja</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-users-cog text-primary me-2"></i>
                                                    {{ $dataKaryawan->unit_krj ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">SKT Wilayah Kerja</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->skt_wil_krj ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-muted">Tugas & Tanggung Jawab</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-tasks text-primary me-2"></i>
                                                    {{ $dataKaryawan->tugas ?? '-' }}
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
                                        <h5 class="mb-0 text-white"><i class="fas fa-user-check me-2"></i>Status Hubungan
                                            Industrial</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Status Karyawan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-user-check text-primary me-2"></i>
                                                    @if ($dataKaryawan->sts_kry == 'AKTIF')
                                                        <span class="badge bg-success">{{ $dataKaryawan->sts_kry }}</span>
                                                    @elseif($dataKaryawan->sts_kry == 'CALON')
                                                        <span class="badge bg-warning">{{ $dataKaryawan->sts_kry }}</span>
                                                    @elseif($dataKaryawan->sts_kry == 'NON-AKTIF')
                                                        <span class="badge bg-danger">{{ $dataKaryawan->sts_kry }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Tanggal Masuk</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-calendar-plus text-primary me-2"></i>
                                                    {{ $dataKaryawan->tgl_masuk ? \Carbon\Carbon::parse($dataKaryawan->tgl_masuk)->format('d F Y') : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">NRK</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-id-badge text-primary me-2"></i>
                                                    {{ $dataKaryawan->nrk ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-muted">SKT Status Karyawan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->skt_sts_kry ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status PHK (jika ada) -->
                                @if ($dataKaryawan->sts_kry == 'NON-AKTIF' && ($dataKaryawan->tgl_phk || $dataKaryawan->ket_phk))
                                    <div class="card border-danger mb-4">
                                        <div class="card-header bg-danger bg-opacity-25">
                                            <h5 class="mb-0"><i class="fas fa-user-times me-2"></i>Informasi PHK</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold text-muted">Tanggal PHK</label>
                                                    <div class="detail-value">
                                                        <i class="fas fa-calendar-minus text-danger me-2"></i>
                                                        {{ $dataKaryawan->tgl_phk ? \Carbon\Carbon::parse($dataKaryawan->tgl_phk)->format('d F Y') : '-' }}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold text-muted">Keterangan PHK</label>
                                                    <div class="detail-value">
                                                        <i class="fas fa-comment-alt text-danger me-2"></i>
                                                        {{ $dataKaryawan->ket_phk ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Informasi Sistem -->
                                <div class="card border-info mb-4">
                                    <div class="card-header bg-info bg-opacity-25">
                                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Sistem</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Dibuat Oleh</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-user text-info me-2"></i>
                                                    {{ $dataKaryawan->creator ? $dataKaryawan->creator->nama_kry : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Tanggal Dibuat</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-calendar text-info me-2"></i>
                                                    {{ $dataKaryawan->created_at ? $dataKaryawan->created_at->format('d F Y H:i') : '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Diubah Oleh</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-user-edit text-info me-2"></i>
                                                    {{ $dataKaryawan->updater ? $dataKaryawan->updater->nama_kry : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Terakhir Diubah</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-calendar-edit text-info me-2"></i>
                                                    {{ $dataKaryawan->updated_at ? $dataKaryawan->updated_at->format('d F Y H:i') : '-' }}
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
@endsection

@push('styles')
    <style>
        .detail-value {
            padding: 12px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #0d6efd;
            font-size: 15px;
            min-height: 48px;
            display: flex;
            align-items: center;
        }

        .card-header {
            font-weight: 600;
        }

        .card {
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }

        .nav-tabs .nav-link {
            color: #495057;
        }

        .nav-tabs .nav-link.active {
            font-weight: 600;
        }

        .nav-tabs .nav-link:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Additional scripts can be added here if needed
    </script>
    @endpush@extends('layouts.app')

    @section('title', 'Detail Karyawan')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-user me-2"></i>Detail Karyawan</span>
                        <div>
                            @if (isset($userPermissions['ubah']) && $userPermissions['ubah'])
                                <a href="{{ route('data-karyawan.edit', $dataKaryawan->id) }}"
                                    class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            @endif
                            <a href="{{ route('data-karyawan.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Nav tabs for detail sections -->
                        <ul class="nav nav-tabs mb-4" id="detailTabs" role="tablist">
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
                            <!-- Data Pribadi -->
                            <div class="tab-pane fade show active" id="biodata" role="tabpanel"
                                aria-labelledby="biodata-tab">
                                <!-- Data Pribadi -->
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-id-card me-2"></i>Data Pribadi</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">NIK KTP</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-id-card-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->nik ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Nama Lengkap</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-user text-primary me-2"></i>
                                                    {{ $dataKaryawan->nama ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Tempat Lahir</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->tpt_lahir ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Tanggal Lahir</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->tgl_lahir ? \Carbon\Carbon::parse($dataKaryawan->tgl_lahir)->format('d F Y') : '-' }}
                                                    @if ($dataKaryawan->tgl_lahir)
                                                        <span class="badge bg-info ms-2">
                                                            {{ \Carbon\Carbon::parse($dataKaryawan->tgl_lahir)->age }}
                                                            tahun
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Jenis Kelamin</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-venus-mars text-primary me-2"></i>
                                                    {{ $dataKaryawan->sex ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Agama</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-pray text-primary me-2"></i>
                                                    {{ $dataKaryawan->agama ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-muted">Kewarganegaraan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-flag text-primary me-2"></i>
                                                    {{ $dataKaryawan->kewarganegaraan ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Keluarga -->
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-id-card me-2"></i>Status Keluarga
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Status Pernikahan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-heart text-primary me-2"></i>
                                                    {{ $dataKaryawan->sts_nikah ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Status dalam Keluarga</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-user-friends text-primary me-2"></i>
                                                    {{ $dataKaryawan->sts_keluarga ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Jumlah Anak</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-baby text-primary me-2"></i>
                                                    {{ $dataKaryawan->jml_anak ?? '0' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi Kontak -->
                                <div class="card border-secondary mb-4">
                                    <div class="card-header bg-secondary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-id-card me-2"></i>Informasi Kontak
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">No. Telepon Utama</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-phone text-primary me-2"></i>
                                                    {{ $dataKaryawan->tlp1 ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">No. Telepon Alternatif</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-mobile-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->tlp2 ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Email Utama</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-envelope text-primary me-2"></i>
                                                    {{ $dataKaryawan->email1 ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Email Alternatif</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-envelope text-primary me-2"></i>
                                                    {{ $dataKaryawan->email2 ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Instagram</label>
                                                <div class="detail-value">
                                                    <i class="fab fa-instagram text-primary me-2"></i>
                                                    {{ $dataKaryawan->instagram ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Facebook</label>
                                                <div class="detail-value">
                                                    <i class="fab fa-facebook text-primary me-2"></i>
                                                    {{ $dataKaryawan->facebook ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-muted">Dokumen</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-file-pdf text-primary me-2"></i>
                                                    @if ($dataKaryawan->foto_dokumen)
                                                        <a href="{{ Storage::url($dataKaryawan->foto_dokumen) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-download me-1"></i>Unduh Dokumen
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Alamat -->
                            <div class="tab-pane fade" id="alamat" role="tabpanel" aria-labelledby="alamat-tab">
                                <!-- Alamat KTP -->
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-id-card me-2"></i>Alamat KTP</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Provinsi</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-globe-asia text-primary me-2"></i>
                                                    {{ $dataKaryawan->prov_ktp ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kelurahan/Desa</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-building text-primary me-2"></i>
                                                    {{ $dataKaryawan->kel_ktp ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kota/Kabupaten</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map text-primary me-2"></i>
                                                    {{ $dataKaryawan->kota_ktp ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">RT/RW</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-home text-primary me-2"></i>
                                                    {{ $dataKaryawan->rt_rw_ktp ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kecamatan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-city text-primary me-2"></i>
                                                    {{ $dataKaryawan->kec_ktp ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kode Pos</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-mail-bulk text-primary me-2"></i>
                                                    {{ $dataKaryawan->kd_pos_ktp ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-muted">Alamat Lengkap</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map-marked-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->alamat_ktp ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alamat Domisili -->
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-house-user me-2"></i>Alamat Domisili
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Provinsi</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-globe-asia text-primary me-2"></i>
                                                    {{ $dataKaryawan->prov_dom ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kelurahan/Desa</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-building text-primary me-2"></i>
                                                    {{ $dataKaryawan->kel_dom ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kota/Kabupaten</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map text-primary me-2"></i>
                                                    {{ $dataKaryawan->kota_dom ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">RT/RW</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-home text-primary me-2"></i>
                                                    {{ $dataKaryawan->rt_rw_dom ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kecamatan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-city text-primary me-2"></i>
                                                    {{ $dataKaryawan->kec_dom ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kode Pos</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-mail-bulk text-primary me-2"></i>
                                                    {{ $dataKaryawan->kd_pos_dom ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-muted">Alamat Lengkap</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map-marked-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->alamat_dom ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pendidikan -->
                            <div class="tab-pane fade" id="pendidikan" role="tabpanel" aria-labelledby="pendidikan-tab">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-graduation-cap me-2"></i>Pendidikan
                                            Terakhir</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-muted">Jenjang Pendidikan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-level-up-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->jenjang_skl ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Nama Institusi</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-university text-primary me-2"></i>
                                                    {{ $dataKaryawan->institusi_skl ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Kota</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    {{ $dataKaryawan->kota_skl ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Fakultas</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-building-columns text-primary me-2"></i>
                                                    {{ $dataKaryawan->fakultas_skl ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Gelar</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-medal text-primary me-2"></i>
                                                    {{ $dataKaryawan->gelar_skl ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Jurusan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-book-open text-primary me-2"></i>
                                                    {{ $dataKaryawan->jurusan_skl ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Tanggal Lulus</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-calendar-check text-primary me-2"></i>
                                                    {{ $dataKaryawan->tgl_lulus_skl ? \Carbon\Carbon::parse($dataKaryawan->tgl_lulus_skl)->format('d F Y') : '-' }}
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
                                            Kontrak Kerja</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Status Kontrak</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-clipboard-check text-primary me-2"></i>
                                                    @if ($dataKaryawan->sts_ktr)
                                                        <span class="badge bg-info">{{ $dataKaryawan->sts_ktr }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Perusahaan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-building text-primary me-2"></i>
                                                    {{ $dataKaryawan->perusahaanRelation && is_object($dataKaryawan->perusahaanRelation) ? $dataKaryawan->perusahaanRelation->nama_prs1 : '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Tanggal Mulai Kontrak</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-calendar-plus text-primary me-2"></i>
                                                    {{ $dataKaryawan->tgl_awal_ktr ? \Carbon\Carbon::parse($dataKaryawan->tgl_awal_ktr)->format('d F Y') : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Tanggal Akhir Kontrak</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-calendar-minus text-primary me-2"></i>
                                                    {{ $dataKaryawan->tgl_akhir_ktr ? \Carbon\Carbon::parse($dataKaryawan->tgl_akhir_ktr)->format('d F Y') : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Durasi Kontrak</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-clock text-primary me-2"></i>
                                                    {{ $dataKaryawan->durasi_ktr ? $dataKaryawan->durasi_ktr . ' Bulan' : '-' }}
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
                                        <h5 class="mb-0 text-white"><i class="fas fa-briefcase me-2"></i>Informasi Jenjang
                                            Karir</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Departemen</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-sitemap text-primary me-2"></i>
                                                    {{ $dataKaryawan->departemen && is_object($dataKaryawan->departemen) ? $dataKaryawan->departemen->nama_dep : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Jabatan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-user-tie text-primary me-2"></i>
                                                    {{ $dataKaryawan->jabatan ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Unit Kerja</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-users-cog text-primary me-2"></i>
                                                    {{ $dataKaryawan->unit_krj ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Wilayah Kerja</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-map text-primary me-2"></i>
                                                    {{ $dataKaryawan->wilayahKerja && is_object($dataKaryawan->wilayahKerja) ? $dataKaryawan->wilayahKerja->wilayah_krj : '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-muted">Tugas & Tanggung Jawab</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-tasks text-primary me-2"></i>
                                                    {{ $dataKaryawan->tugas ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hubungan Industrial -->
                            <div class="tab-pane fade" id="hubin" role="tabpanel" aria-labelledby="hubin-tab">
                                <!-- Status Karyawan -->
                                <div class="card border-success mb-4">
                                    <div class="card-header bg-success bg-opacity-25">
                                        <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Status Hubungan
                                            Industrial</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Status Karyawan</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-user-check text-primary me-2"></i>
                                                    @if ($dataKaryawan->sts_kry == 'AKTIF')
                                                        <span
                                                            class="badge bg-success">{{ $dataKaryawan->sts_kry }}</span>
                                                    @elseif($dataKaryawan->sts_kry == 'CALON')
                                                        <span
                                                            class="badge bg-warning">{{ $dataKaryawan->sts_kry }}</span>
                                                    @elseif($dataKaryawan->sts_kry == 'NON-AKTIF')
                                                        <span class="badge bg-danger">{{ $dataKaryawan->sts_kry }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">Tanggal Masuk</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-calendar-plus text-primary me-2"></i>
                                                    {{ $dataKaryawan->tgl_masuk ? \Carbon\Carbon::parse($dataKaryawan->tgl_masuk)->format('d F Y') : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-muted">NRK</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-id-badge text-primary me-2"></i>
                                                    {{ $dataKaryawan->nrk ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status PHK (jika ada) -->
                                @if ($dataKaryawan->sts_kry == 'NON-AKTIF' && ($dataKaryawan->tgl_phk || $dataKaryawan->ket_phk))
                                    <div class="card border-danger mb-4">
                                        <div class="card-header bg-danger bg-opacity-25">
                                            <h5 class="mb-0"><i class="fas fa-user-times me-2"></i>Informasi PHK</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold text-muted">Tanggal PHK</label>
                                                    <div class="detail-value">
                                                        <i class="fas fa-calendar-minus text-danger me-2"></i>
                                                        {{ $dataKaryawan->tgl_phk ? \Carbon\Carbon::parse($dataKaryawan->tgl_phk)->format('d F Y') : '-' }}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold text-muted">Keterangan PHK</label>
                                                    <div class="detail-value">
                                                        <i class="fas fa-comment-alt text-danger me-2"></i>
                                                        {{ $dataKaryawan->ket_phk ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Informasi Sistem -->
                                <div class="card border-info mb-4">
                                    <div class="card-header bg-info bg-opacity-25">
                                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Sistem</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Dibuat Oleh</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-user text-info me-2"></i>
                                                    {{ $dataKaryawan->creator ? $dataKaryawan->creator->name : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Tanggal Dibuat</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-calendar text-info me-2"></i>
                                                    {{ $dataKaryawan->created_at ? $dataKaryawan->created_at->format('d F Y H:i') : '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Diubah Oleh</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-user-edit text-info me-2"></i>
                                                    {{ $dataKaryawan->updater ? $dataKaryawan->updater->name : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">Terakhir Diubah</label>
                                                <div class="detail-value">
                                                    <i class="fas fa-calendar-edit text-info me-2"></i>
                                                    {{ $dataKaryawan->updated_at ? $dataKaryawan->updated_at->format('d F Y H:i') : '-' }}
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
@endsection

@push('styles')
    <style>
        .detail-value {
            padding: 12px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #0d6efd;
            font-size: 15px;
            min-height: 48px;
            display: flex;
            align-items: center;
        }

        .card-header {
            font-weight: 600;
        }

        .card {
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }

        .nav-tabs .nav-link {
            color: #495057;
        }

        .nav-tabs .nav-link.active {
            font-weight: 600;
        }

        .nav-tabs .nav-link:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Additional scripts can be added here if needed
    </script>
@endpush
