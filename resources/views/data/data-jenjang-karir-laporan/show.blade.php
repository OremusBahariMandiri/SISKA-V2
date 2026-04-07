@extends('layouts.app')

@section('title', 'Detail Jenjang Karir')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-route me-2"></i>Detail Jenjang Karir Karyawan</span>
                        <a href="{{ route('data-jenjang-karir-laporan.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        @php
                            $karyawan   = $dataJenjangKarir->karyawan;
                            $departemen = $dataJenjangKarir->departemen;
                            $wilayah    = $dataJenjangKarir->wilayahKerja;
                            $dokumen    = $dataJenjangKarir->dokumenKaryawan;
                        @endphp

                        <!-- Data Karyawan Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Data Karyawan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">NRK</th>
                                                <td>: {{ $karyawan->nrk ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>NIK</th>
                                                <td>: {{ $karyawan->nik ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama</th>
                                                <td>: <strong>{{ $karyawan->nama ?? '-' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Jenis Kelamin</th>
                                                <td>: {{ $karyawan->sex ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tempat, Tgl Lahir</th>
                                                <td>: {{ $karyawan->tpt_lahir ?? '-' }},
                                                    {{ $karyawan->tgl_lahir ? $karyawan->tgl_lahir->format('d-m-Y') : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tgl Masuk</th>
                                                <td>: {{ $karyawan->tgl_masuk ? $karyawan->tgl_masuk->format('d-m-Y') : '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Departemen</th>
                                                <td>: {{ optional($karyawan->departemenRelation)->nama_dep ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jabatan</th>
                                                <td>: {{ optional($karyawan->departemenRelation)->nama_jbt ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Wilayah Kerja</th>
                                                <td>: {{ optional($karyawan->wilayahKerjaRelation)->wilayah_krj ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Unit Kerja</th>
                                                <td>: {{ optional($karyawan->unitKerjaRelation)->area_krj ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status Karyawan</th>
                                                <td>:
                                                    @if ($karyawan && $karyawan->sts_kry == 'AKTIF')
                                                        <span class="badge bg-success">AKTIF</span>
                                                    @elseif ($karyawan)
                                                        <span class="badge bg-secondary">{{ $karyawan->sts_kry }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Jenjang Karir Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-route me-2"></i>Detail Jenjang Karir</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">No. Jenjang Karir</th>
                                                <td>: <strong>{{ $dataJenjangKarir->no_jk ?? '-' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal TTD</th>
                                                <td>: {{ $dataJenjangKarir->tgl_ttd ? $dataJenjangKarir->tgl_ttd->format('d-m-Y') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jenis Dokumen</th>
                                                <td>:
                                                    @if ($dokumen)
                                                        <span class="badge bg-secondary">{{ $dokumen->kode_dok_kry }}</span>
                                                        {{ $dokumen->nama_dok_kry }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Departemen (JK)</th>
                                                <td>: {{ $departemen->nama_dep ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jabatan (JK)</th>
                                                <td>: {{ $departemen->nama_jbt ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Area Kerja (JK)</th>
                                                <td>: {{ $wilayah->area_krj ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                @if ($dataJenjangKarir->tugas)
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6 class="fw-bold">Tugas / Uraian Jabatan</h6>
                                            <p class="text-muted">{{ $dataJenjangKarir->tugas }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($dataJenjangKarir->file_dokumen)
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6 class="fw-bold">File Dokumen</h6>
                                            <a href="{{ asset('storage/' . $dataJenjangKarir->file_dokumen) }}"
                                                target="_blank" class="btn btn-primary btn-sm">
                                                <i class="fas fa-download me-1"></i> Download File
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Metadata Section -->
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Sistem</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Dibuat Oleh</th>
                                                <td>: {{ $dataJenjangKarir->creator->nama_kry ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Dibuat</th>
                                                <td>: {{ $dataJenjangKarir->created_at ? $dataJenjangKarir->created_at->format('d-m-Y H:i:s') : '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Diubah Oleh</th>
                                                <td>: {{ $dataJenjangKarir->updater->nama_kry ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Diubah</th>
                                                <td>: {{ $dataJenjangKarir->updated_at ? $dataJenjangKarir->updated_at->format('d-m-Y H:i:s') : '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection