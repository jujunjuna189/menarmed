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
@endsection