@extends('layouts.app')
@section('content')
<div class="page page-center">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-12 text-center">
                <div class="d-inline-block">
                    <div class="card rounded-20 border-0">
                        <div class="card-body p-3">
                            <div id="qrcode"></div>
                        </div>
                    </div>
                    <div style="border: 1px dashed #636e72;" class="mx-3"></div>
                    <div class="card rounded-20 border-0">
                        <div class="card-body text-center pt-3">
                            <h2 class="fw-bold mb-3">{{ $qrcode->title }}</h2>
                            <div class="mt-3">
                                <h3 class="mb-0"><strong>Spartan Mobile</strong></h3>
                                <p class="small">Copyright By Aplication Spartan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="{{ asset('assets/pus_dist/lib/jquery-qrcode/jquery-qrcode.js') }}"></script>
<script>
    let code = <?= json_encode($qrcode->code) ?>;

    $(document).ready(function() {
        generate();
        window.print();
    });

    const generate = () => {
        $("#qrcode").qrcode({
            size: 300,
            fill: '#0c0c0c',
            text: code,
            mode: 0,
        });
    }
</script>
@endpush