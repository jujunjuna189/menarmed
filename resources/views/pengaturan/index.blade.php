@extends('layouts.app_template')
@section('content')
<style>
    .settings-page .btn, .settings-modal .btn { box-shadow: none !important; }
    .settings-section { padding: 24px 0; }
    .settings-tabs { gap: 24px; border-bottom: 1px solid #dce1e7; }
    .settings-tabs .nav-link { padding: 12px 0; border: 0; border-bottom: 2px solid transparent; border-radius: 0; color: #667382; background: transparent; font-weight: 600; }
    .settings-tabs .nav-link.active { border-color: #206bc4; color: #206bc4; background: transparent; }
    .settings-count { margin-left: 8px; font-size: 12px; color: #667382; }
    .settings-section h3 { font-size: 16px; margin: 0; }
    .settings-preview { aspect-ratio: 16 / 9; background: #f1f3f5; border: 1px solid #dce1e7; border-radius: 6px; overflow: hidden; }
    .settings-preview .carousel-inner, .settings-preview .carousel-item { height: 100%; }
    .settings-preview img { width: 100%; height: 100%; object-fit: contain; }
    .settings-slider-row { display: flex; align-items: center; gap: 12px; padding: 12px 8px; border-bottom: 1px solid #e6e9ed; }
    .settings-slider-row.is-current { background: #eef5fc; }
    .settings-thumbnail-button { padding: 0; border: 0; background: transparent; flex-shrink: 0; border-radius: 4px; }
    .settings-thumbnail-button:focus-visible { outline: 2px solid #206bc4; outline-offset: 3px; }
    .settings-preview .carousel-control-prev, .settings-preview .carousel-control-next { width: 32px; height: 32px; top: 50%; transform: translateY(-50%); background: #182433; border-radius: 4px; opacity: .85; }
    .settings-preview .carousel-control-prev { left: 10px; }
    .settings-preview .carousel-control-next { right: 10px; }
    .settings-preview .carousel-control-prev-icon, .settings-preview .carousel-control-next-icon { width: 16px; height: 16px; }
    .settings-slider-row:last-child { border-bottom: 0; }
    .settings-thumbnail { width: 72px; height: 48px; object-fit: contain; background: #f1f3f5; border-radius: 4px; flex-shrink: 0; }
    .settings-filename { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .settings-delete { width: 30px; height: 30px; padding: 0; flex-shrink: 0; }
    .settings-size-warning { font-size: 10px; font-weight: 500; line-height: 1.3; padding: 2px 5px; white-space: normal; }
    .settings-marquee { white-space: pre-wrap; overflow-wrap: anywhere; padding: 20px; border-left: 3px solid #206bc4; background: #f1f3f5; color: #182433; line-height: 1.8; }
</style>
<div class="settings-page">
    <h2 class="page-title mb-3">Pengaturan</h2>
    <div class="nav settings-tabs" role="tablist" aria-label="Pengaturan dashboard">
        <button class="nav-link active" id="settings-text-tab" data-bs-toggle="tab" data-bs-target="#settings-text" type="button" role="tab" aria-controls="settings-text" aria-selected="true">Teks Berjalan</button>
        <button class="nav-link" id="settings-images-tab" data-bs-toggle="tab" data-bs-target="#settings-images" type="button" role="tab" aria-controls="settings-images" aria-selected="false">Slider Dashboard<span class="settings-count">{{ $dashboard_slider->count() }}</span></button>
    </div>
    <div class="tab-content">
    <section class="settings-section tab-pane active" id="settings-text" role="tabpanel" aria-labelledby="settings-text-tab" tabindex="0">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
            <h3>Teks Berjalan</h3>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-update-marquee"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 3 5 5-12 12H4v-5L16 3m-2 2 5 5"/></svg>Edit</button>
        </div>
        <div class="settings-marquee">{{ $dashboard_marquee->text ?? 'Belum ada teks berjalan.' }}</div>
    </section>
    <section class="settings-section tab-pane" id="settings-images" role="tabpanel" aria-labelledby="settings-images-tab" tabindex="0">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
            <h3>Slider Dashboard</h3>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-slider"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>Tambah</button>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                @if($dashboard_slider->count())
                <div id="settings-carousel" class="carousel slide settings-preview" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($dashboard_slider as $val)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            <img src="{{ asset($val->path) }}" alt="Slider dashboard {{ $loop->iteration }}">
                        </div>
                        @endforeach
                    </div>
                    @if($dashboard_slider->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#settings-carousel" data-bs-slide="prev" aria-label="Gambar sebelumnya"><span class="carousel-control-prev-icon" aria-hidden="true"></span></button>
                    <button class="carousel-control-next" type="button" data-bs-target="#settings-carousel" data-bs-slide="next" aria-label="Gambar berikutnya"><span class="carousel-control-next-icon" aria-hidden="true"></span></button>
                    @endif
                </div>
                @else
                <div class="settings-preview d-flex align-items-center justify-content-center text-muted">Belum ada gambar.</div>
                @endif
            </div>
            <div class="col-md-6">
                @forelse($dashboard_slider as $val)
                <div class="settings-slider-row {{ $loop->first ? 'is-current' : '' }}" data-slide-index="{{ $loop->index }}">
                    <button type="button" class="settings-thumbnail-button" data-bs-target="#settings-carousel" data-bs-slide-to="{{ $loop->index }}" title="Lihat gambar {{ $loop->iteration }}" aria-label="Lihat gambar {{ $loop->iteration }}">
                        <img src="{{ asset($val->path) }}" class="settings-thumbnail" alt="Slider {{ $loop->iteration }}">
                    </button>
                    <div class="flex-fill" style="min-width: 0;">
                        <div class="fw-semibold">Gambar {{ $loop->iteration }}</div>
                        <div class="settings-filename text-muted small" title="{{ basename($val->path) }}">{{ basename($val->path) }}</div>
                        <div class="text-muted small">{{ $slider_sizes[$val->id] }}</div>
                        @if($slider_size_warnings[$val->id])
                        <span class="badge bg-yellow-lt settings-size-warning mt-1">Ukuran lebih dari 1 MB</span>
                        @endif
                    </div>
                    <button type="button" class="btn btn-icon btn-outline-secondary settings-delete" data-bs-toggle="modal" data-bs-target="#modal-edit-slider" data-update-url="{{ route('pengaturan.slider.update', $val->id) }}" data-image="{{ asset($val->path) }}" title="Edit gambar" aria-label="Edit gambar {{ $loop->iteration }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 3 5 5-12 12H4v-5L16 3m-2 2 5 5"/></svg>
                    </button>
                    <button type="button" class="btn btn-icon btn-outline-danger settings-delete" onclick="sliderDelete({{ (int) $val->id }})" title="Hapus gambar" aria-label="Hapus gambar {{ $loop->iteration }}"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13M10 11v5M14 11v5"/></svg></button>
                </div>
                @empty
                <div class="text-muted py-3">Belum ada slider dashboard.</div>
                @endforelse
            </div>
        </div>
    </section>
    </div>
</div>
@endsection
@section('modal')
<div class="modal fade settings-modal" id="modal-edit-slider" tabindex="-1" aria-labelledby="edit-slider-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="edit-slider-form" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="edit-slider-title">Edit Gambar Slider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="settings-preview mb-3"><img id="edit-slider-preview" alt="Preview gambar slider"></div>
                <label for="edit-slider-file" class="form-label">Gambar pengganti</label>
                <input type="file" name="file" id="edit-slider-file" class="form-control" accept="image/jpeg,image/png,image/webp" required>
                @error('file')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal Update Marquee -->
<div class="modal fade settings-modal" id="modal-update-marquee" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah teks berjalan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="text_marquee" class="form-label">Teks berjalan</label>
                    <textarea name="text" id="text_marquee" class="form-control" rows="4" required>{{ $dashboard_marquee->text ?? '' }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Batal
                </a>
                <button type="button" class="btn btn-primary ms-auto" onclick="marqueeStore()">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal slider -->
<div class="modal fade settings-modal" id="modal-slider" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Slider Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form class="dropzone border-dashed" id="dropzone-custom" action="{{ url('api/setting/slider/store') }}" autocomplete="off" novalidate>
                    <div class="fallback">
                        <input name="file" type="file" />
                    </div>
                    <div class="dz-message">
                        <h3 class="dropzone-msg-title">Unggah Gambar</h3>
                        <span class="dropzone-msg-desc">Tarik atau pilih gambar</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Batal
                </a>
                <a href="#" class="btn btn-primary ms-auto" onclick="reloadPage()">
                    Selesai
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    const element = {
        text_marquee: '#text_marquee',
    };
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function() {
        const editModal = document.getElementById('modal-edit-slider');
        const editForm = document.getElementById('edit-slider-form');
        const editFile = document.getElementById('edit-slider-file');
        const editPreview = document.getElementById('edit-slider-preview');
        let previewUrl = null;
        const releasePreview = () => {
            if (previewUrl) URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        };
        editModal.addEventListener('show.bs.modal', event => {
            editForm.reset();
            releasePreview();
            editForm.action = event.relatedTarget.dataset.updateUrl;
            editPreview.src = event.relatedTarget.dataset.image;
        });
        editModal.addEventListener('hidden.bs.modal', releasePreview);
        editFile.addEventListener('change', () => {
            const file = editFile.files[0];
            if (!file) return;
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 5 * 1024 * 1024) {
                editFile.value = '';
                notif('Pilih JPG, PNG, atau WebP maksimal 5 MB.', 'warning');
                return;
            }
            releasePreview();
            previewUrl = URL.createObjectURL(file);
            editPreview.src = previewUrl;
        });
        const carousel = document.getElementById('settings-carousel');
        if (carousel) {
            carousel.addEventListener('slid.bs.carousel', function(event) {
                document.querySelectorAll('.settings-slider-row').forEach(row => {
                    row.classList.toggle('is-current', Number(row.dataset.slideIndex) === event.to);
                });
            });
        }
        document.getElementById('settings-images-tab').addEventListener('shown.bs.tab', () => {
            sessionStorage.setItem('settings-tab', 'images');
        });
        document.getElementById('settings-text-tab').addEventListener('shown.bs.tab', () => {
            sessionStorage.setItem('settings-tab', 'text');
        });
        if (sessionStorage.getItem('settings-tab') === 'images') {
            new bootstrap.Tab(document.getElementById('settings-images-tab')).show();
        }
        new Dropzone("#dropzone-custom", {
            paramName: "file", // The name that will be used to transfer the file
            acceptedFiles: "image/*",
            headers: { 'X-CSRF-TOKEN': @json(csrf_token()) },
            error: function(file, message) {
                notif(typeof message === 'string' ? message : 'Gambar gagal diunggah.', 'danger');
            }
        });
    });

    // Slider
    const marqueeStore = () => {
        let text = $(element.text_marquee).val().trim();
        if (!text) { notif('Teks berjalan tidak boleh kosong.', 'warning'); return; }
        let data = {
            text: text,
        };
        requestServer({
            url: url + '/api/setting/marquee/store',
            data: data,
            onLoader: true,
            onSuccess: function(value) {
                close_swal(true, 'Berhasil update teks berjalan', 'success');
                reloadPage();
            },
        });
    }

    // Slider
    const sliderDelete = async (id) => {
        if (!await confirmDelete('Hapus gambar slider ini?')) return;
        let data = {
            slider_id: id,
        };
        requestServer({
            url: url + '/api/setting/slider/delete',
            data: data,
            onLoader: true,
            onSuccess: function(value) {
                close_swal(true, 'Berhasil hapus slider', 'success');
                reloadPage();
            },
        });
    }
</script>
@endpush
