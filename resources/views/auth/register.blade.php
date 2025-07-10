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
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');
    
    :root {
        --primary-color: #2563eb;
        --secondary-color: #7c3aed;
        --dark-bg: #0f172a;
        --card-bg: rgba(15, 23, 42, 0.9);
        --text-color: #e2e8f0;
        --input-bg: rgba(30, 41, 59, 0.7);
        --input-border: rgba(148, 163, 184, 0.3);
        --border-radius: 12px;
    }

    html, body {
        margin: 0;
        padding: 0;
        background-color: var(--dark-bg) !important;
        color: var(--text-color);
        height: 100%;
        overflow-x: hidden;
        font-family: 'Inter', sans-serif;
    }

    .register-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .register-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        max-width: 900px;
        width: 100%;
        overflow: hidden;
        display: flex;
        flex-wrap: wrap;
        border: 1px solid rgba(148, 163, 184, 0.1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .register-image {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.05), rgba(124, 58, 237, 0.05));
        flex: 1 1 40%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        min-height: 200px;
    }

    .register-image img {
        width: 100%;
        max-width: 300px;
        min-width: 150px;
        height: auto;
        object-fit: contain;
        filter: brightness(1.05);
        transition: all 0.3s ease;
    }

    .form-section {
        flex: 1 1 60%;
        padding: 3rem 2.5rem;
        background-color: transparent;
    }

    .form-section h4 {
        font-weight: 600;
        margin-bottom: 30px;
        color: var(--text-color);
        text-align: center;
        font-size: 1.8rem;
        letter-spacing: -0.5px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        color: var(--text-color);
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .form-control {
        background-color: var(--input-bg);
        color: #ffffff !important; /* Putih solid untuk kontras maksimal */
        caret-color: var(--primary-color)
        border: 1px solid var(--input-border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        width: 100%;
        font-size: 1rem;
        transition: all 0.2s ease;
    }

    .form-control:not(:placeholder-shown) {
        border-color: rgba(37, 99, 235, 0.5);
    }

    .form-control.is-typing {
        background-color: rgba(30, 41, 59, 0.9);
    }

    .form-control::placeholder {
        color: rgba(226, 232, 240, 0.5);
    }

    .form-control:focus {
        background-color: var(--input-bg);
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%232563eb' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 12px;
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .btn-custom {
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        border: none;
        color: white;
        padding: 0.75rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 1rem;
        width: 100%;
        margin-top: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-custom:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .login-link {
        font-size: 0.95rem;
        color: rgba(226, 232, 240, 0.7);
        text-align: center;
        margin-top: 1.5rem;
    }

    .login-link a {
        color: var(--primary-color);
        font-weight: 500;
        text-decoration: none;
    }

    .invalid-feedback {
        display: block;
        margin-top: 0.4rem;
        font-size: 0.85rem;
        color: #f87171;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .register-image img {
            max-width: 250px;
        }
        
        .form-section {
            padding: 2.5rem 2rem;
        }
    }

    @media (max-width: 768px) {
        .register-card {
            flex-direction: column;
            max-width: 100%;
        }

        .register-image {
            flex: none;
            height: auto;
            padding: 2rem;
        }

        .register-image img {
            max-width: 200px;
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
        
        .register-image {
            padding: 1.5rem;
        }
        
        .register-image img {
            max-width: 180px;
        }
        
        .form-section {
            padding: 1.5rem 1rem;
        }
        
        .form-section h4 {
            font-size: 1.4rem;
        }
    }

    @media (max-width: 400px) {
        .register-image img {
            max-width: 150px;
        }
        
        textarea.form-control {
            min-height: 80px;
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