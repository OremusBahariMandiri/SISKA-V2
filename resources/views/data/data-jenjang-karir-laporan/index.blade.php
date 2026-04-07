@extends('layouts.app')

@section('title', 'Pelaporan Jenjang Karir Karyawan')

@section('content')
    <div class="container-fluid dataJenjangKarirPelaporanPage">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-route me-2"></i>Pelaporan Jenjang Karir Karyawan</span>
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
                                <i class="fas fa-route me-1"></i> Total Riwayat JK: <strong>{{ $totalJK }}</strong>
                            </span>
                            <span class="badge bg-info me-2" style="font-size: 0.9rem;">
                                <i class="fas fa-users me-1"></i> Total Karyawan: <strong>{{ $totalKaryawan }}</strong>
                            </span>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Active Filter Display -->
                        @if (
                            !empty($currentFilters['nama']) ||
                                !empty($currentFilters['nrk']) ||
                                !empty($currentFilters['no_jk']) ||
                                !empty($currentFilters['departemen']) ||
                                !empty($currentFilters['jabatan']) ||
                                !empty($currentFilters['jenis_kelamin']) ||
                                !empty($currentFilters['wilker']) ||
                                !empty($currentFilters['unit_kerja']) ||
                                !empty($currentFilters['tgl_ttd_start']) ||
                                !empty($currentFilters['tgl_ttd_end']) ||
                                !empty($currentFilters['jenis_dokumen']))
                            <div class="alert alert-info" role="alert" id="filterActiveAlert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Filter Aktif:</strong>

                                @if (!empty($currentFilters['nama']))
                                    Nama: <span class="badge bg-primary">{{ $currentFilters['nama'] }}</span>
                                @endif

                                @if (!empty($currentFilters['nrk']))
                                    NRK: <span class="badge bg-primary">{{ $currentFilters['nrk'] }}</span>
                                @endif

                                @if (!empty($currentFilters['no_jk']))
                                    No JK: <span class="badge bg-info">{{ $currentFilters['no_jk'] }}</span>
                                @endif

                                @if (!empty($currentFilters['departemen']))
                                    @php $selDep = $departemenOptions->where('id', $currentFilters['departemen'])->first(); @endphp
                                    @if ($selDep)
                                        Departemen: <span class="badge bg-success">{{ $selDep->nama_dep }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['jabatan']))
                                    @php $selJbt = $jabatanOptions->where('id', $currentFilters['jabatan'])->first(); @endphp
                                    @if ($selJbt)
                                        Jabatan: <span class="badge bg-warning">{{ $selJbt->nama_jbt }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['jenis_kelamin']))
                                    Jenis Kelamin: <span
                                        class="badge bg-dark">{{ $jenisKelaminOptions[$currentFilters['jenis_kelamin']] ?? $currentFilters['jenis_kelamin'] }}</span>
                                @endif

                                @if (!empty($currentFilters['wilker']))
                                    Wilayah: <span class="badge bg-danger">{{ $currentFilters['wilker'] }}</span>
                                @endif

                                @if (!empty($currentFilters['tgl_ttd_start']) || !empty($currentFilters['tgl_ttd_end']))
                                    Tgl TTD:
                                    @if (!empty($currentFilters['tgl_ttd_start']))
                                        <span class="badge bg-secondary">{{ $currentFilters['tgl_ttd_start'] }}</span>
                                    @endif
                                    s/d
                                    @if (!empty($currentFilters['tgl_ttd_end']))
                                        <span class="badge bg-secondary">{{ $currentFilters['tgl_ttd_end'] }}</span>
                                    @endif
                                @endif

                                <a href="{{ route('data-jenjang-karir-laporan.index') }}"
                                    class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Reset Filter
                                </a>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="dataJenjangKarirPelaporanTable"
                                class="table table-bordered table-striped table-hover data-table">
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
                                        <th width="3%" class="text-center">NO JK</th>
                                        <th width="3%" class="text-center">TGL TTD</th>
                                        <th width="2%" class="text-center">JNS DOK</th>
                                        <th width="5%" class="text-center">TUGAS</th>
                                        <th width="3%" class="text-center no-wrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataJenjangKarirs as $karir)
                                        @php
                                            $karyawan = $karir->karyawan;
                                            $departemen = $karir->departemen;
                                            $wilayah = $karir->wilayahKerja;
                                            $dokumen = $karir->dokumenKaryawan;
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
                                                    {{ $karyawan->sex == 'LAKI-LAKI' ? 'L' : 'P' }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($departemen)
                                                    <span
                                                        title="{{ $departemen->nama_dep }}">{{ $departemen->singkatan_dep }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <span
                                                    title="{{ $departemen->nama_jbt ?? '' }}">{{ $departemen->singkatan_jbt ?? '-' }}</span>
                                            </td>

                                            <td class="text-center">
                                                <span
                                                    title="{{ $wilayah->wilayah_krj ?? '' }}">{{ $wilayah->singkatan_wk ?? '-' }}</span>
                                            </td>

                                            <td class="text-center">
                                                <span>{{ $wilayah->area_krj ?? '-' }}</span>
                                            </td>

                                            <td>
                                                <span class="fw-bold">{{ $karir->no_jk ?? '-' }}</span>
                                            </td>

                                            <td class="text-center">
                                                {{ $karir->tgl_ttd ? $karir->tgl_ttd->format('d-m-Y') : '-' }}
                                            </td>

                                            <td class="text-center">
                                                @if ($dokumen)
                                                    <span class="badge bg-secondary"
                                                        title="{{ $dokumen->nama_dok_kry ?? '' }}">
                                                        {{ $dokumen->kode_dok_kry ?? '-' }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>
                                                <small>{{ $karir->tugas ? \Illuminate\Support\Str::limit($karir->tugas, 60) : '-' }}</small>
                                            </td>


                                            <td class="text-center no-wrap">
                                                <div class="btn-group" role="group">
                                                    @if (auth()->user()->is_admin || ($userPermissions['detail'] ?? false))
                                                        <a href="{{ route('data-jenjang-karir-laporan.show', $karir->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                            title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if ($karir->file_dokumen)
                                                        <a href="{{ asset('storage/' . $karir->file_dokumen) }}"
                                                            target="_blank" class="btn btn-sm btn-success"
                                                            data-bs-toggle="tooltip" title="Lihat File">
                                                            <i class="fas fa-file-alt"></i>
                                                        </a>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="tooltip" title="File tidak tersedia" disabled>
                                                            <i class="fas fa-file-alt"></i>
                                                        </button>
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
                    <h5 class="modal-title"><i class="fas fa-filter me-2"></i>Filter Pelaporan Jenjang Karir</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="GET" action="{{ route('data-jenjang-karir-laporan.index') }}">
                        <div class="row mb-3">
                            {{-- Jabatan --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_jabatan" class="form-label fw-bold">Jabatan</label>
                                    <select class="form-select select2" id="filter_jabatan" name="filter_jabatan">
                                        <option value="">Semua Jabatan</option>
                                        @foreach ($jabatanOptions as $jabatan)
                                            <option value="{{ $jabatan->id }}"
                                                {{ $currentFilters['jabatan'] == $jabatan->id ? 'selected' : '' }}>
                                                {{ $jabatan->nama_jbt }}
                                                @if ($jabatan->singkatan_jbt)
                                                    ({{ $jabatan->singkatan_jbt }})
                                                @endif
                                                - {{ $jabatan->nama_dep }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Departemen --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_departemen" class="form-label fw-bold">Departemen</label>
                                    <select class="form-select select2" id="filter_departemen" name="filter_departemen">
                                        <option value="">Semua Departemen</option>
                                        @foreach ($departemenOptions as $departemen)
                                            <option value="{{ $departemen->id }}"
                                                {{ $currentFilters['departemen'] == $departemen->id ? 'selected' : '' }}>
                                                {{ $departemen->nama_dep }}
                                                @if ($departemen->singkatan_dep)
                                                    ({{ $departemen->singkatan_dep }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Wilayah Kerja --}}
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_wilker" class="form-label fw-bold">Wilayah Kerja</label>
                                    <select class="form-select select2" id="filter_wilker" name="filter_wilker">
                                        <option value="">Semua Wilayah Kerja</option>
                                        @foreach ($wilayahKerjaOptions as $wilker)
                                            <option value="{{ $wilker->wilayah_krj }}"
                                                {{ $currentFilters['wilker'] == $wilker->wilayah_krj ? 'selected' : '' }}>
                                                {{ $wilker->wilayah_krj }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Area Kerja --}}
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_unit_kerja" class="form-label fw-bold">Area Kerja</label>
                                    <select class="form-select select2" id="filter_unit_kerja" name="filter_unit_kerja">
                                        <option value="">Semua Area Kerja</option>
                                        @foreach ($unitKerjaOptions as $unitKerja)
                                            <option value="{{ $unitKerja->id }}"
                                                {{ $currentFilters['unit_kerja'] == $unitKerja->id ? 'selected' : '' }}>
                                                {{ $unitKerja->area_krj }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_jenis_kelamin" class="form-label fw-bold">Jenis Kelamin</label>
                                    <select class="form-select select2" id="filter_jenis_kelamin"
                                        name="filter_jenis_kelamin">
                                        <option value="">Semua Jenis Kelamin</option>
                                        @foreach ($jenisKelaminOptions as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ $currentFilters['jenis_kelamin'] == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Jenis Dokumen --}}
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_jenis_dokumen" class="form-label fw-bold">Jenis Dokumen</label>
                                    <select class="form-select select2" id="filter_jenis_dokumen"
                                        name="filter_jenis_dokumen">
                                        <option value="">Semua Jenis Dokumen</option>
                                        @foreach ($dokumenOptions as $dok)
                                            <option value="{{ $dok->id }}"
                                                {{ $currentFilters['jenis_dokumen'] == $dok->id ? 'selected' : '' }}>
                                                {{ $dok->kode_dok_kry }} - {{ $dok->nama_dok_kry }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Nama --}}
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_nama" class="form-label fw-bold">Nama Karyawan</label>
                                    <input type="text" class="form-control" id="filter_nama" name="filter_nama"
                                        placeholder="Cari nama karyawan..." value="{{ $currentFilters['nama'] ?? '' }}">
                                </div>
                            </div>

                            {{-- NRK --}}
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_nrk" class="form-label fw-bold">NRK</label>
                                    <input type="text" class="form-control" id="filter_nrk" name="filter_nrk"
                                        placeholder="Cari NRK..." value="{{ $currentFilters['nrk'] ?? '' }}">
                                </div>
                            </div>

                            {{-- No JK --}}
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_no_jk" class="form-label fw-bold">No. Jenjang Karir</label>
                                    <input type="text" class="form-control" id="filter_no_jk" name="filter_no_jk"
                                        placeholder="Cari nomor jenjang karir..."
                                        value="{{ $currentFilters['no_jk'] ?? '' }}">
                                </div>
                            </div>

                            {{-- Tgl TTD --}}
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Tgl TTD (Dari)</label>
                                    <input type="date" class="form-control" id="filter_tgl_ttd_start"
                                        name="filter_tgl_ttd_start" value="{{ $currentFilters['tgl_ttd_start'] ?? '' }}">
                                </div>
                            </div>

                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Tgl TTD (Sampai)</label>
                                    <input type="date" class="form-control" id="filter_tgl_ttd_end"
                                        name="filter_tgl_ttd_end" value="{{ $currentFilters['tgl_ttd_end'] ?? '' }}">
                                </div>
                            </div>

                            <div class="d-flex mt-3">
                                <div class="col-md-12 justify-content-center">
                                    <div class="form-group w-100 text-end">
                                        <button type="button" class="btn btn-secondary me-2" id="resetFilter">
                                            <i class="fas fa-redo me-1"></i>Reset Semua Filter
                                        </button>
                                        <button type="button" class="btn btn-primary" id="applyFilter">
                                            <i class="fas fa-search me-1"></i>Terapkan Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-download me-2"></i>Export Data</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Pilih format export yang diinginkan:</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        <small>Export akan menggunakan filter yang sedang aktif</small>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-success" id="exportExcel">
                            <i class="fas fa-file-excel me-2"></i>Export ke Excel (.xlsx)
                        </button>
                        <button type="button" class="btn btn-outline-danger" id="exportPDF">
                            <i class="fas fa-file-pdf me-2"></i>Export ke PDF
                        </button>
                        <button type="button" class="btn btn-outline-info" id="exportCSV">
                            <i class="fas fa-file-csv me-2"></i>Export ke CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Modal -->
    <div class="modal fade" id="summaryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-chart-pie me-2"></i>Ringkasan Pelaporan Jenjang Karir</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-primary shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-route fa-3x text-primary mb-3"></i>
                                    <h6 class="text-muted mb-2">Total Riwayat Jenjang Karir</h6>
                                    <h2 class="fw-bold text-primary">{{ $totalJK }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-info mb-3"></i>
                                    <h6 class="text-muted mb-2">Total Karyawan</h6>
                                    <h2 class="fw-bold text-info">{{ $totalKaryawan }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Department -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-sitemap me-2 text-success"></i>Berdasarkan Departemen
                        </h5>
                        <div class="row">
                            @foreach ($departemenOptions as $dep)
                                @php
                                    $depIds = \App\Models\DataMaster\Departemen::where('nama_dep', $dep->nama_dep)
                                        ->pluck('id')
                                        ->toArray();
                                    $count = $dataJenjangKarirs->whereIn('id_departemen', $depIds)->count();
                                @endphp
                                @if ($count > 0)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card border-left-success shadow-sm">
                                            <div class="card-body py-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="mb-0 fw-bold text-truncate" style="max-width: 150px;"
                                                        title="{{ $dep->nama_dep }}">
                                                        {{ \Illuminate\Support\Str::limit($dep->nama_dep, 20) }}
                                                    </p>
                                                    <h4 class="fw-bold text-success mb-0">{{ $count }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        .dataJenjangKarirPelaporanPage .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .border-left-success {
            border-left: 4px solid #198754 !important;
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

        .document-status-summary {
            margin-bottom: 1rem;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            border-left: 4px solid #0d6efd;
        }

        .document-status-summary .badge {
            font-size: 0.9rem !important;
            padding: 0.5em 0.75em;
            margin-right: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #filterActiveAlert {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .modal .select2-container {
            z-index: 1070 !important;
        }

        .modal .select2-dropdown {
            z-index: 1071 !important;
        }

        .select2-container--open .select2-dropdown {
            z-index: 1071 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            function initSelect2() {
                $('#filterModal .select2').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) $(this).select2('destroy');
                    $(this).select2({
                        theme: 'bootstrap-5',
                        dropdownParent: $('#filterModal'),
                        width: '100%',
                        placeholder: $(this).find('option:first').text() || 'Pilih...',
                        allowClear: true,
                        language: {
                            noResults: function() {
                                return "Tidak ada hasil ditemukan";
                            },
                            searching: function() {
                                return "Mencari...";
                            }
                        }
                    });
                });
            }

            $('#filterModal').on('shown.bs.modal', function() {
                initSelect2();
            });
            $('#filterModal').on('hidden.bs.modal', function() {
                $('#filterModal .select2').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) $(this).select2('destroy');
                });
            });

            // Initialize tooltips
            [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                .map(function(el) {
                    return new bootstrap.Tooltip(el);
                });

            // DataTable
            if ($.fn.DataTable.isDataTable('#dataJenjangKarirPelaporanTable')) {
                $('#dataJenjangKarirPelaporanTable').DataTable().destroy();
            }

            var table = $('#dataJenjangKarirPelaporanTable').DataTable();

            // Button handlers
            $('#filterButton').click(function() {
                $('#filterModal').modal('show');
            });
            $('#applyFilter').click(function() {
                $('#filterForm').submit();
            });
            $('#resetFilter').click(function() {
                window.location.href = "{{ route('data-jenjang-karir-laporan.index') }}";
            });
            $('#exportButton').click(function() {
                $('#exportModal').modal('show');
            });
            $('#summaryButton').click(function() {
                $('#summaryModal').modal('show');
            });

            $('#exportExcel').click(function() {
                var formData = $('#filterForm').serialize();
                window.location.href = "{{ route('data-jenjang-karir-laporan.index') }}?export=excel&" +
                    formData;
            });

            // Auto-hide alerts
            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 5000);
        });
    </script>
@endpush
