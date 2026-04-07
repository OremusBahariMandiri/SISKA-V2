@extends('layouts.app')

@section('title', 'Detail Gaji')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-money-bill-wave me-2"></i>Detail Gaji Karyawan</span>
                        <a href="{{ route('data-gaji-laporan.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        @php
                            $karyawan = $dataGaji->karyawan;
                            $departemen = $karyawan ? $karyawan->departemenRelation : null;
                            $wilayah = $karyawan ? $karyawan->wilayahKerjaRelation : null;
                            $unitKerja = $karyawan ? $karyawan->unitKerjaRelation : null;
                        @endphp

                        <!-- Status Alert -->
                        @if ($dataGaji->sts_data_gaji == 'NON-AKTIF')
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>PERHATIAN:</strong> Data gaji ini berstatus NON-AKTIF!
                                @if ($dataGaji->tgl_na_gaji)
                                    <br>Tanggal Non-Aktif: {{ \Carbon\Carbon::parse($dataGaji->tgl_na_gaji)->format('d-m-Y') }}
                                @endif
                                @if ($dataGaji->ket_na_gaji)
                                    <br>Keterangan: {{ $dataGaji->ket_na_gaji }}
                                @endif
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
                                                <td>: {{ $unitKerja->area_krj ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status Karyawan</th>
                                                <td>: {{ $karyawan->sts_kry ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Gaji Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Detail Gaji</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">ID Gaji</th>
                                                <td>: <strong class="text-primary">{{ $dataGaji->id_gaji ?? '-' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Status Data Gaji</th>
                                                <td>:
                                                    @if ($dataGaji->sts_data_gaji == 'AKTIF')
                                                        <span class="badge bg-success">AKTIF</span>
                                                    @else
                                                        <span class="badge bg-secondary">NON-AKTIF</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <hr>

                                <!-- Pendapatan Tetap -->
                                <h6 class="fw-bold text-success mb-3"><i class="fas fa-coins me-2"></i>PENDAPATAN TETAP</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <th width="60%">Gaji Pokok</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->gj_pokok ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tunjangan Jabatan</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->tunjab ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tunjangan Komunikasi</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->tunkom ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <th width="60%">FOT</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->fot ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tunjangan Makan</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->tunmal ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr class="table-success">
                                                <th><strong>TOTAL PENDAPATAN TETAP</strong></th>
                                                <td class="text-end"><strong>Rp {{ number_format($dataGaji->ttl_pendapatan_ttp ?? 0, 0, ',', '.') }}</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- Pendapatan Tidak Tetap -->
                                <h6 class="fw-bold text-info mb-3"><i class="fas fa-hand-holding-usd me-2"></i>PENDAPATAN TIDAK TETAP</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <th width="60%">Lembur Harian</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->lbr_harian ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Lembur Per Jam</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->lbr_perjam ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tunjangan Kinerja</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->tukin ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <th width="60%">Insentif</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->insentif ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Bonus</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->bonus ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>THR</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->thr ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-sm table-bordered">
                                            <tr class="table-info">
                                                <th width="79.5%"><strong>TOTAL PENDAPATAN TIDAK TETAP</strong></th>
                                                <td class="text-end"><strong>Rp {{ number_format($dataGaji->ttl_pendapatan_tdk_ttp ?? 0, 0, ',', '.') }}</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- Total Pendapatan -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <tr class="table-primary">
                                                <th width="79.5%"><strong><i class="fas fa-calculator me-2"></i>TOTAL PENDAPATAN</strong></th>
                                                <td class="text-end"><strong>Rp {{ number_format($dataGaji->ttl_pendapatan ?? 0, 0, ',', '.') }}</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <hr>

                                <!-- Potongan -->
                                <h6 class="fw-bold text-danger mb-3"><i class="fas fa-cut me-2"></i>POTONGAN</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <th width="60%">BPJS Ketenagakerjaan</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->bpjs_tkj ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>BPJS Kesehatan</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->bpjs_kes ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Iuran Koperasi</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->iuran_koperasi ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tabungan Perumahan Karyawan</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->tps_kry ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Pajak PKP</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->pjk_pkp ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <th width="60%">Pajak PPh</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->pjk_pph ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Potongan THR</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->ptg_thr ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Pinjaman Koperasi</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->pjm_kop ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Denda/Sanksi</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->dda_sanksi ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr class="table-danger">
                                                <th><strong>TOTAL POTONGAN</strong></th>
                                                <td class="text-end"><strong>Rp {{ number_format($dataGaji->ttl_potongan ?? 0, 0, ',', '.') }}</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <hr>

                                <!-- Total Gaji Bersih -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <tr class="table-success">
                                                <th width="79.5%"><strong><i class="fas fa-money-bill-wave me-2"></i>GAJI BERSIH YANG DITERIMA</strong></th>
                                                <td class="text-end"><h4 class="mb-0"><strong>Rp {{ number_format($dataGaji->ttl_terima_gaji ?? 0, 0, ',', '.') }}</strong></h4></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <hr>

                                <!-- Beban Perusahaan (Info Only) -->
                                <h6 class="fw-bold text-warning mb-3"><i class="fas fa-building me-2"></i>BEBAN PERUSAHAAN (Informasi)</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <th width="60%">BPJS Ketenagakerjaan (Perusahaan)</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->bpjs_tkj_prs ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>BPJS Kesehatan (Perusahaan)</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->bpjs_kes_prs ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <th width="60%">Tabungan Perumahan (Perusahaan)</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->tps_prs ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Asuransi Kesehatan (Perusahaan)</th>
                                                <td class="text-end">Rp {{ number_format($dataGaji->askes_prs ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Riwayat Gaji Karyawan -->
                        @if ($allGajis->count() > 1)
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Gaji Karyawan ({{ $allGajis->count() }} record)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-striped">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center">NO</th>
                                                    <th class="text-center">ID GAJI</th>
                                                    <th class="text-center">GAJI POKOK</th>
                                                    <th class="text-center">TOTAL PENDAPATAN</th>
                                                    <th class="text-center">TOTAL POTONGAN</th>
                                                    <th class="text-center">GAJI BERSIH</th>
                                                    <th class="text-center">STATUS</th>
                                                    <th class="text-center">DIBUAT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($allGajis as $gaji)
                                                    <tr class="{{ $gaji->id == $dataGaji->id ? 'table-primary' : '' }}">
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td class="text-center">
                                                            <span class="fw-bold text-primary">{{ $gaji->id_gaji }}</span>
                                                            @if ($gaji->id == $dataGaji->id)
                                                                <br><span class="badge bg-info">Sedang Dilihat</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end">Rp {{ number_format($gaji->gj_pokok ?? 0, 0, ',', '.') }}</td>
                                                        <td class="text-end">Rp {{ number_format($gaji->ttl_pendapatan ?? 0, 0, ',', '.') }}</td>
                                                        <td class="text-end">Rp {{ number_format($gaji->ttl_potongan ?? 0, 0, ',', '.') }}</td>
                                                        <td class="text-end"><strong>Rp {{ number_format($gaji->ttl_terima_gaji ?? 0, 0, ',', '.') }}</strong></td>
                                                        <td class="text-center">
                                                            @if ($gaji->sts_data_gaji == 'AKTIF')
                                                                <span class="badge bg-success">AKTIF</span>
                                                            @else
                                                                <span class="badge bg-secondary">NON-AKTIF</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $gaji->creator->nama_kry ?? '-' }}<br>
                                                            <small>{{ $gaji->created_at ? $gaji->created_at->format('d/m/Y H:i') : '-' }}</small>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

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
                                                <td>: {{ $dataGaji->creator->nama_kry ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Dibuat</th>
                                                <td>: {{ $dataGaji->created_at ? $dataGaji->created_at->format('d-m-Y H:i:s') : '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Diubah Oleh</th>
                                                <td>: {{ $dataGaji->updater->nama_kry ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Diubah</th>
                                                <td>: {{ $dataGaji->updated_at ? $dataGaji->updated_at->format('d-m-Y H:i:s') : '-' }}</td>
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

@push('styles')
    <style>
        .table-borderless th {
            padding: 0.5rem;
            font-weight: 600;
        }

        .table-borderless td {
            padding: 0.5rem;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table-sm th,
        .table-sm td {
            font-size: 0.875rem;
        }
    </style>
@endpush