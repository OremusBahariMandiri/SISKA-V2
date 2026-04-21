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
                        <x-data-filter route="{{ route('data-jenjang-karir.index') }}" :filters="$currentFilters" :options="$filterOptions"
                        title="Filter Jenjang Karir" />

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

            );
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
                    customClass: {
                        image: 'img-fluid rounded'
                    }
                });
            });

            // ===== TOOLTIP INITIALIZATION =====
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush