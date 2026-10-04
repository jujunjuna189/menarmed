@extends('layouts.app_template')
@section('content')
<style>
    .suggestion-page .btn { box-shadow: none !important; }
    .suggestion-page .table td { padding-top: 10px; padding-bottom: 10px; }
    .suggestion-message { display: flex; align-items: center; gap: 8px; }
    .suggestion-message-text { display: block; max-width: 420px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .suggestion-view { width: 28px; height: 28px; padding: 0; flex-shrink: 0; border: 0; background: transparent; }
    .suggestion-detail { white-space: pre-wrap; overflow-wrap: anywhere; }
    @media (max-width: 575px) { .suggestion-message-text { max-width: 180px; } }
</style>
<div class="suggestion-page">
    <div class="d-flex flex-column gap-3 mb-3">
        <h2 class="page-title mb-0">Masukan &amp; Saran</h2>
        <form action="{{ route('saran') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2">
            <div class="input-icon">
                <span class="input-icon-addon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="10" cy="10" r="7"/><path d="m21 21-6-6"/></svg></span>
                <input type="search" name="search" class="form-control" placeholder="Cari pengirim atau pesan" value="{{ $search }}" aria-label="Cari pengirim atau pesan">
            </div>
            <select name="page[size]" class="form-select w-auto" onchange="this.form.requestSubmit()" aria-label="Jumlah data">
                @foreach([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ $page_size === $size ? 'selected' : '' }}>{{ $size }} data</option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="card">
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Pengirim</th>
                            <th>Saran dan Masukan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($saran as $key => $val)
                        <tr>
                            <td>{{ $saran->firstItem() + $key }}</td>
                            <td>{{ $val->from_display ?? '-' }}</td>
                            <td>
                                <div class="suggestion-message">
                                    <span class="suggestion-message-text">{{ $val->message }}</span>
                                    <button type="button" class="btn btn-icon suggestion-view" data-bs-toggle="modal" data-bs-target="#suggestion-{{ $val->id }}" title="Lihat pesan lengkap" aria-label="Lihat pesan lengkap">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">{{ $search !== '' ? 'Masukan dan saran tidak ditemukan.' : 'Belum ada masukan dan saran.' }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                <p class="m-0 text-muted">
                    Menampilkan <span>{{ $saran->firstItem() ?? 0 }}</span>
                    sampai <span>{{ $saran->lastItem() ?? 0 }}</span>
                    dari <span>{{ $saran->total() }}</span> data
                </p>
                <ul class="pagination m-0 ms-auto">
                    <li class="page-item {{ $saran->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $controller->prevPagination($saran->currentPage(), 'saran', request()->all())->link }}" aria-label="Sebelumnya">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <polyline points="15 6 9 12 15 18"></polyline>
                            </svg>
                            Sebelumnya
                        </a>
                    </li>
                    @foreach($controller->counterPagination($saran->lastPage(), $saran->currentPage(), 'saran', request()->all()) as $page)
                    <li class="page-item {{ $page->is_active ? 'active' : '' }}">
                        <a class="page-link" href="{{ $page->link }}">{{ $page->lable }}</a>
                    </li>
                    @endforeach
                    <li class="page-item {{ $saran->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $controller->nextPagination($saran->currentPage(), $saran->lastPage(), 'saran', request()->all())->link }}" aria-label="Berikutnya">
                            Berikutnya
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
@foreach($saran as $val)
<div class="modal fade" id="suggestion-{{ $val->id }}" tabindex="-1" aria-labelledby="suggestion-title-{{ $val->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suggestion-title-{{ $val->id }}">Masukan &amp; Saran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="text-muted small mb-1">Pengirim</div>
                <div class="mb-3 text-break">{{ $val->from_display ?? '-' }}</div>
                <div class="suggestion-detail">{{ $val->message }}</div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
