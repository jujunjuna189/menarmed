@extends('layouts.app_template')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="page-title mb-0">Izin Aktif</h2>
    <a href="{{ route('home') }}" class="btn btn-outline-secondary">Kembali</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead><tr>
                <th>Kategori</th><th>Nama</th><th>Waktu Keluar</th><th>Waktu Masuk</th><th>Tujuan</th><th>Jenis Kendaraan</th>
            </tr></thead>
            <tbody>
                @forelse($permits as $permit)
                <tr>
                    <td><span class="badge bg-secondary-lt">{{ $permit->category }}</span></td>
                    <td>{{ $permit->name ?? 'Pengguna tidak ditemukan' }}</td>
                    <td class="text-nowrap">{{ \Carbon\Carbon::parse($permit->keluar)->format('d/m/Y H:i') }}</td>
                    <td class="text-nowrap">{{ $permit->masuk ? \Carbon\Carbon::parse($permit->masuk)->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $permit->tujuan ?: '-' }}</td>
                    <td>{{ $permit->jenis_kendaraan ?: '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada izin aktif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $permits->links() }}</div>
</div>
@endsection
