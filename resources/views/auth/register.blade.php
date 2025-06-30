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

  .register-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
  }

  .register-card {
    background: rgba(30, 30, 30, 0.9);
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(255, 102, 0, 0.2);
    max-width: 768px; /* Diperkecil dari 960px ke 768px */
    width: 100%;
    overflow: hidden;
    display: flex;
    flex-wrap: wrap;
    border: 1px solid #333;
  }

  .register-card:hover {
    box-shadow: 0 25px 45px rgba(255, 102, 0, 0.3);
    transform: translateY(-5px);
    transition: all 0.3s ease-in-out;
  }

  .register-image {
    background-color: #FC6600;
    flex: 1 1 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
  }

  .register-image img {
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
  border-radius: 8px;
  padding: 0.75rem 1rem;
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
    padding: 0.75rem;
    border-radius: 8px;
    font-weight: 600;
  }

  .btn-custom:hover {
    background-color: #e05500;
  }

  .login-link {
    font-size: 0.95rem;
    color: #ccc;
    text-align: center;
    margin-top: 1rem;
  }

  .login-link a {
    color: #FC6600;
    font-weight: bold;
    text-decoration: none;
  }

  .login-link a:hover {
    color: #ff9933;
    text-decoration: underline;
  }

  @media (max-width: 768px) {
    .register-card {
      flex-direction: column;
      max-width: 100%;
      border-radius: 0;
      height: auto;
    }

    .register-image {
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

<div class="register-page">
  <div class="register-card" data-aos="fade-up" data-aos-duration="800">
    <!-- Left Image -->
    <div class="register-image" data-aos="fade-right" data-aos-delay="200">
      <img src="{{ asset('assets/img/logo.png') }}" alt="Logo SMK" />
    </div>

    <!-- Right Form -->
    <div class="form-section">
      <h4 data-aos="fade-down" data-aos-delay="300">Daftar Beasiswa</h4>

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3" data-aos="fade-up" data-aos-delay="400">
          <label for="name" class="form-label">Nama Lengkap</label>
          <input id="name" type="text"
            class="form-control @error('name') is-invalid @enderror"
            name="name" value="{{ old('name') }}" required autofocus>
          @error('name')
            <span class="invalid-feedback text-danger" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="mb-3" data-aos="fade-up" data-aos-delay="500">
          <label for="email" class="form-label">Alamat Email</label>
          <input id="email" type="email"
            class="form-control @error('email') is-invalid @enderror"
            name="email" value="{{ old('email') }}" required>
          @error('email')
            <span class="invalid-feedback text-danger" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="mb-3" data-aos="fade-up" data-aos-delay="550">
          <label for="nis" class="form-label">Nomor Induk Siswa (NIS)</label>
          <input id="nis" type="text"
            class="form-control @error('nis') is-invalid @enderror"
            name="nis" value="{{ old('nis') }}" required>
          @error('nis')
            <span class="invalid-feedback text-danger" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="mb-3" data-aos="fade-up" data-aos-delay="600">
          <label for="kelas" class="form-label">Kelas</label>
          <input id="kelas" type="text"
            class="form-control @error('kelas') is-invalid @enderror"
            name="kelas" value="{{ old('kelas') }}" required>
          @error('kelas')
            <span class="invalid-feedback text-danger" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="mb-3" data-aos="fade-up" data-aos-delay="650">
          <label for="jurusan" class="form-label">Jurusan</label>
          <input id="jurusan" type="text"
            class="form-control @error('jurusan') is-invalid @enderror"
            name="jurusan" value="{{ old('jurusan') }}" required>
          @error('jurusan')
            <span class="invalid-feedback text-danger" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="mb-3" data-aos="fade-up" data-aos-delay="700">
          <label for="alamat" class="form-label">Alamat</label>
          <textarea id="alamat"
            class="form-control @error('alamat') is-invalid @enderror"
            name="alamat" required>{{ old('alamat') }}</textarea>
          @error('alamat')
            <span class="invalid-feedback text-danger" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="mb-3" >
          <label for="password" class="form-label">Kata Sandi</label>
          <input id="password" type="password"
            class="form-control @error('password') is-invalid @enderror"
            name="password" required>
          @error('password')
            <span class="invalid-feedback text-danger" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="mb-3">
          <label for="password-confirm" class="form-label">Konfirmasi Kata Sandi</label>
          <input id="password-confirm" type="password"
            class="form-control" name="password_confirmation" required>
        </div>

        <div class="d-grid mb-3">
          <button type="submit" class="btn btn-custom">
            Daftar
          </button>
        </div>
      </form>

      <div class="login-link">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    once: true,
    duration: 800,
    easing: 'ease-out-cubic',
  });
</script>
@endsection
