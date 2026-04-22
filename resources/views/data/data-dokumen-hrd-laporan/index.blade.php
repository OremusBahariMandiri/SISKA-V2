@extends('layouts.app')

@section('title', 'Pelaporan Dokumen HRD')

@section('content')
    <div class="container-fluid dataDokumenHrdPelaporanPage">
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
                            <button type="button" class="btn btn-light me-2" id="exportButton">
                                <i class="fas fa-download me-1"></i> Export
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3 document-status-summary">
                            <span class="badge bg-primary me-2" style="font-size: 0.9rem;">
                                <i class="fas fa-file-alt me-1"></i> Total Dokumen: <strong>{{ $totalDokumen }}</strong>
                            </span>
                            <span class="badge bg-danger me-2" style="font-size: 0.9rem;">
                                <i class="fas fa-exclamation-circle me-1"></i> Expired:
                                <strong>{{ $expiredDocumentsCount }}</strong>
                            </span>
                            <span class="badge text-dark me-2" style="font-size: 0.9rem; background-color:#ffff66">
                                <i class="fas fa-exclamation-triangle me-1"></i> Akan Expired:
                                <strong>{{ $expiringDocumentsCount }}</strong>
                            </span>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                                        <span class="badge bg-primary me-1">Kategori: {{ $currentFilters['kategori'] }}</span>
                                    @endif
                                    @if ($currentFilters['jenis'])
                                        <span class="badge bg-primary me-1">Jenis: {{ $currentFilters['jenis'] }}</span>
                                    @endif
                                    @if ($currentFilters['no_dokumen'])
                                        <span class="badge bg-primary me-1">No. Dokumen: {{ $currentFilters['no_dokumen'] }}</span>
                                    @endif
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="dataDokumenHrdPelaporanTable"
                                class="table table-bordered table-striped table-hover data-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="1%" class="text-center">NO</th>
                                        <th width="4%" class="text-center">KATEGORI</th>
                                        <th width="5%" class="text-center">JENIS DOK</th>
                                        <th width="3%" class="text-center">PERUSAHAAN</th>
                                        <th width="4%" class="text-center">NO DOK</th>
                                        <th width="3%" class="text-center">TGL TTD</th>
                                        <th width="2%" class="text-center">JENIS MSB</th>
                                        <th width="3%" class="text-center">TGL AKHIR</th>
                                        <th width="2%" class="text-center">MSB (BLN)</th>
                                        <th width="3%" class="text-center">TGL PERINGATAN</th>
                                        <th width="2%" class="text-center">DURASI PGT</th>
                                        <th width="4%" class="text-center">KETERANGAN</th>
                                        <th width="2%" class="text-center">STATUS</th>
                                        <th width="3%" class="text-center no-wrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataDokumenHrds as $dokumen)
                                        @php
                                            // ✅ SAMA SEPERTI INDEX DOKUMEN HRD - Menggunakan attribute dari model
                                            $reminderStatus = $dokumen->document_reminder_status;
                                            $priority = $dokumen->document_priority;

                                            // ✅ Tentukan warna row berdasarkan priority
                                            $rowClass = '';
                                            if ($priority == 1) {
                                                $rowClass = 'table-danger'; // Expired atau overdue
                                            } elseif ($priority == 2) {
                                                $rowClass = 'table-danger'; // Urgent (1-7 hari)
                                            } elseif ($priority == 3) {
                                                $rowClass = 'table-success'; // Warning (8-30 hari)
                                            }
                                        @endphp
                                        <tr class="{{ $rowClass }}">
                                            <td class="text-center">{{ $loop->iteration }}</td>

                                            <td>{{ $dokumen->dokumenHrd->ktg_dok_hrd ?? '-' }}</td>

                                            <td>{{ $dokumen->dokumenHrd->jns_dok_hrd ?? '-' }}</td>

                                            <td>{{ $dokumen->perusahaan->nama_prs2 ?? '-' }}</td>

                                            <td>
                                                <span class="fw-bold">{{ $dokumen->no_dok_hrd ?? '-' }}</span>
                                            </td>

                                            <td class="text-center">
                                                @if ($dokumen->tgl_ttd)
                                                    {{ $dokumen->tgl_ttd->format('d-m-Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($dokumen->jns_msb_dok)
                                                    @if ($dokumen->jns_msb_dok === 'TETAP')
                                                        <span class="badge bg-success">TETAP</span>
                                                    @else
                                                        <span class="badge bg-info">{{ $dokumen->jns_msb_dok }}</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($dokumen->tgl_akr_dok)
                                                    {{ $dokumen->tgl_akr_dok->format('d-m-Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                {{ $dokumen->msb_dok ? $dokumen->msb_dok . ' bln' : '-' }}
                                            </td>

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

                                            <td class="text-center">
                                                {{ $dokumen->durasi_pgt ? $dokumen->durasi_pgt . ' hari' : '-' }}
                                            </td>

                                            <td>
                                                <small>{{ $dokumen->ket_dok_hrd ?? '-' }}</small>
                                            </td>

                                            <td class="text-center">
                                                @if ($dokumen->sts_dok == 'AKTIF')
                                                    <span class="badge bg-success">AKTIF</span>
                                                @else
                                                    <span class="badge bg-secondary">NON-AKTIF</span>
                                                @endif
                                            </td>

                                            <td class="text-center no-wrap">
                                                <div class="btn-group" role="group">
                                                    @if (auth()->user()->is_admin || ($userPermissions['detail'] ?? false))
                                                        <a href="{{ route('data-dokumen-hrd-laporan.show', $dokumen->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                            title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
                                                    @if ($dokumen->file_dok)
                                                        <a href="{{ asset('storage/' . $dokumen->file_dok) }}"
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
                                <select class="form-select select2" name="filter_status">
                                    <option value="">Semua Status</option>
                                    @foreach ($statusOptions as $key => $value)
                                        <option value="{{ $key }}" {{ $currentFilters['status'] == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kategori Dokumen</label>
                                <select class="form-select select2" name="filter_kategori">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($kategoriOptions as $kategori)
                                        <option value="{{ $kategori }}" {{ $currentFilters['kategori'] == $kategori ? 'selected' : '' }}>
                                            {{ $kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jenis Dokumen</label>
                                <select class="form-select select2" name="filter_jenis">
                                    <option value="">Semua Jenis</option>
                                    @foreach ($jenisOptions as $jenis)
                                        <option value="{{ $jenis }}" {{ $currentFilters['jenis'] == $jenis ? 'selected' : '' }}>
                                            {{ $jenis }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">No. Dokumen</label>
                                <input type="text" class="form-control" name="filter_no_dokumen"
                                       value="{{ $currentFilters['no_dokumen'] }}"
                                       placeholder="Cari nomor dokumen...">
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
                    <h5 class="modal-title"><i class="fas fa-chart-pie me-2"></i>Ringkasan Pelaporan Dokumen HRD</h5>
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
                                    <h2 class="fw-bold text-primary">{{ $totalDokumen }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h6 class="text-muted mb-2">Dokumen Aktif</h6>
                                    <h2 class="fw-bold text-success">{{ $totalAktif }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-danger shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                                    <h6 class="text-muted mb-2">Dokumen Expired</h6>
                                    <h2 class="fw-bold text-danger">{{ $expiredDocumentsCount }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                    <h6 class="text-muted mb-2">Akan Expired</h6>
                                    <h2 class="fw-bold text-warning">{{ $expiringDocumentsCount }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Category -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-folder me-2 text-primary"></i>Berdasarkan Kategori Dokumen</h5>
                        <div class="row">
                            @foreach ($kategoriOptions as $kategori)
                                @php
                                    $count = $dataDokumenHrds->filter(function($d) use ($kategori) {
                                        return $d->dokumenHrd && $d->dokumenHrd->ktg_dok_hrd == $kategori;
                                    })->count();
                                @endphp
                                @if ($count > 0)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-left-primary shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="mb-0 fw-bold">{{ $kategori }}</p>
                                                    <h3 class="fw-bold text-primary mb-0">{{ $count }}</h3>
                                                </div>
                                                <div class="progress mt-2" style="height: 5px;">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                        style="width: {{ $totalDokumen > 0 ? ($count / $totalDokumen) * 100 : 0 }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Document Type -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-file-alt me-2 text-secondary"></i>Berdasarkan Jenis Dokumen</h5>
                        <div class="row">
                            @foreach ($dokumenTypes as $dokumenType)
                                @php
                                    $count = $dataDokumenHrds->where('id_dokumen_hrd', $dokumenType->id)->count();
                                @endphp
                                @if ($count > 0)
                                    <div class="col-md-4 col-lg-3 mb-3">
                                        <div class="card border-left-secondary shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <p class="mb-0 fw-bold text-truncate" title="{{ $dokumenType->jns_dok_hrd }}" style="max-width: 150px;">
                                                            {{ Str::limit($dokumenType->jns_dok_hrd, 20) }}
                                                        </p>
                                                    </div>
                                                    <div class="text-end">
                                                        <h3 class="fw-bold text-secondary mb-0">{{ $count }}</h3>
                                                    </div>
                                                </div>
                                                <div class="progress mt-2" style="height: 5px;">
                                                    <div class="progress-bar bg-secondary" role="progressbar"
                                                        style="width: {{ $totalDokumen > 0 ? ($count / $totalDokumen) * 100 : 0 }}%">
                                                    </div>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <style>
        .dataDokumenHrdPelaporanPage .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .border-left-primary { border-left: 4px solid #0d6efd !important; }
        .border-left-success { border-left: 4px solid #198754 !important; }
        .border-left-info    { border-left: 4px solid #0dcaf0 !important; }
        .border-left-warning { border-left: 4px solid #ffc107 !important; }
        .border-left-secondary { border-left: 4px solid #6c757d !important; }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 0.75rem;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.75rem;
        }

        .no-wrap {
            white-space: nowrap !important;
            min-width: 100px !important;
        }

        /* Select2 inside modal */
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }
        .modal .select2-container { z-index: 1070 !important; }
        .modal .select2-dropdown  { z-index: 1071 !important; }
        .select2-container--open .select2-dropdown { z-index: 1071 !important; }

        /* Document status summary badges */
        .document-status-summary {
            margin-bottom: 1rem;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            border-left: 4px solid #0d6efd;
        }

        #filterActiveAlert {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        @media (max-width: 768px) {
            .table th, .table td { font-size: 0.7rem; }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // ===== SELECT2 INIT IN MODAL =====
            function initializeSelect2InModal() {
                $('#filterModal .select2').each(function () {
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
                            noResults: function () { return "Tidak ada hasil ditemukan"; },
                            searching: function () { return "Mencari..."; },
                            inputTooShort: function () { return "Ketik untuk mencari..."; }
                        }
                    });
                });
            }

            // ===== DATATABLE =====
            if ($.fn.DataTable.isDataTable('#dataDokumenHrdPelaporanTable')) {
                $('#dataDokumenHrdPelaporanTable').DataTable().destroy();
            }

            var table = $('#dataDokumenHrdPelaporanTable').DataTable({
                responsive: true,
                destroy: true,
                language: {
                    emptyTable:     "Tidak ada data yang tersedia pada tabel ini",
                    info:           "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty:      "Menampilkan 0 sampai 0 dari 0 entri",
                    infoFiltered:   "(disaring dari _MAX_ entri keseluruhan)",
                    lengthMenu:     "Tampilkan _MENU_ entri",
                    loadingRecords: "Sedang memuat...",
                    processing:     "Sedang memproses...",
                    search:         "Cari:",
                    zeroRecords:    "Tidak ditemukan data yang sesuai",
                    paginate: {
                        first:    "Pertama",
                        last:     "Terakhir",
                        next:     "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                columnDefs: [
                    { orderable: false, targets: [13] },
                    { responsivePriority: 1, targets: [13] },
                    { responsivePriority: 2, targets: [0, 1, 4] },
                    { responsivePriority: 3, targets: [12, 3] }
                ],
                drawCallback: function () {
                    var api = this.api();
                    var startIndex = api.page.info().start;
                    api.column(0, { page: 'current' }).nodes().each(function (cell, i) {
                        cell.innerHTML = startIndex + i + 1;
                    });
                }
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });

            // ===== FILTER BUTTON =====
            $('#filterButton').on('click', function () {
                $('#filterModal').modal('show');
                setTimeout(initializeSelect2InModal, 300);
            });

            // ===== RESET FILTER =====
            $('#resetFilter').on('click', function () {
                window.location.href = "{{ route('data-dokumen-hrd-laporan.index') }}";
            });

            // ===== EXPORT BUTTON =====
            $('#exportButton').on('click', function () {
                $('#exportModal').modal('show');
            });

            $('#exportExcel').on('click', function () {
                var formData = $('#filterForm').serialize();
                window.location.href = "{{ route('data-dokumen-hrd-laporan.index') }}?export=excel&" + formData;
            });

            // ===== SUMMARY BUTTON =====
            $('#summaryButton').on('click', function () {
                $('#summaryModal').modal('show');
            });

            // Auto-hide alerts
            setTimeout(function () { $(".alert:not(#filterActiveAlert)").fadeOut("slow"); }, 5000);
        });
    </script>
@endpush