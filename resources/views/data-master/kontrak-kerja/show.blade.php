@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-eye me-2"></i>Detail Kontrak Kerja</span>
                        <div>
                            @if (auth()->user()->is_admin || auth()->user()->userAccess()->where('menu_acs', 'kontrak-kerja')->where('ubah_acs', true)->exists())
                                <a href="{{ route('kontrak-kerja.edit', $kontrak->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            @endif
                            <a href="{{ route('kontrak-kerja.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Informasi Utama Kontrak -->
                            <div class="col-md-12">
                                <div class="card h-100 border-primary">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-info-circle me-2"></i>Informasi Kontrak</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-bold"><i class="fas fa-barcode me-2"></i>Kode Kontrak</td>
                                                <td>: <span class="badge bg-primary">{{ $kontrak->kode_ktr }}</span></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold"><i class="fas fa-file-signature me-2"></i>Nama Kontrak</td>
                                                <td>: {{ $kontrak->nama_ktr }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold"><i class="fas fa-tag me-2"></i>Singkatan Kontrak</td>
                                                <td>:
                                                    @if($kontrak->singkatan_ktr)
                                                        <span class="badge bg-info">{{ $kontrak->singkatan_ktr }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistik Karyawan -->
                            {{-- <div class="col-md-6">
                                <div class="card h-100 border-success">
                                    <div class="card-header bg-success bg-opacity-25">
                                        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Statistik Karyawan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <div class="display-4 text-success">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <h2 class="mt-2">{{ $kontrak->karyawan_count }}</h2>
                                            <p class="text-muted">Total Karyawan dengan Kontrak Ini</p>
                                        </div>

                                        @if($kontrak->karyawan_count > 0)
                                            <div class="alert alert-success">
                                                <i class="fas fa-check-circle me-2"></i>
                                                Kontrak ini sedang digunakan oleh <strong>{{ $kontrak->karyawan_count }}</strong> karyawan
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Kontrak ini belum digunakan oleh karyawan manapun
                                            </div>
                                        @endif

                                        @if($kontrak->karyawan_count > 0)
                                            <a href="#" class="btn btn-sm btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#karyawanListModal">
                                                <i class="fas fa-list me-1"></i>Lihat Daftar Karyawan
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div> --}}

                            <!-- Informasi Audit -->
                            <div class="col-md-12">
                                <div class="card border-secondary">
                                    <div class="card-header bg-secondary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-history me-2"></i>Informasi Audit</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h6 class="card-title"><i class="fas fa-user-plus me-2"></i>Dibuat oleh</h6>
                                                        <p class="mb-1"><strong>{{ $kontrak->creator->name ?? 'Sistem' }}</strong></p>
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            {{ $kontrak->created_at ? $kontrak->created_at->format('d M Y H:i:s') : '-' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h6 class="card-title"><i class="fas fa-user-edit me-2"></i>Terakhir diubah</h6>
                                                        <p class="mb-1"><strong>{{ $kontrak->updater->name ?? 'Belum ada perubahan' }}</strong></p>
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            {{ $kontrak->updated_at && $kontrak->updated_at != $kontrak->created_at ? $kontrak->updated_at->format('d M Y H:i:s') : '-' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-center mt-4">
                            @if (auth()->user()->is_admin || auth()->user()->userAccess()->where('menu_acs', 'kontrak-kerja')->where('ubah_acs', true)->exists())
                                <a href="{{ route('kontrak-kerja.edit', $kontrak->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i>Edit Kontrak
                                </a>
                            @endif

                            @if (auth()->user()->is_admin || auth()->user()->userAccess()->where('menu_acs', 'kontrak-kerja')->where('hapus_acs', true)->exists())
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                    @if($kontrak->karyawan_count > 0) disabled title="Tidak dapat menghapus kontrak yang masih digunakan" @endif>
                                    <i class="fas fa-trash me-1"></i>Hapus Kontrak
                                </button>
                            @endif

                            <a href="{{ route('kontrak-kerja.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Daftar Karyawan -->
    @if($kontrak->karyawan_count > 0)
    <div class="modal fade" id="karyawanListModal" tabindex="-1" aria-labelledby="karyawanListModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="karyawanListModalLabel">
                        <i class="fas fa-users me-2"></i>Daftar Karyawan dengan Kontrak {{ $kontrak->kode_kontrak }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-success">
                                <tr>
                                    <th>No</th>
                                    <th>NRK</th>
                                    <th>Nama</th>
                                    <th>Departemen</th>
                                    <th>Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kontrak->karyawan as $karyawan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $karyawan->nrk ?? '-' }}</td>
                                    <td>{{ $karyawan->nama }}</td>
                                    <td>{{ $karyawan->departemenRelation->nama_dept ?? '-' }}</td>
                                    <td>{{ $karyawan->jabatan ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Delete Confirmation -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kontrak <strong>{{ $kontrak->nama_kontrak }}</strong>?</p>
                    <p class="text-danger"><i class="fas fa-info-circle me-1"></i>Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <form action="{{ route('kontrak-kerja.destroy', $kontrak->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .table-borderless td {
            padding: 0.5rem 0;
        }

        .badge {
            font-size: 0.9em;
        }

        .display-4 {
            font-size: 3rem;
        }

        .card-body {
            min-height: 200px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@endpush