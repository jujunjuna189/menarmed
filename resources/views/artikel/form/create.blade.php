@extends('layouts.app_template')
@section('content')
<style>
    .article-form-page .btn { box-shadow: none !important; }
    .article-form-page .note-editor.note-frame { border: 1px solid #dce1e7; border-radius: 6px; box-shadow: none; }
    .article-form-page .note-toolbar { display: flex; flex-wrap: wrap; align-items: center; gap: 4px; padding: 8px; background: #f6f8fb; border-bottom: 1px solid #dce1e7; }
    .article-form-page .note-toolbar > .note-btn-group { margin: 0; padding-right: 6px; border-right: 1px solid #dce1e7; }
    .article-form-page .note-toolbar > .note-btn-group:last-child { border-right: 0; padding-right: 0; margin-left: auto; }
    .article-form-page .note-btn { min-height: 32px; padding: 5px 9px; border-color: transparent; border-radius: 4px; background: transparent; color: #526173; box-shadow: none; }
    .article-form-page .note-btn:hover,
    .article-form-page .note-btn.active { background: #eaf2fc; color: #206bc4; }
    .article-form-page .note-btn:focus-visible { outline: 2px solid #206bc4; outline-offset: -2px; }
    .article-form-page .note-editable { padding: 24px 32px; font-size: 15px; line-height: 1.8; color: #182433; overflow-wrap: anywhere; }
    .article-form-page .note-editable p { margin-bottom: 16px; }
    .article-form-page .note-placeholder { padding: 24px 32px; font-size: 15px; color: #929dab; }
    .article-form-page .note-editor:focus-within { border-color: #8eb6e5; }
    .article-editor-footer { display: flex; justify-content: flex-end; gap: 16px; padding: 8px 12px; border: 1px solid #dce1e7; border-top: 0; border-radius: 0 0 6px 6px; color: #667382; font-size: 12px; background: #f6f8fb; }
    .article-form-page .note-editor.note-frame { border-bottom-left-radius: 0; border-bottom-right-radius: 0; }
    @media (max-width: 575.98px) {
        .article-form-page .note-editable,
        .article-form-page .note-placeholder { padding: 16px; }
    }
    .article-form-page .note-editable img { max-width: 100%; }
    .article-form-page .note-statusbar { background: #f6f8fb; }
    .article-form-page .note-editor.is-invalid { border-color: #d63939; }
    .article-form-page .note-editing-area { min-width: 0; }
</style>
<div class="article-form-page">
    <form id="article-form">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-3">
            <h2 class="page-title mb-0" id="article-form-title">{{ $artikel ? 'Edit Artikel' : 'Tambah Artikel' }}</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('artikel') }}" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m6-6-6 6 6 6"/></svg>
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary" id="save-article">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 3h12l3 3v15H3V3h3m1 0v6h10V3M7 21v-8h10v8"/></svg>
                    Simpan
                </button>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="judul" class="form-label required">Judul</label>
                        <input type="text" name="judul" id="judul" class="form-control" required placeholder="Judul artikel" value="{{ $artikel->judul ?? '' }}">
                    </div>
                    <div class="col-12">
                        <label for="deskripsi" class="form-label required">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="2" class="form-control" required placeholder="Ringkasan artikel">{{ $artikel->deskripsi ?? '' }}</textarea>
                    </div>
                    <div class="col-12">
                        <label for="summernote" class="form-label required">Isi Artikel</label>
                        <textarea id="summernote" aria-describedby="article-content-error"></textarea>
                        <div class="article-editor-footer">
                            <span id="article-word-count">0 kata</span>
                            <span id="article-character-count">0 karakter</span>
                        </div>
                        <div id="article-content-error" class="text-danger small mt-1" hidden>Isi artikel harus diisi.</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('script')
<script>
$(function () {
    let articleId = @json($artikel_id);
    let saving = false;
    const form = document.getElementById('article-form');
    const saveButton = document.getElementById('save-article');
    const contentError = document.getElementById('article-content-error');
    const editor = $('#summernote');
    const buttonContent = saveButton.innerHTML;

    editor.summernote({
        placeholder: 'Tulis isi artikel...',
        height: 420,
        minHeight: 280,
        focus: false,
        styleTags: ['p', 'h2', 'h3', 'h4', 'blockquote'],
        callbacks: {
            onChange: function (html) { updateCounts(html); }
        },
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture', 'table', 'video']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });

    const storedContent = @json($artikel ? \App\Support\ArtikelContent::sanitize($artikel->artikel) : '');
    let content = storedContent;
    try { content = JSON.parse(storedContent); } catch (error) {}
    editor.summernote('code', typeof content === 'string' ? content : storedContent);
    updateCounts(editor.summernote('code'));
    $('.note-editable').attr({ 'aria-label': 'Isi artikel', 'aria-required': 'true', 'aria-describedby': 'article-content-error' });

    function updateCounts(html) {
        const documentContent = new DOMParser().parseFromString(html, 'text/html');
        documentContent.body.querySelectorAll('p, div, h2, h3, h4, li, blockquote, br').forEach(function (element) {
            element.appendChild(documentContent.createTextNode(' '));
        });
        const text = documentContent.body.textContent.replace(/\s+/g, ' ').trim();
        document.getElementById('article-word-count').textContent = (text ? text.split(' ').length : 0) + ' kata';
        document.getElementById('article-character-count').textContent = Array.from(text).length + ' karakter';
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (saving || !form.reportValidity()) return;
        if (editor.summernote('codeview.isActivated')) editor.summernote('codeview.deactivate');
        const html = editor.summernote('code');
        const parsed = new DOMParser().parseFromString(html, 'text/html');
        const empty = !parsed.body.textContent.trim() && !parsed.body.querySelector('img, video, iframe, table, hr');
        contentError.hidden = !empty;
        editor.next('.note-editor').toggleClass('is-invalid', empty);
        $('.note-editable').attr('aria-invalid', String(empty));
        if (empty) {
            editor.summernote('focus');
            return;
        }
        saving = true;
        saveButton.disabled = true;
        saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Menyimpan...';

        $.ajax({
            url: @json(url('/api/artikel/store')),
            type: 'POST',
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': token },
            data: {
                artikel_id: articleId,
                judul: form.elements.judul.value.trim(),
                deskripsi: form.elements.deskripsi.value.trim(),
                artikel: JSON.stringify(html)
            }
        }).done(function (result) {
            if (result.status !== 'Success' || !result.data?.id) {
                showStatus('Artikel gagal disimpan. Silakan coba lagi.', false);
                return;
            }
            articleId = result.data.id;
            document.getElementById('article-form-title').textContent = 'Edit Artikel';
            const editUrl = new URL(@json(route('artikel.create')));
            editUrl.searchParams.set('artikel_id', articleId);
            history.replaceState(null, '', editUrl.href);
            showStatus('Artikel berhasil disimpan.', true);
        }).fail(function (response) {
            const errors = response.responseJSON?.errors;
            const message = errors ? Object.values(errors).flat().join(' ') :
                response.status === 404 ? 'Artikel sudah tidak tersedia. Kembali ke daftar artikel.' :
                response.status === 401 || response.status === 419 ? 'Sesi berakhir. Muat ulang halaman dan login kembali.' :
                response.status === 403 ? 'Anda tidak memiliki akses untuk menyimpan artikel.' :
                'Artikel gagal disimpan. Silakan coba lagi.';
            showStatus(message, false);
        }).always(function () {
            saving = false;
            saveButton.disabled = false;
            saveButton.innerHTML = buttonContent;
        });
    });

    function showStatus(message, success) {
        notif(message, success ? 'success' : 'danger');
    }
});
</script>
@endpush
