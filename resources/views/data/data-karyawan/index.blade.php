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
                        <!-- Active Filter Display -->
                        @if (
                            !empty($currentFilters['status']) ||
                                !empty($currentFilters['perusahaan']) ||
                                !empty($currentFilters['nama']) ||
                                !empty($currentFilters['nrk']) ||
                                !empty($currentFilters['departemen']) ||
                                !empty($currentFilters['jabatan']) ||
                                !empty($currentFilters['kontrak']) ||
                                !empty($currentFilters['jenis_kelamin']) ||
                                !empty($currentFilters['wilker']) ||
                                !empty($currentFilters['unit_kerja']))
                            <div class="alert alert-info" role="alert" id="filterActiveAlert">
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

                                <a href="{{ route('data-karyawan.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Reset Filter
                                </a>
                            </div>
                        @endif

                        <!-- Quick Search Bar -->
                        {{-- <div class="row mb-3">
                            <div class="col-md-6">
                                <form method="GET" action="{{ route('data-karyawan.index') }}" id="quickSearchForm">
                                    <!-- Preserve other filters -->
                                    @foreach ($currentFilters as $key => $value)
                                        @if ($key !== 'nama' && !empty($value))
                                            <input type="hidden" name="filter_{{ $key }}" value="{{ $value }}">
                                        @endif
                                    @endforeach

                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search text-primary"></i>
                                        </span>
                                        <input type="text"
                                               class="form-control"
                                               name="filter_nama"
                                               id="quickSearchInput"
                                               placeholder="Cari nama karyawan..."
                                               value="{{ $currentFilters['nama'] ?? '' }}"
                                               autocomplete="off">
                                        @if (!empty($currentFilters['nama']))
                                            <button type="button" class="btn btn-outline-secondary" id="clearSearch" title="Hapus pencarian">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i>Cari
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Menampilkan {{ $dataKaryawans->count() }} dari total karyawan
                                </small>
                            </div>
                        </div> --}}

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
                                        <th width="7%" class="text-center">SKTWK</th>
                                        <th width="7%" class="text-center">SKTAK</th>
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

                                            // Gunakan unit_krj (ID) bukan wilker (string)
                                            $sktwk = $karyawan->unitKerjaRelation
                                                ? $karyawan->unitKerjaRelation->skt_wilker
                                                : '-';
                                            $sktak = $karyawan->unitKerjaRelation
                                                ? $karyawan->unitKerjaRelation->singkatan_wk
                                                : '-';

                                        @endphp
                                        <tr>
                                            <!-- Kolom Prioritas -->
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $karyawan->nrk ?? '-' }}
                                                <span class="text-muted"> {{ $karyawan->nik ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @if (!empty($currentFilters['nama']))
                                                    {!! str_ireplace($currentFilters['nama'], '<mark>' . $currentFilters['nama'] . '</mark>', $karyawan->nama) !!}
                                                @else
                                                    {{ $karyawan->nama }}
                                                @endif
                                            </td>
                                            <td>{{ $karyawan->tpt_lahir ?? '-' }}</td>
                                            <td>{{ $karyawan->tgl_lahir ? date('d-m-Y', strtotime($karyawan->tgl_lahir)) : '-' }}
                                            </td>
                                            <td>{{ $age }} {{ is_numeric($age) ? 'thn' : '' }}</td>
                                            <td class="text-center">
                                                @if ($karyawan->sex == 'LAKI-LAKI')
                                                    <span>L</span>
                                                @elseif($karyawan->sex == 'PEREMPUAN')
                                                    <span>P</span>
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
                                            <td class="text-center">{{ $kontrak }}
                                            </td>
                                            <td>{{ $karyawan->tgl_akhir_ktr ? date('d-m-Y', strtotime($karyawan->tgl_akhir_ktr)) : '-' }}
                                            </td>
                                            <td><small>{{ Str::limit($departemen, 15) }}</small></td>
                                            <td><small>{{ Str::limit($jabatan, 15) }}</small></td>
                                            {{-- SKTWK --}}
                                            <td><small>{{ $sktwk ?? '-' }}</small></td>

                                            {{-- SKTAK --}}
                                            <td><small>{{ $sktak ?? '-' }}</small></td>
                                            <td class="text-center">
                                                @if ($karyawan->sts_kry == 'CALON')
                                                    <span>CALON</span>
                                                @elseif ($karyawan->sts_kry == 'AKTIF')
                                                    <span>AK
                                                    </span>
                                                @elseif ($karyawan->sts_kry == 'NON-AKTIF')
                                                    <span class="badge bg-danger">NA
                                                    </span>
                                                @else
                                                    <span>{{ $karyawan->sts_kry }}</span>
                                                @endif
                                            </td>
                                            <!-- Kolom Tambahan -->
                                            <td>{{ $karyawan->tgl_phk ? date('d-m-Y', strtotime($karyawan->tgl_phk)) : '-' }}
                                            </td>
                                            <td>{{ $workDuration }}</td>
                                            <td> {{ $karyawan->creator ? $karyawan->creator->nama_kry : '-' }}
                                                <span
                                                    style="font-size: 11px">{{ $karyawan->created_at ? $karyawan->created_at->format('d/m/y H:i') : '-' }}</span>
                                            </td>
                                            <td> {{ $karyawan->updater ? $karyawan->updater->nama_kry : '-' }}
                                                <span
                                                    style="font-size: 11px">{{ $karyawan->updated_at ? $karyawan->updated_at->format('d/m/y H:i') : '-' }}</span>
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
                            {{-- 1 --}}
                            {{-- Status Karyawan --}}
                            <div class="col-md-6">
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
                            {{-- 6 --}}
                            {{-- Jabatan --}}
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

                            {{-- 2 --}}
                            {{-- {Perusahaan} --}}
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
                            {{-- 7 --}}
                            {{-- Jenis Kontrak --}}
                            <div class="col-md-6 mt-2">
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

                            {{-- 3 --}}
                            {{-- Wilker --}}
                            <div class="col-md-6 mt-2">
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
                            {{-- 8 --}}
                            {{-- Jenis Kelamin --}}
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

                            {{-- 4 --}}
                            {{-- Unit Kerja --}}
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_unit_kerja" class="form-label fw-bold">Unit Kerja</label>
                                    <div style="flex: 1">
                                        <select class="form-select select2" id="filter_unit_kerja"
                                            name="filter_unit_kerja">
                                            <option value="">Semua Unit Kerja</option>
                                            @foreach ($unitKerjaOptions as $unitKerja)
                                                <option value="{{ $unitKerja->id }}"
                                                    {{ $currentFilters['unit_kerja'] == $unitKerja->id ? 'selected' : '' }}>
                                                    {{ $unitKerja->area_krj }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- 9 --}}
                            {{-- Nama Karyawan --}}
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_nama" class="form-label fw-bold">Nama Karyawan</label>
                                    <input type="text" class="form-control" id="filter_nama" name="filter_nama"
                                        placeholder="Cari nama karyawan..." value="{{ $currentFilters['nama'] ?? '' }}">
                                </div>
                            </div>

                            {{-- 5 --}}
                            {{-- Departemen --}}
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
                            {{-- 10 --}}
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_nrk" class="form-label fw-bold">NRK</label>
                                    <input type="text" class="form-control" id="filter_nrk" name="filter_nrk"
                                        placeholder="Cari NRK karyawan..." value="{{ $currentFilters['nrk'] ?? '' }}">
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
                                                        PT.
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
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        /* ===== CARD STYLING ===== */
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

        /* ===== TABLE STYLING ===== */
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

        /* ===== EMPLOYEE PHOTO STYLING ===== */
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

        #filterActiveAlert {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* ===== ALERT STYLING ===== */
        .alert {
            border: none;
            border-radius: 0.5rem;
        }

        .alert .badge {
            margin: 0.2rem;
        }

        /* ===== SELECT2 CUSTOM STYLING ===== */
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--bootstrap-5 .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: #495057;
            line-height: 26px;
            padding-left: 0;
            padding-right: 20px;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: 36px;
            right: 10px;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            z-index: 1070 !important;
            /* PENTING: Lebih tinggi dari modal */
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
            background-color: #0d6efd;
            color: white;
        }

        .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 6px 12px;
        }

        /* Select2 in modal specific styling - PENTING */
        .modal .select2-container {
            z-index: 1070 !important;
        }

        .modal .select2-dropdown {
            z-index: 1071 !important;
        }

        .select2-container--open .select2-dropdown {
            z-index: 1071 !important;
        }

        /* Input group with select2 */
        .input-group .select2-container {
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
        }

        .input-group .select2-container .select2-selection {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-left: 0;
        }

        .input-group .select2-container--focus .select2-selection {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* ===== RESPONSIVE TABLE ===== */
        .table-responsive {
            overflow-x: auto;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.75rem;
            }

            .employee-photo,
            .employee-photo-placeholder {
                width: 28px !important;
                height: 28px !important;
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // ===== INISIALISASI SELECT2 UNTUK FILTER MODAL =====
            function initializeSelect2InModal() {
                $('#filterModal .select2').each(function() {
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
                            noResults: function() {
                                return "Tidak ada hasil ditemukan";
                            },
                            searching: function() {
                                return "Mencari...";
                            },
                            inputTooShort: function() {
                                return "Ketik untuk mencari...";
                            }
                        }
                    });
                });
            }

            // Initialize Select2 when modal is opened
            $('#filterModal').on('shown.bs.modal', function() {
                initializeSelect2InModal();
            });

            // Cleanup when modal is closed
            $('#filterModal').on('hidden.bs.modal', function() {
                $('#filterModal .select2').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });
            });

            // ===== FILTER BUTTON =====
            $('#filterButton').click(function() {
                $('#filterModal').modal('show');
            });

            // ===== APPLY FILTER =====
            $('#applyFilter').click(function() {
                $('#filterForm').submit();
            });

            // ===== RESET FILTER =====
            $('#resetFilter').click(function() {
                // Reset all form inputs
                $('#filter_nama').val('');
                $('#filter_status').val('').trigger('change');
                $('#filter_perusahaan').val('').trigger('change');
                $('#filter_departemen').val('').trigger('change');
                $('#filter_jabatan').val('').trigger('change');
                $('#filter_kontrak').val('').trigger('change');
                $('#filter_jenis_kelamin').val('').trigger('change');
                $('#filter_skt_wilker').val('').trigger('change');
                $('#filter_wilker').val('').trigger('change');
                $('#filter_unit_kerja').val('').trigger('change');
                $('#filter_nrk').val('');

                // Reinitialize Select2 after reset
                initializeSelect2InModal();
            });

            // ===== SUMMARY BUTTON =====
            $('#summaryButton').click(function() {
                $('#summaryModal').modal('show');
            });

            // ===== EXPORT BUTTON =====
            $('#exportButton').click(function() {
                $('#exportModal').modal('show');
            });

            // ===== EXPORT HANDLERS =====
            $('#exportExcel').click(function() {
                let currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('export', 'excel');
                window.location.href = currentUrl.toString();
            });

            $('#exportPDF').click(function() {
                let currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('export', 'pdf');
                window.location.href = currentUrl.toString();
            });

            $('#exportCSV').click(function() {
                let currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('export', 'csv');
                window.location.href = currentUrl.toString();
            });

            $(document).on('click', '.delete-confirm', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                // Using Laravel route helper with placeholder
                var deleteUrl = "{{ route('data-karyawan.destroy', ':id') }}".replace(':id', id);
                $('#deleteForm').attr('action', deleteUrl);

                $('#deleteConfirmationModal').modal('show');
            });

            // ===== DELETE CONFIRMATION =====
            // $('.delete-confirm').click(function() {
            //     const employeeId = $(this).data('id');
            //     const employeeName = $(this).data('name');

            //     $('#employeeName').text(employeeName);
            //     $('#deleteForm').attr('action', `/data-karyawan/${employeeId}`);
            //     $('#deleteConfirmationModal').modal('show');
            // });

            // ===== QUICK SEARCH FUNCTIONALITY =====
            $('#quickSearchInput').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    $('#quickSearchForm').submit();
                }
            });

            // ===== CLEAR SEARCH =====
            $('#clearSearch').click(function() {
                $('#quickSearchInput').val('');
                let form = $('#quickSearchForm');
                form.find('input[name="filter_nama"]').val('');
                form.submit();
            });

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

            // ===== LOADING STATE FOR SEARCH =====
            $('#quickSearchForm').on('submit', function() {
                let submitBtn = $(this).find('button[type="submit"]');
                submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Mencari...');
                submitBtn.prop('disabled', true);
            });

            // ===== KEYBOARD SHORTCUTS =====
            $(document).keydown(function(e) {
                // Ctrl/Cmd + F for quick search
                if ((e.ctrlKey || e.metaKey) && e.keyCode === 70) {
                    e.preventDefault();
                    $('#quickSearchInput').focus();
                }

                // Ctrl/Cmd + K for filter modal
                if ((e.ctrlKey || e.metaKey) && e.keyCode === 75) {
                    e.preventDefault();
                    $('#filterModal').modal('show');
                }
            });

            // ===== TOOLTIP INITIALIZATION =====
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // ===== AUTO DISMISS ALERTS =====
            setTimeout(function() {
                $('.alert-success, .alert-danger').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>
@endpush
