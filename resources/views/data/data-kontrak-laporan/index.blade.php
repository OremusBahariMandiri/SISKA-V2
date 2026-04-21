@extends('layouts.app')

@section('title', 'Pelaporan Kontrak Karyawan')

@section('content')
    <div class="container-fluid dataKontrakPelaporanPage">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-contract me-2"></i>Pelaporan Kontrak Karyawan</span>
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
                                <i class="fas fa-file-contract me-1"></i> Total Kontrak: <strong>{{ $totalKontrak }}</strong>
                            </span>
                            <span class="badge bg-danger me-2" style="font-size: 0.9rem;">
                                <i class="fas fa-exclamation-circle me-1"></i> Expired:
                                <strong>{{ $expiredContractsCount }}</strong>
                            </span>
                            <span class="badge text-dark me-2" style="font-size: 0.9rem; background-color:#ffff66">
                                <i class="fas fa-exclamation-triangle me-1"></i> Akan Expired:
                                <strong>{{ $expiringContractsCount }}</strong>
                            </span>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- filter komponen --}}
                        <x-data-filter route="{{ route('data-kontrak-laporan.index') }}" :filters="$currentFilters" :options="$filterOptions"
                        title="Filter Data Kontrak" />

                        <div class="table-responsive">
                            <table id="dataKontrakPelaporanTable"
                                class="table table-bordered table-striped table-hover data-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="1%" class="text-center">NO</th>
                                        <th width="3%" class="text-center">NRK</th>
                                        <th width="5%" class="text-center">NAMA</th>
                                        <th width="1%" class="text-center">SEX</th>
                                        <th width="2%" class="text-center">PRS</th>
                                        <th width="2%" class="text-center">DEP</th>
                                        <th width="2%" class="text-center">JBT</th>
                                        <th width="2%" class="text-center">WILKER</th>
                                        <th width="2%" class="text-center">AREA</th>
                                        <th width="4%" class="text-center">NO KTR</th>
                                        <th width="3%" class="text-center">JNS KTR</th>
                                        <th width="3%" class="text-center">KTG KTR</th>
                                        <th width="3%" class="text-center">TGL AWAL</th>
                                        <th width="3%" class="text-center">TGL AKHIR</th>
                                        <th width="2%" class="text-center">DUR</th>
                                        <th width="3%" class="text-center">TGL PGT</th>
                                        <th width="2%" class="text-center">STATUS</th>
                                        <th width="3%" class="text-center no-wrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataKontraks as $kontrak)
                                        @php
                                            $karyawan = $kontrak->karyawan;
                                            $departemen = $karyawan ? $karyawan->departemenRelation : null;
                                            $wilayah = $karyawan ? $karyawan->wilayahKerjaRelation : null;
                                            $perusahaan = $karyawan ? $karyawan->perusahaanRelation : null;
                                            $kontrakType = $kontrak->kontrakKerja;

                                            // Calculate contract status
                                            $today = now();
                                            $isExpired =
                                                $kontrak->tgl_akhir_ktr &&
                                                $kontrak->tgl_akhir_ktr < $today &&
                                                $kontrak->sts_srt_ktr == 'AKTIF';
                                            $isExpiring =
                                                $kontrak->tgl_pgt_ktr &&
                                                $kontrak->tgl_pgt_ktr <= $today &&
                                                $kontrak->tgl_akhir_ktr >= $today &&
                                                $kontrak->sts_srt_ktr == 'AKTIF';

                                            $rowClass = '';
                                            if ($isExpired) {
                                                $rowClass = 'table-danger';
                                            } elseif ($isExpiring) {
                                                $rowClass = 'table-warning';
                                            }
                                        @endphp
                                        <tr class="{{ $rowClass }}">
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
                                                <span>{{ $perusahaan->nama_prs2 ?? '-' }}</span>
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
                                                <span>{{ $wilayah->singkatan_wk ?? '-' }}</span>
                                            </td>

                                            <td class="text-center">
                                                <span>{{ $wilayah->area_krj ?? '-' }}</span>
                                            </td>

                                            <td>
                                                <span class="fw-bold">{{ $kontrak->no_srt_ktr ?? '-' }}</span>
                                            </td>

                                            <td class="text-center">
                                                @if ($kontrakType)
                                                    <span>{{ $kontrakType->singkatan_ktr ?? $kontrakType->nama_ktr }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($kontrak->ktg_ktk)
                                                    <span class="badge bg-warning text-dark">{{ $kontrak->ktg_ktk }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($kontrak->tgl_awl_ktr)
                                                    {{ $kontrak->tgl_awl_ktr->format('d-m-Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($kontrak->tgl_akhir_ktr)
                                                    {{ $kontrak->tgl_akhir_ktr->format('d-m-Y') }}
                                                    @if ($isExpired)
                                                        <br><span class="badge bg-danger"><i class="fas fa-times-circle"></i> EXPIRED</span>
                                                    @elseif ($isExpiring)
                                                        <br><span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle"></i> SEGERA</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($kontrak->durasi_ktr)
                                                    {{ $kontrak->durasi_ktr }} bln
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                {{ $kontrak->tgl_pgt_ktr ? $kontrak->tgl_pgt_ktr->format('d-m-Y') : '-' }}
                                            </td>

                                            <td class="text-center">
                                                @if ($kontrak->sts_srt_ktr == 'AKTIF')
                                                    <span class="badge bg-success">AKTIF</span>
                                                @else
                                                    <span class="badge bg-secondary">NON-AKTIF</span>
                                                @endif
                                            </td>

                                            <td class="text-center no-wrap">
                                                <div class="btn-group" role="group">
                                                    @if (auth()->user()->is_admin || ($userPermissions['detail'] ?? false))
                                                        <a href="{{ route('data-kontrak-laporan.show', $kontrak->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                            title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
                                                    @if ($kontrak->file_doc_ktr)
                                                        <a href="{{ asset('storage/' . $kontrak->file_doc_ktr) }}"
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
                    <h5 class="modal-title"><i class="fas fa-chart-pie me-2"></i>Ringkasan Pelaporan Kontrak</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Overall Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-primary shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-contract fa-3x text-primary mb-3"></i>
                                    <h6 class="text-muted mb-2">Total Kontrak</h6>
                                    <h2 class="fw-bold text-primary">{{ $totalKontrak }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h6 class="text-muted mb-2">Kontrak Aktif</h6>
                                    <h2 class="fw-bold text-success">{{ $totalAktif }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-danger shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                                    <h6 class="text-muted mb-2">Kontrak Expired</h6>
                                    <h2 class="fw-bold text-danger">{{ $expiredContractsCount }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                    <h6 class="text-muted mb-2">Akan Expired</h6>
                                    <h2 class="fw-bold text-warning">{{ $expiringContractsCount }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Company -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-building me-2 text-primary"></i>Berdasarkan Perusahaan</h5>
                        <div class="row">
                            @foreach ($perusahaans as $perusahaan)
                                @php
                                    $count = $dataKontraks->filter(function($k) use ($perusahaan) {
                                        return $k->karyawan && $k->karyawan->perusahaan == $perusahaan->id;
                                    })->count();
                                @endphp
                                @if ($count > 0)
                                    <div class="col-md-4 col-lg-3 mb-3">
                                        <div class="card border-left-primary shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <p class="mb-0 fw-bold text-truncate" title="{{ $perusahaan->nama_prs2 }}" style="max-width: 150px;">
                                                            {{ Str::limit($perusahaan->nama_prs2, 20) }}
                                                        </p>
                                                    </div>
                                                    <div class="text-end">
                                                        <h3 class="fw-bold text-primary mb-0">{{ $count }}</h3>
                                                    </div>
                                                </div>
                                                <div class="progress mt-2" style="height: 5px;">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                        style="width: {{ $totalKontrak > 0 ? ($count / $totalKontrak) * 100 : 0 }}%">
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

                    <!-- By Contract Type -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-file-contract me-2 text-secondary"></i>Berdasarkan Jenis Kontrak</h5>
                        <div class="row">
                            @foreach ($kontrakTypes as $kontrakType)
                                @php
                                    $count = $dataKontraks->where('id_ktr', $kontrakType->id)->count();
                                @endphp
                                @if ($count > 0)
                                    <div class="col-md-4 col-lg-3 mb-3">
                                        <div class="card border-left-secondary shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <p class="mb-0 fw-bold text-truncate" title="{{ $kontrakType->nama_ktr }}" style="max-width: 150px;">
                                                            {{ Str::limit($kontrakType->singkatan_ktr ?? $kontrakType->nama_ktr, 20) }}
                                                        </p>
                                                    </div>
                                                    <div class="text-end">
                                                        <h3 class="fw-bold text-secondary mb-0">{{ $count }}</h3>
                                                    </div>
                                                </div>
                                                <div class="progress mt-2" style="height: 5px;">
                                                    <div class="progress-bar bg-secondary" role="progressbar"
                                                        style="width: {{ $totalKontrak > 0 ? ($count / $totalKontrak) * 100 : 0 }}%">
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

                    <!-- By Kategori -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-tags me-2 text-info"></i>Berdasarkan Kategori Kontrak</h5>
                        <div class="row">
                            @foreach (['TETAP', 'TIDAK TETAP'] as $kategori)
                                @php
                                    $count = $dataKontraks->where('ktg_ktk', $kategori)->count();
                                @endphp
                                <div class="col-md-6 mb-3">
                                    <div class="card border-left-info shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="mb-0 fw-bold">{{ $kategori }}</p>
                                                <h3 class="fw-bold text-info mb-0">{{ $count }}</h3>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-info" role="progressbar"
                                                    style="width: {{ $totalKontrak > 0 ? ($count / $totalKontrak) * 100 : 0 }}%">
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
        .dataKontrakPelaporanPage .card {
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

    <script>
        $(document).ready(function () {

            // ===== SELECT2 INIT IN MODAL =====
            function initializeSelect2InModal() {
                $('#filterModal .select2').each(function () {
                    // Destroy existing Select2 instance if any
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }

                    // Initialize Select2 with proper configuration
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
            if ($.fn.DataTable.isDataTable('#dataKontrakPelaporanTable')) {
                $('#dataKontrakPelaporanTable').DataTable().destroy();
            }

            var table = $('#dataKontrakPelaporanTable').DataTable({
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
                    { orderable: false, targets: [17] },
                    { responsivePriority: 1, targets: [17] },
                    { responsivePriority: 2, targets: [0, 1, 2] },
                    { responsivePriority: 3, targets: [16, 10] }
                ],
                drawCallback: function () {
                    // Re-number rows per page
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

           

            // ===== APPLY FILTER =====
            $('#applyFilter').on('click', function () {
                $('#filterForm').submit();
            });

            // ===== RESET FILTER =====
            $('#resetFilter').on('click', function () {
                // Reset all form inputs
                $('#filter_nama').val('');
                $('#filter_nrk').val('');
                $('#filter_no_kontrak').val('');
                $('#filter_status').val('').trigger('change');
                $('#filter_perusahaan').val('').trigger('change');
                $('#filter_departemen').val('').trigger('change');
                $('#filter_jabatan').val('').trigger('change');
                $('#filter_jenis_kontrak').val('').trigger('change');
                $('#filter_kategori_kontrak').val('').trigger('change');
                $('#filter_jenis_kelamin').val('').trigger('change');
                $('#filter_wilker').val('').trigger('change');
                $('#filter_unit_kerja').val('').trigger('change');

                // Reinitialize Select2 after reset
                initializeSelect2InModal();
            });

            // ===== EXPORT BUTTON =====
            $('#exportButton').on('click', function () {
                $('#exportModal').modal('show');
            });

            $('#exportExcel').on('click', function () {
                var formData = $('#filterForm').serialize();
                window.location.href = "{{ route('data-kontrak-laporan.index') }}?export=excel&" + formData;
            });

            // ===== SUMMARY BUTTON =====
            $('#summaryButton').on('click', function () {
                $('#summaryModal').modal('show');
            });

            // Auto-hide alerts
            setTimeout(function () { $(".alert").fadeOut("slow"); }, 5000);
        });
    </script>
@endpush