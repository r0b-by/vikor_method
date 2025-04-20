<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SPK VIKOR - Beasiswa SMK Prima Unggul</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
/* =======================
   GLOBAL STYLES
======================= */
body {
  background: #f9f9f9;
  color: #333;
  font-family: 'Poppins', sans-serif;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

section {
  padding: 80px 0;
}

h3 {
  font-weight: 600;
  margin-bottom: 25px;
  font-size: 1.8rem;
}

/* =======================
   NAVBAR
======================= */
.navbar {
  background-color: #FC6600;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  transition: background-color 0.3s ease, color 0.3s ease;
}

.navbar .navbar-brand {
  color: white;
}

/* Offcanvas Header & Footer */
.navbar, .offcanvas-header, footer {
  background-color: #FC6600;
  color: white;
}

.offcanvas-title,
.navbar .navbar-brand {
  color: white;
}

.offcanvas .nav-link {
  color: #333;
}
.offcanvas .nav-link:hover {
  color: #FC6600;
}

/* =======================
   HERO SECTION
======================= */
.hero {
  padding-top: 7rem;
  padding-bottom: 5rem;
  background-color: #ffffff;
  border-radius: 2rem;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
}

.hero .row {
  background-color: #ffffff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.hero h1 {
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
  line-height: 1.2;
}

/* =======================
   BUTTON STYLES
======================= */
.btn-custom, .btn-outline-light {
  border-radius: 30px;
  font-weight: bold;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.btn-custom {
  background-color: #FC6600;
  color: white;
}

.btn-custom:hover {
  background-color: #e05500;
  transform: translateY(-2px);
}

.btn-outline-light {
  border-color: #FC6600;
  color: #FC6600;
}

.btn-outline-light:hover {
  background-color: #FC6600;
  color: white;
  transform: translateY(-2px);
}

/* =======================
   CONTENT BOX
======================= */
.content-box {
  background-color: #FC6600;
  color: white;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.content-box:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
}

/* =======================
   IMAGES
======================= */
.img-fluid {
  max-width: 100%;
  height: auto;
  border-radius: 10px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

/* =======================
   FOOTER
======================= */
footer {
  padding: 20px 0;
  border-top: 4px solid #ffa040;
  box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.1);
}

/* =======================
   PARTICLES BACKGROUND
======================= */
#particles-js {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  z-index: -1;
  pointer-events: none;
}

/* =======================
   DARK MODE
======================= */
body.dark-mode {
  background-color: #121212;
  color: #eee;
}

body.dark-mode .navbar,
body.dark-mode .offcanvas-header,
body.dark-mode footer {
  background-color: #1e1e1e;
  color: #ffa040;
  box-shadow: 0 2px 10px rgba(255, 255, 255, 0.05);
}

body.dark-mode .navbar .navbar-brand,
body.dark-mode .offcanvas-title {
  color: #ffa040;
}

body.dark-mode .offcanvas {
  background-color: #121212;
}

body.dark-mode .offcanvas .nav-link {
  color: #fff;
}
body.dark-mode .offcanvas .nav-link:hover {
  color: #ffa040;
}

body.dark-mode .btn-close {
  filter: invert(1);
}

body.dark-mode .hero {
  background-color: #1e1e1e;
  color: #ffa040;
}

body.dark-mode .hero .row {
  background-color: #2c2c2c;
  box-shadow: 0 8px 20px rgba(255, 255, 255, 0.1);
}

body.dark-mode .content-box {
  background-color: #333;
  color: #fff;
}

body.dark-mode .btn-custom {
  background-color: #ffa040;
  color: #000;
}

body.dark-mode .btn-outline-light {
  color: #ffa040;
  border-color: #ffa040;
}

body.dark-mode .btn-outline-light:hover {
  background-color: #ffa040;
  color: #000;
}

body.dark-mode a.nav-link {
  color: #fff;
}
body.dark-mode a.nav-link:hover {
  color: #ffa040;
}

/* Hamburger Icon in Dark Mode */
body.dark-mode .navbar-toggler-icon {
  background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='white' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
}
body.dark-mode .navbar-toggler {
  background-color: transparent;
  border: none;
}

/* =======================
   RESPONSIVE
======================= */
@media (max-width: 767px) {
  .hero {
    padding: 80px 0;
  }
}
</style>

</head>
<body>

  <!-- Navbar -->
<nav id="navbar" class="navbar navbar-light fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="{{ asset('assets/img/logo-navbar.png') }}" alt="Logo" width="40" class="me-2 rounded-circle">
      <span class="fw-bold text-white">SMK PRIMA UNGGUL</span>
    </a>
    <div class="d-flex align-items-center">
      <button id="darkModeToggle" class="btn btn-sm btn-outline-light me-2">🌙</button> <!-- Toggle Dark Mode -->
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
        aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#about">About</a>
          </li>
          <li class="nav-item mt-3">
            <a href="{{ route('login') }}" class="btn btn-custom w-100 mb-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-light w-100">Daftar</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
<div id="particles-js"></div>

<!-- Hero Section -->
<section class="hero-section" style="padding-top: 150px;">
  <div class="container">
    <div class="row gy-4 align-items-center" data-aos="fade-up">
      <!-- Kolom Gambar -->
      <div class="col-md-6 text-center">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Sekolah" width="400" class="img-fluid" />
      </div>
      <!-- Kolom Teks -->
      <div class="col-md-6 text-md-start text-center">
        <h1 class="display-5 fw-bold text-uppercase">Sistem Pendukung Keputusan</h1>
        <h2 class="h4 mb-3">Penerimaan Beasiswa SMK Prima Unggul</h2>
        <p class="lead">Menggunakan Metode <strong>VIKOR</strong> untuk menentukan siswa yang layak menerima beasiswa secara objektif dan tepat sasaran.</p>
        <div class="mt-4">
          <a href="{{ route('login') }}" class="btn btn-custom btn-lg me-2">Login</a>
          <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Daftar</a>
        </div>
      </div>
    </div>
  </div>
</section>



  <!-- Content Sections -->
  <div class="container">
    <!-- SPK Section -->
    <div class="row align-items-center mb-5" data-aos="fade-up">
      <div class="col-md-6">
        <img src="{{ asset('assets/img/sistem-penunjang-keputusan.png') }}" alt="SPK" class="img-fluid rounded">
      </div>
      <div class="col-md-6">
        <div class="content-box">
          <h3>Apa itu Sistem Pendukung Keputusan (SPK)?</h3>
          <p>SPK adalah sistem berbasis komputer untuk membantu pengambilan keputusan dengan menyediakan data, model, dan analisis yang relevan.</p>
        </div>
      </div>
    </div>

    <!-- VIKOR Section -->
    <div class="row align-items-center flex-md-row-reverse mb-5" data-aos="fade-up">
      <div class="col-md-6">
        <img src="{{ asset('assets/img/vikor.png') }}" alt="Metode VIKOR" class="img-fluid rounded">
      </div>
      <div class="col-md-6">
        <div class="content-box">
          <h3>Metode VIKOR</h3>
          <p>VIKOR adalah metode pengambilan keputusan multikriteria yang mencari solusi kompromi terbaik dari beberapa alternatif berdasarkan kedekatan terhadap solusi ideal.</p>
        </div>
      </div>
    </div>

    <!-- Beasiswa Section -->
    <div class="row align-items-center mb-5" data-aos="fade-up">
      <div class="col-md-6">
        <img src="{{ asset('assets/img/beasiswa.png') }}" alt="Beasiswa" class="img-fluid rounded">
      </div>
      <div class="col-md-6">
        <div class="content-box">
          <h3>Beasiswa SMK Prima Unggul</h3>
          <p>Beasiswa bagi siswa berprestasi dari keluarga kurang mampu untuk mendukung semangat belajar dan akses pendidikan lebih baik.</p>
        </div>
      </div>
    </div>

    <!-- Tentang Sistem Section -->
    <div class="row align-items-center flex-md-row-reverse mb-5" data-aos="fade-up" id="about">
      <div class="col-md-6">
        <img src="{{ asset('assets/img/sistem.png') }}" alt="Sistem" class="img-fluid rounded">
      </div>
      <div class="col-md-6">
        <div class="content-box">
          <h3>Pengembangan Sistem</h3>
          <p>Sistem ini mempertimbangkan tiga faktor utama:</p>
          <ul>
            <li><strong>Akademik</strong>: Nilai dan prestasi belajar.</li>
            <li><strong>Ekonomi</strong>: Penghasilan dan kondisi keluarga.</li>
            <li><strong>Potensi</strong>: Bakat, minat, dan kegiatan siswa.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="text-center mt-auto py-4">
    <small>&copy; {{ date('Y') }} SMK Prima Unggul. All rights reserved.</small>
  </footer>

  <!-- Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

<script>
  // Init AOS
  AOS.init();

  // Navbar scroll
  let prevScrollpos = window.pageYOffset;
  const navbar = document.getElementById("navbar");
  window.onscroll = function () {
    const currentScrollPos = window.pageYOffset;
    if (prevScrollpos > currentScrollPos) {
      navbar.style.top = "0";
    } else {
      navbar.style.top = "-80px";
    }
    prevScrollpos = currentScrollPos;
  };

  // Smooth scroll
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
      });
    });
  });

  // Particles config
  particlesJS("particles-js", {
    particles: {
      number: { value: 60, density: { enable: true, value_area: 800 } },
      color: { value: "#FC6600" },
      shape: { type: "circle", stroke: { width: 0, color: "#000000" } },
      opacity: { value: 0.5, random: true },
      size: { value: 3, random: true },
      line_linked: {
        enable: true,
        distance: 150,
        color: "#FC6600",
        opacity: 0.4,
        width: 1
      },
      move: {
        enable: true,
        speed: 3,
        direction: "none",
        random: false,
        straight: false,
        out_mode: "out"
      }
    },
    interactivity: {
      detect_on: "canvas",
      events: {
        onhover: { enable: true, mode: "repulse" },
        onclick: { enable: true, mode: "push" },
        resize: true
      },
      modes: {
        repulse: { distance: 100, duration: 0.4 },
        push: { particles_nb: 4 }
      }
    },
    retina_detect: true
  });

  // Dark Mode Toggle
  const toggle = document.getElementById('darkModeToggle');
const body = document.body;

function applyTheme(isDark) {
  body.classList.toggle('dark-mode', isDark);
  toggle.textContent = isDark ? '☀️' : '🌙';
  updateParticleColor(isDark ? '#00FFFF' : '#FF7F50');
  localStorage.setItem('theme', isDark ? 'dark' : 'light');
}

toggle.addEventListener('click', () => {
  const isDark = !body.classList.contains('dark-mode');
  applyTheme(isDark);
});

window.addEventListener('DOMContentLoaded', () => {
  const isDark = localStorage.getItem('theme') === 'dark';
  applyTheme(isDark);
});

</script>
<script>
  function updateParticleColor(color) {
  if (window.pJSDom && window.pJSDom[0]) {
    const pJS = window.pJSDom[0].pJS;
    pJS.particles.color.value = color;
    pJS.particles.line_linked.color = color;
    pJS.fn.particlesRefresh();
  }
}
</script>

<script>
  // Tampilkan loader saat link diklik
  document.addEventListener('DOMContentLoaded', function () {
    const loader = document.getElementById('loader');

    // Tangkap semua link internal
    const links = document.querySelectorAll('a[href]:not([target="_blank"])');

    links.forEach(link => {
      link.addEventListener('click', function (e) {
        const href = this.getAttribute('href');

        if (href && !href.startsWith('#') && !href.startsWith('javascript')) {
          loader.style.display = 'flex'; // tampilkan loader
        }
      });
    });

    // Opsional: jika ingin auto-hide saat selesai load (fallback)
    window.addEventListener('pageshow', () => {
      loader.style.display = 'none';
    });
  });
</script>

</body>
</html>
