@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')
    <div class="container-fluid dataKaryawanPage">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-users me-2"></i>Data Karyawan</span>
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
                                <a href="{{ route('data-karyawan.create') }}" class="btn btn-light">
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
                        @if (!empty($currentFilters['status']) || !empty($currentFilters['perusahaan']) || !empty($currentFilters['departemen']) || !empty($currentFilters['jabatan']) || !empty($currentFilters['kontrak']) || !empty($currentFilters['jenis_kelamin']) || !empty($currentFilters['skt_wilker']))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Filter Aktif:</strong>

                                @if (!empty($currentFilters['status']))
                                    Status: <span class="badge bg-primary">{{ $currentFilters['status'] }}</span>
                                @endif

                                @if (!empty($currentFilters['perusahaan']))
                                    @php
                                        $selectedPerusahaan = $perusahaans
                                            ->where('id', $currentFilters['perusahaan'])
                                            ->first();
                                    @endphp
                                    @if ($selectedPerusahaan)
                                        Perusahaan: <span class="badge bg-success">{{ $selectedPerusahaan->nama_prs2 }}</span>
                                    @endif
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

                                @if (!empty($currentFilters['kontrak']))
                                    @php
                                        $selectedKontrak = $kontrakOptions
                                            ->where('id', $currentFilters['kontrak'])
                                            ->first();
                                    @endphp
                                    @if ($selectedKontrak)
                                        Kontrak: <span class="badge bg-secondary">{{ $selectedKontrak->nama_ktr }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['jenis_kelamin']))
                                    Jenis Kelamin: <span class="badge bg-dark">{{ $jenisKelaminOptions[$currentFilters['jenis_kelamin']] ?? $currentFilters['jenis_kelamin'] }}</span>
                                @endif

                                @if (!empty($currentFilters['skt_wilker']))
                                    Singkatan Wilker: <span class="badge bg-light text-dark">{{ $currentFilters['skt_wilker'] }}</span>
                                @endif

                                <a href="{{ route('data-karyawan.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Reset Filter
                                </a>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="dataKaryawanTable" class="table table-bordered table-striped data-table">
                                <thead class="table-light">
                                    <tr>
                                        <!-- Kolom Prioritas -->
                                        <th width="3%" class="text-center">NO</th>
                                        <th width="4%" class="text-center">NRK / NIK</th>
                                        <th width="8%" class="text-center">NAMA</th>
                                        <th class="text-center">TPT LHR</th>
                                        <th class="text-center">TGL LHR</th>
                                        <th class="text-center">UMUR</th>
                                        <th class="text-center">SEX</th>
                                        <th width="6%" class="text-center">FOTO</th>
                                        <th width="7%" class="text-center">TGL MSK</th>
                                        <th width="6%" class="text-center">PRSH</th>
                                        <th width="6%" class="text-center">KNT</th>
                                        <th width="7%" class="text-center">TGL HK</th>
                                        <th width="8%" class="text-center">DEP</th>
                                        <th width="7%" class="text-center">JBT</th>
                                        <th width="7%" class="text-center">WILKER</th>
                                        <th width="7%" class="text-center">SKT WILKER</th>
                                        <th width="7%" class="text-center">STKAR</th>
                                        <th width="7%" class="text-center">TGL NA</th>
                                        <th width="8%" class="text-center">MKR</th>
                                        <th width="8%" class="text-center">CREATE</th>
                                        <th width="8%" class="text-center">UPDATE</th>
                                        <th width="8%" class="text-center">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataKaryawans as $karyawan)
                                        @php
                                            // Calculate age from date of birth
                                            $birthDate = $karyawan->tgl_lahir
                                                ? \Carbon\Carbon::parse($karyawan->tgl_lahir)
                                                : null;
                                            $age = $birthDate ? $birthDate->age : '-';

                                            // Calculate work duration from join date
                                            $joinDate = $karyawan->tgl_masuk
                                                ? \Carbon\Carbon::parse($karyawan->tgl_masuk)
                                                : null;
                                            $workDuration = '-';
                                            $workYears = 0;
                                            $workMonths = 0;

                                            if ($joinDate) {
                                                $now = \Carbon\Carbon::now();
                                                $diffInDays = $joinDate->diffInDays($now);
                                                $years = floor($diffInDays / 365);
                                                $months = floor(($diffInDays % 365) / 30);
                                                $days = $diffInDays - $years * 365 - $months * 30;

                                                $workYears = $years;
                                                $workMonths = $months;

                                                $workDuration = '';
                                                if ($years > 0) {
                                                    $workDuration .= $years . ' th ';
                                                }
                                                if ($months > 0) {
                                                    $workDuration .= $months . ' bln ';
                                                }
                                                if ($days > 0) {
                                                    $workDuration .= $days . ' hr';
                                                }
                                                $workDuration = trim($workDuration);
                                            }

                                            // Get company name
                                            $perusahaan = $karyawan->perusahaanRelation
                                                ? $karyawan->perusahaanRelation->nama_prs2
                                                : '-';

                                            // Get contract status
                                            $kontrak = $karyawan->kontrakRelation
                                                ? $karyawan->kontrakRelation->singkatan_ktr
                                                : '-';

                                            // Get department
                                            $departemen = $karyawan->departemenRelation
                                                ? $karyawan->departemenRelation->singkatan_dep
                                                : '-';
                                            $jabatan = $karyawan->departemenRelation
                                                ? $karyawan->departemenRelation->singkatan_jbt
                                                : '-';
                                        @endphp
                                        <tr>
                                            <!-- Kolom Prioritas -->
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $karyawan->nrk ?? '-' }}
                                                <span class="text-muted"> {{ $karyawan->nik ?? '-' }}</span>
                                            </td>
                                            <td>{{ $karyawan->nama }}</td>
                                            <td>{{ $karyawan->tpt_lahir ?? '-' }}</td>
                                            <td>{{ $karyawan->tgl_lahir ? date('d-m-Y', strtotime($karyawan->tgl_lahir)) : '-' }}
                                            </td>
                                            <td>{{ $age }} {{ is_numeric($age) ? 'thn' : '' }}</td>
                                            <td class="text-center">
                                                @if ($karyawan->sex == 'LAKI-LAKI')
                                                    <i class="fas fa-mars text-primary" title="Laki-laki"></i>
                                                @elseif($karyawan->sex == 'PEREMPUAN')
                                                    <i class="fas fa-venus text-danger" title="Perempuan"></i>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($karyawan->foto_dokumen)
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
                                                            style="width: 32px; height: 32px; object-fit: cover; cursor: pointer;">
                                                    @else
                                                        <div class="employee-photo-placeholder">
                                                            {{ substr($karyawan->nama, 0, 1) }}
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="employee-photo-placeholder">
                                                        {{ substr($karyawan->nama, 0, 1) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $karyawan->tgl_masuk ? date('d-m-Y', strtotime($karyawan->tgl_masuk)) : '-' }}
                                            </td>
                                            <td><small>{{ Str::limit($perusahaan, 15) }}</small></td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ $kontrak }}</span>
                                            </td>
                                            <td>{{ $karyawan->tgl_akhir_ktr ? date('d-m-Y', strtotime($karyawan->tgl_akhir_ktr)) : '-' }}
                                            </td>
                                            <td><small>{{ Str::limit($departemen, 15) }}</small></td>
                                            <td><small>{{ Str::limit($jabatan, 15) }}</small></td>
                                            <td><small>{{ $karyawan->wilker ?? '-' }}</small></td>
                                            <td><small>{{ $karyawan->skt_wil_krj ?? '-' }}</small></td>
                                            <td class="text-center">
                                                @if ($karyawan->sts_kry == 'CALON')
                                                    <span class="badge badge-lg bg-warning text-dark">
                                                        <i class="fas fa-hourglass-half me-1"></i>CALON
                                                    </span>
                                                @elseif ($karyawan->sts_kry == 'AKTIF')
                                                    <span class="badge badge-lg bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>AK
                                                    </span>
                                                @elseif ($karyawan->sts_kry == 'NON-AKTIF')
                                                    <span class="badge badge-lg bg-danger">
                                                        <i class="fas fa-times-circle me-1"></i>NA
                                                    </span>
                                                @else
                                                    <span class="badge badge-lg bg-dark">{{ $karyawan->sts_kry }}</span>
                                                @endif
                                            </td>
                                            <!-- Kolom Tambahan -->
                                            <td>{{ $karyawan->tgl_phk ? date('d-m-Y', strtotime($karyawan->tgl_phk)) : '-' }}
                                            </td>
                                            <td>{{ $workDuration }}</td>
                                            <td> {{ $karyawan->creator ? $karyawan->creator->nama_kry : '-' }}
                                                <span style="font-size: 11px">{{ $karyawan->created_at ? $karyawan->created_at->format('d F Y H:i') : '-' }}</span>
                                            </td>
                                            <td> {{ $karyawan->updater ? $karyawan->updater->nama_kry : '-' }}
                                                <span style="font-size: 11px">{{ $karyawan->updated_at ? $karyawan->updated_at->format('d F Y H:i') : '-' }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-center">
                                                    @if (auth()->user()->is_admin || ($userPermissions['detail'] ?? false))
                                                        <a href="{{ route('data-karyawan.show', $karyawan->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                            title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->is_admin || ($userPermissions['ubah'] ?? false))
                                                        <a href="{{ route('data-karyawan.edit', $karyawan->id) }}"
                                                            class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                            title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->is_admin || ($userPermissions['hapus'] ?? false))
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger delete-confirm"
                                                            data-bs-toggle="tooltip" title="Hapus"
                                                            data-id="{{ $karyawan->id }}"
                                                            data-name="{{ $karyawan->nama }}">
                                                            <i class="fas fa-trash"></i>
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

    <!-- Modals -->
    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-filter me-2"></i>Filter Data Karyawan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="GET" action="{{ route('data-karyawan.index') }}">
                        <!-- Row 1: Basic Filters -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_status" class="form-label fw-bold">Status Karyawan</label>
                                    <select class="form-select" id="filter_status" name="filter_status">
                                        <option value="">Semua Status</option>
                                        @foreach ($statusOptions as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ $currentFilters['status'] == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_perusahaan" class="form-label fw-bold">Perusahaan</label>
                                    <select class="form-select" id="filter_perusahaan" name="filter_perusahaan">
                                        <option value="">Semua Perusahaan</option>
                                        @foreach ($perusahaans as $perusahaan)
                                            <option value="{{ $perusahaan->id }}"
                                                {{ $currentFilters['perusahaan'] == $perusahaan->id ? 'selected' : '' }}>
                                                {{ $perusahaan->nama_prs2 }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_jenis_kelamin" class="form-label fw-bold">Jenis Kelamin</label>
                                    <select class="form-select" id="filter_jenis_kelamin" name="filter_jenis_kelamin">
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
                        </div>

                        <!-- Row 2: Department & Position Filters -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_departemen" class="form-label fw-bold">Departemen</label>
                                    <select class="form-select" id="filter_departemen" name="filter_departemen">
                                        <option value="">Semua Departemen</option>
                                        @foreach ($departemenOptions as $departemen)
                                            <option value="{{ $departemen->id }}"
                                                {{ $currentFilters['departemen'] == $departemen->id ? 'selected' : '' }}>
                                                {{ $departemen->nama_dep }} @if($departemen->singkatan_dep)({{ $departemen->singkatan_dep }})@endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_jabatan" class="form-label fw-bold">Jabatan</label>
                                    <select class="form-select" id="filter_jabatan" name="filter_jabatan">
                                        <option value="">Semua Jabatan</option>
                                        @foreach ($jabatanOptions as $jabatan)
                                            <option value="{{ $jabatan->id }}"
                                                {{ $currentFilters['jabatan'] == $jabatan->id ? 'selected' : '' }}>
                                                {{ $jabatan->nama_jbt }} @if($jabatan->singkatan_jbt)({{ $jabatan->singkatan_jbt }})@endif - {{ $jabatan->nama_dep }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_kontrak" class="form-label fw-bold">Jenis Kontrak</label>
                                    <select class="form-select" id="filter_kontrak" name="filter_kontrak">
                                        <option value="">Semua Kontrak</option>
                                        @foreach ($kontrakOptions as $kontrak)
                                            <option value="{{ $kontrak->id }}"
                                                {{ $currentFilters['kontrak'] == $kontrak->id ? 'selected' : '' }}>
                                                {{ $kontrak->nama_ktr }} @if($kontrak->singkatan_ktr)({{ $kontrak->singkatan_ktr }})@endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Row 3: Work Area Filter -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_skt_wilker" class="form-label fw-bold">Singkatan Wilayah Kerja</label>
                                    <select class="form-select" id="filter_skt_wilker" name="filter_skt_wilker">
                                        <option value="">Semua Wilayah</option>
                                        @foreach ($sktWilkerOptions as $sktWilker)
                                            <option value="{{ $sktWilker }}"
                                                {{ $currentFilters['skt_wilker'] == $sktWilker ? 'selected' : '' }}>
                                                @if($sktWilker === 'null')
                                                    Tanpa Singkatan
                                                @else
                                                    {{ $sktWilker }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-group w-100">
                                    <button type="button" class="btn btn-secondary me-2" id="resetFilter">
                                        <i class="fas fa-redo me-1"></i>Reset Semua Filter
                                    </button>
                                    <button type="button" class="btn btn-primary" id="applyFilter">
                                        <i class="fas fa-search me-1"></i>Terapkan Filter
                                    </button>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus karyawan <strong id="employeeName"></strong>?</p>
                    <p class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Modal -->
    <div class="modal fade" id="summaryModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-chart-pie me-2"></i>Ringkasan Data Karyawan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Overall Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-primary shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                                    <h6 class="text-muted mb-2">Total Karyawan</h6>
                                    <h2 class="fw-bold text-primary" id="totalKaryawan">{{ $dataKaryawans->count() }}
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h6 class="text-muted mb-2">Karyawan Aktif</h6>
                                    <h2 class="fw-bold text-success" id="totalAktif">
                                        {{ $dataKaryawans->where('sts_kry', 'AKTIF')->count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-hourglass-half fa-3x text-warning mb-3"></i>
                                    <h6 class="text-muted mb-2">Karyawan Calon</h6>
                                    <h2 class="fw-bold text-warning" id="totalCalon">
                                        {{ $dataKaryawans->where('sts_kry', 'CALON')->count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-danger shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                                    <h6 class="text-muted mb-2">Karyawan Non-Aktif</h6>
                                    <h2 class="fw-bold text-danger" id="totalNonAktif">
                                        {{ $dataKaryawans->where('sts_kry', 'NON-AKTIF')->count() }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Company -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-building me-2 text-primary"></i>Berdasarkan Perusahaan
                        </h5>
                        <div class="row">
                            @foreach ($perusahaans as $perusahaan)
                                @php
                                    $count = $dataKaryawans->where('perusahaan', $perusahaan->id)->count();
                                @endphp
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card border-left-primary shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-muted mb-1 small">{{ $perusahaan->kode_prs }}</h6>
                                                    <p class="mb-0 fw-bold text-truncate"
                                                        title="{{ $perusahaan->nama_prs2 }}" style="max-width: 150px;">
                                                        {{ Str::limit($perusahaan->nama_prs2, 20) }}
                                                    </p>
                                                </div>
                                                <div class="text-end">
                                                    <h3 class="fw-bold text-primary mb-0">{{ $count }}</h3>
                                                </div>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    style="width: {{ $dataKaryawans->count() > 0 ? ($count / $dataKaryawans->count()) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Department -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-sitemap me-2 text-success"></i>Berdasarkan Departemen
                        </h5>
                        <div class="row">
                            @php
                                // Group departemen by nama_dep
                                $groupedDepartemen = $departemens->groupBy('nama_dep');
                            @endphp
                            @foreach ($groupedDepartemen as $namaDep => $deptGroup)
                                @php
                                    // Get all IDs for this department name
                                    $deptIds = $deptGroup->pluck('id')->toArray();
                                    // Count karyawan for all departments with this name
                                    $count = $dataKaryawans->whereIn('departemen', $deptIds)->count();
                                    // Get first department for display
                                    $firstDept = $deptGroup->first();
                                @endphp
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card border-left-success shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-muted mb-1 small">
                                                        {{ $deptGroup->pluck('kode_dep')->unique()->implode(', ') }}
                                                    </h6>
                                                    <p class="mb-0 fw-bold text-truncate" title="{{ $namaDep }}"
                                                        style="max-width: 150px;">
                                                        {{ Str::limit($namaDep, 20) }}
                                                    </p>
                                                    @if ($deptGroup->count() > 1)
                                                        <small class="text-muted">
                                                            <i class="fas fa-layer-group"></i> {{ $deptGroup->count() }}
                                                            sub
                                                        </small>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <h3 class="fw-bold text-success mb-0">{{ $count }}</h3>
                                                </div>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $dataKaryawans->count() > 0 ? ($count / $dataKaryawans->count()) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Gender -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-users me-2 text-warning"></i>Berdasarkan Jenis Kelamin
                        </h5>
                        <div class="row">
                            @foreach ($jenisKelaminOptions as $gender => $genderLabel)
                                @php
                                    $count = $dataKaryawans->where('sex', $gender)->count();
                                @endphp
                                <div class="col-md-6 mb-3">
                                    <div class="card border-left-warning shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-muted mb-1 small">
                                                        @if($gender == 'LAKI-LAKI')
                                                            <i class="fas fa-mars text-primary"></i>
                                                        @else
                                                            <i class="fas fa-venus text-danger"></i>
                                                        @endif
                                                    </h6>
                                                    <p class="mb-0 fw-bold">{{ $genderLabel }}</p>
                                                </div>
                                                <div class="text-end">
                                                    <h3 class="fw-bold text-warning mb-0">{{ $count }}</h3>
                                                </div>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                    style="width: {{ $dataKaryawans->count() > 0 ? ($count / $dataKaryawans->count()) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Contract Type -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-file-contract me-2 text-secondary"></i>Berdasarkan Jenis Kontrak
                        </h5>
                        <div class="row">
                            @foreach ($kontrakOptions as $kontrak)
                                @php
                                    $count = $dataKaryawans->where('sts_ktr', $kontrak->id)->count();
                                @endphp
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card border-left-secondary shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-muted mb-1 small">{{ $kontrak->kode_ktr }}</h6>
                                                    <p class="mb-0 fw-bold text-truncate"
                                                        title="{{ $kontrak->nama_ktr }}" style="max-width: 150px;">
                                                        {{ Str::limit($kontrak->nama_ktr, 20) }}
                                                    </p>
                                                </div>
                                                <div class="text-end">
                                                    <h3 class="fw-bold text-secondary mb-0">{{ $count }}</h3>
                                                </div>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-secondary" role="progressbar"
                                                    style="width: {{ $dataKaryawans->count() > 0 ? ($count / $dataKaryawans->count()) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Work Area -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-map-marked-alt me-2 text-info"></i>Berdasarkan Wilayah
                            Kerja</h5>
                        <div class="row">
                            @foreach ($wilayahKerjas as $wilker)
                                @php
                                    $count = $dataKaryawans->where('wilker', $wilker->id)->count();
                                @endphp
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card border-left-info shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-muted mb-1 small">{{ $wilker->kode_wk }}</h6>
                                                    <p class="mb-0 fw-bold text-truncate"
                                                        title="{{ $wilker->wilayah_krj }}" style="max-width: 150px;">
                                                        {{ Str::limit($wilker->wilayah_krj, 20) }} -
                                                        {{ Str::limit($wilker->area_krj, 20) }}
                                                    </p>
                                                </div>
                                                <div class="text-end">
                                                    <h3 class="fw-bold text-info mb-0">{{ $count }}</h3>
                                                </div>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-info" role="progressbar"
                                                    style="width: {{ $dataKaryawans->count() > 0 ? ($count / $dataKaryawans->count()) * 100 : 0 }}%">
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
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        .border-left-primary {
            border-left: 4px solid #0d6efd !important;
        }

        .border-left-success {
            border-left: 4px solid #198754 !important;
        }

        .border-left-info {
            border-left: 4px solid #0dcaf0 !important;
        }

        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }

        .border-left-secondary {
            border-left: 4px solid #6c757d !important;
        }

        .card:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease-in-out;
        }

        .dataKaryawanPage .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table th {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            font-weight: 600;
            font-size: 0.8rem;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
            border-color: #dee2e6;
            font-size: 0.8rem;
        }

        .employee-photo {
            width: 32px !important;
            height: 32px !important;
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
            width: 32px !important;
            height: 32px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.85rem;
            margin: 0 auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .employee-photo-placeholder:hover {
            transform: scale(1.15);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
        }

        .badge-lg {
            font-size: 0.75em;
            font-weight: 600;
            padding: 0.4em 0.8em;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3em;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%) !important;
        }

        .text-muted.small {
            font-size: 0.75rem;
            color: #6c757d !important;
            opacity: 0.7;
        }

        .alert {
            border: none;
            border-radius: 0.5rem;
        }

        /* Filter badge styling */
        .alert .badge {
            margin: 0.2rem;
        }

        /* Responsive table */
        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.75rem;
            }

            .employee-photo,
            .employee-photo-placeholder {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
            }

            .badge-lg {
                font-size: 0.65em;
                padding: 0.3em 0.6em;
            }

            .btn {
                font-size: 0.7rem;
                padding: 0.2rem 0.4rem;
            }

            .table th {
                font-size: 0.75rem;
            }

            .table td {
                font-size: 0.75rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            let table;
            if (!$.fn.DataTable.isDataTable('#dataKaryawanTable')) {
                table = $('#dataKaryawanTable').DataTable({
                    pageLength: 25,
                    language: {
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        zeroRecords: "Data tidak ditemukan",
                        info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                        infoEmpty: "Tidak ada data yang tersedia",
                        infoFiltered: "(difilter dari _MAX_ total data)",
                        search: "Cari:",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        }
                    }
                });
            } else {
                table = $('#dataKaryawanTable').DataTable();
            }

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Filter modal
            $('#filterButton').click(function() {
                $('#filterModal').modal('show');
            });

            // Apply filters
            $('#applyFilter').click(function() {
                $('#filterForm').submit();
            });

            // Reset filters
            $('#resetFilter').click(function() {
                $('#filter_status').val('');
                $('#filter_perusahaan').val('');
                $('#filter_departemen').val('');
                $('#filter_jabatan').val('');
                $('#filter_kontrak').val('');
                $('#filter_jenis_kelamin').val('');
                $('#filter_skt_wilker').val('');
            });

            // Summary modal
            $('#summaryButton').click(function() {
                $('#summaryModal').modal('show');
            });

            // Export modal
            $('#exportButton').click(function() {
                $('#exportModal').modal('show');
            });

            // Export Excel
            $('#exportExcel').click(function() {
                let currentParams = new URLSearchParams(window.location.search);
                let url = "{{ route('data-karyawan.export-excel') }}?" + currentParams.toString();
                window.location.href = url;
                $('#exportModal').modal('hide');
            });

            // Delete confirmation
            $(document).on('click', '.delete-confirm', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');

                $('#employeeName').text(name);
                $('#deleteForm').attr('action', "{{ url('data-karyawan') }}/" + id);
                $('#deleteConfirmationModal').modal('show');
            });

            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Click to view larger photo
            $(document).on('click', '.employee-photo', function(e) {
                e.preventDefault();
                e.stopPropagation();

                let src = $(this).attr('src');
                let alt = $(this).attr('alt');

                // Remove existing modal if any
                $('#photoModal').remove();

                let modal = `
                    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="photoModalLabel">${alt}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="${src}" class="img-fluid rounded" alt="${alt}" style="max-height: 70vh; object-fit: contain;">
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('body').append(modal);

                // Create Bootstrap modal instance and show it
                let photoModal = new bootstrap.Modal(document.getElementById('photoModal'));
                photoModal.show();

                // Remove modal from DOM when hidden
                document.getElementById('photoModal').addEventListener('hidden.bs.modal', function() {
                    this.remove();
                });
            });
        });
    </script>
@endpush