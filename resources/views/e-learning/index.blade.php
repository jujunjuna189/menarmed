@extends('layouts.app_template')
@section('content')
<style>
    .learning-page .btn, #modal-e-learning .btn { box-shadow: none !important; }
    .learning-table { table-layout: fixed; min-width: 600px; }
    .learning-table td { padding-top: 10px; padding-bottom: 10px; }
    .learning-summary { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .learning-actions { width: 30px; height: 30px; padding: 0; border: 0; background: transparent; }
    .learning-menu { position: fixed !important; z-index: 1050; border: 1px solid #dce1e7; box-shadow: none; }
    .learning-controls .input-icon { width: 240px; }
    @media (max-width: 575.98px) {
        .learning-controls, .learning-controls form { width: 100%; }
        .learning-controls .input-icon { flex: 1; min-width: 0; width: auto; }
    }
</style>
<div class="learning-page">
    <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-2 mb-3">
        <h2 class="page-title mb-0">E-Learning</h2>
        <div class="learning-controls d-flex flex-wrap align-items-center gap-2">
            <form action="{{ route('e-learning') }}" method="GET" class="d-flex gap-2">
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="10" cy="10" r="7"/><path d="m21 21-6-6"/></svg>
                    </span>
                    <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Cari materi" aria-label="Cari e-learning">
                </div>
                <select name="page[size]" class="form-select w-auto" onchange="this.form.requestSubmit()" aria-label="Jumlah data per halaman">
                    @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" {{ $page_size === $size ? 'selected' : '' }}>{{ $size }} data</option>
                    @endforeach
                </select>
            </form>
            <button type="button" class="btn btn-primary" id="add-learning">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Tambah E-Learning
            </button>
        </div>
    </div>
    <div class="card">
            <div class="table-responsive">
                <table class="table card-table table-vcenter learning-table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 60px">No.</th>
                            <th style="width: 30%">Judul</th>
                            <th>Deskripsi</th>
                            <th>URL Materi</th>
                            <th style="width: 64px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($learning as $key => $val)
                        <tr>
                            <td>{{ $learning->firstItem() + $key }}</td>
                            <td class="learning-summary fw-medium">{{ $val->judul ?: '-' }}</td>
                            <td class="learning-summary text-muted">{{ $val->deskripsi ?: '-' }}</td>
                            <td class="learning-summary text-muted">{{ $val->path }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-icon learning-actions" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi materi" aria-label="Aksi materi {{ $val->judul }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end learning-menu">
                                        @if(in_array(strtolower(parse_url($val->path ?? '', PHP_URL_SCHEME) ?? ''), ['http', 'https'], true))
                                            <a href="{{ $val->path }}" target="_blank" rel="noopener noreferrer" class="dropdown-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h6v6m0-6L10 14M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/></svg>
                                                Buka Materi
                                            </a>
                                        @else
                                            <span class="dropdown-item disabled" aria-disabled="true">URL tidak valid</span>
                                        @endif
                                        <button type="button" class="dropdown-item edit-learning" data-url="{{ route('e-learning.update', $val->id) }}" data-title="{{ $val->judul }}" data-description="{{ $val->deskripsi }}" data-path="{{ $val->path }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 3 5 5-12 12H4v-5L16 3m-2 2 5 5"/></svg>
                                            Edit
                                        </button>
                                        <div class="dropdown-divider"></div>
                                        <button type="button" class="dropdown-item text-danger delete-learning" data-url="{{ route('e-learning.destroy', $val->id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M9 6V4h6v2M5 6l1 14h12l1-14M10 10v6m4-6v6"/></svg>
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Data E-Learning tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                <p class="m-0 text-muted">
                    Menampilkan <span>{{ $learning->firstItem() ?? 0 }}</span>
                    sampai <span>{{ $learning->lastItem() ?? 0 }}</span>
                    dari <span>{{ $learning->total() }}</span> data
                </p>
                <ul class="pagination m-0 ms-auto">
                    <li class="page-item {{ $learning->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $controller->prevPagination($learning->currentPage(), 'e-learning', request()->all())->link }}" aria-label="Sebelumnya">
                            <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <polyline points="15 6 9 12 15 18"></polyline>
                            </svg>
                            Sebelumnya
                        </a>
                    </li>
                    @foreach($controller->counterPagination($learning->lastPage(), $learning->currentPage(), 'e-learning', request()->all()) as $page)
                    <li class="page-item {{ $page->is_active ? 'active' : '' }}">
                        <a class="page-link" href="{{ $page->link }}">{{ $page->lable }}</a>
                    </li>
                    @endforeach
                    <li class="page-item {{ $learning->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $controller->nextPagination($learning->currentPage(), $learning->lastPage(), 'e-learning', request()->all())->link }}" aria-label="Berikutnya">
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
<div class="modal fade" id="modal-e-learning" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <form class="modal-content" id="learning-form">
            <div class="modal-header">
                <h5 class="modal-title">Tambah E-Learning</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="learning-title" class="form-label required">Judul</label>
                    <input type="text" class="form-control" id="learning-title" name="judul" maxlength="255" placeholder="Judul materi" required>
                </div>
                <div class="mb-3">
                    <label for="learning-description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" rows="3" id="learning-description" name="deskripsi" placeholder="Ringkasan materi"></textarea>
                </div>
                <div>
                    <label for="learning-url" class="form-label required">URL Materi</label>
                    <input type="url" class="form-control" id="learning-url" name="path" placeholder="https://docs.google.com/presentation" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary ms-auto" id="save-learning">
                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('learning-form');
    const modal = new bootstrap.Modal(document.getElementById('modal-e-learning'));
    const button = document.getElementById('save-learning');
    const original = button.innerHTML;
    let editUrl = null;
    const modalTitle = document.querySelector('#modal-e-learning .modal-title');
    document.getElementById('add-learning').addEventListener('click', function () {
        editUrl = null;
        modalTitle.textContent = 'Tambah E-Learning';
        form.reset();
        modal.show();
    });
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (button.disabled || !form.reportValidity()) return;
        const path = form.elements.path.value.trim();
        if (!/^https?:\/\//i.test(path)) {
            notif('URL materi harus menggunakan http atau https.', 'error');
            return;
        }
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
        $.ajax({
            url: editUrl || @json(url('/api/e-learning/store')),
            type: editUrl ? 'PATCH' : 'POST',
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': token },
            data: { judul: form.elements.judul.value.trim(), deskripsi: form.elements.deskripsi.value.trim(), path: path }
        }).done(function (result) {
            if (result.status !== 'Success') {
                notif('Materi gagal disimpan.', 'error');
                return;
            }
            modal.hide();
            sessionStorage.setItem('learning-saved', editUrl ? 'E-Learning berhasil diperbarui.' : 'E-Learning berhasil ditambahkan.');
            window.location.reload();
        }).fail(function (response) {
            const errors = response.responseJSON?.errors;
            notif(errors ? Object.values(errors).flat().join(' ') : 'Materi gagal disimpan. Silakan coba lagi.', 'error');
        }).always(function () {
            button.disabled = false;
            button.innerHTML = original;
        });
    });
    document.querySelectorAll('.edit-learning').forEach(function (action) {
        action.addEventListener('click', function () {
            editUrl = action.dataset.url;
            form.elements.judul.value = action.dataset.title;
            form.elements.deskripsi.value = action.dataset.description;
            form.elements.path.value = action.dataset.path;
            modalTitle.textContent = 'Edit E-Learning';
            modal.show();
        });
    });
    document.querySelectorAll('.delete-learning').forEach(function (action) {
        action.addEventListener('click', async function () {
            if (action.disabled || !await confirmDelete('Hapus materi e-learning ini?')) return;
            action.disabled = true;
            $.ajax({ url: action.dataset.url, type: 'DELETE', dataType: 'json', headers: { 'X-CSRF-TOKEN': token } })
                .done(function () {
                    sessionStorage.setItem('learning-saved', 'E-Learning berhasil dihapus.');
                    window.location.reload();
                }).fail(function () {
                    notif('Materi gagal dihapus. Silakan coba lagi.', 'error');
                    action.disabled = false;
                });
        });
    });
    document.querySelectorAll('.learning-actions').forEach(function (action) {
        const menu = action.nextElementSibling;
        action.addEventListener('shown.bs.dropdown', function () {
            document.body.appendChild(menu);
            const bounds = action.getBoundingClientRect();
            menu.style.setProperty('transform', 'none');
            menu.style.setProperty('inset', 'auto');
            menu.style.left = Math.max(8, bounds.right - menu.offsetWidth) + 'px';
            menu.style.top = Math.max(8, bounds.bottom + menu.offsetHeight + 4 > window.innerHeight ? bounds.top - menu.offsetHeight - 4 : bounds.bottom + 4) + 'px';
        });
        action.addEventListener('hidden.bs.dropdown', function () {
            action.parentElement.appendChild(menu);
            menu.removeAttribute('style');
        });
    });
    const savedMessage = sessionStorage.getItem('learning-saved');
    if (savedMessage) {
        sessionStorage.removeItem('learning-saved');
        notif(savedMessage, 'success');
    }
});
</script>
@endpush
