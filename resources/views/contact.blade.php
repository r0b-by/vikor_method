{{-- In contact.blade.php --}}
@extends('layouts.app')

@section('title', 'Contact Us - SPK VIKOR AI')

@section('content')
  <!-- Contact Page -->
  <div id="contact-page" class="page" data-aos="fade-in">
    <section id="contact">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
            <h2 class="display-5 fw-bold mb-4">Hubungi Kami</h2>
            <p class="mb-4">Untuk pertanyaan lebih lanjut tentang sistem SPK VIKOR AI atau permintaan demo, silakan hubungi kami melalui formulir berikut.</p>
            
            <div class="contact-form">
              <form id="contactForm">
                <div class="mb-3">
                  <label for="name" class="form-label">Nama Lengkap</label>
                  <input type="text" class="form-control" id="name" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                  <label for="subject" class="form-label">Subjek</label>
                  <input type="text" class="form-control" id="subject" required>
                </div>
                <div class="mb-3">
                  <label for="message" class="form-label">Pesan</label>
                  <textarea class="form-control" id="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                </button>
              </form>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-left">
            <div class="content-box h-100">
              <h3>Informasi Kontak</h3>
              <div class="d-flex mb-4">
                <div class="me-3">
                  <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                    <i class="fas fa-map-marker-alt text-primary"></i>
                  </div>
                </div>
                <div>
                  <h5 class="mb-1">Alamat</h5>
                  <p class="mb-0">Jl. Pendidikan No. 123, Kota Bandung, Jawa Barat</p>
                </div>
              </div>
              <div class="d-flex mb-4">
                <div class="me-3">
                  <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                    <i class="fas fa-phone-alt text-primary"></i>
                  </div>
                </div>
                <div>
                  <h5 class="mb-1">Telepon</h5>
                  <p class="mb-0">+62 22 1234 5678</p>
                </div>
              </div>
              <div class="d-flex mb-4">
                <div class="me-3">
                  <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                    <i class="fas fa-envelope text-primary"></i>
                  </div>
                </div>
                <div>
                  <h5 class="mb-1">Email</h5>
                  <p class="mb-0">info@smkprimaunggul.sch.id</p>
                </div>
              </div>
              <div class="d-flex">
                <div class="me-3">
                  <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                    <i class="fas fa-clock text-primary"></i>
                  </div>
                </div>
                <div>
                  <h5 class="mb-1">Jam Operasional</h5>
                  <p class="mb-0">Senin - Jumat: 08.00 - 16.00 WIB</p>
                </div>
              </div>
              
              <div class="mt-5">
                <h4 class="mb-3">Ikuti Kami</h4>
                <div class="d-flex gap-3">
                  <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2">
                    <i class="fab fa-facebook-f"></i>
                  </a>
                  <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2">
                    <i class="fab fa-twitter"></i>
                  </a>
                  <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2">
                    <i class="fab fa-instagram"></i>
                  </a>
                  <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2">
                    <i class="fab fa-youtube"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="p-0">
      <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.8003504833436!2d107.6182903152946!3d-6.907218769346039!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e639b0e0c3a5%3A0x3a3a3a3a3a3a3a3a!2sSMK%20Prima%20Unggul!5e0!3m2!1sen!2sid!4v1621234567890!5m2!1sen!2sid" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </section>
  </div>
@endsection