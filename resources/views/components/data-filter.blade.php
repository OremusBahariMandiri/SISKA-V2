@props([
    'route' => '#',
    'filters' => [],
    'options' => [],
    'title' => 'Filter Data',
])

{{-- ===== ACTIVE FILTER ALERT ===== --}}
@php
    $hasActiveFilter = collect($filters)->filter(fn($v) => $v !== '' && $v !== null)->isNotEmpty();
@endphp

@if($hasActiveFilter)
    <div class="alert alert-info d-flex align-items-center flex-wrap gap-2" role="alert" id="filterActiveAlert">
        <i class="fas fa-info-circle me-1"></i>
        <strong>Filter Aktif:</strong>

        @foreach($options as $option)
            @php $val = $filters[$option['name']] ?? ''; @endphp
            @if($val !== '' && $val !== null)
                @if($option['type'] === 'text')
                    <span class="badge bg-primary">
                        {{ $option['label'] }}: {{ $val }}
                    </span>
                @elseif($option['type'] === 'select')
                    @php
                        $selected = collect($option['data'])->firstWhere('value', $val);
                    @endphp
                    @if($selected)
                        <span class="badge bg-primary">
                            {{ $option['label'] }}: {{ $selected['label'] }}
                        </span>
                    @endif
                @endif
            @endif
        @endforeach

        <a href="{{ $route }}" class="btn btn-sm btn-outline-secondary ms-1">
            <i class="fas fa-times me-1"></i> Reset Filter
        </a>
    </div>
@endif

{{-- ===== FILTER MODAL ===== --}}
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-filter me-2"></i>{{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm" method="GET" action="{{ $route }}">
                    <div class="row">
                        @foreach($options as $index => $option)
                            <div class="col-md-6 {{ $index >= 2 ? 'mt-2' : '' }}">
                                <div class="form-group">
                                    <label for="filter_{{ $option['name'] }}" class="form-label fw-bold">
                                        {{ $option['label'] }}
                                    </label>

                                    @if($option['type'] === 'select')
                                        <select class="form-select select2-filter"
                                                id="filter_{{ $option['name'] }}"
                                                name="filter_{{ $option['name'] }}">
                                            <option value="">{{ $option['placeholder'] ?? 'Semua ' . $option['label'] }}</option>
                                            @foreach($option['data'] as $item)
                                                <option value="{{ $item['value'] }}"
                                                    {{ ($filters[$option['name']] ?? '') == $item['value'] ? 'selected' : '' }}>
                                                    {{ $item['label'] }}
                                                </option>
                                            @endforeach
                                        </select>

                                    @elseif($option['type'] === 'text')
                                        <input type="text"
                                               class="form-control"
                                               id="filter_{{ $option['name'] }}"
                                               name="filter_{{ $option['name'] }}"
                                               placeholder="{{ $option['placeholder'] ?? 'Cari ' . $option['label'] . '...' }}"
                                               value="{{ $filters[$option['name']] ?? '' }}">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-secondary me-2" id="filterResetBtn">
                                <i class="fas fa-redo me-1"></i> Reset Semua Filter
                            </button>
                            <button type="button" class="btn btn-primary" id="filterApplyBtn">
                                <i class="fas fa-search me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {
    const filterRoute = "{{ $route }}";

    // ===== SELECT2 INIT =====
    function initSelect2() {
        $('#filterModal .select2-filter').each(function () {
            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).select2('destroy');
            }
            $(this).select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#filterModal'),
                width: '100%',
                allowClear: true,
                placeholder: $(this).find('option:first').text() || 'Pilih...',
                language: {
                    noResults: () => "Tidak ada hasil ditemukan",
                    searching: () => "Mencari...",
                }
            });
        });
    }

    $('#filterModal').on('shown.bs.modal', initSelect2);
    $('#filterModal').on('hidden.bs.modal', function () {
        $('#filterModal .select2-filter').each(function () {
            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).select2('destroy');
            }
        });
    });

    // ===== APPLY =====
    $('#filterApplyBtn').on('click', function () {
        $('#filterForm').submit();
    });

    // ===== RESET =====
    $('#filterResetBtn').on('click', function () {
        window.location.href = filterRoute;
    });

    // ===== OPEN MODAL via #filterButton =====
    $('#filterButton').on('click', function () {
        $('#filterModal').modal('show');
    });
});
</script>
@endpush