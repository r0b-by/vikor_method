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
  .reset-container {
    min-height: 100vh;
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
  }

  .reset-card {
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

  .reset-card:hover {
    box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2); /* Stronger shadow on hover */
    transform: translateY(-10px); /* Slight lift on hover */
  }

  .reset-image {
    background-color: #FC6600;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .reset-image img {
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
    .reset-image {
      padding: 30px 0;
    }

    .form-section {
      padding: 30px 20px;
    }
  }
</style>

<div class="container reset-container" data-aos="fade-up">
  <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
      <div class="row reset-card">
        <!-- Left Image -->
        <div class="col-md-6 reset-image p-4">
          <img src="{{ asset('assets/img/logo.png') }}" alt="Logo SMK" class="img-fluid" />
        </div>

        <!-- Right Form -->
        <div class="col-md-6 form-section">
          <h4 class="text-center mb-4">Reset Kata Sandi</h4>

          <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
              <label for="email" class="form-label">Alamat Email</label>
              <input id="email" type="email"
                class="form-control @error('email') is-invalid @enderror"
                name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
              @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Kata Sandi Baru</label>
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
                Reset Kata Sandi
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
