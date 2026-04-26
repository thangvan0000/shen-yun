@extends('layouts.app', ['title' => 'Báo cáo'])

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-on-surface">Báo cáo đăng ký</h1>
            <p class="text-sm text-on-surface-variant mt-1">
                {{ $start->format('d/m/Y') }} – {{ $end->format('d/m/Y') }}
            </p>
        </div>

        {{-- Filter toggle --}}
        <div class="flex items-center gap-2">
            <a href="{{ url('/admin/reports?filter=week') }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition-all
                      {{ $filter === 'week'
                         ? 'bg-primary text-on-primary shadow-sm'
                         : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }}">
                Theo tuần
            </a>
            <a href="{{ url('/admin/reports?filter=month') }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition-all
                      {{ $filter === 'month'
                         ? 'bg-primary text-on-primary shadow-sm'
                         : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest' }}">
                Theo tháng
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ([
            ['Khách',   $totals['adult'],   'person',     'bg-blue-50    text-[#0058bc]'],
            ['NTL',     $totals['ntl'],     'groups',     'bg-amber-50   text-[#b8913a]'],
            ['NTL mới', $totals['ntl_new'], 'person_add', 'bg-emerald-50 text-[#2d6a4f]'],
            ['Trẻ em',  $totals['child'],   'child_care', 'bg-red-50     text-[#c2564a]'],
        ] as [$label, $value, $icon, $colorClass])
        <div class="admin-card p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl {{ $colorClass }} flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-xl">{{ $icon }}</span>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-on-surface">{{ number_format($value) }}</div>
                <div class="text-xs text-on-surface-variant font-medium mt-0.5">{{ $label }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Chart --}}
    <div class="admin-card p-6">
        <h2 class="text-base font-bold text-on-surface mb-5">Số lượng theo ngày</h2>
        <div class="relative" style="height: 340px;">
            <canvas id="reportChart"></canvas>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    const labels   = @json($labels);
    const datasets = [
        {
            label: 'Khách',
            data: @json($adultData),
            backgroundColor: 'rgba(0,88,188,0.75)',
            borderColor: '#0058bc',
            borderWidth: 1,
            borderRadius: 6,
        },
        {
            label: 'NTL',
            data: @json($ntlData),
            backgroundColor: 'rgba(217,183,111,0.80)',
            borderColor: '#d9b76f',
            borderWidth: 1,
            borderRadius: 6,
        },
        {
            label: 'NTL mới',
            data: @json($ntlNewData),
            backgroundColor: 'rgba(45,106,79,0.75)',
            borderColor: '#2d6a4f',
            borderWidth: 1,
            borderRadius: 6,
        },
        {
            label: 'Trẻ em',
            data: @json($childData),
            backgroundColor: 'rgba(194,86,74,0.75)',
            borderColor: '#c2564a',
            borderWidth: 1,
            borderRadius: 6,
        },
    ];

    new Chart(document.getElementById('reportChart'), {
        type: 'bar',
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { family: 'Manrope', size: 12 }, usePointStyle: true, pointStyle: 'rectRounded' }
                },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Manrope', size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, precision: 0, font: { family: 'Manrope', size: 11 } },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            }
        }
    });
</script>
@endpush
@endsection
