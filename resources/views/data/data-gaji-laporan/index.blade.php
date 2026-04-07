@extends('layouts.app')

@section('title', 'Pelaporan Gaji Karyawan')

@section('content')
    <div class="container-fluid dataGajiPelaporanPage">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-money-bill-wave me-2"></i>Pelaporan Gaji Karyawan</span>
                        <div>
                            <button type="button" class="btn btn-light me-2" id="summaryButton">
                                <i class="fas fa-chart-pie me-1"></i> Ringkasan
                            </button>
                            <button type="button" class="btn btn-light me-2" id="filterButton">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <button type="button" class="btn btn-light me-2" id="exportButton">
                                <i class="fas fa-download me-1"></i> Export
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3 document-status-summary">
                            <span class="badge bg-primary me-2" style="font-size: 0.9rem;">
                                <i class="fas fa-file-invoice-dollar me-1"></i> Total Data Gaji: <strong>{{ number_format($totalGaji) }}</strong>
                            </span>
                            <span class="badge bg-success me-2" style="font-size: 0.9rem;">
                                <i class="fas fa-check-circle me-1"></i> Aktif: <strong>{{ number_format($totalAktif) }}</strong>
                            </span>
                            <span class="badge bg-secondary me-2" style="font-size: 0.9rem;">
                                <i class="fas fa-times-circle me-1"></i> Non-Aktif: <strong>{{ number_format($totalNonAktif) }}</strong>
                            </span>
                            <span class="badge bg-info me-2" style="font-size: 0.9rem;">
                                <i class="fas fa-users me-1"></i> Total Karyawan: <strong>{{ number_format($totalKaryawan) }}</strong>
                            </span>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Active Filter Display -->
                        @php
                            $hasActiveFilters = !empty($currentFilters['status']) ||
                                !empty($currentFilters['nama']) ||
                                !empty($currentFilters['nrk']) ||
                                !empty($currentFilters['nik']) ||
                                !empty($currentFilters['id_gaji']) ||
                                !empty($currentFilters['departemen']) ||
                                !empty($currentFilters['jabatan']) ||
                                !empty($currentFilters['jenis_kelamin']) ||
                                !empty($currentFilters['wilker']) ||
                                !empty($currentFilters['unit_kerja']) ||
                                !empty($currentFilters['gaji_min']) ||
                                !empty($currentFilters['gaji_max']);
                        @endphp

                        @if ($hasActiveFilters)
                            <div class="alert alert-info" role="alert" id="filterActiveAlert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Filter Aktif:</strong>

                                @if (!empty($currentFilters['status']))
                                    Status: <span class="badge bg-primary">{{ $currentFilters['status'] }}</span>
                                @endif

                                @if (!empty($currentFilters['nama']))
                                    Nama: <span class="badge bg-primary">{{ $currentFilters['nama'] }}</span>
                                @endif

                                @if (!empty($currentFilters['nrk']))
                                    NRK: <span class="badge bg-primary">{{ $currentFilters['nrk'] }}</span>
                                @endif

                                @if (!empty($currentFilters['nik']))
                                    NIK: <span class="badge bg-primary">{{ $currentFilters['nik'] }}</span>
                                @endif

                                @if (!empty($currentFilters['id_gaji']))
                                    ID Gaji: <span class="badge bg-info">{{ $currentFilters['id_gaji'] }}</span>
                                @endif

                                @if (!empty($currentFilters['departemen']))
                                    @php
                                        $selectedDepartemen = $departemenOptions->where('id', $currentFilters['departemen'])->first();
                                    @endphp
                                    @if ($selectedDepartemen)
                                        Departemen: <span class="badge bg-warning">{{ $selectedDepartemen->nama_dep }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['jabatan']))
                                    @php
                                        $selectedJabatan = $jabatanOptions->where('id', $currentFilters['jabatan'])->first();
                                    @endphp
                                    @if ($selectedJabatan)
                                        Jabatan: <span class="badge bg-success">{{ $selectedJabatan->nama_jbt }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['jenis_kelamin']))
                                    Jenis Kelamin: <span class="badge bg-dark">{{ $jenisKelaminOptions[$currentFilters['jenis_kelamin']] }}</span>
                                @endif

                                @if (!empty($currentFilters['wilker']))
                                    Wilayah Kerja: <span class="badge bg-danger">{{ $currentFilters['wilker'] }}</span>
                                @endif

                                @if (!empty($currentFilters['unit_kerja']))
                                    @php
                                        $selectedUnit = $unitKerjaOptions->where('id', $currentFilters['unit_kerja'])->first();
                                    @endphp
                                    @if ($selectedUnit)
                                        Unit Kerja: <span class="badge bg-info">{{ $selectedUnit->area_krj }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['gaji_min']) || !empty($currentFilters['gaji_max']))
                                    Range Gaji: <span class="badge bg-success">
                                        Rp {{ number_format($currentFilters['gaji_min'] ?? 0) }} - Rp {{ number_format($currentFilters['gaji_max'] ?? 999999999) }}
                                    </span>
                                @endif

                                <a href="{{ route('data-gaji-laporan.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Reset Filter
                                </a>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="dataGajiPelaporanTable" class="table table-bordered table-striped table-hover data-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="1%" class="text-center">NO</th>
                                        <th width="3%" class="text-center">NRK</th>
                                        <th width="5%" class="text-center">NAMA</th>
                                        <th width="1%" class="text-center">SEX</th>
                                        <th width="2%" class="text-center">DEP</th>
                                        <th width="2%" class="text-center">JBT</th>
                                        <th width="2%" class="text-center">WILKER</th>
                                        <th width="2%" class="text-center">AREA</th>
                                        <th width="3%" class="text-center">ID GAJI</th>
                                        <th width="4%" class="text-center">GAJI POKOK</th>
                                        <th width="4%" class="text-center">TTL PENDAPATAN</th>
                                        <th width="4%" class="text-center">TTL POTONGAN</th>
                                        <th width="4%" class="text-center">GAJI BERSIH</th>
                                        <th width="2%" class="text-center">STATUS</th>
                                        <th width="4%" class="text-center">DIBUAT</th>
                                        <th width="3%" class="text-center no-wrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataGajis as $gaji)
                                        @php
                                            $karyawan = $gaji->karyawan;
                                            $departemen = $karyawan ? $karyawan->departemenRelation : null;
                                            $wilayah = $karyawan ? $karyawan->wilayahKerjaRelation : null;
                                            $unitKerja = $karyawan ? $karyawan->unitKerjaRelation : null;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>

                                            <td>
                                                <span class="fw-bold">{{ $karyawan->nrk ?? '-' }}</span><br>
                                                <small class="text-muted">{{ $karyawan->nik ?? '-' }}</small>
                                            </td>

                                            <td>
                                                <div class="fw-bold">{{ $karyawan->nama ?? '-' }}</div>
                                            </td>

                                            <td class="text-center">
                                                @if ($karyawan && $karyawan->sex)
                                                    <span>{{ $karyawan->sex == 'LAKI-LAKI' ? 'L' : 'P' }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($departemen)
                                                    <span>{{ $departemen->singkatan_dep }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <span>{{ $departemen->singkatan_jbt ?? '-' }}</span>
                                            </td>

                                            <td class="text-center">
                                                <span>{{ $wilayah->wilayah_krj ?? '-' }}</span>
                                            </td>

                                            <td class="text-center">
                                                <span>{{ $unitKerja->area_krj ?? '-' }}</span>
                                            </td>

                                            <td class="text-center">
                                                <span class="fw-bold text-primary">{{ $gaji->id_gaji ?? '-' }}</span>
                                            </td>

                                            <td class="text-end">
                                                <span class="fw-bold">Rp {{ number_format($gaji->gj_pokok ?? 0, 0, ',', '.') }}</span>
                                            </td>

                                            <td class="text-end">
                                                <span class="text-success">Rp {{ number_format($gaji->ttl_pendapatan ?? 0, 0, ',', '.') }}</span>
                                            </td>

                                            <td class="text-end">
                                                <span class="text-danger">Rp {{ number_format($gaji->ttl_potongan ?? 0, 0, ',', '.') }}</span>
                                            </td>

                                            <td class="text-end">
                                                <span class="fw-bold text-success">Rp {{ number_format($gaji->ttl_terima_gaji ?? 0, 0, ',', '.') }}</span>
                                            </td>

                                            <td class="text-center">
                                                @if ($gaji->sts_data_gaji == 'AKTIF')
                                                    <span class="badge bg-success">AKTIF</span>
                                                @else
                                                    <span class="badge bg-secondary">NON-AKTIF</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                {{ $gaji->creator->nama_kry ?? '-' }}<br>
                                                <small class="text-muted">{{ $gaji->created_at ? $gaji->created_at->format('d/m/y H:i') : '-' }}</small>
                                            </td>

                                            <td class="text-center no-wrap">
                                                <div class="btn-group" role="group">
                                                    @if (auth()->user()->is_admin || ($userPermissions['detail'] ?? false))
                                                        <a href="{{ route('data-gaji-laporan.show', $gaji->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-filter me-2"></i>Filter Data Gaji</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="GET" action="{{ route('data-gaji-laporan.index') }}">
                        <div class="row mb-3">
                            {{-- Status --}}
                            <div class="col-md-6">
                                <label for="filter_status" class="form-label fw-bold">Status</label>
                                <select class="form-select select2" id="filter_status" name="filter_status">
                                    <option value="">Semua Status</option>
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ ($currentFilters['status'] ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div class="col-md-6">
                                <label for="filter_jenis_kelamin" class="form-label fw-bold">Jenis Kelamin</label>
                                <select class="form-select select2" id="filter_jenis_kelamin" name="filter_jenis_kelamin">
                                    <option value="">Semua Jenis Kelamin</option>
                                    @foreach ($jenisKelaminOptions as $value => $label)
                                        <option value="{{ $value }}" {{ ($currentFilters['jenis_kelamin'] ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Nama --}}
                            <div class="col-md-6 mt-2">
                                <label for="filter_nama" class="form-label fw-bold">Nama Karyawan</label>
                                <input type="text" class="form-control" id="filter_nama" name="filter_nama"
                                    placeholder="Cari nama karyawan..." value="{{ $currentFilters['nama'] ?? '' }}">
                            </div>

                            {{-- NRK --}}
                            <div class="col-md-6 mt-2">
                                <label for="filter_nrk" class="form-label fw-bold">NRK</label>
                                <input type="text" class="form-control" id="filter_nrk" name="filter_nrk"
                                    placeholder="Cari NRK..." value="{{ $currentFilters['nrk'] ?? '' }}">
                            </div>

                            {{-- NIK --}}
                            <div class="col-md-6 mt-2">
                                <label for="filter_nik" class="form-label fw-bold">NIK</label>
                                <input type="text" class="form-control" id="filter_nik" name="filter_nik"
                                    placeholder="Cari NIK..." value="{{ $currentFilters['nik'] ?? '' }}">
                            </div>

                            {{-- ID Gaji --}}
                            <div class="col-md-6 mt-2">
                                <label for="filter_id_gaji" class="form-label fw-bold">ID Gaji</label>
                                <input type="text" class="form-control" id="filter_id_gaji" name="filter_id_gaji"
                                    placeholder="Cari ID Gaji..." value="{{ $currentFilters['id_gaji'] ?? '' }}">
                            </div>

                            {{-- Departemen --}}
                            <div class="col-md-6 mt-2">
                                <label for="filter_departemen" class="form-label fw-bold">Departemen</label>
                                <select class="form-select select2" id="filter_departemen" name="filter_departemen">
                                    <option value="">Semua Departemen</option>
                                    @foreach ($departemenOptions as $departemen)
                                        <option value="{{ $departemen->id }}" {{ ($currentFilters['departemen'] ?? '') == $departemen->id ? 'selected' : '' }}>
                                            {{ $departemen->nama_dep }} @if ($departemen->singkatan_dep)({{ $departemen->singkatan_dep }})@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jabatan --}}
                            <div class="col-md-6 mt-2">
                                <label for="filter_jabatan" class="form-label fw-bold">Jabatan</label>
                                <select class="form-select select2" id="filter_jabatan" name="filter_jabatan">
                                    <option value="">Semua Jabatan</option>
                                    @foreach ($jabatanOptions as $jabatan)
                                        <option value="{{ $jabatan->id }}" {{ ($currentFilters['jabatan'] ?? '') == $jabatan->id ? 'selected' : '' }}>
                                            {{ $jabatan->nama_jbt }} @if ($jabatan->singkatan_jbt)({{ $jabatan->singkatan_jbt }})@endif - {{ $jabatan->nama_dep }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Wilayah Kerja --}}
                            <div class="col-md-6 mt-2">
                                <label for="filter_wilker" class="form-label fw-bold">Wilayah Kerja</label>
                                <select class="form-select select2" id="filter_wilker" name="filter_wilker">
                                    <option value="">Semua Wilayah Kerja</option>
                                    @foreach ($wilayahKerjaOptions as $wilker)
                                        <option value="{{ $wilker->wilayah_krj }}" {{ ($currentFilters['wilker'] ?? '') == $wilker->wilayah_krj ? 'selected' : '' }}>
                                            {{ $wilker->wilayah_krj }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Unit Kerja --}}
                            <div class="col-md-6 mt-2">
                                <label for="filter_unit_kerja" class="form-label fw-bold">Area Kerja</label>
                                <select class="form-select select2" id="filter_unit_kerja" name="filter_unit_kerja">
                                    <option value="">Semua Area Kerja</option>
                                    @foreach ($unitKerjaOptions as $unitKerja)
                                        <option value="{{ $unitKerja->id }}" {{ ($currentFilters['unit_kerja'] ?? '') == $unitKerja->id ? 'selected' : '' }}>
                                            {{ $unitKerja->area_krj }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Range Gaji Pokok --}}
                            <div class="col-md-6 mt-2">
                                <label for="filter_gaji_min" class="form-label fw-bold">Gaji Pokok Minimum</label>
                                <input type="number" class="form-control" id="filter_gaji_min" name="filter_gaji_min"
                                    placeholder="Rp 0" value="{{ $currentFilters['gaji_min'] ?? '' }}">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label for="filter_gaji_max" class="form-label fw-bold">Gaji Pokok Maximum</label>
                                <input type="number" class="form-control" id="filter_gaji_max" name="filter_gaji_max"
                                    placeholder="Rp 999,999,999" value="{{ $currentFilters['gaji_max'] ?? '' }}">
                            </div>

                            {{-- Buttons --}}
                            <div class="col-md-12 mt-3 text-end">
                                <button type="button" class="btn btn-secondary me-2" id="resetFilter">
                                    <i class="fas fa-redo me-1"></i>Reset Semua Filter
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Modal -->
    <div class="modal fade" id="summaryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-chart-pie me-2"></i>Ringkasan Data Gaji</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-muted">Total Data Gaji</h6>
                                    <h3 class="text-primary">{{ number_format($totalGaji) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-muted">Total Karyawan</h6>
                                    <h3 class="text-info">{{ number_format($totalKaryawan) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-success text-white mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Data Aktif</h6>
                                    <h3>{{ number_format($totalAktif) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-secondary text-white mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Data Non-Aktif</h6>
                                    <h3>{{ number_format($totalNonAktif) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-muted">Total Pendapatan</h6>
                                    <h4 class="text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-muted">Total Potongan</h6>
                                    <h4 class="text-danger">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card bg-primary text-white mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Total Gaji Bersih</h6>
                                    <h3>Rp {{ number_format($totalGajiBersih, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-info text-white mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Rata-rata Gaji Pokok</h6>
                                    <h4>Rp {{ number_format($rataRataGajiPokok, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-warning text-dark mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Rata-rata Gaji Bersih</h6>
                                    <h4>Rp {{ number_format($rataRataGajiBersih, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-download me-2"></i>Export Data Gaji</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Pilih format export yang diinginkan:</p>
                    <form id="exportForm" method="GET" action="{{ route('data-gaji-laporan.index') }}">
                        @foreach ($currentFilters as $key => $value)
                            @if (!empty($value))
                                <input type="hidden" name="filter_{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <div class="d-grid gap-2">
                            <button type="submit" name="export" value="excel" class="btn btn-success">
                                <i class="fas fa-file-excel me-2"></i>Export ke Excel
                            </button>
                            <button type="submit" name="export" value="pdf" class="btn btn-danger">
                                <i class="fas fa-file-pdf me-2"></i>Export ke PDF
                            </button>
                            <button type="submit" name="export" value="csv" class="btn btn-info">
                                <i class="fas fa-file-csv me-2"></i>Export ke CSV
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        .dataGajiPelaporanPage .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table th {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            font-weight: 600;
            font-size: 0.75rem;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
            border-color: #dee2e6;
            font-size: 0.75rem;
        }

        .no-wrap {
            white-space: nowrap !important;
            min-width: 100px !important;
        }

        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            z-index: 1070 !important;
        }

        .modal .select2-container {
            z-index: 1070 !important;
        }

        .modal .select2-dropdown {
            z-index: 1071 !important;
        }

        .document-status-summary {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.7rem;
            }

            .btn {
                font-size: 0.65rem;
                padding: 0.2rem 0.4rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            console.log('Data Gaji Pelaporan system initializing...');

            // Initialize Select2
            function initializeSelect2InModal() {
                $('#filterModal .select2').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }

                    $(this).select2({
                        theme: 'bootstrap-5',
                        dropdownParent: $('#filterModal'),
                        width: '100%',
                        placeholder: $(this).find('option:first').text() || 'Pilih...',
                        allowClear: true,
                        language: {
                            noResults: function() { return "Tidak ada hasil ditemukan"; },
                            searching: function() { return "Mencari..."; }
                        }
                    });
                });
            }

            $('#filterModal').on('shown.bs.modal', function() {
                initializeSelect2InModal();
            });

            $('#filterModal').on('hidden.bs.modal', function() {
                $('#filterModal .select2').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Initialize DataTable
            var table = $('#dataGajiPelaporanTable').DataTable({
                responsive: true,
                destroy: true,
                language: {
                    "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "loadingRecords": "Sedang memuat...",
                    "processing": "Sedang memproses...",
                    "search": "Cari:",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                columnDefs: [{
                    orderable: false,
                    targets: [15] // AKSI column
                }, {
                    responsivePriority: 1,
                    targets: [15]
                }, {
                    responsivePriority: 2,
                    targets: [0, 1, 2]
                }],
                order: [[0, 'asc']]
            });

            // Button handlers
            $('#summaryButton').click(function() {
                $('#summaryModal').modal('show');
            });

            $('#filterButton').click(function() {
                $('#filterModal').modal('show');
            });

            $('#exportButton').click(function() {
                $('#exportModal').modal('show');
            });

            $('#resetFilter').click(function() {
                $('#filterForm')[0].reset();
                $('#filterForm .select2').val(null).trigger('change');
            });

            // Auto-hide alerts
            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 5000);

            console.log('Data Gaji Pelaporan system initialization complete!');
        });
    </script>
@endpush