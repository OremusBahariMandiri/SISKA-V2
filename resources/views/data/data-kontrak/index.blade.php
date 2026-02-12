@extends('layouts.app')

@section('title', 'Data Kontrak')

@section('content')
    <div class="container-fluid dataKontrakPage">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-contract me-2"></i>Data Kontrak</span>
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
                                <a href="{{ route('data-kontrak.create') }}" class="btn btn-light">
                                    <i class="fas fa-plus-circle me-1"></i> Tambah
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3 contract-status-summary">
                            <span id="expiredContractsBadge" class="badge bg-danger me-2" style="font-size: 0.9rem;">
                                <i class="fas fa-exclamation-circle me-1"></i> Kontrak Expired : <span
                                    id="expiredContractsCount">0</span>
                            </span>
                            <span id="warningContractsBadge" class="badge text-dark me-2"
                                style="font-size: 0.9rem; background-color:#ffff66">
                                <i class="fas fa-exclamation-triangle me-1"></i>Kontrak Akan Expired : <span
                                    id="warningContractsCount">0</span>
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
                        @if (
                            !empty($currentFilters['status']) ||
                                !empty($currentFilters['perusahaan']) ||
                                !empty($currentFilters['kontrak_type']) ||
                                !empty($currentFilters['departemen']) ||
                                !empty($currentFilters['wilayah_kerja']) ||
                                !empty($currentFilters['contract_status']) ||
                                !empty($currentFilters['education_level']) ||
                                !empty($currentFilters['search']))
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
                                        Perusahaan: <span
                                            class="badge bg-success">{{ $selectedPerusahaan->nama_prs2 }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['kontrak_type']))
                                    @php
                                        $selectedKontrak = $kontrakTypes
                                            ->where('id', $currentFilters['kontrak_type'])
                                            ->first();
                                    @endphp
                                    @if ($selectedKontrak)
                                        Tipe Kontrak: <span class="badge bg-info">{{ $selectedKontrak->nama_ktr }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['departemen']))
                                    @php
                                        $selectedDepartemen = $departemens
                                            ->where('id', $currentFilters['departemen'])
                                            ->first();
                                    @endphp
                                    @if ($selectedDepartemen)
                                        Departemen: <span
                                            class="badge bg-warning">{{ $selectedDepartemen->nama_dep }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['wilayah_kerja']))
                                    @php
                                        $selectedWilayah = $wilayahKerjas
                                            ->where('id', $currentFilters['wilayah_kerja'])
                                            ->first();
                                    @endphp
                                    @if ($selectedWilayah)
                                        Wilayah: <span class="badge bg-secondary">{{ $selectedWilayah->wilayah_krj }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['contract_status']))
                                    Status Kontrak: <span
                                        class="badge bg-dark">{{ $contractStatusOptions[$currentFilters['contract_status']] ?? $currentFilters['contract_status'] }}</span>
                                @endif

                                @if (!empty($currentFilters['education_level']))
                                    Pendidikan: <span
                                        class="badge bg-light text-dark">{{ $currentFilters['education_level'] }}</span>
                                @endif

                                @if (!empty($currentFilters['search']))
                                    Pencarian: <span class="badge bg-danger">{{ $currentFilters['search'] }}</span>
                                @endif

                                <a href="{{ route('data-kontrak.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Reset Filter
                                </a>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="dataKontrakTable" class="table table-bordered table-striped data-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="2%" class="text-center">NO</th>
                                        <th width="4%" class="text-center">NRK</th>
                                        <th width="6%" class="text-center">NAMA</th>
                                        <th width="3%" class="text-center">UMUR</th>
                                        <th width="3%" class="text-center">SEX</th>
                                        <th width="3%" class="text-center">FOTO</th>
                                        <th width="5%" class="text-center">TGL MSK</th>
                                        <th width="4%" class="text-center">MKR</th>
                                        <th width="5%" class="text-center">JSKL</th>
                                        <th width="5%" class="text-center">DEP</th>
                                        <th width="5%" class="text-center">JBT</th>
                                        <th width="5%" class="text-center">WILKER</th>
                                        <th width="5%" class="text-center">PRSH</th>
                                        <th width="5%" class="text-center">NO SR KTR</th>
                                        <th width="3%" class="text-center">TGL SR KTR</th>
                                        <th width="4%" class="text-center">STS KTR</th>
                                        <th width="5%" class="text-center">TGL AW KTR</th>
                                        <th width="5%" class="text-center">TGL AK KTR</th>
                                        <th width="4%" class="text-center">DUR KTR</th>
                                        <th width="5%" class="text-center">TGL PER</th>
                                        <th width="5%" class="text-center">PERINGATAN</th>
                                        <th width="3%" class="text-center">DOK</th>
                                        <th width="4%" class="text-center">STS SR</th>
                                        <th width="5%" class="text-center">TGL SR NA</th>
                                        <th width="8%" class="text-center">CREATE</th>
                                        <th width="8%" class="text-center">UPDATE</th>
                                        <th width="6%" class="text-center no-wrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataKontraks as $kontrak)
                                        @php
                                            // Calculate contract duration and remaining days
                                            $remainingDays = null;
                                            $isActive = false;
                                            $isExpired = false;
                                            $isExpiringSoon = false;

                                            if ($kontrak->tgl_akhir_ktr) {
                                                $endDate = \Carbon\Carbon::parse($kontrak->tgl_akhir_ktr);
                                                $now = \Carbon\Carbon::now();
                                                $remainingDays = $now->diffInDays($endDate, false);
                                                $isExpired = $remainingDays < 0;
                                                $isExpiringSoon = $remainingDays >= 0 && $remainingDays <= 30;

                                                if ($kontrak->tgl_awl_ktr) {
                                                    $startDate = \Carbon\Carbon::parse($kontrak->tgl_awl_ktr);
                                                    $isActive =
                                                        $now->between($startDate, $endDate) &&
                                                        $kontrak->sts_srt_ktr == 'AKTIF';
                                                }
                                            }

                                            // Get related data
                                            $karyawan = $kontrak->karyawan;
                                            $kontrakType = $kontrak->kontrakKerja;
                                            $perusahaan = $kontrak->perusahaan;
                                            $departemen = $kontrak->departemen;
                                            $wilayah = $kontrak->wilayahKerja;

                                            // Format education
                                            $education = $kontrak->jenjang_skl;
                                            if ($kontrak->jurusan_skl) {
                                                $education .= ' - ' . $kontrak->jurusan_skl;
                                            }

                                            // Calculate age
                                            $age = null;
                                            if ($karyawan && $karyawan->tgl_lahir) {
                                                $birthDate = \Carbon\Carbon::parse($karyawan->tgl_lahir);
                                                $age = $birthDate->age;
                                            }

                                            // Calculate work duration
                                            $workDuration = null;
                                            if ($karyawan && $karyawan->tgl_masuk) {
                                                $startWork = \Carbon\Carbon::parse($karyawan->tgl_masuk);
                                                $workDurationYears = $startWork->diffInYears(\Carbon\Carbon::now());
                                                $workDurationMonths =
                                                    $startWork->diffInMonths(\Carbon\Carbon::now()) % 12;
                                                $workDuration = $workDurationYears . 'th';
                                                if ($workDurationMonths > 0) {
                                                    $workDuration .= ' ' . $workDurationMonths . 'bl';
                                                }
                                            }
                                        @endphp
                                        <tr data-tgl-pengingat-kontrak="{{ $kontrak->tgl_pgt_ktr ? \Carbon\Carbon::parse($kontrak->tgl_pgt_ktr)->format('Y-m-d') : '' }}">

                                            <!-- NO -->
                                            <td class="text-center">{{ $loop->iteration }}</td>

                                            <!-- NRK -->
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $karyawan->nrk ?? '-' }}</span>
                                                <span class="badge bg-danger">{{ $karyawan->nik ?? '-' }}</span>
                                            </td>

                                            <!-- NAMA -->
                                            <td>
                                                <div class="fw-bold">{{ $karyawan->nama ?? '-' }}</div>
                                            </td>

                                            <!-- UMUR -->
                                            <td class="text-center">
                                                @if ($age)
                                                    <span class="badge bg-info">{{ $age }} th</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- JK (Jenis Kelamin) -->
                                            <td class="text-center">
                                                @if ($karyawan && $karyawan->sex)
                                                    <span
                                                        class="badge {{ $karyawan->sex == 'L' ? 'bg-primary' : 'bg-pink' }}">
                                                        {{ $karyawan->sex == 'L' ? 'L' : 'P' }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- FOTO -->
                                            <td class="text-center">
                                                @if ($karyawan && $karyawan->foto_dokumen)
                                                    <img src="{{ asset('storage/' . $karyawan->foto_dokumen) }}"
                                                        alt="Foto" class="rounded-circle" width="40"
                                                        height="40" style="object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                @endif
                                            </td>

                                            <!-- TGL MASUK -->
                                            <td class="text-center">
                                                {{ $karyawan && $karyawan->tgl_masuk ? $karyawan->tgl_masuk->format('d-m-Y') : '-' }}
                                            </td>

                                            <!-- MASA KERJA -->
                                            <td class="text-center">
                                                @if ($workDuration)
                                                    <span class="badge bg-success">{{ $workDuration }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- PENDIDIKAN -->
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $kontrak->jenjang_skl }}</span>

                                            </td>

                                            <!-- DEPARTEMEN -->
                                            <td class="text-center">
                                                @if ($departemen)
                                                    <span class="badge bg-warning">{{ $departemen->singkatan_dep }}</span>
                                                @elseif($karyawan && $karyawan->departemenRelation)
                                                    <span
                                                        class="badge bg-warning">{{ $karyawan->departemenRelation->singkatan_dep }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- JABATAN -->
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ $departemen->singkatan_jbt ?? '-' }}</span>
                                            </td>

                                            <!-- WILKER -->
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $wilayah->singkatan_wk ?? '-' }}</span>
                                            </td>

                                            <!-- PERUSAHAAN -->
                                            <td class="text-center">
                                                <span class="badge bg-success">{{ $perusahaan->nama_prs2 ?? '-' }}</span>
                                            </td>

                                            <!-- NO KONTRAK -->
                                            <td class="text-center">
                                                <small>{{ $kontrak->no_srt_ktr ?? '-' }}</small>
                                            </td>

                                            <!-- TGL KONTRAK -->
                                            <td class="text-center">
                                                {{ $kontrak->tgl_srt_ktr ? date('d-m-Y', strtotime($kontrak->tgl_srt_ktr)) : '-' }}
                                            </td>

                                            <!-- STATUS -->
                                            <td class="text-center">
                                                @if ($kontrakType->singkatan_ktr == 'PKWTT')
                                                    <span class="badge badge-lg bg-success">
                                                        <i class="fas fa-times-circle me-1"></i>PKWTT
                                                    </span>
                                                @elseif ($kontrakType->singkatan_ktr == 'PKWT')
                                                    <span class="badge badge-lg bg-secondary">PKWT
                                                    </span>
                                                @elseif ($kontrakType->singkatan_ktr == 'SPK')
                                                    <span class="badge badge-lg bg-danger">SPK
                                                    </span>
                                                @elseif ($kontrakType->singkatan_ktr == 'PENDING')
                                                    <span class="badge badge-lg bg-warning text-dark">PENDING
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge badge-lg bg-dark">{{ $kontrakType->singkatan_ktr }}</span>
                                                @endif
                                            </td>

                                            <!-- TGL MULAI -->
                                            <td class="text-center">
                                                {{ $kontrak->tgl_awl_ktr ? date('d-m-Y', strtotime($kontrak->tgl_awl_ktr)) : '-' }}
                                            </td>

                                            <!-- TGL AKHIR -->
                                            <td class="text-center">
                                                {{ $kontrak->tgl_akhir_ktr ? date('d-m-Y', strtotime($kontrak->tgl_akhir_ktr)) : '-' }}
                                            </td>

                                            <!-- DURASI -->
                                            <td class="text-center">
                                                @if ($kontrak->durasi_ktr)
                                                    <span class="badge bg-info">{{ $kontrak->durasi_ktr }} bln</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- TGL PGT (Tanggal Pengingat) -->
                                            <td class="text-center">
                                                {{ $kontrak->tgl_pgt_ktr ? date('d-m-Y', strtotime($kontrak->tgl_pgt_ktr)) : '-' }}
                                            </td>

                                            <!-- PERINGATAN (NEW COLUMN) -->
                                            <td class="text-center sisa-peringatan-col">
                                                -
                                            </td>

                                            <!-- DOKUMEN -->
                                            <td class="text-center">
                                                @if ($kontrak->file_doc_ktr)
                                                    <a href="{{ asset('storage/' . $kontrak->file_doc_ktr) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="tooltip" title="Lihat Dokumen">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <!-- STS SR (Status Surat) -->
                                            <td class="text-center">
                                                @if ($kontrak->sts_srt_ktr == 'AKTIF')
                                                    <span class="badge bg-success">{{ $kontrak->sts_srt_ktr }}</span>
                                                @elseif ($kontrak->sts_srt_ktr == 'NON-AKTIF')
                                                    <span class="badge bg-secondary">{{ $kontrak->sts_srt_ktr }}</span>
                                                @elseif ($kontrak->sts_srt_ktr == 'EXPIRED')
                                                    <span class="badge bg-danger">{{ $kontrak->sts_srt_ktr }}</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">{{ $kontrak->sts_srt_ktr ?? '-' }}</span>
                                                @endif
                                            </td>

                                            <!-- TGL SR NA (Tanggal Surat Non Aktif) -->
                                            <td class="text-center">
                                                {{ $kontrak->tgl_sr_na ? date('d-m-Y', strtotime($kontrak->tgl_sr_na)) : '-' }}
                                            </td>

                                            <td> {{ $kontrak->creator ? $kontrak->creator->nama_kry : '-' }}
                                                <span
                                                    style="font-size: 11px">{{ $kontrak->created_at ? $kontrak->created_at->format('d/m/y H:i') : '-' }}</span>
                                            </td>
                                            <td> {{ $kontrak->updater ? $kontrak->updater->nama_kry : '-' }}
                                                <span
                                                    style="font-size: 11px">{{ $kontrak->updated_at ? $kontrak->updated_at->format('d/m/y H:i') : '-' }}</span>
                                            </td>

                                            <!-- AKSI -->
                                            <td class="text-center no-wrap">
                                                <div class="btn-group" role="group" aria-label="Actions">
                                                    @if (auth()->user()->is_admin || ($userPermissions['detail'] ?? false))
                                                        <a href="{{ route('data-kontrak.show', $kontrak->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                            title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->is_admin || ($userPermissions['ubah'] ?? false))
                                                        <a href="{{ route('data-kontrak.edit', $kontrak->id) }}"
                                                            class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                            title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->is_admin || ($userPermissions['hapus'] ?? false))
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger delete-confirm"
                                                            data-bs-toggle="tooltip" title="Hapus"
                                                            data-id="{{ $kontrak->id }}"
                                                            data-name="{{ $karyawan->nama ?? 'Kontrak' }}">
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

    <!-- Modals - keeping all existing modals as they are -->
    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-filter me-2"></i>Filter Data Kontrak</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="GET" action="{{ route('data-kontrak.index') }}">
                        <!-- Row 1: Basic Filters -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_status" class="form-label fw-bold">Status Kontrak</label>
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
                                    <label for="filter_contract_status" class="form-label fw-bold">Status Masa
                                        Kontrak</label>
                                    <select class="form-select" id="filter_contract_status"
                                        name="filter_contract_status">
                                        <option value="">Semua Kondisi</option>
                                        @foreach ($contractStatusOptions as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ $currentFilters['contract_status'] == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_kontrak_type" class="form-label fw-bold">Jenis Kontrak</label>
                                    <select class="form-select" id="filter_kontrak_type" name="filter_kontrak_type">
                                        <option value="">Semua Jenis</option>
                                        @foreach ($kontrakTypes as $kontrak)
                                            <option value="{{ $kontrak->id }}"
                                                {{ $currentFilters['kontrak_type'] == $kontrak->id ? 'selected' : '' }}>
                                                {{ $kontrak->nama_ktr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Row 2: Company & Department Filters -->
                        <div class="row mb-3">
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
                                    <label for="filter_departemen" class="form-label fw-bold">Departemen</label>
                                    <select class="form-select" id="filter_departemen" name="filter_departemen">
                                        <option value="">Semua Departemen</option>
                                        @foreach ($departemens as $departemen)
                                            <option value="{{ $departemen->id }}"
                                                {{ $currentFilters['departemen'] == $departemen->id ? 'selected' : '' }}>
                                                {{ $departemen->nama_dep }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_wilayah_kerja" class="form-label fw-bold">Wilayah Kerja</label>
                                    <select class="form-select" id="filter_wilayah_kerja" name="filter_wilayah_kerja">
                                        <option value="">Semua Wilayah</option>
                                        @foreach ($wilayahKerjas as $wilayah)
                                            <option value="{{ $wilayah->id }}"
                                                {{ $currentFilters['wilayah_kerja'] == $wilayah->id ? 'selected' : '' }}>
                                                {{ $wilayah->wilayah_krj }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Row 3: Education & Search -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_education_level" class="form-label fw-bold">Jenjang
                                        Pendidikan</label>
                                    <select class="form-select" id="filter_education_level"
                                        name="filter_education_level">
                                        <option value="">Semua Jenjang</option>
                                        @foreach ($educationLevelOptions as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ $currentFilters['education_level'] == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="search" class="form-label fw-bold">Pencarian</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                        value="{{ $currentFilters['search'] }}"
                                        placeholder="Cari berdasarkan nama karyawan, NRK, NIK, atau nomor kontrak...">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" id="resetFilter">
                                    <i class="fas fa-redo me-1"></i>Reset Filter
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
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-chart-pie me-2"></i>Ringkasan Data Kontrak</h5>
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
                                    <h2 class="fw-bold text-primary">{{ $dataKontraks->count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h6 class="text-muted mb-2">Kontrak Aktif</h6>
                                    <h2 class="fw-bold text-success">
                                        {{ $dataKontraks->where('sts_srt_ktr', 'AKTIF')->count() }}
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                    <h6 class="text-muted mb-2">Akan Berakhir</h6>
                                    <h2 class="fw-bold text-warning">
                                        @php
                                            $expiringSoon = $dataKontraks->filter(function ($kontrak) {
                                                if (!$kontrak->tgl_akhir_ktr) {
                                                    return false;
                                                }
                                                $endDate = \Carbon\Carbon::parse($kontrak->tgl_akhir_ktr);
                                                $now = \Carbon\Carbon::now();
                                                $remainingDays = $now->diffInDays($endDate, false);
                                                return $remainingDays >= 0 && $remainingDays <= 30;
                                            });
                                        @endphp
                                        {{ $expiringSoon->count() }}
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-danger shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                                    <h6 class="text-muted mb-2">Sudah Berakhir</h6>
                                    <h2 class="fw-bold text-danger">
                                        @php
                                            $expired = $dataKontraks->filter(function ($kontrak) {
                                                if (!$kontrak->tgl_akhir_ktr) {
                                                    return false;
                                                }
                                                $endDate = \Carbon\Carbon::parse($kontrak->tgl_akhir_ktr);
                                                $now = \Carbon\Carbon::now();
                                                return $now->greaterThan($endDate);
                                            });
                                        @endphp
                                        {{ $expired->count() }}
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Contract Type -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-clipboard-list me-2 text-primary"></i>Berdasarkan Jenis
                            Kontrak</h5>
                        <div class="row">
                            @foreach ($kontrakTypes as $type)
                                @php
                                    $count = $dataKontraks->where('id_ktr', $type->id)->count();
                                @endphp
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card border-left-primary shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-muted mb-1 small">{{ $type->kode_ktr }}</h6>
                                                    <p class="mb-0 fw-bold text-truncate" title="{{ $type->nama_ktr }}"
                                                        style="max-width: 150px;">
                                                        {{ Str::limit($type->nama_ktr, 20) }}
                                                    </p>
                                                </div>
                                                <div class="text-end">
                                                    <h3 class="fw-bold text-primary mb-0">{{ $count }}</h3>
                                                </div>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    style="width: {{ $dataKontraks->count() > 0 ? ($count / $dataKontraks->count()) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Company -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-building me-2 text-success"></i>Berdasarkan Perusahaan
                        </h5>
                        <div class="row">
                            @foreach ($perusahaans as $perusahaan)
                                @php
                                    $count = $dataKontraks->where('id_prsh', $perusahaan->id)->count();
                                @endphp
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card border-left-success shadow-sm h-100">
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
                                                    <h3 class="fw-bold text-success mb-0">{{ $count }}</h3>
                                                </div>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $dataKontraks->count() > 0 ? ($count / $dataKontraks->count()) * 100 : 0 }}%">
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kontrak untuk <strong id="contractName"></strong>?</p>
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

        .dataKontrakPage .card {
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

        /* Force AKSI column to never wrap or break */
        .no-wrap {
            white-space: nowrap !important;
            min-width: 120px !important;
        }

        .btn-group {
            display: flex;
            gap: 2px;
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

        .badge.bg-pink {
            background-color: #e91e63 !important;
            color: white !important;
        }

        /* ===== HIGHLIGHT ROWS STYLING - Same as dokumen legal ===== */
        .dataKontrakPage table#dataKontrakTable tbody tr.highlight-red {
            background-color: #fc0000 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        .dataKontrakPage table#dataKontrakTable tbody tr.highlight-yellow {
            background-color: #ffff00 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        .dataKontrakPage table#dataKontrakTable tbody tr.highlight-orange {
            background-color: #00e013 !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        .dataKontrakPage table#dataKontrakTable tbody tr.highlight-gray {
            background-color: #cccccc !important;
            color: rgb(0, 0, 0) !important;
            --bs-table-accent-bg: none !important;
            --bs-table-striped-bg: none !important;
        }

        /* Ensure hover states don't override highlight colors */
        .dataKontrakPage table#dataKontrakTable tbody tr.highlight-red:hover {
            background-color: #ff3333 !important;
        }

        .dataKontrakPage table#dataKontrakTable tbody tr.highlight-yellow:hover {
            background-color: #ffff66 !important;
        }

        .dataKontrakPage table#dataKontrakTable tbody tr.highlight-orange:hover {
            background-color: #00e013 !important;
        }

        .dataKontrakPage table#dataKontrakTable tbody tr.highlight-gray:hover {
            background-color: #dddddd !important;
        }

        /* Override Bootstrap's striped table styles */
        .dataKontrakPage .table-striped>tbody>tr:nth-of-type(odd).highlight-red,
        .dataKontrakPage .table-striped>tbody>tr:nth-of-type(even).highlight-red {
            background-color: #fc0000 !important;
        }

        .dataKontrakPage .table-striped>tbody>tr:nth-of-type(odd).highlight-yellow,
        .dataKontrakPage .table-striped>tbody>tr:nth-of-type(even).highlight-yellow {
            background-color: #ffff00 !important;
        }

        .dataKontrakPage .table-striped>tbody>tr:nth-of-type(odd).highlight-orange,
        .dataKontrakPage .table-striped>tbody>tr:nth-of-type(even).highlight-orange {
            background-color: #00e013 !important;
        }

        .dataKontrakPage .table-striped>tbody>tr:nth-of-type(odd).highlight-gray,
        .dataKontrakPage .table-striped>tbody>tr:nth-of-type(even).highlight-gray {
            background-color: #cccccc !important;
        }

        /* Memastikan kolom tabel tetap terlihat meskipun dalam baris yang di-highlight */
        .dataKontrakPage table#dataKontrakTable tbody tr.highlight-red>td,
        .dataKontrakPage table#dataKontrakTable tbody tr.highlight-yellow>td,
        .dataKontrakPage table#dataKontrakTable tbody tr.highlight-orange>td,
        .dataKontrakPage table#dataKontrakTable tbody tr.highlight-gray>td {
            background-color: inherit !important;
        }

        .text-muted.small {
            font-size: 0.7rem;
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
                font-size: 0.7rem;
            }

            .badge-lg {
                font-size: 0.6em;
                padding: 0.25em 0.5em;
            }

            .btn {
                font-size: 0.65rem;
                padding: 0.2rem 0.4rem;
            }

            .table th {
                font-size: 0.7rem;
            }

            .table td {
                font-size: 0.7rem;
            }
        }

        /* Photo styling */
        .rounded-circle {
            border: 2px solid #dee2e6;
        }

        /* Contract status summary badges */
        .contract-status-summary {
            margin-bottom: 1rem;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            border-left: 4px solid #0d6efd;
        }

        .contract-status-summary .badge {
            font-size: 0.9rem !important;
            padding: 0.5em 0.75em;
            margin-right: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Add hover effect to action buttons */
        .dataKontrakPage .btn-sm {
            transition: transform 0.2s;
        }

        .dataKontrakPage .btn-sm:hover {
            transform: scale(1.1);
        }

        /* Hover effect for table rows */
        .dataKontrakPage #dataKontrakTable tbody tr {
            transition: all 0.2s ease;
        }

        .dataKontrakPage #dataKontrakTable tbody tr:hover {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            cursor: pointer;
            position: relative;
            z-index: 1;
        }

        /* Flash effect when hovering */
        @keyframes flashBorder {
            0% {
                box-shadow: 0 0 0 rgba(13, 110, 253, 0);
            }

            50% {
                box-shadow: 0 0 8px rgba(13, 110, 253, 0.5);
            }

            100% {
                box-shadow: 0 0 0 rgba(13, 110, 253, 0);
            }
        }

        .dataKontrakPage #dataKontrakTable tbody tr.row-hover-active {
            animation: flashBorder 1s ease infinite;
        }

        /* Highlight filter active state */
        .filter-active {
            background-color: #e8f4ff !important;
            border-left: 3px solid #0d6efd !important;
        }



        /* Responsive table controls visibility */
        table.dataTable.dtr-inline.collapsed tbody tr.parent td.control:before,
        table.dataTable.dtr-inline.collapsed tbody tr.parent td.dtr-control:before {
            transform: translate(-50%, -50%) rotate(90deg) !important;
        }

        table.dataTable.dtr-inline.collapsed tbody td.control,
        table.dataTable.dtr-inline.collapsed tbody td.dtr-control {
            position: relative;
        }

        table.dataTable.dtr-inline.collapsed tbody td.control:before,
        table.dataTable.dtr-inline.collapsed tbody td.dtr-control:before {
            content: "▷";
            font-size: 12px;
            color: #337ab7;
            cursor: pointer;
            position: absolute;
            left: 5px;
            top: 50%;
            transform: translateY(-50%);
        }



        /* Style the child row details */

        table.dataTable tbody tr.child td {
            border-top: 1px solid #dee2e6;
        }

        table.dataTable tbody tr.child ul.dtr-details {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        table.dataTable tbody tr.child ul.dtr-details li {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        table.dataTable tbody tr.child ul.dtr-details li:last-child {
            border-bottom: none;
        }


    </style>
@endpush

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        $(document).ready(function() {
            // Destroy existing DataTable if it exists
            if ($.fn.DataTable.isDataTable('#dataKontrakTable')) {
                $('#dataKontrakTable').DataTable().destroy();
            }

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Function to calculate contract stats based on reminder dates (same logic as dokumen legal)
            function calculateContractStats() {
                let expiredCount = 0;
                let warningCount = 0;

                $('#dataKontrakTable tbody tr').each(function() {
                    const row = $(this);

                    // 1. Check contract status first - STS SR column (index 22, 0-based)
                    const statusText = row.find('td:eq(22)').text().trim();

                    // Skip non-active contracts
                    if (statusText.includes("NON-AKTIF") || statusText.includes("EXPIRED") || statusText.includes("PENDING")) {
                        return true; // continue to next iteration
                    }

                    // 2. Check if contract is expired based on TglAkhirKtr (index 17, 0-based)
                    const tglAkhir = row.find('td:eq(17)').text().trim();
                    if (tglAkhir !== '-') {
                        const akhirDate = moment(tglAkhir, 'DD-MM-YYYY');
                        const today = moment().startOf('day');

                        if (akhirDate.isBefore(today)) {
                            expiredCount++;
                            return true; // Already counted as expired, continue to next row
                        }
                    }

                    // 3. Check TglPengingat for expired/warning (main logic like dokumen legal)
                    const tglPengingatStr = row.data('tgl-pengingat-kontrak');
                    if (tglPengingatStr) {
                        const tglPengingat = moment(tglPengingatStr);
                        const today = moment().startOf('day');
                        const diffDays = tglPengingat.diff(today, 'days');

                        if (diffDays <= 0) {
                            // Reminder date has passed or is today
                            expiredCount++;
                            return true; // continue
                        } else if (diffDays <= 30) {
                            // Warning: within 30 days
                            warningCount++;
                            return true; // continue
                        }
                    }

                    // 4. Check TglAkhirKtr for warning (30 days) - fallback if no reminder date
                    if (tglAkhir !== '-' && !tglPengingatStr) {
                        const akhirDate = moment(tglAkhir, 'DD-MM-YYYY');
                        const today = moment().startOf('day');

                        if (akhirDate.isAfter(today) && akhirDate.diff(today, 'days') <= 30) {
                            warningCount++;
                        }
                    }
                });

                // Update counter badges
                $('#expiredContractsCount').text(expiredCount);
                $('#warningContractsCount').text(warningCount);
            }

            // Function to apply row highlighting (same priority system as dokumen legal)
            function applyRowHighlighting() {
                // Reset all highlighting
                $('#dataKontrakTable tbody tr').removeClass('highlight-red highlight-yellow highlight-orange highlight-gray');

                $('#dataKontrakTable tbody tr').each(function() {
                    const row = $(this);

                    // 1. Check contract status first (highest priority) - STS SR column (index 22)
                    const statusText = row.find('td:eq(22)').text().trim();
                    if (statusText.includes("NON-AKTIF") || statusText.includes("EXPIRED") || statusText.includes("PENDING")) {
                        row.addClass('highlight-gray');
                        return true; // continue to next iteration
                    }

                    // 2. Check if expired based on TglAkhirKtr (index 17)
                    const tglAkhir = row.find('td:eq(17)').text().trim();
                    if (tglAkhir !== '-') {
                        const akhirDate = moment(tglAkhir, 'DD-MM-YYYY');
                        const today = moment().startOf('day');

                        if (akhirDate.isBefore(today)) {
                            row.addClass('highlight-red');
                            return true; // Stop processing this row
                        }
                    }

                    // 3. Check TglPengingat for warning/expired status (main logic)
                    const tglPengingatStr = row.data('tgl-pengingat-kontrak');
                    if (tglPengingatStr) {
                        const tglPengingat = moment(tglPengingatStr);
                        const today = moment().startOf('day');
                        const diffDays = tglPengingat.diff(today, 'days');

                        if (diffDays <= 0) {
                            // Already expired or today
                            row.addClass('highlight-red');
                            return true;
                        } else if (diffDays <= 7) {
                            // Urgent warning: within 7 days
                            row.addClass('highlight-yellow');
                            return true;
                        } else if (diffDays <= 30) {
                            // Warning: within 30 days
                            row.addClass('highlight-orange');
                            return true;
                        }
                    }

                    // 4. Check TglAkhirKtr for warning (within 30 days) - fallback
                    if (tglAkhir !== '-') {
                        const akhirDate = moment(tglAkhir, 'DD-MM-YYYY');
                        const today = moment().startOf('day');
                        const diffDays = akhirDate.diff(today, 'days');

                        if (diffDays > 0 && diffDays <= 30) {
                            row.addClass('highlight-yellow');
                        }
                    }
                });
            }

            // Function to update warning text in PERINGATAN column
            function updatePeringatanText() {
                const today = moment().startOf('day');

                $('#dataKontrakTable tbody tr').each(function() {
                    const tglPengingatStr = $(this).data('tgl-pengingat-kontrak');
                    const $peringatanCol = $(this).find('.sisa-peringatan-col');

                    // If no reminder date, skip this row
                    if (!tglPengingatStr) {
                        return true;
                    }

                    // Parse reminder date
                    const tglPengingat = moment(tglPengingatStr);

                    // Calculate difference in days
                    const diffDays = tglPengingat.diff(today, 'days');

                    // Determine text and badge class to display in warning column
                    let peringatanText = '';
                    let badgeClass = 'bg-secondary';

                    if (diffDays < 0) {
                        // Reminder date has passed
                        peringatanText = 'Terlambat ' + Math.abs(diffDays) + ' hari';
                        badgeClass = 'bg-danger';
                    } else if (diffDays === 0) {
                        // Reminder date is today
                        peringatanText = 'Hari ini';
                        badgeClass = 'bg-danger';
                    } else if (diffDays <= 7) {
                        // Urgent: within 7 days
                        peringatanText = diffDays + ' hari lagi';
                        badgeClass = 'bg-warning text-dark';
                    } else if (diffDays <= 30) {
                        // Warning: within 30 days
                        peringatanText = diffDays + ' hari lagi';
                        badgeClass = 'bg-info';
                    } else {
                        // Safe: more than 30 days
                        peringatanText = diffDays + ' hari lagi';
                        badgeClass = 'bg-success';
                    }

                    // Update warning text with proper badge
                    $peringatanCol.html('<span class="badge ' + badgeClass + '">' + peringatanText + '</span>');
                });
            }

            // Function to get row priority for sorting (same as dokumen legal)
            function getRowPriority(row) {
                const statusText = $(row).find('td:eq(22)').text().trim(); // STS SR column at index 22
                const tglAkhir = $(row).find('td:eq(17)').text().trim();
                const tglPengingatStr = $(row).data('tgl-pengingat-kontrak');

                // Priority 5 (lowest): Inactive contracts (gray)
                if (statusText.includes("NON-AKTIF") || statusText.includes("EXPIRED") || statusText.includes("PENDING")) {
                    return 5;
                }

                // Check if contract is expired based on TglAkhirKtr
                if (tglAkhir !== '-') {
                    const akhirDate = moment(tglAkhir, 'DD-MM-YYYY');
                    const today = moment().startOf('day');

                    if (akhirDate.isBefore(today)) {
                        return 1; // Priority 1: Expired contracts (red)
                    }
                }

                // Check TglPengingat for warning/expired status
                if (tglPengingatStr) {
                    const tglPengingat = moment(tglPengingatStr);
                    const today = moment().startOf('day');
                    const diffDays = tglPengingat.diff(today, 'days');

                    if (diffDays <= 0) {
                        return 1; // Priority 1: Already expired or today (red)
                    } else if (diffDays <= 7) {
                        return 2; // Priority 2: Urgent warning within 7 days (yellow)
                    } else if (diffDays <= 30) {
                        return 3; // Priority 3: Warning within 30 days (orange)
                    }
                }

                // Check TglAkhirKtr for warning (within 30 days)
                if (tglAkhir !== '-') {
                    const akhirDate = moment(tglAkhir, 'DD-MM-YYYY');
                    const today = moment().startOf('day');
                    const diffDays = akhirDate.diff(today, 'days');

                    if (diffDays > 0 && diffDays <= 30) {
                        return 2; // Priority 2: Warning within 30 days (yellow)
                    }
                }

                return 4; // Priority 4: Normal contracts (no highlight)
            }

            // ADD CUSTOM SORTING PLUGIN TO DATATABLES
            $.fn.dataTable.ext.order['dom-priority'] = function(settings, col) {
                return this.api().column(col, {
                    order: 'index'
                }).nodes().map(function(td, i) {
                    return getRowPriority($(td).closest('tr'));
                });
            };

            // Initialize DataTable with priority-based sorting
            var table = $('#dataKontrakTable').DataTable({
                responsive: true,
                destroy: true, // Add destroy option
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
                    // Add custom priority sorting to first column
                    targets: 0,
                    orderDataType: 'dom-priority'
                }, {
                    // Non-sortable columns
                    orderable: false,
                    targets: [24] // Aksi column (index 24, 0-based counting: 0-24 = 25 columns total)
                }, {
                    // Make AKSI column never collapse/hide (highest responsive priority)
                    responsivePriority: 1,
                    targets: [24] // AKSI column must always be visible
                }, {
                    // Important columns that should stay visible as much as possible
                    responsivePriority: 2,
                    targets: [0, 1, 2] // NO, NRK, NAMA
                }, {
                    // Status columns - important to see
                    responsivePriority: 3,
                    targets: [22, 15, 20] // STS SR, STS KTR, PERINGATAN
                }, {
                    // Date columns - medium priority
                    responsivePriority: 4,
                    targets: [16, 17, 19, 23] // TGL AW KTR, TGL AK KTR, TGL PER, TGL SR NA
                }, {
                    // Other columns can be hidden first
                    responsivePriority: 5,
                    targets: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 18, 21] // UMUR, SEX, FOTO, etc.
                }],
                // Change default ordering to use our custom priority
                order: [[0, 'asc']], // Sort by priority first
                drawCallback: function() {
                    // Update row numbers on each redraw
                    this.api().column(0, {
                        page: 'current'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });

                    // Apply highlighting and update text for visible rows
                    applyRowHighlighting();
                    updatePeringatanText();
                },
                initComplete: function() {
                    // Apply initial highlighting and text updates
                    applyRowHighlighting();
                    updatePeringatanText();

                    // Calculate stats from ALL data
                    setTimeout(function() {
                        calculateContractStats();
                    }, 500);
                }
            });

            // Filter functionality
            $('#filterButton').on('click', function() {
                $('#filterModal').modal('show');
            });

            $('#applyFilter').on('click', function() {
                $('#filterForm').submit();
            });

            $('#resetFilter').on('click', function() {
                $('#filterForm')[0].reset();
                window.location.href = "{{ route('data-kontrak.index') }}";
            });

            // Export functionality
            $('#exportButton').on('click', function() {
                $('#exportModal').modal('show');
            });

            // Summary functionality
            $('#summaryButton').on('click', function() {
                $('#summaryModal').modal('show');
            });

            // Delete functionality
            $('.delete-confirm').on('click', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                $('#contractName').text(name);
                $('#deleteForm').attr('action', '{{ route('data-kontrak.destroy', ':id') }}'.replace(':id', id));
                $('#deleteConfirmationModal').modal('show');
            });

            // Event listener for DataTables events to update row styling
            table.on('draw.dt', function() {
                applyRowHighlighting();
                updatePeringatanText();
                calculateContractStats();
            });

            // Add flash effect when row hovered
            $('#dataKontrakTable tbody').on('mouseenter', 'tr', function() {
                $(this).addClass('row-hover-active');
            }).on('mouseleave', 'tr', function() {
                $(this).removeClass('row-hover-active');
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 5000);

            // Debug function to check data attributes
            console.log('Debugging contract data attributes:');
            $('#dataKontrakTable tbody tr').each(function(index) {
                const tglPengingatStr = $(this).data('tgl-pengingat-kontrak');
                const statusText = $(this).find('td:eq(22)').text().trim();
                console.log('Row ' + index + ':', {
                    'tgl-pengingat': tglPengingatStr,
                    'status': statusText
                });
            });
        });
    </script>
@endpush