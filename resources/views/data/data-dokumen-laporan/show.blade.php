@extends('layouts.app')

@section('title', 'Detail Dokumen')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-alt me-2"></i>Detail Dokumen Karyawan</span>
                        <a href="{{ route('data-dokumen-laporan.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        @php
                            $karyawan = $dataDokumen->karyawan;
                            $departemen = $karyawan ? $karyawan->departemenRelation : null;
                            $wilayah = $karyawan ? $karyawan->wilayahKerjaRelation : null;
                            $perusahaan = $karyawan ? $karyawan->perusahaanRelation : null;
                            $dokumenType = $dataDokumen->dokumenType;

                            // Calculate document status
                            $today = now();
                            $isExpired = $dataDokumen->tgl_akr_dok && $dataDokumen->tgl_akr_dok < $today && $dataDokumen->sts_dok == 'AKTIF';
                            $isExpiring = $dataDokumen->tgl_pgt_dok &&
                                         $dataDokumen->tgl_pgt_dok <= $today &&
                                         $dataDokumen->tgl_akr_dok >= $today &&
                                         $dataDokumen->sts_dok == 'AKTIF';
                        @endphp

                        <!-- Status Alert -->
                        @if ($isExpired)
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>PERINGATAN:</strong> Dokumen ini sudah EXPIRED!
                            </div>
                        @elseif ($isExpiring)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>PERHATIAN:</strong> Dokumen ini akan segera expired!
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

                        <!-- Data Dokumen Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Detail Dokumen</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Jenis Dokumen</th>
                                                <td>: {{ $dokumenType->jns_dok_kry ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kode Dokumen</th>
                                                <td>: {{ $dokumenType->kode_dok_kry ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kategori Dokumen</th>
                                                <td>: {{ $dokumenType->ktg_dok_kry ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>No Dokumen</th>
                                                <td>: <strong>{{ $dataDokumen->no_dok ?? '-' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>:
                                                    @if ($dataDokumen->sts_dok == 'AKTIF')
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
                                                <th width="40%">Tanggal Terbit</th>
                                                <td>: {{ $dataDokumen->tgl_awal_dok ? $dataDokumen->tgl_awal_dok->format('d-m-Y') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Akhir</th>
                                                <td>: {{ $dataDokumen->tgl_akr_dok ? $dataDokumen->tgl_akr_dok->format('d-m-Y') : '-' }}
                                                    @if ($isExpired)
                                                        <span class="badge bg-danger ms-2">EXPIRED</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Peringatan</th>
                                                <td>: {{ $dataDokumen->tgl_pgt_dok ? $dataDokumen->tgl_pgt_dok->format('d-m-Y') : '-' }}
                                                    @if ($isExpiring)
                                                        <span class="badge bg-warning text-dark ms-2">SEGERA EXPIRED</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Jenis Masa Berlaku</th>
                                                <td>: {{ $dataDokumen->jns_msb_dok ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Keterangan</th>
                                                <td>: {{ $dataDokumen->ket_dok ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                @if ($dataDokumen->file_dok)
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6 class="fw-bold">File Dokumen</h6>
                                            <a href="{{ asset('storage/' . $dataDokumen->file_dok) }}" target="_blank" class="btn btn-primary btn-sm">
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
                                                <td>: {{ $dataDokumen->creator->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Dibuat</th>
                                                <td>: {{ $dataDokumen->created_at ? $dataDokumen->created_at->format('d-m-Y H:i:s') : '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Diubah Oleh</th>
                                                <td>: {{ $dataDokumen->updater->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Diubah</th>
                                                <td>: {{ $dataDokumen->updated_at ? $dataDokumen->updated_at->format('d-m-Y H:i:s') : '-' }}</td>
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
