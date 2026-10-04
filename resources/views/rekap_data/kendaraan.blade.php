@extends('layouts.app_template')
@section('content')
<style>
    .permission-page .btn, .permission-modal .btn { box-shadow: none !important; }
    .permission-page .table td { padding-top: 10px; padding-bottom: 10px; }
    .permission-purpose { max-width: 220px; overflow: hidden; text-overflow: ellipsis; }
    .permission-action { width: 30px; height: 30px; padding: 0; border: 0; background: transparent; }
    .permission-menu { position: fixed !important; z-index: 1050; border: 1px solid #dce1e7; box-shadow: none; }
</style>
<div class="permission-page">
    <div class="d-flex flex-column gap-3 mb-3">
        <h2 class="page-title mb-0">Rekap Angkutan</h2>
        <form action="{{ route('report.kendaraan') }}" method="GET" class="d-flex flex-wrap align-items-end gap-2">
            <div><label for="permission-start" class="form-label small">Dari</label><input id="permission-start" type="date" name="start_date" class="form-control" value="{{ $start_date }}" onchange="this.form.requestSubmit()"></div>
            <div><label for="permission-end" class="form-label small">Sampai</label><input id="permission-end" type="date" name="end_date" class="form-control" value="{{ $end_date }}" onchange="this.form.requestSubmit()"></div>
            <div class="input-icon">
                <span class="input-icon-addon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="10" cy="10" r="7"/><path d="m21 21-6-6"/></svg></span>
                <input type="search" name="filter[name]" class="form-control" placeholder="Cari personel" value="{{ $search_name }}" aria-label="Cari personel">
            </div>
            <select name="page[size]" class="form-select w-auto" onchange="this.form.requestSubmit()" aria-label="Jumlah data">
                @foreach([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ (int) $page_size === $size ? 'selected' : '' }}>{{ $size }} data</option>
                @endforeach
            </select>
            <a href="{{ route('report.kendaraan.export', ['start_date' => $start_date, 'end_date' => $end_date, 'filter' => ['name' => $search_name]]) }}" class="btn btn-outline-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12m-4-4 4 4 4-4M5 17v4h14v-4"/></svg>Excel
            </a>
            <a href="{{ route('report.kendaraan.pdf', ['start_date' => $start_date, 'end_date' => $end_date, 'filter' => ['name' => $search_name]]) }}" class="btn btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 3h9l4 4v14H6zM14 3v5h5M9 12h7m-7 4h7"/></svg>PDF
            </a>
        </form>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Nama</th>
                            <th>Waktu Keluar</th>
                            <th>Waktu Masuk</th>
                            <th>Tujuan</th>
                            <th>Jenis Kendaraan</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report as $key => $val)
                        <tr>
                            <td>{{ $report->firstItem() + $key }}</td>
                            <td>{{ $val->userModel->name ?? '-' }}</td>
                            <td>{{ $val->keluar != null ? Carbon\Carbon::make($val->keluar)->format('d/m/Y H:i:s') : '-' }}</td>
                            <td>{{ $val->masuk != null ? Carbon\Carbon::make($val->masuk)->format('d/m/Y H:i:s') : '-' }}</td>
                            <td class="permission-purpose" title="{{ $val->tujuan }}">{{ $val->tujuan }}</td>
                            <td>{{ $val->jenis_kendaraan }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-icon permission-action" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi angkutan" aria-label="Aksi angkutan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end permission-menu">
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $val->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 3 5 5-12 12H4v-5L16 3m-2 2 5 5"/></svg>Edit
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Data perizinan tidak ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                <p class="m-0 text-muted">Menampilkan <span>{{$report->firstItem() ?? 0}}</span> sampai <span>{{$report->lastItem() ?? 0}}</span> dari <span>{{$report->total()}}</span> data</p>
                <ul class="pagination m-0 ms-auto">
                    <li class="page-item {{ $report->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{$controller->prevPagination($report->currentPage(), 'report.kendaraan', request()->all())->link}}">
                            <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <polyline points="15 6 9 12 15 18"></polyline>
                            </svg>
                            Sebelumnya
                        </a>
                    </li>
                    @foreach($controller->counterPagination($report->lastPage(), $report->currentPage(), 'report.kendaraan', request()->all()) as $val)
                    <li class="page-item {{$val->is_active ? 'active' : ''}}"><a class="page-link" href="{{$val->link}}">{{$val->lable}}</a></li>
                    @endforeach
                    <li class="page-item {{ $report->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{$controller->nextPagination($report->currentPage(), $report->lastPage(), 'report.kendaraan', request()->all())->link}}">
                            Berikutnya
                            <!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <polyline points="9 6 15 12 9 18"></polyline>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
    </div>
</div>
@endsection
@section('modal')
@foreach($report as $val)
                        <!-- Modal Edit -->
                        <div class="modal fade permission-modal" id="modal-edit-{{ $val->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('report.kendaraan.update', $val->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Angkutan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nama</label>
                                                <input type="text" class="form-control" value="{{ $val->userModel->name ?? '-' }}" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tujuan</label>
                                                <input type="text" name="tujuan" class="form-control" value="{{ $val->tujuan }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jenis Kendaraan</label>
                                                <input type="text" name="jenis_kendaraan" class="form-control" value="{{ $val->jenis_kendaraan }}" maxlength="255" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Waktu Keluar</label>
                                                        <input type="datetime-local" name="keluar" class="form-control" value="{{ $val->keluar ? Carbon\Carbon::make($val->keluar)->format('Y-m-d\TH:i') : '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Waktu Masuk</label>
                                                        <input type="datetime-local" name="masuk" class="form-control" value="{{ $val->masuk ? Carbon\Carbon::make($val->masuk)->format('Y-m-d\TH:i') : '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
@endforeach
@endsection
@push('script')
<script>
document.querySelectorAll('.permission-action').forEach(button => {
    const menu = button.nextElementSibling;
    button.addEventListener('shown.bs.dropdown', () => {
        document.body.appendChild(menu);
        const bounds = button.getBoundingClientRect();
        menu.style.setProperty('transform', 'none');
        menu.style.setProperty('inset', 'auto');
        menu.style.left = Math.max(8, bounds.right - menu.offsetWidth) + 'px';
        menu.style.top = Math.max(8, bounds.bottom + menu.offsetHeight + 4 > innerHeight ? bounds.top - menu.offsetHeight - 4 : bounds.bottom + 4) + 'px';
    });
    button.addEventListener('hidden.bs.dropdown', () => { button.parentElement.appendChild(menu); menu.removeAttribute('style'); });
});
</script>
@endpush
