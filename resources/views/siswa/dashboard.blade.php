@extends('dashboard.layouts.dashboardmain')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Minimalis -->
    <div class="text-center mb-5" data-aos="fade-down">
        <h1 class="fw-bold text-gradient mb-2">Dashboard Siswa</h1>
        <p class="text-white-50">Pantau hasil seleksi dan profil Anda</p>
        <div class="divider mx-auto bg-primary"></div>
    </div>

    <!-- Card Profil Minimalis -->
    <div class="card glass-card mb-4" data-aos="fade-up">
        <div class="card-header bg-transparent border-bottom border-white-10">
            <h5 class="mb-0">
                <i class="fas fa-user me-2"></i>Profil Pengguna
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-item">
                        <span class="label">Nama</span>
                        <span class="value">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Email</span>
                        <span class="value">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Kelas</span>
                        <span class="value">{{ Auth::user()->kelas ?? '-' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <span class="label">Status</span>
                        <span class="badge {{ Auth::user()->status == 'active' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst(Auth::user()->status) }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="label">Bergabung</span>
                        <span class="value">{{ Auth::user()->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="text-end mt-3">
                <a href="{{ route('siswa.profile.edit-siswa', auth()->id()) }}" class="btn btn-sm btn-primary">
                    Edit Profil
                </a>
            </div>
        </div>
    </div>

    <!-- Card Hasil VIKOR -->
    <div class="card glass-card" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header bg-transparent border-bottom border-white-10">
            <h5 class="mb-0">
                <i class="fas fa-chart-pie me-2"></i>Hasil Seleksi
            </h5>
        </div>
        <div class="card-body">
            @if(!$hasilVikor)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Hasil seleksi belum tersedia
                </div>
            @else
                <div class="result-summary">
                    <div class="result-item bg-primary">
                        <div class="result-value">{{ number_format($hasilVikor->nilai_q, 4) }}</div>
                        <div class="result-label">Indeks VIKOR</div>
                    </div>
                    <div class="result-item bg-success">
                        <div class="result-value">#{{ $hasilVikor->ranking }}</div>
                        <div class="result-label">Peringkat</div>
                    </div>
                    <div class="result-item {{ $hasilVikor->status == 'Lulus' ? 'bg-success' : 'bg-danger' }}">
                        <div class="result-value">{{ $hasilVikor->status }}</div>
                        <div class="result-label">Status</div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="metric">
                        <label>Utility (S)</label>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: {{ ($hasilVikor->nilai_s * 100) }}%"></div>
                            <span>{{ number_format($hasilVikor->nilai_s, 4) }}</span>
                        </div>
                    </div>
                    <div class="metric">
                        <label>Regret (R)</label>
                        <div class="progress-bar-container">
                            <div class="progress-bar bg-warning" style="width: {{ ($hasilVikor->nilai_r * 100) }}%"></div>
                            <span>{{ number_format($hasilVikor->nilai_r, 4) }}</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-2 mt-4">
                    <a href="{{ route('siswa.cetak-hasil') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-print me-1"></i> Cetak
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .glass-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .text-gradient {
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    .divider {
        width: 80px;
        height: 2px;
        opacity: 0.5;
    }
    
    .info-item {
        margin-bottom: 1rem;
    }
    
    .info-item .label {
        display: block;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.6);
    }
    
    .info-item .value {
        font-weight: 500;
        color: white;
    }
    
    .result-summary {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .result-item {
        flex: 1;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        background: rgba(59, 130, 246, 0.2);
    }
    
    .result-value {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .result-label {
        font-size: 0.8rem;
        opacity: 0.8;
    }
    
    .metric {
        margin-bottom: 1rem;
    }
    
    .metric label {
        display: block;
        margin-bottom: 0.3rem;
        font-size: 0.9rem;
    }
    
    .progress-bar-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .progress-bar {
        height: 6px;
        background: #3b82f6;
        border-radius: 3px;
        flex-grow: 1;
    }
    
    .progress-bar-container span {
        font-size: 0.8rem;
        min-width: 60px;
        text-align: right;
    }
</style>
@endsection