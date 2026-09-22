@extends('layouts.app')

@section('title', 'Tracking Perubahan Kontrak')

@section('content')
    <div class="container-fluid tk-page">

        {{-- ====== HEADER ====== --}}
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
            <div>
                <h4 class="tk-title mb-0">
                    <i class="fas fa-exchange-alt me-2"></i>Tracking Perubahan Kontrak
                </h4>
                <small class="text-muted">
                    Riwayat & jenjang perubahan kontrak kerja karyawan — SPKK → PKWT → PKWTT
                </small>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge tk-badge-upgrade px-3 py-2">
                    <i class="fas fa-arrow-up me-1"></i>Upgrade: <strong>{{ number_format($statUpgrade) }}</strong>
                </span>
                <span class="badge tk-badge-perpanjang px-3 py-2">
                    <i class="fas fa-redo me-1"></i>Perpanjang: <strong>{{ number_format($statPerpanjang) }}</strong>
                </span>
                <span class="badge tk-badge-downgrade px-3 py-2">
                    <i class="fas fa-arrow-down me-1"></i>Downgrade: <strong>{{ number_format($statDowngrade) }}</strong>
                </span>
            </div>
        </div>

        {{-- ====== FILTER ====== --}}
        <div class="card shadow-sm mb-4 tk-filter-card" id="tkActiveFilters"
            data-filter-perusahaan="{{ $currentFilters['perusahaan'] }}"
            data-filter-wilker="{{ $currentFilters['wilker'] }}" data-filter-area="{{ $currentFilters['area'] }}"
            data-filter-status="{{ $currentFilters['status'] }}"
            data-filter-departemen="{{ $currentFilters['departemen'] }}"
            data-filter-kontrak-aktif="{{ $currentFilters['kontrak_aktif'] }}"
            data-filter-tgl-dari="{{ $currentFilters['tgl_dari'] }}"
            data-filter-tgl-sampai="{{ $currentFilters['tgl_sampai'] }}"
            data-filter-include-top-mgmt="{{ $currentFilters['include_top_mgmt'] }}">

            <div class="card-header d-flex align-items-center"
                style="background:#f8fafc;border-bottom:1px solid #e2e8f0;border-left:4px solid #4c6ef5;">
                <span class="fw-semibold" style="color:#1e293b;font-size:.875rem;">
                    <i class="fas fa-sliders-h me-2" style="color:#4c6ef5;"></i>Filter Data
                </span>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('reports.tracking-kontrak.index') }}" id="tkFilterForm">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-3">
                            <label class="form-label tk-label">Perusahaan (PT)</label>
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
                            <label class="form-label tk-label">Wilayah Kerja</label>
                            <select name="filter_wilker" class="form-select form-select-sm" id="tkFilterWilker">
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
                            <label class="form-label tk-label">Area Kerja</label>
                            <select name="filter_area" class="form-select form-select-sm" id="tkFilterArea">
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
                            <label class="form-label tk-label">Departemen</label>
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

                        <div class="col-md-3">
                            <label class="form-label tk-label">Kontrak Aktif Saat Ini</label>
                            <select name="filter_kontrak_aktif" class="form-select form-select-sm">
                                <option value="">— Semua Kontrak —</option>
                                @foreach ($kontrakOptions as $ktr)
                                    <option value="{{ $ktr->id }}"
                                        {{ $currentFilters['kontrak_aktif'] == $ktr->id ? 'selected' : '' }}>
                                        {{ $ktr->singkatan_ktr ?? $ktr->kode_ktr }} — {{ $ktr->nama_ktr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label tk-label">Status Karyawan</label>
                            <select name="filter_status" class="form-select form-select-sm">
                                <option value="">— Semua Status —</option>
                                <option value="AKTIF" {{ $currentFilters['status'] == 'AKTIF' ? 'selected' : '' }}>
                                    Aktif</option>
                                <option value="NON-AKTIF" {{ $currentFilters['status'] == 'NON-AKTIF' ? 'selected' : '' }}>
                                    Non-Aktif</option>
                                <option value="CALON" {{ $currentFilters['status'] == 'CALON' ? 'selected' : '' }}>
                                    Calon</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label tk-label">
                                <i class="fas fa-crown me-1" style="color:#b45309;"></i>Top Manajement
                            </label>
                            <div class="siska-topmgmt-toggle">
                                <input type="hidden" name="filter_include_top_mgmt" value="0">
                                <input type="checkbox" name="filter_include_top_mgmt" id="tkToggleTopMgmt" value="1"
                                    class="siska-toggle-input"
                                    {{ $currentFilters['include_top_mgmt'] === '1' ? 'checked' : '' }}>
                                <label for="tkToggleTopMgmt" class="siska-toggle-label" id="tkToggleLabel">
                                    <span class="siska-toggle-thumb"></span>
                                    <span class="siska-toggle-text">
                                        {{ $currentFilters['include_top_mgmt'] === '1' ? 'Disertakan' : 'Dikecualikan' }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label tk-label">Tampilkan</label>
                            <select name="per_page" class="form-select form-select-sm">
                                @foreach ([20, 50, 100] as $pp)
                                    <option value="{{ $pp }}"
                                        {{ $currentFilters['per_page'] == $pp ? 'selected' : '' }}>
                                        {{ $pp }} per halaman
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Divider --}}
                        <div class="col-12">
                            <hr style="border-color:#e2e8f0;margin:0;">
                        </div>

                        {{-- Baris Periode + Cari + Tombol --}}
                        <div class="col-12">
                            <div class="d-flex align-items-end gap-3">

                                <div style="flex:1;">
                                    <label class="form-label tk-label">
                                        <i class="fas fa-calendar-alt me-1" style="color:#4c6ef5;"></i>
                                        Filter Perubahan Kontrak dalam Periode
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text tk-ig-indigo">Dari</span>
                                        <input type="date" name="filter_tgl_dari" class="form-control form-control-sm"
                                            value="{{ $currentFilters['tgl_dari'] }}">
                                        <span class="input-group-text tk-ig-indigo">S/d</span>
                                        <input type="date" name="filter_tgl_sampai"
                                            class="form-control form-control-sm"
                                            value="{{ $currentFilters['tgl_sampai'] }}">
                                    </div>
                                    <div class="tk-hint mt-1">
                                        <i class="fas fa-info-circle me-1"></i>Filter karyawan yang ada perubahan kontrak
                                        pada rentang ini
                                    </div>
                                </div>

                                <div style="flex:1;">
                                    <label class="form-label tk-label">
                                        <i class="fas fa-search me-1"></i>Cari Karyawan
                                    </label>
                                    <input type="text" name="search" class="form-control form-control-sm"
                                        placeholder="Nama atau NRK..." value="{{ $currentFilters['search'] }}">
                                </div>

                                <div class="d-flex gap-2 flex-shrink-0" style="padding-bottom:1.25rem;">
                                    <a href="{{ route('reports.tracking-kontrak.index') }}"
                                        class="btn btn-outline-secondary btn-sm px-3">
                                        <i class="fas fa-undo me-1"></i>Reset
                                    </a>
                                    <button type="submit" class="btn btn-sm px-4"
                                        style="background:#4c6ef5;color:#fff;border:none;">
                                        <i class="fas fa-search me-1"></i>Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- ====== STAT CARDS ====== --}}
        <div class="row g-3 mb-4">
            @php
                $statCards = [
                    [
                        'icon' => 'fa-arrow-circle-up',
                        'num' => $statUpgrade,
                        'lbl' => 'Total Upgrade Kontrak',
                        'cls' => 'tk-stat-upgrade',
                    ],
                    [
                        'icon' => 'fa-redo-alt',
                        'num' => $statPerpanjang,
                        'lbl' => 'Total Perpanjangan',
                        'cls' => 'tk-stat-perpanjang',
                    ],
                    [
                        'icon' => 'fa-arrow-circle-down',
                        'num' => $statDowngrade,
                        'lbl' => 'Total Downgrade Kontrak',
                        'cls' => 'tk-stat-downgrade',
                    ],
                    [
                        'icon' => 'fa-user-plus',
                        'num' => $statBaru,
                        'lbl' => 'Karyawan 1 Kontrak',
                        'cls' => 'tk-stat-baru',
                    ],
                ];
            @endphp
            @foreach ($statCards as $s)
                <div class="col-6 col-md-3">
                    <div class="tk-stat-card {{ $s['cls'] }}">
                        <div class="tk-stat-icon"><i class="fas {{ $s['icon'] }}"></i></div>
                        <div class="tk-stat-num">{{ number_format($s['num']) }}</div>
                        <div class="tk-stat-lbl">{{ $s['lbl'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ====== CHART + TOP TRANSISI ====== --}}
        <div class="row g-4 mb-4">

            <div class="col-lg-5">
                <div class="card tk-section-card h-100">
                    <div class="card-header tk-section-header"
                        style="background:linear-gradient(135deg,#3730a3 0%,#4c6ef5 100%);color:#fff;">
                        <i class="fas fa-chart-pie me-2" style="opacity:.85;"></i>Distribusi Kontrak Aktif Saat Ini
                    </div>
                    <div class="card-body d-flex flex-column align-items-center">
                        <canvas id="chartKontrakAktif" height="200"></canvas>
                        <div class="w-100 mt-3">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tipe Kontrak</th>
                                        <th class="text-end">Karyawan</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalKtrAktif = $perKontrakAktif->sum('jumlah'); @endphp
                                    @foreach ($perKontrakAktif as $item)
                                        <tr>
                                            <td>
                                                <span class="tk-ktr-badge me-1">{{ $item['label'] }}</span>
                                                <small class="text-muted">{{ $item['nama'] }}</small>
                                            </td>
                                            <td class="text-end fw-bold">{{ number_format($item['jumlah']) }}</td>
                                            <td class="text-end">
                                                <span class="badge" style="background:#4c6ef5;">
                                                    {{ $totalKtrAktif > 0 ? round(($item['jumlah'] / $totalKtrAktif) * 100, 1) : 0 }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card tk-section-card h-100">
                    <div class="card-header tk-section-header d-flex justify-content-between align-items-center"
                        style="background:linear-gradient(135deg,#0f766e 0%,#0d9488 100%);color:#fff;">
                        <span><i class="fas fa-random me-2" style="opacity:.85;"></i>Pola Transisi Kontrak
                            Terbanyak</span>
                        <small style="opacity:.7;font-weight:400;">Top 10</small>
                    </div>
                    <div class="card-body p-0">
                        @if ($topTransisi->count() > 0)
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">#</th>
                                        <th>Pola Perubahan</th>
                                        <th width="15%" class="text-end">Kejadian</th>
                                        <th width="35%">Proporsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $maxTransisi = $topTransisi->max('jumlah'); @endphp
                                    @foreach ($topTransisi as $i => $item)
                                        @php
                                            $parts = explode(' → ', $item['label']);
                                            $isUpgrade = count($parts) === 2;
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                <span class="tk-rank-badge">{{ $i + 1 }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if (count($parts) === 2)
                                                        <span class="tk-ktr-badge">{{ $parts[0] }}</span>
                                                        <i class="fas fa-long-arrow-alt-right"
                                                            style="color:#4c6ef5;font-size:.75rem;"></i>
                                                        <span
                                                            class="tk-ktr-badge tk-ktr-badge-new">{{ $parts[1] }}</span>
                                                    @else
                                                        <span class="fw-semibold"
                                                            style="font-size:.83rem;">{{ $item['label'] }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <span class="tk-num-badge"
                                                    style="background:#0d9488;">{{ number_format($item['jumlah']) }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div
                                                        style="flex:1;height:8px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                                                        <div
                                                            style="width:{{ $maxTransisi > 0 ? round(($item['jumlah'] / $maxTransisi) * 100) : 0 }}%;height:100%;background:linear-gradient(90deg,#0f766e,#14b8a6);border-radius:4px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-random fa-2x mb-2 d-block opacity-25"></i>
                                Belum ada data transisi kontrak
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- ====== TABEL KARYAWAN ====== --}}
        {{-- ====== DAFTAR KARYAWAN ====== --}}
        <div class="card tk-section-card mb-4">
            <div class="card-header tk-section-header d-flex justify-content-between align-items-center"
                style="background:linear-gradient(135deg,#1e293b 0%,#334155 100%);color:#fff;">
                <span>
                    <i class="fas fa-list-alt me-2" style="opacity:.85;"></i>Daftar Karyawan & Riwayat Kontrak
                </span>
                <span class="badge" style="background:rgba(255,255,255,.2);font-size:.75rem;">
                    {{ $karyawans->total() }} karyawan
                </span>
            </div>

            <div class="card-body p-0">
                @forelse ($karyawans as $k)
                    <div class="tk-row {{ $loop->even ? 'tk-row-even' : '' }}">

                        {{-- Kolom kiri: identitas --}}
                        <div class="tk-col-identity">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <code class="tk-nrk">{{ $k['nrk'] }}</code>
                                <span class="fw-bold tk-nama">{{ $k['nama'] }}</span>
                                <span
                                    class="tk-sts-badge
                            {{ $k['sts_kry'] === 'AKTIF'
                                ? 'tk-sts-aktif'
                                : ($k['sts_kry'] === 'NON-AKTIF'
                                    ? 'tk-sts-nonaktif'
                                    : 'tk-sts-calon') }}">
                                    {{ $k['sts_kry'] }}
                                </span>
                            </div>
                            <div class="tk-meta">
                                <span><i class="fas fa-building"></i> {{ $k['perusahaan'] }}</span>
                                <span><i class="fas fa-layer-group"></i> {{ $k['departemen'] }}</span>
                                <span><i class="fas fa-map-marker-alt"></i> {{ $k['unit_kerja'] }}</span>
                            </div>
                            <div class="tk-meta mt-1">
                                <span><i class="fas fa-calendar-plus"></i> {{ $k['tgl_masuk'] }}</span>
                            </div>
                            <div class="d-flex flex-wrap gap-1 mt-2">
                                @if ($k['jumlah_upgrade'] > 0)
                                    <span class="tk-pill tk-pill-upgrade">
                                        <i class="fas fa-arrow-up"></i> {{ $k['jumlah_upgrade'] }} upgrade
                                    </span>
                                @endif
                                @if ($k['jumlah_perpanjang'] > 0)
                                    <span class="tk-pill tk-pill-perpanjang">
                                        <i class="fas fa-redo"></i> {{ $k['jumlah_perpanjang'] }} perpanjang
                                    </span>
                                @endif
                                @if ($k['jumlah_downgrade'] > 0)
                                    <span class="tk-pill tk-pill-downgrade">
                                        <i class="fas fa-arrow-down"></i> {{ $k['jumlah_downgrade'] }} downgrade
                                    </span>
                                @endif
                                <span class="tk-pill tk-pill-neutral">
                                    <i class="fas fa-file-contract"></i> {{ $k['total_kontrak'] }} kontrak
                                </span>
                            </div>
                        </div>

                        {{-- Kolom tengah: timeline kontrak --}}
                        <div class="tk-col-timeline">
                            <div class="tk-timeline-strip">
                                @foreach ($k['timeline'] as $i => $t)
                                    {{-- Panah transisi --}}
                                    @if ($i > 0)
                                        <div
                                            class="tk-connector
                                    {{ $t['arah'] === 'upgrade'
                                        ? 'tk-connector-upgrade'
                                        : ($t['arah'] === 'downgrade'
                                            ? 'tk-connector-downgrade'
                                            : 'tk-connector-perpanjang') }}">
                                            <i
                                                class="fas
                                        {{ $t['arah'] === 'upgrade'
                                            ? 'fa-level-up-alt'
                                            : ($t['arah'] === 'downgrade'
                                                ? 'fa-level-down-alt'
                                                : 'fa-redo-alt') }}">
                                            </i>
                                        </div>
                                    @endif

                                    {{-- Node kontrak --}}
                                    <div
                                        class="tk-node
                                {{ $t['status'] === 'AKTIF' ? 'tk-node-aktif' : 'tk-node-lama' }}
                                {{ $t['arah'] === 'upgrade' ? 'tk-node-is-upgrade' : ($t['arah'] === 'downgrade' ? 'tk-node-is-downgrade' : '') }}">

                                        {{-- Badge singkatan --}}
                                        <div class="tk-node-chip">{{ $t['singkatan'] }}</div>

                                        {{-- Periode --}}
                                        <div class="tk-node-period">
                                            {{ $t['tgl_mulai'] }}<br>
                                            <span class="text-muted">→ {{ $t['tgl_akhir'] }}</span>
                                        </div>

                                        {{-- Durasi --}}
                                        <div class="tk-node-dur">{{ $t['durasi'] }}</div>

                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Kolom kanan: tombol --}}
                        <div class="tk-col-action">
                            <button class="tk-btn-detail" data-id="{{ $k['id'] }}"
                                data-nama="{{ $k['nama'] }}">
                                <i class="fas fa-eye"></i>
                                <span>Detail</span>
                            </button>
                        </div>

                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-search fa-2x mb-2 d-block opacity-25"></i>
                        Tidak ada data karyawan dengan riwayat kontrak
                    </div>
                @endforelse
            </div>

            @if ($karyawans->hasPages())
                <div class="card-footer bg-transparent border-top py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted">
                            Menampilkan {{ $karyawans->firstItem() }}–{{ $karyawans->lastItem() }}
                            dari {{ $karyawans->total() }} karyawan
                        </small>
                        {{ $karyawans->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>

    </div>

    {{-- ====== MODAL DETAIL TIMELINE ====== --}}
    <div class="modal fade" id="tkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header"
                    style="background:linear-gradient(135deg,#3730a3,#4c6ef5);color:#fff;border-bottom:none;padding:.9rem 1.25rem;">
                    <div class="d-flex flex-column">
                        <h5 class="modal-title mb-0" id="tkModalTitle">Timeline Kontrak</h5>
                        <small id="tkModalSub"
                            style="color:rgba(255,255,255,.7);font-size:.75rem;margin-top:2px;"></small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="tkLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <div class="mt-2 text-muted small">Memuat data...</div>
                    </div>
                    <div id="tkDetailContent" style="display:none;">

                        {{-- Info karyawan --}}
                        <div class="tk-modal-info mb-4" id="tkModalInfo"></div>

                        {{-- Timeline visual --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3" style="color:#1e293b;">
                                <i class="fas fa-stream me-2" style="color:#4c6ef5;"></i>Alur Perubahan Kontrak
                            </h6>
                            <div class="tk-modal-timeline" id="tkModalTimeline"></div>
                        </div>

                        {{-- Tabel detail --}}
                        <h6 class="fw-bold mb-3" style="color:#1e293b;">
                            <i class="fas fa-table me-2" style="color:#4c6ef5;"></i>Rincian Tiap Kontrak
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0" id="tkDetailTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Perubahan</th>
                                        <th>Tipe Kontrak</th>
                                        <th>No. Surat</th>
                                        <th>Tgl Surat</th>
                                        <th>Tgl Mulai</th>
                                        <th>Tgl Akhir</th>
                                        <th>Durasi</th>
                                        <th>Perusahaan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="tkDetailBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2" style="background:#f8fafc;border-top:1px solid #e2e8f0;">
                    <button type="button" class="btn btn-sm px-4"
                        style="background:#4c6ef5;color:#fff;border:none;border-radius:6px;"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .tk-page {
            --tk-indigo: #4c6ef5;
            --tk-green: #059669;
            --tk-red: #dc2626;
            --tk-orange: #d97706;
            --tk-teal: #0d9488;
            font-family: 'Segoe UI', sans-serif;
        }

        .tk-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -.3px;
        }

        .tk-badge-upgrade {
            background: linear-gradient(135deg, #047857, #10b981);
            color: #fff;
            border-radius: 8px;
            font-size: .8rem;
        }

        .tk-badge-perpanjang {
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: #fff;
            border-radius: 8px;
            font-size: .8rem;
        }

        .tk-badge-downgrade {
            background: linear-gradient(135deg, #b91c1c, #dc2626);
            color: #fff;
            border-radius: 8px;
            font-size: .8rem;
        }

        .tk-filter-card {
            border: none;
            border-left: 4px solid #4c6ef5;
            border-radius: 8px;
        }

        .tk-label {
            font-size: .73rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 4px;
        }

        .tk-hint {
            font-size: .65rem;
            color: #94a3b8;
            line-height: 1.3;
        }

        .tk-ig-indigo {
            background: #eef2ff;
            border-color: #c7d2fe;
            color: #4338ca;
            font-size: .72rem;
            font-weight: 600;
            padding: .25rem .5rem;
        }

        /* Stat cards */
        .tk-stat-card {
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
        }

        .tk-stat-card::before {
            content: '';
            position: absolute;
            top: -18px;
            right: -18px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .1);
        }

        .tk-stat-icon {
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

        .tk-stat-num {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -1px;
            margin-top: .35rem;
        }

        .tk-stat-lbl {
            font-size: .72rem;
            font-weight: 500;
            opacity: .85;
            margin-top: .2rem;
        }

        .tk-stat-upgrade {
            background: linear-gradient(145deg, #047857, #10b981);
        }

        .tk-stat-perpanjang {
            background: linear-gradient(145deg, #1d4ed8, #3b82f6);
        }

        .tk-stat-downgrade {
            background: linear-gradient(145deg, #b91c1c, #ef4444);
        }

        .tk-stat-baru {
            background: linear-gradient(145deg, #1e293b, #334155);
        }

        /* Section */
        .tk-section-card {
            border: 1px solid #e9eef5 !important;
            border-radius: 12px !important;
            overflow: hidden;
            box-shadow: 0 1px 6px rgba(0, 0, 0, .06);
        }

        .tk-section-header {
            font-weight: 600;
            font-size: .875rem;
            padding: .7rem 1.1rem;
            color: #fff;
        }

        /* Badges */
        .tk-ktr-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 5px;
            font-size: .7rem;
            font-weight: 700;
            background: #e0e7ff;
            color: #3730a3;
        }

        .tk-ktr-badge-new {
            background: #d1fae5;
            color: #065f46;
        }

        .tk-num-badge {
            display: inline-block;
            color: #fff;
            padding: 2px 10px;
            border-radius: 12px;
            font-weight: 700;
            font-size: .82rem;
        }

        .tk-rank-badge {
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

        .tk-nrk {
            font-size: .72rem;
            color: #64748b;
            background: #f1f5f9;
            padding: 1px 6px;
            border-radius: 4px;
        }

        /* Karyawan row */
        .tk-karyawan-row {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            transition: background .15s;
        }

        .tk-karyawan-row:last-child {
            border-bottom: none;
        }

        .tk-karyawan-row:hover {
            background: #f8faff;
        }

        .tk-row-even {
            background: #fdfdfe;
        }

        .tk-kry-info {
            min-width: 260px;
            flex: 0 0 260px;
        }

        .tk-kry-action {
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }

        /* Timeline inline */
        .tk-timeline-wrap {
            flex: 1;
            overflow-x: auto;
        }

        .tk-timeline {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 4px 0;
            min-width: max-content;
        }

        .tk-kontrak-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 10px;
            padding: 6px 10px;
            border: 2px solid;
            min-width: 110px;
            text-align: center;
            background: #fff;
            transition: transform .15s;
        }

        .tk-node-aktif {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, .1);
        }

        .tk-node-nonaktif {
            border-color: #d1d5db;
            opacity: .75;
        }

        .tk-node-upgrade {
            border-color: #4c6ef5;
        }

        .tk-node-badge {
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .5px;
            padding: 2px 8px;
            border-radius: 5px;
            margin-bottom: 4px;
            background: #e0e7ff;
            color: #3730a3;
        }

        .tk-node-aktif .tk-node-badge {
            background: #d1fae5;
            color: #065f46;
        }

        .tk-node-dates {
            font-size: .62rem;
            color: #94a3b8;
            display: flex;
            gap: 3px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .tk-node-durasi {
            font-size: .65rem;
            color: #64748b;
            margin-top: 2px;
            font-weight: 600;
        }

        .tk-arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            flex-shrink: 0;
            font-size: .7rem;
            color: #fff;
        }

        .tk-arrow-upgrade {
            background: #10b981;
        }

        .tk-arrow-downgrade {
            background: #ef4444;
        }

        .tk-arrow-perpanjang {
            background: #3b82f6;
        }

        /* Mini badges */
        .tk-badge-mini {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: .67rem;
            font-weight: 600;
        }

        .tk-badge-mini.tk-badge-upgrade {
            background: #d1fae5;
            color: #065f46;
        }

        .tk-badge-mini.tk-badge-perpanjang {
            background: #dbeafe;
            color: #1e40af;
        }

        .tk-badge-mini.tk-badge-downgrade {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Detail button */
        .tk-btn-detail {
            background: #f0f4ff;
            border: 1px solid #c7d2fe;
            color: #4c6ef5;
            border-radius: 8px;
            font-size: .75rem;
            padding: 4px 12px;
            transition: all .15s;
            white-space: nowrap;
        }

        .tk-btn-detail:hover {
            background: #4c6ef5;
            color: #fff;
            border-color: #4c6ef5;
        }

        /* Modal timeline */
        .tk-modal-timeline {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .tk-modal-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 12px;
            padding: 10px 14px;
            border: 2px solid;
            min-width: 130px;
            text-align: center;
            background: #fff;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .08);
        }

        .tk-modal-node-baru {
            border-color: #94a3b8;
        }

        .tk-modal-node-upgrade {
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, .12);
        }

        .tk-modal-node-downgrade {
            border-color: #ef4444;
        }

        .tk-modal-node-perpanjang {
            border-color: #3b82f6;
        }

        .tk-modal-node-aktif {
            border-color: #10b981;
        }

        .tk-modal-node-label {
            font-size: .85rem;
            font-weight: 800;
            letter-spacing: .5px;
            padding: 3px 10px;
            border-radius: 6px;
            margin-bottom: 6px;
            background: #e0e7ff;
            color: #3730a3;
        }

        .tk-modal-node-upgrade .tk-modal-node-label {
            background: #d1fae5;
            color: #065f46;
        }

        .tk-modal-node-downgrade .tk-modal-node-label {
            background: #fee2e2;
            color: #991b1b;
        }

        .tk-modal-node-perpanjang .tk-modal-node-label {
            background: #dbeafe;
            color: #1e40af;
        }

        .tk-modal-arrow {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            flex-shrink: 0;
        }

        .tk-modal-arrow-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            color: #fff;
        }

        .tk-modal-arrow-label {
            font-size: .6rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        /* Modal info */
        .tk-modal-info {
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: .85rem 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            font-size: .82rem;
        }

        .tk-modal-info-item {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .tk-modal-info-item .label {
            font-size: .65rem;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
        }

        .tk-modal-info-item .value {
            font-weight: 600;
            color: #1e293b;
        }

        /* Toggle Top Mgmt */
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

        @media(max-width:768px) {
            .tk-karyawan-row {
                flex-direction: column;
            }

            .tk-kry-info {
                min-width: unset;
                flex: unset;
            }
        }

        /* ── Row karyawan ── */
        .tk-row {
            display: grid;
            grid-template-columns: 260px 1fr 80px;
            gap: 16px;
            align-items: center;
            padding: 14px 20px;
            border-bottom: 1px solid #f1f5f9;
            transition: background .12s;
        }

        .tk-row:last-child {
            border-bottom: none;
        }

        .tk-row:hover {
            background: #f8faff;
        }

        .tk-row-even {
            background: #fdfeff;
        }

        .tk-row-even:hover {
            background: #f5f8ff;
        }

        /* Kolom identitas */
        .tk-col-identity {
            min-width: 0;
        }

        .tk-nama {
            font-size: .88rem;
            color: #1e293b;
        }

        .tk-nrk {
            font-size: .7rem;
            color: #94a3b8;
            background: #f1f5f9;
            padding: 1px 6px;
            border-radius: 4px;
            font-family: monospace;
        }

        .tk-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            font-size: .72rem;
            color: #64748b;
        }

        .tk-meta i {
            margin-right: 3px;
            color: #94a3b8;
        }

        /* Status badge */
        .tk-sts-badge {
            padding: 1px 8px;
            border-radius: 10px;
            font-size: .65rem;
            font-weight: 700;
        }

        .tk-sts-aktif {
            background: #d1fae5;
            color: #065f46;
        }

        .tk-sts-nonaktif {
            background: #fee2e2;
            color: #991b1b;
        }

        .tk-sts-calon {
            background: #fef3c7;
            color: #92400e;
        }

        /* Pill badges */
        .tk-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: .66rem;
            font-weight: 600;
        }

        .tk-pill i {
            font-size: .6rem;
        }

        .tk-pill-upgrade {
            background: #d1fae5;
            color: #065f46;
        }

        .tk-pill-perpanjang {
            background: #dbeafe;
            color: #1e40af;
        }

        .tk-pill-downgrade {
            background: #fee2e2;
            color: #991b1b;
        }

        .tk-pill-neutral {
            background: #f1f5f9;
            color: #475569;
        }

        /* ── Timeline strip ── */
        .tk-col-timeline {
            overflow-x: auto;
            padding: 4px 0;
        }

        .tk-col-timeline::-webkit-scrollbar {
            height: 3px;
        }

        .tk-col-timeline::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 3px;
        }

        .tk-timeline-strip {
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: max-content;
        }

        /* Node kontrak */
        .tk-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            padding: 8px 12px;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            background: #fff;
            min-width: 100px;
            text-align: center;
            transition: box-shadow .15s;
        }

        .tk-node-lama {
            border-color: #e2e8f0;
            opacity: .7;
        }

        .tk-node-aktif {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, .1);
        }

        .tk-node-is-upgrade {
            border-color: #4c6ef5;
        }

        .tk-node-is-downgrade {
            border-color: #ef4444;
        }

        /* Chip singkatan di node */
        .tk-node-chip {
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .4px;
            padding: 2px 8px;
            border-radius: 5px;
            background: #e0e7ff;
            color: #3730a3;
            white-space: nowrap;
        }

        .tk-node-aktif .tk-node-chip {
            background: #d1fae5;
            color: #065f46;
        }

        .tk-node-is-downgrade .tk-node-chip {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Periode & durasi di node */
        .tk-node-period {
            font-size: .62rem;
            color: #94a3b8;
            line-height: 1.4;
        }

        .tk-node-dur {
            font-size: .65rem;
            font-weight: 700;
            color: #475569;
        }

        /* Konektor antar node */
        .tk-connector {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            flex-shrink: 0;
            font-size: .65rem;
            color: #fff;
        }

        .tk-connector-upgrade {
            background: #10b981;
        }

        .tk-connector-downgrade {
            background: #ef4444;
        }

        .tk-connector-perpanjang {
            background: #3b82f6;
        }

        /* Kolom aksi */
        .tk-col-action {
            display: flex;
            justify-content: center;
        }

        .tk-btn-detail {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #c7d2fe;
            background: #f0f4ff;
            color: #4c6ef5;
            font-size: .7rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s;
            white-space: nowrap;
        }

        .tk-btn-detail i {
            font-size: .85rem;
        }

        .tk-btn-detail:hover {
            background: #4c6ef5;
            color: #fff;
            border-color: #4c6ef5;
            box-shadow: 0 4px 12px rgba(76, 110, 245, .3);
        }

        @media (max-width: 768px) {
            .tk-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const DETAIL_URL = '{{ route('reports.tracking-kontrak.detail', ':id') }}';
            const AREA_URL = '{{ route('reports.tracking-kontrak.area-by-wilker') }}';

            // ── Toggle teks ──
            const tkToggle = document.getElementById('tkToggleTopMgmt');
            if (tkToggle) {
                tkToggle.addEventListener('change', function() {
                    document.querySelector('#tkToggleLabel .siska-toggle-text').textContent =
                        this.checked ? 'Disertakan' : 'Dikecualikan';
                });
            }

            // ── Dropdown area ──
            const fWilker = document.getElementById('tkFilterWilker');
            const fArea = document.getElementById('tkFilterArea');
            if (fWilker && fArea) {
                fWilker.addEventListener('change', function() {
                    fetch(AREA_URL + (this.value ? '?wilker=' + encodeURIComponent(this.value) : ''))
                        .then(r => r.json())
                        .then(data => {
                            fArea.innerHTML = '<option value="">— Semua Area —</option>';
                            data.forEach(ak => {
                                fArea.innerHTML +=
                                    `<option value="${ak.id}">${ak.area_krj}${ak.singkatan_wk ? ' (' + ak.singkatan_wk + ')' : ''}</option>`;
                            });
                        });
                });
            }

            // ── Chart distribusi kontrak aktif ──
            const ktrData = @json($perKontrakAktif->values());
            const PAL = ['#4c6ef5', '#10b981', '#f59e0b', '#dc2626', '#7c3aed', '#0891b2'];
            Chart.defaults.font.family = "'Segoe UI',sans-serif";

            if (ktrData.length && document.getElementById('chartKontrakAktif')) {
                new Chart(document.getElementById('chartKontrakAktif'), {
                    type: 'doughnut',
                    data: {
                        labels: ktrData.map(r => r.label),
                        datasets: [{
                            data: ktrData.map(r => r.jumlah),
                            backgroundColor: PAL,
                            borderWidth: 3,
                            borderColor: '#fff',
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        cutout: '60%',
                        responsive: true,
                        plugins: {
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 10,
                                cornerRadius: 6
                            },
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 14,
                                    boxWidth: 14
                                }
                            }
                        }
                    }
                });
            }

            // ── Buka modal detail ──
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.tk-btn-detail');
                if (!btn) return;

                const id = btn.dataset.id;
                const nama = btn.dataset.nama;
                const url = DETAIL_URL.replace(':id', id);

                document.getElementById('tkModalTitle').textContent = 'Timeline Kontrak — ' + nama;
                document.getElementById('tkModalSub').textContent = '';
                document.getElementById('tkLoading').style.display = 'block';
                document.getElementById('tkDetailContent').style.display = 'none';

                const modal = new bootstrap.Modal(document.getElementById('tkModal'));
                modal.show();

                fetch(url)
                    .then(r => r.json())
                    .then(res => {
                        document.getElementById('tkLoading').style.display = 'none';
                        document.getElementById('tkModalSub').textContent =
                            res.timeline.length + ' riwayat kontrak';

                        // Info karyawan
                        const k = res.karyawan;
                        document.getElementById('tkModalInfo').innerHTML = `
                    <div class="tk-modal-info-item">
                        <span class="label">NRK</span>
                        <span class="value"><code style="background:#f1f5f9;padding:1px 6px;border-radius:4px;font-size:.78rem;">${k.nrk}</code></span>
                    </div>
                    <div class="tk-modal-info-item">
                        <span class="label">Nama</span>
                        <span class="value">${k.nama}</span>
                    </div>
                    <div class="tk-modal-info-item">
                        <span class="label">Perusahaan</span>
                        <span class="value">${k.perusahaan}</span>
                    </div>
                    <div class="tk-modal-info-item">
                        <span class="label">Departemen</span>
                        <span class="value">${k.departemen}</span>
                    </div>
                    <div class="tk-modal-info-item">
                        <span class="label">Jabatan</span>
                        <span class="value">${k.jabatan}</span>
                    </div>
                    <div class="tk-modal-info-item">
                        <span class="label">Tgl Masuk</span>
                        <span class="value">${k.tgl_masuk}</span>
                    </div>
                    <div class="tk-modal-info-item">
                        <span class="label">Status</span>
                        <span class="value">
                            <span class="badge ${k.sts_kry === 'AKTIF' ? 'bg-success' : 'bg-danger'}">${k.sts_kry}</span>
                        </span>
                    </div>
                `;

                        // Timeline visual
                        const tl = res.timeline;
                        const arrowColor = {
                            upgrade: '#10b981',
                            downgrade: '#ef4444',
                            perpanjangan: '#3b82f6',
                            baru: '#94a3b8'
                        };
                        const arrowIcon = {
                            upgrade: 'fa-arrow-up',
                            downgrade: 'fa-arrow-down',
                            perpanjangan: 'fa-redo',
                            baru: 'fa-plus'
                        };
                        const arrowLabel = {
                            upgrade: 'Upgrade',
                            downgrade: 'Downgrade',
                            perpanjangan: 'Perpanjang',
                            baru: 'Awal'
                        };
                        const nodeClass = {
                            upgrade: 'tk-modal-node-upgrade',
                            downgrade: 'tk-modal-node-downgrade',
                            perpanjangan: 'tk-modal-node-perpanjang',
                            baru: 'tk-modal-node-baru'
                        };

                        document.getElementById('tkModalTimeline').innerHTML = tl.map((t, i) => {
                            const arrow = i > 0 ? `
                        <div class="tk-modal-arrow">
                            <div class="tk-modal-arrow-icon" style="background:${arrowColor[t.arah] || '#94a3b8'};">
                                <i class="fas ${arrowIcon[t.arah] || 'fa-arrow-right'}"></i>
                            </div>
                            <div class="tk-modal-arrow-label">${arrowLabel[t.arah] || ''}</div>
                        </div>` : '';

                            const aktifClass = t.status === 'AKTIF' ? 'tk-modal-node-aktif' :
                                '';
                            const sisaBulan = t.sisa_bulan !== null ?
                                `<div style="font-size:.65rem;color:#10b981;font-weight:600;margin-top:2px;">sisa ${t.sisa_bulan} bln</div>` :
                                '';

                            return `${arrow}
                    <div class="tk-modal-node ${nodeClass[t.arah] || ''} ${aktifClass}">
                        <div class="tk-modal-node-label">${t.singkatan}</div>
                        <div style="font-size:.72rem;color:#64748b;margin-bottom:3px;">${t.nama_ktr}</div>
                        <div style="font-size:.65rem;color:#94a3b8;">${t.tgl_mulai}</div>
                        <div style="font-size:.65rem;color:#94a3b8;">→ ${t.tgl_akhir}</div>
                        <div style="font-size:.67rem;color:#475569;font-weight:600;margin-top:2px;">${t.durasi}</div>
                        ${sisaBulan}
                        <span class="badge mt-1 ${t.status === 'AKTIF' ? 'bg-success' : 'bg-secondary'}"
                              style="font-size:.6rem;">${t.status}</span>
                    </div>`;
                        }).join('');

                        // Tabel detail
                        const stsClass = s => s === 'AKTIF' ?
                            'background:#d1fae5;color:#065f46;' :
                            'background:#fee2e2;color:#991b1b;';
                        const arahBadge = a => {
                            const map = {
                                upgrade: ['bg-success', 'Upgrade'],
                                downgrade: ['bg-danger', 'Downgrade'],
                                perpanjangan: ['bg-primary', 'Perpanjang'],
                                baru: ['bg-secondary', 'Kontrak Awal'],
                            };
                            const [cls, lbl] = map[a] || ['bg-secondary', a];
                            return `<span class="badge ${cls}" style="font-size:.65rem;">${lbl}</span>`;
                        };

                        document.getElementById('tkDetailBody').innerHTML = tl.map((t, i) => `
                <tr ${t.status === 'AKTIF' ? 'style="background:#f0fdf4;"' : ''}>
                    <td class="text-muted text-center">${i + 1}</td>
                    <td>${arahBadge(t.arah)}</td>
                    <td>
                        <span class="badge" style="background:#e0e7ff;color:#3730a3;font-size:.72rem;font-weight:700;">${t.singkatan}</span>
                        <small class="text-muted d-block">${t.nama_ktr}</small>
                    </td>
                    <td style="font-size:.78rem;">${t.no_surat}</td>
                    <td style="font-size:.78rem;white-space:nowrap;">${t.tgl_surat}</td>
                    <td style="font-size:.78rem;white-space:nowrap;">${t.tgl_mulai}</td>
                    <td style="font-size:.78rem;white-space:nowrap;">${t.tgl_akhir}</td>
                    <td style="font-size:.78rem;">${t.durasi}</td>
                    <td style="font-size:.75rem;">${t.perusahaan}</td>
                    <td>
                        <span style="${stsClass(t.status)}padding:2px 8px;border-radius:10px;font-size:.7rem;font-weight:600;">
                            ${t.status}
                        </span>
                    </td>
                </tr>`).join('');

                        document.getElementById('tkDetailContent').style.display = 'block';
                    })
                    .catch(() => {
                        document.getElementById('tkLoading').style.display = 'none';
                        document.getElementById('tkDetailContent').innerHTML =
                            '<div class="text-center text-danger py-4">Gagal memuat data</div>';
                        document.getElementById('tkDetailContent').style.display = 'block';
                    });
            });
        });
    </script>
@endpush
