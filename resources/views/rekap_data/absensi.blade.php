@extends('layouts.app_template')
@section('content')
<style>
    .monthly-attendance .btn { box-shadow: none !important; }
    .monthly-table { border-collapse: separate; border-spacing: 0; }
    .monthly-table td, .monthly-table th { border-right: 1px solid #e6eaf0; text-align: center; padding: 8px 6px; }
    .monthly-table .number-column { position: sticky; left: 0; z-index: 1; width: 48px; min-width: 48px; max-width: 48px; box-sizing: border-box; background: #fff; }
    .monthly-table thead .number-column { background: #f6f8fb; z-index: 2; }
    .monthly-table .person-column { position: sticky; left: 48px; z-index: 1; min-width: 200px; max-width: 240px; text-align: left; background: #fff; border-right: 2px solid #dce1e7; white-space: normal; overflow-wrap: anywhere; }
    .monthly-table thead .person-column { background: #f6f8fb; z-index: 2; }
    .monthly-table .day-column { min-width: 44px; }
    .monthly-table .weekend { background: #f6f8fb; }
    .attendance-cell { display: inline-flex; align-items: center; justify-content: center; min-width: 28px; height: 28px; padding: 0 5px; border: 0; border-radius: 4px; background: #eaf2fc; color: #206bc4; font-size: 12px; font-weight: 600; cursor: pointer; }
    .attendance-cell:hover { background: #dceafa; }
    .attendance-cell:focus-visible { outline: 2px solid #206bc4; outline-offset: 2px; }
    .attendance-cell[data-code="H"] { background: #e8f6ec; color: #247a36; }
    .attendance-cell[data-code="I"], .attendance-cell[data-code="CUTI"] { background: #fff4d6; color: #946200; }
    .attendance-cell[data-code="S"], .attendance-cell[data-code="A"] { background: #fde9e7; color: #b3322d; }
</style>
<div class="monthly-attendance">
    <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-3 mb-3">
        <div><h2 class="page-title mb-1">Rekap Absensi</h2><div class="text-muted">{{ $month->locale('id')->translatedFormat('F Y') }}</div></div>
        <form action="{{ route('report.absensi') }}" method="GET" class="d-flex flex-wrap gap-2">
            <input type="month" name="month" class="form-control w-auto" value="{{ $month->format('Y-m') }}" onchange="this.form.requestSubmit()" aria-label="Bulan rekap">
            <div class="input-icon">
                <span class="input-icon-addon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="10" cy="10" r="7"/><path d="m21 21-6-6"/></svg></span>
                <input type="search" name="filter[name]" class="form-control" placeholder="Cari personel" value="{{ $search_name }}" aria-label="Cari personel">
            </div>
            <select name="page[size]" class="form-select w-auto" onchange="this.form.requestSubmit()" aria-label="Jumlah personel">
                @foreach([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ $page_size === $size ? 'selected' : '' }}>{{ $size }} data</option>
                @endforeach
            </select>
            <a class="btn btn-outline-primary" href="{{ route('report.absensi.export', ['month' => $month->format('Y-m'), 'filter' => ['name' => $search_name]]) }}">Excel</a>
            <a class="btn btn-outline-secondary" href="{{ route('report.absensi.pdf', ['month' => $month->format('Y-m'), 'filter' => ['name' => $search_name]]) }}">PDF</a>
        </form>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="d-flex flex-wrap gap-3 text-muted small mb-3">
        <span>H: Hadir</span><span>I: Ijin</span><span>S: Sakit</span><span>CUTI</span><span>BP</span><span>DD</span><span>DIK</span><span>DK</span><span>DL</span><span>-: Tidak ada data</span>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter monthly-table mb-0">
                <thead><tr>
                    <th class="number-column">No.</th>
                    <th class="person-column">Personel</th>
                    @for($day = 1; $day <= $month->daysInMonth; $day++)
                        @php
                            $date = $month->copy()->day($day);
                        @endphp
                        <th class="day-column {{ $date->isWeekend() ? 'weekend' : '' }}">
                            <div>{{ $day }}</div><div class="text-muted" style="font-size: 10px">{{ $date->locale('id')->translatedFormat('D') }}</div>
                        </th>
                    @endfor
                </tr></thead>
                <tbody>
                    @forelse($people as $person)
                        <tr>
                            <td class="number-column text-muted">{{ $people->firstItem() + $loop->index }}</td>
                            <td class="person-column"><div class="fw-medium">{{ $person->name }}</div><div class="text-muted small">{{ $person->pangkat ?: '-' }}</div></td>
                            @for($day = 1; $day <= $month->daysInMonth; $day++)
                                @php
                                    $records = $attendance->get($person->id . ':' . $day, collect());
                                    $codes = $records->map(fn($record) => \App\Support\MonthlyAttendance::code($record->ket))->unique();
                                    $code = $codes->implode('/');
                                @endphp
                                <td class="{{ $month->copy()->day($day)->isWeekend() ? 'weekend' : '' }}">
                                    @if($records->isNotEmpty())
                                        <button type="button" class="attendance-cell" data-code="{{ $code }}" data-bs-toggle="modal" data-bs-target="#attendance-{{ $person->id }}-{{ $day }}" title="{{ $records->pluck('ket')->implode(', ') }}" aria-label="Absensi {{ $person->name }} tanggal {{ $day }}">{{ \Illuminate\Support\Str::limit($code, 6) }}</button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @empty
                        <tr><td colspan="{{ $month->daysInMonth + 2 }}" class="text-center text-muted py-4">Personel tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
            <p class="m-0 text-muted">Menampilkan {{ $people->firstItem() ?? 0 }} sampai {{ $people->lastItem() ?? 0 }} dari {{ $people->total() }} personel</p>
            <ul class="pagination m-0 ms-auto">
                <li class="page-item {{ $people->onFirstPage() ? 'disabled' : '' }}"><a class="page-link" href="{{ $controller->prevPagination($people->currentPage(), 'report.absensi', request()->all())->link }}">Sebelumnya</a></li>
                @foreach($controller->counterPagination($people->lastPage(), $people->currentPage(), 'report.absensi', request()->all()) as $page)
                    <li class="page-item {{ $page->is_active ? 'active' : '' }}"><a class="page-link" href="{{ $page->link }}">{{ $page->lable }}</a></li>
                @endforeach
                <li class="page-item {{ $people->hasMorePages() ? '' : 'disabled' }}"><a class="page-link" href="{{ $controller->nextPagination($people->currentPage(), $people->lastPage(), 'report.absensi', request()->all())->link }}">Berikutnya</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection
@section('modal')
@foreach($people as $person)
    @for($day = 1; $day <= $month->daysInMonth; $day++)
        @php
            $records = $attendance->get($person->id . ':' . $day, collect());
        @endphp
        @if($records->isNotEmpty())
            <div class="modal fade" id="attendance-{{ $person->id }}-{{ $day }}" tabindex="-1" aria-labelledby="attendance-title-{{ $person->id }}-{{ $day }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header"><h3 class="modal-title" id="attendance-title-{{ $person->id }}-{{ $day }}">{{ $person->name }} · {{ $month->copy()->day($day)->format('d/m/Y') }}</h3><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
                        <div class="modal-body">
                            @foreach($records as $record)
                                <form action="{{ route('report.absensi.update', $record->id) }}" method="POST" class="{{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                                    @csrf @method('PUT')
                                    <label for="attendance-status-{{ $record->id }}" class="form-label">Keterangan</label>
                                    <input id="attendance-status-{{ $record->id }}" name="ket" class="form-control mb-3" value="{{ $record->ket }}" maxlength="255" required>
                                    <label for="attendance-date-{{ $record->id }}" class="form-label">Tanggal dan Waktu</label>
                                    <input id="attendance-date-{{ $record->id }}" type="datetime-local" name="created_at" class="form-control mb-3" value="{{ $record->created_at->format('Y-m-d\TH:i') }}" required>
                                    @php
                                        $latitude = $record->latitude;
                                        $longitude = $record->longitude;
                                        $hasCoordinates = is_numeric($latitude) && is_numeric($longitude)
                                            && $latitude >= -90 && $latitude <= 90
                                            && $longitude >= -180 && $longitude <= 180;
                                    @endphp
                                    <div class="mb-3">
                                        <div class="form-label">Lokasi Absensi</div>
                                        @if($hasCoordinates)
                                            <div class="row g-2 mb-2">
                                                <div class="col-sm-6">
                                                    <label for="attendance-latitude-{{ $record->id }}" class="form-label text-muted small">Latitude</label>
                                                    <input id="attendance-latitude-{{ $record->id }}" class="form-control" value="{{ $latitude }}" readonly>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="attendance-longitude-{{ $record->id }}" class="form-label text-muted small">Longitude</label>
                                                    <input id="attendance-longitude-{{ $record->id }}" class="form-control" value="{{ $longitude }}" readonly>
                                                </div>
                                            </div>
                                            <a href="https://www.google.com/maps/search/?{{ http_build_query(['api' => 1, 'query' => $latitude . ',' . $longitude]) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s7-6 7-12a7 7 0 0 0-14 0c0 6 7 12 7 12"/><circle cx="12" cy="9" r="2"/></svg>
                                                Buka Peta
                                            </a>
                                        @else
                                            <div class="text-muted small">Koordinat tidak tersedia.</div>
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endfor
@endforeach
@endsection
