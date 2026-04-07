@extends('layouts.app')

@section('title', 'Detail Gaji')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
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
                            $unitKerja = $karyawan ? $karyawan->unitKerjaRelation : null;

                            // wilker menyimpan string langsung, bukan foreign key
                            $namaWilayah = $karyawan->wilker ?? '-';
                            $namaUnitKerja = $unitKerja->area_krj ?? '-';
                        @endphp

                        <!-- Status Alert -->
                        @if ($dataGaji->sts_data_gaji == 'NON-AKTIF')
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>PERHATIAN:</strong> Data gaji ini berstatus NON-AKTIF!
                                @if ($dataGaji->tgl_na_gaji)
                                    <br>Tanggal Non-Aktif:
                                    {{ \Carbon\Carbon::parse($dataGaji->tgl_na_gaji)->format('d-m-Y') }}
                                @endif
                                @if ($dataGaji->ket_na_gaji)
                                    <br>Keterangan: {{ $dataGaji->ket_na_gaji }}
                                @endif
                            </div>
                        @endif

                        <!-- Data Karyawan Section -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary bg-opacity-25">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Data Karyawan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <label class="col-sm-5 col-form-label fw-bold">NRK</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"
                                                    value="{{ $karyawan->nrk ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-5 col-form-label fw-bold">NIK</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"
                                                    value="{{ $karyawan->nik ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-5 col-form-label fw-bold">Nama</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control fw-bold"
                                                    value="{{ $karyawan->nama ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-5 col-form-label fw-bold">Jenis Kelamin</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"
                                                    value="{{ $karyawan->sex ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-5 col-form-label fw-bold">TTL</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"
                                                    value="{{ $karyawan->tpt_lahir ?? '-' }}{{ $karyawan->tgl_lahir ? ', ' . $karyawan->tgl_lahir->format('d-m-Y') : '' }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <label class="col-sm-5 col-form-label fw-bold">Departemen</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control fw-bold"
                                                    value="{{ $departemen->nama_dep ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-5 col-form-label fw-bold">Jabatan</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control fw-bold"
                                                    value="{{ $departemen->nama_jbt ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-5 col-form-label fw-bold">Wilayah Kerja</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"
                                                value="{{ $namaWilayah }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-5 col-form-label fw-bold">Unit Kerja</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"
                                                value="{{ $namaUnitKerja }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-5 col-form-label fw-bold">Status</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"
                                                    value="{{ $karyawan->sts_kry ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Gaji Section - Sama seperti Modal -->
                        <div class="row">
                            <!-- ===== KOLOM KIRI: Pendapatan Tetap + Tidak Tetap ===== -->
                            <div class="col-md-6">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-wallet me-2"></i>Pendapatan</h5>
                                    </div>
                                    <div class="card-body">

                                        <!-- Pendapatan Tetap -->
                                        <div class="mb-4">
                                            <h6 class="text-success fw-bold mb-3 pb-2 border-bottom">
                                                <i class="fas fa-check-circle me-2"></i>Pendapatan Tetap
                                            </h6>

                                            <div class="row mb-2">
                                                <label class="col-sm-5">Gaji Pokok</label>
                                                <div class="col-sm-7 text-end">Rp
                                                    {{ number_format($dataGaji->gj_pokok ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-sm-5">Tunjangan Jabatan</label>
                                                <div class="col-sm-7 text-end">
                                                    Rp {{ number_format($dataGaji->tunjab ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-sm-5">Tunjangan Komunikasi</label>
                                                <div class="col-sm-7 text-end">
                                                    Rp {{ number_format($dataGaji->tunkom ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-sm-5">FOT</label>
                                                <div class="col-sm-7 text-end">
                                                    Rp {{ number_format($dataGaji->fot ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-sm-5">Tunjangan Kemahalan</label>
                                                <div class="col-sm-7 text-end">
                                                    Rp {{ number_format($dataGaji->tunmal ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="row mb-3 border-top pt-2">
                                                <label class="col-sm-5 fw-bold">Jumlah Tetap</label>
                                                <div class="col-sm-7 text-end">
                                                    <strong class="text-black">Rp
                                                        {{ number_format($dataGaji->ttl_pendapatan_ttp ?? 0, 0, ',', '.') }}</strong>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Pendapatan Tidak Tetap -->
                                        <div>
                                            <h6 class="text-warning fw-bold mb-3 pb-2 border-bottom">
                                                <i class="fas fa-coins me-2"></i>Pendapatan Tidak Tetap
                                            </h6>

                                            <div class="row mb-2">
                                                <label class="col-sm-5">Lembur Harian</label>
                                                <div class="col-sm-7 text-end">
                                                    Rp {{ number_format($dataGaji->lbr_harian ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-sm-5">Lembur Per Jam</label>
                                                <div class="col-sm-7 text-end">
                                                    Rp {{ number_format($dataGaji->lbr_perjam ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-sm-5">Tunjangan Kinerja</label>
                                                <div class="col-sm-7 text-end">
                                                    Rp {{ number_format($dataGaji->tukin ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-sm-5">Insentif</label>
                                                <div class="col-sm-7 text-end">
                                                    Rp {{ number_format($dataGaji->insentif ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-sm-5">Bonus</label>
                                                <div class="col-sm-7 text-end">
                                                    Rp {{ number_format($dataGaji->bonus ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-sm-5">THR</label>
                                                <div class="col-sm-7 text-end">
                                                    Rp {{ number_format($dataGaji->thr ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="row border-top pt-2">
                                                <label class="col-sm-5 fw-bold">Jumlah Tidak Tetap</label>
                                                <div class="col-sm-7 text-end">
                                                    <strong class="text-black">Rp
                                                        {{ number_format($dataGaji->ttl_pendapatan_tdk_ttp ?? 0, 0, ',', '.') }}</strong>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- ===== KOLOM KANAN: Potongan ===== -->
                            <div class="col-md-6">
                                <div class="card border-danger mb-4" style="height: 600px">
                                    <div class="card-header bg-danger bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-minus-circle me-2"></i>Potongan</h5>
                                    </div>
                                    <div class="card-body">

                                        <div class="row mb-2">
                                            <label class="col-sm-5">BPJS Naker</label>
                                            <div class="col-sm-7 text-end">
                                                Rp {{ number_format($dataGaji->bpjs_tkj ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-sm-5">BPJS Kesehatan</label>
                                            <div class="col-sm-7 text-end">
                                                Rp {{ number_format($dataGaji->bpjs_kes ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-sm-5">Iuran Koperasi</label>
                                            <div class="col-sm-7 text-end">
                                                Rp {{ number_format($dataGaji->iuran_koperasi ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-sm-5">Tabungan Pensiun</label>
                                            <div class="col-sm-7 text-end">
                                                Rp {{ number_format($dataGaji->tps_kry ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-sm-5">Pajak PKP</label>
                                            <div class="col-sm-7 text-end">
                                                Rp {{ number_format($dataGaji->pjk_pkp ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-sm-5">Pajak PPh</label>
                                            <div class="col-sm-7 text-end">
                                                Rp {{ number_format($dataGaji->pjk_pph ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-sm-5">Potongan THR</label>
                                            <div class="col-sm-7 text-end">
                                                Rp {{ number_format($dataGaji->ptg_thr ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-sm-5">Pinjaman Koperasi</label>
                                            <div class="col-sm-7 text-end">
                                                Rp {{ number_format($dataGaji->pjm_kop ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-sm-5">Denda Sanksi</label>
                                            <div class="col-sm-7 text-end">
                                                Rp {{ number_format($dataGaji->dda_sanksi ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <div class="row border-top pt-2">
                                            <label class="col-sm-5 fw-bold">Jumlah Potongan</label>
                                            <div class="col-sm-7 text-end">
                                                <strong class="text-black">Rp
                                                    {{ number_format($dataGaji->ttl_potongan ?? 0, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>{{-- end row utama --}}

                        <!-- ===== RINGKASAN GAJI ===== -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-calculator me-2"></i>Ringkasan Gaji
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label fw-bold">Total Pendapatan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-end"
                                                            value="{{ number_format($dataGaji->ttl_pendapatan ?? 0, 0, ',', '.') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label fw-bold">Total Potongan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-end"
                                                            value="{{ number_format($dataGaji->ttl_potongan ?? 0, 0, ',', '.') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label fw-bold">Gaji Diterima</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-end"
                                                            value="{{ number_format($dataGaji->ttl_terima_gaji ?? 0, 0, ',', '.') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== BEBAN TANGGUNGAN PERUSAHAAN ===== -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-building me-2"></i>Beban Tanggungan
                                            Perusahaan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label fw-bold">BPJS Naker</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control text-end"
                                                            value="{{ number_format($dataGaji->bpjs_tkj_prs ?? 0, 0, ',', '.') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label fw-bold">BPJS Kes</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control text-end"
                                                            value="{{ number_format($dataGaji->bpjs_kes_prs ?? 0, 0, ',', '.') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label fw-bold">Tabungan Pensiun</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control text-end"
                                                            value="{{ number_format($dataGaji->tps_prs ?? 0, 0, ',', '.') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label fw-bold">Asuransi Kesehatan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control text-end"
                                                            value="{{ number_format($dataGaji->askes_prs ?? 0, 0, ',', '.') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== STATUS DATA GAJI ===== -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-secondary mb-4">
                                    <div class="card-header bg-secondary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-info-circle me-2"></i>Status Data
                                            Gaji</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label fw-bold">Status Data Gaji</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-check-circle"></i></span>
                                                        <input type="text" class="form-control"
                                                            value="{{ $dataGaji->sts_data_gaji ?? 'AKTIF' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label fw-bold">Tanggal Status Non Aktif</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataGaji->tgl_na_gaji ? \Carbon\Carbon::parse($dataGaji->tgl_na_gaji)->format('d-m-Y') : '-' }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label fw-bold">Keterangan Non Aktif</label>
                                                    <textarea class="form-control" rows="2" readonly>{{ $dataGaji->ket_na_gaji ?? '-' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Riwayat Gaji Karyawan -->
                        @if ($allGajis->count() > 1)
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-25">
                                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Gaji Karyawan
                                        ({{ $allGajis->count() }} record)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-striped">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" width="5%">NO</th>
                                                    <th class="text-center" width="10%">ID GAJI</th>
                                                    <th class="text-center" width="15%">GAJI POKOK</th>
                                                    <th class="text-center" width="15%">TOTAL PENDAPATAN</th>
                                                    <th class="text-center" width="15%">TOTAL POTONGAN</th>
                                                    <th class="text-center" width="15%">GAJI BERSIH</th>
                                                    <th class="text-center" width="10%">STATUS</th>
                                                    <th class="text-center" width="15%">DIBUAT</th>
                                                    <th class="text-center" width="5%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($allGajis as $gaji)
                                                    <tr class="{{ $gaji->id == $dataGaji->id ? 'table-primary' : '' }}">
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td class="text-center">
                                                            <span class="fw-bold text-primary">{{ $gaji->id_gaji }}</span>
                                                            @if ($gaji->id == $dataGaji->id)
                                                                <br><span class="badge bg-info text-dark">Aktif</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end">Rp
                                                            {{ number_format($gaji->gj_pokok ?? 0, 0, ',', '.') }}</td>
                                                        <td class="text-end">Rp
                                                            {{ number_format($gaji->ttl_pendapatan ?? 0, 0, ',', '.') }}
                                                        </td>
                                                        <td class="text-end">Rp
                                                            {{ number_format($gaji->ttl_potongan ?? 0, 0, ',', '.') }}</td>
                                                        <td class="text-end"><strong>Rp
                                                                {{ number_format($gaji->ttl_terima_gaji ?? 0, 0, ',', '.') }}</strong>
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($gaji->sts_data_gaji == 'AKTIF')
                                                                <span class="badge bg-success">AKTIF</span>
                                                            @else
                                                                <span class="badge bg-secondary">NON-AKTIF</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <small>{{ $gaji->creator->nama_kry ?? '-' }}<br>
                                                                {{ $gaji->created_at ? $gaji->created_at->format('d/m/Y H:i') : '-' }}</small>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button"
                                                                class="btn btn-sm btn-info view-salary-btn"
                                                                data-salary-id="{{ $gaji->id }}"
                                                                data-bs-toggle="tooltip" title="Lihat Detail">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Informasi Sistem -->
                        <div class="card border-secondary">
                            <div class="card-header bg-secondary bg-opacity-25">
                                <h5 class="mb-0"><i class="fas fa-cog me-2 text-white"></i>Informasi Sistem</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <label class="col-sm-5 col-form-label text-muted fw-bold">Dibuat Oleh</label>
                                            <div class="col-sm-7">
                                                <p class="form-control-plaintext">
                                                    {{ $dataGaji->creator->nama_kry ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-5 col-form-label text-muted fw-bold">Dibuat Pada</label>
                                            <div class="col-sm-7">
                                                <p class="form-control-plaintext">
                                                    {{ $dataGaji->created_at ? $dataGaji->created_at->format('d-m-Y H:i:s') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <label class="col-sm-5 col-form-label text-muted fw-bold">Diubah Oleh</label>
                                            <div class="col-sm-7">
                                                <p class="form-control-plaintext">
                                                    {{ $dataGaji->updater->nama_kry ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-5 col-form-label text-muted fw-bold">Diubah Pada</label>
                                            <div class="col-sm-7">
                                                <p class="form-control-plaintext">
                                                    {{ $dataGaji->updated_at ? $dataGaji->updated_at->format('d-m-Y H:i:s') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary Detail Modal (Sama seperti di Edit & Show) -->
    <div class="modal fade" id="salaryModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-money-bill-wave me-2"></i>Detail Data Gaji
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="salaryForm">
                        <input type="hidden" id="salary_id" name="salary_id">

                        <!-- Mode Indicator -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <span>Mode Detail - Data hanya dapat dilihat</span>
                        </div>

                        <!-- ===== ROW UTAMA: Pendapatan (Kiri) | Potongan (Kanan) ===== -->
                        <div class="row">

                            <!-- ===== KOLOM KIRI: Pendapatan Tetap + Tidak Tetap ===== -->
                            <div class="col-md-6">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-wallet me-2"></i>Pendapatan</h5>
                                    </div>
                                    <div class="card-body">

                                        <!-- Pendapatan Tetap -->
                                        <div class="mb-4">
                                            <h6 class="text-success fw-bold mb-3 pb-2 border-bottom">
                                                <i class="fas fa-check-circle me-2"></i>Pendapatan Tetap
                                            </h6>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_gj_pokok" class="col-sm-5 col-form-label">Gaji
                                                    Pokok</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_gj_pokok" name="gj_pokok" value="0"
                                                            min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_tunjab" class="col-sm-5 col-form-label">Tunjangan
                                                    Jabatan</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_tunjab" name="tunjab" value="0"
                                                            min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_tunkom" class="col-sm-5 col-form-label">Tunjangan
                                                    Komunikasi</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_tunkom" name="tunkom" value="0"
                                                            min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_fot" class="col-sm-5 col-form-label">Fix Over Time
                                                    (FOT)</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_fot" name="fot" value="0" min="0"
                                                            step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_tunmal" class="col-sm-5 col-form-label">Tunjangan
                                                    Kemahalan</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_tunmal" name="tunmal" value="0"
                                                            min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label class="col-sm-5 col-form-label fw-bold">Jumlah Pendapatan
                                                    Tetap</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-end"
                                                            id="modal_total_pendapatan_tetap" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Pendapatan Tidak Tetap -->
                                        <div>
                                            <h6 class="text-warning fw-bold mb-3 pb-2 border-bottom">
                                                <i class="fas fa-coins me-2"></i>Pendapatan Tidak Tetap
                                            </h6>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_lbr_harian" class="col-sm-5 col-form-label">Lembur
                                                    Harian</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_lbr_harian" name="lbr_harian" value="0"
                                                            min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_lbr_perjam" class="col-sm-5 col-form-label">Lembur Per
                                                    Jam</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_lbr_perjam" name="lbr_perjam" value="0"
                                                            min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_tukin" class="col-sm-5 col-form-label">Tunjangan
                                                    Kinerja</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_tukin" name="tukin" value="0"
                                                            min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_insentif"
                                                    class="col-sm-5 col-form-label">Insentif</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_insentif" name="insentif" value="0"
                                                            min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_bonus" class="col-sm-5 col-form-label">Bonus</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_bonus" name="bonus" value="0"
                                                            min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label for="modal_thr" class="col-sm-5 col-form-label">Tunjangan Hari Raya
                                                    (THR)</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_thr" name="thr" value="0" min="0"
                                                            step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label class="col-sm-5 col-form-label fw-bold">Jumlah Pendapatan Tidak
                                                    Tetap</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-end"
                                                            id="modal_total_pendapatan_tidak_tetap" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- ===== KOLOM KANAN: Potongan ===== -->
                            <div class="col-md-6">
                                <div class="card border-danger mb-4" style="height: 700px">
                                    <div class="card-header bg-danger bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-minus-circle me-2"></i>Potongan</h5>
                                    </div>
                                    <div class="card-body">

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_bpjs_tkj" class="col-sm-5 col-form-label">BPJS Naker</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end"
                                                        id="modal_bpjs_tkj" name="bpjs_tkj" value="0"
                                                        min="0" step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_bpjs_kes" class="col-sm-5 col-form-label">BPJS
                                                Kesehatan</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end"
                                                        id="modal_bpjs_kes" name="bpjs_kes" value="0"
                                                        min="0" step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_iuran_koperasi" class="col-sm-5 col-form-label">Iuran Wajib
                                                Koperasi</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end"
                                                        id="modal_iuran_koperasi" name="iuran_koperasi" value="0"
                                                        min="0" step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_tps_kry" class="col-sm-5 col-form-label">Tabungan
                                                Pensiun</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end"
                                                        id="modal_tps_kry" name="tps_kry" value="0" min="0"
                                                        step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_pjk_pkp" class="col-sm-5 col-form-label">Pajak PKP</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end"
                                                        id="modal_pjk_pkp" name="pjk_pkp" value="0" min="0"
                                                        step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_pjk_pph" class="col-sm-5 col-form-label">Pajak PPh</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end"
                                                        id="modal_pjk_pph" name="pjk_pph" value="0" min="0"
                                                        step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_ptg_thr" class="col-sm-5 col-form-label">Potongan
                                                THR</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end"
                                                        id="modal_ptg_thr" name="ptg_thr" value="0" min="0"
                                                        step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_pjm_kop" class="col-sm-5 col-form-label">Pinjaman
                                                Koperasi</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end"
                                                        id="modal_pjm_kop" name="pjm_kop" value="0" min="0"
                                                        step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label for="modal_dda_sanksi" class="col-sm-5 col-form-label">Denda
                                                Sanksi</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" class="form-control text-end"
                                                        id="modal_dda_sanksi" name="dda_sanksi" value="0"
                                                        min="0" step="100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label class="col-sm-5 col-form-label fw-bold">Jumlah Potongan</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" class="form-control fw-bold text-end"
                                                        id="modal_total_potongan" readonly>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>{{-- end row utama --}}

                        <!-- ===== RINGKASAN GAJI ===== -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-calculator me-2"></i>Ringkasan Gaji
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Total Pendapatan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-end"
                                                            id="modal_total_pendapatan" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Total Potongan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-end"
                                                            id="modal_total_potongan_summary" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold">Gaji Diterima</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="text" class="form-control fw-bold text-end"
                                                            id="modal_gaji_bersih" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== BEBAN TANGGUNGAN PERUSAHAAN ===== -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-building me-2"></i>Beban Tanggungan
                                            Perusahaan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="modal_bpjs_tkj_prs" class="form-label fw-bold">BPJS
                                                        Naker</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_bpjs_tkj_prs" name="bpjs_tkj_prs" value="0"
                                                            min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="modal_bpjs_kes_prs" class="form-label fw-bold">BPJS
                                                        Kes</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_bpjs_kes_prs" name="bpjs_kes_prs" value="0"
                                                            min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="modal_tps_prs" class="form-label fw-bold">Tabungan
                                                        Pensiun</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_tps_prs" name="tps_prs" value="0"
                                                            min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="modal_askes_prs" class="form-label fw-bold">Asuransi
                                                        Kesehatan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control text-end"
                                                            id="modal_askes_prs" name="askes_prs" value="0"
                                                            min="0" step="100" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== STATUS DATA GAJI ===== -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-secondary">
                                    <div class="card-header bg-secondary bg-opacity-25">
                                        <h5 class="mb-0 text-white"><i class="fas fa-info-circle me-2"></i>Status Data
                                            Gaji</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="modal_sts_data_gaji"
                                                        class="form-label fw-bold">Status</label>
                                                    <input type="text" class="form-control" id="modal_sts_data_gaji"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="modal_tgl_na_gaji" class="form-label fw-bold">Tgl Non
                                                        Aktif</label>
                                                    <input type="text" class="form-control" id="modal_tgl_na_gaji"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="modal_ket_na_gaji"
                                                        class="form-label fw-bold">Keterangan</label>
                                                    <textarea class="form-control" id="modal_ket_na_gaji" rows="1" readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
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
        .card {
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            margin-bottom: 0.3rem;
            font-size: 0.85rem;
        }

        .form-control-plaintext {
            background: none;
            border: none;
            padding: 0.375rem 0;
            font-size: 0.95rem;
        }

        #modal_total_pendapatan_tetap,
        #modal_total_pendapatan_tidak_tetap,
        #modal_total_potongan {
            text-align: right;
            padding-right: 24px !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let currentSalaryId = null;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('[data-bs-toggle="tooltip"]').tooltip();

            // ===== FORMAT RUPIAH =====
            function formatRupiah(num) {
                return new Intl.NumberFormat('id-ID').format(Math.round(num || 0));
            }

            function getVal(id) {
                return parseFloat($('#' + id).val() || 0) || 0;
            }

            function hitungSemua() {
                const tetap = getVal('modal_gj_pokok') + getVal('modal_tunjab') + getVal('modal_tunkom') +
                    getVal('modal_fot') + getVal('modal_tunmal');

                const tidakTetap = getVal('modal_lbr_harian') + getVal('modal_lbr_perjam') + getVal('modal_tukin') +
                    getVal('modal_insentif') + getVal('modal_bonus') + getVal('modal_thr');

                const potongan = getVal('modal_bpjs_tkj') + getVal('modal_bpjs_kes') + getVal(
                        'modal_iuran_koperasi') +
                    getVal('modal_tps_kry') + getVal('modal_pjk_pkp') + getVal('modal_pjk_pph') +
                    getVal('modal_ptg_thr') + getVal('modal_pjm_kop') + getVal('modal_dda_sanksi');

                const totalPendapatan = tetap + tidakTetap;
                const gajiBersih = totalPendapatan - potongan;

                $('#modal_total_pendapatan_tetap').val(formatRupiah(tetap));
                $('#modal_total_pendapatan_tidak_tetap').val(formatRupiah(tidakTetap));
                $('#modal_total_potongan').val(formatRupiah(potongan));
                $('#modal_total_pendapatan').val(formatRupiah(totalPendapatan));
                $('#modal_total_potongan_summary').val(formatRupiah(potongan));
                $('#modal_gaji_bersih').val(formatRupiah(gajiBersih));
            }

            // ===== VIEW SALARY DETAIL =====
            $(document).on('click', '.view-salary-btn', function() {
                const salaryId = $(this).data('salary-id');
                currentSalaryId = salaryId;
                resetSalaryModal();
                loadSalaryDataToModal(salaryId);
                $('#salaryModal').modal('show');
            });

            // ===== HELPER FUNCTIONS =====
            function resetSalaryModal() {
                $('#salary_id').val('');
                $('#salaryModal input[type="number"]').val(0);
                hitungSemua();
            }

            function loadSalaryDataToModal(salaryId) {
                $.ajax({
                    url: `/data-gaji/salaries/${salaryId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const salary = response.data;

                            $('#salary_id').val(salary.id);
                            $('#modal_gj_pokok').val(salary.gj_pokok || 0);
                            $('#modal_tunjab').val(salary.tunjab || 0);
                            $('#modal_tunkom').val(salary.tunkom || 0);
                            $('#modal_fot').val(salary.fot || 0);
                            $('#modal_tunmal').val(salary.tunmal || 0);

                            $('#modal_lbr_harian').val(salary.lbr_harian || 0);
                            $('#modal_lbr_perjam').val(salary.lbr_perjam || 0);
                            $('#modal_tukin').val(salary.tukin || 0);
                            $('#modal_insentif').val(salary.insentif || 0);
                            $('#modal_bonus').val(salary.bonus || 0);
                            $('#modal_thr').val(salary.thr || 0);

                            $('#modal_bpjs_tkj').val(salary.bpjs_tkj || 0);
                            $('#modal_bpjs_kes').val(salary.bpjs_kes || 0);
                            $('#modal_iuran_koperasi').val(salary.iuran_koperasi || 0);
                            $('#modal_tps_kry').val(salary.tps_kry || 0);
                            $('#modal_pjk_pkp').val(salary.pjk_pkp || 0);
                            $('#modal_pjk_pph').val(salary.pjk_pph || 0);
                            $('#modal_ptg_thr').val(salary.ptg_thr || 0);
                            $('#modal_pjm_kop').val(salary.pjm_kop || 0);
                            $('#modal_dda_sanksi').val(salary.dda_sanksi || 0);

                            $('#modal_bpjs_tkj_prs').val(salary.bpjs_tkj_prs || 0);
                            $('#modal_bpjs_kes_prs').val(salary.bpjs_kes_prs || 0);
                            $('#modal_tps_prs').val(salary.tps_prs || 0);
                            $('#modal_askes_prs').val(salary.askes_prs || 0);

                            $('#modal_sts_data_gaji').val(salary.sts_data_gaji || 'AKTIF');
                            $('#modal_tgl_na_gaji').val(salary.tgl_na_gaji || '');
                            $('#modal_ket_na_gaji').val(salary.ket_na_gaji || '');

                            hitungSemua();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal memuat data gaji.',
                            icon: 'error'
                        });
                    }
                });
            }

            $('#salaryModal').on('hidden.bs.modal', function() {
                resetSalaryModal();
            });
        });
    </script>
@endpush
