@extends('layouts.app_template')

@section('content')
<style>
    #monitor-page:fullscreen {
        background: #f4f6fa;
        padding: 1rem;
        overflow: auto;
        box-sizing: border-box;
    }

    #monitor-page::backdrop {
        background: #f4f6fa;
    }
</style>

<div id="monitor-page">
    <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-3 mb-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="status-dot status-dot-animated bg-red"></span>
                <h2 class="page-title mb-0">Monitor Absensi</h2>
            </div>
            <div class="text-muted mt-1">Kehadiran personel secara live pada {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2 d-print-none">
            <form action="{{ route('absensi') }}" method="get" class="d-flex flex-wrap gap-2" id="monitor-filter">
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7"/><line x1="21" y1="21" x2="15" y2="15"/></svg>
                    </span>
                    <input type="search" name="filter[name]" class="form-control" value="{{ request('filter.name') }}" placeholder="Cari personel" aria-label="Cari personel">
                </div>
                <select name="sort" class="form-select w-auto" aria-label="Urutan personel" onchange="this.form.requestSubmit()">
                    @foreach (['name' => 'Nama A-Z', '-name' => 'Nama Z-A', 'pangkat' => 'Pangkat A-Z', '-pangkat' => 'Pangkat Z-A'] as $value => $label)
                        <option value="{{ $value }}" {{ request('sort', 'name') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="page[size]" value="{{ $user->perPage() }}">
            </form>
            <a href="{{ route('absensi.template') }}" class="btn btn-outline-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3v12"/><path d="M8 11l4 4l4 -4"/><path d="M5 21h14"/></svg>
                Format
            </a>
            <button type="button" class="btn btn-primary" id="open-import">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                Import
            </button>
            <a href="{{ route('track_maps') }}" class="btn btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21 3 6"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                Peta
            </a>
            <button type="button" class="btn btn-icon btn-outline-secondary" id="toggle-fullscreen" title="Tampilkan layar penuh" aria-label="Tampilkan layar penuh">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 8v-2a2 2 0 0 1 2 -2h2"/><path d="M4 16v2a2 2 0 0 0 2 2h2"/><path d="M16 4h2a2 2 0 0 1 2 2v2"/><path d="M16 20h2a2 2 0 0 0 2 -2v-2"/></svg>
            </button>
        </div>
    </div>

    <x-monitor.live-absensi :user="$user" :user-ids="$userIds" :controller="$controller" />
</div>

<div class="modal modal-blur fade" id="modal-import" tabindex="-1" aria-labelledby="modal-import-title" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form id="form-import" action="{{ route('absensi.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="modal-import-title">Import Absensi</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label" for="attendance-file">File Excel</label>
                    <input type="file" class="form-control" id="attendance-file" name="file" accept=".xlsx,.xls" required>
                    <div class="form-hint">Gunakan format Excel yang tersedia melalui tombol Format.</div>
                    <div class="invalid-feedback d-block" id="import-error"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto" id="submit-import">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const monitorPage = document.getElementById('monitor-page');
    const importModalElement = document.getElementById('modal-import');
    const importModal = new bootstrap.Modal(importModalElement);
    const importForm = document.getElementById('form-import');
    const submitButton = document.getElementById('submit-import');
    const errorElement = document.getElementById('import-error');

    document.getElementById('open-import').addEventListener('click', () => importModal.show());
    document.getElementById('toggle-fullscreen').addEventListener('click', async function () {
        if (!document.fullscreenElement) {
            await monitorPage.requestFullscreen();
        } else {
            await document.exitFullscreen();
        }
    });

    importForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        errorElement.textContent = '';
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Mengimpor...';

        try {
            const response = await fetch(importForm.action, {
                method: 'POST',
                body: new FormData(importForm),
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const result = await response.json();
            if (!response.ok || result.status !== 'success') {
                const validationMessage = result.errors ? Object.values(result.errors).flat()[0] : null;
                throw new Error(validationMessage || result.message || 'Import data gagal.');
            }

            importModal.hide();
            notif(result.message, 'success');
            window.setTimeout(() => window.location.reload(), 800);
        } catch (error) {
            errorElement.textContent = error.message || 'Terjadi kesalahan saat import data.';
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5v14"/><path d="M5 12h14"/></svg>Import Data';
        }
    });
});
</script>
@endpush
