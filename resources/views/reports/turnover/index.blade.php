@extends('layouts.app')

@section('title', 'Turnover Karyawan')

@section('content')
    <div class="container-fluid to-page">

        {{-- ====== HEADER ====== --}}
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
            <div>
                <h4 class="to-title mb-0"><i class="fas fa-door-open me-2"></i>Turnover Karyawan</h4>
                <small class="text-muted">
                    Analisis resign & PHK —
                    {{ $dari->translatedFormat('d M Y') }} s/d {{ $sampai->translatedFormat('d M Y') }}
                </small>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge to-badge-rate px-3 py-2 fs-6">
                    <i class="fas fa-percentage me-1"></i>Turnover Rate:
                    <strong>{{ $turnoverRate }}%</strong>
                </span>
                <span class="badge to-badge-resign px-3 py-2 fs-6">
                    <i class="fas fa-user-minus me-1"></i>Resign: <strong>{{ number_format($totalResign) }}</strong>
                </span>
            </div>
        </div>

        {{-- ====== FILTER PANEL ====== --}}
        <div class="card shadow-sm mb-4 to-filter-card" id="toActiveFilters"
            data-filter-perusahaan="{{ $currentFilters['perusahaan'] }}"
            data-filter-wilker="{{ $currentFilters['wilker'] }}" data-filter-area="{{ $currentFilters['area'] }}"
            data-filter-kontrak="{{ $currentFilters['kontrak'] }}"
            data-filter-departemen="{{ $currentFilters['departemen'] }}"
            data-filter-tgl-dari="{{ $currentFilters['tgl_dari'] }}"
            data-filter-tgl-sampai="{{ $currentFilters['tgl_sampai'] }}"
            data-filter-include-top-mgmt="{{ $currentFilters['include_top_mgmt'] }}">

            <div class="card-header d-flex justify-content-between align-items-center"
                style="background:#f8fafc;border-bottom:1px solid #e2e8f0;border-left:4px solid #dc2626;">
                <span class="fw-semibold" style="color:#1e293b;font-size:.875rem;">
                    <i class="fas fa-sliders-h me-2" style="color:#dc2626;"></i>Filter & Periode
                </span>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('reports.turnover.index') }}" id="toFilterForm">
                    <div class="row g-3 align-items-end">

                        {{-- Baris 1 --}}
                        <div class="col-md-3">
                            <label class="form-label to-label">Perusahaan (PT)</label>
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
                            <label class="form-label to-label">Wilayah Kerja</label>
                            <select name="filter_wilker" class="form-select form-select-sm" id="toFilterWilker">
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
                            <label class="form-label to-label">Area Kerja</label>
                            <select name="filter_area" class="form-select form-select-sm" id="toFilterArea">
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
                            <label class="form-label to-label">Departemen</label>
                            <select name="filter_departemen" class="form-select form-select-sm">
                                <option value="">— Semua Departemen —</option>
                                @foreach ($departemenOptions as $dep)
                                    <option value="{{ $dep->nama_dep }}"
                                        {{ $currentFilters['departemen'] == $dep->nama_dep ? 'selected' : '' }}>
                                        {{ $dep->singkatan_dep ? '[' . $dep->singkatan_dep . '] ' : '' }}{{ $dep->nama_dep }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Baris 2 --}}
                        <div class="col-md-3">
                            <label class="form-label to-label">Tipe Kontrak</label>
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
                            <label class="form-label to-label">
                                <i class="fas fa-crown me-1" style="color:#b45309;"></i>Top Manajement
                            </label>
                            <div class="siska-topmgmt-toggle">
                                <input type="hidden" name="filter_include_top_mgmt" value="0">
                                <input type="checkbox" name="filter_include_top_mgmt" id="toToggleTopMgmt" value="1"
                                    class="siska-toggle-input"
                                    {{ $currentFilters['include_top_mgmt'] === '1' ? 'checked' : '' }}>
                                <label for="toToggleTopMgmt" class="siska-toggle-label" id="toToggleLabel">
                                    <span class="siska-toggle-thumb"></span>
                                    <span class="siska-toggle-text">
                                        {{ $currentFilters['include_top_mgmt'] === '1' ? 'Disertakan' : 'Dikecualikan' }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- Spacer --}}
                        <div class="col-md-6"></div>

                        {{-- Divider --}}
                        <div class="col-12">
                            <hr style="border-color:#e2e8f0;margin:0;">
                        </div>

                        {{-- Baris 3: Periode + Tombol --}}
                        <div class="col-12">
                            <div class="d-flex align-items-end gap-3">

                                <div class="flex-grow-1">
                                    <label class="form-label to-label">
                                        <i class="fas fa-calendar-alt me-1" style="color:#dc2626;"></i>
                                        Periode Resign / PHK
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text to-ig-red">Dari</span>
                                        <input type="date" name="filter_tgl_dari" class="form-control form-control-sm"
                                            value="{{ $currentFilters['tgl_dari'] }}">
                                        <span class="input-group-text to-ig-red">S/d</span>
                                        <input type="date" name="filter_tgl_sampai" class="form-control form-control-sm"
                                            value="{{ $currentFilters['tgl_sampai'] }}">
                                    </div>
                                    <div class="to-hint mt-1">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Filter berdasarkan tanggal PHK / resign karyawan
                                    </div>
                                </div>

                                {{-- Shortcut tahun --}}
                                <div class="flex-shrink-0" style="padding-bottom:1.25rem;">
                                    @foreach ([date('Y'), date('Y') - 1, date('Y') - 2] as $yr)
                                        <a href="{{ route(
                                            'reports.turnover.index',
                                            array_merge($currentFilters, [
                                                'filter_tgl_dari' => $yr . '-01-01',
                                                'filter_tgl_sampai' => $yr . '-12-31',
                                            ]),
                                        ) }}"
                                            class="btn btn-outline-secondary btn-sm me-1 {{ $dari->year == $yr && $sampai->month == 12 ? 'active' : '' }}">
                                            {{ $yr }}
                                        </a>
                                    @endforeach
                                </div>

                                <div class="d-flex gap-2 flex-shrink-0" style="padding-bottom:1.25rem;">
                                    <a href="{{ route('reports.turnover.index') }}"
                                        class="btn btn-outline-secondary btn-sm px-3">
                                        <i class="fas fa-undo me-1"></i>Reset
                                    </a>
                                    <button type="submit" class="btn btn-danger btn-sm px-4">
                                        <i class="fas fa-search me-1"></i>Terapkan
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- Active Chips --}}
                    @php $hasChip = array_filter(array_diff_key($currentFilters, ['tgl_dari'=>1,'tgl_sampai'=>1,'include_top_mgmt'=>1])); @endphp
                    @if ($hasChip || $currentFilters['include_top_mgmt'] === '1')
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <small class="text-muted align-self-center">Filter aktif:</small>
                            @if ($currentFilters['perusahaan'])
                                @php $pc = $perusahaans->find($currentFilters['perusahaan']); @endphp
                                <span class="to-chip to-chip-blue">
                                    <i class="fas fa-building me-1"></i>{{ $pc?->nama_prs2 ?? $pc?->nama_prs1 }}
                                    <a href="{{ route('reports.turnover.index', array_merge($currentFilters, ['filter_perusahaan' => ''])) }}"
                                        class="ms-1 text-inherit">×</a>
                                </span>
                            @endif
                            @if ($currentFilters['wilker'])
                                <span class="to-chip to-chip-indigo">
                                    <i class="fas fa-map me-1"></i>{{ $currentFilters['wilker'] }}
                                    <a href="{{ route('reports.turnover.index', array_merge($currentFilters, ['filter_wilker' => '', 'filter_area' => ''])) }}"
                                        class="ms-1 text-inherit">×</a>
                                </span>
                            @endif
                            @if ($currentFilters['area'])
                                @php $ac = $areaKerjaOptions->firstWhere('id', $currentFilters['area']); @endphp
                                <span class="to-chip to-chip-cyan">
                                    <i
                                        class="fas fa-map-marker-alt me-1"></i>{{ $ac?->area_krj ?? $currentFilters['area'] }}
                                    <a href="{{ route('reports.turnover.index', array_merge($currentFilters, ['filter_area' => ''])) }}"
                                        class="ms-1 text-inherit">×</a>
                                </span>
                            @endif
                            @if ($currentFilters['departemen'])
                                <span class="to-chip to-chip-orange">
                                    <i class="fas fa-layer-group me-1"></i>{{ $currentFilters['departemen'] }}
                                    <a href="{{ route('reports.turnover.index', array_merge($currentFilters, ['filter_departemen' => ''])) }}"
                                        class="ms-1 text-inherit">×</a>
                                </span>
                            @endif
                            @if ($currentFilters['kontrak'])
                                @php $kc = $kontrakOptions->find($currentFilters['kontrak']); @endphp
                                <span class="to-chip to-chip-red">
                                    <i class="fas fa-file-contract me-1"></i>{{ $kc?->singkatan_ktr ?? $kc?->kode_ktr }}
                                    <a href="{{ route('reports.turnover.index', array_merge($currentFilters, ['filter_kontrak' => ''])) }}"
                                        class="ms-1 text-inherit">×</a>
                                </span>
                            @endif
                            @if ($currentFilters['include_top_mgmt'] === '1')
                                <span class="to-chip to-chip-orange">
                                    <i class="fas fa-crown me-1"></i>Termasuk Top Manajement
                                    <a href="{{ route('reports.turnover.index', array_merge($currentFilters, ['filter_include_top_mgmt' => '0'])) }}"
                                        class="ms-1 text-inherit">×</a>
                                </span>
                            @endif
                        </div>
                    @endif

                </form>
            </div>
        </div>

        {{-- ====== STAT CARDS ====== --}}
        <div class="row g-3 mb-4">
            @php
                $statCards = [
                    [
                        'icon' => 'fa-user-minus',
                        'num' => $totalResign,
                        'lbl' => 'Total Resign/PHK',
                        'cls' => 'to-stat-resign',
                        'type' => 'all',
                        'val' => '',
                    ],
                    [
                        'icon' => 'fa-percentage',
                        'num' => $turnoverRate . '%',
                        'lbl' => 'Turnover Rate',
                        'cls' => 'to-stat-rate',
                        'type' => '',
                        'val' => '',
                    ],
                    [
                        'icon' => 'fa-user-plus',
                        'num' => $totalMasuk,
                        'lbl' => 'Karyawan Masuk',
                        'cls' => 'to-stat-masuk',
                        'type' => '',
                        'val' => '',
                    ],
                    [
                        'icon' => 'fa-users',
                        'num' => round($rataAktif),
                        'lbl' => 'Rata-rata Aktif',
                        'cls' => 'to-stat-aktif',
                        'type' => '',
                        'val' => '',
                    ],
                    [
                        'icon' => 'fa-sign-in-alt',
                        'num' => $aktifAwal,
                        'lbl' => 'Aktif Awal Periode',
                        'cls' => 'to-stat-awal',
                        'type' => '',
                        'val' => '',
                    ],
                    [
                        'icon' => 'fa-sign-out-alt',
                        'num' => $aktifAkhir,
                        'lbl' => 'Aktif Akhir Periode',
                        'cls' => 'to-stat-akhir',
                        'type' => '',
                        'val' => '',
                    ],
                ];
            @endphp
            @foreach ($statCards as $s)
                <div class="col-6 col-md-2">
                    <div class="to-stat-card {{ $s['cls'] }} {{ $s['type'] ? 'drill-trigger' : '' }}"
                        data-type="{{ $s['type'] }}" data-value="{{ $s['val'] }}"
                        data-label="{{ $s['lbl'] }}">
                        <div class="to-stat-icon"><i class="fas {{ $s['icon'] }}"></i></div>
                        <div class="to-stat-num">{{ is_numeric($s['num']) ? number_format($s['num']) : $s['num'] }}</div>
                        <div class="to-stat-lbl">{{ $s['lbl'] }}</div>
                        @if ($s['type'])
                            <div class="to-stat-hint"><i class="fas fa-mouse-pointer me-1"></i>Klik detail</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ====== TREN BULANAN ====== --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card to-section-card">
                    <div class="card-header to-section-header"
                        style="background:linear-gradient(135deg,#b91c1c 0%,#dc2626 100%);color:#fff;">
                        <i class="fas fa-chart-line me-2" style="opacity:.85;"></i>Tren Resign per Bulan
                        <small class="ms-2 fw-normal" style="opacity:.7;">klik bar untuk lihat detail</small>
                    </div>
                    <div class="card-body">
                        @if ($resignPerBulan->count() > 0)
                            <canvas id="chartTrenBulan" height="80"></canvas>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-chart-bar fa-2x mb-2 d-block opacity-25"></i>
                                Tidak ada data resign pada periode ini
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ====== BARIS ANALISIS ====== --}}
        <div class="row g-4 mb-4">

            {{-- Per Departemen --}}
            <div class="col-lg-6">
                <div class="card to-section-card h-100">
                    <div class="card-header to-section-header"
                        style="background:linear-gradient(135deg,#b45309 0%,#d97706 100%);color:#fff;">
                        <i class="fas fa-layer-group me-2" style="opacity:.85;"></i>Resign per Departemen
                    </div>
                    <div class="card-body">
                        <canvas id="chartResignDep" height="200"></canvas>
                        <div class="to-table-mini mt-3">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Departemen</th>
                                        <th class="text-end">Resign</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($resignPerDep as $item)
                                        <tr class="drill-trigger to-row-click" style="cursor:pointer;"
                                            data-type="departemen" data-value="{{ $item['label'] }}"
                                            data-label="Resign — {{ $item['label'] }}">
                                            <td>
                                                <span class="badge to-dep-badge me-1">{{ $item['singkatan'] }}</span>
                                                {{ $item['label'] }}
                                            </td>
                                            <td class="text-end">
                                                <span
                                                    class="to-num-badge bg-warning text-dark">{{ number_format($item['jumlah']) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-warning text-dark bg-opacity-75">
                                                    {{ $totalResign > 0 ? round(($item['jumlah'] / $totalResign) * 100, 1) : 0 }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Per PT + Masa Kerja --}}
            <div class="col-lg-6 d-flex flex-column gap-4">

                {{-- Per PT --}}
                <div class="card to-section-card">
                    <div class="card-header to-section-header"
                        style="background:linear-gradient(135deg,#1a4fcf 0%,#1a6fcf 100%);color:#fff;">
                        <i class="fas fa-building me-2" style="opacity:.85;"></i>Resign per Perusahaan (PT)
                    </div>
                    <div class="card-body">
                        <div class="to-table-mini">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Perusahaan</th>
                                        <th class="text-end">Resign</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($resignPerPT as $item)
                                        <tr class="drill-trigger to-row-click" data-type="pt"
                                            data-value="{{ $item['id'] }}" data-label="{{ $item['label'] }}">
                                            <td>
                                                <span class="fw-semibold">{{ $item['singkatan'] }}</span>
                                                <small class="text-muted d-block">{{ $item['label'] }}</small>
                                            </td>
                                            <td class="text-end">
                                                <span
                                                    class="to-num-badge bg-primary">{{ number_format($item['jumlah']) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-primary bg-opacity-75">
                                                    {{ $totalResign > 0 ? round(($item['jumlah'] / $totalResign) * 100, 1) : 0 }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Masa Kerja saat Resign --}}
                <div class="card to-section-card flex-grow-1">
                    <div class="card-header to-section-header"
                        style="background:linear-gradient(135deg,#5b21b6 0%,#7c3aed 100%);color:#fff;">
                        <i class="fas fa-hourglass-half me-2" style="opacity:.85;"></i>Masa Kerja saat Resign
                    </div>
                    <div class="card-body">
                        <canvas id="chartMasaKerja" height="150"></canvas>
                        <div class="to-table-mini mt-3">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Rentang</th>
                                        <th class="text-end">Orang</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($masaKerjaBucket as $item)
                                        <tr>
                                            <td>{{ $item['label'] }}</td>
                                            <td class="text-end">
                                                <span
                                                    class="to-num-badge bg-secondary">{{ number_format($item['jumlah']) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-secondary bg-opacity-75">
                                                    {{ $totalResign > 0 ? round(($item['jumlah'] / $totalResign) * 100, 1) : 0 }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ====== BARIS BAWAH ====== --}}
        <div class="row g-4 mb-4">

            {{-- Wilayah --}}
            <div class="col-lg-4">
                <div class="card to-section-card h-100">
                    <div class="card-header to-section-header"
                        style="background:linear-gradient(135deg,#3730a3 0%,#4c6ef5 100%);color:#fff;">
                        <i class="fas fa-map me-2" style="opacity:.85;"></i>Per Wilayah
                    </div>
                    <div class="card-body">
                        <canvas id="chartWilayah" height="180"></canvas>
                        <div class="to-table-mini mt-3">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Wilayah</th>
                                        <th class="text-end">Resign</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($resignPerWilayah as $item)
                                        <tr class="drill-trigger to-row-click" data-type="wilayah"
                                            data-value="{{ $item['label'] }}"
                                            data-label="Wilayah: {{ $item['label'] }}">
                                            <td class="fw-semibold">{{ $item['label'] }}</td>
                                            <td class="text-end">
                                                <span class="to-num-badge"
                                                    style="background:#4c6ef5;">{{ number_format($item['jumlah']) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kontrak --}}
            <div class="col-lg-4">
                <div class="card to-section-card h-100">
                    <div class="card-header to-section-header"
                        style="background:linear-gradient(135deg,#b91c1c 0%,#dc2626 100%);color:#fff;">
                        <i class="fas fa-file-contract me-2" style="opacity:.85;"></i>Per Tipe Kontrak
                    </div>
                    <div class="card-body">
                        <canvas id="chartKontrak" height="180"></canvas>
                        <div class="to-table-mini mt-3">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kontrak</th>
                                        <th class="text-end">Resign</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($resignPerKontrak as $item)
                                        <tr class="drill-trigger to-row-click" data-type="kontrak"
                                            data-value="{{ $item['id'] }}" data-label="Kontrak: {{ $item['label'] }}">
                                            <td>
                                                <span class="badge to-ktr-badge me-1">{{ $item['kode'] }}</span>
                                                <small>{{ $item['label'] }}</small>
                                            </td>
                                            <td class="text-end">
                                                <span
                                                    class="to-num-badge bg-danger">{{ number_format($item['jumlah']) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-danger bg-opacity-75">
                                                    {{ $totalResign > 0 ? round(($item['jumlah'] / $totalResign) * 100, 1) : 0 }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gender --}}
            <div class="col-lg-4">
                <div class="card to-section-card h-100">
                    <div class="card-header to-section-header"
                        style="background:linear-gradient(135deg,#7e22ce 0%,#a855f7 100%);color:#fff;">
                        <i class="fas fa-venus-mars me-2" style="opacity:.85;"></i>Per Jenis Kelamin
                    </div>
                    <div class="card-body d-flex flex-column">
                        <canvas id="chartGender" height="180"></canvas>
                        <div class="to-table-mini mt-3 flex-grow-1">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Jenis Kelamin</th>
                                        <th class="text-end">Resign</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($resignPerGender as $item)
                                        <tr class="drill-trigger to-row-click" data-type="gender"
                                            data-value="{{ $item['label'] }}"
                                            data-label="{{ $item['label'] === 'LAKI-LAKI' ? 'Laki-laki' : 'Perempuan' }}">
                                            <td>
                                                @if ($item['label'] === 'LAKI-LAKI')
                                                    <span style="color:#3b82f6;font-weight:700;">♂</span>
                                                    <span class="ms-1">Laki-laki</span>
                                                @else
                                                    <span style="color:#a855f7;font-weight:700;">♀</span>
                                                    <span class="ms-1">Perempuan</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <span class="to-num-badge"
                                                    style="background:{{ $item['label'] === 'LAKI-LAKI' ? '#3b82f6' : '#a855f7' }};">
                                                    {{ number_format($item['jumlah']) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge"
                                                    style="background:{{ $item['label'] === 'LAKI-LAKI' ? '#3b82f6' : '#a855f7' }};">
                                                    {{ $totalResign > 0 ? round(($item['jumlah'] / $totalResign) * 100, 1) : 0 }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ====== ALASAN PHK — FULL WIDTH ====== --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card to-section-card">
                    <div class="card-header to-section-header d-flex justify-content-between align-items-center"
                        style="background:linear-gradient(135deg,#0f766e 0%,#0d9488 60%,#14b8a6 100%);color:#fff;">
                        <span><i class="fas fa-comment-alt me-2" style="opacity:.85;"></i>Alasan PHK / Resign</span>
                        <small style="opacity:.7;font-weight:400;">Top 10 alasan terbanyak</small>
                    </div>
                    <div class="card-body p-0">
                        @if ($resignPerAlasan->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="4%" class="text-center">#</th>
                                            <th>Alasan</th>
                                            <th width="12%" class="text-end">Orang</th>
                                            <th width="30%">Proporsi</th>
                                            <th width="8%" class="text-end">%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $maxAlasan = $resignPerAlasan->max('jumlah'); @endphp
                                        @foreach ($resignPerAlasan as $i => $item)
                                            <tr>
                                                <td class="text-center">
                                                    <span class="to-alasan-rank">{{ $i + 1 }}</span>
                                                </td>
                                                <td class="fw-semibold" style="font-size:.85rem;">
                                                    {{ $item['label'] }}
                                                </td>
                                                <td class="text-end">
                                                    <span class="to-num-badge" style="background:#0d9488;">
                                                        {{ number_format($item['jumlah']) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div
                                                            style="flex:1;height:10px;background:#e2e8f0;border-radius:5px;overflow:hidden;">
                                                            <div
                                                                style="width:{{ $maxAlasan > 0 ? round(($item['jumlah'] / $maxAlasan) * 100) : 0 }}%;height:100%;background:linear-gradient(90deg,#0f766e,#14b8a6);border-radius:5px;transition:width .6s ease;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge" style="background:#0d9488;">
                                                        {{ $totalResign > 0 ? round(($item['jumlah'] / $totalResign) * 100, 1) : 0 }}%
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-comment-slash fa-2x mb-2 d-block opacity-25"></i>
                                Tidak ada data alasan PHK / resign pada periode ini
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ====== MODAL DRILL-DOWN ====== --}}
    <div class="modal fade" id="toModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:#dc2626;color:#fff;border-bottom:none;padding:.9rem 1.25rem;">
                    <div class="d-flex flex-column">
                        <h5 class="modal-title mb-0" id="toModalTitle">Detail Resign</h5>
                        <small id="toModalSub"
                            style="color:rgba(255,255,255,.7);font-size:.75rem;margin-top:2px;"></small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="toLoading" class="text-center py-5">
                        <div class="spinner-border text-danger" role="status"></div>
                        <div class="mt-2 text-muted small">Memuat data...</div>
                    </div>
                    <div id="toContent" style="display:none;">
                        <div class="px-3 pt-2 pb-2 d-flex justify-content-between align-items-center border-bottom"
                            style="background:#f8fafc;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-search" style="color:#94a3b8;font-size:.8rem;"></i>
                                <input type="text" id="toSearch"
                                    class="form-control form-control-sm border-0 bg-transparent p-0"
                                    placeholder="Cari nama, NRK, departemen, alasan..."
                                    style="width:280px;box-shadow:none;font-size:.83rem;">
                            </div>
                            <span id="toCount" class="badge"
                                style="background:#e2e8f0;color:#475569;font-weight:600;font-size:.72rem;"></span>
                        </div>
                        <div class="table-responsive" style="max-height:460px;">
                            <table class="table table-sm table-hover table-striped mb-0" id="toTable">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="8%">NRK</th>
                                        <th width="18%">Nama</th>
                                        <th width="5%">JK</th>
                                        <th width="10%">Perusahaan</th>
                                        <th width="11%">Departemen</th>
                                        <th width="11%">Jabatan</th>
                                        <th width="8%">Kontrak</th>
                                        <th width="9%">Tgl Masuk</th>
                                        <th width="9%">Tgl PHK</th>
                                        <th width="8%">Masa Kerja</th>
                                        <th width="10%">Alasan</th>
                                    </tr>
                                </thead>
                                <tbody id="toTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                    <div id="toEmpty" style="display:none;" class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>Tidak ada data resign
                    </div>
                </div>
                <div class="modal-footer justify-content-between py-2"
                    style="background:#f8fafc;border-top:1px solid #e2e8f0;">
                    <small class="text-muted" id="toFooterInfo" style="font-size:.75rem;"></small>
                    <button type="button" class="btn btn-sm px-4"
                        style="background:#dc2626;color:#fff;border:none;border-radius:6px;"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .to-page {
            --to-red: #dc2626;
            --to-orange: #d97706;
            --to-indigo: #4c6ef5;
            --to-purple: #7c3aed;
            --to-teal: #0d9488;
            font-family: 'Segoe UI', sans-serif;
        }

        .to-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -.3px;
        }

        .to-badge-rate {
            background: linear-gradient(135deg, #b91c1c, #dc2626);
            color: #fff;
            border-radius: 8px;
        }

        .to-badge-resign {
            background: #1e293b;
            color: #fff;
            border-radius: 8px;
        }

        .to-filter-card {
            border: none;
            border-left: 4px solid #dc2626;
            border-radius: 8px;
        }

        .to-label {
            font-size: .73rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 4px;
        }

        .to-hint {
            font-size: .65rem;
            color: #94a3b8;
            line-height: 1.3;
        }

        .to-ig-red {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
            font-size: .72rem;
            font-weight: 600;
            padding: .25rem .5rem;
        }

        /* Chips */
        .to-chip {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: .75rem;
            font-weight: 600;
        }

        .to-chip a {
            text-decoration: none;
            opacity: .7;
        }

        .to-chip a:hover {
            opacity: 1;
        }

        .to-chip-blue {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .to-chip-indigo {
            background: #eef2ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
        }

        .to-chip-cyan {
            background: #ecfeff;
            color: #0e7490;
            border: 1px solid #a5f3fc;
        }

        .to-chip-orange {
            background: #fff7ed;
            color: #c2410c;
            border: 1px solid #fed7aa;
        }

        .to-chip-red {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Stat cards */
        .to-stat-card {
            border-radius: 12px;
            padding: 1.1rem 1rem;
            color: #fff;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
            transition: transform .18s, box-shadow .18s;
        }

        .to-stat-card.drill-trigger {
            cursor: pointer;
        }

        .to-stat-card.drill-trigger:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, .2);
        }

        .to-stat-card::before {
            content: '';
            position: absolute;
            top: -18px;
            right: -18px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .1);
        }

        .to-stat-icon {
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

        .to-stat-num {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -1px;
            margin-top: .35rem;
        }

        .to-stat-lbl {
            font-size: .72rem;
            font-weight: 500;
            opacity: .85;
            margin-top: .2rem;
        }

        .to-stat-hint {
            font-size: .62rem;
            opacity: 0;
            transition: opacity .2s;
            margin-top: .15rem;
        }

        .to-stat-card.drill-trigger:hover .to-stat-hint {
            opacity: .75;
        }

        .to-stat-resign {
            background: linear-gradient(145deg, #b91c1c, #ef4444);
        }

        .to-stat-rate {
            background: linear-gradient(145deg, #7c2d12, #ea580c);
        }

        .to-stat-masuk {
            background: linear-gradient(145deg, #047857, #10b981);
        }

        .to-stat-aktif {
            background: linear-gradient(145deg, #1e293b, #334155);
        }

        .to-stat-awal {
            background: linear-gradient(145deg, #1d4ed8, #3b82f6);
        }

        .to-stat-akhir {
            background: linear-gradient(145deg, #5b21b6, #7c3aed);
        }

        /* Section cards */
        .to-section-card {
            border: 1px solid #e9eef5 !important;
            border-radius: 12px !important;
            overflow: hidden;
            box-shadow: 0 1px 6px rgba(0, 0, 0, .06);
        }

        .to-section-header {
            font-weight: 600;
            font-size: .875rem;
            padding: .7rem 1.1rem;
            color: #fff;
            letter-spacing: .1px;
        }

        .to-table-mini {
            max-height: 200px;
            overflow-y: auto;
        }

        .to-table-mini::-webkit-scrollbar {
            width: 4px;
        }

        .to-table-mini::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .to-row-click:hover {
            background: #fff0f0 !important;
        }

        .to-num-badge {
            display: inline-block;
            color: #fff;
            padding: 2px 10px;
            border-radius: 12px;
            font-weight: 700;
            font-size: .82rem;
            cursor: pointer;
            transition: transform .15s;
        }

        .to-num-badge:hover {
            transform: scale(1.1);
        }

        .to-dep-badge {
            background: #f1f5f9;
            color: #475569;
            font-size: .65rem;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .to-ktr-badge {
            background: #fef2f2;
            color: #991b1b;
            font-size: .65rem;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Toggle Top Mgmt (reuse from siska) */
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

        #toTable tbody tr td {
            padding: .5rem .75rem !important;
            vertical-align: middle;
            font-size: .82rem;
        }

        #toTable thead th {
            font-size: .75rem;
            padding: .5rem .75rem !important;
            font-weight: 600;
        }

        @media(max-width:768px) {
            .to-stat-num {
                font-size: 1.4rem;
            }
        }

        /* Alasan rank badge */
        .to-alasan-rank {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #0d9488;
            color: #fff;
            font-size: .68rem;
            font-weight: 700;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const DETAIL_URL = '{{ route('reports.turnover.detail') }}';
            const AREA_URL = '{{ route('reports.turnover.area-by-wilker') }}';

            const filterEl = document.getElementById('toActiveFilters');
            const gFilters = {
                filter_perusahaan: filterEl.dataset.filterPerusahaan || '',
                filter_wilker: filterEl.dataset.filterWilker || '',
                filter_area: filterEl.dataset.filterArea || '',
                filter_kontrak: filterEl.dataset.filterKontrak || '',
                filter_departemen: filterEl.dataset.filterDepartemen || '',
                filter_tgl_dari: filterEl.dataset.filterTglDari || '',
                filter_tgl_sampai: filterEl.dataset.filterTglSampai || '',
                filter_include_top_mgmt: filterEl.dataset.filterIncludeTopMgmt || '0',
            };

            // ── Toggle teks ──
            const toggleInp = document.getElementById('toToggleTopMgmt');
            if (toggleInp) {
                toggleInp.addEventListener('change', function() {
                    document.querySelector('#toToggleLabel .siska-toggle-text').textContent =
                        this.checked ? 'Disertakan' : 'Dikecualikan';
                });
            }

            // ── Dropdown area dinamis ──
            const filterWilker = document.getElementById('toFilterWilker');
            const filterArea = document.getElementById('toFilterArea');
            if (filterWilker && filterArea) {
                filterWilker.addEventListener('change', function() {
                    fetch(AREA_URL + (this.value ? '?wilker=' + encodeURIComponent(this.value) : ''))
                        .then(r => r.json())
                        .then(data => {
                            filterArea.innerHTML = '<option value="">— Semua Area —</option>';
                            data.forEach(ak => {
                                filterArea.innerHTML +=
                                    `<option value="${ak.id}">${ak.area_krj}${ak.singkatan_wk ? ' (' + ak.singkatan_wk + ')' : ''}</option>`;
                            });
                        });
                });
            }

            // ── Chart palette ──
            const PAL = ['#dc2626', '#d97706', '#4c6ef5', '#0d9488', '#7c3aed', '#059669', '#f59e0b', '#0891b2',
                '#ec4899', '#6366f1'
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
                        const v = typeof c.parsed === 'object' ? (c.parsed.y ?? c.parsed.x ?? c.parsed) : c
                            .parsed;
                        return ` ${c.label}: ${Number(v || 0).toLocaleString('id-ID')} orang`;
                    }
                }
            };

            // ── Tren bulanan ──
            const trendData = @json($resignPerBulan->values());
            if (trendData.length && document.getElementById('chartTrenBulan')) {
                new Chart(document.getElementById('chartTrenBulan'), {
                    type: 'bar',
                    data: {
                        labels: trendData.map(r => r.label),
                        datasets: [{
                            label: 'Resign',
                            data: trendData.map(r => r.jumlah),
                            backgroundColor: trendData.map((_, i) =>
                                i === trendData.length - 1 ? '#fca5a5' : '#dc2626'),
                            borderRadius: 5,
                        }]
                    },
                    options: {
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
                                    color: 'transparent'
                                }
                            },
                            y: {
                                grid: {
                                    color: '#f1f5f9'
                                },
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        onClick: (e, els) => {
                            if (!els.length) return;
                            const r = trendData[els[0].index];
                            openModal('bulan', r.tahun + '-' + r.bulan, r.label);
                        }
                    }
                });
            }

            // ── Resign per Dep ──
            const depData = @json($resignPerDep->values());
            if (depData.length && document.getElementById('chartResignDep')) {
                new Chart(document.getElementById('chartResignDep'), {
                    type: 'bar',
                    data: {
                        labels: depData.map(r => r.singkatan),
                        datasets: [{
                            label: 'Resign',
                            data: depData.map(r => r.jumlah),
                            backgroundColor: PAL,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        indexAxis: 'y',
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
                                    color: '#f1f5f9'
                                }
                            },
                            y: {
                                grid: {
                                    color: 'transparent'
                                }
                            }
                        },
                        onClick: (e, els) => {
                            if (els.length) openModal('departemen', depData[els[0].index].label,
                                'Resign — ' + depData[els[0].index].label);
                        }
                    }
                });
            }

            // ── Masa Kerja ──
            const mkData = @json($masaKerjaBucket->values());
            if (mkData.length && document.getElementById('chartMasaKerja')) {
                new Chart(document.getElementById('chartMasaKerja'), {
                    type: 'doughnut',
                    data: {
                        labels: mkData.map(r => r.label),
                        datasets: [{
                            data: mkData.map(r => r.jumlah),
                            backgroundColor: PAL,
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
                        }
                    }
                });
            }

            // ── Wilayah ──
            const wlData = @json($resignPerWilayah->values());
            if (wlData.length && document.getElementById('chartWilayah')) {
                new Chart(document.getElementById('chartWilayah'), {
                    type: 'pie',
                    data: {
                        labels: wlData.map(r => r.label),
                        datasets: [{
                            data: wlData.map(r => r.jumlah),
                            backgroundColor: ['#4c6ef5', '#6366f1', '#818cf8', '#a5b4fc',
                                '#c7d2fe'
                            ],
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
                            if (els.length) openModal('wilayah', wlData[els[0].index].label,
                                'Wilayah: ' + wlData[els[0].index].label);
                        }
                    }
                });
            }

            // ── Kontrak ──
            const ktrData = @json($resignPerKontrak->values());
            if (ktrData.length && document.getElementById('chartKontrak')) {
                new Chart(document.getElementById('chartKontrak'), {
                    type: 'doughnut',
                    data: {
                        labels: ktrData.map(r => r.kode),
                        datasets: [{
                            data: ktrData.map(r => r.jumlah),
                            backgroundColor: ['#dc2626', '#d97706', '#059669', '#1a6fcf',
                                '#7c3aed'
                            ],
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
                            const r = ktrData[els[0].index];
                            openModal('kontrak', r.id, 'Kontrak: ' + r.label);
                        }
                    }
                });
            }

            // ── Gender ──
            // ── Gender ──
            const gnData = @json($resignPerGender->values());
            if (gnData.length && document.getElementById('chartGender')) {
                new Chart(document.getElementById('chartGender'), {
                    type: 'doughnut',
                    data: {
                        labels: gnData.map(r => r.label === 'LAKI-LAKI' ? 'Laki-laki' : 'Perempuan'),
                        datasets: [{
                            data: gnData.map(r => r.jumlah),
                            backgroundColor: ['#3b82f6', '#a855f7'],
                            borderWidth: 3,
                            borderColor: '#fff',
                            hoverOffset: 10
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
                                    padding: 14,
                                    boxWidth: 14,
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        },
                        onClick: (e, els) => {
                            if (els.length) openModal('gender', gnData[els[0].index].label,
                                gnData[els[0].index].label === 'LAKI-LAKI' ? 'Laki-laki' :
                                'Perempuan');
                        }
                    }
                });
            }

            // ── Delegation drill ──
            document.addEventListener('click', function(e) {
                const dt = e.target.closest('.drill-trigger');
                if (!dt || !dt.dataset.type) return;
                e.stopPropagation();
                openModal(dt.dataset.type, dt.dataset.value, dt.dataset.label);
            });

            // ── Open Modal ──
            function openModal(type, value, label) {
                const modal = new bootstrap.Modal(document.getElementById('toModal'));
                document.getElementById('toModalTitle').textContent = label || 'Detail Resign';
                document.getElementById('toModalSub').textContent = '';
                document.getElementById('toLoading').style.display = 'block';
                document.getElementById('toContent').style.display = 'none';
                document.getElementById('toEmpty').style.display = 'none';
                document.getElementById('toSearch').value = '';
                modal.show();

                const params = new URLSearchParams({
                    type,
                    value,
                    ...gFilters
                });
                fetch(DETAIL_URL + '?' + params)
                    .then(r => r.json())
                    .then(res => {
                        document.getElementById('toLoading').style.display = 'none';
                        document.getElementById('toModalSub').textContent = res.total + ' karyawan';
                        document.getElementById('toFooterInfo').textContent = 'Total: ' + res.total
                            .toLocaleString('id-ID') + ' karyawan resign';
                        document.getElementById('toCount').textContent = res.total + ' karyawan';
                        if (!res.data?.length) {
                            document.getElementById('toEmpty').style.display = 'block';
                            return;
                        }
                        renderRows(res.data);
                        document.getElementById('toContent').style.display = 'block';
                    })
                    .catch(() => {
                        document.getElementById('toLoading').style.display = 'none';
                        document.getElementById('toEmpty').style.display = 'block';
                    });
            }

            function renderRows(data) {
                const gIcon = s => s === 'LAKI-LAKI' ?
                    '<span style="color:#3b82f6;font-size:.8rem;">♂ L</span>' :
                    '<span style="color:#a855f7;font-size:.8rem;">♀ P</span>';

                document.getElementById('toTableBody').innerHTML = data.map((k, i) => `
        <tr>
            <td class="text-muted">${i+1}</td>
            <td><code style="font-size:.75rem;color:#64748b;background:#f1f5f9;padding:1px 5px;border-radius:4px;">${k.nrk}</code></td>
            <td style="font-weight:600;font-size:.84rem;">${k.nama}</td>
            <td>${gIcon(k.sex)}</td>
            <td><span style="font-size:.75rem;background:#eff6ff;color:#1d4ed8;padding:2px 6px;border-radius:5px;font-weight:600;">${k.perusahaan}</span></td>
            <td style="font-size:.78rem;color:#475569;">${k.departemen}</td>
            <td style="font-size:.78rem;color:#374151;">${k.jabatan}</td>
            <td><span style="font-size:.7rem;background:#e0e7ff;color:#3730a3;padding:2px 6px;border-radius:5px;font-weight:600;">${k.kontrak}</span></td>
            <td style="font-size:.75rem;color:#94a3b8;white-space:nowrap;">${k.tgl_masuk}</td>
            <td style="font-size:.75rem;color:#ef4444;white-space:nowrap;font-weight:600;">${k.tgl_phk}</td>
            <td style="font-size:.75rem;color:#7c3aed;white-space:nowrap;">${k.masa_kerja}</td>
            <td style="font-size:.73rem;color:#64748b;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${k.alasan}">${k.alasan}</td>
        </tr>`).join('');
            }

            document.getElementById('toSearch').addEventListener('input', function() {
                const q = this.value.toLowerCase();
                let vis = 0;
                document.querySelectorAll('#toTableBody tr').forEach(r => {
                    const show = r.textContent.toLowerCase().includes(q);
                    r.style.display = show ? '' : 'none';
                    if (show) vis++;
                });
                document.getElementById('toCount').textContent = vis + ' karyawan';
            });

        });
    </script>
@endpush
