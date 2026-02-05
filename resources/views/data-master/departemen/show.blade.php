@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-sitemap me-2"></i>Detail Departemen</span>
                        <div>
                            <a href="{{ route('departemen.edit', $departemen->id) }}" class="btn btn-warning btn-sm me-2">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <a href="{{ route('departemen.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Informasi Departemen -->
                        <div class="card border-secondary mb-4">
                            <div class="card-header bg-secondary bg-opacity-25 text-white">
                                <h5 class="mb-0"><i class="fas fa-sitemap me-2"></i>Informasi Departemen</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Kode Departemen</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                    <div class="form-control">{{ $departemen->kode_dep }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Nama Departemen</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <div class="form-control">{{ $departemen->nama_dep }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        @if ($departemen->singkatan_dep)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Singkatan Departemen</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                        <div class="form-control">{{ $departemen->singkatan_dep }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Display Name</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-eye"></i></span>
                                                    <div class="form-control">{{ $departemen->display_name }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Jabatan -->
                        <div class="card border-secondary mb-4">
                            <div class="card-header bg-secondary bg-opacity-25 text-white">
                                <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Informasi Jabatan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Nama Jabatan</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                    <div class="form-control">{{ $departemen->nama_jbt }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($departemen->singkatan_jbt)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Singkatan Jabatan</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                        <div class="form-control">{{ $departemen->singkatan_jbt }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Display Position</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-eye"></i></span>
                                                    <div class="form-control">{{ $departemen->display_position }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Metadata -->
                        <div class="card border-info">
                            <div class="card-header bg-info bg-opacity-25 text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Sistem</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-group mb-3">
                                            <label class="info-label fw-bold">Dibuat Pada</label>
                                            <div class="info-value">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
                                                    <div class="form-control">
                                                        {{ $departemen->created_at ? $departemen->created_at->format('d/m/Y H:i:s') : '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($departemen->creator)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Dibuat Oleh</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-user-plus"></i></span>
                                                        <div class="form-control">{{ $departemen->creator->nama_kry }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        @if ($departemen->updated_at && $departemen->updated_at != $departemen->created_at)
                                            <div class="info-group mb-3">
                                                <label class="info-label fw-bold">Diperbarui Pada</label>
                                                <div class="info-value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                                        <div class="form-control">
                                                            {{ $departemen->updated_at->format('d/m/Y H:i:s') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($departemen->updater)
                                                <div class="info-group mb-3">
                                                    <label class="info-label fw-bold">Diperbarui Oleh</label>
                                                    <div class="info-value">
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-user-edit"></i></span>
                                                            <div class="form-control">{{ $departemen->updater->nama_kry }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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
    </style>
@endpush