@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-alt me-2"></i>Detail Dokumen HRD</span>
                        <div class="btn-group" role="group">
                            @if (auth()->user()->is_admin || ($userPermissions['ubah'] ?? false))
                                <a href="{{ route('dokumen-hrd.edit', $dokumen->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            @endif
                            <a href="{{ route('dokumen-hrd.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="kode_dok_hrd" class="form-label fw-bold">Kode Dokumen</label>
                                        <input type="text" class="form-control" id="kode_dok_hrd"
                                            value="{{ $dokumen->kode_dok_hrd }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ktg_dok_hrd" class="form-label fw-bold">Kategori Dokumen</label>
                                        <input type="text" class="form-control" id="ktg_dok_hrd"
                                            value="{{ $dokumen->ktg_dok_hrd }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jns_dok_hrd" class="form-label fw-bold">Jenis Dokumen</label>
                                        <input type="text" class="form-control" id="jns_dok_hrd"
                                            value="{{ $dokumen->jns_dok_hrd ?? '-' }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-bold mb-3"><i class="fas fa-clock me-2"></i>Informasi Audit</h6>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="created_by" class="form-label fw-bold">Dibuat Oleh</label>
                                        <input type="text" class="form-control" id="created_by"
                                            value="{{ $dokumen->creator->name ?? $dokumen->created_by ?? '-' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="created_at" class="form-label fw-bold">Tanggal Dibuat</label>
                                        <input type="text" class="form-control" id="created_at"
                                            value="{{ $dokumen->created_at ? $dokumen->created_at->format('d M Y H:i:s') : '-' }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="updated_by" class="form-label fw-bold">Diubah Oleh</label>
                                        <input type="text" class="form-control" id="updated_by"
                                            value="{{ $dokumen->updater->name ?? $dokumen->updated_by ?? '-' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="updated_at" class="form-label fw-bold">Tanggal Diubah</label>
                                        <input type="text" class="form-control" id="updated_at"
                                            value="{{ ($dokumen->updated_at && $dokumen->updated_at !== $dokumen->created_at) ? $dokumen->updated_at->format('d M Y H:i:s') : '-' }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-control:disabled {
            background-color: #e9ecef;
            color: #495057;
            cursor: not-allowed;
            opacity: 1;
            border-color: #dee2e6;
        }

        .form-label {
            margin-bottom: 0.5rem;
        }
    </style>
@endpush