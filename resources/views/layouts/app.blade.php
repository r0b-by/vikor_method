<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>@yield('title', 'SPK VIKOR AI - Beasiswa SMK Prima Unggul')</title>

    <!-- Fonts & Icons -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/wellcome.css') }}">
    
    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav id="navbar" class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('welcome') }}">
                <img src="{{ asset('assets/img/logo-navbar.png') }}" alt="Logo" width="40" class="me-2">
                <span class="fw-bold d-none d-sm-inline">SMK PRIMA UNGGUL</span>
                <span class="ai-chip ms-2 d-none d-md-inline">AI Enhanced</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <i class="fas fa-bars"></i>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title d-flex align-items-center" id="offcanvasNavbarLabel">
                        <img src="{{ asset('assets/img/logo-navbar.png') }}" alt="Logo" width="40" class="me-2">
                        <span>Menu Navigasi</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}" href="{{ route('welcome') }}">
                                <i class="fas fa-home me-3"></i>Beranda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                                <i class="fas fa-info-circle me-3"></i>Tentang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('features') ? 'active' : '' }}" href="{{ route('features') }}">
                                <i class="fas fa-star me-3"></i>Fitur
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                                <i class="fas fa-envelope me-3"></i>Kontak
                            </a>
                        </li>
                    </ul>
                    <div class="d-grid gap-2 mt-3"> {{-- Sesuaikan mt-3 ini. Jika masih terlalu tinggi/rendah, coba mt-2 atau mt-1, atau tanpa mt sama sekali. --}}
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="app" class="py-4">
    @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h4 class="mb-4">SMK Prima Unggul</h4>
                    <p>Mengembangkan pendidikan vokasi berkualitas dengan dukungan teknologi terkini untuk mencetak lulusan yang kompeten dan berkarakter.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5 class="mb-4">Tautan Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('welcome') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-white text-decoration-none">Tentang</a></li>
                        <li class="mb-2"><a href="{{ route('features') }}" class="text-white text-decoration-none">Fitur</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}" class="text-white text-decoration-none">Kontak</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-white text-decoration-none">Login</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="mb-4">Kontak Kami</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Jl. Pendidikan No. 123, Bandung</li>
                        <li class="mb-2"><i class="fas fa-phone-alt me-2"></i> +62 22 1234 5678</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@smkprimaunggul.sch.id</li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="mb-4">Newsletter</h5>
                    <p>Berlangganan newsletter kami untuk mendapatkan informasi terbaru.</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email Anda">
                        <button class="btn btn-primary" type="button">Berlangganan</button>
                    </div>
                </div>
            </div>
            <hr class="my-4 bg-light opacity-10">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; {{ date('Y') }} SMK Prima Unggul. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Developed with <i class="fas fa-heart text-danger"></i> by IT Team</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
    
    <!-- Application Scripts -->
    @vite('resources/js/app.js')
    <script src="{{ asset('js/wellcome.js') }}"></script>
    
    @stack('scripts')
</body>
</html>