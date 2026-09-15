@extends('layouts.app_template')
@section('content')
<div class="row g-3 align-items-center mb-4">
    <div class="col-auto">
        <span class="status-indicator status-red status-indicator-animated">
            <span class="status-indicator-circle"></span>
            <span class="status-indicator-circle"></span>
            <span class="status-indicator-circle"></span>
        </span>
    </div>
    <div class="col">
        <h2 class="page-title">
            Absensi Personil {{ date('d M Y') }}
        </h2>
        <div class="text-muted">
            <ul class="list-inline list-inline-dots mb-0">
                <li class="list-inline-item"><span class="text-red">Live</span></li>
                <li class="list-inline-item">Monitoring absensi personil</li>
            </ul>
        </div>
    </div>
    <div class="col-md-auto ms-auto d-print-none">
        <x-field.field-search />
    </div>
    <div class="col-md-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="{{ route('absensi.template') }}" class="btn btn-primary">
                Download Format
            </a>
            <a href="#" class="btn btn-success" onclick="openModalImport()">
                Import Excel
            </a>
            <a href="{{ route('track_maps') }}" class="btn">
                <!-- Download SVG icon from http://tabler-icons.io/i/Map-2 -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <line x1="18" y1="6" x2="18" y2="6.01"></line>
                    <path d="M18 13l-3.5 -5a4 4 0 1 1 7 0l-3.5 5"></path>
                    <polyline points="10.5 4.75 9 4 3 7 3 20 9 17 15 20 21 17 21 15"></polyline>
                    <line x1="9" y1="4" x2="9" y2="17"></line>
                    <line x1="15" y1="15" x2="15" y2="20"></line>
                </svg>
                Trak Posisi
            </a>
            <a href="#" class="btn">
                <!-- Download SVG icon from http://tabler-icons.io/i/heart-rate-monitor -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <rect x="3" y="4" width="18" height="12" rx="1"></rect>
                    <path d="M7 20h10"></path>
                    <path d="M9 16v4"></path>
                    <path d="M15 16v4"></path>
                    <path d="M7 10h2l2 3l2 -6l1 3h3"></path>
                </svg>
                Full Screen
            </a>
        </div>
    </div>
</div>
<x-monitor.live-absensi :user="$user" />

<div class="modal modal-blur fade" id="modal-import" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-import" action="{{ route('absensi.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">File Excel</label>
                        <input type="file" class="form-control" name="file" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Batal
                </a>
                <a href="#" class="btn btn-primary ms-auto" onclick="document.getElementById('form-import').submit()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Import
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function openModalImport() {
        $('#modal-import').modal('show');
    }

    // Handle form submission with AJAX for better UX
    document.getElementById('form-import').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat import data');
        });
    });
</script>
@endsection