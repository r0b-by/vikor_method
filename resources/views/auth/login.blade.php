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

    .login-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .login-card {
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

    .login-image {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.05), rgba(124, 58, 237, 0.05));
        flex: 1 1 40%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        min-height: 200px;
    }

    .login-image img {
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

    .daftar-link {
        font-size: 0.95rem;
        color: rgba(226, 232, 240, 0.7);
        text-align: center;
        margin-top: 1.5rem;
    }

    .daftar-link a {
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

    .form-check {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .form-check-input {
        width: 1.1em;
        height: 1.1em;
        margin-right: 0.5rem;
        background-color: var(--input-bg);
        border: 1px solid var(--input-border);
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .form-check-label {
        color: var(--text-color);
        font-size: 0.95rem;
    }

    .forgot-password {
        text-align: center;
        margin: 1rem 0;
    }

    .forgot-password a {
        color: var(--primary-color);
        font-weight: 500;
        text-decoration: none;
        font-size: 0.9rem;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .login-image img {
            max-width: 250px;
        }
    }

    @media (max-width: 768px) {
        .login-card {
            flex-direction: column;
            max-width: 100%;
        }

        .login-image {
            flex: none;
            height: auto;
            padding: 2rem;
        }

        .login-image img {
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
        .login-page {
            padding: 1rem;
        }
        
        .login-image {
            padding: 1.5rem;
        }
        
        .login-image img {
            max-width: 180px;
        }
        
        .form-section {
            padding: 1.5rem 1rem;
        }
    }

    @media (max-width: 400px) {
        .login-image img {
            max-width: 150px;
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

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input id="email" type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        placeholder="email@example.com"
                        oninput="this.classList.add('is-typing')"
                        onblur="this.classList.remove('is-typing')">
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
                        placeholder="••••••••"
                        oninput="this.classList.add('is-typing')"
                        onblur="this.classList.remove('is-typing')">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- [Bagian lainnya tetap sama] -->
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

@push('scripts')
<script>
    // Untuk memberikan feedback visual saat mengetik
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('input', function() {
            if(this.value.length > 0) {
                this.classList.add('has-value');
            } else {
                this.classList.remove('has-value');
            }
        });
    });
</script>
@endpush
@endsection