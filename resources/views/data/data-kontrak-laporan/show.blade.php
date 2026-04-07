@extends('layouts.app')

@section('title', 'Detail Kontrak')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-contract me-2"></i>Detail Kontrak Karyawan</span>
                        <a href="{{ route('data-kontrak-laporan.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        @php
                            $karyawan = $dataKontrak->karyawan;
                            $departemen = $karyawan ? $karyawan->departemenRelation : null;
                            $wilayah = $karyawan ? $karyawan->wilayahKerjaRelation : null;
                            $perusahaan = $dataKontrak->perusahaan;
                            $kontrakType = $dataKontrak->kontrakKerja;

                            // Calculate contract status
                            $today = now();
                            $isExpired = $dataKontrak->tgl_akhir_ktr && $dataKontrak->tgl_akhir_ktr < $today && $dataKontrak->sts_srt_ktr == 'AKTIF';
                            $isExpiring = $dataKontrak->tgl_pgt_ktr &&
                                         $dataKontrak->tgl_pgt_ktr <= $today &&
                                         $dataKontrak->tgl_akhir_ktr >= $today &&
                                         $dataKontrak->sts_srt_ktr == 'AKTIF';
                        @endphp

                        <!-- Status Alert -->
                        @if ($isExpired)
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>PERINGATAN:</strong> Kontrak ini sudah EXPIRED!
                            </div>
                        @elseif ($isExpiring)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>PERHATIAN:</strong> Kontrak ini akan segera expired!
                            </div>
                        @endif

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
                                                <th>Tempat, Tanggal Lahir</th>
                                                <td>: {{ $karyawan->tpt_lahir ?? '-' }}, {{ $karyawan->tgl_lahir ? $karyawan->tgl_lahir->format('d-m-Y') : '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Departemen</th>
                                                <td>: {{ $departemen->nama_dep ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jabatan</th>
                                                <td>: {{ $departemen->nama_jbt ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Wilayah Kerja</th>
                                                <td>: {{ $wilayah->wilayah_krj ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Unit Kerja</th>
                                                <td>: {{ $wilayah->area_krj ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Perusahaan</th>
                                                <td>: {{ $perusahaan->nama_prs1 ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Kontrak Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Detail Kontrak</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Jenis Kontrak</th>
                                                <td>: {{ $kontrakType->nama_ktr ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kategori</th>
                                                <td>: {{ $dataKontrak->ktg_ktk ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>No Kontrak</th>
                                                <td>: <strong>{{ $dataKontrak->no_srt_ktr ?? '-' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>:
                                                    @if ($dataKontrak->sts_srt_ktr == 'AKTIF')
                                                        <span class="badge bg-success">AKTIF</span>
                                                    @else
                                                        <span class="badge bg-secondary">NON-AKTIF</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Tanggal Awal</th>
                                                <td>: {{ $dataKontrak->tgl_awl_ktr ? $dataKontrak->tgl_awl_ktr->format('d-m-Y') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Akhir</th>
                                                <td>: {{ $dataKontrak->tgl_akhir_ktr ? $dataKontrak->tgl_akhir_ktr->format('d-m-Y') : '-' }}
                                                    @if ($isExpired)
                                                        <span class="badge bg-danger ms-2">EXPIRED</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Peringatan</th>
                                                <td>: {{ $dataKontrak->tgl_pgt_ktr ? $dataKontrak->tgl_pgt_ktr->format('d-m-Y') : '-' }}
                                                    @if ($isExpiring)
                                                        <span class="badge bg-warning text-dark ms-2">SEGERA EXPIRED</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Durasi</th>
                                                <td>: {{ $dataKontrak->durasi_ktr ? $dataKontrak->durasi_ktr . ' bulan' : '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                @if ($dataKontrak->file_doc_ktr)
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6 class="fw-bold">File Kontrak</h6>
                                            <a href="{{ asset('storage/' . $dataKontrak->file_doc_ktr) }}" target="_blank" class="btn btn-primary btn-sm">
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
                                                <td>: {{ $dataKontrak->creator->nama_kry ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Dibuat</th>
                                                <td>: {{ $dataKontrak->created_at ? $dataKontrak->created_at->format('d-m-Y H:i:s') : '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Diubah Oleh</th>
                                                <td>: {{ $dataKontrak->updater->nama_kry ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Diubah</th>
                                                <td>: {{ $dataKontrak->updated_at ? $dataKontrak->updated_at->format('d-m-Y H:i:s') : '-' }}</td>
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