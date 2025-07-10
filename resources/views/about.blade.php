{{-- In about.blade.php --}}
@extends('layouts.app') {{-- This tells Laravel to use layouts/app.blade.php as the parent layout --}}

@section('title', 'About Us - SPK VIKOR AI') {{-- Optional: Override the title defined in the layout --}}

@section('content')
  <!-- About Page -->
  <div id="about-page" class="page" data-aos="fade-in">
    <section id="about">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
            <img src="{{ asset('assets/img/beasiswa.png') }}" alt="AI Process" class="img-fluid rounded">
          </div>
          <div class="col-lg-6" data-aos="fade-left">
            <h2 class="display-5 fw-bold mb-4">Bagaimana Sistem Kami Bekerja?</h2>
            <div class="d-flex mb-3">
              <div class="me-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                  <i class="fas fa-database text-primary fs-4"></i>
                </div>
              </div>
              <div>
                <h4>Koleksi Data</h4>
                <p>Mengumpulkan data siswa meliputi akademik, ekonomi, dan non-akademik.</p>
              </div>
            </div>
            <div class="d-flex mb-3">
              <div class="me-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                  <i class="fas fa-cogs text-primary fs-4"></i>
                </div>
              </div>
              <div>
                <h4>Proses VIKOR</h4>
                <p>Menghitung nilai utilitas dan penyesalan untuk menentukan solusi kompromi.</p>
              </div>
            </div>
            <div class="d-flex mb-3">
              <div class="me-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                  <i class="fas fa-robot text-primary fs-4"></i>
                </div>
              </div>
              <div>
                <h4>AI Enhancement</h4>
                <p>Algoritma machine learning meningkatkan akurasi perhitungan VIKOR.</p>
              </div>
            </div>
            <div class="d-flex">
              <div class="me-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                  <i class="fas fa-chart-pie text-primary fs-4"></i>
                </div>
              </div>
              <div>
                <h4>Hasil & Rekomendasi</h4>
                <p>Menghasilkan ranking siswa berdasarkan kriteria yang ditentukan.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-dark">
      <div class="container">
        <div class="row">
          <div class="col-lg-6" data-aos="fade-right">
            <div class="content-box">
              <h3>Metode VIKOR</h3>
              <p>VIKOR (VIšekriterijumsko KOmpromisno Rangiranje) adalah metode pengambilan keputusan multikriteria yang mencari solusi kompromi terbaik dari beberapa alternatif berdasarkan kedekatan terhadap solusi ideal.</p>
              <p>Metode ini sangat cocok untuk sistem seleksi beasiswa karena mampu mempertimbangkan berbagai kriteria sekaligus dan menemukan solusi yang paling mendekati ideal.</p>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-left">
            <div class="content-box">
              <h3>Integrasi AI</h3>
              <p>Sistem kami meningkatkan metode VIKOR tradisional dengan integrasi kecerdasan buatan untuk:</p>
              <ul>
                <li>Optimasi bobot kriteria secara dinamis</li>
                <li>Prediksi tren penerima beasiswa</li>
                <li>Deteksi anomali data</li>
                <li>Visualisasi hasil yang interaktif</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection