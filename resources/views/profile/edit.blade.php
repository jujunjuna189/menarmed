@extends('layouts.app_template')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h2 class="h1 mb-1">Profile &amp; Account</h2>
        <div class="text-muted">Kelola data diri dan keamanan akun Anda.</div>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    <div class="d-flex">
        <div>{{ session('success') }}</div>
    </div>
    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
</div>
@endif

<form action="{{ route('profile.update') }}" method="POST">
    @csrf
    @method('PATCH')

    <div class="row row-cards">
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    @if ($user->thumbnail)
                    <span class="avatar avatar-xl mb-3" style="background-image: url('{{ asset($user->thumbnail) }}')"></span>
                    @else
                    <span class="avatar avatar-xl mb-3 bg-blue-lt text-blue">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </span>
                    @endif
                    <h3 class="mb-1">{{ $user->name }}</h3>
                    <div class="text-muted">{{ $user->jabatan ?: 'Jabatan belum diisi' }}</div>
                    <div class="text-muted small mt-1">{{ $user->satuan ?: 'Satuan belum diisi' }}</div>
                </div>
                <div class="card-body border-top">
                    <div class="row g-2">
                        <div class="col-5 text-muted">Email</div>
                        <div class="col-7 text-end text-truncate">{{ $user->email }}</div>
                        <div class="col-5 text-muted">Pangkat</div>
                        <div class="col-7 text-end">{{ $user->pangkat ?: '-' }}</div>
                        <div class="col-5 text-muted">Korp</div>
                        <div class="col-7 text-end">{{ $user->korp ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Informasi Akun</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Nama lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pangkat</label>
                            <input type="text" name="pangkat" value="{{ old('pangkat', $user->pangkat) }}" class="form-control @error('pangkat') is-invalid @enderror">
                            @error('pangkat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Korp</label>
                            <input type="text" name="korp" value="{{ old('korp', $user->korp) }}" class="form-control @error('korp') is-invalid @enderror">
                            @error('korp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sumber PA</label>
                            <input type="text" name="sumber_pa" value="{{ old('sumber_pa', $user->sumber_pa) }}" class="form-control @error('sumber_pa') is-invalid @enderror">
                            @error('sumber_pa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Satuan</label>
                            <input type="text" name="satuan" value="{{ old('satuan', $user->satuan) }}" class="form-control @error('satuan') is-invalid @enderror">
                            @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" class="form-control @error('jabatan') is-invalid @enderror">
                            @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tempat lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $user->tempat_lahir) }}" class="form-control @error('tempat_lahir') is-invalid @enderror">
                            @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal lahir</label>
                            <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $user->tgl_lahir) }}" class="form-control @error('tgl_lahir') is-invalid @enderror">
                            @error('tgl_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Agama</label>
                            <input type="text" name="agama" value="{{ old('agama', $user->agama) }}" class="form-control @error('agama') is-invalid @enderror">
                            @error('agama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-0">
                            <label class="form-label">Golongan darah</label>
                            <input type="text" name="gol_darah" value="{{ old('gol_darah', $user->gol_darah) }}" class="form-control @error('gol_darah') is-invalid @enderror">
                            @error('gol_darah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-0">
                            <label class="form-label">Senjata</label>
                            <input type="text" name="senjata" value="{{ old('senjata', $user->senjata) }}" class="form-control @error('senjata') is-invalid @enderror">
                            @error('senjata')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Ubah Password</h3>
                </div>
                <div class="card-body">
                    <div class="text-muted mb-3">Kosongkan bagian ini jika tidak ingin mengubah password.</div>
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label">Password saat ini</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label">Password baru</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Konfirmasi password</label>
                            <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M6 4h10l4 4v12h-16v-16z"></path>
                        <circle cx="12" cy="14" r="2"></circle>
                        <polyline points="14 4 14 8 8 8 8 4"></polyline>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
