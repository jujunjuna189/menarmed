@extends('layouts.app_template')
@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
    <div>
        <h2 class="page-title mb-1">Ringkasan Operasional</h2>
        <div class="text-muted">Data personel dan aktivitas satuan hari ini</div>
    </div>
    <div id="dashboard-updated" class="text-muted small mt-2 mt-md-0">
        Diperbarui {{ $updatedAt->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}
    </div>
</div>

<div class="row row-deck row-cards mb-3">
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('pengguna', ['key' => 3]) }}" class="card card-link text-reset">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <span class="avatar bg-blue-lt text-blue me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/></svg>
                    </span>
                    <div>
                        <div class="text-muted small">Total Personel</div>
                        <div class="h1 mb-0" data-summary="personnel">{{ number_format($summary['personnel']) }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('report.absensi') }}" class="card card-link text-reset">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <span class="avatar bg-green-lt text-green me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l3 3l8 -8"/><path d="M20 12a8 8 0 1 1 -5.3 -7.55"/></svg>
                    </span>
                    <div class="flex-fill">
                        <div class="text-muted small">Hadir Hari Ini</div>
                        <div class="d-flex align-items-baseline gap-2">
                            <div class="h1 mb-0" data-summary="attendance_today">{{ number_format($summary['attendance_today']) }}</div>
                            <span class="text-green small" data-summary="attendance_rate">{{ $summary['attendance_rate'] }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('report.perizinan') }}" class="card card-link text-reset">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <span class="avatar bg-yellow-lt text-yellow me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 7h6"/><path d="M9 11h6"/><path d="M9 15h4"/></svg>
                    </span>
                    <div>
                        <div class="text-muted small">Izin Aktif</div>
                        <div class="h1 mb-0" data-summary="active_permits">{{ number_format($summary['active_permits']) }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('saran') }}" class="card card-link text-reset">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <span class="avatar bg-purple-lt text-purple me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10"/><path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2"/></svg>
                    </span>
                    <div>
                        <div class="text-muted small">Saran Bulan Ini</div>
                        <div class="h1 mb-0" data-summary="suggestions_this_month">{{ number_format($summary['suggestions_this_month']) }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row row-deck row-cards mb-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Tren Kehadiran</h3>
                    <div class="text-muted small">Personel unik yang melakukan absensi dalam 7 hari terakhir</div>
                </div>
            </div>
            <div class="card-body">
                <div id="attendance-trend-chart" class="chart-lg"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Izin Aktif</h3>
                    <div class="text-muted small">Berdasarkan kategori perizinan</div>
                </div>
            </div>
            <div class="card-body">
                <div id="permit-chart" class="chart-lg"></div>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck row-cards">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Komposisi Personel</h3>
                    <div class="text-muted small">Jumlah pengguna berdasarkan kelompok akses</div>
                </div>
            </div>
            <div class="card-body">
                <div id="personnel-chart" class="chart-lg"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div>
                    <h3 class="card-title">Absensi Terbaru</h3>
                    <div class="text-muted small">Aktivitas kehadiran terakhir yang tercatat</div>
                </div>
                <a href="{{ route('report.absensi') }}" class="btn btn-sm btn-outline-primary ms-auto">Lihat Rekap</a>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Personel</th>
                            <th>Keterangan</th>
                            <th class="text-end">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAttendances as $attendance)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ optional($attendance->userModel)->name ?? 'Personel tidak ditemukan' }}</div>
                                <div class="text-muted small">{{ optional($attendance->userModel)->pangkat ?? '-' }}</div>
                            </td>
                            <td><span class="badge bg-azure-lt">{{ $attendance->ket ?: 'Tanpa keterangan' }}</span></td>
                            <td class="text-end text-muted text-nowrap">{{ optional($attendance->created_at)->locale('id')->isoFormat('D MMM, HH:mm') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Belum ada data absensi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const attendanceTrend = @json($attendanceTrend);
    const activePermits = @json($activePermits);
    const personnelByRole = @json($personnelByRole);
    const chartTextColor = '#626976';
    const gridColor = '#e6e7e9';

    const attendanceChart = new ApexCharts(document.querySelector('#attendance-trend-chart'), {
        chart: { type: 'area', height: 300, toolbar: { show: false }, animations: { enabled: false } },
        series: [{ name: 'Personel hadir', data: attendanceTrend.map(item => item.total) }],
        xaxis: { categories: attendanceTrend.map(item => item.label), labels: { style: { colors: chartTextColor } } },
        yaxis: { min: 0, forceNiceScale: true, labels: { formatter: value => Math.round(value), style: { colors: chartTextColor } } },
        colors: ['#206bc4'],
        stroke: { curve: 'smooth', width: 3 },
        fill: { type: 'solid', opacity: 0.12 },
        dataLabels: { enabled: false },
        grid: { borderColor: gridColor, strokeDashArray: 4 },
        tooltip: { y: { formatter: value => value + ' personel' } }
    });

    const permitChart = new ApexCharts(document.querySelector('#permit-chart'), {
        chart: { type: 'donut', height: 300 },
        series: Object.values(activePermits),
        labels: Object.keys(activePermits),
        colors: ['#f59f00', '#d63939', '#4299e1'],
        legend: { position: 'bottom' },
        dataLabels: { enabled: false },
        noData: { text: 'Tidak ada izin aktif' },
        plotOptions: { pie: { donut: { size: '68%', labels: { show: true, total: { show: true, label: 'Total' } } } } }
    });

    const personnelChart = new ApexCharts(document.querySelector('#personnel-chart'), {
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        series: [{ name: 'Personel', data: personnelByRole.map(item => item.total) }],
        xaxis: { categories: personnelByRole.map(item => item.label), labels: { style: { colors: chartTextColor } } },
        yaxis: { min: 0, forceNiceScale: true, labels: { formatter: value => Math.round(value), style: { colors: chartTextColor } } },
        colors: ['#2fb344'],
        plotOptions: { bar: { borderRadius: 3, columnWidth: '44%' } },
        dataLabels: { enabled: false },
        grid: { borderColor: gridColor, strokeDashArray: 4 },
        tooltip: { y: { formatter: value => value + ' personel' } }
    });
    const chartsReady = Promise.all([attendanceChart.render(), permitChart.render(), personnelChart.render()]);
    let refreshing = false;
    async function refreshStatistics() {
        if (document.hidden || refreshing) return;
        refreshing = true;
        const controller = new AbortController();
        const timeout = setTimeout(() => controller.abort(), 15000);
        try {
            const response = await fetch(@json(route('home')), {
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
                cache: 'no-store',
                signal: controller.signal
            });
            if (!response.ok || response.redirected) return;
            const data = await response.json();
            await chartsReady;
            document.querySelectorAll('[data-summary]').forEach(element => {
                const key = element.dataset.summary;
                element.textContent = key === 'attendance_rate'
                    ? data.summary[key] + '%'
                    : Number(data.summary[key]).toLocaleString();
            });
            await Promise.all([
                attendanceChart.updateOptions({
                    series: [{ name: 'Personel hadir', data: data.attendanceTrend.map(item => item.total) }],
                    xaxis: { categories: data.attendanceTrend.map(item => item.label) }
                }),
                permitChart.updateOptions({ series: Object.values(data.activePermits), labels: Object.keys(data.activePermits) }),
                personnelChart.updateOptions({
                    series: [{ name: 'Personel', data: data.personnelByRole.map(item => item.total) }],
                    xaxis: { categories: data.personnelByRole.map(item => item.label) }
                })
            ]);
            document.querySelector('#dashboard-updated').textContent = 'Diperbarui ' + data.updatedAt;
        } catch (_) {
            // Preserve the last successful statistics on network errors.
        } finally {
            clearTimeout(timeout);
            refreshing = false;
        }
    }
    const interval = setInterval(refreshStatistics, 30000);
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) refreshStatistics();
    });
    window.addEventListener('pagehide', () => clearInterval(interval), { once: true });
});
</script>
@endpush
