@extends('layouts.app')

@section('title', 'Pelaporan Dokumen HRD')

@section('content')
    <div class="container-fluid dataDokumenHrdPage">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-contract me-2"></i>Pelaporan Dokumen HRD</span>
                        <div>
                            <button type="button" class="btn btn-light me-2" id="summaryButton">
                                <i class="fas fa-chart-pie me-1"></i> Ringkasan
                            </button>
                            <button type="button" class="btn btn-light me-2" id="filterButton">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <button type="button" class="btn btn-light" id="exportButton">
                                <i class="fas fa-download me-1"></i> Export
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3 document-status-summary">
                            <span id="expiredDocumentsBadge" class="badge bg-danger me-2" style="font-size: 0.9rem;">
                                <i class="fas fa-exclamation-circle me-1"></i> Dokumen Expired :
                                <span id="expiredDocumentsCount">{{ $expiredDocumentsCount }}</span>
                            </span>
                            <span id="warningDocumentsBadge" class="badge text-dark me-2"
                                style="font-size: 0.9rem; background-color:#ffff66">
                                <i class="fas fa-exclamation-triangle me-1"></i>Dokumen Akan Expired :
                                <span id="warningDocumentsCount">{{ $expiringDocumentsCount }}</span>
                            </span>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Active Filter Display -->
                        @if (array_filter($currentFilters))
                            <div class="alert alert-info alert-dismissible fade show" role="alert" id="filterActiveAlert">
                                <i class="fas fa-filter me-2"></i>
                                <strong>Filter Aktif:</strong>
                                <div class="mt-2">
                                    @if ($currentFilters['status'])
                                        <span class="badge bg-primary me-1">Status: {{ $currentFilters['status'] }}</span>
                                    @endif
                                    @if ($currentFilters['kategori'])
                                        <span class="badge bg-primary me-1">Kategori:
                                            {{ $currentFilters['kategori'] }}</span>
                                    @endif
                                    @if ($currentFilters['jenis'])
                                        <span class="badge bg-primary me-1">Jenis: {{ $currentFilters['jenis'] }}</span>
                                    @endif
                                    @if ($currentFilters['no_dokumen'])
                                        <span class="badge bg-primary me-1">No. Dokumen:
                                            {{ $currentFilters['no_dokumen'] }}</span>
                                    @endif
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="dataDokumenHrdTable" class="table table-bordered table-striped data-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center col-no">NO</th>
                                        <th class="text-center col-kategori">KATEGORI</th>
                                        <th class="text-center col-jenis">JENIS DOKUMEN</th>
                                        <th class="text-center col-ket">KET. DOK</th>
                                        <th class="text-center col-ket">CATATAN</th>
                                        <th class="text-center col-no-dok">NO. DOKUMEN</th>
                                        <th class="text-center col-perusahaan">PERUSAHAAN</th>
                                        <th class="text-center col-tgl">TGL TTD</th>
                                        <th class="text-center col-msb">MSB</th>
                                        <th class="text-center col-tgl">TGL AKHIR</th>
                                        <th class="text-center col-tgl">TGL PERINGATAN</th>
                                        <th class="text-center col-status">STATUS</th>
                                        <th class="text-center col-file">FILE</th>
                                        <th class="text-center col-aksi">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataDokumenHrds as $dokumen)
                                        @php
                                            $reminderStatus = $dokumen->document_reminder_status;
                                            $priority = $dokumen->document_priority;

                                            $rowClass = '';
                                            if ($priority == 1) {
                                                $rowClass = 'table-danger';
                                            } elseif ($priority == 2) {
                                                $rowClass = 'table-danger';
                                            } elseif ($priority == 3) {
                                                $rowClass = 'table-success';
                                            }
                                        @endphp
                                        <tr class="{{ $rowClass }}">
                                            <!-- NO -->
                                            <td class="text-center">{{ $loop->iteration }}</td>

                                            <!-- KATEGORI -->
                                            <td>{{ $dokumen->dokumenHrd->ktg_dok_hrd ?? '-' }}</td>

                                            <!-- JENIS DOKUMEN -->
                                            <td>{{ $dokumen->dokumenHrd->jns_dok_hrd ?? '-' }}</td>

                                            <td>{{ $dokumen->ket_dok_hrd ?? '-' }}</td>

                                            <td>{{ $dokumen->catatan_dok_hrd ?? '-' }}</td>

                                            <!-- NO DOKUMEN -->
                                            <td class="text-center">{{ $dokumen->no_dok_hrd ?? '-' }}</td>

                                            <!-- PERUSAHAAN -->
                                            <td class="text-center">{{ $dokumen->perusahaan->nama_prs2 ?? '-' }}</td>

                                            <!-- TGL TTD -->
                                            <td class="text-center">
                                                {{ $dokumen->tgl_ttd ? $dokumen->tgl_ttd->format('d-m-Y') : '-' }}
                                            </td>

                                            <!-- MASA BERLAKU -->
                                            <td class="text-center">
                                                @if ($dokumen->jns_msb_dok === 'TETAP')
                                                    <span class="badge bg-success">TETAP</span>
                                                @else
                                                    {{ $dokumen->msb_dok ? $dokumen->msb_dok . ' bln' : '-' }}
                                                @endif
                                            </td>

                                            <!-- TGL AKHIR -->
                                            <td class="text-center">
                                                {{ $dokumen->tgl_akr_dok ? $dokumen->tgl_akr_dok->format('d-m-Y') : '-' }}
                                            </td>

                                            <!-- TGL PERINGATAN -->
                                            <td class="text-center">
                                                @if ($dokumen->tgl_prt_dok)
                                                    {{ $dokumen->tgl_prt_dok->format('d-m-Y') }}
                                                    @if ($reminderStatus)
                                                        <br>
                                                        <span class="badge bg-{{ $reminderStatus['class'] }} mt-1">
                                                            {{ $reminderStatus['message'] }}
                                                        </span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- STATUS -->
                                            <td class="text-center">
                                                @if ($dokumen->sts_dok === 'AKTIF')
                                                    <span class="badge bg-success">AKTIF</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $dokumen->sts_dok }}</span>
                                                @endif
                                            </td>

                                            <!-- FILE -->
                                            <td class="text-center">
                                                <div class="d-flex gap-1 justify-content-center">
                                                    @if ($dokumen->file_dok)
                                                        <a href="{{ asset('storage/' . $dokumen->file_dok) }}" target="_blank"
                                                            class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                            title="Lihat File PDF">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    @endif

                                                    @if ($dokumen->file_dok_2)
                                                        <a href="{{ asset('storage/' . $dokumen->file_dok_2) }}" target="_blank"
                                                            class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip"
                                                            title="Lihat File DOC/Excel">
                                                            <i class="fas fa-file-word"></i>
                                                        </a>
                                                    @endif

                                                    @if (!$dokumen->file_dok && !$dokumen->file_dok_2)
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- AKSI -->
                                            <td class="text-center no-wrap">
                                                <div class="btn-group" role="group">
                                                    @if (auth()->user()->is_admin || ($userPermissions['detail'] ?? false))
                                                        <a href="{{ route('data-dokumen-hrd-laporan.show', $dokumen->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                            title="Detail">
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-filter me-2"></i>Filter Data Dokumen HRD</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('data-dokumen-hrd-laporan.index') }}" method="GET" id="filterForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status Dokumen</label>
                                <select class="form-select" name="filter_status">
                                    <option value="">Semua Status</option>
                                    @foreach ($statusOptions as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ $currentFilters['status'] == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kategori Dokumen</label>
                                <select class="form-select" name="filter_kategori">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($kategoriOptions as $kategori)
                                        <option value="{{ $kategori }}"
                                            {{ $currentFilters['kategori'] == $kategori ? 'selected' : '' }}>
                                            {{ $kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jenis Dokumen</label>
                                <select class="form-select" name="filter_jenis">
                                    <option value="">Semua Jenis</option>
                                    @foreach ($jenisOptions as $jenis)
                                        <option value="{{ $jenis }}"
                                            {{ $currentFilters['jenis'] == $jenis ? 'selected' : '' }}>
                                            {{ $jenis }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">No. Dokumen</label>
                                <input type="text" class="form-control" name="filter_no_dokumen"
                                    value="{{ $currentFilters['no_dokumen'] }}" placeholder="Cari nomor dokumen...">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="resetFilter">
                            <i class="fas fa-redo me-1"></i>Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>Terapkan Filter
                        </button>
                    </div>
                </form>
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-chart-pie me-2"></i>Ringkasan Data Dokumen HRD</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Overall Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-primary shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                                    <h6 class="text-muted mb-2">Total Dokumen</h6>
                                    <h2 class="fw-bold text-primary">{{ $dataDokumenHrds->count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h6 class="text-muted mb-2">Dokumen Aktif</h6>
                                    <h2 class="fw-bold text-success">
                                        {{ $dataDokumenHrds->where('sts_dok', 'AKTIF')->count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-hourglass-half fa-3x text-warning mb-3"></i>
                                    <h6 class="text-muted mb-2">Akan Expired</h6>
                                    <h2 class="fw-bold text-warning">{{ $expiringDocumentsCount }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-danger shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                                    <h6 class="text-muted mb-2">Expired</h6>
                                    <h2 class="fw-bold text-danger">{{ $expiredDocumentsCount }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Document Category -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-folder me-2 text-primary"></i>Berdasarkan Kategori
                            Dokumen</h5>
                        <div class="row">
                            @foreach ($kategoriOptions as $kategori)
                                @php
                                    $count = $dataDokumenHrds
                                        ->filter(function ($item) use ($kategori) {
                                            return optional($item->dokumenHrd)->ktg_dok_hrd === $kategori;
                                        })
                                        ->count();
                                @endphp
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card border-left-primary shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="mb-0 fw-bold">{{ $kategori }}</p>
                                                </div>
                                                <div class="text-end">
                                                    <h3 class="fw-bold text-primary mb-0">{{ $count }}</h3>
                                                </div>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    style="width: {{ $dataDokumenHrds->count() > 0 ? ($count / $dataDokumenHrds->count()) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
        .dataDokumenHrdPage .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .border-left-primary {
            border-left: 4px solid #0d6efd !important;
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

        /* Pengaturan lebar kolom yang lebih presisi */
        .col-no {
            width: 40px !important;
            min-width: 40px !important;
            max-width: 40px !important;
        }

        .col-kategori {
            width: 120px !important;
            min-width: 120px !important;
        }

        .col-jenis {
            width: 150px !important;
            min-width: 150px !important;
        }

        .col-ket {
            width: 300px !important;
            min-width: 300px !important;
            white-space: normal !important;
        }

        .col-no-dok {
            width: 120px !important;
            min-width: 120px !important;
        }

        .col-perusahaan {
            width: 130px !important;
            min-width: 130px !important;
        }

        .col-tgl {
            width: 95px !important;
            min-width: 95px !important;
        }

        .col-msb {
            width: 70px !important;
            min-width: 70px !important;
        }

        .col-status {
            width: 70px !important;
            min-width: 70px !important;
        }

        .col-file {
            width: 80px !important;
            min-width: 80px !important;
        }

        .col-aksi {
            width: 120px !important;
            min-width: 120px !important;
        }

        .no-wrap {
            white-space: nowrap !important;
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

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.7rem;
            }

            .btn {
                font-size: 0.65rem;
                padding: 0.2rem 0.4rem;
            }

            .table th,
            .table td {
                font-size: 0.7rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#dataDokumenHrdTable').DataTable();

            // Filter button
            $('#filterButton').on('click', function() {
                $('#filterModal').modal('show');
            });

            // Reset filter
            $('#resetFilter').on('click', function() {
                window.location.href = "{{ route('data-dokumen-hrd-laporan.index') }}";
            });

            // Summary button
            $('#summaryButton').on('click', function() {
                $('#summaryModal').modal('show');
            });

            // Export button
            $('#exportButton').on('click', function() {
                $('#exportModal').modal('show');
            });

            // Export Excel
            $('#exportExcel').on('click', function() {
                var formData = $('#filterForm').serialize();
                window.location.href = "{{ route('data-dokumen-hrd-laporan.index') }}?export=excel&" + formData;
            });

            // Auto-hide alerts
            setTimeout(function() {
                $(".alert:not(#filterActiveAlert)").fadeOut("slow");
            }, 5000);

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush