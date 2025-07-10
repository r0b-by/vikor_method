@extends('layouts.app')

@section('content')
  <!-- Home Page -->
  <div id="home-page" class="page active" data-aos="fade-in">
    <!-- Hero Section -->
   <section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-1 order-2" data-aos="fade-right">
                <div class="hero-content">
                    <h1 class="neon-text">SPK</h1>
                    <h2>Sistem Pendukung Keputusan Beasiswa</h2>
                    <p>Menggunakan algoritma VIKOR yang ditingkatkan dengan kecerdasan buatan untuk menentukan penerima beasiswa secara objektif dan akurat.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('login') }}" class="btn btn-primary glow-on-hover">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk Sistem
                        </a>
                        <a href="#" class="btn btn-outline-light glow-on-hover" onclick="showPage('about')">
                            <i class="fas fa-info-circle me-2"></i>Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-lg-2 order-1 mb-4 mb-lg-0" data-aos="fade-left">
                <div class="canvas-container">
                    <canvas id="blackHoleCanvas"></canvas>
                    <div class="canvas-overlay">
                        <h1 class="black-hole-title">VIKOR</h1>
                        <p class="black-hole-subtitle">VIšekriterijumsko KOmpromisno Rangiranje</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Features Preview Section -->
    <section id="features-preview" class="bg-dark">
      <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
          <h2 class="display-5 fw-bold mb-3">Fitur Unggulan Sistem</h2>
          <p class="lead">Teknologi mutakhir untuk pengambilan keputusan yang lebih baik</p>
        </div>
        
        <div class="row g-4">
          <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="content-box h-100">
              <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                  <i class="fas fa-brain text-primary fs-3"></i>
                </div>
                <h3 class="mb-0">AI Enhanced</h3>
              </div>
              <p>Algoritma VIKOR yang ditingkatkan dengan machine learning untuk hasil yang lebih akurat dan adaptif.</p>
              <a href="{{ route('features') }}" class="btn btn-sm btn-outline-light mt-3">Selengkapnya</a>
            </div>
          </div>
          
          <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="content-box h-100">
              <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                  <i class="fas fa-chart-line text-primary fs-3"></i>
                </div>
                <h3 class="mb-0">Analisis Real-time</h3>
              </div>
              <p>Proses perhitungan dan visualisasi data secara real-time dengan tampilan interaktif.</p>
              <a href="{{ route('features') }}" class="btn btn-sm btn-outline-light mt-3">Selengkapnya</a>
            </div>
          </div>
          
          <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="content-box h-100">
              <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                  <i class="fas fa-shield-alt text-primary fs-3"></i>
                </div>
                <h3 class="mb-0">Keamanan Data</h3>
              </div>
              <p>Sistem keamanan berlapis dengan enkripsi end-to-end untuk melindungi data sensitif.</p>
              <a href="{{ route('features') }}" class="btn btn-sm btn-outline-light mt-3">Selengkapnya</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <style>
   /* Canvas container styling */
    .canvas-container {
        width: 100%;
        height: 100%;
        min-height: 250px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #blackHoleCanvas {
        display: block;
        width: 100% !important;
        height: 100% !important;
        max-width: 100%;
        max-height: 100%;
        background-color: transparent;
    }

    .canvas-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        width: 100%;
        pointer-events: none;
        z-index: 10;
    }

    .black-hole-title {
        font-size: 3rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 0 0 10px rgba(0, 200, 255, 0.7),
                     0 0 20px rgba(0, 150, 255, 0.5),
                     0 0 30px rgba(0, 100, 255, 0.3);
        margin-bottom: 0.5rem;
        animation: pulse 3s infinite alternate;
    }

    .black-hole-subtitle {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.8);
        text-shadow: 0 0 5px rgba(0, 200, 255, 0.5);
    }

    @keyframes pulse {
        0% {
            text-shadow: 0 0 10px rgba(0, 200, 255, 0.7),
                         0 0 20px rgba(0, 150, 255, 0.5),
                         0 0 30px rgba(0, 100, 255, 0.3);
        }
        100% {
            text-shadow: 0 0 15px rgba(0, 230, 255, 0.8),
                         0 0 25px rgba(0, 180, 255, 0.6),
                         0 0 35px rgba(0, 130, 255, 0.4);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .black-hole-title {
            font-size: 2.5rem;
        }
        .black-hole-subtitle {
            font-size: 1rem;
        }
    }

    @media (max-width: 767.98px) {
        .black-hole-title {
            font-size: 2rem;
        }
        .canvas-container {
            min-height: 200px;
        }
    }

    @media (max-width: 575.98px) {
        .black-hole-title {
            font-size: 1.5rem;
        }
        .black-hole-subtitle {
            font-size: 0.9rem;
        }
    }
  </style>
@endsection