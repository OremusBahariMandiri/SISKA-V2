@extends('layouts.app')

@section('title', 'Ringkasan SISKA')

@section('content')
<div class="container-fluid ringkasan-siska-page">

    {{-- ============================================================ --}}
    {{-- HEADER --}}
    {{-- ============================================================ --}}
    <div class="siska-header mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h4 class="siska-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Ringkasan SISKA
                </h4>
                <small class="text-muted">Sistem Informasi Kepegawaian — Ringkasan Data Karyawan</small>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge siska-badge-total px-3 py-2 fs-6">
                    <i class="fas fa-users me-1"></i>
                    Total: <strong>{{ number_format($totalKaryawan) }}</strong> Karyawan
                </span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- FILTER PANEL --}}
    {{-- ============================================================ --}}
    <div class="card shadow-sm mb-4 siska-filter-card">
        <div class="card-header d-flex justify-content-between align-items-center siska-filter-header">
            <span class="fw-semibold"><i class="fas fa-sliders-h me-2"></i>Filter Data</span>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                data-bs-target="#filterCollapse">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.ringkasan-siska.index') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label siska-label">Perusahaan (PT)</label>
                            <select name="filter_perusahaan" class="form-select form-select-sm">
                                <option value="">— Semua PT —</option>
                                @foreach($perusahaans as $prs)
                                    <option value="{{ $prs->id }}"
                                        {{ $currentFilters['perusahaan'] == $prs->id ? 'selected' : '' }}>
                                        {{ $prs->nama_prs2 ? $prs->nama_prs2 . ' — ' : '' }}{{ $prs->nama_prs1 }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label siska-label">Pusat / Cabang (Wilayah)</label>
                            <select name="filter_wilker" class="form-select form-select-sm">
                                <option value="">— Semua Wilayah —</option>
                                @foreach($wilayahKerjaOptions as $wk)
                                    <option value="{{ $wk->wilayah_krj }}"
                                        {{ $currentFilters['wilker'] == $wk->wilayah_krj ? 'selected' : '' }}>
                                        {{ $wk->wilayah_krj }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label siska-label">Tipe Kontrak</label>
                            <select name="filter_kontrak" class="form-select form-select-sm">
                                <option value="">— Semua Kontrak —</option>
                                @foreach($kontrakOptions as $ktr)
                                    <option value="{{ $ktr->id }}"
                                        {{ $currentFilters['kontrak'] == $ktr->id ? 'selected' : '' }}>
                                        {{ $ktr->singkatan_ktr ?? $ktr->kode_ktr }} — {{ $ktr->nama_ktr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label siska-label">Status Karyawan</label>
                            <select name="filter_status" class="form-select form-select-sm">
                                <option value="">— Semua Status —</option>
                                <option value="AKTIF" {{ $currentFilters['status'] == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                                <option value="NON-AKTIF" {{ $currentFilters['status'] == 'NON-AKTIF' ? 'selected' : '' }}>Non-Aktif</option>
                                <option value="CALON" {{ $currentFilters['status'] == 'CALON' ? 'selected' : '' }}>Calon</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="fas fa-search me-1"></i> Terapkan Filter
                        </button>
                        <a href="{{ route('reports.ringkasan-siska.index') }}" class="btn btn-outline-secondary btn-sm px-4">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- STAT CARDS ATAS --}}
    {{-- ============================================================ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="siska-stat-card siska-stat-total">
                <div class="siska-stat-icon"><i class="fas fa-users"></i></div>
                <div class="siska-stat-num">{{ number_format($totalKaryawan) }}</div>
                <div class="siska-stat-lbl">Total Karyawan</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="siska-stat-card siska-stat-aktif">
                <div class="siska-stat-icon"><i class="fas fa-user-check"></i></div>
                <div class="siska-stat-num">{{ number_format($statAktif) }}</div>
                <div class="siska-stat-lbl">Aktif</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="siska-stat-card siska-stat-nonaktif">
                <div class="siska-stat-icon"><i class="fas fa-user-times"></i></div>
                <div class="siska-stat-num">{{ number_format($statNonAktif) }}</div>
                <div class="siska-stat-lbl">Non-Aktif</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="siska-stat-card siska-stat-calon">
                <div class="siska-stat-icon"><i class="fas fa-user-clock"></i></div>
                <div class="siska-stat-num">{{ number_format($statCalon) }}</div>
                <div class="siska-stat-lbl">Calon</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="siska-stat-card siska-stat-laki">
                <div class="siska-stat-icon"><i class="fas fa-mars"></i></div>
                <div class="siska-stat-num">{{ number_format($statLakiLaki) }}</div>
                <div class="siska-stat-lbl">Laki-laki</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="siska-stat-card siska-stat-perempuan">
                <div class="siska-stat-icon"><i class="fas fa-venus"></i></div>
                <div class="siska-stat-num">{{ number_format($statPerempuan) }}</div>
                <div class="siska-stat-lbl">Perempuan</div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ROW 1: Karyawan Per PT + Bidang Usaha --}}
    {{-- ============================================================ --}}
    <div class="row g-4 mb-4">
        {{-- KARYAWAN PER PT --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100 siska-section-card">
                <div class="card-header siska-section-header siska-hdr-blue">
                    <i class="fas fa-building me-2"></i>Jumlah Karyawan per Perusahaan (PT)
                </div>
                <div class="card-body">
                    <canvas id="chartPerPT" height="220"></canvas>
                    <div class="siska-table-mini mt-3">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Perusahaan</th>
                                    <th>Bidang</th>
                                    <th class="text-end">Karyawan</th>
                                    <th class="text-end">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perPT as $item)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $item['singkatan'] }}</span>
                                        <br><small class="text-muted">{{ $item['label'] }}</small>
                                    </td>
                                    <td><small>{{ $item['bidang'] }}</small></td>
                                    <td class="text-end fw-bold">{{ number_format($item['jumlah']) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-primary bg-opacity-75">
                                            {{ $totalKaryawan > 0 ? round($item['jumlah'] / $totalKaryawan * 100, 1) : 0 }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- BIDANG USAHA --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100 siska-section-card">
                <div class="card-header siska-section-header siska-hdr-teal">
                    <i class="fas fa-industry me-2"></i>Pembagian per Bidang Usaha
                </div>
                <div class="card-body d-flex flex-column">
                    <canvas id="chartBidangUsaha" height="200"></canvas>
                    <div class="siska-table-mini mt-3 flex-grow-1">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Bidang Usaha</th>
                                    <th class="text-end">Karyawan</th>
                                    <th class="text-end">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perBidangUsaha as $item)
                                <tr>
                                    <td>{{ $item['label'] }}</td>
                                    <td class="text-end fw-bold">{{ number_format($item['jumlah']) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-success bg-opacity-75">
                                            {{ $totalKaryawan > 0 ? round($item['jumlah'] / $totalKaryawan * 100, 1) : 0 }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ROW 2: Pusat & Cabang + Unit Kerja --}}
    {{-- ============================================================ --}}
    <div class="row g-4 mb-4">
        {{-- PUSAT & CABANG --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100 siska-section-card">
                <div class="card-header siska-section-header siska-hdr-indigo">
                    <i class="fas fa-map-marker-alt me-2"></i>Pusat & Cabang (Wilayah Kerja)
                </div>
                <div class="card-body">
                    <canvas id="chartPusatCabang" height="220"></canvas>
                    <div class="siska-table-mini mt-3">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Wilayah</th>
                                    <th class="text-end">Karyawan</th>
                                    <th class="text-end">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perPusatCabang as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item['label'] }}</td>
                                    <td class="text-end fw-bold">{{ number_format($item['jumlah']) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-indigo bg-opacity-75" style="background:#4c6ef5 !important">
                                            {{ $totalKaryawan > 0 ? round($item['jumlah'] / $totalKaryawan * 100, 1) : 0 }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- UNIT KERJA --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100 siska-section-card">
                <div class="card-header siska-section-header siska-hdr-cyan">
                    <i class="fas fa-sitemap me-2"></i>Unit Kerja (Area)
                </div>
                <div class="card-body">
                    <canvas id="chartUnitKerja" height="220"></canvas>
                    <div class="siska-table-mini mt-3">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Unit Kerja</th>
                                    <th>Wilayah</th>
                                    <th class="text-end">Karyawan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perUnitKerja as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item['label'] }}</td>
                                    <td><small class="text-muted">{{ $item['wilayah'] }}</small></td>
                                    <td class="text-end fw-bold">{{ number_format($item['jumlah']) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ROW 3: Departemen & Jabatan + Kontrak Kerja --}}
    {{-- ============================================================ --}}
    <div class="row g-4 mb-4">
        {{-- DEPARTEMEN --}}
        <div class="col-lg-6">
            <div class="card shadow-sm h-100 siska-section-card">
                <div class="card-header siska-section-header siska-hdr-orange">
                    <i class="fas fa-layer-group me-2"></i>Pembagian per Departemen
                </div>
                <div class="card-body">
                    <canvas id="chartDepartemen" height="240"></canvas>
                    <div class="siska-table-mini mt-3">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Departemen</th>
                                    <th class="text-end">Karyawan</th>
                                    <th class="text-end">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perDepartemen as $item)
                                <tr>
                                    <td>
                                        <span class="badge siska-dep-badge me-1">{{ $item['singkatan'] }}</span>
                                        {{ $item['label'] }}
                                    </td>
                                    <td class="text-end fw-bold">{{ number_format($item['jumlah']) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-warning text-dark bg-opacity-75">
                                            {{ $totalKaryawan > 0 ? round($item['jumlah'] / $totalKaryawan * 100, 1) : 0 }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- JABATAN + KONTRAK --}}
        <div class="col-lg-6">
            {{-- KONTRAK KERJA --}}
            <div class="card shadow-sm mb-4 siska-section-card">
                <div class="card-header siska-section-header siska-hdr-red">
                    <i class="fas fa-file-contract me-2"></i>Tipe Kontrak Kerja (PKWT/PKWTT/SPKK)
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <canvas id="chartKontrak" height="200"></canvas>
                        </div>
                        <div class="col-md-7">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kontrak</th>
                                        <th class="text-end">Karyawan</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($perKontrak as $item)
                                    <tr>
                                        <td>
                                            <span class="badge siska-ktr-badge me-1">{{ $item['kode'] }}</span>
                                            <small>{{ $item['label'] }}</small>
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format($item['jumlah']) }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-danger bg-opacity-75">
                                                {{ $totalKaryawan > 0 ? round($item['jumlah'] / $totalKaryawan * 100, 1) : 0 }}%
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada data</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- JABATAN --}}
            <div class="card shadow-sm siska-section-card">
                <div class="card-header siska-section-header siska-hdr-purple">
                    <i class="fas fa-user-tag me-2"></i>Pembagian per Jabatan
                </div>
                <div class="card-body p-0">
                    <div class="siska-jabatan-list">
                        @forelse($perJabatan->take(10) as $i => $item)
                        <div class="siska-jabatan-row">
                            <div class="siska-jabatan-rank">{{ $i + 1 }}</div>
                            <div class="siska-jabatan-info">
                                <div class="fw-semibold">{{ $item['label'] }}</div>
                                <small class="text-muted">{{ $item['departemen'] }}</small>
                            </div>
                            <div class="siska-jabatan-bar-wrap">
                                <div class="siska-jabatan-bar"
                                    style="width: {{ $totalKaryawan > 0 ? min(100, round($item['jumlah'] / $totalKaryawan * 100)) : 0 }}%">
                                </div>
                            </div>
                            <div class="siska-jabatan-count">{{ number_format($item['jumlah']) }}</div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-3">Tidak ada data</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<style>
/* ============ SISKA PAGE VARIABLES ============ */
.ringkasan-siska-page {
    --siska-blue:    #1a6fcf;
    --siska-teal:    #0d9488;
    --siska-indigo:  #4c6ef5;
    --siska-cyan:    #0891b2;
    --siska-orange:  #d97706;
    --siska-red:     #dc2626;
    --siska-purple:  #7c3aed;
    --siska-green:   #059669;
    font-family: 'Segoe UI', sans-serif;
}

/* ============ HEADER ============ */
.siska-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: #1e293b;
    letter-spacing: -0.3px;
}
.siska-badge-total {
    background: #1e293b;
    color: #fff;
    border-radius: 8px;
}

/* ============ FILTER CARD ============ */
.siska-filter-card {
    border: none;
    border-left: 4px solid var(--siska-blue);
    border-radius: 8px;
}
.siska-filter-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.siska-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

/* ============ STAT CARDS ============ */
.siska-stat-card {
    border-radius: 10px;
    padding: 1rem 0.75rem;
    text-align: center;
    color: #fff;
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: default;
    min-height: 110px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
}
.siska-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.siska-stat-icon { font-size: 1.5rem; opacity: 0.85; }
.siska-stat-num  { font-size: 1.8rem; font-weight: 700; line-height: 1; }
.siska-stat-lbl  { font-size: 0.72rem; opacity: 0.9; font-weight: 500; }

.siska-stat-total    { background: linear-gradient(135deg, #1e293b, #334155); }
.siska-stat-aktif    { background: linear-gradient(135deg, #059669, #10b981); }
.siska-stat-nonaktif { background: linear-gradient(135deg, #dc2626, #ef4444); }
.siska-stat-calon    { background: linear-gradient(135deg, #d97706, #f59e0b); }
.siska-stat-laki     { background: linear-gradient(135deg, #1a6fcf, #3b82f6); }
.siska-stat-perempuan{ background: linear-gradient(135deg, #9333ea, #c084fc); }

/* ============ SECTION CARDS ============ */
.siska-section-card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
}
.siska-section-header {
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.65rem 1rem;
    color: #fff;
}
.siska-hdr-blue   { background: var(--siska-blue); }
.siska-hdr-teal   { background: var(--siska-teal); }
.siska-hdr-indigo { background: var(--siska-indigo); }
.siska-hdr-cyan   { background: var(--siska-cyan); }
.siska-hdr-orange { background: var(--siska-orange); }
.siska-hdr-red    { background: var(--siska-red); }
.siska-hdr-purple { background: var(--siska-purple); }

/* ============ MINI TABLE ============ */
.siska-table-mini { max-height: 220px; overflow-y: auto; }
.siska-table-mini::-webkit-scrollbar { width: 4px; }
.siska-table-mini::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

/* ============ BADGES ============ */
.siska-dep-badge {
    background: #f1f5f9;
    color: #475569;
    font-size: 0.65rem;
    padding: 2px 6px;
    border-radius: 4px;
}
.siska-ktr-badge {
    background: #fef2f2;
    color: #991b1b;
    font-size: 0.65rem;
    padding: 2px 6px;
    border-radius: 4px;
}

/* ============ JABATAN LIST ============ */
.siska-jabatan-list { padding: 0.5rem 0; }
.siska-jabatan-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0.45rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s;
}
.siska-jabatan-row:last-child { border-bottom: none; }
.siska-jabatan-row:hover { background: #f8fafc; }
.siska-jabatan-rank {
    width: 22px;
    height: 22px;
    background: #7c3aed;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.68rem;
    font-weight: 700;
    flex-shrink: 0;
}
.siska-jabatan-info { min-width: 120px; flex: 0 0 auto; }
.siska-jabatan-info .fw-semibold { font-size: 0.82rem; }
.siska-jabatan-bar-wrap {
    flex: 1;
    height: 8px;
    background: #e2e8f0;
    border-radius: 4px;
    overflow: hidden;
}
.siska-jabatan-bar {
    height: 100%;
    background: linear-gradient(90deg, #7c3aed, #c084fc);
    border-radius: 4px;
    transition: width 0.8s ease;
}
.siska-jabatan-count {
    font-size: 0.82rem;
    font-weight: 700;
    color: #374151;
    min-width: 30px;
    text-align: right;
}

/* ============ RESPONSIVE ============ */
@media (max-width: 768px) {
    .siska-stat-num { font-size: 1.4rem; }
    .siska-jabatan-info { min-width: 80px; }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ============ PALET WARNA ============
    const PALETTE = [
        '#1a6fcf','#0d9488','#4c6ef5','#0891b2',
        '#d97706','#dc2626','#7c3aed','#059669',
        '#f59e0b','#6366f1','#14b8a6','#ec4899',
    ];

    Chart.defaults.font.family = "'Segoe UI', sans-serif";
    Chart.defaults.font.size   = 11;
    Chart.defaults.color       = '#64748b';

    const tooltipPlugin = {
        plugins: {
            tooltip: {
                backgroundColor: '#1e293b',
                padding: 10,
                cornerRadius: 6,
                callbacks: {
                    label: ctx => ` ${ctx.label}: ${ctx.parsed.toLocaleString('id-ID')} karyawan`
                }
            },
            legend: { position: 'bottom', labels: { padding: 12, boxWidth: 12 } }
        }
    };

    // =========================================================
    // 1. Chart Per PT (Horizontal Bar)
    // =========================================================
    const ptLabels  = @json($perPT->pluck('singkatan'));
    const ptData    = @json($perPT->pluck('jumlah'));
    new Chart(document.getElementById('chartPerPT'), {
        type: 'bar',
        data: {
            labels: ptLabels,
            datasets: [{
                label: 'Karyawan',
                data: ptData,
                backgroundColor: PALETTE.slice(0, ptData.length),
                borderRadius: 5,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false }, tooltip: tooltipPlugin.plugins.tooltip },
            scales: {
                x: { grid: { color: '#f1f5f9' }, ticks: { stepSize: 1 } },
                y: { grid: { display: false } }
            }
        }
    });

    // =========================================================
    // 2. Chart Bidang Usaha (Doughnut)
    // =========================================================
    const buLabels  = @json($perBidangUsaha->pluck('label'));
    const buData    = @json($perBidangUsaha->pluck('jumlah'));
    new Chart(document.getElementById('chartBidangUsaha'), {
        type: 'doughnut',
        data: {
            labels: buLabels,
            datasets: [{
                data: buData,
                backgroundColor: PALETTE,
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 8,
            }]
        },
        options: {
            cutout: '60%',
            responsive: true,
            ...tooltipPlugin
        }
    });

    // =========================================================
    // 3. Chart Pusat & Cabang (Pie)
    // =========================================================
    const pcLabels  = @json($perPusatCabang->pluck('label'));
    const pcData    = @json($perPusatCabang->pluck('jumlah'));
    new Chart(document.getElementById('chartPusatCabang'), {
        type: 'pie',
        data: {
            labels: pcLabels,
            datasets: [{
                data: pcData,
                backgroundColor: ['#4c6ef5','#6366f1','#818cf8','#a5b4fc','#c7d2fe'],
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 8,
            }]
        },
        options: { responsive: true, ...tooltipPlugin }
    });

    // =========================================================
    // 4. Chart Unit Kerja (Bar)
    // =========================================================
    const ukLabels  = @json($perUnitKerja->pluck('label'));
    const ukData    = @json($perUnitKerja->pluck('jumlah'));
    new Chart(document.getElementById('chartUnitKerja'), {
        type: 'bar',
        data: {
            labels: ukLabels,
            datasets: [{
                label: 'Karyawan',
                data: ukData,
                backgroundColor: '#0891b2',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, tooltip: tooltipPlugin.plugins.tooltip },
            scales: {
                x: { grid: { display: false }, ticks: { maxRotation: 30 } },
                y: { grid: { color: '#f1f5f9' } }
            }
        }
    });

    // =========================================================
    // 5. Chart Departemen (Horizontal Bar)
    // =========================================================
    const depLabels = @json($perDepartemen->pluck('singkatan'));
    const depData   = @json($perDepartemen->pluck('jumlah'));
    new Chart(document.getElementById('chartDepartemen'), {
        type: 'bar',
        data: {
            labels: depLabels,
            datasets: [{
                label: 'Karyawan',
                data: depData,
                backgroundColor: depData.map((_, i) => PALETTE[i % PALETTE.length]),
                borderRadius: 4,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false }, tooltip: tooltipPlugin.plugins.tooltip },
            scales: {
                x: { grid: { color: '#f1f5f9' } },
                y: { grid: { display: false } }
            }
        }
    });

    // =========================================================
    // 6. Chart Kontrak (Doughnut)
    // =========================================================
    const ktrLabels = @json($perKontrak->pluck('kode'));
    const ktrData   = @json($perKontrak->pluck('jumlah'));
    new Chart(document.getElementById('chartKontrak'), {
        type: 'doughnut',
        data: {
            labels: ktrLabels,
            datasets: [{
                data: ktrData,
                backgroundColor: ['#dc2626','#d97706','#059669','#1a6fcf','#7c3aed'],
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 8,
            }]
        },
        options: {
            cutout: '55%',
            responsive: true,
            ...tooltipPlugin
        }
    });

});
</script>
@endpush