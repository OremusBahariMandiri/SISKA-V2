@extends('layouts.app')

@section('title', 'Pelaporan Dokumen Karyawan')

@section('content')
    <div class="container-fluid dataDokumenPelaporanPage">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-invoice me-2"></i>Pelaporan Dokumen Karyawan</span>
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

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Active Filter Display -->
                        @if (
                            !empty($currentFilters['status']) ||
                                !empty($currentFilters['jenis_dokumen']) ||
                                !empty($currentFilters['nama']) ||
                                !empty($currentFilters['nrk']) ||
                                !empty($currentFilters['no_dokumen']) ||
                                !empty($currentFilters['departemen']) ||
                                !empty($currentFilters['jabatan']) ||
                                !empty($currentFilters['perusahaan']) ||
                                !empty($currentFilters['jenis_kelamin']) ||
                                !empty($currentFilters['wilker']) ||
                                !empty($currentFilters['unit_kerja']))
                            <div class="alert alert-info" role="alert" id="filterActiveAlert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Filter Aktif:</strong>

                                @if (!empty($currentFilters['status']))
                                    Status: <span class="badge bg-primary">{{ $currentFilters['status'] }}</span>
                                @endif

                                @if (!empty($currentFilters['jenis_dokumen']))
                                    @php
                                        $selectedDokumen = $dokumenTypes
                                            ->where('id', $currentFilters['jenis_dokumen'])
                                            ->first();
                                    @endphp
                                    @if ($selectedDokumen)
                                        Jenis Dokumen: <span
                                            class="badge bg-success">{{ $selectedDokumen->ktg_dok_kry ?? $selectedDokumen->jns_dok_kry }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['perusahaan']))
                                    @php
                                        $selectedPerusahaan = $perusahaans
                                            ->where('id', $currentFilters['perusahaan'])
                                            ->first();
                                    @endphp
                                    @if ($selectedPerusahaan)
                                        Perusahaan: <span
                                            class="badge bg-warning">{{ $selectedPerusahaan->nama_prs2 }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['nama']))
                                    Nama: <span class="badge bg-primary">{{ $currentFilters['nama'] }}</span>
                                @endif

                                @if (!empty($currentFilters['nrk']))
                                    NRK: <span class="badge bg-primary">{{ $currentFilters['nrk'] }}</span>
                                @endif

                                @if (!empty($currentFilters['no_dokumen']))
                                    No Dokumen: <span class="badge bg-info">{{ $currentFilters['no_dokumen'] }}</span>
                                @endif

                                <a href="{{ route('data-dokumen-laporan.index') }}"
                                    class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Reset Filter
                                </a>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="dataDokumenPelaporanTable"
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
                                        <th width="4%" class="text-center">NO DOK</th>
                                        <th width="3%" class="text-center">KET DOK</th>
                                        <th width="3%" class="text-center">JENIS DOK</th>
                                        <th width="4%" class="text-center">CATATAN</th>
                                        <th width="3%" class="text-center">TGL TERBIT</th>
                                        <th width="3%" class="text-center">MASA BERLAKU</th>
                                        <th width="3%" class="text-center">TGL AKHIR</th>
                                        <th width="2%" class="text-center">STATUS</th>
                                        <th width="3%" class="text-center no-wrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataDokumens as $dokumen)
                                        @php
                                            $karyawan = $dokumen->karyawan;
                                            $departemen = $karyawan ? $karyawan->departemenRelation : null;
                                            // Gunakan unitKerjaRelation seperti di Data Karyawan
                                            $unitKerja = $karyawan ? $karyawan->unitKerjaRelation : null;
                                            $wilayahKrj = $unitKerja ? $unitKerja->wilayah_krj : '-';
                                            $areaKrj = $unitKerja ? $unitKerja->area_krj : '-';
                                            $perusahaan = $karyawan ? $karyawan->perusahaanRelation : null;
                                            $dokumenType = $dokumen->dokumenType;

                                            // Calculate document status
                                            $today = now();
                                            $isExpired =
                                                $dokumen->tgl_akr_dok &&
                                                $dokumen->tgl_akr_dok < $today &&
                                                $dokumen->sts_dok == 'AKTIF';
                                            $isExpiring =
                                                $dokumen->tgl_pgt_dok &&
                                                $dokumen->tgl_pgt_dok <= $today &&
                                                $dokumen->tgl_akr_dok >= $today &&
                                                $dokumen->sts_dok == 'AKTIF';

                                            $rowClass = '';
                                            if ($isExpired) {
                                                $rowClass = 'table-danger';
                                            } elseif ($isExpiring) {
                                                $rowClass = 'table-warning';
                                            }

                                            // Determine masa berlaku
                                            $masaBerlaku = '-';
                                            if ($dokumen->tgl_akr_dok) {
                                                $masaBerlaku = 'Terbatas';
                                            } else {
                                                $masaBerlaku = 'Selamanya';
                                            }
                                        @endphp
                                        <tr class="{{ $rowClass }}">
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

                                            <!-- SEX (Jenis Kelamin) -->
                                            <td class="text-center">
                                                @if ($karyawan && $karyawan->sex)
                                                    <span>{{ $karyawan->sex == 'LAKI-LAKI' ? 'L' : 'P' }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- PERUSAHAAN -->
                                            <td class="text-center">
                                                <span>{{ $perusahaan->nama_prs2 ?? '-' }}</span>
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
                                                <span>{{ $departemen->singkatan_jbt ?? '-' }}</span>
                                            </td>

                                            <!-- WILAYAH KERJA -->
                                            <td class="text-center">
                                                <span>{{ $wilayahKrj }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span>{{ $areaKrj }}</span>
                                            </td>

                                            <!-- NO DOKUMEN -->
                                            <td>
                                                <span class="fw-bold">{{ $dokumen->no_dok ?? '-' }}</span>
                                            </td>

                                            <!-- KETERANGAN DOK (Kategori dari master) -->
                                            <td class="text-center">
                                                @if ($dokumenType)
                                                    <span>{{ $dokumenType->ktg_dok_kry ?? '-' }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- JENIS DOKUMEN (Singkatan/Jenis dari master) -->
                                            <td class="text-center">
                                                @if ($dokumenType)
                                                    <span>{{ $dokumenType->singkatan_dok ?? $dokumenType->jns_dok_kry }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- CATATAN -->
                                            <td>
                                                <small>{{ $dokumen->ket_dok ?? '-' }}</small>
                                            </td>

                                            <!-- TGL TERBIT -->
                                            <td class="text-center">
                                                @if ($dokumen->tgl_awal_dok)
                                                    {{ $dokumen->tgl_awal_dok->format('d-m-Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- JENIS MASA BERLAKU -->
                                            <td class="text-center">
                                                @if ($dokumen->tgl_akr_dok)
                                                    <span class="badge bg-warning text-dark">Terbatas</span>
                                                @else
                                                    <span class="badge bg-success">Selamanya</span>
                                                @endif
                                            </td>

                                            <!-- TGL AKHIR -->
                                            <td class="text-center">
                                                @if ($dokumen->tgl_akr_dok)
                                                    {{ $dokumen->tgl_akr_dok->format('d-m-Y') }}
                                                    @if ($isExpired)
                                                        <br><span class="badge bg-danger"><i
                                                                class="fas fa-times-circle"></i> EXPIRED</span>
                                                    @elseif ($isExpiring)
                                                        <br><span class="badge bg-warning text-dark"><i
                                                                class="fas fa-exclamation-triangle"></i> SEGERA</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- STATUS -->
                                            <td class="text-center">
                                                @if ($dokumen->sts_dok == 'AKTIF')
                                                    <span class="badge bg-success">AKTIF</span>
                                                @else
                                                    <span class="badge bg-secondary">NON-AKTIF</span>
                                                @endif
                                            </td>

                                            <!-- AKSI -->
                                            <td class="text-center no-wrap">
                                                <div class="btn-group" role="group">
                                                    @if (auth()->user()->is_admin || ($userPermissions['detail'] ?? false))
                                                        <a href="{{ route('data-dokumen-laporan.show', $dokumen->id) }}"
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-filter me-2"></i>Filter Pelaporan Dokumen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="GET" action="{{ route('data-dokumen-laporan.index') }}">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_status" class="form-label fw-bold">Status Dokumen</label>
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

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_jenis_dokumen" class="form-label fw-bold">Jenis Dokumen</label>
                                    <select class="form-select select2" id="filter_jenis_dokumen"
                                        name="filter_jenis_dokumen">
                                        <option value="">Semua Jenis Dokumen</option>
                                        @foreach ($dokumenTypes as $dokumen)
                                            <option value="{{ $dokumen->id }}"
                                                {{ $currentFilters['jenis_dokumen'] == $dokumen->id ? 'selected' : '' }}>
                                                {{ $dokumen->jns_dok_kry }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_kategori_dokumen" class="form-label fw-bold">Kategori
                                        Dokumen</label>
                                    <select class="form-select select2" id="filter_kategori_dokumen"
                                        name="filter_kategori_dokumen">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($kategoriDokumenOptions as $kategori)
                                            <option value="{{ $kategori->ktg_dok_kry }}"
                                                {{ $currentFilters['kategori_dokumen'] == $kategori->ktg_dok_kry ? 'selected' : '' }}>
                                                {{ $kategori->ktg_dok_kry }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_nama" class="form-label fw-bold">Nama Karyawan</label>
                                    <input type="text" class="form-control" id="filter_nama" name="filter_nama"
                                        placeholder="Cari nama karyawan..." value="{{ $currentFilters['nama'] ?? '' }}">
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_nrk" class="form-label fw-bold">NRK</label>
                                    <input type="text" class="form-control" id="filter_nrk" name="filter_nrk"
                                        placeholder="Cari NRK..." value="{{ $currentFilters['nrk'] ?? '' }}">
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_no_dokumen" class="form-label fw-bold">No Dokumen</label>
                                    <input type="text" class="form-control" id="filter_no_dokumen"
                                        name="filter_no_dokumen" placeholder="Cari nomor dokumen..."
                                        value="{{ $currentFilters['no_dokumen'] ?? '' }}">
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_perusahaan" class="form-label fw-bold">Perusahaan</label>
                                    <select class="form-select select2" id="filter_perusahaan" name="filter_perusahaan">
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

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_jabatan" class="form-label fw-bold">Jabatan</label>
                                    <select class="form-select select2" id="filter_jabatan" name="filter_jabatan">
                                        <option value="">Semua Jabatan</option>
                                        @foreach ($jabatanOptions as $jabatan)
                                            <option value="{{ $jabatan->id }}"
                                                {{ $currentFilters['jabatan'] == $jabatan->id ? 'selected' : '' }}>
                                                {{ $jabatan->nama_jbt }} @if ($jabatan->singkatan_jbt)
                                                    ({{ $jabatan->singkatan_jbt }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

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

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="filter_jenis_kelamin" class="form-label fw-bold">Jenis Kelamin</label>
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

                        <div class="row mt-3">
                            <div class="col-md-12 text-end">
                                <button type="button" class="btn btn-secondary me-2" id="resetFilter">
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
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-chart-pie me-2"></i>Ringkasan Pelaporan Dokumen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Overall Statistics - Row 1 -->
                    <div class="row mb-3">
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

                    <!-- Overall Statistics - Row 2 -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-info shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-info mb-3"></i>
                                    <h6 class="text-muted mb-2">Total Karyawan</h6>
                                    <h2 class="fw-bold text-info">{{ $totalKaryawan }}</h2>
                                    <small class="text-muted">Karyawan yang memiliki dokumen</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-secondary shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-building fa-3x text-secondary mb-3"></i>
                                    <h6 class="text-muted mb-2">Total Perusahaan</h6>
                                    <h2 class="fw-bold text-secondary">{{ $totalPerusahaan }}</h2>
                                    <small class="text-muted">Perusahaan yang terdaftar</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Document Type -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-file-alt me-2 text-primary"></i>Berdasarkan Jenis
                            Dokumen</h5>
                        <div class="row">
                            @foreach ($dokumenTypes as $dokumen)
                                @php
                                    $count = $dataDokumens->where('id_dokumen', $dokumen->id)->count();
                                @endphp
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card border-left-primary shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <small class="text-muted">{{ $dokumen->kode_dok_kry }}</small>
                                                    <p class="mb-0 fw-bold text-truncate"
                                                        title="{{ $dokumen->jns_dok_kry }}" style="max-width: 150px;">
                                                        {{ Str::limit($dokumen->jns_dok_kry, 20) }}
                                                    </p>
                                                    @if ($dokumen->ktg_dok_kry)
                                                        <small class="badge bg-info">{{ $dokumen->ktg_dok_kry }}</small>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <h3 class="fw-bold text-primary mb-0">{{ $count }}</h3>
                                                </div>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    style="width: {{ $totalDokumen > 0 ? ($count / $totalDokumen) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- By Document Category -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-folder-open me-2 text-success"></i>Berdasarkan Kategori
                            Dokumen</h5>
                        <div class="row">
                            @foreach ($kategoriDokumenOptions as $kategori)
                                @php
                                    $count = $dataDokumens
                                        ->filter(function ($dok) use ($kategori) {
                                            return $dok->dokumenType &&
                                                $dok->dokumenType->ktg_dok_kry == $kategori->ktg_dok_kry;
                                        })
                                        ->count();
                                @endphp
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card border-left-success shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="mb-0 fw-bold text-truncate"
                                                        title="{{ $kategori->ktg_dok_kry }}" style="max-width: 150px;">
                                                        {{ Str::limit($kategori->ktg_dok_kry, 20) }}
                                                    </p>
                                                </div>
                                                <div class="text-end">
                                                    <h3 class="fw-bold text-success mb-0">{{ $count }}</h3>
                                                </div>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $totalDokumen > 0 ? ($count / $totalDokumen) * 100 : 0 }}%">
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
                        <h5 class="fw-bold mb-3"><i class="fas fa-building me-2 text-secondary"></i>Berdasarkan Perusahaan
                        </h5>
                        <div class="row">
                            @foreach ($perusahaans as $perusahaan)
                                @php
                                    $count = $dataDokumens
                                        ->filter(function ($dok) use ($perusahaan) {
                                            return $dok->karyawan && $dok->karyawan->perusahaan == $perusahaan->id;
                                        })
                                        ->count();

                                    $karyawanCount = $dataDokumens
                                        ->filter(function ($dok) use ($perusahaan) {
                                            return $dok->karyawan && $dok->karyawan->perusahaan == $perusahaan->id;
                                        })
                                        ->pluck('id_data_kry')
                                        ->unique()
                                        ->count();
                                @endphp
                                @if ($count > 0)
                                    <div class="col-md-4 col-lg-3 mb-3">
                                        <div class="card border-left-secondary shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <small class="text-muted">{{ $perusahaan->nama_prs2 }}</small>
                                                        <p class="mb-0 fw-bold text-truncate"
                                                            title="{{ $perusahaan->nama_prs1 }}"
                                                            style="max-width: 150px;">
                                                            {{ Str::limit($perusahaan->nama_prs1, 20) }}
                                                        </p>
                                                        <small class="badge bg-info">{{ $karyawanCount }} Karyawan</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <h3 class="fw-bold text-secondary mb-0">{{ $count }}</h3>
                                                        <small class="text-muted">Dokumen</small>
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
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        .dataDokumenPelaporanPage .card {
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

        .border-left-danger {
            border-left: 4px solid #dc3545 !important;
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

        .table-danger {
            background-color: #f8d7da !important;
        }

        .table-warning {
            background-color: #fff3cd !important;
        }

        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .modal .select2-container {
            z-index: 1070 !important;
        }

        .modal .select2-dropdown {
            z-index: 1071 !important;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.7rem;
            }

            .btn {
                font-size: 0.65rem;
                padding: 0.2rem 0.4rem;
            }
        }

        #filterActiveAlert {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
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
                        allowClear: true
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

            // Initialize DataTable
            $('#dataDokumenPelaporanTable').DataTable(
                //     {
                //     responsive: true,
                //     language: {
                //         "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                //         "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                //         "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                //         "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                //         "lengthMenu": "Tampilkan _MENU_ entri",
                //         "loadingRecords": "Sedang memuat...",
                //         "processing": "Sedang memproses...",
                //         "search": "Cari:",
                //         "zeroRecords": "Tidak ditemukan data yang sesuai",
                //         "paginate": {
                //             "first": "Pertama",
                //             "last": "Terakhir",
                //             "next": "Selanjutnya",
                //             "previous": "Sebelumnya"
                //         }
                //     },
                //     columnDefs: [{
                //         orderable: false,
                //         targets: [17] // AKSI column
                //     }, {
                //         responsivePriority: 1,
                //         targets: [17] // AKSI - highest priority
                //     }, {
                //         responsivePriority: 2,
                //         targets: [0, 1, 2] // NO, NRK, NAMA
                //     }, {
                //         responsivePriority: 3,
                //         targets: [9, 11, 16] // NO DOK, JENIS DOK, STATUS
                //     }]
                // }
            );

            // Filter button
            $('#filterButton').click(function() {
                $('#filterModal').modal('show');
            });

            // Apply filter
            $('#applyFilter').click(function() {
                $('#filterForm').submit();
            });

            // Reset filter
            $('#resetFilter').click(function() {
                window.location.href = "{{ route('data-dokumen-laporan.index') }}";
            });

            // Export button
            $('#exportButton').click(function() {
                $('#exportModal').modal('show');
            });

            // Export Excel
            $('#exportExcel').click(function() {
                var formData = $('#filterForm').serialize();
                window.location.href = "{{ route('data-dokumen-laporan.index') }}?export=excel&" +
                    formData;
            });

            // Summary button
            $('#summaryButton').click(function() {
                $('#summaryModal').modal('show');
            });

            // Auto-hide alerts
            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 5000);

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
