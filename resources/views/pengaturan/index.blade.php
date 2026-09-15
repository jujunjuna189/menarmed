@extends('layouts.app_template')
@section('content')
<div class="d-flex align-items-center">
    <h2 class="h1 my-0 me-2">Pengaturan</h2>
    <span class="nav-link-icon d-md-none d-lg-inline-block">
        <!-- Download SVG icon from http://tabler-icons.io/i/settings -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        </svg>
    </span>
</div>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <h4>Teks Berjalan</h4>
            <div class="card rounded-10 border-0">
                <div class="card-body p-0">
                    <div class="accordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#marquee" aria-expanded="true">
                                    Marquee
                                </button>
                            </h2>
                            <div id="marquee" class="accordion-collapse collapse" data-bs-parent="#accordion-example">
                                <div class="accordion-body pt-0">
                                    <div class="d-flex align-items-center">
                                        <span>
                                            {{ $dashboard_marquee->text }}
                                        </span>
                                        <span class="badge bg-yellow-lt border-dashed cursor-pointer ms-2" data-bs-toggle="modal" data-bs-target="#modal-update-marquee">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                <path d="M16 5l3 3"></path>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h4 class="mt-3">Slider Show</h4>
            <div class="card rounded-10 border-0">
                <div class="card-body p-0">
                    <div class="accordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="true">
                                    Dashboard
                                </button>
                            </h2>
                            <div id="collapse-1" class="accordion-collapse collapse show" data-bs-parent="#accordion-example">
                                <div class="accordion-body pt-0">
                                    <!-- <div class="d-flex justify-content-between align-items-center">
                                        <span>Status slider dashboard</span>
                                        <div class="mt-2">
                                            <label class="form-check form-check-single form-switch ps-0">
                                                <input class="form-check-input" type="checkbox" checked="">
                                            </label>
                                        </div>
                                    </div> -->
                                    <div class="mt-3 py-1 border-dashed border-1 rounded-10"></div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mt-3">
                                                <span class="fw-bold">Preview</span>
                                            </div>
                                            <div class="mt-3">
                                                <div class="card border-0 px-2">
                                                    <div class="card-body p-0">
                                                        <div id="carousel-indicators" class="carousel slide" data-bs-ride="carousel">
                                                            <ol class="carousel-indicators">
                                                                @foreach($dashboard_slider as $val)
                                                                <li data-bs-target="#carousel-indicators" data-bs-slide-to="{{ $dashboard_slider_indicator }}" class="@if($dashboard_slider_indicator++ == 0) active @endif"></li>
                                                                @endforeach
                                                            </ol>
                                                            <div class="carousel-inner rounded-10">
                                                                @foreach($dashboard_slider as $val)
                                                                <div class="carousel-item @if($dashboard_slider_number++ == 0) active @endif">
                                                                    <img class="d-block w-100" alt="" style="height: 200px;" src="{{ asset($val->path) }}">
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="mt-3">
                                                <span class="fw-bold">List Item*</span>
                                            </div>
                                            <div class="mt-3 ps-2">
                                                @foreach($dashboard_slider as $val)
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div>
                                                        <span>Item {{ $dashboard_slider_item++ }}</span>
                                                        <span class="badge bg-red-lt border-dashed ms-3 cursor-pointer" onclick="sliderDelete('<?= $val->id ?>')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <line x1="4" y1="7" x2="20" y2="7"></line>
                                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <span>{{ substr(asset($val->path), 47, strlen(asset($val->path))) }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="mt-4 ps-2">
                                                <span class="badge p-2 bg-transparent text-dark border-dashed" data-bs-toggle="modal" data-bs-target="#modal-slider">Tambah</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
<!-- Modal Update Marquee -->
<div class="modal modal-blur fade" id="modal-update-marquee" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah teks berjalan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" name="text" id="text_marquee" class="form-control" placeholder="Masukan teks disini...">
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Batal
                </a>
                <button type="button" class="btn btn-primary ms-auto" onclick="marqueeStore()">
                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal slider -->
<div class="modal modal-blur fade" id="modal-slider" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Slider Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Batal
                </a>
                <a href="#" class="btn btn-primary ms-auto" onclick="reload()">
                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Simpan
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
        new Dropzone("#dropzone-custom", {
            paramName: "file", // The name that will be used to transfer the file
            acceptedFiles: "image/*",
            error: function(value) {
                console.log(value);
            }
        });
    });

    // Slider
    const marqueeStore = () => {
        let text = $(element.text_marquee).val();
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
    const sliderDelete = (id) => {
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