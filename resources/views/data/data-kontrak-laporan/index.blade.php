@extends('layouts.app')

@section('title', 'Pelaporan Kontrak Karyawan')

@section('content')
    <div class="container-fluid dataKontrakPelaporanPage">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-file-contract me-2"></i>Pelaporan Kontrak Karyawan</span>
                        <div>
                            <button type="button" class="btn btn-light me-2" id="summaryButton">
                                <i class="fas fa-chart-pie me-1"></i> Ringkasan
                            </button>
                            <button type="button" class="btn btn-light me-2" id="filterButton">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <button type="button" class="btn btn-light me-2" id="exportButton">
                                <i class="fas fa-download me-1"></i> Export
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3 document-status-summary">
                            <span class="badge bg-primary me-2" style="font-size: 0.9rem;">
                                <i class="fas fa-file-contract me-1"></i> Total Kontrak: <strong>{{ $totalKontrak }}</strong>
                            </span>
                            <span class="badge bg-danger me-2" style="font-size: 0.9rem;">
                                <i class="fas fa-exclamation-circle me-1"></i> Expired:
                                <strong>{{ $expiredContractsCount }}</strong>
                            </span>
                            <span class="badge text-dark me-2" style="font-size: 0.9rem; background-color:#ffff66">
                                <i class="fas fa-exclamation-triangle me-1"></i> Akan Expired:
                                <strong>{{ $expiringContractsCount }}</strong>
                            </span>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Active Filter Display -->
                        @if (
                            !empty($currentFilters['status']) ||
                                !empty($currentFilters['jenis_kontrak']) ||
                                !empty($currentFilters['nama']) ||
                                !empty($currentFilters['nrk']) ||
                                !empty($currentFilters['no_kontrak']) ||
                                !empty($currentFilters['departemen']) ||
                                !empty($currentFilters['jabatan']) ||
                                !empty($currentFilters['perusahaan']) ||
                                !empty($currentFilters['jenis_kelamin']) ||
                                !empty($currentFilters['wilker']) ||
                                !empty($currentFilters['unit_kerja']))
                            <div class="alert alert-info" role="alert" id="filterActiveAlert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Filter Aktif:</strong>

                                @if (!empty($currentFilters['status']))
                                    Status: <span class="badge bg-primary">{{ $currentFilters['status'] }}</span>
                                @endif

                                @if (!empty($currentFilters['jenis_kontrak']))
                                    @php
                                        $selectedKontrak = $kontrakTypes
                                            ->where('id', $currentFilters['jenis_kontrak'])
                                            ->first();
                                    @endphp
                                    @if ($selectedKontrak)
                                        Jenis Kontrak: <span
                                            class="badge bg-success">{{ $selectedKontrak->nama_ktr }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['perusahaan']))
                                    @php
                                        $selectedPerusahaan = $perusahaans
                                            ->where('id', $currentFilters['perusahaan'])
                                            ->first();
                                    @endphp
                                    @if ($selectedPerusahaan)
                                        Perusahaan: <span
                                            class="badge bg-warning">{{ $selectedPerusahaan->nama_prs2 }}</span>
                                    @endif
                                @endif

                                @if (!empty($currentFilters['nama']))
                                    Nama: <span class="badge bg-primary">{{ $currentFilters['nama'] }}</span>
                                @endif

                                @if (!empty($currentFilters['nrk']))
                                    NRK: <span class="badge bg-primary">{{ $currentFilters['nrk'] }}</span>
                                @endif

                                @if (!empty($currentFilters['no_kontrak']))
                                    No Kontrak: <span class="badge bg-info">{{ $currentFilters['no_kontrak'] }}</span>
                                @endif

                                <a href="{{ route('data-kontrak-laporan.index') }}"
                                    class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Reset Filter
                                </a>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="dataKontrakPelaporanTable"
                                class="table table-bordered table-striped table-hover data-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="1%" class="text-center">NO</th>
                                        <th width="3%" class="text-center">NRK</th>
                                        <th width="5%" class="text-center">NAMA</th>
                                        <th width="1%" class="text-center">SEX</th>
                                        <th width="2%" class="text-center">PRS</th>
                                        <th width="2%" class="text-center">DEP</th>
                                        <th width="2%" class="text-center">JBT</th>
                                        <th width="2%" class="text-center">WILKER</th>
                                        <th width="2%" class="text-center">AREA</th>
                                        <th width="4%" class="text-center">NO KTR</th>
                                        <th width="3%" class="text-center">JNS KTR</th>
                                        <th width="3%" class="text-center">KTG KTR</th>
                                        <th width="3%" class="text-center">TGL AWAL</th>
                                        <th width="3%" class="text-center">TGL AKHIR</th>
                                        <th width="2%" class="text-center">DUR</th>
                                        <th width="3%" class="text-center">TGL PGT</th>
                                        <th width="2%" class="text-center">STATUS</th>
                                        <th width="3%" class="text-center no-wrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataKontraks as $kontrak)
                                        @php
                                            $karyawan = $kontrak->karyawan;
                                            $departemen = $karyawan ? $karyawan->departemenRelation : null;
                                            $wilayah = $karyawan ? $karyawan->wilayahKerjaRelation : null;
                                            $perusahaan = $karyawan ? $karyawan->perusahaanRelation : null;
                                            $kontrakType = $kontrak->kontrakKerja;

                                            // Calculate contract status
                                            $today = now();
                                            $isExpired =
                                                $kontrak->tgl_akhir_ktr &&
                                                $kontrak->tgl_akhir_ktr < $today &&
                                                $kontrak->sts_srt_ktr == 'AKTIF';
                                            $isExpiring =
                                                $kontrak->tgl_pgt_ktr &&
                                                $kontrak->tgl_pgt_ktr <= $today &&
                                                $kontrak->tgl_akhir_ktr >= $today &&
                                                $kontrak->sts_srt_ktr == 'AKTIF';

                                            $rowClass = '';
                                            if ($isExpired) {
                                                $rowClass = 'table-danger';
                                            } elseif ($isExpiring) {
                                                $rowClass = 'table-warning';
                                            }
                                        @endphp
                                        <tr class="{{ $rowClass }}">
                                            <td class="text-center">{{ $loop->iteration }}</td>

                                            <td>
                                                <span class="fw-bold">{{ $karyawan->nrk ?? '-' }}</span><br>
                                                <small class="text-muted">{{ $karyawan->nik ?? '-' }}</small>
                                            </td>

                                            <td>
                                                <div class="fw-bold">{{ $karyawan->nama ?? '-' }}</div>
                                            </td>

                                            <td class="text-center">
                                                @if ($karyawan && $karyawan->sex)
                                                    <span>{{ $karyawan->sex == 'LAKI-LAKI' ? 'L' : 'P' }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <span>{{ $perusahaan->nama_prs2 ?? '-' }}</span>
                                            </td>

                                            <td class="text-center">
                                                @if ($departemen)
                                                    <span>{{ $departemen->singkatan_dep }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <span>{{ $departemen->singkatan_jbt ?? '-' }}</span>
                                            </td>

                                            <td class="text-center">
                                                <span>{{ $wilayah->singkatan_wk ?? '-' }}</span>
                                            </td>

                                            <td class="text-center">
                                                <span>{{ $wilayah->area_krj ?? '-' }}</span>
                                            </td>

                                            <td>
                                                <span class="fw-bold">{{ $kontrak->no_srt_ktr ?? '-' }}</span>
                                            </td>

                                            <td class="text-center">
                                                @if ($kontrakType)
                                                    <span>{{ $kontrakType->singkatan_ktr ?? $kontrakType->nama_ktr }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($kontrak->ktg_ktk)
                                                    <span class="badge bg-warning text-dark">{{ $kontrak->ktg_ktk }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($kontrak->tgl_awl_ktr)
                                                    {{ $kontrak->tgl_awl_ktr->format('d-m-Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($kontrak->tgl_akhir_ktr)
                                                    {{ $kontrak->tgl_akhir_ktr->format('d-m-Y') }}
                                                    @if ($isExpired)
                                                        <br><span class="badge bg-danger"><i
                                                                class="fas fa-times-circle"></i> EXPIRED</span>
                                                    @elseif ($isExpiring)
                                                        <br><span class="badge bg-warning text-dark"><i
                                                                class="fas fa-exclamation-triangle"></i> SEGERA</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($kontrak->durasi_ktr)
                                                    {{ $kontrak->durasi_ktr }} bln
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                {{ $kontrak->tgl_pgt_ktr ? $kontrak->tgl_pgt_ktr->format('d-m-Y') : '-' }}
                                            </td>

                                            <td class="text-center">
                                                @if ($kontrak->sts_srt_ktr == 'AKTIF')
                                                    <span class="badge bg-success">AKTIF</span>
                                                @else
                                                    <span class="badge bg-secondary">NON-AKTIF</span>
                                                @endif
                                            </td>

                                            <td class="text-center no-wrap">
                                                <div class="btn-group" role="group">
                                                    @if (auth()->user()->is_admin || ($userPermissions['detail'] ?? false))
                                                        <a href="{{ route('data-kontrak-laporan.show', $kontrak->id) }}"
                                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                            title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
                                                    @if ($kontrak->file_doc_ktr)
                                                        <a href="{{ asset('storage/' . $kontrak->file_doc_ktr) }}"
                                                            target="_blank" class="btn btn-sm btn-success"
                                                            data-bs-toggle="tooltip" title="Lihat File">
                                                            <i class="fas fa-file-alt"></i>
                                                        </a>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="tooltip" title="File tidak tersedia">
                                                            <i class="fas fa-file-alt"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal, Export Modal, Summary Modal - sama seperti data-dokumen-laporan -->
    <!-- [Copy dari data-dokumen-laporan/index.blade.php dan sesuaikan untuk kontrak] -->
@endsection