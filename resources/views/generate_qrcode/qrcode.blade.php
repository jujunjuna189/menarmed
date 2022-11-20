@extends('layouts.app_template')
@section('content')
<div class="row">
    <div class="col-md-7">
        @foreach($qrcode as $val)
        <div class="card mt-2 rounded-20">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="text-dark h1 fw-bold">{{ $val->title }}</h2>
                        <div class="mt-2 mb-3">
                            <label for="kode"><span class="fw-bold">Kode :</span> <span class="text-muted">{{ $val->code }}</span></label>
                        </div>
                        <a href="#" class="btn rounded-10">
                            <!-- Download SVG icon from http://tabler-icons.io/i/update -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 15l8.385 -8.415a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3z"></path>
                                <path d="M16 5l3 3"></path>
                                <path d="M9 7.07a7.002 7.002 0 0 0 1 13.93a7.002 7.002 0 0 0 6.929 -5.999"></path>
                            </svg>
                            Perbarui
                        </a>
                        <a href="{{ route('generate', ['code' => $val->code]) }}" class="btn bg-red-lt rounded-10">
                            <!-- Download SVG icon from http://tabler-icons.io/i/print -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"></path>
                                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path>
                                <rect x="7" y="13" width="10" height="8" rx="2"></rect>
                            </svg>
                            Print
                        </a>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="130" height="130" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <rect x="4" y="4" width="6" height="6" rx="1"></rect>
                            <line x1="7" y1="17" x2="7" y2="17.01"></line>
                            <rect x="14" y="4" width="6" height="6" rx="1"></rect>
                            <line x1="7" y1="7" x2="7" y2="7.01"></line>
                            <rect x="4" y="14" width="6" height="6" rx="1"></rect>
                            <line x1="17" y1="7" x2="17" y2="7.01"></line>
                            <line x1="14" y1="14" x2="17" y2="14"></line>
                            <line x1="20" y1="14" x2="20" y2="14.01"></line>
                            <line x1="14" y1="14" x2="14" y2="17"></line>
                            <line x1="14" y1="20" x2="17" y2="20"></line>
                            <line x1="17" y1="17" x2="20" y2="17"></line>
                            <line x1="20" y1="17" x2="20" y2="20"></line>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="col-md-3 col-lg-3  offset-md-1">
        <div class="d-flex justify-content-center">
            <div class="d-inline-block">
                <div class="card rounded-20 border-0">
                    <div class="card-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <rect x="4" y="4" width="6" height="6" rx="1"></rect>
                            <line x1="7" y1="17" x2="7" y2="17.01"></line>
                            <rect x="14" y="4" width="6" height="6" rx="1"></rect>
                            <line x1="7" y1="7" x2="7" y2="7.01"></line>
                            <rect x="4" y="14" width="6" height="6" rx="1"></rect>
                            <line x1="17" y1="7" x2="17" y2="7.01"></line>
                            <line x1="14" y1="14" x2="17" y2="14"></line>
                            <line x1="20" y1="14" x2="20" y2="14.01"></line>
                            <line x1="14" y1="14" x2="14" y2="17"></line>
                            <line x1="14" y1="20" x2="17" y2="20"></line>
                            <line x1="17" y1="17" x2="20" y2="17"></line>
                            <line x1="20" y1="17" x2="20" y2="20"></line>
                        </svg>
                    </div>
                </div>
                <div style="border: 1px dashed #636e72;" class="mx-3"></div>
                <div class="card rounded-20 border-0">
                    <div class="card-body">
                        <h2 class="fw-bold mb-3">Generate QrCode</h2>
                        <div class="form-group text-center">
                            <label for="title">Judul</label>
                            <input type="text" class="form-control rounded-10" placeholder="...">
                        </div>
                        <div class="form-group text-center mt-2">
                            <label for="title">Kode</label>
                            <input type="text" class="form-control rounded-10" placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="card rounded-20 border-0">
                        <div class="card-body">
                            <h3>Keterangan</h3>
                            <ul>
                                <li class="p-2"><span class="fw-bold">01</span> - Perizinan</li>
                                <li class="p-2"><span class="fw-bold">02</span> - Gudang Senjata</li>
                                <li class="p-2"><span class="fw-bold">03</span> - Logistik</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    const qrcodeUpdate = (code) => {

    }
</script>
@endpush