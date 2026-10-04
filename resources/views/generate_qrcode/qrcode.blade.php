@extends('layouts.app_template')

@section('content')
@php
    $categoryLabels = [
        1 => 'Perizinan',
        2 => 'Gudang Senjata',
        3 => 'Logistik',
        4 => 'Ranpur',
        5 => 'Angkutan',
    ];
@endphp

<style>
    .qr-preview { width: 132px; height: 132px; }
    .qr-preview canvas { display: block; width: 132px; height: 132px; }
    .qr-code-value { word-break: break-all; }
    .qr-card-actions { display: flex; align-items: center; gap: 8px; padding-top: 12px; padding-bottom: 12px; background: #fff; }
    .qr-action { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; flex: 0 0 34px; padding: 0; border: 1px solid #dce1e7; border-radius: 6px; background: transparent; color: #667382; text-decoration: none; cursor: pointer; transition: color .15s ease, background .15s ease, border-color .15s ease; }
    .qr-action .icon { width: 18px; height: 18px; margin: 0; }
    .qr-action:hover { background: #f1f3f5; border-color: #b8c2ce; color: #182433; }
    .qr-action:focus-visible { outline: 2px solid #206bc4; outline-offset: 2px; }
    .qr-action-print { background: #f0f6fd; border-color: #b9cee8; color: #206bc4; }
    .qr-action-print:hover { background: #e1edfc; border-color: #8eb6e5; color: #1a5ba8; }
</style>

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
    <div>
        <h2 class="page-title mb-1">QR Code Operasional</h2>
        <div class="text-muted">Kelola, cetak, dan unduh kode akses untuk layanan Menarmed Mobile</div>
    </div>
    <div class="input-icon d-print-none">
        <span class="input-icon-addon">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7"/><line x1="21" y1="21" x2="15" y2="15"/></svg>
        </span>
        <input type="search" class="form-control" id="qr-search" placeholder="Cari nama atau kode" aria-label="Cari QR Code">
    </div>
</div>

<div class="row row-deck row-cards mb-3">
    <div class="col-sm-4">
        <div class="card"><div class="card-body py-3"><div class="text-muted small">Total QR Code</div><div class="h2 mb-0">{{ $qrcode->count() }}</div></div></div>
    </div>
    <div class="col-sm-4">
        <div class="card"><div class="card-body py-3"><div class="text-muted small">Kategori Layanan</div><div class="h2 text-blue mb-0">{{ $categoryCount }}</div></div></div>
    </div>
    <div class="col-sm-4">
        <div class="card"><div class="card-body py-3"><div class="text-muted small">Status</div><div class="h2 text-green mb-0">Aktif</div></div></div>
    </div>
</div>

<div class="row row-deck row-cards" id="qr-list">
    @forelse($qrcode as $item)
    @php($category = $categoryLabels[(int) $item->key] ?? 'Kategori ' . $item->key)
    <div class="col-md-6 col-xl-4 qr-item" data-search="{{ strtolower($item->title . ' ' . $item->code . ' ' . $category) }}">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="qr-preview flex-shrink-0" id="qr-{{ $item->id }}" data-code="{{ $item->code }}"></div>
                    <div class="flex-fill min-w-0">
                        <span class="badge bg-azure-lt mb-2">{{ $category }}</span>
                        <h3 class="card-title mb-2">{{ $item->title }}</h3>
                        <div class="text-muted small mb-1">Kode</div>
                        <code class="qr-code-value text-body">{{ $item->code }}</code>
                    </div>
                </div>
            </div>
            <div class="card-footer qr-card-actions d-print-none">
                <a href="{{ route('qrcode.print', $item) }}" class="qr-action qr-action-print" target="_blank" rel="noopener" title="Cetak QR Code" aria-label="Cetak QR Code" data-bs-toggle="tooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9v-4h12v4"/><rect x="6" y="13" width="12" height="8" rx="1"/><path d="M6 17h-2a2 2 0 0 1 -2 -2v-4a2 2 0 0 1 2 -2h16a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-2"/></svg>
                </a>
                <button type="button" class="qr-action download-qr" data-id="{{ $item->id }}" data-filename="{{ Illuminate\Support\Str::slug($item->title) }}" title="Unduh PNG" aria-label="Unduh PNG" data-bs-toggle="tooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3v12"/><path d="M8 11l4 4l4 -4"/><path d="M5 21h14"/></svg>
                </button>
                <button type="button" class="qr-action copy-code" data-code="{{ $item->code }}" title="Salin kode" aria-label="Salin kode" data-bs-toggle="tooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="8" y="8" width="12" height="12" rx="2"/><path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"/></svg>
                </button>
                <button type="button" class="qr-action edit-qr ms-auto" data-id="{{ $item->id }}" data-title="{{ $item->title }}" data-code="{{ $item->code }}" title="Edit QR Code" aria-label="Edit QR Code" data-bs-toggle="tooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3l-11 11l-4 1l1 -4z"/></svg>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12"><div class="card"><div class="card-body text-center text-muted py-5">Belum ada QR Code.</div></div></div>
    @endforelse
</div>

<div class="card d-none" id="qr-empty-search">
    <div class="card-body text-center text-muted py-5">QR Code yang dicari tidak ditemukan.</div>
</div>
@endsection

@section('modal')
<div class="modal modal-blur fade" id="modal-edit-qr" tabindex="-1" aria-labelledby="modal-edit-qr-title" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form id="qr-form">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal-edit-qr-title">Edit QR Code</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning py-2">
                        Mengganti kode akan membuat QR Code yang pernah dicetak sebelumnya tidak berlaku.
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="qr-title">Nama QR Code</label>
                        <input type="text" class="form-control" id="qr-title" name="title" maxlength="100" required>
                    </div>
                    <div>
                        <label class="form-label" for="qr-code">Kode</label>
                        <input type="text" class="form-control font-monospace" id="qr-code" name="code" maxlength="255" pattern="[A-Za-z0-9._-]+" required>
                        <div class="form-hint">Gunakan huruf, angka, titik, garis bawah, atau tanda hubung.</div>
                    </div>
                    <div class="invalid-feedback d-block mt-3" id="qr-error"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto" id="save-qr">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('assets/pus_dist/lib/jquery-qrcode/jquery-qrcode.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = new bootstrap.Modal(document.getElementById('modal-edit-qr'));
    const editForm = document.getElementById('qr-form');
    const updateUrl = @json(route('qrcode.update', ['qrcode' => '__ID__']));
    let editingId = null;

    document.querySelectorAll('.qr-preview').forEach(preview => {
        $(preview).qrcode({ render: 'canvas', size: 132, fill: '#182433', background: '#ffffff', text: preview.dataset.code });
    });

    document.getElementById('qr-search').addEventListener('input', function () {
        const keyword = this.value.trim().toLowerCase();
        let visible = 0;
        document.querySelectorAll('.qr-item').forEach(item => {
            const matches = item.dataset.search.includes(keyword);
            item.classList.toggle('d-none', !matches);
            if (matches) visible++;
        });
        document.getElementById('qr-empty-search').classList.toggle('d-none', visible !== 0);
    });

    document.querySelectorAll('.copy-code').forEach(button => {
        button.addEventListener('click', async function () {
            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(this.dataset.code);
                } else {
                    const input = document.createElement('textarea');
                    input.value = this.dataset.code;
                    input.style.position = 'fixed';
                    input.style.opacity = '0';
                    document.body.appendChild(input);
                    input.select();
                    document.execCommand('copy');
                    input.remove();
                }
                notif('Kode berhasil disalin.', 'success');
            } catch (error) {
                notif('Kode tidak dapat disalin oleh browser.', 'error');
            }
        });
    });

    document.querySelectorAll('.download-qr').forEach(button => {
        button.addEventListener('click', function () {
            const canvas = document.querySelector(`#qr-${this.dataset.id} canvas`);
            if (!canvas) return;
            const link = document.createElement('a');
            link.download = `qr-${this.dataset.filename}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    });

    document.querySelectorAll('.edit-qr').forEach(button => {
        button.addEventListener('click', function () {
            editingId = this.dataset.id;
            document.getElementById('qr-title').value = this.dataset.title;
            document.getElementById('qr-code').value = this.dataset.code;
            document.getElementById('qr-error').textContent = '';
            editModal.show();
        });
    });

    editForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        const saveButton = document.getElementById('save-qr');
        const errorElement = document.getElementById('qr-error');
        saveButton.disabled = true;
        errorElement.textContent = '';

        try {
            const response = await fetch(updateUrl.replace('__ID__', editingId), {
                method: 'PATCH',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                body: JSON.stringify(Object.fromEntries(new FormData(editForm).entries()))
            });
            const result = await response.json();
            if (!response.ok) {
                const validationMessage = result.errors ? Object.values(result.errors).flat()[0] : null;
                throw new Error(validationMessage || result.message || 'QR Code gagal diperbarui.');
            }
            editModal.hide();
            notif(result.message, 'success');
            window.setTimeout(() => window.location.reload(), 700);
        } catch (error) {
            errorElement.textContent = error.message;
        } finally {
            saveButton.disabled = false;
        }
    });
});
</script>
@endpush
