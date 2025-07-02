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
        max-width: 900px;
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
        flex: 1 1 40%;
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

    .login-link {
        font-size: 0.95rem;
        color: #ccc;
        text-align: center;
        margin-top: 1.5rem;
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

    .invalid-feedback {
        display: block;
        margin-top: 0.4rem;
        font-size: 0.85rem;
    }

    @media (max-width: 768px) {
        .register-card {
            flex-direction: column;
            max-width: 100%;
            border-radius: 15px;
        }

        .register-image {
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
        .register-page {
            padding: 1rem;
        }
        
        .form-section {
            padding: 1.5rem 1rem;
        }
    }
</style>

<div class="register-page">
    <div class="register-card">
        <!-- Left Image -->
        <div class="register-image">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo SMK" />
        </div>

        <!-- Right Form -->
        <div class="form-section">
            <h4>Daftar Beasiswa</h4>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input id="name" type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        name="name" value="{{ old('name') }}" required autofocus
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input id="email" type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required
                        placeholder="Masukkan alamat email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- NIS -->
                <div class="form-group">
                    <label for="nis" class="form-label">Nomor Induk Siswa (NIS)</label>
                    <input id="nis" type="text"
                        class="form-control @error('nis') is-invalid @enderror"
                        name="nis" value="{{ old('nis') }}" required
                        placeholder="Masukkan NIS">
                    @error('nis')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Kelas -->
                <div class="form-group">
                    <label for="kelas" class="form-label">Kelas</label>
                    <input id="kelas" type="text"
                        class="form-control @error('kelas') is-invalid @enderror"
                        name="kelas" value="{{ old('kelas') }}" required
                        placeholder="Contoh: XII RPL 1">
                    @error('kelas')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Jurusan -->
                <div class="form-group">
                    <label for="jurusan" class="form-label">Jurusan</label>
                    <input id="jurusan" type="text"
                        class="form-control @error('jurusan') is-invalid @enderror"
                        name="jurusan" value="{{ old('jurusan') }}" required
                        placeholder="Contoh: Rekayasa Perangkat Lunak">
                    @error('jurusan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Academic Period Dropdown -->
                <div class="form-group">
                    <label for="academic_period" class="form-label">Tahun Ajaran & Semester</label>
                    <select id="academic_period" class="form-control @error('academic_period_combined') is-invalid @enderror" 
                        name="academic_period_combined" required>
                        <option value="">Pilih Tahun Ajaran & Semester</option>
                        @foreach ($academicPeriods as $period)
                            <option value="{{ $period->tahun_ajaran . '|' . $period->semester }}"
                                {{ old('academic_period_combined') == $period->tahun_ajaran . '|' . $period->semester ? 'selected' : '' }}>
                                {{ $period->tahun_ajaran }} - {{ $period->semester }}
                                @if($period->is_active) (Aktif) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('academic_period_combined')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="form-group">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea id="alamat" rows="3"
                        class="form-control @error('alamat') is-invalid @enderror"
                        name="alamat" required placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                    @error('alamat')
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
                        name="password" required
                        placeholder="Minimal 8 karakter">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="form-group">
                    <label for="password-confirm" class="form-label">Konfirmasi Kata Sandi</label>
                    <input id="password-confirm" type="password"
                        class="form-control" name="password_confirmation" required
                        placeholder="Ketik ulang kata sandi">
                </div>

                <div class="form-group">
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
@endsection