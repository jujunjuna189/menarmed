@extends('layouts.app')
@section('content')
<div class="page page-center">
    <div class="container-tight">
        <form class="card card-md" action="{{ route('login') }}" method="POST" autocomplete="off">
            @csrf
            <div class="card-body">
                <h2 class="text-center mb-4 h1">Login</h2>
                <div class="mb-3">
                    <label class="form-label">Nrp</label>
                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-2">
                    @if (Route::has('password.request'))
                    <label class="form-label">
                        Password
                        <span class="form-label-description">
                            <a href="{{ route('password.request') }}">Saya lupa password</a>
                        </span>
                    </label>
                    @endif
                    <div class="input-group input-group-flat">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        <span class="input-group-text">
                            <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                                <!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="12" cy="12" r="2" />
                                    <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                </svg>
                            </a>
                        </span>

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            {{ __('Ingat Saya') }}
                        </label>
                    </div>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-dark w-100">Masuk</button>
                </div>
            </div>
        </form>
        <div class="text-center text-muted mt-3">
            Belum punya akun ? <a href="{{ route('register') }}" tabindex="-1">Daftar</a>
        </div>
    </div>
</div>
@endsection