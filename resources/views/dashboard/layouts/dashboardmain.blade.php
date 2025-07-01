<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="dark">
    <title>@yield('title') - SPK VIKOR BEASISWA SMK PRIMA UNGGUL</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/nucleo-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nucleo-svg.css') }}">
    
    <link rel="stylesheet" href="{{ asset('assets/css/argon-dashboard-tailwind.css') }}">
    
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/perfect-scrollbar.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/tooltips.css') }}">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/js/app.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #4a5568;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }

        html.dark body {
            background-color: #1a202c;
            color: #e2e8f0;
        }

        .layout-container {
            display: flex;
            flex: 1;
            position: relative;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .content-wrapper {
            flex: 1;
            padding: 1.5rem;
            padding-top: 5rem; /* Ruang untuk navbar */
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 15;
        }
        
        .sidebar-open .mobile-overlay {
            display: block;
        }
    </style>
</head>

<body class="bg-gray-50 text-slate-700 dark:bg-slate-900">
    @include('dashboard.layouts.sidenav')
    
    <div class="main-content-scrollable xl:ml-68">
        @include('dashboard.layouts.navbar')
        
        <div class="content-wrapper">
            @yield('content')
            @stack('scripts')
        </div>
        
        @include('dashboard.layouts.footer')
    </div>

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    {{-- Ini adalah JS utama Argon Dashboard. Cukup gunakan versi minified-nya --}}
    <script src="{{ asset('assets/js/argon-dashboard-tailwind.min.js') }}"></script> 

    <script src="{{ asset('assets/vendor/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.js') }}"></script>
    
    {{-- Chart.js dari CDN (lebih baik satu sumber saja) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    {{-- Ini kemungkinan ekstensi untuk Chart.js, biarkan jika ada fungsionalitas tambahan --}}
    <script src="{{ asset('assets/js/plugins/Chart.extension.js') }}"></script> 
    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // No page transition animation
        });
    </script>
     <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fungsi untuk toggle sidebar di mobile
            const sidenavTrigger = document.querySelector('[sidenav-trigger]');
            const sidenavClose = document.querySelector('[sidenav-close]');
            const sidenavMain = document.getElementById('sidenav-main');
            const mainContent = document.getElementById('mainContent');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const body = document.body;

            if (sidenavTrigger) {
                sidenavTrigger.addEventListener('click', function() {
                    sidenavMain.classList.remove('-translate-x-full');
                    sidenavMain.classList.add('translate-x-0');
                    body.classList.add('sidebar-open');
                    body.classList.add('overflow-hidden');
                });
            }

            if (sidenavClose) {
                sidenavClose.addEventListener('click', function() {
                    sidenavMain.classList.add('-translate-x-full');
                    sidenavMain.classList.remove('translate-x-0');
                    body.classList.remove('sidebar-open');
                    body.classList.remove('overflow-hidden');
                });
            }

            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', function() {
                    sidenavMain.classList.add('-translate-x-full');
                    sidenavMain.classList.remove('translate-x-0');
                    body.classList.remove('sidebar-open');
                    body.classList.remove('overflow-hidden');
                });
            }

            // Fungsi dropdown profile
            const toggleButton = document.getElementById('profileDropdownToggle');
            const dropdownMenu = document.getElementById('profileDropdownMenu');

            if (toggleButton && dropdownMenu) {
                toggleButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    dropdownMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function (event) {
                    if (!toggleButton.contains(event.target) && 
                        !dropdownMenu.contains(event.target)) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        AOS.init({
            duration: 600,
            once: true,
        });
    });
    </script>
</body>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.getElementById("profileDropdownToggle");
    const menu = document.getElementById("profileDropdownMenu");

    toggle?.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        menu.classList.toggle("hidden");
    });

    document.addEventListener("click", function (e) {
        if (!menu.contains(e.target) && !toggle.contains(e.target)) {
            menu.classList.add("hidden");
        }
    });
});
</script>
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
        }
    }
</script>
</html>