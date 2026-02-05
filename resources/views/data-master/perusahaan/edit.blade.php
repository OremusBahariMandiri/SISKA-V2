@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-building-edit me-2"></i>Edit Perusahaan</span>
                        <a href="{{ route('perusahaan.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('perusahaan.update', $perusahaan->id) }}" method="POST" id="perusahaanForm">
                            @csrf
                            @method('PUT')

                            <!-- Grouped form sections with cards -->
                            <div class="row g-4">
                                <!-- Informasi Perusahaan -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-secondary">
                                        <div class="card-header bg-secondary bg-opacity-25 text-white">
                                            <h5 class="mb-0"><i class="fas fa-building me-2"></i>Informasi Perusahaan</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="kode_prs" class="form-label fw-bold">Kode Perusahaan <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <input type="text" class="form-control auto-uppercase" id="kode_prs"
                                                        name="kode_prs" value="{{ old('kode_prs', $perusahaan->kode_prs) }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="nama_prs1" class="form-label fw-bold">Nama Perusahaan <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <input type="text" class="form-control auto-uppercase" id="nama_prs1"
                                                        name="nama_prs1" value="{{ old('nama_prs1', $perusahaan->nama_prs1) }}"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="nama_prs2" class="form-label fw-bold">Singkatan Perusahaan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-house"></i></span>
                                                    <input type="text" class="form-control auto-uppercase" id="nama_prs2"
                                                        name="nama_prs2" value="{{ old('nama_prs2', $perusahaan->nama_prs2) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="alamat_prs" class="form-label fw-bold">Alamat <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-map-marker-alt"></i></span>
                                                    <textarea class="form-control auto-uppercase" id="alamat_prs" name="alamat_prs" rows="3" required>{{ old('alamat_prs', $perusahaan->alamat_prs) }}</textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="rt_rw_prs" class="form-label fw-bold">RT/RW</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-home"></i></span>
                                                            <input type="text" class="form-control" id="rt_rw_prs" name="rt_rw_prs" value="{{ old('rt_rw_prs', $perusahaan->rt_rw_prs) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kel_prs" class="form-label fw-bold">Kelurahan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                            <input type="text" class="form-control auto-uppercase" id="kel_prs" name="kel_prs" value="{{ old('kel_prs', $perusahaan->kel_prs) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kec_prs" class="form-label fw-bold">Kecamatan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                            <input type="text" class="form-control auto-uppercase" id="kec_prs" name="kec_prs" value="{{ old('kec_prs', $perusahaan->kec_prs) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kd_pos_prs" class="form-label fw-bold">Kode Pos</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                                            <input type="number" class="form-control" id="kd_pos_prs" name="kd_pos_prs" value="{{ old('kd_pos_prs', $perusahaan->kd_pos_prs) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kota_prs" class="form-label fw-bold">Kota <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-city"></i></span>
                                                            <input type="text" class="form-control auto-uppercase" id="kota_prs" name="kota_prs" value="{{ old('kota_prs', $perusahaan->kota_prs) }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="prov_prs" class="form-label fw-bold">Provinsi <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
                                                            <input type="text" class="form-control auto-uppercase" id="prov_prs" name="prov_prs" value="{{ old('prov_prs', $perusahaan->prov_prs) }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kontak & Bisnis -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-secondary">
                                        <div class="card-header bg-secondary bg-opacity-25 text-white">
                                            <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Kontak & Informasi Bisnis</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="tlp1" class="form-label fw-bold">Telepon <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    <input type="number" class="form-control" id="tlp1" name="tlp1" value="{{ old('tlp1', $perusahaan->tlp1) }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="tlp2" class="form-label fw-bold">Telepon 2</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                                    <input type="number" class="form-control" id="tlp2" name="tlp2" value="{{ old('tlp2', $perusahaan->tlp2) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="email1" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    <input type="email" class="form-control" id="email1" name="email1" value="{{ old('email1', $perusahaan->email1) }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="email2" class="form-label fw-bold">Email 2</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    <input type="email" class="form-control" id="email2" name="email2" value="{{ old('email2', $perusahaan->email2) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="instagram" class="form-label fw-bold">Instagram</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                                    <input type="text" class="form-control" id="instagram" name="instagram" value="{{ old('instagram', $perusahaan->instagram) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="facebook" class="form-label fw-bold">Facebook</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                                    <input type="text" class="form-control" id="facebook" name="facebook" value="{{ old('facebook', $perusahaan->facebook) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="web" class="form-label fw-bold">Website</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                    <input type="text" class="form-control" id="web" name="web" value="{{ old('web', $perusahaan->web) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="tgl_pendirian" class="form-label fw-bold">Tanggal Pendirian</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                    <input type="date" class="form-control" id="tgl_pendirian" name="tgl_pendirian"
                                                        value="{{ old('tgl_pendirian', $perusahaan->tgl_pendirian ? $perusahaan->tgl_pendirian->format('Y-m-d') : '') }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="bidang_ush" class="form-label fw-bold">Bidang Usaha <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-industry"></i></span>
                                                    <input type="text" class="form-control auto-uppercase" id="bidang_ush" name="bidang_ush" value="{{ old('bidang_ush', $perusahaan->bidang_ush) }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="ijin_ush" class="form-label fw-bold">Izin Usaha</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                    <input type="text" class="form-control auto-uppercase" id="ijin_ush" name="ijin_ush" value="{{ old('ijin_ush', $perusahaan->ijin_ush) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="golongan_ush" class="form-label fw-bold">Golongan Usaha</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                                    <div style="flex: 1">
                                                        <select class="form-select select2" id="golongan_ush" name="golongan_ush">
                                                            <option value="" disabled>PILIH GOLONGAN USAHA</option>
                                                            <option value="-" {{ old('golongan_ush', $perusahaan->golongan_ush) == '-' ? 'selected' : '' }}>-</option>
                                                            <option value="MIKRO" {{ old('golongan_ush', $perusahaan->golongan_ush) == 'MIKRO' ? 'selected' : '' }}>MIKRO</option>
                                                            <option value="KECIL" {{ old('golongan_ush', $perusahaan->golongan_ush) == 'KECIL' ? 'selected' : '' }}>KECIL</option>
                                                            <option value="MENENGAH" {{ old('golongan_ush', $perusahaan->golongan_ush) == 'MENENGAH' ? 'selected' : '' }}>MENENGAH</option>
                                                            <option value="BESAR" {{ old('golongan_ush', $perusahaan->golongan_ush) == 'BESAR' ? 'selected' : '' }}>BESAR</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="dirut" class="form-label fw-bold">Direktur Utama <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                    <input type="text" class="form-control auto-uppercase" id="dirut" name="dirut" value="{{ old('dirut', $perusahaan->dirut) }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="direktur" class="form-label fw-bold">Direktur</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                    <input type="text" class="form-control auto-uppercase" id="direktur" name="direktur" value="{{ old('direktur', $perusahaan->direktur) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="komisaris_utm" class="form-label fw-bold">Komisaris Utama</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                    <input type="text" class="form-control auto-uppercase" id="komisaris_utm" name="komisaris_utm" value="{{ old('komisaris_utm', $perusahaan->komisaris_utm) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="komisaris1" class="form-label fw-bold">Komisaris</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                    <input type="text" class="form-control auto-uppercase" id="komisaris1" name="komisaris1" value="{{ old('komisaris1', $perusahaan->komisaris1) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 col-md-4 mx-auto mt-4">
                                <button type="submit" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-save me-2"></i>Update
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
    <style>
        .card-header {
            font-weight: 600;
        }

        .form-label {
            margin-bottom: 0.3rem;
        }

        .card {
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .text-danger {
            font-weight: bold;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .form-switch .form-check-input {
            width: 2.5em;
            height: 1.25em;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation with visual feedback
            const form = document.getElementById('perusahaanForm');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();

                    // Highlight missing required fields
                    document.querySelectorAll('[required]').forEach(function(input) {
                        if (!input.value) {
                            input.classList.add('is-invalid');
                            // Create error message if it doesn't exist
                            if (!input.nextElementSibling || !input.nextElementSibling.classList
                                .contains('invalid-feedback')) {
                                const feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback';
                                feedback.textContent = 'Field ini wajib diisi';
                                input.parentNode.insertBefore(feedback, input.nextElementSibling);
                            }
                        } else {
                            input.classList.remove('is-invalid');
                        }
                    });

                    // Scroll to first error
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstError.focus();
                    }
                }
            });

            // Remove invalid class when input changes
            document.querySelectorAll('input, select').forEach(function(input) {
                input.addEventListener('input', function() {
                    if (this.value) {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            // Validate email format
            const emailInputs = document.querySelectorAll('input[type="email"]');
            emailInputs.forEach(function(input) {
                input.addEventListener('blur', function() {
                    if (this.value && !isValidEmail(this.value)) {
                        this.classList.add('is-invalid');
                        // Create error message if it doesn't exist
                        if (!this.nextElementSibling || !this.nextElementSibling.classList
                            .contains('invalid-feedback')) {
                            const feedback = document.createElement('div');
                            feedback.className = 'invalid-feedback';
                            feedback.textContent = 'Format email tidak valid';
                            this.parentNode.insertBefore(feedback, this.nextElementSibling);
                        }
                    }
                });
            });
        });

        // Email validation function
        function isValidEmail(email) {
            const re =
                /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
    </script>
@endpush