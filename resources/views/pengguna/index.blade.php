@extends('layouts.app_template')
@section('content')
<style>
    .users-page .btn, #modal-user .btn, #modal-edit-admin .btn, #modal-import .btn { box-shadow: none !important; }
    #reset-password-preview {
        color: #1e293b !important;
        -webkit-text-fill-color: #1e293b !important;
        background-color: #f1f5f9 !important;
        opacity: 1;
    }
    .admin-action { width: 30px; height: 30px; padding: 0; border: 0; background: transparent; }
    .admin-menu { position: fixed !important; z-index: 1050; box-shadow: none; border: 1px solid #dce1e7; }
    .users-page .table td { padding-top: 10px; padding-bottom: 10px; }
    .users-heading { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 16px; }
    .users-heading .page-title { flex-shrink: 0; }
    .users-actions { display: flex; align-items: center; justify-content: flex-end; flex-wrap: wrap; gap: 8px; }
    .users-actions .btn { min-height: 38px; white-space: nowrap; }
    .users-filters { display: grid; grid-template-columns: minmax(200px, 1fr) 170px 120px; gap: 12px; width: 100%; }
    .users-filters .form-control, .users-filters .form-select { min-height: 38px; }
    .users-filter-bar { padding: 16px; border-bottom: 1px solid #dce1e7; }
    @media (max-width: 767.98px) {
        .users-heading { align-items: flex-start; flex-direction: column; gap: 12px; }
        .users-actions { justify-content: flex-start; width: 100%; }
        .users-filters { grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 8px; }
        .users-filters .input-icon { grid-column: 1 / -1; }
    }
    @media (max-width: 575.98px) {
        .users-actions { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .users-actions #reset-selected-passwords { grid-column: 1 / -1; }
        .users-filter-bar { padding: 12px; }
    }
</style>
<div class="users-page">
    <div class="users-heading">
        <h2 class="page-title mb-0">{{ $role->role }}</h2>
        <div class="users-actions">

            @if($table->aksi)
                <button type="button" class="btn btn-outline-primary" id="reset-selected-passwords" data-bs-toggle="modal" data-bs-target="#modal-reset-passwords">Reset Password</button>
            @endif
            @if((int) $role->key === 1)
                <button type="button" class="btn btn-primary" onclick="openModalUser()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Admin
                </button>
            @else
                <a href="{{ route('pengguna.template') }}" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12m-4-4 4 4 4-4M5 17v4h14v-4"/></svg>
                    Format Excel
                </a>
                <button type="button" class="btn btn-primary" onclick="openModalImport()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V3m-4 4 4-4 4 4M5 17v4h14v-4"/></svg>
                    Import Excel
                </button>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="users-filter-bar">
            <form action="{{ route('pengguna') }}" method="GET" class="users-filters">
                <input type="hidden" name="key" value="{{ $role->key }}">
                <div class="input-icon">
                    <span class="input-icon-addon"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="10" cy="10" r="7"/><path d="m21 21-6-6"/></svg></span>
                    <input type="search" name="filter[name]" value="{{ $search_name }}" class="form-control" placeholder="Cari nama pengguna" aria-label="Cari nama pengguna">
                </div>
                <select name="sort" class="form-select" onchange="this.form.requestSubmit()" aria-label="Urutan pengguna">
                    @foreach(['name' => 'Nama A-Z', '-name' => 'Nama Z-A', 'email' => 'Username A-Z', '-email' => 'Username Z-A'] as $value => $label)
                        <option value="{{ $value }}" {{ $sort === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="page[size]" class="form-select" onchange="this.form.requestSubmit()" aria-label="Jumlah data per halaman">
                    @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" {{ $page_size === $size ? 'selected' : '' }}>{{ $size }} data</option>
                    @endforeach
                </select>
            </form>
        </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Nama</th>
                            <th>Username</th>
                            @if($table->kemampuan)
                            <th>Kemampuan</th>
                            @endif
                            @if($table->aksi)
                            <th style="width: 64px;" class="text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengguna as $key => $val)
                        <tr>
                            <td>{{ $pengguna->firstItem() + $key }}</td>
                            <td>{{ $val->name ?? '-' }}</td>
                            <td>{{ $val->email }}</td>
                            @if($table->kemampuan)
                            <td>@if(empty($val->kemampuanModel)) <span class="badge bg-red-lt">Belum ada data</span> @else <span class="badge bg-success-lt">Tersedia</span> @endif</td>
                            @endif
                            @if($table->aksi && (int) $role->key !== 1)
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-icon admin-action" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi personel" aria-label="Aksi personel {{ $val->name }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end admin-menu">
                                <a href="{{ route('pengguna.view', ['user_id' => $val->id]) }}" class="dropdown-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="12" cy="12" r="2"></circle>
                                        <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
                                    </svg>
                                    Lihat Profil
                                </a>
                                        <a href="{{ route('pengguna.view', ['user_id' => $val->id, 'kemampuan' => 1]) }}" class="dropdown-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                @if($val->kemampuanModel)
                                                    <path d="m16 3 5 5-12 12H4v-5L16 3m-2 2 5 5"/>
                                                @else
                                                    <path d="M12 5v14M5 12h14"/>
                                                @endif
                                            </svg>
                                            {{ $val->kemampuanModel ? 'Edit Kemampuan' : 'Tambah Kemampuan' }}
                                        </a>
                                        <button type="button" class="dropdown-item edit-admin" data-url="{{ route('pengguna.personel.update', $val->id) }}" data-name="{{ $val->name }}" data-email="{{ $val->email }}" data-kind="Personel">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 3 5 5-12 12H4v-5L16 3m-2 2 5 5"/></svg>
                                            Edit
                                        </button>
                                        <button type="button" class="dropdown-item reset-password" data-url="{{ route('pengguna.reset-password', $val->id) }}" data-name="{{ $val->name }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"/></svg>
                                            Reset Password
                                        </button>
                                        <div class="dropdown-divider"></div>
                                        <button type="button" class="dropdown-item text-danger delete-personnel" data-url="{{ route('pengguna.personel.destroy', $val->id) }}" data-name="{{ $val->name }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M9 6V4h6v2M5 6l1 14h12l1-14M10 10v6m4-6v6"/></svg>
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </td>
                            @endif
                            @if($table->aksi && (int) $role->key === 1)
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-icon admin-action" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Aksi admin {{ $val->name }}" title="Aksi admin">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end admin-menu">
                                        <button type="button" class="dropdown-item edit-admin" data-url="{{ route('pengguna.admin.update', $val->id) }}" data-name="{{ $val->name }}" data-email="{{ $val->email }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 3 5 5-12 12H4v-5L16 3m-2 2 5 5"/></svg>
                                            Edit
                                        </button>
                                        <button type="button" class="dropdown-item reset-password" data-url="{{ route('pengguna.reset-password', $val->id) }}" data-name="{{ $val->name }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"/></svg>
                                            Reset Password
                                        </button>
                                        <div class="dropdown-divider"></div>
                                        <button type="button" class="dropdown-item text-danger remove-admin" data-id="{{ $val->id }}" data-name="{{ $val->name }}" @if($val->id === auth()->id()) disabled @endif>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M9 6V4h6v2M5 6l1 14h12l1-14M10 10v6m4-6v6"/></svg>
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ 3 + (int) $table->kemampuan + (int) $table->aksi }}" class="text-center text-muted py-4">Data pengguna tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                <p class="m-0 text-muted">
                    Menampilkan <span>{{ $pengguna->firstItem() ?? 0 }}</span>
                    sampai <span>{{ $pengguna->lastItem() ?? 0 }}</span>
                    dari <span>{{ $pengguna->total() }}</span> data
                </p>
                <ul class="pagination m-0 ms-auto">
                    <li class="page-item {{ $pengguna->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $controller->prevPagination($pengguna->currentPage(), 'pengguna', request()->all())->link }}" aria-label="Sebelumnya">
                            <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <polyline points="15 6 9 12 15 18"></polyline>
                            </svg>
                            Sebelumnya
                        </a>
                    </li>
                    @foreach($controller->counterPagination($pengguna->lastPage(), $pengguna->currentPage(), 'pengguna', request()->all()) as $page)
                    <li class="page-item {{ $page->is_active ? 'active' : '' }}">
                        <a class="page-link" href="{{ $page->link }}">{{ $page->lable }}</a>
                    </li>
                    @endforeach
                    <li class="page-item {{ $pengguna->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $controller->nextPagination($pengguna->currentPage(), $pengguna->lastPage(), 'pengguna', request()->all())->link }}" aria-label="Berikutnya">
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
<div class="modal fade" id="modal-confirm-reset" tabindex="-1" aria-labelledby="confirm-reset-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2 px-3">
                <h3 class="modal-title" id="confirm-reset-title">Reset password?</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-3">
                <p id="confirm-reset-description" class="mb-2 text-break"></p>
                <label for="reset-password-preview" class="form-label small mb-1">Password setelah reset</label>
                <div class="input-group">
                    <input id="reset-password-preview" type="text" class="form-control bg-light" style="font-family: monospace; font-weight: 600" value="Password123!" readonly autocomplete="off" spellcheck="false">
                    <button type="button" class="btn btn-outline-secondary" id="copy-reset-password" aria-label="Salin password baru" title="Salin password">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="8" y="8" width="12" height="12" rx="2"/><path d="M16 8V4H4v12h4"/></svg>
                    </button>
                </div>
            </div>
            <div class="modal-footer p-3 gap-2">
                <button type="button" class="btn btn-outline-secondary flex-fill m-0" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary flex-fill m-0" id="confirm-reset-submit">Reset Password</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-reset-passwords" tabindex="-1" aria-labelledby="reset-passwords-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="reset-passwords-title">Reset Password {{ $role->role }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Pilih akun yang akan direset ke <strong>Password123!</strong>.</p>
                <input type="search" id="reset-user-search" class="form-control mb-3" placeholder="Cari nama atau NRP" aria-label="Cari akun untuk reset password">
                <div class="d-flex gap-2 mb-3">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="reset-pick-all">Pilih Semua</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="reset-clear-all">Batalkan Pilihan</button>
                </div>
                <div id="reset-user-list" class="d-flex flex-column gap-2" style="max-height: 320px; overflow-y: auto" aria-live="polite"></div>
                <p class="text-muted small mt-3 mb-0">Pilih Semua mencakup seluruh akun dalam daftar ini, termasuk halaman lainnya.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="reset-users-submit" disabled>Reset Password (0)</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-import" tabindex="-1" aria-labelledby="import-personnel-title" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="import-personnel-title">Import Personel</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-import" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="personnel-import-file" class="form-label required">File Excel</label>
                        <input id="personnel-import-file" type="file" class="form-control" name="file" accept=".xlsx, .xls" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" form="form-import" class="btn btn-primary ms-auto" id="import-personnel-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Import
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-user" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto">
                <div id="list-user">
                    <div class="border p-3 rounded bg-white"></div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Batal
                </a>
                <div class="ms-auto">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-edit-admin" tabindex="-1" aria-labelledby="edit-admin-title" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <form class="modal-content" id="edit-admin-form">
            <div class="modal-header">
                <h3 class="modal-title" id="edit-admin-title">Edit Admin</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label for="admin-name" class="form-label required">Nama</label><input id="admin-name" name="name" class="form-control" maxlength="255" required></div>
                <div class="mb-3"><label for="admin-email" class="form-label required">Username</label><input id="admin-email" name="email" class="form-control" maxlength="255" required></div>
                <div class="mb-3"><label for="admin-password" class="form-label">Password Baru</label><input type="password" id="admin-password" name="password" class="form-control" minlength="8" autocomplete="new-password"></div>
                <div><label for="admin-password-confirmation" class="form-label">Konfirmasi Password Baru</label><input type="password" id="admin-password-confirmation" name="password_confirmation" class="form-control" minlength="8" autocomplete="new-password"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary" id="save-admin-edit">Simpan</button></div>
        </form>
    </div>
</div>
@endsection
@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('edit-admin-form');
    const modal = new bootstrap.Modal(document.getElementById('modal-edit-admin'));
    let editUrl = null;
    const save = document.getElementById('save-admin-edit');
    const done = () => {
        sessionStorage.setItem('user-import-message', 'Data pengguna berhasil diperbarui.');
        location.reload();
    };
    const failure = response => {
        const errors = response.responseJSON?.errors;
        notif(errors ? Object.values(errors).flat().join(' ') : response.responseJSON?.message || 'Perubahan gagal disimpan.', 'danger');
    };
    document.querySelectorAll('.edit-admin').forEach(action => action.addEventListener('click', () => {
        form.reset();
        editUrl = action.dataset.url;
        document.getElementById('edit-admin-title').textContent = 'Edit ' + (action.dataset.kind || 'Admin');
        form.elements.name.value = action.dataset.name;
        form.elements.email.value = action.dataset.email;
        modal.show();
    }));
    form.addEventListener('submit', event => {
        event.preventDefault();
        if (save.disabled || !form.reportValidity()) return;
        save.disabled = true;
        $.ajax({ url: editUrl, type: 'PATCH', data: $(form).serialize(), headers: { 'X-CSRF-TOKEN': token }, dataType: 'json' })
            .done(done).fail(failure).always(() => { save.disabled = false; });
    });
    document.getElementById('copy-reset-password').addEventListener('click', async () => {
        const preview = document.getElementById('reset-password-preview');
        try {
            if (!navigator.clipboard) throw new Error('Clipboard unavailable');
            await navigator.clipboard.writeText(preview.value);
            notif('Password berhasil disalin.', 'success');
        } catch (error) {
            preview.focus();
            preview.select();
            notif('Password dipilih. Tekan Ctrl+C atau Cmd+C untuk menyalin.', 'info');
        }
    });
    let confirmationPending = false;
    let restoringResetSelection = false;
    const confirmPasswordReset = async description => {
        if (confirmationPending) return false;
        confirmationPending = true;
        const selectionElement = document.getElementById('modal-reset-passwords');
        const restoreSelection = selectionElement.classList.contains('show');
        const waitForModal = (element, event, action) => new Promise(resolve => {
            element.addEventListener(event, resolve, { once: true });
            action();
        });
        if (restoreSelection) {
            await waitForModal(selectionElement, 'hidden.bs.modal', () => bootstrap.Modal.getInstance(selectionElement).hide());
        }
        const element = document.getElementById('modal-confirm-reset');
        const modal = (bootstrap.Modal.getInstance(element) || new bootstrap.Modal(element));
        const approve = document.getElementById('confirm-reset-submit');
        document.getElementById('confirm-reset-description').textContent = description;
        const confirmed = await new Promise(resolve => {
            let accepted = false;
            const accept = () => { accepted = true; approve.disabled = true; modal.hide(); };
            approve.addEventListener('click', accept);
            element.addEventListener('hidden.bs.modal', () => {
                approve.removeEventListener('click', accept);
                approve.disabled = false;
                resolve(accepted);
            }, { once: true });
            modal.show();
        });
        if (restoreSelection) {
            restoringResetSelection = true;
            await waitForModal(selectionElement, 'shown.bs.modal', () => bootstrap.Modal.getInstance(selectionElement).show());
        }
        confirmationPending = false;
        return confirmed;
    };
    const resetElement = document.getElementById('modal-reset-passwords');
    const resetList = document.getElementById('reset-user-list');
    const resetSearch = document.getElementById('reset-user-search');
    const resetSubmit = document.getElementById('reset-users-submit');
    const pickAll = document.getElementById('reset-pick-all');
    const clearAll = document.getElementById('reset-clear-all');
    let resetAccounts = [];
    const selectedIds = new Set();
    let resetting = false;
    let loadingAccounts = false;
    const renderAccounts = () => {
        resetSubmit.textContent = 'Reset Password (' + selectedIds.size + ')';
        resetSubmit.disabled = resetting || loadingAccounts || !selectedIds.size;
        pickAll.disabled = clearAll.disabled = resetting || loadingAccounts || !resetAccounts.length;
        resetSearch.disabled = resetting || loadingAccounts;
        if (loadingAccounts) { resetList.textContent = 'Memuat akun...'; return; }
        resetList.replaceChildren();
        const query = resetSearch.value.trim().toLowerCase();
        resetAccounts.filter(account => (account.name + ' ' + account.email).toLowerCase().includes(query)).forEach(account => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn text-start justify-content-between ' + (selectedIds.has(account.id) ? 'btn-primary' : 'btn-outline-secondary');
            button.setAttribute('aria-pressed', String(selectedIds.has(account.id)));
            button.disabled = resetting;
            const label = document.createElement('span');
            label.textContent = account.name + ' · ' + account.email;
            const status = document.createElement('span');
            status.textContent = selectedIds.has(account.id) ? '✓' : '+';
            button.append(label, status);
            button.addEventListener('click', () => {
                if (selectedIds.has(account.id)) selectedIds.delete(account.id); else selectedIds.add(account.id);
                renderAccounts();
            });
            resetList.appendChild(button);
        });
        if (!resetList.children.length) resetList.textContent = 'Tidak ada akun yang ditemukan.';
    };
    resetElement.addEventListener('show.bs.modal', () => {
        if (restoringResetSelection) { restoringResetSelection = false; return; }
        selectedIds.clear();
        resetAccounts = [];
        resetSearch.value = '';
        loadingAccounts = true;
        renderAccounts();
        $.ajax({ url: @json(route('pengguna.reset-password-options')), data: { role: @json((int) $role->key) }, dataType: 'json' })
            .done(response => { resetAccounts = response.data; })
            .fail(failure).always(() => { loadingAccounts = false; renderAccounts(); });
    });
    resetElement.addEventListener('hide.bs.modal', event => { if (resetting) event.preventDefault(); });
    resetSearch.addEventListener('input', renderAccounts);
    pickAll.addEventListener('click', () => { resetAccounts.forEach(account => selectedIds.add(account.id)); renderAccounts(); });
    clearAll.addEventListener('click', () => { selectedIds.clear(); renderAccounts(); });
    resetSubmit.addEventListener('click', async () => {
        const ids = Array.from(selectedIds);
        if (resetting || !ids.length || !await confirmPasswordReset('Reset password untuk ' + ids.length + ' akun terpilih?')) return;
        resetting = true;
        renderAccounts();
        $.ajax({ url: @json(route('pengguna.reset-passwords')), type: 'POST', contentType: 'application/json', data: JSON.stringify({ ids }), headers: { 'X-CSRF-TOKEN': token }, dataType: 'json' })
            .done(response => {
                resetting = false;
                bootstrap.Modal.getInstance(resetElement).hide();
                notif(response.message, 'success');
            }).fail(failure).always(() => { resetting = false; renderAccounts(); });
    });
    document.querySelectorAll('.reset-password').forEach(action => action.addEventListener('click', async () => {
        if (action.disabled || !await confirmPasswordReset('Reset password akun "' + action.dataset.name + '"?')) return;
        action.disabled = true;
        $.ajax({ url: action.dataset.url, type: 'POST', headers: { 'X-CSRF-TOKEN': token }, dataType: 'json' })
            .done(response => { notif(response.message, 'success'); })
            .fail(failure).always(() => { action.disabled = false; });
    }));
    document.querySelectorAll('.delete-personnel').forEach(action => action.addEventListener('click', async () => {
        if (action.disabled || !await confirmDelete('Hapus personel "' + action.dataset.name + '" beserta seluruh riwayat operasional dan data kemampuannya? Penghapusan ini permanen.')) return;
        action.disabled = true;
        $.ajax({ url: action.dataset.url, type: 'DELETE', headers: { 'X-CSRF-TOKEN': token }, dataType: 'json' })
            .done(() => {
                sessionStorage.setItem('user-import-message', 'Personel beserta seluruh riwayatnya berhasil dihapus.');
                location.reload();
            }).fail(failure).always(() => { action.disabled = false; });
    }));
    document.querySelectorAll('.remove-admin').forEach(action => action.addEventListener('click', async () => {
        if (action.disabled || !await confirmDelete('Hapus akses admin "' + action.dataset.name + '"? Pengguna akan kembali menjadi Personel.')) return;
        action.disabled = true;
        $.ajax({ url: @json(route('pengguna.update_role')), type: 'POST', data: { id: action.dataset.id, role: 3 }, headers: { 'X-CSRF-TOKEN': token }, dataType: 'json' })
            .done(done).fail(failure).always(() => { action.disabled = false; });
    }));
    document.querySelectorAll('.admin-action').forEach(action => {
        const menu = action.nextElementSibling;
        action.addEventListener('shown.bs.dropdown', () => {
            document.body.appendChild(menu);
            const bounds = action.getBoundingClientRect();
            menu.style.setProperty('transform', 'none');
            menu.style.setProperty('inset', 'auto');
            menu.style.left = Math.max(8, bounds.right - menu.offsetWidth) + 'px';
            menu.style.top = Math.max(8, bounds.bottom + menu.offsetHeight + 4 > innerHeight ? bounds.top - menu.offsetHeight - 4 : bounds.bottom + 4) + 'px';
        });
        action.addEventListener('hidden.bs.dropdown', () => {
            action.parentElement.appendChild(menu);
            menu.removeAttribute('style');
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const message = sessionStorage.getItem('user-import-message');
    if (message) {
        sessionStorage.removeItem('user-import-message');
        notif(message, 'success');
    }
});
</script>
<script>
    // Import
    const openModalImport = () => {
        document.getElementById('form-import').reset();
        const element = document.getElementById('modal-import');
        (bootstrap.Modal.getInstance(element) || new bootstrap.Modal(element)).show();
    }

    const submitImport = () => {
        const form = document.getElementById('form-import');
        const button = document.getElementById('import-personnel-submit');
        if (button.disabled || !form.reportValidity()) return;
        const original = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengimpor...';
        let formData = new FormData(form);

        fetch("{{ route('pengguna.import') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                sessionStorage.setItem('user-import-message', data.message || 'Data berhasil diimpor.');
                bootstrap.Modal.getInstance(document.getElementById('modal-import')).hide();
                location.reload();
            } else {
                notif(data.errors ? Object.values(data.errors).flat().join(' ') : data.message || 'Terjadi kesalahan', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            notif('Terjadi kesalahan sistem', 'danger');
        }).finally(() => {
            button.disabled = false;
            button.innerHTML = original;
        });
    }
    document.getElementById('form-import').addEventListener('submit', event => {
        event.preventDefault();
        submitImport();
    });
</script>
@endpush
@push('script')
<script>
    // Modal delete
    let _modal_user = '#modal-user';
    let _modal_list_user = '#modal-user #list-user';

    const openModalUser = () => {
        $(_modal_user).modal("show");
        getUser();
    }

    // Open Modal
    const openModal = (modal) => {
        $(modal).modal("show");
    }

    const closeModal = () => {
        $(_modal_user).modal("hide");
    }

    const getUser = () => {
        requestServer({
            url: url + '/pengguna/json?key=3',
            type: 'get',
            onLoader: true,
            onSuccess: function(value) {
                close_swal(false);
                $(_modal_list_user).empty();
                $.each(value.data.pengguna, (index, item) => {
                    var element = `<div class="border py-2 px-3 rounded bg-white mb-1" style="display: flex; justify-content: space-between">
                        <div>
                            ${item.name}
                            <div class="">${item.email}</div>
                        </div>
                        <div>
                            <div class="btn btn-primary px-2 py-2" onclick="saveAdmin(${item.id}, 1)">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round" ><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            </div>
                        </div>
                    </div>`;
                    $(_modal_list_user).append(element);
                });
            },
        });
    }

    const saveAdmin = (id, role) => {
        requestServer({
            url: url + '/pengguna/update-role',
            data: {
                id: id,
                role: role,
            },
            onLoader: true,
            onSuccess: function(value) {
                close_swal(true, 'Berhasil Memperbarui Data Admin', 'success');
                closeModal();
                reloadPage();
            },
        });
    }

</script>
@endpush
