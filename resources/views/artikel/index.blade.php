@extends('layouts.app_template')
@section('content')
<style>
    .article-page .btn { box-shadow: none !important; }
    .article-table { table-layout: fixed; min-width: 640px; }
    .article-table td { padding-top: 10px; padding-bottom: 10px; }
    .article-table .article-summary { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .article-menu { position: fixed !important; z-index: 1050; box-shadow: none; border: 1px solid #dce1e7; }
    .article-actions { width: 30px; height: 30px; padding: 0; border: 0; background: transparent; }
    .article-controls .input-icon { width: 240px; }
    @media (max-width: 575.98px) {
        .article-controls, .article-controls form { width: 100%; }
        .article-controls .input-icon { flex: 1; width: auto; min-width: 0; }
    }
</style>
<div class="article-page">
    <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-2 mb-3">
        <h2 class="page-title mb-0">Artikel</h2>
        <div class="article-controls d-flex flex-wrap align-items-center gap-2">
            <form action="{{ route('artikel') }}" method="GET" id="article-filter" class="d-flex gap-2">
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="10" cy="10" r="7"/><path d="m21 21-6-6"/></svg>
                    </span>
                    <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Cari artikel" aria-label="Cari artikel">
                </div>
                <select name="page[size]" class="form-select w-auto" onchange="this.form.requestSubmit()" aria-label="Jumlah artikel per halaman">
                    @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" {{ $page_size === $size ? 'selected' : '' }}>{{ $size }} data</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('artikel.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Tambah Artikel
            </a>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="table-responsive">
            <table class="table card-table table-vcenter article-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px">No.</th>
                        <th style="width: 30%">Judul</th>
                        <th>Deskripsi</th>
                        <th>Artikel</th>
                        <th style="width: 64px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($artikel as $key => $val)
                        @php
                            $content = json_decode($val->artikel ?? '', true);
                            $excerpt = html_entity_decode(strip_tags(is_string($content) ? $content : ($val->artikel ?? '')), ENT_QUOTES, 'UTF-8');
                        @endphp
                        <tr>
                            <td class="text-muted">{{ $artikel->firstItem() + $key }}</td>
                            <td class="article-summary fw-medium">{{ $val->judul ?: '-' }}</td>
                            <td class="article-summary text-muted">{{ $val->deskripsi ?: '-' }}</td>
                            <td class="article-summary text-muted">{{ $excerpt ?: '-' }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-icon article-actions" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Aksi artikel {{ $val->judul }}" title="Aksi artikel">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end article-menu">
                                        <a class="dropdown-item" href="{{ route('artikel.view', ['artikel_id' => $val->id]) }}" target="_blank" rel="noopener">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="2"/><path d="M2 12c5-9 15-9 20 0-5 9-15 9-20 0"/></svg>
                                            Preview
                                        </a>
                                        <a class="dropdown-item" href="{{ route('artikel.create', ['artikel_id' => $val->id]) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 3 5 5-12 12H4v-5L16 3m-2 2 5 5"/></svg>
                                            Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <form method="POST" action="{{ route('artikel.destroy', ['artikel' => $val->id]) }}" class="article-delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="search" value="{{ $search }}">
                                            <input type="hidden" name="page[size]" value="{{ $page_size }}">
                                            <button type="submit" class="dropdown-item text-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M9 6V4h6v2M5 6l1 14h12l1-14M10 10v6m4-6v6"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Artikel tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
            <p class="m-0 text-muted">
                Menampilkan <span>{{ $artikel->firstItem() ?? 0 }}</span>
                sampai <span>{{ $artikel->lastItem() ?? 0 }}</span>
                dari <span>{{ $artikel->total() }}</span> data
            </p>
            <ul class="pagination m-0 ms-auto">
                <li class="page-item {{ $artikel->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $controller->prevPagination($artikel->currentPage(), 'artikel', request()->all())->link }}" aria-label="Sebelumnya">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <polyline points="15 6 9 12 15 18"></polyline>
                        </svg>
                        Sebelumnya
                    </a>
                </li>
                @foreach($controller->counterPagination($artikel->lastPage(), $artikel->currentPage(), 'artikel', request()->all()) as $page)
                <li class="page-item {{ $page->is_active ? 'active' : '' }}">
                    <a class="page-link" href="{{ $page->link }}">{{ $page->lable }}</a>
                </li>
                @endforeach
                <li class="page-item {{ $artikel->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $controller->nextPagination($artikel->currentPage(), $artikel->lastPage(), 'artikel', request()->all())->link }}" aria-label="Berikutnya">
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
@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.article-actions').forEach(function (button) {
        const menu = button.nextElementSibling;
        // Place menus outside the scroll container so the last row stays accessible.
        button.addEventListener('shown.bs.dropdown', function () {
            document.body.appendChild(menu);
            const bounds = button.getBoundingClientRect();
            const top = bounds.bottom + 4;
            menu.style.setProperty('transform', 'none');
            menu.style.setProperty('inset', 'auto');
            menu.style.left = Math.max(8, bounds.right - menu.offsetWidth) + 'px';
            menu.style.top = Math.max(8, top + menu.offsetHeight > window.innerHeight ? bounds.top - menu.offsetHeight - 4 : top) + 'px';
        });
        button.addEventListener('hidden.bs.dropdown', function () {
            button.parentElement.appendChild(menu);
            menu.removeAttribute('style');
        });
    });
    document.querySelectorAll('.article-delete-form').forEach(function (form) {
        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            if (await confirmDelete('Hapus artikel ini?')) HTMLFormElement.prototype.submit.call(form);
        });
    });
});
</script>
@endpush
