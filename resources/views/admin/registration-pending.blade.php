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
        --warning-color: #f59e0b;
    }

    .pending-page {
        min-height: 100vh;
        background-color: var(--dark-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        font-family: 'Inter', sans-serif;
    }

    .pending-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        max-width: 800px;
        width: 100%;
        padding: 3rem 2.5rem;
        border: 1px solid rgba(148, 163, 184, 0.1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    .pending-title {
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: var(--text-color);
        font-size: 2rem;
        letter-spacing: -0.5px;
    }

    .pending-message {
        color: var(--text-color);
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .pending-status {
        color: var(--warning-color);
        font-weight: 600;
        font-size: 1.2rem;
        margin: 1rem 0;
    }

    .pending-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }

    .btn-primary {
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 1rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .btn-secondary {
        background: rgba(30, 41, 59, 0.7);
        border: 1px solid var(--input-border);
        color: var(--text-color);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 1rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-secondary:hover {
        background: rgba(30, 41, 59, 0.9);
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .pending-card {
            padding: 2rem 1.5rem;
        }
        
        .pending-title {
            font-size: 1.7rem;
        }
        
        .pending-message {
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .pending-page {
            padding: 1rem;
        }
        
        .pending-card {
            padding: 1.5rem 1rem;
        }
        
        .pending-title {
            font-size: 1.5rem;
        }
        
        .pending-actions {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .btn-primary, .btn-secondary {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="pending-page">
    <div class="pending-card">
        <h2 class="pending-title">Pendaftaran Anda Sedang Diproses</h2>
        
        <p class="pending-message">
            Terima kasih telah mendaftar beasiswa. Akun Anda saat ini berstatus:
        </p>
        
        <div class="pending-status">MENUNGGU PERSETUJUAN</div>
        
        <p class="pending-message">
            Anda akan dapat login dan mengakses dashboard setelah pendaftaran Anda disetujui oleh administrator.
            Mohon tunggu konfirmasi melalui email yang Anda daftarkan.
        </p>
        
        <div class="pending-actions">
            <a href="{{ route('login') }}" class="btn-primary">
                Kembali ke Halaman Login
            </a>
            <a href="{{ url('/') }}" class="btn-secondary">
                Kembali ke Halaman Utama
            </a>
        </div>
    </div>
</div>
@endsection