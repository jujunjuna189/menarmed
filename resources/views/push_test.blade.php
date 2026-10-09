@extends('layouts.app_template')
@section('content')
<div class="mb-4"><div class="page-pretitle">Komunikasi</div><h2 class="page-title">Push Notifikasi</h2></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
<form method="POST" action="{{ route('push-test.send') }}" id="push-composer">
    @csrf
    <div class="row g-4"><div class="col-lg-8">
    <div class="card" style="border-radius: 8px">
    <div class="card-header"><h3 class="card-title">Pesan baru</h3></div>
    <div class="card-body">
        <label for="push-title" class="form-label required">Judul notifikasi</label>
        <input id="push-title" name="title" class="form-control mb-3" value="{{ old('title') }}" required maxlength="120" placeholder="Contoh: Pengumuman apel pagi">
        @error('title')<div class="text-danger mb-3">{{ $message }}</div>@enderror
        <label for="push-body" class="form-label required">Isi pesan</label>
        <textarea id="push-body" name="body" class="form-control mb-3" rows="5" required maxlength="1000" placeholder="Tulis pesan untuk penerima...">{{ old('body') }}</textarea>
        @error('body')<div class="text-danger mb-3">{{ $message }}</div>@enderror
        <label class="form-check form-switch mb-0"><input class="form-check-input" type="checkbox" name="siren" value="1" @checked(old('siren'))><span class="form-check-label">Suara sirene</span></label>
    </div>
    <div class="card-footer d-flex justify-content-end"><button class="btn btn-primary" type="submit">
        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 14 21 3M21 3l-7 18-4-7-7-4z"/></svg>
        Kirim notifikasi
    </button></div>
    </div></div><div class="col-lg-4">
    <h3>Penerima</h3><span class="badge bg-secondary-lt mb-3">Satu perangkat</span>
    <label for="fcm-token" class="form-label required">Token FCM perangkat tujuan</label>
    <textarea id="fcm-token" name="token" class="form-control" rows="4" required maxlength="4096" autocomplete="off" spellcheck="false"></textarea>
    @error('token')<div class="text-danger mt-1">{{ $message }}</div>@enderror
    <hr class="my-4"><h3>Pratinjau pesan</h3>
    <div class="border-start ps-3" style="overflow-wrap: anywhere"><div class="text-secondary small mb-2">MENARMED</div><div id="preview-title" class="fw-bold mb-1">Judul notifikasi</div><div id="preview-body" class="text-secondary" style="white-space: pre-wrap">Isi pesan</div></div>
    </div></div>
</form>
<script>
(() => {
    const form = document.getElementById('push-composer');
    ['title', 'body'].forEach(field => {
        const input = document.getElementById('push-' + field);
        const preview = document.getElementById('preview-' + field);
        const fallback = preview.textContent;
        const update = () => preview.textContent = input.value.trim() || fallback;
        input.addEventListener('input', update);
        update();
    });
    form.addEventListener('submit', () => {
        const button = form.querySelector('button[type="submit"]');
        button.disabled = true;
        button.textContent = 'Mengirim...';
    });
})();
</script>
@endsection
