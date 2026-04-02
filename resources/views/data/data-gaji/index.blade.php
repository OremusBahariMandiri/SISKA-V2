@extends('layouts.app')

@section('title', 'Data Gaji')

@section('content')
    <div class="container-fluid dataGajiPage">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-money-bill-wave me-2"></i>Data Gaji</span>
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
                            @if (auth()->user()->is_admin || ($userPermissions['tambah'] ?? false))
                                <a href="{{ route('data-gaji.create') }}" class="btn btn-light">
                                    <i class="fas fa-plus-circle me-1"></i> Tambah
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Active Filter Display -->
                        @if (
                            !empty($currentFilters['nama']) ||
                            !empty($currentFilters['nrk']) ||
                            !empty($currentFilters['departemen']) ||
                            !empty($currentFilters['jabatan']) ||
                            !empty($currentFilters['jenis_kelamin']) ||
                            !empty($currentFilters['wilker']) ||
                            !empty($currentFilters['unit_kerja']))
                            <div class="alert alert-info" role="alert" id="filterActiveAlert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Filter Aktif:</strong>

                                @if (!empty($currentFilters['nama']))
                                    Nama: <span class="badge bg-primary">{{ $currentFilters['nama'] }}</span>
                                @endif

                                @if (!empty($currentFilters['nrk']))
                                    NRK: <span class="badge bg-primary">{{ $currentFilters['nrk'] }}</span>
                                @endif

                                @if (!empty($currentFilters['departemen']))
                                    @php
                                        $selectedDepartemen = $departemenOptions->where('id', $currentFilters['departemen'])->first();
                                    @endphp
                                    @if ($selectedDepartemen)
                                        Departemen: <span class="badge bg-info">{{ $selectedDepartemen->nama_dep }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['jabatan']))
                                    @php
                                        $selectedJabatan = $jabatanOptions->where('id', $currentFilters['jabatan'])->first();
                                    @endphp
                                    @if ($selectedJabatan)
                                        Jabatan: <span class="badge bg-warning">{{ $selectedJabatan->nama_jbt }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['unit_kerja']))
                                    @php
                                        $selectedUnitKerja = $unitKerjaOptions->where('id', $currentFilters['unit_kerja'])->first();
                                    @endphp
                                    @if ($selectedUnitKerja)
                                        Unit Kerja: <span class="badge bg-danger">{{ $selectedUnitKerja->area_krj }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['jenis_kelamin']))
                                    Jenis Kelamin: <span class="badge bg-dark">{{ $jenisKelaminOptions[$currentFilters['jenis_kelamin']] ?? $currentFilters['jenis_kelamin'] }}</span>
                                @endif

                                @if (!empty($currentFilters['wilker']))
                                    Wilayah Kerja: <span class="badge bg-danger">{{ $currentFilters['wilker'] }}</span>
                                @endif

                                <a href="{{ route('data-gaji.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Reset Filter
                                </a>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="dataGajiTable" class="table table-bordered table-striped data-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="1%" class="text-center">NO</th>
                                        <th width="3%" class="text-center">NRK</th>
                                        <th width="5%" class="text-center">NAMA</th>
                                        <th width="2%" class="text-center">UMR</th>
                                        <th width="1%" class="text-center">SEX</th>
                                        <th width="2%" class="text-center">FOTO</th>
                                        <th width="2%" class="text-center">DEP</th>
                                        <th width="2%" class="text-center">JBT</th>
                                        <th width="2%" class="text-center">WLK</th>
                                        <th width="2%" class="text-center">AREA</th>
                                        <th width="4%" class="text-center">GAJI BERSIH</th>
                                        <th width="2%" class="text-center">STS</th>
                                        <th width="4%" class="text-center">CREATE</th>
                                        <th width="4%" class="text-center">UPDATE</th>
                                        <th width="3%" class="text-center no-wrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataGajis as $gaji)
                                        @php
                                            // Get related data
                                            $karyawan = $gaji->karyawan;
                                            $departemen = $karyawan ? $karyawan->departemenRelation : null;
                                            $wilayahKerja = $karyawan ? $karyawan->wilayahKerjaRelation : null;
                                            $unitKerja = $karyawan ? $karyawan->unitKerjaRelation : null;

                                            // Calculate age
                                            $age = null;
                                            if ($karyawan && $karyawan->tgl_lahir) {
                                                $birthDate = \Carbon\Carbon::parse($karyawan->tgl_lahir);
                                                $age = $birthDate->age;
                                            }
                                        @endphp
                                        <tr>
                                            <!-- NO -->
                                            <td class="text-center">{{ $loop->iteration }}</td>

                                            <!-- NRK -->
                                            <td>
                                                <span class="fw-bold">{{ $karyawan->nrk ?? '-' }}</span><br>
                                                <small class="text-muted">{{ $karyawan->nik ?? '-' }}</small>
                                            </td>

                                            <!-- NAMA -->
                                            <td>
                                                <div class="fw-bold">{{ $karyawan->nama ?? '-' }}</div>
                                            </td>

                                            <!-- UMUR -->
                                            <td class="text-center">
                                                @if ($age)
                                                    <span><small>{{ $age }} th</small></span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- JK (Jenis Kelamin) -->
                                            <td class="text-center">
                                                @if ($karyawan && $karyawan->sex)
                                                    <span>{{ $karyawan->sex == 'LAKI-LAKI' ? 'L' : 'P' }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- FOTO -->
                                            <td class="text-center">
                                                @if ($karyawan && $karyawan->foto_dokumen)
                                                    @php
                                                        $fileExtension = pathinfo(storage_path('app/public/' . $karyawan->foto_dokumen), PATHINFO_EXTENSION);
                                                        $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']);
                                                    @endphp

                                                    @if ($isImage)
                                                        <img src="{{ asset('storage/' . $karyawan->foto_dokumen) }}"
                                                            class="employee-photo rounded-circle"
                                                            alt="Foto {{ $karyawan->nama }}"
                                                            style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;">
                                                    @else
                                                        <div class="employee-photo-placeholder">
                                                            {{ substr($karyawan->nama, 0, 1) }}
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="employee-photo-placeholder">
                                                        {{ substr($karyawan->nama ?? 'U', 0, 1) }}
                                                    </div>
                                                @endif
                                            </td>

                                            <!-- DEPARTEMEN -->
                                            <td class="text-center">
                                                @if ($departemen)
                                                    <span>{{ $departemen->singkatan_dep }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- JABATAN -->
                                            <td class="text-center">
                                                @if ($departemen)
                                                    <span>{{ $departemen->singkatan_jbt }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- WILAYAH KERJA -->
                                            <td class="text-center">
                                                @if ($wilayahKerja)
                                                    <span>{{ $wilayahKerja->wilayah_krj }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- AREA KERJA -->
                                            <td class="text-center">
                                                @if ($unitKerja)
                                                    <span>{{ $unitKerja->singkatan_wk }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- GAJI BERSIH -->
                                            <td class="text-end">
                                                <span class="fw-bold text-success">
                                                    Rp {{ number_format($gaji->ttl_terima_gaji ?? 0, 0, ',', '.') }}
                                                </span>
                                            </td>

                                            <!-- STATUS -->
                                            <td class="text-center">
                                                @if(($gaji->sts_data_gaji ?? 'AKTIF') === 'AKTIF')
                                                    <span class="badge bg-success">AKTIF</span>
                                                @else
                                                    <span class="badge bg-secondary">NON-AKTIF</span>
                                                @endif
                                            </td>

                                            <!-- CREATE -->
                                            <td class="text-center">
                                                {{ $gaji->creator ? $gaji->creator->nama_kry : '-' }}
                                                <br><small class="text-muted">{{ $gaji->created_at ? $gaji->created_at->format('d/m/y H:i') : '-' }}</small>
                                            </td>

                                            <!-- UPDATE -->
                                            <td class="text-center">
                                                {{ $gaji->updater ? $gaji->updater->nama_kry : '-' }}
                                                <br><small class="text-muted">{{ $gaji->updated_at ? $gaji->updated_at->format('d/m/y H:i') : '-' }}</small>
                                            </td>

                                            <!-- AKSI -->
                                            <td class="text-center no-wrap">
                                                <div class="btn-group" role="group" aria-label="Actions">
                                                    @if (auth()->user()->is_admin || ($userPermissions['detail'] ?? false))
                                                        <a href="{{ route('data-gaji.show', $gaji->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->is_admin || ($userPermissions['ubah'] ?? false))
                                                        <a href="{{ route('data-gaji.edit', $gaji->id) }}"
                                                            class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    {{-- @if (auth()->user()->is_admin || ($userPermissions['hapus'] ?? false))
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger delete-confirm"
                                                            data-bs-toggle="tooltip" title="Hapus"
                                                            data-id="{{ $gaji->id }}"
                                                            data-name="{{ $karyawan->nama ?? 'Gaji' }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif --}}
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
                    <form id="filterForm" method="GET" action="{{ route('data-gaji.index') }}">
                        <div class="row mb-3">
                            {{-- Jabatan --}}
                            <div class="col-md-6">
                                <label for="filter_jabatan" class="form-label fw-bold">Jabatan</label>
                                <select class="form-select select2" id="filter_jabatan" name="filter_jabatan">
                                    <option value="">Semua Jabatan</option>
                                    @foreach ($jabatanOptions as $jabatan)
                                        <option value="{{ $jabatan->id }}"
                                            {{ ($currentFilters['jabatan'] ?? '') == $jabatan->id ? 'selected' : '' }}>
                                            {{ $jabatan->nama_jbt }} @if ($jabatan->singkatan_jbt)({{ $jabatan->singkatan_jbt }})@endif - {{ $jabatan->nama_dep }}
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
                                        <option value="{{ $value }}"
                                            {{ ($currentFilters['jenis_kelamin'] ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
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
                                        <option value="{{ $wilker->wilayah_krj }}"
                                            {{ ($currentFilters['wilker'] ?? '') == $wilker->wilayah_krj ? 'selected' : '' }}>
                                            {{ $wilker->wilayah_krj }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Nama Karyawan --}}
                            <div class="col-md-6 mt-2">
                                <label for="filter_nama" class="form-label fw-bold">Nama Karyawan</label>
                                <input type="text" class="form-control" id="filter_nama" name="filter_nama"
                                    placeholder="Cari nama karyawan..." value="{{ $currentFilters['nama'] ?? '' }}">
                            </div>

                            {{-- Unit Kerja --}}
                            <div class="col-md-6 mt-2">
                                <label for="filter_unit_kerja" class="form-label fw-bold">Area Kerja</label>
                                <select class="form-select select2" id="filter_unit_kerja" name="filter_unit_kerja">
                                    <option value="">Semua Area Kerja</option>
                                    @foreach ($unitKerjaOptions as $unitKerja)
                                        <option value="{{ $unitKerja->id }}"
                                            {{ ($currentFilters['unit_kerja'] ?? '') == $unitKerja->id ? 'selected' : '' }}>
                                            {{ $unitKerja->area_krj }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- NRK --}}
                            <div class="col-md-6 mt-2">
                                <label for="filter_nrk" class="form-label fw-bold">NRK</label>
                                <input type="text" class="form-control" id="filter_nrk" name="filter_nrk"
                                    placeholder="Cari NRK karyawan..." value="{{ $currentFilters['nrk'] ?? '' }}">
                            </div>

                            {{-- Departemen --}}
                            <div class="col-md-6 mt-2">
                                <label for="filter_departemen" class="form-label fw-bold">Departemen</label>
                                <select class="form-select select2" id="filter_departemen" name="filter_departemen">
                                    <option value="">Semua Departemen</option>
                                    @foreach ($departemenOptions as $departemen)
                                        <option value="{{ $departemen->id }}"
                                            {{ ($currentFilters['departemen'] ?? '') == $departemen->id ? 'selected' : '' }}>
                                            {{ $departemen->nama_dep }} @if ($departemen->singkatan_dep)({{ $departemen->singkatan_dep }})@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="col-md-12 mt-3 text-end">
                                <button type="button" class="btn btn-secondary me-2" id="resetFilter">
                                    <i class="fas fa-redo me-1"></i>Reset Semua Filter
                                </button>
                                <button type="button" class="btn btn-primary" id="applyFilter">
                                    <i class="fas fa-search me-1"></i>Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus Data Gaji</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <p>Apakah Anda yakin ingin menghapus data gaji untuk <strong id="employeeName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Hapus Data Gaji
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        /* ===== CARD STYLING ===== */
        .dataGajiPage .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        /* ===== TABLE STYLING ===== */
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
            min-width: 120px !important;
        }

        /* ===== EMPLOYEE PHOTO STYLING ===== */
        .employee-photo {
            width: 40px !important;
            height: 40px !important;
            object-fit: cover;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .employee-photo:hover {
            transform: scale(1.15);
            border-color: #0d6efd;
            box-shadow: 0 3px 6px rgba(13, 110, 253, 0.3);
            z-index: 10;
        }

        .employee-photo-placeholder {
            width: 40px !important;
            height: 40px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
            margin: 0 auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .employee-photo-placeholder:hover {
            transform: scale(1.15);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
        }

        /* ===== SELECT2 CUSTOM STYLING ===== */
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

        /* Responsive design */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.7rem;
            }

            .btn {
                font-size: 0.65rem;
                padding: 0.2rem 0.4rem;
            }

            .employee-photo,
            .employee-photo-placeholder {
                width: 32px !important;
                height: 32px !important;
                font-size: 0.8rem;
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
            console.log('Data Gaji system initializing...');

            // Initialize Select2 in modal
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

            // Destroy existing DataTable if exists
            if ($.fn.DataTable.isDataTable('#dataGajiTable')) {
                $('#dataGajiTable').DataTable().destroy();
            }

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // ===== IMAGE PREVIEW ON CLICK =====
            $(document).on('click', '.employee-photo', function() {
                const imgSrc = $(this).attr('src');
                const employeeName = $(this).attr('alt');

                Swal.fire({
                    title: employeeName,
                    imageUrl: imgSrc,
                    imageAlt: employeeName,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: '600px',
                    customClass: { image: 'img-fluid rounded' }
                });
            });

            // Initialize DataTable
            var table = $('#dataGajiTable').DataTable({
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
                    targets: [14] // AKSI column
                }, {
                    responsivePriority: 1,
                    targets: [14]
                }, {
                    responsivePriority: 2,
                    targets: [0, 1, 2]
                }],
                order: [[0, 'asc']]
            });

            // Filter handlers
            $('#filterButton').click(function() { $('#filterModal').modal('show'); });
            $('#applyFilter').click(function() { $('#filterForm').submit(); });
            $('#resetFilter').click(function() {
                $('#filterForm')[0].reset();
                window.location.href = "{{ route('data-gaji.index') }}";
            });

            // Delete confirmation
            $(document).on('click', '.delete-confirm', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                var deleteUrl = "{{ route('data-gaji.destroy', ':id') }}".replace(':id', id);
                $('#deleteForm').attr('action', deleteUrl);
                $('#employeeName').text(name);

                $('#deleteConfirmationModal').modal('show');
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 5000);

            console.log('Data Gaji system initialization complete!');
        });
    </script>
@endpush