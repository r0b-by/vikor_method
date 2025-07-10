@extends('layouts.app')

@section('title', 'Our Features - SPK VIKOR AI')

@section('content')
  <!-- Features Page -->
  <div id="features-page" class="page" data-aos="fade-in">
    <section id="features">
      <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
          <h2 class="display-5 fw-bold mb-3">Fitur Lengkap Sistem</h2>
          <p class="lead">Teknologi mutakhir untuk pengambilan keputusan yang lebih baik</p>
        </div>
        
        <div class="row g-4">
          <div class="col-md-6" data-aos="fade-up">
            <div class="content-box h-100">
              <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                  <i class="fas fa-brain text-primary fs-3"></i>
                </div>
                <h3 class="mb-0">AI Enhanced Algorithm</h3>
              </div>
              <p>Algoritma VIKOR yang ditingkatkan dengan machine learning untuk hasil yang lebih akurat dan adaptif. Sistem dapat belajar dari data historis untuk meningkatkan kualitas keputusan.</p>
              <ul class="mt-3">
                <li>Optimasi bobot otomatis</li>
                <li>Adaptasi terhadap perubahan kriteria</li>
                <li>Prediksi berbasis data historis</li>
              </ul>
            </div>
          </div>
          
          <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="content-box h-100">
              <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                  <i class="fas fa-chart-line text-primary fs-3"></i>
                </div>
                <h3 class="mb-0">Analisis Real-time</h3>
              </div>
              <p>Proses perhitungan dan visualisasi data secara real-time dengan tampilan interaktif. Hasil perhitungan dapat langsung dilihat dan dievaluasi.</p>
              <ul class="mt-3">
                <li>Dashboard analitik interaktif</li>
                <li>Visualisasi data 3D</li>
                <li>Ekspor laporan otomatis</li>
              </ul>
            </div>
          </div>
          
          <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="content-box h-100">
              <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                  <i class="fas fa-shield-alt text-primary fs-3"></i>
                </div>
                <h3 class="mb-0">Keamanan Data</h3>
              </div>
              <p>Sistem keamanan berlapis dengan enkripsi end-to-end untuk melindungi data sensitif. Setiap akses ke sistem diawasi dan dicatat.</p>
              <ul class="mt-3">
                <li>Enkripsi AES-256</li>
                <li>Autentikasi multi-faktor</li>
                <li>Audit log lengkap</li>
              </ul>
            </div>
          </div>
          
          <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="content-box h-100">
              <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                  <i class="fas fa-users-cog text-primary fs-3"></i>
                </div>
                <h3 class="mb-0">Manajemen Pengguna</h3>
              </div>
              <p>Sistem manajemen pengguna yang komprehensif dengan berbagai level akses sesuai kebutuhan institusi.</p>
              <ul class="mt-3">
                <li>Multi-level user roles</li>
                <li>Custom permission system</li>
                <li>Activity monitoring</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-dark">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 order-lg-1 order-2" data-aos="fade-right">
            <div class="content-box">
              <h3>Teknologi Modern</h3>
              <p>Sistem kami dibangun dengan teknologi terkini untuk memastikan performa optimal dan pengalaman pengguna yang mulus.</p>
              
              <div class="row mt-4">
                <div class="col-6">
                  <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-2">
                      <i class="fab fa-laravel text-primary"></i>
                    </div>
                    <span>Laravel Framework</span>
                  </div>
                  <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-2">
                      <i class="fab fa-js text-primary"></i>
                    </div>
                    <span>JavaScript ES6+</span>
                  </div>
                </div>
                <div class="col-6">
                  <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-2">
                      <i class="fab fa-python text-primary"></i>
                    </div>
                    <span>Python AI</span>
                  </div>
                  <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-2">
                      <i class="fas fa-database text-primary"></i>
                    </div>
                    <span>MySQL Database</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 order-lg-2 order-1 mb-4 mb-lg-0" data-aos="fade-left">
            <img src="{{ asset('assets/img/sistem-penunjang-keputusan.png') }}" alt="Technology Stack" class="img-fluid">
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection