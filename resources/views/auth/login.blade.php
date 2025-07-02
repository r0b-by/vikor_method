@extends('layouts.app')

@section('content')
@push('styles')
<style>
    nav.navbar {
        display: none !important;
    }
</style>
@endpush

<style>
    html, body {
        margin: 0;
        padding: 0;
        background-color: #0d0d0d !important;
        color: #f1f1f1;
        height: 100%;
        overflow-x: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .login-card {
        background: rgba(30, 30, 30, 0.9);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(255, 102, 0, 0.2);
        max-width: 900px;
        width: 100%;
        overflow: hidden;
        display: flex;
        flex-wrap: wrap;
        border: 1px solid #333;
    }

    .login-card:hover {
        box-shadow: 0 25px 45px rgba(255, 102, 0, 0.3);
        transform: translateY(-5px);
        transition: all 0.3s ease-in-out;
    }

    .login-image {
        background-color: #FC6600;
        flex: 1 1 40%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .login-image img {
        width: 80%;
        max-width: 300px;
        filter: drop-shadow(0 0 20px #fc6600);
    }

    .form-section {
        flex: 1 1 60%;
        padding: 3rem 2.5rem;
        background-color: transparent;
    }

    .form-section h4 {
        font-weight: bold;
        margin-bottom: 30px;
        color: #FC6600;
        text-align: center;
        font-size: 1.8rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        color: #f1f1f1;
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .form-control {
        background-color: #1f1f1f;
        color: #f1f1f1;
        border: 1px solid #444;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        width: 100%;
        font-size: 1rem;
    }

    .form-control::placeholder {
        color: #aaa;
        opacity: 1;
    }

    .form-control:focus {
        background-color: #1f1f1f;
        border-color: #FC6600;
        box-shadow: 0 0 0 0.25rem rgba(252, 102, 0, 0.25);
        color: #fff;
    }

    .btn-custom {
        background-color: #FC6600;
        border: none;
        color: white;
        padding: 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        margin-top: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-custom:hover {
        background-color: #e05500;
        transform: translateY(-2px);
    }

    .daftar-link {
        font-size: 0.95rem;
        color: #ccc;
        text-align: center;
        margin-top: 1.5rem;
    }

    .daftar-link a {
        color: #FC6600;
        font-weight: bold;
        text-decoration: none;
    }

    .daftar-link a:hover {
        color: #ff9933;
        text-decoration: underline;
    }

    .invalid-feedback {
        display: block;
        margin-top: 0.4rem;
        font-size: 0.85rem;
    }

    .form-check {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .form-check-input {
        width: 1.1em;
        height: 1.1em;
        margin-right: 0.5rem;
        background-color: #1f1f1f;
        border: 1px solid #444;
    }

    .form-check-input:checked {
        background-color: #FC6600;
        border-color: #FC6600;
    }

    .form-check-label {
        color: #f1f1f1;
        font-size: 0.95rem;
    }

    .forgot-password {
        text-align: center;
        margin: 1rem 0;
    }

    .forgot-password a {
        color: #FC6600;
        font-weight: 500;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .forgot-password a:hover {
        color: #ff9933;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .login-card {
            flex-direction: column;
            max-width: 100%;
            border-radius: 15px;
        }

        .login-image {
            flex: none;
            height: 180px;
            padding: 1.5rem;
        }

        .form-section {
            flex: none;
            padding: 2rem 1.5rem;
        }
        
        .form-section h4 {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .login-page {
            padding: 1rem;
        }
        
        .form-section {
            padding: 1.5rem 1rem;
        }
    }
</style>

<div class="login-page">
    <div class="login-card">
        <!-- Left Image -->
        <div class="login-image">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo SMK" />
        </div>

        <!-- Right Form -->
        <div class="form-section">
            <h4>Login Beasiswa</h4>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input id="email" type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        placeholder="email@example.com">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input id="password" type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password"
                        placeholder="••••••••">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Ingat Saya
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="btn btn-custom">Masuk</button>
                </div>

                <!-- Forgot Password -->
                @if (Route::has('password.request'))
                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                </div>
                @endif
            </form>

            <div class="daftar-link">
                Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection