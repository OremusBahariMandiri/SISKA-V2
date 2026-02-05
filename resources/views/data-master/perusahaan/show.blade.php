@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-building me-2"></i>Detail Perusahaan</span>
                        <div>
                            <a href="{{ route('perusahaan.edit', $perusahaan->id) }}" class="btn btn-warning btn-sm me-2">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <a href="{{ route('perusahaan.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Informasi Perusahaan -->
                            <div class="col-md-6">
                                <div class="card h-100 border-secondary">
                                    <div class="card-header bg-secondary bg-opacity-25 text-white">
                                        <h5 class="mb-0"><i class="fas fa-building me-2"></i>Informasi Perusahaan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Nama Perusahaan</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <div class="form-control">{{ $perusahaan->nama_prs1 }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($perusahaan->nama_prs2)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Singkatan Perusahaan</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-house"></i></span>
                                                        <div class="form-control">{{ $perusahaan->nama_prs2 }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Alamat Lengkap</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                    <div class="form-control text-break" style="min-height: 60px; white-space: pre-line;">
                                                        {{ $perusahaan->full_address }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($perusahaan->rt_rw_prs)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">RT/RW</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                                                        <div class="form-control">{{ $perusahaan->rt_rw_prs }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($perusahaan->kel_prs)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Kelurahan</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                        <div class="form-control">{{ $perusahaan->kel_prs }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($perusahaan->kec_prs)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Kecamatan</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                        <div class="form-control">{{ $perusahaan->kec_prs }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Kota</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-city"></i></span>
                                                    <div class="form-control">{{ $perusahaan->kota_prs }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Provinsi</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
                                                    <div class="form-control">{{ $perusahaan->prov_prs }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($perusahaan->kd_pos_prs)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Kode Pos</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                                        <div class="form-control">{{ $perusahaan->kd_pos_prs }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Kontak & Informasi Bisnis -->
                            <div class="col-md-6">
                                <div class="card h-100 border-secondary">
                                    <div class="card-header bg-secondary bg-opacity-25 text-white">
                                        <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Kontak & Informasi Bisnis</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Telepon</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    <div class="form-control">{{ $perusahaan->tlp1 }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($perusahaan->tlp2)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Telepon 2</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                                        <div class="form-control">{{ $perusahaan->tlp2 }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Email</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    <div class="form-control">{{ $perusahaan->email1 }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($perusahaan->email2)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Email 2</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                        <div class="form-control">{{ $perusahaan->email2 }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($perusahaan->instagram)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Instagram</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                                        <div class="form-control">
                                                            <a href="https://instagram.com/{{ ltrim($perusahaan->instagram, '@') }}" target="_blank">{{ $perusahaan->instagram }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($perusahaan->facebook)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Facebook</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                                        <div class="form-control">
                                                            <a href="{{ $perusahaan->facebook }}" target="_blank">{{ $perusahaan->facebook }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($perusahaan->web)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Website</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                        <div class="form-control">
                                                            <a href="{{ $perusahaan->web }}" target="_blank">{{ $perusahaan->web }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($perusahaan->tgl_pendirian)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Tanggal Pendirian</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                        <div class="form-control">{{ $perusahaan->tgl_pendirian->format('d-m-Y') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Bidang Usaha</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-industry"></i></span>
                                                    <div class="form-control">{{ $perusahaan->bidang_ush }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($perusahaan->ijin_ush)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Izin Usaha</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                        <div class="form-control">{{ $perusahaan->ijin_ush }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($perusahaan->golongan_ush)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Golongan Usaha</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                                        <div class="form-control">{{ $perusahaan->golongan_ush }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Direktur Utama</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                    <div class="form-control">{{ $perusahaan->dirut }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($perusahaan->direktur)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Direktur</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                        <div class="form-control">{{ $perusahaan->direktur }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($perusahaan->komisaris_utm)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Komisaris Utama</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                        <div class="form-control">{{ $perusahaan->komisaris_utm }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($perusahaan->komisaris1)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Komisaris</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                        <div class="form-control">{{ $perusahaan->komisaris1 }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($perusahaan->komisaris2)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Komisaris 2</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                        <div class="form-control">{{ $perusahaan->komisaris2 }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($perusahaan->komisaris3)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Komisaris 3</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                        <div class="form-control">{{ $perusahaan->komisaris3 }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
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
        .card-header {
            font-weight: 600;
        }

        .info-label {
            margin-bottom: 0.3rem;
            display: block;
        }

        .info-group {
            margin-bottom: 1rem;
        }

        .info-value .form-control {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            display: flex;
            align-items: center;
            min-height: 38px;
        }

        .card {
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .input-group-text {
            background-color: #e9ecef;
            border: 1px solid #dee2e6;
        }

        .info-value .form-control a {
            color: #0d6efd;
            text-decoration: none;
        }

        .info-value .form-control a:hover {
            color: #0a58ca;
            text-decoration: underline;
        }
    </style>
@endpush