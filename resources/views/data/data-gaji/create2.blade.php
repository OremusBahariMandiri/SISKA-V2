@extends('layouts.app')

@section('title', 'Tambah Data Gaji')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-money-bill-wave me-2"></i>Tambah Data Gaji</span>
                        <a href="{{ route('data-gaji.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-exclamation-triangle me-2"></i>Terdapat kesalahan:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('data-gaji.store') }}" method="POST" id="dataGajiForm" novalidate>
                            @csrf
                            <input type="hidden" id="id_kode" name="id_kode" value="{{ old('id_kode', $newId) }}">

                            <!-- Nav Tabs -->
                            <ul class="nav nav-tabs mb-4" id="formTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="karyawan-tab" data-bs-toggle="tab"
                                        data-bs-target="#karyawan" type="button" role="tab" aria-selected="true">
                                        <i class="fas fa-user me-1"></i> Data Karyawan
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="data-gaji-tab" data-bs-toggle="tab"
                                        data-bs-target="#data-gaji" type="button" role="tab" aria-selected="false">
                                        <i class="fas fa-money-bill-wave me-1"></i> Data Gaji
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pendidikan-tab" data-bs-toggle="tab"
                                        data-bs-target="#pendidikan" type="button" role="tab" aria-selected="false">
                                        <i class="fas fa-graduation-cap me-1"></i> Pendidikan
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="karir-tab" data-bs-toggle="tab"
                                        data-bs-target="#karir" type="button" role="tab" aria-selected="false">
                                        <i class="fas fa-briefcase me-1"></i> Jenjang Karir
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="hubin-tab" data-bs-toggle="tab"
                                        data-bs-target="#hubin" type="button" role="tab" aria-selected="false">
                                        <i class="fas fa-user-check me-1"></i> Hubungan Industrial
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content">

                                <!-- ===== TAB 1: DATA KARYAWAN ===== -->
                                <div class="tab-pane fade show active" id="karyawan" role="tabpanel">
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i class="fas fa-user me-2"></i>Pilih Data Karyawan</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="id_karyawan" class="form-label fw-bold">Karyawan <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                            <div style="flex:1">
                                                                <select class="form-select select2" id="id_karyawan" name="id_karyawan" data-required="true">
                                                                    <option value="">Pilih Karyawan</option>
                                                                    @foreach ($karyawans as $karyawan)
                                                                        <option value="{{ $karyawan->id }}"
                                                                            {{ old('id_karyawan') == $karyawan->id ? 'selected' : '' }}>
                                                                            {{ $karyawan->nama }} - {{ $karyawan->nrk ?? 'NRK Belum Ada' }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Employee Info Display -->
                                            <div id="employeeInfo" class="row" style="display:none;">
                                                <div class="col-md-12">
                                                    <div class="card bg-light border-0">
                                                        <div class="card-header bg-primary text-white">
                                                            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Karyawan</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <!-- Foto -->
                                                                <div class="col-md-3 text-center mb-4">
                                                                    <div class="employee-photo-container">
                                                                        <img id="emp_foto" src="" alt="Foto Karyawan"
                                                                            class="img-fluid rounded shadow employee-photo"
                                                                            style="display:none;">
                                                                        <div id="emp_foto_placeholder" class="default-avatar rounded shadow">
                                                                            <i class="fas fa-user-circle fa-8x text-secondary"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Info Karyawan -->
                                                                <div class="col-md-9">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">NIK</label>
                                                                                <input type="text" class="form-control" id="emp_nik" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">NRK</label>
                                                                                <input type="text" class="form-control" id="emp_nrk" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Jenis Kelamin</label>
                                                                                <input type="text" class="form-control" id="emp_sex" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Tempat Lahir</label>
                                                                                <input type="text" class="form-control" id="emp_tpt_lahir" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Tanggal Lahir</label>
                                                                                <input type="text" class="form-control" id="emp_tgl_lahir" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Telepon</label>
                                                                                <input type="text" class="form-control" id="emp_tlp1" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Status Kawin</label>
                                                                                <input type="text" class="form-control" id="emp_sts_nikah" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Jumlah Anak</label>
                                                                                <input type="text" class="form-control" id="emp_jml_anak" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label fw-bold">Email</label>
                                                                                <input type="text" class="form-control" id="emp_email" readonly>
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
                                </div>

                                <!-- ===== TAB 2: DATA GAJI ===== -->
                                <div class="tab-pane fade" id="data-gaji" role="tabpanel">

                                    <!-- Card: Pendapatan Tetap -->
                                    <div class="card border-success mb-4">
                                        <div class="card-header bg-success bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i class="fas fa-wallet me-2"></i>Pendapatan Tetap</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="gj_pokok" class="form-label fw-bold">Gaji Pokok</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="gj_pokok" name="gj_pokok"
                                                                value="{{ old('gj_pokok', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="tunjab" class="form-label fw-bold">Tunjangan Jabatan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="tunjab" name="tunjab"
                                                                value="{{ old('tunjab', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="tunkom" class="form-label fw-bold">Tunjangan Komunikasi</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="tunkom" name="tunkom"
                                                                value="{{ old('tunkom', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="fot" class="form-label fw-bold">Fix Over Time (FOT)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="fot" name="fot"
                                                                value="{{ old('fot', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="tunmal" class="form-label fw-bold">Tunjangan Kemahalan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="tunmal" name="tunmal"
                                                                value="{{ old('tunmal', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold text-success">Total Pendapatan Tetap</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="text" class="form-control fw-bold text-success" id="total_pendapatan_tetap" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card: Pendapatan Tidak Tetap -->
                                    <div class="card border-warning mb-4">
                                        <div class="card-header bg-warning bg-opacity-25">
                                            <h5 class="mb-0 text-dark"><i class="fas fa-coins me-2"></i>Pendapatan Tidak Tetap</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="lbr_harian" class="form-label fw-bold">Lembur Harian</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="lbr_harian" name="lbr_harian"
                                                                value="{{ old('lbr_harian', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="lbr_perjam" class="form-label fw-bold">Lembur Per Jam</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="lbr_perjam" name="lbr_perjam"
                                                                value="{{ old('lbr_perjam', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="tukin" class="form-label fw-bold">Tunjangan Kinerja</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="tukin" name="tukin"
                                                                value="{{ old('tukin', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="insentif" class="form-label fw-bold">Insentif</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="insentif" name="insentif"
                                                                value="{{ old('insentif', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="bonus" class="form-label fw-bold">Bonus</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="bonus" name="bonus"
                                                                value="{{ old('bonus', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="thr" class="form-label fw-bold">Tunjangan Hari Raya (THR)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="thr" name="thr"
                                                                value="{{ old('thr', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold text-warning">Total Pendapatan Tidak Tetap</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="text" class="form-control fw-bold text-warning" id="total_pendapatan_tidak_tetap" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card: Potongan -->
                                    {{-- PERUBAHAN: nama field disesuaikan dengan migration --}}
                                    <div class="card border-danger mb-4">
                                        <div class="card-header bg-danger bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i class="fas fa-minus-circle me-2"></i>Potongan</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                {{-- bpjs_tkj_kry → bpjs_tkj --}}
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="bpjs_tkj" class="form-label fw-bold">BPJS Tenaga Kerja Karyawan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="bpjs_tkj" name="bpjs_tkj"
                                                                value="{{ old('bpjs_tkj', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- bpjs_kes_kry → bpjs_kes --}}
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="bpjs_kes" class="form-label fw-bold">BPJS Kesehatan Karyawan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="bpjs_kes" name="bpjs_kes"
                                                                value="{{ old('bpjs_kes', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- iuran_wjb_kop → iuran_koperasi --}}
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="iuran_koperasi" class="form-label fw-bold">Iuran Wajib Koperasi</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="iuran_koperasi" name="iuran_koperasi"
                                                                value="{{ old('iuran_koperasi', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="tps_kry" class="form-label fw-bold">Tabungan Pensiun Karyawan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="tps_kry" name="tps_kry"
                                                                value="{{ old('tps_kry', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="pjk_pkp" class="form-label fw-bold">Pajak PKP</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="pjk_pkp" name="pjk_pkp"
                                                                value="{{ old('pjk_pkp', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="pjk_pph" class="form-label fw-bold">Pajak PPh</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="pjk_pph" name="pjk_pph"
                                                                value="{{ old('pjk_pph', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="ptg_thr" class="form-label fw-bold">Potongan THR</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="ptg_thr" name="ptg_thr"
                                                                value="{{ old('ptg_thr', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="pjm_kop" class="form-label fw-bold">Pinjaman Koperasi</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="pjm_kop" name="pjm_kop"
                                                                value="{{ old('pjm_kop', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="dda_sanksi" class="form-label fw-bold">Denda Sanksi</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="dda_sanksi" name="dda_sanksi"
                                                                value="{{ old('dda_sanksi', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold text-danger">Total Potongan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="text" class="form-control fw-bold text-danger" id="total_potongan" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card: Beban Tanggungan Perusahaan -->
                                    <div class="card border-info mb-4">
                                        <div class="card-header bg-info bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i class="fas fa-building me-2"></i>Beban Tanggungan Perusahaan</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label for="bpjs_tkj_prs" class="form-label fw-bold">BPJS TK Perusahaan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="bpjs_tkj_prs" name="bpjs_tkj_prs"
                                                                value="{{ old('bpjs_tkj_prs', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label for="bpjs_kes_prs" class="form-label fw-bold">BPJS Kes Perusahaan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="bpjs_kes_prs" name="bpjs_kes_prs"
                                                                value="{{ old('bpjs_kes_prs', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label for="tps_prs" class="form-label fw-bold">Tabungan Pensiun Perusahaan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="tps_prs" name="tps_prs"
                                                                value="{{ old('tps_prs', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label for="askes_prs" class="form-label fw-bold">Asuransi Kesehatan Perusahaan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control" id="askes_prs" name="askes_prs"
                                                                value="{{ old('askes_prs', 0) }}" min="0" step="100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card: Ringkasan Gaji -->
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i class="fas fa-calculator me-2"></i>Ringkasan Gaji</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold text-success">Total Pendapatan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="text" class="form-control fw-bold text-success" id="total_pendapatan" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold text-danger">Total Potongan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="text" class="form-control fw-bold text-danger" id="total_potongan_summary" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold text-primary">Gaji Bersih (Take Home Pay)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="text" class="form-control fw-bold text-primary fs-5" id="gaji_bersih" readonly>
                                                        </div>
                                                    </div>
                                                </div>
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
                                            <div class="alert alert-info" role="alert">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Data berikut akan otomatis terisi berdasarkan karyawan yang dipilih.
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Jenjang Pendidikan</label>
                                                        <input type="text" class="form-control" id="pend_jenjang_skl" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tanggal Lulus</label>
                                                        <input type="text" class="form-control" id="pend_tgl_lulus_skl" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Nama Institusi</label>
                                                        <input type="text" class="form-control" id="pend_institusi_skl" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Fakultas</label>
                                                        <input type="text" class="form-control" id="pend_fakultas_skl" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Jurusan</label>
                                                        <input type="text" class="form-control" id="pend_jurusan_skl" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Kota</label>
                                                        <input type="text" class="form-control" id="pend_kota_skl" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Gelar</label>
                                                        <input type="text" class="form-control" id="pend_gelar_skl" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===== TAB 4: JENJANG KARIR ===== -->
                                <div class="tab-pane fade" id="karir" role="tabpanel">
                                    <div class="card border-warning mb-4">
                                        <div class="card-header bg-warning bg-opacity-25">
                                            <h5 class="mb-0 text-dark"><i class="fas fa-briefcase me-2"></i>Jenjang Karir Saat Ini</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info" role="alert">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Data berikut akan otomatis terisi berdasarkan karyawan yang dipilih.
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Departemen</label>
                                                        <input type="text" class="form-control" id="karir_departemen_nama" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Singkatan Dep.</label>
                                                        <input type="text" class="form-control" id="karir_skt_dep" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Jabatan</label>
                                                        <input type="text" class="form-control" id="karir_jabatan_nama" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Singkatan Jbt.</label>
                                                        <input type="text" class="form-control" id="karir_skt_jbt" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Wilayah Kerja</label>
                                                        <input type="text" class="form-control" id="karir_wilker_nama" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Unit Kerja</label>
                                                        <input type="text" class="form-control" id="karir_unit_krj_nama" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">SKT Wilayah Kerja</label>
                                                        <input type="text" class="form-control" id="karir_skt_wilker" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tugas & Tanggung Jawab</label>
                                                        <textarea class="form-control" id="karir_tugas" rows="4" readonly></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===== TAB 5: HUBUNGAN INDUSTRIAL ===== -->
                                <div class="tab-pane fade" id="hubin" role="tabpanel">
                                    <div class="card border-info mb-4">
                                        <div class="card-header bg-info bg-opacity-25">
                                            <h5 class="mb-0 text-white"><i class="fas fa-user-check me-2"></i>Hubungan Industrial</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info" role="alert">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Data berikut akan otomatis terisi berdasarkan karyawan yang dipilih.
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tanggal Masuk</label>
                                                        <input type="text" class="form-control" id="hubin_tgl_masuk" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Status Karyawan</label>
                                                        <input type="text" class="form-control" id="hubin_sts_kry" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Tanggal PHK</label>
                                                        <input type="text" class="form-control" id="hubin_tgl_phk" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">Keterangan PHK</label>
                                                        <input type="text" class="form-control" id="hubin_ket_phk" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <a href="{{ route('data-gaji.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                    <i class="fas fa-save me-2"></i> Simpan Data Gaji
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />

    <style>
        .card-header { font-weight: 600; }
        .form-label { margin-bottom: 0.3rem; }
        .card { margin-bottom: 1rem; transition: all 0.3s ease; }
        .card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.15); transform: translateY(-2px); }

        .employee-photo-container { position: relative; width: 100%; max-width: 200px; margin: 0 auto; }
        .employee-photo { width: 100%; height: auto; max-height: 250px; object-fit: cover; border: 3px solid #0d6efd; }
        .employee-photo.loaded { animation: photoFadeIn 0.5s ease-in-out; }
        @keyframes photoFadeIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
        .default-avatar { width: 100%; height: 200px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; border: 2px dashed #dee2e6; }

        .employee-validation-error {
            border: 2px solid #dc3545 !important; border-radius: 0.375rem !important;
            background-color: rgba(248,215,218,0.3); animation: shake 0.8s ease-in-out;
        }
        @keyframes shake { 0%,20%,40%,60%,80% { transform: translateX(0); } 10%,30%,50%,70%,90% { transform: translateX(-10px); } }

        .employee-validation-success {
            border: 2px solid #198754 !important; border-radius: 0.375rem !important;
            background-color: rgba(25,135,84,0.1); animation: successPulse 1s ease-in-out;
        }
        @keyframes successPulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(25,135,84,0.7); }
            50% { transform: scale(1.02); box-shadow: 0 0 0 10px rgba(25,135,84,0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(25,135,84,0); }
        }

        .employee-loading { position: relative; }
        .employee-loading::after {
            content: ''; position: absolute; top: 50%; right: 35px; transform: translateY(-50%);
            width: 20px; height: 20px; border: 2px solid #f3f3f3; border-top: 2px solid #0d6efd;
            border-radius: 50%; animation: spin 1s linear infinite; z-index: 10;
        }
        @keyframes spin { 0% { transform: translateY(-50%) rotate(0deg); } 100% { transform: translateY(-50%) rotate(360deg); } }
        .is-invalid { border-color: #dc3545 !important; background-color: rgba(220,53,69,0.05); }

        .select2-container--default .select2-selection--single {
            height: 38px; border: 1px solid #ced4da; border-radius: 0.375rem;
        }
        .input-group .select2-container { flex: 1 1 auto; width: 1%; min-width: 0; }
        .input-group .select2-container .select2-selection { border-top-left-radius: 0; border-bottom-left-radius: 0; border-left: 0; }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // =====================================================
            // SALARY CALCULATOR
            // =====================================================
            function formatRupiah(num) {
                return new Intl.NumberFormat('id-ID').format(Math.round(num || 0));
            }

            function getVal(id) {
                return parseFloat(document.getElementById(id)?.value || 0) || 0;
            }

            function hitungSemua() {
                const tetap = getVal('gj_pokok') + getVal('tunjab') + getVal('tunkom') + getVal('fot') + getVal('tunmal');

                const tidakTetap = getVal('lbr_harian') + getVal('lbr_perjam') + getVal('tukin') +
                                   getVal('insentif') + getVal('bonus') + getVal('thr');

                // Nama ID disesuaikan: bpjs_tkj, bpjs_kes, iuran_koperasi
                const potongan = getVal('bpjs_tkj') + getVal('bpjs_kes') + getVal('iuran_koperasi') +
                                 getVal('tps_kry') + getVal('pjk_pkp') + getVal('pjk_pph') +
                                 getVal('ptg_thr') + getVal('pjm_kop') + getVal('dda_sanksi');

                const totalPendapatan = tetap + tidakTetap;
                const gajiBersih      = totalPendapatan - potongan;

                document.getElementById('total_pendapatan_tetap').value       = formatRupiah(tetap);
                document.getElementById('total_pendapatan_tidak_tetap').value = formatRupiah(tidakTetap);
                document.getElementById('total_potongan').value               = formatRupiah(potongan);
                document.getElementById('total_pendapatan').value             = formatRupiah(totalPendapatan);
                document.getElementById('total_potongan_summary').value       = formatRupiah(potongan);
                document.getElementById('gaji_bersih').value                  = formatRupiah(gajiBersih);
            }

            document.querySelectorAll('#data-gaji input[type="number"]').forEach(function (el) {
                el.addEventListener('input', hitungSemua);
            });

            hitungSemua();

            // =====================================================
            // EMPLOYEE VALIDATION ALERTS
            // =====================================================
            function showSuccessValidation(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Validasi Berhasil!',
                    html: `
                        <div class="text-center">
                            <h5 class="text-success mb-3">${response.employee_name}</h5>
                            <p class="mb-2"><strong>NRK:</strong> ${response.employee_nrk || 'Belum ada'}</p>
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-check-circle me-2"></i>
                                Karyawan belum memiliki data gaji.<br>
                                <strong>Proses dapat dilanjutkan.</strong>
                            </div>
                        </div>`,
                    confirmButtonText: 'Lanjutkan',
                    confirmButtonColor: '#198754',
                    timer: 4000,
                    timerProgressBar: true,
                    showClass: { popup: 'animate__animated animate__bounceIn' }
                });
            }

            function showErrorValidation(response) {
                const latest = response.latest_gaji;
                Swal.fire({
                    icon: 'error',
                    title: 'Karyawan Sudah Terdaftar!',
                    html: `
                        <div class="text-start">
                            <p><strong>Nama:</strong> ${response.employee_name}</p>
                            <p><strong>NRK:</strong> ${response.employee_nrk || 'N/A'}</p>
                            <p><strong>Total Data Gaji:</strong> ${response.existing_count} record</p>
                            <hr>
                            <p class="mb-2"><strong>Data Gaji Terakhir:</strong></p>
                            <ul class="list-unstyled ms-3">
                                <li>• <strong>ID Gaji:</strong> ${latest.id_gaji}</li>
                                <li>• <strong>Gaji Pokok:</strong> Rp ${new Intl.NumberFormat('id-ID').format(latest.gj_pokok)}</li>
                            </ul>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Tidak dapat membuat data gaji baru!</strong><br>
                                Gunakan fitur <strong>"Edit"</strong> pada data yang sudah ada.
                            </div>
                        </div>`,
                    confirmButtonText: 'Pilih Karyawan Lain',
                    confirmButtonColor: '#dc3545',
                    showCancelButton: true,
                    cancelButtonText: 'Lihat Data Gaji',
                    cancelButtonColor: '#0d6efd',
                    allowOutsideClick: false,
                    width: '600px',
                    showClass: { popup: 'animate__animated animate__shakeX' }
                }).then((result) => {
                    if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = `/data-gaji?filter_nama=${encodeURIComponent(response.employee_name)}`;
                    }
                });
            }

            async function checkEmployeeExists(karyawanId) {
                try {
                    $('#id_karyawan').closest('.input-group').addClass('employee-loading');
                    const response = await $.ajax({
                        url: `/data-gaji/check-employee/${karyawanId}`,
                        type: 'GET', dataType: 'json', timeout: 10000
                    });
                    $('#id_karyawan').closest('.input-group').removeClass('employee-loading');

                    if (response.success === true && response.exists === false) {
                        showSuccessValidation(response);
                        $('#id_karyawan').closest('.input-group').find('.form-select').addClass('employee-validation-success');
                        setTimeout(() => {
                            $('#id_karyawan').closest('.input-group').find('.form-select').removeClass('employee-validation-success');
                        }, 4000);
                        return true;
                    } else if (response.success === false && response.exists === true) {
                        showErrorValidation(response);
                        $('#id_karyawan').closest('.input-group').find('.form-select').addClass('employee-validation-error');
                        setTimeout(() => {
                            $('#id_karyawan').closest('.input-group').find('.form-select').removeClass('employee-validation-error');
                        }, 3000);
                        $('#id_karyawan').val('').trigger('change.select2');
                        clearAllEmployeeFields();
                        return false;
                    }
                } catch (error) {
                    $('#id_karyawan').closest('.input-group').removeClass('employee-loading');
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Sudah Terdaftar Dalam Sistem',
                        text: 'Silahkan cari pada halaman utama dan lakukan edit untuk melakukan perubahan data',
                        confirmButtonText: 'OK'
                    });
                    $('#id_karyawan').val('').trigger('change.select2');
                    clearAllEmployeeFields();
                    return false;
                }
            }

            // =====================================================
            // LOAD EMPLOYEE DATA
            // =====================================================
            async function loadEmployeeData(karyawanId) {
                $('#employeeInfo').show();
                const loading = 'Loading...';
                $('#emp_nik, #emp_nrk, #emp_sex, #emp_tpt_lahir, #emp_tgl_lahir, #emp_tlp1, #emp_sts_nikah, #emp_jml_anak, #emp_email').val(loading);
                $('#hubin_tgl_masuk, #hubin_sts_kry, #hubin_tgl_phk, #hubin_ket_phk').val(loading);

                try {
                    const response = await $.ajax({
                        url: `/data-gaji/get-employee-data/${karyawanId}`,
                        type: 'GET', dataType: 'json', timeout: 15000
                    });

                    if (response.success && response.data) {
                        const emp = response.data;

                        $('#emp_nik').val(emp.nik || '-');
                        $('#emp_nrk').val(emp.nrk || '-');
                        $('#emp_sex').val(emp.sex || '-');
                        $('#emp_tpt_lahir').val(emp.tpt_lahir || '-');
                        $('#emp_tgl_lahir').val(emp.tgl_lahir_formatted || '-');
                        $('#emp_tlp1').val(emp.tlp1 || '-');
                        $('#emp_sts_nikah').val(emp.sts_nikah || '-');
                        $('#emp_jml_anak').val(emp.jml_anak || '-');
                        $('#emp_email').val(emp.email1 || '-');

                        if (emp.foto_dokumen) {
                            $('#emp_foto').attr('src', `/storage/${emp.foto_dokumen}`).show().addClass('loaded');
                            $('#emp_foto_placeholder').hide();
                        } else {
                            $('#emp_foto').hide().removeClass('loaded');
                            $('#emp_foto_placeholder').show();
                        }
                        $('#emp_foto').off('error').on('error', function () {
                            $(this).hide().removeClass('loaded');
                            $('#emp_foto_placeholder').show();
                        });

                        $('#pend_jenjang_skl').val(emp.jenjang_skl || '-');
                        $('#pend_tgl_lulus_skl').val(emp.tgl_lulus_skl_formatted || '-');
                        $('#pend_institusi_skl').val(emp.institusi_skl || '-');
                        $('#pend_fakultas_skl').val(emp.fakultas_skl || '-');
                        $('#pend_jurusan_skl').val(emp.jurusan_skl || '-');
                        $('#pend_kota_skl').val(emp.kota_skl || '-');
                        $('#pend_gelar_skl').val(emp.gelar_skl || '-');

                        $('#karir_departemen_nama').val(emp.departemen || '-');
                        $('#karir_skt_dep').val(emp.skt_dep || '-');
                        $('#karir_jabatan_nama').val(emp.jabatan || '-');
                        $('#karir_skt_jbt').val(emp.skt_jbt || '-');
                        $('#karir_wilker_nama').val(emp.wilker || '-');
                        $('#karir_unit_krj_nama').val(emp.unit_krj || '-');
                        $('#karir_skt_wilker').val(emp.skt_wil_krj || '-');
                        $('#karir_tugas').val(emp.tugas || '-');

                        $('#hubin_tgl_masuk').val(emp.tgl_masuk_formatted || '-');
                        $('#hubin_sts_kry').val(emp.sts_kry || '-');
                        $('#hubin_tgl_phk').val(emp.tgl_phk_formatted || '-');
                        $('#hubin_ket_phk').val(emp.ket_phk || '-');
                    }
                } catch (error) {
                    console.error('❌ Employee data loading error:', error);
                    $('#emp_nik, #emp_nrk, #emp_sex, #emp_tpt_lahir, #emp_tgl_lahir, #emp_tlp1, #emp_sts_nikah, #emp_jml_anak, #emp_email').val('Error');
                    $('#hubin_tgl_masuk, #hubin_sts_kry, #hubin_tgl_phk, #hubin_ket_phk').val('Error');
                }
            }

            function clearAllEmployeeFields() {
                $('#employeeInfo').hide();
                $('#emp_nik, #emp_nrk, #emp_sex, #emp_tpt_lahir, #emp_tgl_lahir, #emp_tlp1, #emp_sts_nikah, #emp_jml_anak, #emp_email').val('');
                $('#emp_foto').hide().attr('src', '').removeClass('loaded');
                $('#emp_foto_placeholder').show();
                $('#pend_jenjang_skl, #pend_tgl_lulus_skl, #pend_institusi_skl, #pend_fakultas_skl, #pend_jurusan_skl, #pend_kota_skl, #pend_gelar_skl').val('');
                $('#karir_departemen_nama, #karir_skt_dep, #karir_jabatan_nama, #karir_skt_jbt, #karir_wilker_nama, #karir_unit_krj_nama, #karir_skt_wilker, #karir_tugas').val('');
                $('#hubin_tgl_masuk, #hubin_sts_kry, #hubin_tgl_phk, #hubin_ket_phk').val('');
            }

            $('#id_karyawan').on('change', function () {
                const karyawanId = $(this).val();
                if (karyawanId) {
                    checkEmployeeExists(karyawanId).then(canProceed => {
                        if (canProceed) {
                            setTimeout(() => loadEmployeeData(karyawanId), 1000);
                        }
                    });
                } else {
                    clearAllEmployeeFields();
                }
            });

            // =====================================================
            // FORM VALIDATION
            // =====================================================
            document.getElementById('dataGajiForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const requiredFields = this.querySelectorAll('[data-required="true"]:not(:disabled)');
                let missingFields    = [];
                let firstInvalidField = null;

                this.querySelectorAll('[data-required="true"]').forEach(f => f.classList.remove('is-invalid'));

                requiredFields.forEach(function (field) {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        const label = field.closest('.form-group')?.querySelector('label')?.textContent?.replace('*', '').trim() || field.name;
                        missingFields.push(label);
                        if (!firstInvalidField) firstInvalidField = field;
                    }
                });

                if (missingFields.length > 0) {
                    let fieldsList = '<ul class="text-start mb-0">';
                    missingFields.forEach(f => fieldsList += '<li>' + f + '</li>');
                    fieldsList += '</ul>';

                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        html: '<p class="mb-2">Mohon lengkapi field berikut yang wajib diisi:</p>' + fieldsList,
                        confirmButtonText: 'OK, Saya Mengerti',
                        confirmButtonColor: '#0d6efd',
                    });

                    if (firstInvalidField) {
                        const tabPane = firstInvalidField.closest('.tab-pane');
                        if (tabPane) {
                            const tabButton = document.querySelector(`[data-bs-target="#${tabPane.id}"]`);
                            if (tabButton) {
                                new bootstrap.Tab(tabButton).show();
                                setTimeout(() => {
                                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    firstInvalidField.focus();
                                }, 300);
                            }
                        }
                    }
                    return false;
                }

                const submitBtn = document.getElementById('submitBtn');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
                submitBtn.disabled  = true;
                this.submit();
            });

            $('.select2').select2({ theme: 'bootstrap-5' });
        });
    </script>
@endpush