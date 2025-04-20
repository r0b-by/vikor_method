@extends('layouts.app')

@section('content')
<!-- Hapus Navbar -->
@push('styles')
  <style>
    nav.navbar {
      display: none !important;
    }
  </style>
@endpush

<!-- AOS Animation CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />

<style>
  .register-container {
    min-height: 100vh;
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
  }

  .register-card {
    background-color: #f8f9fa;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15); /* Shadow effect */
    max-width: 1000px;
    width: 100%;
    margin-bottom: 20px;
    border: 1px solid #ddd; /* Border around the card */
    transition: all 0.3s ease; /* Smooth transition for hover effect */
  }

  .register-card:hover {
    box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2); /* Stronger shadow on hover */
    transform: translateY(-10px); /* Slight lift on hover */
  }

  .register-image {
    background-color: #FC6600;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .register-image img {
    width: 80%;
    max-width: 300px;
  }

  .form-section {
    padding: 40px;
    background-color: #ffffff;
  }

  .form-section h4 {
    font-weight: bold;
    margin-bottom: 25px;
    color: #FC6600;
  }

  .btn-custom {
    background-color: #FC6600;
    border: none;
    color: white;
  }

  .btn-custom:hover {
    background-color: #e05500;
  }

  .login-link {
    font-size: 0.95rem;
    color: #555;
  }

  .login-link a {
    color: #FC6600;
    font-weight: bold;
    text-decoration: none;
  }

  .login-link a:hover {
    text-decoration: underline;
  }

  @media (max-width: 768px) {
    .register-image {
      padding: 30px 0;
    }

    .form-section {
      padding: 30px 20px;
    }
  }
</style>

<div class="container register-container" data-aos="fade-up">
  <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
      <div class="row register-card">
        <!-- Left Image -->
        <div class="col-md-6 register-image p-4">
          <img src="{{ asset('assets/img/logo.png') }}" alt="Logo SMK" class="img-fluid" />
        </div>

        <!-- Right Form -->
        <div class="col-md-6 form-section">
          <h4 class="text-center mb-4">Daftar Beasiswa</h4>

          <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
              <label for="name" class="form-label">Nama Lengkap</label>
              <input id="name" type="text"
                class="form-control @error('name') is-invalid @enderror"
                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
              @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Alamat Email</label>
              <input id="email" type="email"
                class="form-control @error('email') is-invalid @enderror"
                name="email" value="{{ old('email') }}" required autocomplete="email">
              @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Kata Sandi</label>
              <input id="password" type="password"
                class="form-control @error('password') is-invalid @enderror"
                name="password" required autocomplete="new-password">
              @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="mb-3">
              <label for="password-confirm" class="form-label">Konfirmasi Kata Sandi</label>
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-custom">
                Daftar
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Link ke halaman login -->
      <div class="text-center login-link">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
      </div>
    </div>
  </div>
</div>

<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>
@endsection
