@extends('layouts.app')

@section('content')
<style>
    .qr-sheet { width: min(100%, 440px); }
    #qrcode canvas { display: block; width: 320px; height: 320px; max-width: 100%; margin: 0 auto; }
    @media print {
        body { border: 0 !important; background: #fff !important; }
        .print-actions { display: none !important; }
        .qr-sheet { width: 100%; max-width: none; }
        .card { border: 0 !important; box-shadow: none !important; }
    }
</style>

<div class="page page-center">
    <div class="container py-4">
        <div class="qr-sheet mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-3 print-actions">
                <a href="{{ route('qrcode') }}" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12h14"/><path d="M5 12l6 6"/><path d="M5 12l6 -6"/></svg>
                    Kembali
                </a>
                <div class="btn-list">
                    <button type="button" class="btn btn-outline-primary" id="download-qr">Unduh PNG</button>
                    <button type="button" class="btn btn-primary" id="print-qr">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9v-4h12v4"/><rect x="6" y="13" width="12" height="8" rx="1"/><path d="M6 17h-2a2 2 0 0 1 -2 -2v-4a2 2 0 0 1 2 -2h16a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-2"/></svg>
                        Cetak
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="card-body text-center p-4 p-md-5">
                    <div id="qrcode" class="mb-4"></div>
                    <h1 class="mb-2">{{ $qrcode->title }}</h1>
                    <div class="text-muted mb-4">Pindai menggunakan aplikasi Menarmed Mobile</div>
                    <div class="border-top pt-3">
                        <div class="fw-bold">Menarmed Mobile</div>
                        <code class="text-muted">{{ $qrcode->code }}</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('assets/pus_dist/lib/jquery-qrcode/jquery-qrcode.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const code = @json($qrcode->code);
    $('#qrcode').qrcode({ render: 'canvas', size: 320, fill: '#111827', background: '#ffffff', text: code });

    document.getElementById('print-qr').addEventListener('click', () => window.print());
    document.getElementById('download-qr').addEventListener('click', function () {
        const canvas = document.querySelector('#qrcode canvas');
        if (!canvas) return;
        const link = document.createElement('a');
        link.download = @json('qr-' . \Illuminate\Support\Str::slug($qrcode->title) . '.png');
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
});
</script>
@endpush
