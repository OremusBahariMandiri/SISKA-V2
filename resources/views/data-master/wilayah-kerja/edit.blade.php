@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-map-marker-edit me-2"></i>Edit Wilayah Kerja</span>
                        <a href="{{ route('wilayah-kerja.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('wilayah-kerja.update', $wilayahKerja->id) }}" method="POST" id="wilayahKerjaForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Grouped form sections with cards -->
                            <div class="row g-4">
                                <!-- Informasi Wilayah -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-secondary">
                                        <div class="card-header bg-secondary bg-opacity-25 text-white">
                                            <h5 class="mb-0"><i class="fas fa-map-marked-alt me-2"></i>Informasi Wilayah</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="kode_wk" class="form-label fw-bold">Kode Wilayah Kerja <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                    <input type="text" class="form-control auto-uppercase" id="kode_wk"
                                                        name="kode_wk" value="{{ old('kode_wk', $wilayahKerja->kode_wk) }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="wilayah_krj" class="form-label fw-bold">Wilayah Kerja <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                    <input type="text" class="form-control auto-uppercase" id="wilayah_krj"
                                                        name="wilayah_krj" value="{{ old('wilayah_krj', $wilayahKerja->wilayah_krj) }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="skt_wilker" class="form-label fw-bold">SKT Wilayah Kerja <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                    <input type="text" class="form-control auto-uppercase" id="skt_wilker"
                                                        name="skt_wilker" value="{{ old('skt_wilker', $wilayahKerja->skt_wilker) }}" required>
                                                </div>
                                            </div>


                                            <div class="form-group mb-3">
                                                <label for="area_krj" class="form-label fw-bold">Area Kerja <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-location-dot"></i></span>
                                                    <input type="text" class="form-control auto-uppercase" id="area_krj"
                                                        name="area_krj" value="{{ old('area_krj', $wilayahKerja->area_krj) }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="singkatan_wk" class="form-label fw-bold">Singkatan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                    <input type="text" class="form-control auto-uppercase"
                                                        id="singkatan_wk" name="singkatan_wk" value="{{ old('singkatan_wk', $wilayahKerja->singkatan_wk) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="alamat_wk" class="form-label fw-bold">Alamat <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                    <textarea class="form-control auto-uppercase" id="alamat_wk" name="alamat_wk" rows="3" required>{{ old('alamat_wk', $wilayahKerja->alamat_wk) }}</textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="rt_rw_wk" class="form-label fw-bold">RT/RW</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-home"></i></span>
                                                            <input type="text" class="form-control" id="rt_rw_wk" name="rt_rw_wk" value="{{ old('rt_rw_wk', $wilayahKerja->rt_rw_wk) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kel_wk" class="form-label fw-bold">Kelurahan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                            <input type="text" class="form-control auto-uppercase" id="kel_wk" name="kel_wk" value="{{ old('kel_wk', $wilayahKerja->kel_wk) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kec_wk" class="form-label fw-bold">Kecamatan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                            <input type="text" class="form-control auto-uppercase" id="kec_wk" name="kec_wk" value="{{ old('kec_wk', $wilayahKerja->kec_wk) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kd_pos_wk" class="form-label fw-bold">Kode Pos</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                                            <input type="number" class="form-control" id="kd_pos_wk" name="kd_pos_wk" value="{{ old('kd_pos_wk', $wilayahKerja->kd_pos_wk) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="kota_wk" class="form-label fw-bold">Kota <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-city"></i></span>
                                                            <input type="text" class="form-control auto-uppercase" id="kota_wk" name="kota_wk" value="{{ old('kota_wk', $wilayahKerja->kota_wk) }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="prov_wk" class="form-label fw-bold">Provinsi <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
                                                            <input type="text" class="form-control auto-uppercase" id="prov_wk" name="prov_wk" value="{{ old('prov_wk', $wilayahKerja->prov_wk) }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kontak & Dokumen -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-secondary">
                                        <div class="card-header bg-secondary bg-opacity-25 text-white">
                                            <h5 class="mb-0"><i class="fas fa-phone me-2"></i>Kontak & Dokumen</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="tlp1" class="form-label fw-bold">Telepon <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    <input type="number" class="form-control" id="tlp1" name="tlp1" value="{{ old('tlp1', $wilayahKerja->tlp1) }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="tlp2" class="form-label fw-bold">Telepon 2</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                                    <input type="number" class="form-control" id="tlp2" name="tlp2" value="{{ old('tlp2', $wilayahKerja->tlp2) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="email1" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    <input type="email" class="form-control" id="email1" name="email1" value="{{ old('email1', $wilayahKerja->email1) }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="email2" class="form-label fw-bold">Email 2</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    <input type="email" class="form-control" id="email2" name="email2" value="{{ old('email2', $wilayahKerja->email2) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="instagram" class="form-label fw-bold">Instagram</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                                    <input type="text" class="form-control" id="instagram" name="instagram" value="{{ old('instagram', $wilayahKerja->instagram) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="facebook" class="form-label fw-bold">Facebook</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                                    <input type="text" class="form-control" id="facebook" name="facebook" value="{{ old('facebook', $wilayahKerja->facebook) }}">
                                                </div>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="foto_dokumen" class="form-label fw-bold">Foto/Dokumen</label>

                                                @if ($wilayahKerja->foto_dokumen)
                                                    <div class="mb-2">
                                                        <small class="text-info">
                                                            <i class="fas fa-info-circle"></i> File saat ini:
                                                            <a href="{{ Storage::url($wilayahKerja->foto_dokumen) }}" target="_blank" class="text-decoration-none">
                                                                {{ basename($wilayahKerja->foto_dokumen) }}
                                                            </a>
                                                        </small>
                                                    </div>
                                                @endif

                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-upload"></i></span>
                                                    <input type="file" class="form-control" id="foto_dokumen" name="foto_dokumen"
                                                           accept=".jpeg,.jpg,.png,.gif,.pdf,.doc,.docx">
                                                </div>
                                                <small class="text-muted">
                                                    Format yang didukung: JPEG, PNG, GIF, PDF, DOC, DOCX
                                                    @if ($wilayahKerja->foto_dokumen)
                                                        <br>Kosongkan jika tidak ingin mengubah file.
                                                    @endif
                                                </small>
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
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation with visual feedback
            const form = document.getElementById('wilayahKerjaForm');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();

                    // Highlight missing required fields
                    document.querySelectorAll('[required]').forEach(function(input) {
                        if (!input.value) {
                            input.classList.add('is-invalid');
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
            document.querySelectorAll('input, select, textarea').forEach(function(input) {
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
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
    </script>
@endpush