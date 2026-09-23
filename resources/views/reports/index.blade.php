@extends('layouts.app')

@section('title', 'Ringkasan SISKA')

@section('content')
    <div class="container-fluid ringkasan-siska-page">

        {{-- ====== HEADER ====== --}}
        <div class="siska-header mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h4 class="siska-title mb-0"><i class="fas fa-chart-bar me-2"></i>Ringkasan SISKA</h4>
                    <small class="text-muted">Sistem Informasi Kepegawaian — klik angka untuk lihat detail karyawan</small>
                </div>
                <span class="badge siska-badge-total px-3 py-2 fs-6">
                    <i class="fas fa-users me-1"></i>Total:
                    <strong class="drill-trigger ms-1 siska-link" data-type="status" data-value=""
                        data-label="Semua Karyawan">
                        {{ number_format($totalKaryawan) }}
                    </strong> Karyawan
                </span>
            </div>
        </div>

        {{-- ====== FILTER PANEL ====== --}}
        <div class="card shadow-sm mb-4 siska-filter-card" id="activeFilters"
            data-filter-perusahaan="{{ $currentFilters['perusahaan'] }}"
            data-filter-wilker="{{ $currentFilters['wilker'] }}" data-filter-area="{{ $currentFilters['area'] }}"
            data-filter-status="{{ $currentFilters['status'] }}" data-filter-kontrak="{{ $currentFilters['kontrak'] }}"
            data-filter-departemen="{{ $currentFilters['departemen'] }}"
            data-filter-tgl-masuk-dari="{{ $currentFilters['tgl_masuk_dari'] }}"
            data-filter-tgl-masuk-sampai="{{ $currentFilters['tgl_masuk_sampai'] }}"
            data-filter-tgl-phk-dari="{{ $currentFilters['tgl_phk_dari'] }}"
            data-filter-tgl-phk-sampai="{{ $currentFilters['tgl_phk_sampai'] }}"
            data-filter-include-top-mgmt="{{ $currentFilters['include_top_mgmt'] }}">
            <div class="card-header bg-primary d-flex justify-content-between align-items-center"
                style="background:#f8fafc;border-bottom:1px solid #e2e8f0;border-left:4px solid #1a6fcf;">
                <span class="fw-semibold" style="color:#ffffff;font-size:.875rem;">
                    <i class="fas fa-sliders-h me-2" style="color:#ffffff;"></i>Filter Data
                </span>
            </div>
            <div class="collapse show" id="filterCollapse">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.ringkasan-siska.index') }}" id="filterForm">
                        <div class="row g-3 align-items-end">

                            {{-- ── BARIS 1: 4 select utama ── --}}
                            <div class="col-md-3">
                                <label class="form-label siska-label">Perusahaan (PT)</label>
                                <select name="filter_perusahaan" class="form-select form-select-sm">
                                    <option value="">— Semua PT —</option>
                                    @foreach ($perusahaans as $prs)
                                        <option value="{{ $prs->id }}"
                                            {{ $currentFilters['perusahaan'] == $prs->id ? 'selected' : '' }}>
                                            {{ $prs->nama_prs2 ? $prs->nama_prs2 . ' — ' : '' }}{{ $prs->nama_prs1 }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label siska-label">Wilayah Kerja</label>
                                <select name="filter_wilker" class="form-select form-select-sm" id="filterWilker">
                                    <option value="">— Semua Wilayah —</option>
                                    @foreach ($wilayahKerjaOptions as $wk)
                                        <option value="{{ $wk->wilayah_krj }}"
                                            {{ $currentFilters['wilker'] == $wk->wilayah_krj ? 'selected' : '' }}>
                                            {{ $wk->wilayah_krj }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label siska-label">Area Kerja</label>
                                <select name="filter_area" class="form-select form-select-sm" id="filterArea">
                                    <option value="">— Semua Area —</option>
                                    @foreach ($areaKerjaOptions as $ak)
                                        <option value="{{ $ak->id }}"
                                            {{ $currentFilters['area'] == $ak->id ? 'selected' : '' }}>
                                            {{ $ak->area_krj }}{{ $ak->singkatan_wk ? ' (' . $ak->singkatan_wk . ')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label siska-label">Departemen</label>
                                <select name="filter_departemen" class="form-select form-select-sm" id="filterDepartemen">
                                    <option value="">— Semua Departemen —</option>
                                    @foreach ($departemenOptions as $dep)
                                        <option value="{{ $dep->nama_dep }}"
                                            {{ $currentFilters['departemen'] == $dep->nama_dep ? 'selected' : '' }}>
                                            {{ $dep->singkatan_dep ? '[' . $dep->singkatan_dep . '] ' : '' }}{{ $dep->nama_dep }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ── BARIS 2: Kontrak + Status + Top Mgmt + spacer ── --}}
                            <div class="col-md-3">
                                <label class="form-label siska-label">Tipe Kontrak</label>
                                <select name="filter_kontrak" class="form-select form-select-sm">
                                    <option value="">— Semua Kontrak —</option>
                                    @foreach ($kontrakOptions as $ktr)
                                        <option value="{{ $ktr->id }}"
                                            {{ $currentFilters['kontrak'] == $ktr->id ? 'selected' : '' }}>
                                            {{ $ktr->singkatan_ktr ?? $ktr->kode_ktr }} — {{ $ktr->nama_ktr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label siska-label">Status</label>
                                <select name="filter_status" class="form-select form-select-sm">
                                    <option value="">— Semua Status —</option>
                                    <option value="AKTIF" {{ $currentFilters['status'] == 'AKTIF' ? 'selected' : '' }}>
                                        Aktif</option>
                                    <option value="NON-AKTIF"
                                        {{ $currentFilters['status'] == 'NON-AKTIF' ? 'selected' : '' }}>Non-Aktif</option>
                                    <option value="CALON" {{ $currentFilters['status'] == 'CALON' ? 'selected' : '' }}>
                                        Calon</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label siska-label">Top Manajement
                                </label>
                                <div class="siska-topmgmt-toggle">
                                    <input type="hidden" name="filter_include_top_mgmt" value="0">
                                    <input type="checkbox" name="filter_include_top_mgmt" id="filterIncludeTopMgmt"
                                        value="1" class="siska-toggle-input"
                                        {{ ($currentFilters['include_top_mgmt'] ?? '0') === '1' ? 'checked' : '' }}>
                                    <label for="filterIncludeTopMgmt" class="siska-toggle-label" id="toggleTopMgmtLabel">
                                        <span class="siska-toggle-thumb"></span>
                                        <span class="siska-toggle-text">
                                            {{ ($currentFilters['include_top_mgmt'] ?? '0') === '1' ? 'Disertakan' : 'Dikecualikan' }}
                                        </span>
                                    </label>
                                </div>
                            </div>

                            {{-- spacer baris 2 --}}
                            <div class="col-md-3"></div>

                            {{-- ── DIVIDER ── --}}
                            <div class="col-12">
                                <hr class="siska-filter-divider">
                            </div>

                            {{-- ── BARIS 3: Periode + Tombol dalam satu flex row ── --}}
                            <div class="col-12">
                                <div class="d-flex align-items-end gap-3">

                                    {{-- Periode Masuk --}}
                                    <div class="flex-grow-1">
                                        <label class="form-label siska-label">
                                            <i class="fas fa-sign-in-alt me-1" style="color:#059669;"></i>Periode Masuk
                                            Kerja
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text siska-ig-green">Dari</span>
                                            <input type="date" name="filter_tgl_masuk_dari"
                                                class="form-control form-control-sm"
                                                value="{{ $currentFilters['tgl_masuk_dari'] }}">
                                            <span class="input-group-text siska-ig-green">S/d</span>
                                            <input type="date" name="filter_tgl_masuk_sampai"
                                                class="form-control form-control-sm"
                                                value="{{ $currentFilters['tgl_masuk_sampai'] }}">
                                        </div>
                                        <div class="siska-filter-hint mt-1">
                                            <i class="fas fa-info-circle me-1"></i>Berdasarkan tanggal mulai bekerja
                                        </div>
                                    </div>

                                    {{-- Periode Keluar --}}
                                    <div class="flex-grow-1">
                                        <label class="form-label siska-label">
                                            <i class="fas fa-sign-out-alt me-1" style="color:#dc2626;"></i>Periode Keluar
                                            / PHK
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text siska-ig-red">Dari</span>
                                            <input type="date" name="filter_tgl_phk_dari"
                                                class="form-control form-control-sm"
                                                value="{{ $currentFilters['tgl_phk_dari'] }}">
                                            <span class="input-group-text siska-ig-red">S/d</span>
                                            <input type="date" name="filter_tgl_phk_sampai"
                                                class="form-control form-control-sm"
                                                value="{{ $currentFilters['tgl_phk_sampai'] }}">
                                        </div>
                                        <div class="siska-filter-hint mt-1">
                                            <i class="fas fa-info-circle me-1"></i>Berdasarkan tanggal PHK / resign
                                        </div>
                                    </div>

                                    {{-- Tombol rata bawah dengan input-group --}}
                                    <div class="d-flex gap-2 flex-shrink-0" style="padding-bottom:1.25rem;">
                                        <a href="{{ route('reports.ringkasan-siska.index') }}"
                                            class="btn btn-outline-secondary btn-sm px-3">
                                            <i class="fas fa-undo me-1"></i>Reset
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-sm px-4">
                                            <i class="fas fa-search me-1"></i>Terapkan
                                        </button>
                                    </div>

                                </div>
                            </div>

                        </div>

                        {{-- Active filter chips --}}
                        @if (array_filter($currentFilters))
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <small class="text-muted align-self-center">Filter aktif:</small>
                                @if ($currentFilters['departemen'])
                                    <span class="siska-chip siska-chip-orange">
                                        <i class="fas fa-layer-group me-1"></i>{{ $currentFilters['departemen'] }}
                                        <a href="{{ route('reports.ringkasan-siska.index', array_merge($currentFilters, ['filter_departemen' => ''])) }}"
                                            class="ms-1 text-inherit">×</a>
                                    </span>
                                @endif
                                @if ($currentFilters['perusahaan'])
                                    @php $prsChip = $perusahaans->find($currentFilters['perusahaan']); @endphp
                                    <span class="siska-chip siska-chip-blue">
                                        <i
                                            class="fas fa-building me-1"></i>{{ $prsChip?->nama_prs2 ?? $prsChip?->nama_prs1 }}
                                        <a href="{{ route('reports.ringkasan-siska.index', array_merge($currentFilters, ['filter_perusahaan' => ''])) }}"
                                            class="ms-1 text-inherit">×</a>
                                    </span>
                                @endif
                                @if ($currentFilters['wilker'])
                                    <span class="siska-chip siska-chip-indigo">
                                        <i class="fas fa-map me-1"></i>{{ $currentFilters['wilker'] }}
                                        <a href="{{ route('reports.ringkasan-siska.index', array_merge($currentFilters, ['filter_wilker' => '', 'filter_area' => ''])) }}"
                                            class="ms-1 text-inherit">×</a>
                                    </span>
                                @endif
                                @if ($currentFilters['area'])
                                    @php $areaChip = $areaKerjaOptions->firstWhere('id', $currentFilters['area']); @endphp
                                    <span class="siska-chip siska-chip-cyan">
                                        <i
                                            class="fas fa-map-marker-alt me-1"></i>{{ $areaChip?->area_krj ?? $currentFilters['area'] }}
                                        <a href="{{ route('reports.ringkasan-siska.index', array_merge($currentFilters, ['filter_area' => ''])) }}"
                                            class="ms-1 text-inherit">×</a>
                                    </span>
                                @endif
                                @if ($currentFilters['status'])
                                    <span class="siska-chip siska-chip-green">
                                        <i class="fas fa-circle me-1"></i>{{ $currentFilters['status'] }}
                                        <a href="{{ route('reports.ringkasan-siska.index', array_merge($currentFilters, ['filter_status' => ''])) }}"
                                            class="ms-1 text-inherit">×</a>
                                    </span>
                                @endif
                                @if ($currentFilters['kontrak'])
                                    @php $ktrChip = $kontrakOptions->find($currentFilters['kontrak']); @endphp
                                    <span class="siska-chip siska-chip-red">
                                        <i
                                            class="fas fa-file-contract me-1"></i>{{ $ktrChip?->singkatan_ktr ?? $ktrChip?->kode_ktr }}
                                        <a href="{{ route('reports.ringkasan-siska.index', array_merge($currentFilters, ['filter_kontrak' => ''])) }}"
                                            class="ms-1 text-inherit">×</a>
                                    </span>
                                @endif
                                @if (($currentFilters['include_top_mgmt'] ?? '0') === '1')
                                    <span class="siska-chip siska-chip-orange">
                                        <i class="fas fa-crown me-1"></i>Termasuk Top Manajement
                                        <a href="{{ route('reports.ringkasan-siska.index', array_merge($currentFilters, ['filter_include_top_mgmt' => '0'])) }}"
                                            class="ms-1 text-inherit">×</a>
                                    </span>
                                @endif
                                @if ($currentFilters['tgl_masuk_dari'] || $currentFilters['tgl_masuk_sampai'])
                                    <span class="siska-chip siska-chip-green">
                                        <i class="fas fa-sign-in-alt me-1"></i>
                                        Masuk:
                                        {{ $currentFilters['tgl_masuk_dari']
                                            ? \Carbon\Carbon::parse($currentFilters['tgl_masuk_dari'])->format('d/m/Y')
                                            : '…' }}
                                        –
                                        {{ $currentFilters['tgl_masuk_sampai']
                                            ? \Carbon\Carbon::parse($currentFilters['tgl_masuk_sampai'])->format('d/m/Y')
                                            : '…' }}
                                        <a href="{{ route(
                                            'reports.ringkasan-siska.index',
                                            array_merge($currentFilters, [
                                                'filter_tgl_masuk_dari' => '',
                                                'filter_tgl_masuk_sampai' => '',
                                            ]),
                                        ) }}"
                                            class="ms-1 text-inherit">×</a>
                                    </span>
                                @endif

                                @if ($currentFilters['tgl_phk_dari'] || $currentFilters['tgl_phk_sampai'])
                                    <span class="siska-chip siska-chip-red">
                                        <i class="fas fa-sign-out-alt me-1"></i>
                                        Keluar:
                                        {{ $currentFilters['tgl_phk_dari']
                                            ? \Carbon\Carbon::parse($currentFilters['tgl_phk_dari'])->format('d/m/Y')
                                            : '…' }}
                                        –
                                        {{ $currentFilters['tgl_phk_sampai']
                                            ? \Carbon\Carbon::parse($currentFilters['tgl_phk_sampai'])->format('d/m/Y')
                                            : '…' }}
                                        <a href="{{ route(
                                            'reports.ringkasan-siska.index',
                                            array_merge($currentFilters, [
                                                'filter_tgl_phk_dari' => '',
                                                'filter_tgl_phk_sampai' => '',
                                            ]),
                                        ) }}"
                                            class="ms-1 text-inherit">×</a>
                                    </span>
                                @endif
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- ====== STAT CARDS ====== --}}
        <div class="row g-3 mb-4">
            @php
                $stats = [
                    [
                        'icon' => 'fa-users',
                        'num' => $totalKaryawan,
                        'lbl' => 'Total Karyawan',
                        'cls' => 'siska-stat-total',
                        'type' => 'status',
                        'val' => '',
                    ],
                    [
                        'icon' => 'fa-user-check',
                        'num' => $statAktif,
                        'lbl' => 'Aktif',
                        'cls' => 'siska-stat-aktif',
                        'type' => 'status',
                        'val' => 'AKTIF',
                    ],
                    [
                        'icon' => 'fa-user-times',
                        'num' => $statNonAktif,
                        'lbl' => 'Non-Aktif',
                        'cls' => 'siska-stat-nonaktif',
                        'type' => 'status',
                        'val' => 'NON-AKTIF',
                    ],
                    [
                        'icon' => 'fa-user-clock',
                        'num' => $statCalon,
                        'lbl' => 'Calon',
                        'cls' => 'siska-stat-calon',
                        'type' => 'status',
                        'val' => 'CALON',
                    ],
                    [
                        'icon' => 'fa-mars',
                        'num' => $statLakiLaki,
                        'lbl' => 'Laki-laki',
                        'cls' => 'siska-stat-laki',
                        'type' => 'gender',
                        'val' => 'LAKI-LAKI',
                    ],
                    [
                        'icon' => 'fa-venus',
                        'num' => $statPerempuan,
                        'lbl' => 'Perempuan',
                        'cls' => 'siska-stat-perempuan',
                        'type' => 'gender',
                        'val' => 'PEREMPUAN',
                    ],
                ];
            @endphp
            @foreach ($stats as $s)
                <div class="col-6 col-md-2">
                    <div class="siska-stat-card {{ $s['cls'] }} drill-trigger" data-type="{{ $s['type'] }}"
                        data-value="{{ $s['val'] }}" data-label="{{ $s['lbl'] }}">
                        <div class="siska-stat-top">
                            <div class="siska-stat-icon"><i class="fas {{ $s['icon'] }}"></i></div>
                        </div>
                        <div class="siska-stat-num">{{ number_format($s['num']) }}</div>
                        <div class="siska-stat-lbl">{{ $s['lbl'] }}</div>
                        <div class="siska-stat-hint"><i class="fas fa-mouse-pointer me-1"></i>Klik untuk detail</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ================================================================ --}}
        {{-- SECTION CROSS ANALYSIS                                           --}}
        {{-- ================================================================ --}}
        @if ($depWilayahMatrix->count() > 0 || $depPTMatrix->count() > 0)
            <div class="row g-4 mb-4">

                {{-- ---- Departemen × Wilayah ---- --}}
                <div class="col-12">
                    <div class="card siska-section-card flush-table">
                        <div class="card-header siska-section-header d-flex justify-content-between align-items-center"
                            style="background:linear-gradient(135deg,#0f766e 0%,#0d9488 60%,#14b8a6 100%);color:#fff;">
                            <span>
                                <i class="fas fa-map-pin me-2" style="opacity:.85;"></i>
                                Pembagian Departemen Berdasarkan Wilayah Kerja
                            </span>
                            <small style="opacity:.7;font-weight:400;">klik angka untuk lihat karyawan</small>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm siska-matrix-table mb-0">
                                    <thead>
                                        <tr class="table-info">
                                            <th class="siska-matrix-dep-col">Departemen</th>
                                            @foreach ($allWilayah as $wNama)
                                                <th class="text-center">{{ $wNama }}</th>
                                            @endforeach
                                            <th class="text-center siska-matrix-total-col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($depWilayahMatrix as $row)
                                            @php $maxVal = $row['total'] > 0 ? max(array_values($row['wilayah']) ?: [1]) : 1; @endphp
                                            <tr>
                                                <td class="fw-semibold siska-matrix-dep-col">
                                                    <span class="drill-trigger siska-link" data-type="departemen"
                                                        data-value="{{ $row['departemen'] }}"
                                                        data-label="Departemen: {{ $row['departemen'] }}">
                                                        {{ $row['departemen'] }}
                                                    </span>
                                                </td>
                                                @foreach ($allWilayah as $wNama)
                                                    @php $cnt = $row['wilayah'][$wNama] ?? 0; @endphp
                                                    <td
                                                        class="text-center siska-matrix-cell {{ $cnt > 0 ? 'has-data' : 'no-data' }}">
                                                        @if ($cnt > 0)
                                                            <span class="drill-trigger siska-matrix-num"
                                                                data-type="dep_wilayah"
                                                                data-value="{{ $row['departemen'] }}||{{ $wNama }}"
                                                                data-label="{{ $row['departemen'] }} — {{ $wNama }}">
                                                                {{ number_format($cnt) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="text-center fw-bold">
                                                    <span class="drill-trigger siska-num-badge bg-secondary"
                                                        data-type="departemen" data-value="{{ $row['departemen'] }}"
                                                        data-label="Departemen: {{ $row['departemen'] }}">
                                                        {{ number_format($row['total']) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ $allWilayah->count() + 2 }}"
                                                    class="text-center text-muted py-3">
                                                    Tidak ada data
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($depWilayahMatrix->count() > 1)
                                        <tfoot>
                                            <tr class="table-light fw-semibold">
                                                <td>Total per Wilayah</td>
                                                @foreach ($allWilayah as $wNama)
                                                    @php $colTotal = $depWilayahMatrix->sum(fn($r) => $r['wilayah'][$wNama] ?? 0); @endphp
                                                    <td class="text-center">
                                                        @if ($colTotal > 0)
                                                            <span class="drill-trigger siska-link" data-type="wilker"
                                                                data-value="{{ $wNama }}"
                                                                data-label="Wilayah: {{ $wNama }}">
                                                                {{ number_format($colTotal) }}
                                                            </span>
                                                        @else
                                                            —
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="text-center">
                                                    {{ number_format($depWilayahMatrix->sum('total')) }}</td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ---- Departemen × PT ---- --}}
                <div class="col-12">
                    <div class="card siska-section-card flush-table">
                        <div class="card-header siska-section-header d-flex justify-content-between align-items-center"
                            style="background:linear-gradient(135deg,#4338ca 0%,#4c6ef5 60%,#6366f1 100%);color:#fff;">
                            <span>
                                <i class="fas fa-building me-2" style="opacity:.85;"></i>
                                Pembagian Departemen Berdasarkan Perusahaan
                            </span>
                            <small style="opacity:.7;font-weight:400;">klik angka untuk lihat karyawan</small>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm siska-matrix-table mb-0">
                                    <thead>
                                        <tr class="table-info">
                                            <th class="siska-matrix-dep-col">Departemen</th>
                                            @foreach ($allPT as $pt)
                                                <th class="text-center" title="{{ $pt['nama'] }}">
                                                    {{ $pt['singkatan'] }}</th>
                                            @endforeach
                                            <th class="text-center siska-matrix-total-col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($depPTMatrix as $row)
                                            @php
                                                $ptVals = collect($row['pt'])->pluck('jumlah')->toArray();
                                                $maxPT = count($ptVals) ? max($ptVals ?: [1]) : 1;
                                            @endphp
                                            <tr>
                                                <td class="fw-semibold siska-matrix-dep-col">
                                                    <span class="drill-trigger siska-link" data-type="departemen"
                                                        data-value="{{ $row['departemen'] }}"
                                                        data-label="Departemen: {{ $row['departemen'] }}">
                                                        {{ $row['departemen'] }}
                                                    </span>
                                                </td>
                                                @foreach ($allPT as $pt)
                                                    @php
                                                        $cell = $row['pt'][$pt['singkatan']] ?? [
                                                            'jumlah' => 0,
                                                            'id' => $pt['id'],
                                                        ];
                                                        $cnt = $cell['jumlah'];
                                                    @endphp
                                                    <td
                                                        class="text-center siska-matrix-cell {{ $cnt > 0 ? 'has-data' : 'no-data' }}">
                                                        @if ($cnt > 0)
                                                            <span class="drill-trigger siska-matrix-num"
                                                                data-type="dep_pt"
                                                                data-value="{{ $row['departemen'] }}||{{ $pt['id'] }}"
                                                                data-label="{{ $row['departemen'] }} — {{ $pt['singkatan'] }}">
                                                                {{ number_format($cnt) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="text-center fw-bold">
                                                    <span class="drill-trigger siska-num-badge bg-secondary"
                                                        data-type="departemen" data-value="{{ $row['departemen'] }}"
                                                        data-label="Departemen: {{ $row['departemen'] }}">
                                                        {{ number_format($row['total']) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ $allPT->count() + 2 }}"
                                                    class="text-center text-muted py-3">
                                                    Tidak ada data
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($depPTMatrix->count() > 1)
                                        <tfoot>
                                            <tr class="table-light fw-semibold">
                                                <td>Total per PT</td>
                                                @foreach ($allPT as $pt)
                                                    @php $colTotal = $depPTMatrix->sum(fn($r) => $r['pt'][$pt['singkatan']]['jumlah'] ?? 0); @endphp
                                                    <td class="text-center">
                                                        @if ($colTotal > 0)
                                                            <span class="drill-trigger siska-link" data-type="pt"
                                                                data-value="{{ $pt['id'] }}"
                                                                data-label="{{ $pt['nama'] }}">
                                                                {{ number_format($colTotal) }}
                                                            </span>
                                                        @else
                                                            —
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="text-center">{{ number_format($depPTMatrix->sum('total')) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif

        {{-- ====== ROW 1: Per PT + Bidang Usaha ====== --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm h-100 siska-section-card">
                    <div class="card-header siska-section-header"
                        style="background:linear-gradient(135deg,#1a4fcf 0%,#1a6fcf 100%);color:#fff;">
                        <i class="fas fa-building me-2" style="opacity:.85;"></i>Jumlah Karyawan per Perusahaan (PT)
                    </div>
                    <div class="card-body">
                        <canvas id="chartPerPT" height="190"></canvas>
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
                                        <tr class="drill-trigger siska-row-clickable" data-type="pt"
                                            data-value="{{ $item['id'] }}" data-label="{{ $item['label'] }}">
                                            <td>
                                                <span class="fw-semibold">{{ $item['singkatan'] }}</span><br>
                                                <small class="text-muted">{{ $item['label'] }}</small>
                                            </td>
                                            <td><small>{{ $item['bidang'] }}</small></td>
                                            <td class="text-end">
                                                <span
                                                    class="siska-num-badge bg-primary">{{ number_format($item['jumlah']) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-primary bg-opacity-75">
                                                    {{ $totalKaryawan > 0 ? round(($item['jumlah'] / $totalKaryawan) * 100, 1) : 0 }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm h-100 siska-section-card">
                    <div class="card-header siska-section-header"
                        style="background:linear-gradient(135deg,#0a7566 0%,#0d9488 100%);color:#fff;">
                        <i class="fas fa-industry me-2" style="opacity:.85;"></i>Pembagian per Bidang Usaha
                    </div>
                    <div class="card-body d-flex flex-column">
                        <canvas id="chartBidangUsaha" height="190"></canvas>
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
                                        <tr class="drill-trigger siska-row-clickable" data-type="bidang"
                                            data-value="{{ $item['label'] }}" data-label="{{ $item['label'] }}">
                                            <td>{{ $item['label'] }}</td>
                                            <td class="text-end">
                                                <span
                                                    class="siska-num-badge bg-success">{{ number_format($item['jumlah']) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-success bg-opacity-75">
                                                    {{ $totalKaryawan > 0 ? round(($item['jumlah'] / $totalKaryawan) * 100, 1) : 0 }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====== ROW 2: Pusat Cabang + Unit Kerja ====== --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm h-100 siska-section-card">
                    <div class="card-header siska-section-header"
                        style="background:linear-gradient(135deg,#3730a3 0%,#4c6ef5 100%);color:#fff;">
                        <i class="fas fa-map me-2" style="opacity:.85;"></i>Wilayah Kerja
                    </div>
                    <div class="card-body">
                        <canvas id="chartPusatCabang" height="190"></canvas>
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
                                        <tr class="drill-trigger siska-row-clickable" data-type="wilker"
                                            data-value="{{ $item['label'] }}" data-label="{{ $item['label'] }}">
                                            <td class="fw-semibold">{{ $item['label'] }}</td>
                                            <td class="text-end">
                                                <span class="siska-num-badge"
                                                    style="background:#4c6ef5">{{ number_format($item['jumlah']) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge" style="background:#4c6ef5">
                                                    {{ $totalKaryawan > 0 ? round(($item['jumlah'] / $totalKaryawan) * 100, 1) : 0 }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm h-100 siska-section-card">
                    <div class="card-header siska-section-header"
                        style="background:linear-gradient(135deg,#0369a1 0%,#0891b2 100%);color:#fff;">
                        <i class="fas fa-sitemap me-2" style="opacity:.85;"></i>Area Kerja
                    </div>
                    <div class="card-body">
                        <canvas id="chartUnitKerja" height="190"></canvas>
                        <div class="siska-table-mini mt-3">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Area Kerja</th>
                                        <th>Wilayah</th>
                                        <th class="text-end">Karyawan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($perUnitKerja as $item)
                                        <tr class="drill-trigger siska-row-clickable" data-type="unit_kerja"
                                            data-value="{{ $item['id'] }}" data-label="{{ $item['label'] }}">
                                            <td class="fw-semibold">{{ $item['label'] }}</td>
                                            <td><small class="text-muted">{{ $item['wilayah'] }}</small></td>
                                            <td class="text-end">
                                                <span class="siska-num-badge"
                                                    style="background:#0891b2">{{ number_format($item['jumlah']) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====== ROW 3: Departemen + Kontrak & Jabatan ====== --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm h-100 siska-section-card">
                    <div class="card-header siska-section-header"
                        style="background:linear-gradient(135deg,#b45309 0%,#d97706 100%);color:#fff;">
                        <i class="fas fa-layer-group me-2" style="opacity:.85;"></i>Per Departemen
                        <small class="ms-2" style="opacity:.7;font-weight:400;">(klik <i class="fas fa-sitemap"></i>
                            untuk lihat jabatan)</small>
                    </div>
                    <div class="card-body">
                        <canvas id="chartDepartemen" height="230"></canvas>
                        <div class="siska-table-mini mt-3">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Departemen</th>
                                        <th class="text-end">Karyawan</th>
                                        <th class="text-end">%</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($perDepartemen as $item)
                                        <tr class="siska-row-clickable">
                                            <td>
                                                <span class="badge siska-dep-badge me-1">{{ $item['singkatan'] }}</span>
                                                <span class="drill-trigger siska-link" data-type="departemen"
                                                    data-value="{{ $item['label'] }}"
                                                    data-label="Departemen: {{ $item['label'] }}">
                                                    {{ $item['label'] }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="siska-num-badge bg-warning text-dark drill-trigger"
                                                    data-type="departemen" data-value="{{ $item['label'] }}"
                                                    data-label="Departemen: {{ $item['label'] }}">
                                                    {{ number_format($item['jumlah']) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-warning text-dark bg-opacity-75">
                                                    {{ $totalKaryawan > 0 ? round(($item['jumlah'] / $totalKaryawan) * 100, 1) : 0 }}%
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-xs siska-btn-jabatan"
                                                    data-nama-dep="{{ $item['label'] }}"
                                                    title="Lihat breakdown jabatan">
                                                    <i class="fas fa-sitemap"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-flex flex-column gap-4">
                <div class="card siska-section-card">
                    <div class="card-header siska-section-header"
                        style="background:linear-gradient(135deg,#b91c1c 0%,#dc2626 100%);color:#fff;">
                        <i class="fas fa-file-contract me-2" style="opacity:.85;"></i>Tipe Kontrak Kerja
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-5"><canvas id="chartKontrak" height="170"></canvas></div>
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
                                            <tr class="drill-trigger siska-row-clickable" data-type="kontrak"
                                                data-value="{{ $item['id'] }}"
                                                data-label="Kontrak: {{ $item['label'] }}">
                                                <td>
                                                    <span class="badge siska-ktr-badge me-1">{{ $item['kode'] }}</span>
                                                    <small>{{ $item['label'] }}</small>
                                                </td>
                                                <td class="text-end">
                                                    <span
                                                        class="siska-num-badge bg-danger">{{ number_format($item['jumlah']) }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge bg-danger bg-opacity-75">
                                                        {{ $totalKaryawan > 0 ? round(($item['jumlah'] / $totalKaryawan) * 100, 1) : 0 }}%
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card siska-section-card flex-grow-1">
                    <div class="card-header siska-section-header"
                        style="background:linear-gradient(135deg,#5b21b6 0%,#7c3aed 100%);color:#fff;">
                        <i class="fas fa-user-tag me-2" style="opacity:.85;"></i>Pembagian per Jabatan
                    </div>
                    <div class="card-body p-0">
                        <div class="siska-jabatan-list">
                            @forelse($perJabatan->take(10) as $i => $item)
                                <div class="siska-jabatan-row drill-trigger" data-type="jabatan"
                                    data-value="{{ $item['id'] }}" data-label="Jabatan: {{ $item['label'] }}">
                                    <div class="siska-jabatan-rank">{{ $i + 1 }}</div>
                                    <div class="siska-jabatan-info">
                                        <div class="fw-semibold">{{ $item['label'] }}</div>
                                        <small class="text-muted">{{ $item['departemen'] }}</small>
                                    </div>
                                    <div class="siska-jabatan-bar-wrap">
                                        <div class="siska-jabatan-bar"
                                            style="width:{{ $totalKaryawan > 0 ? min(100, round(($item['jumlah'] / $totalKaryawan) * 100)) : 0 }}%">
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

    {{-- ====== MODAL DRILL-DOWN ====== --}}
    <div class="modal fade" id="drillModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header siska-modal-header">
                    <div class="d-flex flex-column">
                        <h5 class="modal-title mb-0" id="drillModalTitle">Detail Karyawan</h5>
                        <small id="drillModalSubtitle"
                            style="color:rgba(255,255,255,.7);font-size:.75rem;margin-top:2px;"></small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="drillLoading" class="text-center py-5">
                        <div class="spinner-border" style="color:var(--sk-blue)" role="status"></div>
                        <div class="mt-2 text-muted small">Memuat data karyawan...</div>
                    </div>
                    <div id="drillContent" style="display:none;">
                        <div class="px-3 pt-2 pb-2 d-flex justify-content-between align-items-center border-bottom"
                            style="background:#f8fafc;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-search" style="color:#94a3b8;font-size:.8rem;"></i>
                                <input type="text" id="drillSearch"
                                    class="form-control form-control-sm border-0 bg-transparent p-0"
                                    placeholder="Cari nama, NRK, jabatan, departemen..."
                                    style="width:280px;box-shadow:none;font-size:.83rem;">
                            </div>
                            <span id="drillCount" class="badge"
                                style="background:#e2e8f0;color:#475569;font-weight:600;font-size:.72rem;"></span>
                        </div>
                        <div class="table-responsive" style="max-height:460px;">
                            <table class="table table-sm table-hover table-striped mb-0" id="drillTable">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="8%">NRK</th>
                                        <th width="20%">Nama</th>
                                        <th width="5%">JK</th>
                                        <th width="11%">Perusahaan</th>
                                        <th width="12%">Departemen</th>
                                        <th width="13%">Jabatan</th>
                                        <th width="9%">Area Kerja</th>
                                        <th width="7%">Kontrak</th>
                                        <th width="7%">Status</th>
                                        <th width="9%">Tgl Masuk</th>
                                    </tr>
                                </thead>
                                <tbody id="drillTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                    <div id="drillEmpty" style="display:none;" class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>Tidak ada data karyawan
                    </div>
                </div>
                <div class="modal-footer justify-content-between py-2"
                    style="background:#f8fafc;border-top:1px solid #e2e8f0;">
                    <small class="text-muted" id="drillFooterInfo" style="font-size:.75rem;"></small>
                    <button type="button" class="btn btn-sm px-4"
                        style="background:#1a6fcf;color:#fff;border:none;border-radius:6px;"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== MODAL JABATAN ====== --}}
    <div class="modal fade" id="jabatanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header siska-modal-header-purple">
                    <div class="d-flex flex-column">
                        <h5 class="modal-title mb-0" id="jabatanModalTitle">Breakdown Jabatan</h5>
                        <small style="color:rgba(255,255,255,.7);font-size:.75rem;margin-top:2px;">
                            Daftar jabatan — klik angka atau "Lihat" untuk detail karyawan
                        </small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="jabatanLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <div class="mt-2 text-muted">Memuat data...</div>
                    </div>
                    <div id="jabatanContent" style="display:none;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Jabatan</th>
                                    <th width="12%">Singkatan</th>
                                    <th width="14%" class="text-end">Karyawan</th>
                                    <th width="22%">Proporsi</th>
                                    <th width="10%"></th>
                                </tr>
                            </thead>
                            <tbody id="jabatanTableBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between py-2"
                    style="background:#f8fafc;border-top:1px solid #e2e8f0;">
                    <small class="text-muted" id="jabatanFooterInfo" style="font-size:.75rem;"></small>
                    <button type="button" class="btn btn-sm px-4"
                        style="background:#7c3aed;color:#fff;border:none;border-radius:6px;"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .ringkasan-siska-page {
            --sk-blue: #1a6fcf;
            --sk-teal: #0d9488;
            --sk-indigo: #4c6ef5;
            --sk-cyan: #0891b2;
            --sk-orange: #d97706;
            --sk-red: #dc2626;
            --sk-purple: #7c3aed;
            --sk-green: #059669;
            font-family: 'Segoe UI', sans-serif;
        }

        .siska-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -.3px;
        }

        .siska-badge-total {
            background: #1e293b;
            color: #fff;
            border-radius: 8px;
        }

        .siska-filter-card {
            border: none;
            border-left: 4px solid var(--sk-blue);
            border-radius: 8px;
        }

        .siska-label {
            font-size: .73rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 4px;
        }

        .siska-chip {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: .75rem;
            font-weight: 600;
        }

        .siska-chip a {
            text-decoration: none;
            opacity: .7;
        }

        .siska-chip a:hover {
            opacity: 1;
        }

        .siska-chip-orange {
            background: #fff7ed;
            color: #c2410c;
            border: 1px solid #fed7aa;
        }

        .siska-chip-blue {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .siska-chip-indigo {
            background: #eef2ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
        }

        .siska-chip-cyan {
            background: #ecfeff;
            color: #0e7490;
            border: 1px solid #a5f3fc;
        }

        .siska-chip-green {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .siska-chip-red {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Stat cards */
        .siska-stat-card {
            border-radius: 12px;
            padding: 1.1rem 1rem;
            color: #fff;
            cursor: pointer;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform .18s, box-shadow .18s;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
        }

        .siska-stat-card::before {
            content: '';
            position: absolute;
            top: -18px;
            right: -18px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .1);
        }

        .siska-stat-card::after {
            content: '';
            position: absolute;
            bottom: -28px;
            right: 10px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
        }

        .siska-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, .2);
        }

        .siska-stat-icon {
            font-size: 1.25rem;
            opacity: .75;
            background: rgba(255, 255, 255, .15);
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .siska-stat-num {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -1px;
            margin-top: .35rem;
        }

        .siska-stat-lbl {
            font-size: .72rem;
            font-weight: 500;
            opacity: .85;
            margin-top: .2rem;
        }

        .siska-stat-hint {
            font-size: .62rem;
            opacity: 0;
            transition: opacity .2s;
            margin-top: .15rem;
        }

        .siska-stat-card:hover .siska-stat-hint {
            opacity: .75;
        }

        .siska-stat-total {
            background: linear-gradient(145deg, #1e293b, #334155);
        }

        .siska-stat-aktif {
            background: linear-gradient(145deg, #047857, #10b981);
        }

        .siska-stat-nonaktif {
            background: linear-gradient(145deg, #b91c1c, #ef4444);
        }

        .siska-stat-calon {
            background: linear-gradient(145deg, #b45309, #f59e0b);
        }

        .siska-stat-laki {
            background: linear-gradient(145deg, #1d4ed8, #3b82f6);
        }

        .siska-stat-perempuan {
            background: linear-gradient(145deg, #7e22ce, #a855f7);
        }

        /* Section cards */
        .siska-section-card {
            border: 1px solid #e9eef5 !important;
            border-radius: 12px !important;
            overflow: hidden;
            box-shadow: 0 1px 6px rgba(0, 0, 0, .06), 0 4px 14px rgba(0, 0, 0, .04);
        }

        .siska-section-card.flush-table>.card-body {
            padding: 0 !important;
        }

        .siska-section-header {
            font-weight: 600;
            font-size: .875rem;
            padding: .7rem 1.1rem;
            color: #fff;
            letter-spacing: .1px;
        }

        /* Tables */
        .siska-table-mini {
            max-height: none;
            overflow-y: auto;
        }

        .siska-table-mini::-webkit-scrollbar {
            width: 4px;
        }

        .siska-table-mini::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .siska-row-clickable {
            cursor: pointer;
        }

        .siska-row-clickable:hover {
            background: #f0f4ff !important;
        }

        .siska-link {
            cursor: pointer;
            color: inherit;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .siska-link:hover {
            color: var(--sk-blue);
        }

        .siska-num-badge {
            display: inline-block;
            color: #fff;
            padding: 2px 10px;
            border-radius: 12px;
            font-weight: 700;
            font-size: .82rem;
            cursor: pointer;
            transition: transform .15s;
        }

        .siska-num-badge:hover {
            transform: scale(1.1);
        }

        .siska-dep-badge {
            background: #f1f5f9;
            color: #475569;
            font-size: .65rem;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .siska-ktr-badge {
            background: #fef2f2;
            color: #991b1b;
            font-size: .65rem;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Matrix */
        .siska-matrix-table {
            font-size: .82rem;
        }

        .siska-matrix-dep-col {
            min-width: 160px;
            background: #f8fafc;
        }

        .siska-matrix-total-col {
            min-width: 80px;
            background: #f0f4ff;
        }

        .siska-matrix-cell {
            padding: 6px 10px !important;
            vertical-align: middle;
        }

        .siska-matrix-cell.no-data {
            background: #fafafa;
        }

        .siska-matrix-num {
            cursor: pointer;
            font-weight: 700;
            color: #1e40af;
            font-size: .82rem;
        }

        .siska-matrix-num:hover {
            text-decoration: underline;
            color: #1d4ed8;
        }

        /* Jabatan */
        .siska-jabatan-list {
            padding: .4rem 0;
        }

        .siska-jabatan-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: .4rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: background .15s;
        }

        .siska-jabatan-row:last-child {
            border-bottom: none;
        }

        .siska-jabatan-row:hover {
            background: #f5f0ff;
        }

        .siska-jabatan-rank {
            width: 22px;
            height: 22px;
            background: var(--sk-purple);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .68rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .siska-jabatan-info {
            min-width: 130px;
            flex: 0 0 auto;
        }

        .siska-jabatan-info .fw-semibold {
            font-size: .82rem;
        }

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
            transition: width .8s ease;
        }

        .siska-jabatan-count {
            font-size: .82rem;
            font-weight: 700;
            color: #374151;
            min-width: 32px;
            text-align: right;
        }

        /* Modal */
        #drillModal .modal-header,
        .siska-modal-header {
            background: var(--sk-blue) !important;
            color: #fff !important;
            border-bottom: none !important;
            padding: .9rem 1.25rem !important;
        }

        #jabatanModal .modal-header,
        .siska-modal-header-purple {
            background: var(--sk-purple) !important;
            color: #fff !important;
            border-bottom: none !important;
            padding: .9rem 1.25rem !important;
        }

        #drillModal .modal-title,
        #jabatanModal .modal-title {
            color: #fff !important;
            font-size: 1rem;
            font-weight: 700;
        }

        #drillTable tbody tr td {
            padding: .55rem .75rem !important;
            vertical-align: middle;
            font-size: .82rem;
        }

        #drillTable thead th {
            font-size: .75rem;
            padding: .55rem .75rem !important;
            font-weight: 600;
            letter-spacing: .3px;
        }

        #drillTable tbody tr:hover {
            background: #f0f4ff !important;
        }

        .siska-btn-jabatan {
            background: #f0eaff;
            border: none;
            color: #7c3aed;
            border-radius: 6px;
            padding: 2px 7px;
            font-size: .7rem;
            transition: all .15s;
        }

        .siska-btn-jabatan:hover {
            background: #7c3aed;
            color: #fff;
        }

        .btn-xs {
            padding: .15rem .4rem;
            font-size: .72rem;
        }

        .sts-aktif {
            background: #d1fae5;
            color: #065f46;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: .72rem;
            font-weight: 600;
        }

        .sts-nonaktif {
            background: #fee2e2;
            color: #991b1b;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: .72rem;
            font-weight: 600;
        }

        .sts-calon {
            background: #fef3c7;
            color: #92400e;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: .72rem;
            font-weight: 600;
        }

        @media(max-width:768px) {
            .siska-stat-num {
                font-size: 1.4rem;
            }

            .siska-jabatan-info {
                min-width: 80px;
            }
        }

        .siska-date-input {
            font-size: .78rem;
            padding: .25rem .4rem;
            min-width: 0;
            flex: 1;
        }

        .siska-filter-hint {
            font-size: .65rem;
            color: #94a3b8;
            margin-top: 3px;
            line-height: 1.3;
        }

        /* ── Filter divider baris 2 ── */
        .siska-filter-divider {
            margin: 4px 0 0;
            border-color: #e2e8f0;
        }

        /* ── Input group text kecil ── */
        .siska-ig-text {
            font-size: .72rem;
            font-weight: 600;
            padding: .25rem .45rem;
            border-radius: 0;
        }

        /* ── Toggle switch Top Mgmt ── */
        .siska-topmgmt-toggle {
            position: relative;
            height: 31px;
            /* sama tinggi dengan form-select-sm */
            display: flex;
            align-items: center;
        }

        /* Input group warna */
        .siska-ig-green {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
            font-size: .72rem;
            font-weight: 600;
            padding: .25rem .5rem;
        }

        .siska-ig-red {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
            font-size: .72rem;
            font-weight: 600;
            padding: .25rem .5rem;
        }

        /* Divider */
        .siska-filter-divider {
            margin: 4px 0 0;
            border-color: #e2e8f0;
        }

        /* Hint teks */
        .siska-filter-hint {
            font-size: .65rem;
            color: #94a3b8;
            line-height: 1.3;
        }

        /* Toggle Top Manajement */
        .siska-topmgmt-toggle {
            position: relative;
        }

        .siska-toggle-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .siska-toggle-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
            padding: 0 12px 0 6px;
            height: 31px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            background: #f8fafc;
            font-size: .78rem;
            font-weight: 600;
            color: #64748b;
            transition: background .2s, border-color .2s, color .2s;
            width: 100%;
        }

        .siska-toggle-thumb {
            flex-shrink: 0;
            width: 32px;
            height: 18px;
            border-radius: 10px;
            background: #cbd5e1;
            position: relative;
            transition: background .2s;
        }

        .siska-toggle-thumb::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
            transition: transform .2s;
        }

        .siska-toggle-input:checked+.siska-toggle-label {
            background: #fffbeb;
            border-color: #f59e0b;
            color: #92400e;
        }

        .siska-toggle-input:checked+.siska-toggle-label .siska-toggle-thumb {
            background: #f59e0b;
        }

        .siska-toggle-input:checked+.siska-toggle-label .siska-toggle-thumb::after {
            transform: translateX(14px);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const DETAIL_URL = '{{ route('reports.ringkasan-siska.detail') }}';
            const JABATAN_URL = '{{ route('reports.ringkasan-siska.jabatan', ':dep') }}';
            const AREA_URL = '{{ route('reports.ringkasan-siska.area-by-wilker') }}';

            const filterEl = document.getElementById('activeFilters');
            const gFilters = {
                filter_perusahaan: filterEl.dataset.filterPerusahaan || '',
                filter_wilker: filterEl.dataset.filterWilker || '',
                filter_area: filterEl.dataset.filterArea || '',
                filter_status: filterEl.dataset.filterStatus || '',
                filter_kontrak: filterEl.dataset.filterKontrak || '',
                filter_departemen: filterEl.dataset.filterDepartemen || '',
                filter_tgl_masuk_dari: filterEl.dataset.filterTglMasukDari || '',
                filter_tgl_masuk_sampai: filterEl.dataset.filterTglMasukSampai || '',
                filter_tgl_phk_dari: filterEl.dataset.filterTglPhkDari || '',
                filter_tgl_phk_sampai: filterEl.dataset.filterTglPhkSampai || '',
                filter_include_top_mgmt: filterEl.dataset.filterIncludeTopMgmt || '0',
            };

            // ---- Dropdown Area Kerja: dinamis saat Wilayah Kerja berubah ----
            const filterWilker = document.getElementById('filterWilker');
            const filterArea = document.getElementById('filterArea');
            if (filterWilker && filterArea) {
                filterWilker.addEventListener('change', function() {
                    const wilker = this.value;
                    const params = wilker ? '?wilker=' + encodeURIComponent(wilker) : '';
                    fetch(AREA_URL + params)
                        .then(r => r.json())
                        .then(data => {
                            filterArea.innerHTML = '<option value="">— Semua Area —</option>';
                            data.forEach(function(ak) {
                                const label = ak.area_krj + (ak.singkatan_wk ? ' (' + ak
                                    .singkatan_wk + ')' : '');
                                filterArea.innerHTML +=
                                    `<option value="${ak.id}">${label}</option>`;
                            });
                        });
                });
            }

            /* ---- Chart helpers ---- */
            const PAL = ['#1a6fcf', '#0d9488', '#4c6ef5', '#0891b2', '#d97706', '#dc2626',
                '#7c3aed', '#059669', '#f59e0b', '#6366f1', '#14b8a6', '#ec4899'
            ];
            Chart.defaults.font.family = "'Segoe UI',sans-serif";
            Chart.defaults.font.size = 11;
            Chart.defaults.color = '#64748b';

            const TTP = {
                backgroundColor: '#1e293b',
                padding: 10,
                cornerRadius: 6,
                callbacks: {
                    label: c => {
                        // Untuk vertical bar: nilai ada di c.parsed.y
                        // Untuk horizontal bar (indexAxis:'y'): nilai ada di c.parsed.x
                        const isHoriz = c.chart.options.indexAxis === 'y';
                        const v = isHoriz ? c.parsed.x : c.parsed.y;
                        return ` ${Number(v || 0).toLocaleString('id-ID')} karyawan`;
                    }
                }
            };

            function mkBar(id, labels, data, horiz, color, onClickFn) {
                const ctx = document.getElementById(id);
                if (!ctx) return null;
                return new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Karyawan',
                            data,
                            backgroundColor: color || data.map((_, i) => PAL[i % PAL.length]),
                            borderRadius: 5,
                        }]
                    },
                    options: {
                        indexAxis: horiz ? 'y' : 'x',
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: TTP
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: horiz ? '#f1f5f9' : 'transparent'
                                },
                                ticks: {
                                    maxRotation: 30
                                }
                            },
                            y: {
                                grid: {
                                    color: horiz ? 'transparent' : '#f1f5f9'
                                }
                            },
                        },
                        onClick: (e, els) => {
                            if (els.length && onClickFn) onClickFn(els[0].index);
                        }
                    }
                });
            }

            /* ---- Build Charts ----
               PENTING: $perPT dll adalah Collection of arrays, bukan objects.
               Kita output sebagai JSON array-of-objects lalu map di JS. */
            const perPTData = @json($perPT->values());
            const perBUData = @json($perBidangUsaha->values());
            const perPCData = @json($perPusatCabang->values());
            const perUKData = @json($perUnitKerja->values());
            const perDepData = @json($perDepartemen->values());
            const perKtrData = @json($perKontrak->values());

            // Per PT
            mkBar('chartPerPT',
                perPTData.map(r => r.singkatan),
                perPTData.map(r => r.jumlah),
                true, null,
                i => openDrill('pt', perPTData[i].id, perPTData[i].label)
            );

            // Bidang Usaha (doughnut)
            new Chart(document.getElementById('chartBidangUsaha'), {
                type: 'doughnut',
                data: {
                    labels: perBUData.map(r => r.label),
                    datasets: [{
                        data: perBUData.map(r => r.jumlah),
                        backgroundColor: PAL,
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 8
                    }]
                },
                options: {
                    cutout: '60%',
                    responsive: true,
                    plugins: {
                        tooltip: TTP,
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 10,
                                boxWidth: 12
                            }
                        }
                    },
                    onClick: (e, els) => {
                        if (els.length) openDrill('bidang', perBUData[els[0].index].label, perBUData[
                            els[0].index].label);
                    }
                }
            });

            // Wilayah Kerja (pie)
            new Chart(document.getElementById('chartPusatCabang'), {
                type: 'pie',
                data: {
                    labels: perPCData.map(r => r.label),
                    datasets: [{
                        data: perPCData.map(r => r.jumlah),
                        backgroundColor: ['#4c6ef5', '#6366f1', '#818cf8', '#a5b4fc', '#c7d2fe'],
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: TTP,
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 10,
                                boxWidth: 12
                            }
                        }
                    },
                    onClick: (e, els) => {
                        if (els.length) openDrill('wilker', perPCData[els[0].index].label, perPCData[
                            els[0].index].label);
                    }
                }
            });

            // Area Kerja (bar)
            mkBar('chartUnitKerja',
                perUKData.map(r => r.label),
                perUKData.map(r => r.jumlah),
                false, '#0891b2',
                i => openDrill('unit_kerja', perUKData[i].id, perUKData[i].label)
            );

            // Departemen (bar)
            mkBar('chartDepartemen',
                perDepData.map(r => r.singkatan),
                perDepData.map(r => r.jumlah),
                true, null,
                i => openDrill('departemen', perDepData[i].label, 'Departemen: ' + perDepData[i].label)
            );

            // Kontrak (doughnut)
            new Chart(document.getElementById('chartKontrak'), {
                type: 'doughnut',
                data: {
                    labels: perKtrData.map(r => r.kode),
                    datasets: [{
                        data: perKtrData.map(r => r.jumlah),
                        backgroundColor: ['#dc2626', '#d97706', '#059669', '#1a6fcf', '#7c3aed'],
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 8
                    }]
                },
                options: {
                    cutout: '55%',
                    responsive: true,
                    plugins: {
                        tooltip: TTP,
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 10,
                                boxWidth: 12
                            }
                        }
                    },
                    onClick: (e, els) => {
                        if (!els.length) return;
                        const r = perKtrData[els[0].index];
                        openDrill('kontrak', r.id, 'Kontrak: ' + r.label);
                    }
                }
            });

            /* ---- Delegation ---- */
            document.addEventListener('click', function(e) {
                const dt = e.target.closest('.drill-trigger');
                if (dt) {
                    e.stopPropagation();
                    openDrill(dt.dataset.type, dt.dataset.value, dt.dataset.label);
                    return;
                }
                const bj = e.target.closest('.siska-btn-jabatan');
                if (bj) {
                    e.stopPropagation();
                    openJabatan(bj.dataset.namaDep);
                }
            });

            /* ---- DRILL MODAL ---- */
            function openDrill(type, value, label) {
                const modal = new bootstrap.Modal(document.getElementById('drillModal'));
                document.getElementById('drillModalTitle').textContent = label || 'Detail Karyawan';
                document.getElementById('drillModalSubtitle').textContent = '';
                document.getElementById('drillLoading').style.display = 'block';
                document.getElementById('drillContent').style.display = 'none';
                document.getElementById('drillEmpty').style.display = 'none';
                document.getElementById('drillSearch').value = '';
                modal.show();

                const params = new URLSearchParams({
                    type,
                    value,
                    ...gFilters
                });
                fetch(DETAIL_URL + '?' + params)
                    .then(r => r.json())
                    .then(res => {
                        document.getElementById('drillLoading').style.display = 'none';
                        document.getElementById('drillModalSubtitle').textContent = res.total + ' karyawan';
                        document.getElementById('drillFooterInfo').textContent = 'Total: ' + res.total
                            .toLocaleString('id-ID') + ' karyawan';
                        document.getElementById('drillCount').textContent = res.total + ' karyawan';
                        if (!res.data?.length) {
                            document.getElementById('drillEmpty').style.display = 'block';
                            return;
                        }
                        renderDrillRows(res.data);
                        document.getElementById('drillContent').style.display = 'block';
                    })
                    .catch(() => {
                        document.getElementById('drillLoading').style.display = 'none';
                        document.getElementById('drillEmpty').style.display = 'block';
                    });
            }

            function renderDrillRows(data) {
                const stsClass = s => s === 'AKTIF' ? 'sts-aktif' : s === 'NON-AKTIF' ? 'sts-nonaktif' :
                    'sts-calon';
                const gIcon = s => s === 'LAKI-LAKI' ?
                    '<span style="color:#3b82f6;font-size:.8rem;">♂ L</span>' :
                    '<span style="color:#a855f7;font-size:.8rem;">♀ P</span>';

                document.getElementById('drillTableBody').innerHTML = data.map((k, i) => `
            <tr>
                <td class="text-muted">${i + 1}</td>
                <td><code style="font-size:.75rem;color:#64748b;background:#f1f5f9;padding:1px 5px;border-radius:4px;">${k.nrk}</code></td>
                <td style="font-weight:600;font-size:.84rem;">${k.nama}</td>
                <td>${gIcon(k.sex)}</td>
                <td><span style="font-size:.75rem;background:#eff6ff;color:#1d4ed8;padding:2px 6px;border-radius:5px;font-weight:600;">${k.perusahaan}</span></td>
                <td style="font-size:.78rem;color:#475569;">${k.departemen}</td>
                <td style="font-size:.78rem;color:#374151;">${k.jabatan}</td>
                <td style="font-size:.75rem;color:#64748b;">${k.unit_kerja}</td>
                <td><span style="font-size:.7rem;background:#e0e7ff;color:#3730a3;padding:2px 6px;border-radius:5px;font-weight:600;display:inline-block;max-width:108px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${k.kontrak}">${k.kontrak}</span></td>
                <td><span class="${stsClass(k.sts_kry)}">${k.sts_kry}</span></td>
                <td style="font-size:.75rem;color:#94a3b8;white-space:nowrap;">${k.tgl_masuk}</td>
            </tr>`).join('');
            }

            document.getElementById('drillSearch').addEventListener('input', function() {
                const q = this.value.toLowerCase();
                let vis = 0;
                document.querySelectorAll('#drillTableBody tr').forEach(r => {
                    const show = r.textContent.toLowerCase().includes(q);
                    r.style.display = show ? '' : 'none';
                    if (show) vis++;
                });
                document.getElementById('drillCount').textContent = vis + ' karyawan';
            });

            /* ---- JABATAN MODAL ---- */
            function openJabatan(namaDep) {
                const modal = new bootstrap.Modal(document.getElementById('jabatanModal'));
                document.getElementById('jabatanModalTitle').textContent = 'Breakdown Jabatan — ' + namaDep;
                document.getElementById('jabatanLoading').style.display = 'block';
                document.getElementById('jabatanContent').style.display = 'none';
                modal.show();

                const url = JABATAN_URL.replace(':dep', encodeURIComponent(namaDep));
                const params = new URLSearchParams(gFilters);
                fetch(url + '?' + params)
                    .then(r => r.json())
                    .then(res => {
                        document.getElementById('jabatanLoading').style.display = 'none';
                        const total = res.data.reduce((s, r) => s + r.jumlah, 0);
                        document.getElementById('jabatanFooterInfo').textContent = 'Total: ' + total
                            .toLocaleString('id-ID') + ' karyawan';
                        document.getElementById('jabatanTableBody').innerHTML = res.data.map((r, i) => `
                    <tr>
                        <td class="text-muted">${i + 1}</td>
                        <td class="fw-semibold">${r.jabatan}</td>
                        <td><span class="badge bg-secondary">${r.singkatan}</span></td>
                        <td class="text-end">
                            <span class="siska-num-badge drill-trigger" style="background:#7c3aed;cursor:pointer"
                                data-type="jabatan" data-value="${r.id}" data-label="Jabatan: ${r.jabatan}">
                                ${r.jumlah.toLocaleString('id-ID')}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="flex:1;height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                                    <div style="width:${total > 0 ? Math.round(r.jumlah / total * 100) : 0}%;height:100%;background:#7c3aed;border-radius:4px;"></div>
                                </div>
                                <small class="text-muted" style="min-width:32px">${total > 0 ? Math.round(r.jumlah / total * 100) : 0}%</small>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-xs drill-trigger" style="background:#f0eaff;border:none;color:#7c3aed;border-radius:6px;padding:2px 8px;font-size:.7rem;"
                                data-type="jabatan" data-value="${r.id}" data-label="Jabatan: ${r.jabatan}">
                                <i class="fas fa-users me-1"></i>Lihat
                            </button>
                        </td>
                    </tr>`).join('');
                        document.getElementById('jabatanContent').style.display = 'block';
                    })
                    .catch(() => {
                        document.getElementById('jabatanLoading').style.display = 'none';
                    });
            }

            document.getElementById('jabatanModal').addEventListener('click', function(e) {
                const el = e.target.closest('.drill-trigger');
                if (!el) return;
                bootstrap.Modal.getInstance(document.getElementById('jabatanModal'))?.hide();
                setTimeout(() => openDrill(el.dataset.type, el.dataset.value, el.dataset.label), 350);
            });

        });
    </script>
@endpush
