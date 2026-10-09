@extends('layouts.app_template')
@section('content')
<div class="mb-4"><div class="page-pretitle">Komunikasi</div><h2 class="page-title">Push Notifikasi</h2></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
<div class="card mb-4">
    <div class="card-header"><h3 class="card-title">Alarm Stelling</h3></div>
    <div class="card-body">
        <p class="text-secondary">Bunyikan atau hentikan alarm bersama seperti di mobile. Alarm ini berlaku untuk seluruh perangkat yang mengikuti Alarm Stelling.</p>
        <div id="alarm-feedback" class="alert d-none" role="status"></div>
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label for="alarm-code" class="form-label">Jenis alarm</label>
                <select id="alarm-code" class="form-select" disabled>
                    <option value="0">Awan Jingga — Siaga Tingkat I</option>
                    <option value="1">Awan Kuning — Siaga Tingkat II</option>
                    <option value="2">Awan Biru — Siaga Tingkat III</option>
                    <option value="3">Angin Gunung — Pencabutan Siaga</option>
                    <option value="4">Angin Puyuh — Siap Digerakan Sewaktu Waktu</option>
                </select>
            </div>
            <div class="col-md-3"><div id="alarm-state" class="mb-1" role="status">Memuat status alarm...</div><div id="alarm-time" class="fs-2 font-monospace">00:00:00</div></div>
            <div class="col-md-3"><button id="alarm-toggle" type="button" class="btn btn-danger w-100" disabled>Bunyikan alarm</button></div>
        </div>
        <label class="form-check form-switch mt-3"><input id="alarm-siren" type="checkbox" class="form-check-input" checked disabled><span class="form-check-label">Suara sirene</span></label>
        <p class="text-secondary small">Jika dimatikan, alarm tetap aktif dengan suara notifikasi biasa. Hentikan alarm sebelum mengubah pilihan suara.</p>
        <button id="alarm-reload" type="button" class="btn btn-link px-0 mt-2">Perbarui status</button>
    </div>
</div>
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
        <label class="form-check form-switch mb-0"><input class="form-check-input" type="checkbox" name="siren" value="1" {{ old('siren') ? 'checked' : '' }}><span class="form-check-label">Suara sirene</span></label>
    </div>
    <div class="card-footer d-flex justify-content-end"><button class="btn btn-primary" type="submit">
        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 14 21 3M21 3l-7 18-4-7-7-4z"/></svg>
        Kirim notifikasi
    </button></div>
    </div></div><div class="col-lg-4">
    <h3>Penerima</h3>
    <label for="push-recipient" class="form-label required">Pilih penerima</label>
    <select id="push-recipient" name="recipient_id" class="form-select" required>
        <option value="">Pilih penerima...</option>
        <option value="all" {{ old('recipient_id') === 'all' ? 'selected' : '' }}>Semua aplikasi</option>
        @foreach($recipients as $recipient)
        <option value="{{ $recipient->id }}" {{ (string) old('recipient_id') === (string) $recipient->id ? 'selected' : '' }}>{{ $recipient->name }} · {{ $recipient->email }} ({{ $recipient->device_count }} perangkat)</option>
        @endforeach
    </select>
    @error('recipient_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
    <p class="text-secondary small mt-2">Semua aplikasi menerima pesan melalui langganan notifikasi umum, termasuk tanpa login. Pilihan akun mengirim ke perangkat terdaftar milik akun tersebut.</p>
    @if($recipients->isEmpty())
    <div class="alert alert-info">Belum ada akun dengan perangkat terdaftar. Anda tetap dapat memilih Semua aplikasi.</div>
    @endif
    <hr class="my-4"><h3>Pratinjau pesan</h3>
    <div class="border-start ps-3" style="overflow-wrap: anywhere"><div class="text-secondary small mb-2">MENARMED</div><div id="preview-title" class="fw-bold mb-1">Judul notifikasi</div><div id="preview-body" class="text-secondary" style="white-space: pre-wrap">Isi pesan</div></div>
    </div></div>
</form>
<script>
(() => {
    const select = document.getElementById('alarm-code');
    const button = document.getElementById('alarm-toggle');
    const state = document.getElementById('alarm-state');
    const siren = document.getElementById('alarm-siren');
    const feedback = document.getElementById('alarm-feedback');
    let active = false, busy = false, started = null, loaded = false;
    function message(text, warning = false) {
        feedback.textContent = text;
        feedback.className = 'alert ' + (warning ? 'alert-warning' : 'alert-success');
    }
    function controls() {
        button.disabled = busy || !loaded;
        select.disabled = busy || !loaded || active;
        siren.disabled = busy || !loaded || active;
        button.textContent = busy ? 'Memproses...' : active ? 'Hentikan alarm' : 'Bunyikan alarm';
        state.textContent = loaded ? (active ? 'Alarm aktif' : 'Alarm tidak aktif') : 'Status alarm belum tersedia';
    }
    async function load() {
        if (busy) return;
        busy = true; controls();
        try {
            const response = await fetch(@json(route('push-test.alarm.status')), {headers: {'Accept': 'application/json'}, cache: 'no-store'});
            if (!response.ok) throw new Error('Status alarm gagal dimuat. Klik Perbarui status untuk mencoba lagi.');
            const data = await response.json();
            active = data.status === true;
            started = data.started_at || null;
            if (active) siren.checked = data.siren !== false;
            if (Number.isInteger(data.code) && data.code >= 0 && data.code <= 4) select.value = String(data.code);
            loaded = true;
        } catch (error) { loaded = false; message(error.message, true); }
        finally { busy = false; controls(); }
    }
    button.addEventListener('click', async () => {
        if (busy || !loaded) return;
        const next = !active;
        busy = true; controls();
        try {
            const response = await fetch(@json(route('push-test.alarm.store')), {
                method: 'POST', headers: {'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': @json(csrf_token())},
                body: JSON.stringify({status: next, code: Number(select.value), siren: siren.checked})
            });
            if (!response.ok) throw new Error('Alarm gagal diperbarui. Perbarui status sebelum mencoba lagi.');
            const data = await response.json();
            active = next; started = next ? Date.now() : null;
            message(data.push_sent === false ? 'Alarm aktif, tetapi push notifikasi gagal dikirim. Alarm tetap dapat dihentikan.' : next ? 'Alarm berhasil diaktifkan.' : 'Alarm berhasil dihentikan.', data.push_sent === false);
        } catch (error) { loaded = false; message(error.message, true); }
        finally { busy = false; controls(); }
    });
    document.getElementById('alarm-reload').addEventListener('click', load);
    setInterval(() => {
        const seconds = active && started ? Math.max(0, Math.floor((Date.now() - started) / 1000)) : 0;
        document.getElementById('alarm-time').textContent = [Math.floor(seconds / 3600), Math.floor(seconds / 60) % 60, seconds % 60].map(value => String(value).padStart(2, '0')).join(':');
    }, 1000);
    setInterval(load, 15000);
    load();
})();
</script>
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
