@extends('layouts.app')

@section('title', 'Data Karyawan')

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
                                <i class="fas fa-exclamation-circle me-1"></i> Kontrak Expired :
                                <span id="expiredContractsCount">{{ $expiredContractsCount }}</span>
                            </span>
                            <span id="warningContractsBadge" class="badge text-dark me-2"
                                style="font-size: 0.9rem; background-color:#ffff66">
                                <i class="fas fa-exclamation-triangle me-1"></i>Kontrak Akan Expired :
                                <span id="warningContractsCount">{{ $expiringContractsCount }}</span>
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
                                        <th width="1%" class="text-center">NO</th>
                                        <th width="3%" class="text-center">NRK</th>
                                        <th width="5%" class="text-center">NAMA</th>
                                        <th width="2%" class="text-center">JML</th>
                                        <th width="2%" class="text-center">UMR</th>
                                        <th width="1%" class="text-center">JK</th>
                                        <th width="2%" class="text-center">FOTO</th>
                                        <th width="3%" class="text-center">TGL MSK</th>
                                        <th width="2%" class="text-center">MKR</th>
                                        <th width="2%" class="text-center">SKL</th>
                                        <th width="2%" class="text-center">DEP</th>
                                        <th width="2%" class="text-center">JBT</th>
                                        <th width="2%" class="text-center">WLK</th>
                                        <th width="2%" class="text-center">PRS</th>
                                        <th width="4%" class="text-center">NO KTR</th>
                                        <th width="3%" class="text-center">TGL KTR</th>
                                        <th width="2%" class="text-center">STS</th>
                                        <th width="3%" class="text-center">TGL AW</th>
                                        <th width="3%" class="text-center">TGL AK</th>
                                        <th width="2%" class="text-center">DUR</th>
                                        <th width="3%" class="text-center">TGL PGT</th>
                                        <th width="1%" class="text-center">PERINGATAN</th>
                                        <th width="1%" class="text-center">DOK</th>
                                        <th width="2%" class="text-center">STS</th>
                                        <th width="3%" class="text-center">TGL NA</th>
                                        <th width="4%" class="text-center">CREATE</th>
                                        <th width="4%" class="text-center">UPDATE</th>
                                        <th width="3%" class="text-center no-wrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataKontraks as $kontrak)
                                        @php
                                            // Get related data
                                            $karyawan = $kontrak->karyawan;
                                            $kontrakType = $kontrak->kontrakKerja;
                                            $perusahaan = $kontrak->perusahaan;
                                            $departemen = $kontrak->departemen;
                                            $wilayah = $kontrak->wilayahKerja;

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
                                        <tr data-employee-id="{{ $kontrak->id_data_kry }}"
                                            data-contract-status="{{ $kontrak->sts_srt_ktr }}"
                                            data-tgl-peringatan="{{ $kontrak->tgl_pgt_ktr }}"
                                            data-tgl-akhir="{{ $kontrak->tgl_akhir_ktr }}"
                                            data-ktg-ktk="{{ $kontrak->ktg_ktk ?? '' }}">

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
                                            <td class="text-center">
                                                @php
                                                    $contractCount = $contractCounts[$kontrak->id_data_kry] ?? [
                                                        'total' => 0,
                                                        'active' => 0,
                                                        'non_active' => 0,
                                                    ];
                                                @endphp
                                                <div class="contract-count-display">
                                                    <!-- Total Contracts -->
                                                    <span class="badge bg-primary" title="Total Kontrak">
                                                        <i
                                                            class="fas fa-file-contract me-1"></i>{{ $contractCount['total'] }}
                                                    </span>

                                                    {{-- <!-- Active Contracts -->
                                                    @if ($contractCount['active'] > 0)
                                                        <br><small class="text-success" title="Kontrak Aktif">
                                                            <i class="fas fa-check-circle"></i>
                                                            {{ $contractCount['active'] }}
                                                        </small>
                                                    @endif

                                                    <!-- Non-Active Contracts -->
                                                    @if ($contractCount['non_active'] > 0)
                                                        <small class="text-muted ms-1" title="Kontrak Non-Aktif">
                                                            <i class="fas fa-pause-circle"></i>
                                                            {{ $contractCount['non_active'] }}
                                                        </small>
                                                    @endif --}}
                                                </div>
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

                                            <!-- TGL MASUK -->
                                            <td class="text-center">
                                                {{ $karyawan && $karyawan->tgl_masuk ? $karyawan->tgl_masuk->format('d-m-Y') : '-' }}
                                            </td>

                                            <!-- MASA KERJA -->
                                            <td class="text-center">
                                                @if ($workDuration)
                                                    <span>{{ $workDuration }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- PENDIDIKAN -->
                                            <td class="text-center">
                                                <span>{{ $kontrak->jenjang_skl ?? '-' }}</span>
                                            </td>

                                            <!-- DEPARTEMEN -->
                                            <td class="text-center">
                                                @if ($departemen)
                                                    <span>{{ $departemen->singkatan_dep }}</span>
                                                @elseif($karyawan && $karyawan->departemenRelation)
                                                    <span>{{ $karyawan->departemenRelation->singkatan_dep }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- JABATAN -->
                                            <td class="text-center">
                                                <span>{{ $departemen->singkatan_jbt ?? '-' }}</span>
                                            </td>

                                            <!-- WILKER -->
                                            <td class="text-center">
                                                <span>{{ $wilayah->singkatan_wk ?? '-' }}</span>
                                            </td>

                                            <!-- PERUSAHAAN -->
                                            <td class="text-center">
                                                <span>{{ $perusahaan->nama_prs2 ?? '-' }}</span>
                                            </td>

                                            <!-- NO KONTRAK -->
                                            <td class="text-center">
                                                <small>{{ $kontrak->no_srt_ktr ?? '-' }}</small>
                                            </td>

                                            <!-- TGL KONTRAK -->
                                            <td class="text-center">
                                                {{ $kontrak->tgl_srt_ktr ? date('d-m-Y', strtotime($kontrak->tgl_srt_ktr)) : '-' }}
                                            </td>

                                            <!-- STATUS KONTRAK -->
                                            <td class="text-center">
                                                {{ $kontrakType->singkatan_ktr ?? '-' }}
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
                                                    <span>{{ $kontrak->durasi_ktr }} bln</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- TGL PGT (Tanggal Pengingat) -->
                                            <td class="text-center">
                                                {{ $kontrak->tgl_pgt_ktr ? date('d-m-Y', strtotime($kontrak->tgl_pgt_ktr)) : '-' }}
                                            </td>

                                            <!-- PERINGATAN (Will be calculated by JS) -->
                                            <td class="text-center sisa-peringatan-col">
                                                <span>Loading...</span>
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
                                                {{ $kontrak->sts_srt_ktr }}
                                            </td>

                                            <!-- TGL SR NA (Tanggal Surat Non Aktif) -->
                                            <td class="text-center">
                                                {{ $kontrak->tgl_sr_na ? date('d-m-Y', strtotime($kontrak->tgl_sr_na)) : '-' }}
                                            </td>

                                            <td>{{ $kontrak->creator ? $kontrak->creator->nama_kry : '-' }}
                                                <span
                                                    style="font-size: 11px">{{ $kontrak->created_at ? $kontrak->created_at->format('d/m/y H:i') : '-' }}</span>
                                            </td>
                                            <td>{{ $kontrak->updater ? $kontrak->updater->nama_kry : '-' }}
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

    <!-- Filter Modal -->
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="filter_nama" class="form-label fw-bold">Nama Karyawan</label>
                                    <input type="text" class="form-control" id="filter_nama" name="filter_nama"
                                        placeholder="Cari nama karyawan..." value="{{ $currentFilters['nama'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_jenis_kelamin" class="form-label fw-bold">Jenis Kelamin</label>
                                    <div style="flex: 1">
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
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_status" class="form-label fw-bold">Status Karyawan</label>
                                    <div style="flex: 1">
                                        <select class="form-select select2" id="filter_status" name="filter_status">
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
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_perusahaan" class="form-label fw-bold">Perusahaan</label>
                                    <div style="flex: 1">
                                        <select class="form-select select2" id="filter_perusahaan"
                                            name="filter_perusahaan">
                                            <option value="">Semua Perusahaan</option>
                                            @foreach ($perusahaans as $perusahaan)
                                                <option value="{{ $perusahaan->id }}"
                                                    {{ $currentFilters['perusahaan'] == $perusahaan->id ? 'selected' : '' }}>
                                                    {{ $perusahaan->nama_prs1 }} - {{ $perusahaan->nama_prs2 }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_departemen" class="form-label fw-bold">Departemen</label>
                                    <div style="flex: 1">
                                        <select class="form-select select2" id="filter_departemen"
                                            name="filter_departemen">
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
                            </div>
                        </div>

                        <!-- Row 2: Additional Filters -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_jabatan" class="form-label fw-bold">Jabatan</label>
                                    <div style="flex: 1">
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
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_wilker" class="form-label fw-bold">Wilayah Kerja</label>
                                    <div style="flex: 1">
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
                            </div>
                        </div>

                        <!-- Row 3: Contract Filter -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_kontrak" class="form-label fw-bold">Jenis Kontrak</label>
                                    <div style="flex: 1">
                                        <select class="form-select select2" id="filter_kontrak" name="filter_kontrak">
                                            <option value="">Semua Kontrak</option>
                                            @foreach ($kontrakOptions as $kontrak)
                                                <option value="{{ $kontrak->id }}"
                                                    {{ $currentFilters['kontrak'] == $kontrak->id ? 'selected' : '' }}>
                                                    {{ $kontrak->nama_ktr }} @if ($kontrak->singkatan_ktr)
                                                        ({{ $kontrak->singkatan_ktr }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
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
                    <h5 class="modal-title">Konfirmasi Hapus Semua Kontrak</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan!</strong> Tindakan ini akan menghapus <strong>SEMUA</strong> kontrak karyawan.
                    </div>
                    <p>Apakah Anda yakin ingin menghapus <strong>semua data kontrak</strong> untuk karyawan <strong
                            id="employeeName"></strong>?</p>
                    <p class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Ini akan menghapus seluruh riwayat kontrak, pendidikan, dan karir karyawan tersebut.
                        Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Hapus Semua Kontrak
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Modal -->
    <div class="modal fade" id="summaryModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
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
                                                    <p class="mb-0 fw-bold text-truncate"
                                                        title="{{ $perusahaan->nama_prs2 }}" style="max-width: 150px;">
                                                        PT. {{ Str::limit($perusahaan->nama_prs2, 20) }}
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
                                $groupedDepartemen = $departemens->groupBy('singkatan_dep');
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
                                                        @if ($gender == 'LAKI-LAKI')
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
                        <h5 class="fw-bold mb-3"><i class="fas fa-file-contract me-2 text-secondary"></i>Berdasarkan Jenis
                            Kontrak
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
                                                    <p class="mb-0 fw-bold text-truncate"
                                                        title="{{ $kontrak->nama_ktr }}" style="max-width: 150px;">
                                                        {{ Str::limit($kontrak->singkatan_ktr, 20) }}
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
                    {{-- <div class="mb-4">
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
                    </div> --}}
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
        /* ===== CARD STYLING ===== */
        .dataKontrakPage .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

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

        /* ===== BADGE STYLING ===== */
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

        .badge.bg-pink {
            background-color: #e91e63 !important;
            color: white !important;
        }

        /* ===== HIGHLIGHT ROWS STYLING ===== */
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
            background-color: #33ff33 !important;
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

        /* Responsive design */
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            console.log('Contract system initializing...');

            // Destroy existing DataTable if it exists
            if ($.fn.DataTable.isDataTable('#dataKontrakTable')) {
                $('#dataKontrakTable').DataTable().destroy();
            }

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // ===== IMAGE PREVIEW ON CLICK (COPIED FROM EMPLOYEE PAGE) =====
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

            // Function to calculate warning text and priority from row data directly
            function calculateRowWarning(row) {
                const tglPeringatan = row.data('tgl-peringatan');
                const contractStatus = row.data('contract-status');
                const tglAkhir = row.data('tgl-akhir');
                const ktgKtk = row.data('ktg-ktk');

                console.log('Calculating for row:', {
                    'tgl-peringatan': tglPeringatan,
                    'contract-status': contractStatus,
                    'tgl-akhir': tglAkhir,
                    'ktg-ktk': ktgKtk
                });

                // Skip non-active contracts
                if (contractStatus === 'NON-AKTIF') {
                    return {
                        text: 'NON-AKTIF',
                        badgeClass: 'bg-secondary',
                        priority: 10, // ← ubah dari 6 ke 10 agar selalu paling bawah
                        status: 'non_active'
                    };
                }

                // If contract is AKTIF but has no reminder and no end date (typically TETAP)
                if (contractStatus === 'AKTIF' && (!tglPeringatan || tglPeringatan === '') && (!tglAkhir ||
                        tglAkhir === '')) {
                    return {
                        text: 'Kontrak Tetap',
                        badgeClass: 'bg-secondary',
                        priority: 4,
                        status: 'tetap_aktif' // sudah benar, tidak ada highlight
                    };
                }

                // If no reminder date for TIDAK TETAP contracts, colored gray
                if (!tglPeringatan || tglPeringatan === '') {
                    return {
                        text: 'Tidak Ada Pengingat',
                        badgeClass: 'bg-secondary',
                        priority: 5,
                        status: 'no_reminder'
                    };
                }

                // Calculate days difference using moment
                const today = moment().startOf('day');
                const reminderDate = moment(tglPeringatan);
                const diffDays = reminderDate.diff(today, 'days');

                console.log('Date calculation:', {
                    today: today.format('YYYY-MM-DD'),
                    reminderDate: reminderDate.format('YYYY-MM-DD'),
                    diffDays: diffDays
                });

                let result = {
                    text: '',
                    badgeClass: '',
                    priority: 4,
                    status: 'normal'
                };

                if (diffDays < 0) {
                    // Expired - reminder date has passed
                    result.text = 'Terlambat ' + Math.abs(diffDays) + ' hari';
                    result.badgeClass = 'bg-danger';
                    result.priority = 1;
                    result.status = 'expired';
                } else if (diffDays === 0) {
                    // Today
                    result.text = 'Hari Ini';
                    result.badgeClass = 'bg-danger';
                    result.priority = 1;
                    result.status = 'expired';
                } else if (diffDays <= 7) {
                    // Urgent - within 7 days
                    result.text = diffDays + ' hari lagi';
                    result.badgeClass = 'bg-warning text-dark';
                    result.priority = 2;
                    result.status = 'urgent';
                } else if (diffDays <= 30) {
                    // Warning - within 30 days
                    result.text = diffDays + ' hari lagi';
                    result.badgeClass = 'bg-info';
                    result.priority = 3;
                    result.status = 'warning';
                } else {
                    // Safe - more than 30 days
                    result.text = diffDays + ' hari lagi';
                    result.badgeClass = 'bg-success';
                    result.priority = 4;
                    result.status = 'safe';
                }

                console.log('Warning result:', result);
                return result;
            }

            // Function to apply row highlighting and update warning text
            function applyRowProcessing() {
                console.log('Applying row processing...');

                let expiredCount = 0;
                let warningCount = 0;

                // Reset all highlighting
                $('#dataKontrakTable tbody tr').removeClass(
                    'highlight-red highlight-yellow highlight-orange highlight-gray');

                $('#dataKontrakTable tbody tr').each(function() {
                    const row = $(this);
                    const warningData = calculateRowWarning(row);

                    const peringatanCol = row.find('.sisa-peringatan-col');
                    peringatanCol.html('<span>' + warningData.text + '</span>');

                    // Apply row highlighting
                    switch (warningData.status) {
                        case 'expired':
                            row.addClass('highlight-red');
                            expiredCount++;
                            break;
                        case 'urgent':
                            row.addClass('highlight-yellow');
                            warningCount++;
                            break;
                        case 'warning':
                            row.addClass('highlight-orange');
                            warningCount++;
                            break;
                        case 'safe':
                            // No highlighting for safe status
                            break;
                        case 'tetap_aktif':
                            // No highlighting for TETAP AKTIF contracts
                            break;
                        case 'non_active':
                            row.addClass('highlight-gray');
                            break;
                        case 'no_reminder':
                            const ktgKtk = row.data('ktg-ktk');
                            if (ktgKtk !== 'TETAP') {
                                row.addClass('highlight-gray');
                            }
                            // Jika TETAP, biarkan putih (tidak tambahkan class apapun)
                            break;
                        default:
                            // Inactive contracts - gray
                            row.addClass('highlight-gray');
                            break;
                    }

                    // Store priority for sorting
                    row.data('priority', warningData.priority);
                });



                console.log('Statistics updated - Expired:', expiredCount, 'Warning:', warningCount);
            }

            // Function to get row priority for sorting
            function getRowPriority(row) {
                return $(row).data('priority') || 5;
            }

            // ADD CUSTOM SORTING PLUGIN TO DATATABLES
            $.fn.dataTable.ext.order['dom-priority'] = function(settings, col) {
                return this.api().column(col, {
                    order: 'index'
                }).nodes().map(function(td, i) {
                    return getRowPriority($(td).closest('tr'));
                });
            };

            // Pre-calculate priority SEBELUM DataTable init
            $('#dataKontrakTable tbody tr').each(function() {
                const row = $(this);
                const warningData = calculateRowWarning(row);
                row.data('priority', warningData.priority);
            });

            // Initialize DataTable with priority-based sorting
            var table = $('#dataKontrakTable').DataTable({
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
                    // Add custom priority sorting to first column
                    targets: 0,
                    orderDataType: 'dom-priority'
                }, {
                    // Non-sortable columns
                    orderable: false,
                    targets: [27] // AKSI column
                }, {
                    // Make AKSI column never collapse/hide (highest responsive priority)
                    responsivePriority: 1,
                    targets: [27]
                }, {
                    // Important columns that should stay visible as much as possible
                    responsivePriority: 2,
                    targets: [0, 1, 2] // NO, NRK, NAMA
                }, {
                    // Status columns - important to see
                    responsivePriority: 3,
                    targets: [23, 16, 21] // STS SR, STS KTR, PERINGATAN
                }],
                order: [
                    [0, 'asc']
                ], // Sort by priority first
                drawCallback: function() {
                    // Update row numbers dengan offset halaman
                    var api = this.api();
                    var startIndex = api.page.info().start; // ← ambil offset halaman saat ini

                    api.column(0, {
                        page: 'current'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = startIndex + i + 1; // ← tambahkan offset
                    });

                    applyRowProcessing();
                },
                initComplete: function() {
                    console.log('DataTable initialized, applying initial processing...');

                    // Apply initial processing
                    applyRowProcessing();
                }
            });

            // Event handlers for modals and other functionality
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

            $('#exportButton').on('click', function() {
                $('#exportModal').modal('show');
            });

            $('#exportExcel').click(function() {
                // Get current filter values from form
                var formData = $('#filterForm').serialize();

                // Construct URL with export parameter and current filters
                var exportUrl = "{{ route('data-kontrak.index') }}?export=excel&" + formData;

                // Redirect to the URL
                window.location.href = exportUrl;
            });

            $('#summaryButton').on('click', function() {
                $('#summaryModal').modal('show');
            });

            $(document).on('click', '.delete-confirm', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                // Using Laravel route helper with placeholder
                var deleteUrl = "{{ route('data-kontrak.destroy', ':id') }}".replace(':id', id);
                $('#deleteForm').attr('action', deleteUrl);
                $('#employeeName').text(name);

                $('#deleteConfirmationModal').modal('show');
            });

            // Event listener for DataTables events to update row styling
            table.on('draw.dt', function() {
                applyRowProcessing();
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

            // Force initial calculation after everything is loaded
            setTimeout(function() {
                console.log('Force applying initial processing...');
                applyRowProcessing();
            }, 1000);

            console.log('Contract system initialization complete!');
        });
    </script>
@endpush
