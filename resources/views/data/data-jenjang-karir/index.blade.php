@extends('layouts.app')

@section('title', 'Data Jenjang Karir')

@section('content')
    <div class="container-fluid dataJenjangKarirPage">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-chart-line me-2"></i>Data Jenjang Karir</span>
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
                                <a href="{{ route('data-jenjang-karir.create') }}" class="btn btn-light">
                                    <i class="fas fa-plus-circle me-1"></i> Tambah
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
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
                        @if (
                            !empty($currentFilters['nama']) ||
                                !empty($currentFilters['nrk']) ||
                                !empty($currentFilters['departemen']) ||
                                !empty($currentFilters['jabatan']) ||
                                !empty($currentFilters['jenis_kelamin']) ||
                                !empty($currentFilters['wilker']) ||
                                !empty($currentFilters['unit_kerja']) ||
                                !empty($currentFilters['no_jk']) ||
                                !empty($currentFilters['tgl_ttd_start']) ||
                                !empty($currentFilters['tgl_ttd_end']))
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
                                        $selectedDepartemen = $departemenOptions
                                            ->where('id', $currentFilters['departemen'])
                                            ->first();
                                    @endphp
                                    @if ($selectedDepartemen)
                                        Departemen: <span class="badge bg-info">{{ $selectedDepartemen->nama_dep }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['jabatan']))
                                    @php
                                        $selectedJabatan = $jabatanOptions
                                            ->where('id', $currentFilters['jabatan'])
                                            ->first();
                                    @endphp
                                    @if ($selectedJabatan)
                                        Jabatan: <span class="badge bg-warning">{{ $selectedJabatan->nama_jbt }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['unit_kerja']))
                                    @php
                                        $selectedUnitKerja = $unitKerjaOptions
                                            ->where('id', $currentFilters['unit_kerja'])
                                            ->first();
                                    @endphp
                                    @if ($selectedUnitKerja)
                                        Unit Kerja: <span class="badge bg-danger">{{ $selectedUnitKerja->area_krj }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['jenis_kelamin']))
                                    Jenis Kelamin: <span
                                        class="badge bg-dark">{{ $jenisKelaminOptions[$currentFilters['jenis_kelamin']] ?? $currentFilters['jenis_kelamin'] }}</span>
                                @endif

                                @if (!empty($currentFilters['wilker']))
                                    Wilayah Kerja: <span class="badge bg-danger">{{ $currentFilters['wilker'] }}</span>
                                @endif

                                @if (!empty($currentFilters['no_jk']))
                                    No. Jenjang Karir: <span class="badge bg-secondary">{{ $currentFilters['no_jk'] }}</span>
                                @endif

                                <a href="{{ route('data-jenjang-karir.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Reset Filter
                                </a>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="dataJenjangKarirTable" class="table table-bordered table-striped data-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="1%" class="text-center">NO</th>
                                        <th width="3%" class="text-center">NRK</th>
                                        <th width="5%" class="text-center">NAMA</th>
                                        <th width="2%" class="text-center">JML</th>
                                        <th width="2%" class="text-center">UMR</th>
                                        <th width="1%" class="text-center">SEX</th>
                                        <th width="2%" class="text-center">FOTO</th>
                                        <th width="3%" class="text-center">NO JK</th>
                                        <th width="3%" class="text-center">TGL TTD</th>
                                        <th width="2%" class="text-center">DEP</th>
                                        <th width="2%" class="text-center">JBT</th>
                                        <th width="2%" class="text-center">WLK</th>
                                        <th width="2%" class="text-center">AREA</th>
                                        <th width="1%" class="text-center">DOK</th>
                                        <th width="4%" class="text-center">CREATE</th>
                                        <th width="4%" class="text-center">UPDATE</th>
                                        <th width="3%" class="text-center no-wrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataJenjangKarirs as $karir)
                                        @php
                                            $karyawan = $karir->karyawan;
                                            $departemen = $karir->departemen;
                                            $wilayah = $karir->wilayahKerja;

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
                                                <span class="fw-bold">{{ $karyawan->nrk ?? '-' }}</span>
                                                <span class="text-muted">{{ $karyawan->nik ?? '-' }}</span>
                                            </td>

                                            <!-- NAMA -->
                                            <td>
                                                <div class="fw-bold">{{ $karyawan->nama ?? '-' }}</div>
                                            </td>

                                            <!-- JML KARIR -->
                                            <td class="text-center">
                                                @php
                                                    $careerCount = $careerCounts[$karir->id_karyawan] ?? ['total' => 0];
                                                @endphp
                                                <span class="badge bg-primary" title="Total Jenjang Karir">
                                                    <i class="fas fa-chart-line me-1"></i>{{ $careerCount['total'] }}
                                                </span>
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
                                                    <span>
                                                        {{ $karyawan->sex == 'LAKI-LAKI' ? 'L' : 'P' }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- FOTO -->
                                            <td class="text-center">
                                                @if ($karyawan && $karyawan->foto_dokumen)
                                                    @php
                                                        $fileExtension = pathinfo(
                                                            storage_path('app/public/' . $karyawan->foto_dokumen),
                                                            PATHINFO_EXTENSION,
                                                        );
                                                        $isImage = in_array(strtolower($fileExtension), [
                                                            'jpg',
                                                            'jpeg',
                                                            'png',
                                                            'gif',
                                                        ]);
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

                                            <!-- NO JK -->
                                            <td class="text-center">
                                                <small>{{ $karir->no_jk ?? '-' }}</small>
                                            </td>

                                            <!-- TGL TTD -->
                                            <td class="text-center">
                                                {{ $karir->tgl_ttd ? \Carbon\Carbon::parse($karir->tgl_ttd)->format('d-m-Y') : '-' }}
                                            </td>

                                            <!-- DEPARTEMEN -->
                                            <td class="text-center">
                                                <span>{{ $departemen->singkatan_dep ?? '-' }}</span>
                                            </td>

                                            <!-- JABATAN -->
                                            <td class="text-center">
                                                <span>{{ $departemen->singkatan_jbt ?? '-' }}</span>
                                            </td>

                                            <!-- WILKER -->
                                            <td class="text-center">
                                                <span>{{ $wilayah->wilayah_krj ?? '-' }}</span>
                                            </td>

                                            <!-- AREA KERJA -->
                                            <td class="text-center">
                                                <span>{{ $wilayah->singkatan_wk ?? '-' }}</span>
                                            </td>

                                            <!-- DOKUMEN -->
                                            <td class="text-center">
                                                @if ($karir->file_dokumen)
                                                    <a href="{{ asset('storage/' . $karir->file_dokumen) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="tooltip" title="Lihat Dokumen">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <!-- CREATE -->
                                            <td>-
                                                <span style="font-size: 11px">{{ $karir->created_at ? $karir->created_at->format('d/m/y H:i') : '-' }}</span>
                                            </td>

                                            <!-- UPDATE -->
                                            <td>-
                                                <span style="font-size: 11px">{{ $karir->updated_at ? $karir->updated_at->format('d/m/y H:i') : '-' }}</span>
                                            </td>

                                            <!-- AKSI -->
                                            <td class="text-center no-wrap">
                                                <div class="btn-group" role="group" aria-label="Actions">
                                                    @if (auth()->user()->is_admin || ($userPermissions['detail'] ?? false))
                                                        <a href="{{ route('data-jenjang-karir.show', $karir->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                            title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->is_admin || ($userPermissions['ubah'] ?? false))
                                                        <a href="{{ route('data-jenjang-karir.edit', $karir->id) }}"
                                                            class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                            title="Edit">
                                                            <i class="fas fa-edit"></i>
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
                    <h5 class="modal-title"><i class="fas fa-filter me-2"></i>Filter Data Jenjang Karir</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="GET" action="{{ route('data-jenjang-karir.index') }}">
                        <div class="row mb-3">
                            <!-- Jabatan -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_jabatan" class="form-label fw-bold">Jabatan</label>
                                    <select class="form-select select2" id="filter_jabatan" name="filter_jabatan">
                                        <option value="">Semua Jabatan</option>
                                        @foreach ($jabatanOptions as $jabatan)
                                            <option value="{{ $jabatan->id }}"
                                                {{ $currentFilters['jabatan'] == $jabatan->id ? 'selected' : '' }}>
                                                {{ $jabatan->nama_jbt }} @if ($jabatan->singkatan_jbt)
                                                    ({{ $jabatan->singkatan_jbt }})
                                                @endif - {{ $jabatan->nama_dep }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_jenis_kelamin" class="form-label fw-bold">Jenis Kelamin</label>
                                    <select class="form-select select2" id="filter_jenis_kelamin" name="filter_jenis_kelamin">
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

                            <!-- Wilayah Kerja -->
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

                            <!-- Nama Karyawan -->
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_nama" class="form-label fw-bold">Nama Karyawan</label>
                                    <input type="text" class="form-control" id="filter_nama" name="filter_nama"
                                        placeholder="Cari nama karyawan..." value="{{ $currentFilters['nama'] ?? '' }}">
                                </div>
                            </div>

                            <!-- Unit Kerja -->
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

                            <!-- NRK -->
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_nrk" class="form-label fw-bold">NRK</label>
                                    <input type="text" class="form-control" id="filter_nrk" name="filter_nrk"
                                        placeholder="Cari NRK karyawan..." value="{{ $currentFilters['nrk'] ?? '' }}">
                                </div>
                            </div>

                            <!-- Departemen -->
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_departemen" class="form-label fw-bold">Departemen</label>
                                    <select class="form-select select2" id="filter_departemen" name="filter_departemen">
                                        <option value="">Semua Departemen</option>
                                        @foreach ($departemenOptions as $departemen)
                                            <option value="{{ $departemen->id }}"
                                                {{ $currentFilters['departemen'] == $departemen->id ? 'selected' : '' }}>
                                                {{ $departemen->nama_dep }} @if ($departemen->singkatan_dep)
                                                    ({{ $departemen->singkatan_dep }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- No JK -->
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_no_jk" class="form-label fw-bold">No. Jenjang Karir</label>
                                    <input type="text" class="form-control" id="filter_no_jk" name="filter_no_jk"
                                        placeholder="Cari nomor jenjang karir..." value="{{ $currentFilters['no_jk'] ?? '' }}">
                                </div>
                            </div>

                            <!-- Filter Tanggal TTD -->
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_tgl_ttd_start" class="form-label fw-bold">Tanggal TTD (Dari)</label>
                                    <input type="date" class="form-control" id="filter_tgl_ttd_start" name="filter_tgl_ttd_start"
                                        value="{{ $currentFilters['tgl_ttd_start'] ?? '' }}">
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_tgl_ttd_end" class="form-label fw-bold">Tanggal TTD (Sampai)</label>
                                    <input type="date" class="form-control" id="filter_tgl_ttd_end" name="filter_tgl_ttd_end"
                                        value="{{ $currentFilters['tgl_ttd_end'] ?? '' }}">
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="col-md-12 mt-3 justify-content-center">
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

    <!-- Summary Modal (sama seperti data kontrak, sesuaikan dengan data jenjang karir) -->
    <!-- Export Modal (sama seperti data kontrak) -->

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Sama dengan style data kontrak -->
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#dataJenjangKarirTable').DataTable(
                // {
                // responsive: true,
                // language: {
                //     "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                // }
            }
        );

            // Initialize Select2 in modal
            $('#filterModal').on('shown.bs.modal', function() {
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#filterModal')
                });
            });

            // Filter handlers
            $('#filterButton').click(function() {
                $('#filterModal').modal('show');
            });

            $('#applyFilter').click(function() {
                $('#filterForm').submit();
            });

            $('#resetFilter').click(function() {
                $('#filterForm')[0].reset();
                $('.select2').val('').trigger('change');
            });

            // Image preview
            $(document).on('click', '.employee-photo', function() {
                const imgSrc = $(this).attr('src');
                const employeeName = $(this).attr('alt');

                Swal.fire({
                    title: employeeName,
                    imageUrl: imgSrc,
                    imageAlt: employeeName,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: '600px'
                });
            });
        });
    </script>
@endpush