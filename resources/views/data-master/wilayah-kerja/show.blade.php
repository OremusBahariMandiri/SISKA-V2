@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-map-marked-alt me-2"></i>Detail Wilayah Kerja</span>
                        <div>
                            <a href="{{ route('wilayah-kerja.edit', $wilayahKerja->id) }}" class="btn btn-warning btn-sm me-2">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <a href="{{ route('wilayah-kerja.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Informasi Wilayah -->
                            <div class="col-md-6">
                                <div class="card h-100 border-secondary">
                                    <div class="card-header bg-secondary bg-opacity-25 text-white">
                                        <h5 class="mb-0"><i class="fas fa-map-marked-alt me-2"></i>Informasi Wilayah</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Kode Wilayah Kerja</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                    <div class="form-control">{{ $wilayahKerja->kode_wk }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Wilayah Kerja</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                    <div class="form-control">{{ $wilayahKerja->wilayah_krj }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">SKT Wilayah Kerja</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                    <div class="form-control">{{ $wilayahKerja->skt_wilker }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Area Kerja</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-location-dot"></i></span>
                                                    <div class="form-control">{{ $wilayahKerja->area_krj }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($wilayahKerja->singkatan_wk)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Singkatan</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                        <div class="form-control">{{ $wilayahKerja->singkatan_wk }}</div>
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
                                                        {{ $wilayahKerja->full_address }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($wilayahKerja->rt_rw_wk)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">RT/RW</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                                                        <div class="form-control">{{ $wilayahKerja->rt_rw_wk }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($wilayahKerja->kel_wk)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Kelurahan</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                        <div class="form-control">{{ $wilayahKerja->kel_wk }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($wilayahKerja->kec_wk)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Kecamatan</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                        <div class="form-control">{{ $wilayahKerja->kec_wk }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Kota</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-city"></i></span>
                                                    <div class="form-control">{{ $wilayahKerja->kota_wk }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Provinsi</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
                                                    <div class="form-control">{{ $wilayahKerja->prov_wk }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($wilayahKerja->kd_pos_wk)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Kode Pos</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                                        <div class="form-control">{{ $wilayahKerja->kd_pos_wk }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Kontak & Dokumen -->
                            <div class="col-md-6">
                                <div class="card h-100 border-secondary">
                                    <div class="card-header bg-secondary bg-opacity-25 text-white">
                                        <h5 class="mb-0"><i class="fas fa-phone me-2"></i>Kontak & Dokumen</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Telepon</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    <div class="form-control">{{ $wilayahKerja->tlp1 }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($wilayahKerja->tlp2)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Telepon 2</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                                        <div class="form-control">{{ $wilayahKerja->tlp2 }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Email</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    <div class="form-control">{{ $wilayahKerja->email1 }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($wilayahKerja->email2)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Email 2</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                        <div class="form-control">{{ $wilayahKerja->email2 }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($wilayahKerja->instagram)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Instagram</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                                        <div class="form-control">
                                                            <a href="https://instagram.com/{{ ltrim($wilayahKerja->instagram, '@') }}" target="_blank">{{ $wilayahKerja->instagram }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($wilayahKerja->facebook)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Facebook</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                                        <div class="form-control">
                                                            <a href="{{ $wilayahKerja->facebook }}" target="_blank">{{ $wilayahKerja->facebook }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($wilayahKerja->foto_dokumen)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Foto/Dokumen</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                        <div class="form-control">
                                                            <a href="{{ Storage::url($wilayahKerja->foto_dokumen) }}" target="_blank" class="text-decoration-none">
                                                                <i class="fas fa-download me-1"></i>
                                                                {{ basename($wilayahKerja->foto_dokumen) }}
                                                            </a>

                                                            @php
                                                                $extension = pathinfo($wilayahKerja->foto_dokumen, PATHINFO_EXTENSION);
                                                                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                                            @endphp

                                                            @if (in_array(strtolower($extension), $imageExtensions))
                                                                <div class="mt-2">
                                                                    <img src="{{ Storage::url($wilayahKerja->foto_dokumen) }}"
                                                                         alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                                                </div>
                                                            @endif
                                                        </div>
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