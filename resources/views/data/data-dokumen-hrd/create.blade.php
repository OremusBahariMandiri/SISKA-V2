@extends('layouts.app')

@section('title', 'Tambah Data Dokumen HRD')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-contract me-2"></i>Tambah Data Dokumen HRD</span>
                        <a href="{{ route('data-dokumen-hrd.index') }}" class="btn btn-light btn-sm">
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
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('data-dokumen-hrd.store') }}" method="POST" id="dokumenHrdForm"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            <input type="hidden" name="id_kode" value="{{ old('id_kode', $newId) }}">

                            <!-- Card 1: Informasi Umum Dokumen -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-25">
                                    <h5 class="mb-0 text-white">
                                        <i class="fas fa-file-alt me-2"></i>Informasi Umum Dokumen
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- No Dokumen -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="no_dok_hrd" class="form-label fw-bold">No. Dokumen</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                    <input type="text" class="form-control auto-uppercase"
                                                        id="no_dok_hrd" name="no_dok_hrd" value="{{ old('no_dok_hrd') }}"
                                                        placeholder="Masukkan nomor dokumen">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="id_perusahaan" class="form-label fw-bold">Perusahaan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <div style="flex: 1">
                                                        <select class="form-select select2" id="id_perusahaan"
                                                            name="id_perusahaan">
                                                            <option value="">Pilih Perusahaan</option>
                                                            @foreach ($perusahaans as $perusahaan)
                                                                <option value="{{ $perusahaan->id }}"
                                                                    {{ old('id_perusahaan') == $perusahaan->id ? 'selected' : '' }}>
                                                                    {{ $perusahaan->nama_prs2 }} -
                                                                    {{ $perusahaan->nama_prs1 }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Kategori Dokumen -->
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="ktg_dok_hrd" class="form-label fw-bold">Kategori Dokumen
                                                    <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-folder"></i></span>
                                                    <div style="flex: 1">
                                                        <select class="form-select select2" id="ktg_dok_hrd"
                                                            name="ktg_dok_hrd" data-required="true">
                                                            <option value="">Pilih Kategori</option>
                                                            @php
                                                                $categories = collect($grouped)->keys();
                                                            @endphp
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category }}"
                                                                    {{ old('ktg_dok_hrd') == $category ? 'selected' : '' }}>
                                                                    {{ $category }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Jenis Dokumen -->
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="jns_dok_hrd" class="form-label fw-bold">Jenis Dokumen
                                                    <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                    <div style="flex: 1">
                                                        <select class="form-select select2" id="jns_dok_hrd"
                                                            name="jns_dok_hrd" data-required="true" disabled>
                                                            <option value="">Pilih Jenis Dokumen</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Kode Dokumen Display -->
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="kode_dok_hrd_display" class="form-label fw-bold">Kode
                                                    Dokumen
                                                    <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                    <input type="text" class="form-control bg-light"
                                                        id="kode_dok_hrd_display" placeholder="Otomatis terisi" readonly>
                                                </div>
                                                <div class="form-text text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>Kode otomatis berdasarkan
                                                    jenis dokumen
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Hidden field for id_dokumen_hrd -->
                                        <input type="hidden" id="id_dokumen_hrd" name="id_dokumen_hrd"
                                            value="{{ old('id_dokumen_hrd') }}">

                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="ket_dok_hrd" class="form-label fw-bold">Keterangan</label>
                                                <textarea class="form-control auto-uppercase" id="ket_dok_hrd" name="ket_dok_hrd" rows="3"
                                                    placeholder="Keterangan tambahan dokumen">{{ old('ket_dok_hrd') }}</textarea>
                                            </div>
                                        </div>


                                        <!-- Tanggal TTD -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="tgl_ttd" class="form-label fw-bold">Tanggal
                                                    TTD/Terbit</label>
                                                <input type="date" class="form-control" id="tgl_ttd" name="tgl_ttd"
                                                    value="{{ old('tgl_ttd') }}">
                                            </div>
                                        </div>
                                        <!-- File Dokumen -->
                                        <!-- File Dokumen PDF -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="file_dok" class="form-label fw-bold">File Dokumen
                                                    (PDF)</label>
                                                <input type="file" class="form-control" id="file_dok"
                                                    name="file_dok" accept=".pdf">
                                                <div class="form-text text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>Format: PDF
                                                </div>
                                            </div>
                                        </div>

                                        <!-- File Dokumen 2 (DOC/Excel) -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="file_dok_2" class="form-label fw-bold">File Dokumen
                                                    (DOC/Excel)</label>
                                                <input type="file" class="form-control" id="file_dok_2"
                                                    name="file_dok_2" accept=".doc,.docx,.xls,.xlsx">
                                                <div class="form-text text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>Format: DOC, DOCX, XLS, XLSX
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: Periode Dokumen -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-25">
                                    <h5 class="mb-0 text-white">
                                        <i class="fas fa-calendar-alt me-2"></i>Periode Dokumen
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Jenis Masa Berlaku -->
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="jns_msb_dok" class="form-label fw-bold">Jenis Masa
                                                    Berlaku</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-hourglass-half"></i></span>
                                                    <div style="flex: 1">
                                                        <select class="form-select select2" id="jns_msb_dok"
                                                            name="jns_msb_dok">
                                                            <option value="">Pilih Jenis Masa Berlaku</option>
                                                            <option value="TETAP"
                                                                {{ old('jns_msb_dok') == 'TETAP' ? 'selected' : '' }}>TETAP
                                                            </option>
                                                            <option value="PERPANJANGAN"
                                                                {{ old('jns_msb_dok') == 'PERPANJANGAN' ? 'selected' : '' }}>
                                                                PERPANJANGAN</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-text text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>Pilih TETAP untuk dokumen
                                                    tanpa masa berlaku
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tanggal Akhir Berlaku -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="tgl_akr_dok" class="form-label fw-bold">Tanggal Akhir
                                                    Berlaku</label>
                                                <input type="date" class="form-control" id="tgl_akr_dok"
                                                    name="tgl_akr_dok" value="{{ old('tgl_akr_dok') }}">
                                            </div>
                                        </div>

                                        <!-- Masa Berlaku (Bulan) -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="msb_dok" class="form-label fw-bold">Masa Berlaku
                                                    (Bulan)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    <input type="number" class="form-control" id="msb_dok"
                                                        name="msb_dok" value="{{ old('msb_dok') }}" min="1"
                                                        readonly>
                                                </div>
                                                <div class="form-text text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>Otomatis dihitung dari
                                                    tanggal TTD ke tanggal akhir berlaku
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tanggal Peringatan -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="tgl_prt_dok" class="form-label fw-bold">Tanggal
                                                    Peringatan</label>
                                                <input type="date" class="form-control" id="tgl_prt_dok"
                                                    name="tgl_prt_dok" value="{{ old('tgl_prt_dok') }}">
                                                <div class="form-text text-muted">
                                                    <i class="fas fa-bell me-1"></i>Tanggal untuk memulai pengingat
                                                    perpanjangan
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Durasi Peringatan (Hari) -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="durasi_pgt" class="form-label fw-bold">Durasi Peringatan
                                                    (Hari)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                                    <input type="number" class="form-control" id="durasi_pgt"
                                                        name="durasi_pgt" value="{{ old('durasi_pgt') }}" readonly>
                                                </div>
                                                <div class="form-text text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>Otomatis dihitung dari
                                                    tanggal sistem ke tanggal peringatan
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3: Keterangan Tambahan -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-25">
                                    <h5 class="mb-0 text-white">
                                        <i class="fas fa-comment-alt me-2"></i>Keterangan Tambahan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Keterangan -->
                                        <!-- Status Dokumen -->
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="sts_dok" class="form-label fw-bold">Status Dokumen
                                                    <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-check-circle"></i></span>
                                                    <div style="flex: 1">
                                                        <select class="form-select select2" id="sts_dok" name="sts_dok"
                                                            data-required="true">
                                                            <option value="">Pilih Status</option>
                                                            <option value="AKTIF"
                                                                {{ old('sts_dok') == 'AKTIF' ? 'selected' : '' }}>AKTIF
                                                            </option>
                                                            <option value="NON-AKTIF"
                                                                {{ old('sts_dok') == 'NON-AKTIF' ? 'selected' : '' }}>
                                                                NON-AKTIF</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tanggal Non Aktif (conditional) -->
                                        <div class="col-md-6" id="field_tgl_dok_na" style="display: none;">
                                            <div class="form-group mb-3">
                                                <label for="tgl_dok_na" class="form-label fw-bold">Tanggal Status Non
                                                    Aktif</label>
                                                <input type="date" class="form-control" id="tgl_dok_na"
                                                    name="tgl_dok_na" value="{{ old('tgl_dok_na') }}">
                                            </div>
                                        </div>

                                        <!-- Keterangan Non Aktif (conditional) -->
                                        <div class="col-md-6" id="field_ket_dok_na" style="display: none;">
                                            <div class="form-group mb-3">
                                                <label for="ket_dok_na" class="form-label fw-bold">Keterangan Non
                                                    Aktif</label>
                                                <textarea class="form-control auto-uppercase" id="ket_dok_na" name="ket_dok_na" rows="3"
                                                    placeholder="Alasan status non aktif">{{ old('ket_dok_na') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <a href="{{ route('data-dokumen-hrd.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                    <i class="fas fa-save me-2"></i> Simpan Data Dokumen HRD
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
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <style>
        .card-header {
            font-weight: 600;
        }

        .form-label {
            margin-bottom: 0.3rem;
        }

        .card {
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .select2-container--bootstrap-5 .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 6px 12px;
        }

        .is-invalid {
            border-color: #dc3545 !important;
            background-color: rgba(220, 53, 69, 0.05);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const groupedData = @json($grouped);

        document.addEventListener('DOMContentLoaded', function() {
            // Auto-uppercase functionality
            document.querySelectorAll('input.auto-uppercase, textarea.auto-uppercase').forEach(function(element) {
                if (element.type === 'text' || element.tagName.toLowerCase() === 'textarea') {
                    element.addEventListener('input', function() {
                        this.value = this.value.toUpperCase();
                    });
                }
            });

            $('#ktg_dok_hrd').on('change', function() {
                const kategori = $(this).val();

                $('#jns_dok_hrd')
                    .empty()
                    .append('<option value="">Pilih Jenis Dokumen</option>')
                    .prop('disabled', true);

                if (kategori && groupedData[kategori]) {
                    groupedData[kategori].forEach(item => {
                        $('#jns_dok_hrd').append(
                            `<option value="${item.jns_dok_hrd}" data-id="${item.id}" data-kode="${item.kode_dok_hrd}">
                    ${item.jns_dok_hrd}
                </option>`
                        );
                    });

                    $('#jns_dok_hrd').prop('disabled', false);
                }
            });

            $('#jns_dok_hrd').on('change', function() {
                const selected = $(this).find(':selected');

                $('#kode_dok_hrd_display').val(selected.data('kode') || '');
                $('#id_dokumen_hrd').val(selected.data('id') || '');
            });

            // Conditional field visibility
            function handleStatusDokumenChange() {
                const stsDok = $('#sts_dok').val();
                if (stsDok === 'NON-AKTIF') {
                    $('#field_tgl_dok_na, #field_ket_dok_na').show();
                } else {
                    $('#field_tgl_dok_na, #field_ket_dok_na').hide();
                    $('#tgl_dok_na, #ket_dok_na').val('');
                }
            }

            function handleJenisMasaBerlakuChange() {
                const jnsMsbDok = $('#jns_msb_dok').val();
                const fields = $('#tgl_akr_dok, #msb_dok, #tgl_prt_dok, #durasi_pgt');

                if (jnsMsbDok === 'TETAP') {
                    fields.prop('disabled', true).val('');
                    fields.closest('.form-group').addClass('opacity-50');
                } else {
                    fields.prop('disabled', false);
                    $('.form-group').removeClass('opacity-50');
                }
            }

            // Date calculations
            function calculateValidityPeriod() {
                if ($('#jns_msb_dok').val() === 'TETAP') return;

                const signatureDate = $('#tgl_ttd').val();
                const expiryDate = $('#tgl_akr_dok').val();

                if (signatureDate && expiryDate) {
                    const start = new Date(signatureDate);
                    const end = new Date(expiryDate);

                    if (end > start) {
                        const months = (end.getFullYear() - start.getFullYear()) * 12 +
                            (end.getMonth() - start.getMonth());
                        $('#msb_dok').val(months > 0 ? months : '');
                    }
                }
            }

            function calculateReminderDuration() {
                const reminderDate = $('#tgl_prt_dok').val();

                if (reminderDate) {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0); // Reset time to start of day

                    const reminder = new Date(reminderDate);
                    reminder.setHours(0, 0, 0, 0);

                    const timeDiff = reminder.getTime() - today.getTime();
                    const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

                    $('#durasi_pgt').val(daysDiff);
                }
            }

            // Event handlers
            $('#jns_msb_dok').on('change', handleJenisMasaBerlakuChange);
            $('#sts_dok').on('change', handleStatusDokumenChange);
            $('#tgl_ttd').on('change', calculateValidityPeriod);
            $('#tgl_akr_dok').on('change', function() {
                calculateValidityPeriod();
            });
            $('#tgl_prt_dok').on('change', calculateReminderDuration);

            // Form validation
            $('#dokumenHrdForm').on('submit', function(e) {
                e.preventDefault();

                const requiredFields = this.querySelectorAll('[data-required="true"]:not(:disabled)');
                let missingFields = [];

                requiredFields.forEach(function(field) {
                    field.classList.remove('is-invalid');
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        const label = field.closest('.form-group')?.querySelector('label')
                            ?.textContent?.replace('*', '').trim();
                        missingFields.push(label);
                    }
                });

                if (missingFields.length > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        html: '<p>Mohon lengkapi field berikut:</p><ul>' +
                            missingFields.map(f => '<li>' + f + '</li>').join('') + '</ul>',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                $('#submitBtn').html('<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...').prop(
                    'disabled', true);
                this.submit();
            });



            setTimeout(function() {
                handleJenisMasaBerlakuChange();
                handleStatusDokumenChange();
            }, 100);
        });
    </script>
@endpush
