@extends('layouts.app')
@section('title', $artikel->judul ?: 'Artikel')
@section('content')
<style>
    body { background: #fff; border-top: 0 !important; }
    .article-preview { width: 100%; max-width: 840px; margin: 0 auto; padding: 24px 32px 64px; color: #182433; }
    .article-preview-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding-bottom: 20px; border-bottom: 1px solid #e6eaf0; margin-bottom: 32px; }
    .article-preview-action { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; padding: 0; border: 0; border-radius: 6px; background: transparent; color: #667382; text-decoration: none; cursor: pointer; transition: background .15s ease, color .15s ease; }
    .article-preview-action:hover { background: #f1f3f5; color: #182433; }
    .article-preview-action:focus-visible { outline: 2px solid #206bc4; outline-offset: 2px; }
    .article-preview-action .icon { width: 20px; height: 20px; }
    .article-preview-title { font-size: 32px; line-height: 1.25; margin: 12px 0 16px; overflow-wrap: anywhere; }
    .article-preview-description { font-size: 16px; line-height: 1.7; color: #667382; margin-bottom: 24px; white-space: pre-line; overflow-wrap: anywhere; }
    .article-preview-content { font-size: 15px; line-height: 1.85; overflow-wrap: anywhere; }
    .article-preview-content p { margin-bottom: 20px; }
    .article-preview-content h2 { font-size: 24px; }
    .article-preview-content h3 { font-size: 20px; }
    .article-preview-content h4 { font-size: 18px; }
    .article-preview-content h2, .article-preview-content h3, .article-preview-content h4 { margin: 28px 0 12px; line-height: 1.4; }
    .article-preview-content img { max-width: 100% !important; height: auto !important; }
    .article-preview-content iframe { display: block; max-width: 100%; width: 100%; height: auto; aspect-ratio: 16 / 9; border: 0; margin: 20px 0; }
    .article-preview-content table { display: block; max-width: 100%; overflow-x: auto; border-collapse: collapse; margin-bottom: 20px; }
    .article-preview-content th, .article-preview-content td { border: 1px solid #dce1e7; padding: 8px 12px; min-width: 100px; }
    .article-preview-content th { background: #f6f8fb; }
    .article-preview-content blockquote { margin: 24px 0; padding: 8px 20px; border-left: 3px solid #206bc4; background: #f6f8fb; color: #526173; }
    .article-preview-content a { text-decoration: underline; text-underline-offset: 3px; }
    @media (max-width: 575.98px) {
        .article-preview { padding: 16px 20px 40px; }
        .article-preview-title { font-size: 26px; }
        .article-preview-toolbar { margin-bottom: 24px; }
    }
    @media print {
        body { border: 0 !important; background: #fff; }
        .article-preview { max-width: none; padding: 0; }
        .article-preview-toolbar, .article-preview-content iframe { display: none; }
    }
</style>
<main class="article-preview">
    <div class="article-preview-toolbar">
        <span class="text-muted fw-medium">{{ config('app.name') }}</span>
        <div class="d-flex gap-2">
            @if(auth()->check() && (int) auth()->user()->role === 1)
                <a href="{{ route('artikel') }}" class="article-preview-action" title="Kembali ke daftar artikel" aria-label="Kembali ke daftar artikel">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m6-6-6 6 6 6"/></svg>
                </a>
            @endif
            <button type="button" class="article-preview-action" id="print-article" title="Cetak artikel" aria-label="Cetak artikel">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9V3h12v6M6 18H4a1 1 0 0 1-1-1v-6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6a1 1 0 0 1-1 1h-2M6 14h12v7H6zM17 12h.01"/></svg>
            </button>
        </div>
    </div>
    <article>
        <header>
            @if($artikel->created_at)
                <time class="text-muted small" datetime="{{ $artikel->created_at->toIso8601String() }}">{{ $artikel->created_at->locale('id')->translatedFormat('d F Y') }}</time>
            @endif
            <h1 class="article-preview-title">{{ $artikel->judul ?: 'Artikel' }}</h1>
            @if($artikel->deskripsi)
                <p class="article-preview-description">{{ $artikel->deskripsi }}</p>
            @endif
        </header>
        <div class="article-preview-content">
        {!! \App\Support\ArtikelContent::sanitize($artikel->artikel) !!}
        </div>
    </article>
</main>
@endsection
@push('script')
<script>
document.getElementById('print-article').addEventListener('click', function () {
    window.print();
});
</script>
@endpush
