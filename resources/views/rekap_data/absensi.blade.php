@extends('layouts.app_template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Rekap Data Absensi</h3>
            </div>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card-body border-bottom py-3">
                <form action="{{ route('report.absensi') }}" method="GET" class="d-flex w-100" id="filterForm">
                    <div class="text-muted">
                        Show
                        <div class="mx-2 d-inline-block">
                            <select name="page[size]" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="10" {{ $page_size == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $page_size == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $page_size == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $page_size == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        entries
                    </div>
                    <div class="ms-3 d-flex align-items-center">
                        <label class="form-label mb-0 me-2">Dari:</label>
                        <input type="date" name="start_date" class="form-control form-control-sm me-2" value="{{ $start_date }}" onchange="this.form.submit()">
                        <label class="form-label mb-0 me-2">Sampai:</label>
                        <input type="date" name="end_date" class="form-control form-control-sm me-2" value="{{ $end_date }}" onchange="this.form.submit()">
                    </div>
                    <div class="ms-auto text-muted d-flex">
                        <div class="me-2 d-inline-block">
                            <input type="text" name="filter[name]" class="form-control form-control-sm" placeholder="Search Nama..." value="{{ $search_name }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm ms-1">Cari</button>
                        <a href="{{ route('report.absensi.export', request()->all()) }}" class="btn btn-success btn-sm ms-2">
                            Export Excel
                        </a>
                        <a href="{{ route('report.absensi.pdf', request()->all()) }}" class="btn btn-danger btn-sm ms-2">
                            Export PDF
                        </a>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"></th>
                            <th class="w-1">No.
                                <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-dark icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <polyline points="6 15 12 9 18 15"></polyline>
                                </svg>
                            </th>
                            <th>Nama</th>
                            <th>Keterangan</th>
                            <th>Tanggal dan Waktu</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report as $key => $val)
                        <tr>
                            <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"></td>
                            <td>{{ $report->firstItem() + $key }}</td>
                            <td>{{ $val->userModel->name ?? '-' }}</td>
                            <td>{{ $val->ket }}</td>
                            <td>{{ Carbon\Carbon::make($val->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $val->id }}">
                                    Edit
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal modal-blur fade" id="modal-edit-{{ $val->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('report.absensi.update', $val->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Data Absensi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nama</label>
                                                <input type="text" class="form-control" value="{{ $val->userModel->name ?? '-' }}" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Keterangan</label>
                                                <textarea name="ket" class="form-control" rows="3" required>{{ $val->ket }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal dan Waktu</label>
                                                <input type="datetime-local" name="created_at" class="form-control" value="{{ Carbon\Carbon::make($val->created_at)->format('Y-m-d\TH:i') }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-muted">Showing <span>{{$report->firstItem() ?? 0}}</span> to <span>{{$report->lastItem() ?? 0}}</span> of <span>{{$report->total()}}</span> entries</p>
                <ul class="pagination m-0 ms-auto">
                    <li class="page-item">
                        <a class="page-link" href="{{$controller->prevPagination($report->currentPage(), 'report.absensi', request()->all())->link}}">
                            <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <polyline points="15 6 9 12 15 18"></polyline>
                            </svg>
                            prev
                        </a>
                    </li>
                    @foreach($controller->counterPagination($report->lastPage(), $report->currentPage(), 'report.absensi', request()->all()) as $val)
                    <li class="page-item {{$val->is_active ? 'active' : ''}}"><a class="page-link" href="{{$val->link}}">{{$val->lable}}</a></li>
                    @endforeach
                    <li class="page-item">
                        <a class="page-link" href="{{$controller->nextPagination($report->currentPage(), $report->lastPage(), 'report.absensi', request()->all())->link}}">
                            next
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
</div>
@endsection