@extends('layouts.app_template')
@section('content')
@php
    $skills = [
        'lari' => ['Lari', 'Meter'], 'renang' => ['Renang', 'Detik'],
        'jatri' => ['Menembak Senjata Ringan', 'Poin'], 'jatrat' => ['Menembak Senjata Berat', 'Poin'],
        'pistol' => ['Menembak Pistol', 'Poin'], 'push_up' => ['Push Up', 'x'],
        'sit_up' => ['Sit Up', 'x'], 'pull_up' => ['Pull Up', 'x'], 'shutle_run' => ['Shuttle Run', 'Detik'],
    ];
    $details = ['pangkat' => 'Pangkat', 'korp' => 'Korp', 'satuan' => 'Satuan', 'jabatan' => 'Jabatan',
        'tempat_lahir' => 'Tempat Lahir', 'tgl_lahir' => 'Tanggal Lahir', 'agama' => 'Agama',
        'gol_darah' => 'Golongan Darah', 'sumber_pa' => 'Sumber PA', 'senjata' => 'Senjata'];
@endphp
<style>
    .personnel-profile .btn, #modal-kemampuan .btn { box-shadow: none !important; }
    .personnel-profile .card { border-radius: 6px; box-shadow: none; }
    .personnel-identity { display: flex; align-items: center; gap: 16px; padding: 20px; }
    .personnel-avatar { display: flex; align-items: center; justify-content: center; width: 56px; height: 56px; flex: 0 0 56px; border-radius: 6px; background: #eaf2fc; color: #206bc4; }
    .personnel-name { font-size: 20px; line-height: 1.4; margin: 0 0 4px; overflow-wrap: anywhere; }
    .personnel-section { padding: 20px; }
    .personnel-details { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; margin: 16px 0 0; }
    .personnel-details dt { font-size: 12px; font-weight: 400; color: #667382; margin-bottom: 4px; }
    .personnel-details dd { margin: 0; overflow-wrap: anywhere; }
    .personnel-skills td, .personnel-skills th { padding: 10px 12px; }
    @media (max-width: 575.98px) {
        .personnel-details { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    }
</style>
<div class="personnel-profile">
    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
        <h2 class="page-title">Profil Personel</h2>
        <a href="{{ route('pengguna', ['key' => 3]) }}" class="btn btn-outline-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m6-6-6 6 6 6"/></svg>
            Kembali
        </a>
    </div>
    <div class="card mb-3">
    <div class="personnel-identity">
        <div class="personnel-avatar" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"><circle cx="12" cy="7" r="4"/><path d="M5 21v-2a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v2"/></svg>
        </div>
        <div style="min-width: 0">
            <h3 class="personnel-name">{{ $user->name }}</h3>
            <div class="text-muted" style="overflow-wrap: anywhere">{{ $user->email }}</div>
        </div>
    </div>
    </div>
    <section class="card personnel-section mb-3">
        <h3 class="h3 mb-0">Data Personel</h3>
        <dl class="personnel-details">
            @foreach($details as $field => $label)
                <div><dt>{{ $label }}</dt><dd>{{ $user->$field ?: '-' }}</dd></div>
            @endforeach
        </dl>
    </section>
    <section class="card personnel-section">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3 flex-wrap">
            <h3 class="h3 mb-0">Kemampuan</h3>
            <button type="button" class="btn {{ $user->kemampuanModel ? 'btn-outline-primary' : 'btn-primary' }}" id="edit-skills">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 3 5 5-12 12H4v-5L16 3m-2 2 5 5"/></svg>
                {{ $user->kemampuanModel ? 'Edit Kemampuan' : 'Tambah Kemampuan' }}
            </button>
        </div>
        @if($user->kemampuanModel)
            <div class="table-responsive">
                <table class="table table-vcenter personnel-skills mb-0">
                    <thead><tr><th>Kemampuan</th><th class="text-end">Hasil</th></tr></thead>
                    <tbody>
                        @foreach($skills as $field => $definition)
                            <tr><td>{{ $definition[0] }}</td><td class="text-end fw-medium">{{ $user->kemampuanModel->$field ?: '-' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-muted text-center py-4">Belum ada data kemampuan.</div>
        @endif
    </section>
</div>
@endsection
@section('modal')
<div class="modal fade" id="modal-kemampuan" tabindex="-1" aria-labelledby="skills-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form class="modal-content" id="skills-form">
            <div class="modal-header">
                <h3 class="modal-title" id="skills-title">{{ $user->kemampuanModel ? 'Edit Kemampuan' : 'Tambah Kemampuan' }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    @foreach($skills as $field => $definition)
                        <div class="col-md-6">
                            <label for="skill-{{ $field }}" class="form-label required">{{ $definition[0] }}</label>
                            <div class="input-group">
                                <input type="number" id="skill-{{ $field }}" name="{{ $field }}" class="form-control" min="0" step="{{ in_array($field, ['push_up', 'sit_up', 'pull_up']) ? '1' : 'any' }}" required>
                                <span class="input-group-text">{{ $definition[1] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="save-skills">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('skills-form');
    const modal = new bootstrap.Modal(document.getElementById('modal-kemampuan'));
    const button = document.getElementById('save-skills');
    const definitions = @json($skills);
    const existing = @json($user->kemampuanModel);
    document.getElementById('edit-skills').addEventListener('click', function () {
        form.reset();
        Object.keys(definitions).forEach(function (field) {
            const value = String(existing?.[field] ?? '').match(/[\d.]+/);
            form.elements[field].value = value ? value[0] : '';
        });
        modal.show();
    });
    if (@json(request('kemampuan') === '1')) {
        document.getElementById('edit-skills').click();
        const profileUrl = new URL(window.location.href);
        profileUrl.searchParams.delete('kemampuan');
        history.replaceState(null, '', profileUrl.href);
    }
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (button.disabled || !form.reportValidity()) return;
        const data = { user_id: @json($user->id) };
        Object.keys(definitions).forEach(function (field) {
            data[field] = form.elements[field].value + ' ' + definitions[field][1];
        });
        button.disabled = true;
        button.textContent = 'Menyimpan...';
        $.ajax({
            url: @json(url('/api/pengguna/kemampuan/store')),
            type: 'POST', dataType: 'json', data: data, headers: { 'X-CSRF-TOKEN': token }
        }).done(function (result) {
            if (String(result.status).toLowerCase() !== 'success') {
                notif('Kemampuan gagal disimpan.', 'danger');
                return;
            }
            sessionStorage.setItem('personnel-skills-saved', '1');
            location.reload();
        }).fail(function (response) {
            const errors = response.responseJSON?.errors;
            notif(errors ? Object.values(errors).flat().join(' ') : 'Kemampuan gagal disimpan. Silakan coba lagi.', 'danger');
        }).always(function () {
            button.disabled = false;
            button.textContent = 'Simpan';
        });
    });
    if (sessionStorage.getItem('personnel-skills-saved')) {
        sessionStorage.removeItem('personnel-skills-saved');
        notif('Kemampuan berhasil disimpan.', 'success');
    }
});
</script>
@endpush
