@extends('layouts.app')

@section('title', 'Detail Data Jenjang Karir')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-eye me-2"></i>Detail Data Jenjang Karir</span>
                        <div>
                            @if (isset($userPermissions['ubah']) && $userPermissions['ubah'])
                                <a href="{{ route('data-jenjang-karir.edit', $dataJenjangKarir->id) }}"
                                    class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            @endif
                            <a href="{{ route('data-jenjang-karir.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs mb-4" id="detailTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="karyawan-tab" data-bs-toggle="tab"
                                    data-bs-target="#karyawan" type="button" role="tab">
                                    <i class="fas fa-user me-1"></i> Data Karyawan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="jenjang-karir-tab" data-bs-toggle="tab"
                                    data-bs-target="#jenjang-karir" type="button" role="tab">
                                    <i class="fas fa-chart-line me-1"></i> Data Jenjang Karir
                                    <span class="badge bg-primary ms-1">{{ $allCareers->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pendidikan-tab" data-bs-toggle="tab"
                                    data-bs-target="#pendidikan" type="button" role="tab">
                                    <i class="fas fa-graduation-cap me-1"></i> Pendidikan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="karir-tab" data-bs-toggle="tab"
                                    data-bs-target="#karir" type="button" role="tab">
                                    <i class="fas fa-briefcase me-1"></i> Jenjang Karir
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="hubin-tab" data-bs-toggle="tab"
                                    data-bs-target="#hubin" type="button" role="tab">
                                    <i class="fas fa-user-check me-1"></i> Hubungan Industrial
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="sistem-tab" data-bs-toggle="tab"
                                    data-bs-target="#sistem" type="button" role="tab">
                                    <i class="fas fa-cog me-1"></i> Info Sistem
                                </button>
                            </li>
                        </ul>

                        <!-- Tab content -->
                        <div class="tab-content">

                            <!-- ===== TAB 1: DATA KARYAWAN ===== -->
                            <div class="tab-pane fade show active" id="karyawan" role="tabpanel">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-user me-2"></i>Informasi Karyawan</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($dataJenjangKarir->karyawan)
                                            <div class="row">
                                                <!-- Foto -->
                                                <div class="col-md-3 text-center mb-4">
                                                    <div class="employee-photo-container">
                                                        @if ($dataJenjangKarir->karyawan->foto_dokumen)
                                                            <img src="{{ asset('storage/' . $dataJenjangKarir->karyawan->foto_dokumen) }}"
                                                                alt="Foto {{ $dataJenjangKarir->karyawan->nama ?? 'Karyawan' }}"
                                                                class="img-fluid rounded shadow employee-photo"
                                                                onerror="this.style.display='none'; document.getElementById('foto_placeholder_show').style.display='flex'">
                                                            <div id="foto_placeholder_show" class="default-avatar rounded shadow" style="display: none;">
                                                                <i class="fas fa-user-circle fa-8x text-secondary"></i>
                                                            </div>
                                                        @else
                                                            <div class="default-avatar rounded shadow">
                                                                <i class="fas fa-user-circle fa-8x text-secondary"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Info -->
                                                <div class="col-md-9">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label fw-bold">Nama Lengkap</label>
                                                            <input type="text" class="form-control fw-bold"
                                                                value="{{ $dataJenjangKarir->karyawan->nama ?? '-' }}" readonly>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label fw-bold">NIK</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataJenjangKarir->karyawan->nik ?? '-' }}" readonly>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label fw-bold">NRK</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataJenjangKarir->karyawan->nrk ?? '-' }}" readonly>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label fw-bold">Jenis Kelamin</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataJenjangKarir->karyawan->sex ?? '-' }}" readonly>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label fw-bold">Tempat, Tanggal Lahir</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataJenjangKarir->karyawan->tpt_lahir ?? '' }}{{ $dataJenjangKarir->karyawan->tgl_lahir ? ', ' . $dataJenjangKarir->karyawan->tgl_lahir->format('d-m-Y') : '' }}"
                                                                readonly>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label fw-bold">Telepon</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataJenjangKarir->karyawan->tlp1 ?? '-' }}" readonly>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label fw-bold">Status Kawin</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataJenjangKarir->karyawan->sts_nikah ?? '-' }}" readonly>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label fw-bold">Jumlah Anak</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataJenjangKarir->karyawan->jml_anak ?? '-' }}" readonly>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label fw-bold">Email</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $dataJenjangKarir->karyawan->email1 ?? '-' }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Data karyawan tidak ditemukan atau telah dihapus.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- ===== TAB 2: DATA JENJANG KARIR (tabel riwayat, view-only) ===== -->
                            <div class="tab-pane fade" id="jenjang-karir" role="tabpanel">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Riwayat Jenjang Karir Karyawan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="careersTable">
                                                <thead class="table-info">
                                                    <tr>
                                                        <th width="4%" class="text-center">No</th>
                                                        <th width="10%" class="text-center">No. JK</th>
                                                        <th width="10%" class="text-center">Tgl Terbit</th>
                                                        <th width="10%" class="text-center">Kategori</th>
                                                        <th width="14%" class="text-center">Jenis Dokumen</th>
                                                        <th width="14%" class="text-center">Departemen</th>
                                                        <th width="12%" class="text-center">Jabatan</th>
                                                        <th width="10%" class="text-center">Wilayah Kerja</th>
                                                        <th width="8%" class="text-center">Create</th>
                                                        <th width="8%" class="text-center">Update</th>
                                                        <th width="5%" class="text-center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($allCareers as $index => $career)
                                                        <tr>
                                                            <td class="text-center">{{ $index + 1 }}</td>
                                                            <td class="text-center">
                                                                <small class="fw-bold">{{ $career->no_jk ?: '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $career->tgl_ttd ? \Carbon\Carbon::parse($career->tgl_ttd)->format('d-m-Y') : '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ optional($career->dokumenKaryawan)->ktg_dok_kry ?? '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ optional($career->dokumenKaryawan)->jns_dok_kry ?? '-' }}
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ optional($career->departemen)->nama_dep ?? '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ optional($career->departemen)->nama_jbt ?? '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <small>{{ optional($career->wilayahKerja)->wilayah_krj ?? '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <small class="text-muted">{{ $career->created_at ? $career->created_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <small class="text-muted">{{ $career->updated_at ? $career->updated_at->format('d/m/y H:i') : '-' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                {{-- View-only: hanya tombol detail, tidak ada edit/hapus --}}
                                                                <button type="button"
                                                                    class="btn btn-sm btn-info view-career-btn"
                                                                    data-career-id="{{ $career->id }}"
                                                                    title="Lihat Detail">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="11" class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                                Tidak ada data jenjang karir
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== TAB 3: PENDIDIKAN ===== -->
                            <div class="tab-pane fade" id="pendidikan" role="tabpanel">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-graduation-cap me-2"></i>Pendidikan Terakhir</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jenjang Pendidikan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-level-up-alt"></i></span>
                                                        <input type="text" class="form-control fw-bold text-primary"
                                                            value="{{ $dataJenjangKarir->karyawan->jenjang_skl ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tanggal Lulus</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataJenjangKarir->karyawan->tgl_lulus_skl ? $dataJenjangKarir->karyawan->tgl_lulus_skl->format('d-m-Y') : '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Nama Institusi</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataJenjangKarir->karyawan->institusi_skl ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Fakultas</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-building-columns"></i></span>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataJenjangKarir->karyawan->fakultas_skl ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jurusan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-book-open"></i></span>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataJenjangKarir->karyawan->jurusan_skl ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Kota</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataJenjangKarir->karyawan->kota_skl ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Gelar</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-medal"></i></span>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataJenjangKarir->karyawan->gelar_skl ?? '-' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== TAB 4: JENJANG KARIR SAAT INI ===== -->
                            <div class="tab-pane fade" id="karir" role="tabpanel">
                                <div class="card border-warning mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-briefcase me-2"></i>Jenjang Karir Saat Ini</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Departemen</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                                                        <input type="text" class="form-control fw-bold"
                                                            value="{{ optional($dataJenjangKarir->karyawan->departemenRelation)->nama_dep ?? ($dataJenjangKarir->karyawan->departemen ?? '-') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Singkatan Dep.</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ optional($dataJenjangKarir->karyawan->departemenRelation)->singkatan_dep ?? ($dataJenjangKarir->karyawan->skt_dep ?? '-') }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Jabatan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                        <input type="text" class="form-control fw-bold"
                                                            value="{{ optional($dataJenjangKarir->karyawan->departemenRelation)->nama_jbt ?? ($dataJenjangKarir->karyawan->jabatan ?? '-') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Singkatan Jbt.</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ optional($dataJenjangKarir->karyawan->departemenRelation)->singkatan_jbt ?? ($dataJenjangKarir->karyawan->skt_jbt ?? '-') }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Wilayah Kerja</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                        <input type="text" class="form-control fw-bold"
                                                            value="{{ optional($dataJenjangKarir->karyawan->wilayahKerjaRelation)->wilayah_krj ?? ($dataJenjangKarir->karyawan->wilker ?? '-') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Unit Kerja</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-users-cog"></i></span>
                                                        <input type="text" class="form-control fw-bold"
                                                            value="{{ optional($dataJenjangKarir->karyawan->unitKerjaRelation)->area_krj ?? ($dataJenjangKarir->karyawan->unit_krj ?? '-') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">SKT Wilayah Kerja</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ optional($dataJenjangKarir->karyawan->unitKerjaRelation)->singkatan_wk ?? ($dataJenjangKarir->karyawan->skt_wil_krj ?? '-') }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Tugas & Tanggung Jawab</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                                                        <textarea class="form-control" rows="4" readonly>{{ $dataJenjangKarir->karyawan->tugas ?? '-' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== TAB 5: HUBUNGAN INDUSTRIAL ===== -->
                            <div class="tab-pane fade" id="hubin" role="tabpanel">
                                <div class="card border-info mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-user-check me-2"></i>Hubungan Industrial</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($dataJenjangKarir->karyawan)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-muted">Tanggal Masuk</label>
                                                        <p class="form-control-plaintext fw-bold text-success">
                                                            {{ $dataJenjangKarir->karyawan->tgl_masuk ? $dataJenjangKarir->karyawan->tgl_masuk->format('d-m-Y') : '-' }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-muted">Status Karyawan</label>
                                                        <p class="form-control-plaintext">
                                                            @if ($dataJenjangKarir->karyawan->sts_kry == 'AKTIF')
                                                                <span class="badge bg-success fs-6">{{ $dataJenjangKarir->karyawan->sts_kry }}</span>
                                                            @elseif ($dataJenjangKarir->karyawan->sts_kry == 'NON-AKTIF')
                                                                <span class="badge bg-danger fs-6">{{ $dataJenjangKarir->karyawan->sts_kry }}</span>
                                                            @else
                                                                <span class="badge bg-secondary fs-6">{{ $dataJenjangKarir->karyawan->sts_kry ?? '-' }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-muted">Tanggal PHK</label>
                                                        <p class="form-control-plaintext">
                                                            @if ($dataJenjangKarir->karyawan->tgl_phk)
                                                                <span class="text-danger fw-bold">
                                                                    {{ $dataJenjangKarir->karyawan->tgl_phk->format('d-m-Y') }}
                                                                </span>
                                                            @else
                                                                -
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-muted">Keterangan PHK</label>
                                                        <p class="form-control-plaintext">{{ $dataJenjangKarir->karyawan->ket_phk ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Data karyawan tidak tersedia.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- ===== TAB 6: INFO SISTEM ===== -->
                            <div class="tab-pane fade" id="sistem" role="tabpanel">
                                <div class="card border-secondary mb-4">
                                    <div class="card-header bg-secondary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-cog me-2"></i>Informasi Sistem</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">ID Jenjang Karir</label>
                                                    <p class="form-control-plaintext font-monospace">{{ $dataJenjangKarir->id_jenjang_karir ?? $dataJenjangKarir->id }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">No. Jenjang Karir</label>
                                                    <p class="form-control-plaintext font-monospace">{{ $dataJenjangKarir->no_jk ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Dibuat Pada</label>
                                                    <p class="form-control-plaintext">
                                                        {{ $dataJenjangKarir->created_at ? $dataJenjangKarir->created_at->format('d-m-Y H:i:s') : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Diperbarui Pada</label>
                                                    <p class="form-control-plaintext">
                                                        {{ $dataJenjangKarir->updated_at ? $dataJenjangKarir->updated_at->format('d-m-Y H:i:s') : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- end tab-content --}}

                        <!-- Action buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('data-jenjang-karir.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                        </div>

                    </div>{{-- end card-body --}}
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL: Detail Jenjang Karir (view-only) ===== --}}
    <div class="modal fade" id="careerDetailModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-chart-line me-2"></i>Detail Jenjang Karir
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="careerDetailContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat detail jenjang karir...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .card-header { font-weight: 600; }
        .form-label { margin-bottom: 0.3rem; font-size: 0.85rem; }
        .card { margin-bottom: 1rem; transition: all 0.3s; }
        .card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

        .form-control-plaintext {
            background: none; border: none; padding: 0.375rem 0;
            margin: 0; font-size: 0.95rem; line-height: 1.5; color: #212529 !important;
        }
        .form-control-plaintext:focus { box-shadow: none; outline: none; }

        .employee-photo-container { position: relative; width: 100%; max-width: 250px; margin: 0 auto; }
        .employee-photo { width: 100%; height: auto; max-height: 300px; object-fit: cover; border: 3px solid #0d6efd; }
        .default-avatar { width: 100%; height: 250px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; border: 2px dashed #dee2e6; }

        .table th { background-color: #f8f9fa; font-weight: 600; font-size: 0.875rem; white-space: nowrap; vertical-align: middle; }
        .table td { vertical-align: middle; font-size: 0.875rem; }

        .badge.fs-6 { font-size: 0.9rem !important; }
        .font-monospace { font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important; font-size: 0.9rem; background-color: #f8f9fa; padding: 0.25rem 0.5rem; border-radius: 0.25rem; }

        .nav-tabs .nav-link { border-radius: 0.5rem 0.5rem 0 0; }
        .nav-tabs .nav-link.active { background-color: #fff; border-color: #dee2e6 #dee2e6 #fff; font-weight: 600; }

        #careersTable tbody tr { transition: all 0.2s ease; }
        #careersTable tbody tr:hover { box-shadow: 0 3px 10px rgba(0,0,0,0.15); cursor: pointer; }

        @media print {
            .btn, .nav-tabs { display: none !important; }
            .card { border: 1px solid #ddd !important; box-shadow: none !important; }
            .tab-content > .tab-pane { display: block !important; opacity: 1 !important; }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {

            // Initialize tooltips
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                new bootstrap.Tooltip(el);
            });

            // ===== VIEW CAREER DETAIL =====
            $(document).on('click', '.view-career-btn', function () {
                const careerId = $(this).data('career-id');

                $('#careerDetailModal').modal('show');
                $('#careerDetailContent').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat detail jenjang karir...</p>
                    </div>
                `);

                $.ajax({
                    url: `/data-jenjang-karir/careers/${careerId}`,
                    type: 'GET',
                    success: function (response) {
                        if (response.success) {
                            displayCareerDetail(response.data);
                        } else {
                            $('#careerDetailContent').html(`
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Gagal memuat detail: ${response.message}
                                </div>
                            `);
                        }
                    },
                    error: function () {
                        $('#careerDetailContent').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Terjadi kesalahan saat memuat detail jenjang karir.
                            </div>
                        `);
                    }
                });
            });

            // ===== BUILD DETAIL HTML =====
            function displayCareerDetail(career) {
                const dep    = career.departemen    || {};
                const wilker = career.wilayah_kerja || {};
                const dok    = career.dokumen_karyawan || {};

                // Format tgl_ttd
                const tglTtd = career.tgl_ttd ? formatDate(career.tgl_ttd) : '-';

                // File dokumen
                const fileHtml = career.file_dokumen
                    ? `<div class="input-group">
                            <input type="text" class="form-control text-primary" value="Dokumen tersedia" readonly>
                            <a href="/storage/${career.file_dokumen}" target="_blank" class="btn btn-outline-primary">
                                <i class="fas fa-download"></i>
                            </a>
                       </div>`
                    : `<input type="text" class="form-control text-muted" value="Tidak ada dokumen" readonly>`;

                const html = `
                    <!-- Informasi Umum -->
                    <div class="card border-secondary mb-4">
                        <div class="card-header bg-primary">
                            <h6 class="mb-0 text-white"><i class="fas fa-info-circle me-2"></i>Informasi Umum Jenjang Karir</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">No. Jenjang Karir</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                            <input type="text" class="form-control" value="${career.no_jk || '-'}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Tanggal Terbit</label>
                                        <input type="text" class="form-control fw-bold text-primary" value="${tglTtd}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Kategori Jenjang Karir</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-folder"></i></span>
                                            <input type="text" class="form-control" value="${dok.ktg_dok_kry || '-'}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Jenis Jenjang Karir</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-signature"></i></span>
                                            <input type="text" class="form-control" value="${dok.jns_dok_kry || '-'}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">File Dokumen</label>
                                        ${fileHtml}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Departemen & Jabatan -->
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary">
                            <h6 class="mb-0 text-white"><i class="fas fa-sitemap me-2"></i>Departemen & Jabatan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Departemen</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                                            <input type="text" class="form-control fw-bold" value="${dep.nama_dep || '-'}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Singkatan Dep.</label>
                                        <input type="text" class="form-control" value="${dep.singkatan_dep || '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Jabatan</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                            <input type="text" class="form-control fw-bold" value="${dep.nama_jbt || '-'}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Singkatan Jbt.</label>
                                        <input type="text" class="form-control" value="${dep.singkatan_jbt || '-'}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Wilayah Kerja -->
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary">
                            <h6 class="mb-0 text-white"><i class="fas fa-map-marked-alt me-2"></i>Wilayah Kerja</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Wilayah Kerja</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-map"></i></span>
                                            <input type="text" class="form-control fw-bold" value="${wilker.wilayah_krj || '-'}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Area Kerja</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-users-cog"></i></span>
                                            <input type="text" class="form-control fw-bold" value="${wilker.area_krj || '-'}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Singkatan Wilker</label>
                                        <input type="text" class="form-control" value="${wilker.skt_wilker || '-'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Singkatan Area Kerja</label>
                                        <input type="text" class="form-control" value="${wilker.singkatan_wk || '-'}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tugas -->
                    <div class="card border-primary mb-0">
                        <div class="card-header bg-primary">
                            <h6 class="mb-0 text-white"><i class="fas fa-tasks me-2"></i>Tugas & Tanggung Jawab</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label fw-bold">Deskripsi Tugas</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                                    <textarea class="form-control" rows="4" readonly>${career.tugas || '-'}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('#careerDetailContent').html(html);
            }

            function formatDate(dateString) {
                if (!dateString) return '-';
                try {
                    // Input sudah Y-m-d dari controller, parse manual agar tidak timezone issue
                    const parts = dateString.split('-');
                    if (parts.length === 3) {
                        return parts[2] + '-' + parts[1] + '-' + parts[0];
                    }
                    return dateString;
                } catch (e) {
                    return dateString;
                }
            }

        }); // end ready
    </script>
@endpush