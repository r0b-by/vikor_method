@extends('layouts.app')

@section('content')
@push('styles')
    <style>
        nav.navbar {
            display: none !important;
        }
    </style>
@endpush

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />

<style>
    html, body {
        margin: 0;
        padding: 0;
        background-color: #0d0d0d !important;
        color: #f1f1f1;
        height: 100%;
        overflow-x: hidden;
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
        max-width: 960px;
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
        flex: 1 1 50%;
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
        flex: 1 1 50%;
        padding: 3rem 2rem;
        background-color: transparent;
    }

    .form-section h4 {
        font-weight: bold;
        margin-bottom: 25px;
        color: #FC6600;
        text-align: center;
    }

    .form-label {
        color: #f1f1f1;
    }

    .form-control {
        background-color: #1f1f1f;
        color: #f1f1f1; /* Teks terlihat jelas */
        border: 1px solid #444;
        border-radius: 8px; /* Tambahkan border-radius agar konsisten */
        padding: 0.75rem 1rem; /* Tambahkan padding agar konsisten */
    }

    .form-control::placeholder {
        color: #aaa; /* Placeholder lebih terang di dark mode */
    }

    .form-control:focus {
        background-color: #1f1f1f;
        border-color: #FC6600;
        box-shadow: 0 0 0 0.25rem rgba(252, 102, 0, 0.25);
        color: #fff; /* Pastikan tetap terlihat saat focus */
    }

    .btn-custom {
        background-color: #FC6600;
        border: none;
        color: white;
        padding: 0.75rem; /* Tambahkan padding agar konsisten */
        border-radius: 8px; /* Tambahkan border-radius agar konsisten */
        font-weight: 600; /* Tambahkan font-weight agar konsisten */
    }

    .btn-custom:hover {
        background-color: #e05500;
    }

    .daftar-link {
        font-size: 0.95rem;
        color: #ccc;
        text-align: center;
        margin-top: 1rem;
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

    .form-check-label {
        color: #f1f1f1; /* Pastikan label "Ingat Saya" terlihat jelas */
    }

    .text-center a {
        color: #FC6600; /* Pastikan link "Lupa kata sandi" berwarna oranye */
    }

    .text-center a:hover {
        color: #ff9933; /* Efek hover untuk link "Lupa kata sandi" */
    }


    @media (max-width: 768px) {
        .login-card {
            flex-direction: column;
            max-width: 100%;
            border-radius: 0;
            height: auto; /* Ubah dari 100vh agar tidak memotong konten di perangkat kecil */
        }

        .login-image {
            flex: none;
            height: 200px;
            padding: 1rem;
        }

        .form-section {
            flex: none;
            padding: 2rem 1rem;
        }
    }
</style>

<div class="login-page">
    <div class="login-card" data-aos="fade-up" data-aos-duration="800">
        <div class="login-image" data-aos="fade-right" data-aos-delay="200">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo SMK" />
        </div>

        <div class="form-section">
            <h4 data-aos="fade-down" data-aos-delay="300">Login Beasiswa</h4>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3" data-aos="fade-up" data-aos-delay="400">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input id="email" type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        placeholder="email@example.com">
                    @error('email')
                        <span class="invalid-feedback text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3" data-aos="fade-up" data-aos-delay="500">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input id="password" type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password"
                        placeholder="••••••••">
                    @error('password')
                        <span class="invalid-feedback text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3 form-check" data-aos="fade-up" data-aos-delay="600">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Ingat Saya
                    </label>
                </div>

                <div class="d-grid mb-3" data-aos="zoom-in" data-aos-delay="700">
                    <button type="submit" class="btn btn-custom">Masuk</button>
                </div>

                @if (Route::has('password.request'))
                <div class="text-center" data-aos="fade-up" data-aos-delay="800">
                    <a class="text-decoration-none" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                </div>
                @endif
            </form>

            <div class="daftar-link" data-aos="fade-up" data-aos-delay="900">
                Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        once: true,
        duration: 800,
        easing: 'ease-out-cubic'
    });
</script>
@endsection