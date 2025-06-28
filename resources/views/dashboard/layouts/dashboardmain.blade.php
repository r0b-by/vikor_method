<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') - SPK VIKOR BEASISWA SMK PRIMA UNGGUL</title>

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/nucleo-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nucleo-svg.css') }}">

    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>

    <!-- Main Styling -->
    <link rel="stylesheet" href="{{ asset('assets/css/argon-dashboard-tailwind.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }
        body.fade-out {
            opacity: 0;
        }

        .bg-primary {
            background-color: #FC6600 !important;
        }

        .text-primary {
            color: #FC6600 !important;
        }

        .btn-primary {
            background-color: #FC6600;
            border-color: #FC6600;
        }

        .btn-primary:hover {
            background-color: #e05500;
            border-color: #e05500;
        }

        footer {
            background-color: #FC6600;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
    </style>
</head>

<body class="m-0 font-sans text-base antialiased font-normal bg-gray-50 dark:bg-slate-900 text-slate-700 dark:text-white">

    <div class="absolute w-full bg-primary dark:hidden min-h-75"></div>

    <!-- sidenav -->
    @include('dashboard.layouts.sidenav')
    <!-- end sidenav -->

    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">

        <!-- Navbar -->
        @include('dashboard.layouts.navbar')
        <!-- end Navbar -->

        <!-- Content -->
        <div class="w-full px-6 py-6 mx-auto">
            @yield('content')
        </div>
        <!-- end Content -->

        <!-- Grafik Chart -->
        @push('scripts')
        @isset($latestAlternatif, $latestCriteria)
        <script>

            let myChart = null;

            function renderChart(isDark) {
                const jumlahData = @json([
                    $latestAlternatif ? $latestAlternatif->id : 0,
                    $latestCriteria ? $latestCriteria->id : 0
                ]);

                const ctx = document.getElementById('dataChart')?.getContext('2d');
                if (!ctx) return;

                // Hapus chart lama
                if (myChart) {
                    myChart.destroy();
                }

                myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Alternatif', 'Kriteria'],
                        datasets: [{
                            label: 'Jumlah Data',
                            data: jumlahData,
                            backgroundColor: [
                                isDark ? '#facc15' : '#f97316',
                                isDark ? '#60a5fa' : '#3b82f6'
                            ],
                            borderRadius: 8
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    color: isDark ? '#ffffff' : '#000000'
                                },
                                grid: {
                                    color: isDark ? '#444' : '#ddd'
                                }
                            },
                            y: {
                                ticks: {
                                    color: isDark ? '#ffffff' : '#000000'
                                },
                                grid: {
                                    color: isDark ? '#444' : '#ddd'
                                }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDark ? '#1f2937' : '#f9fafb',
                                titleColor: isDark ? '#facc15' : '#f97316',
                                bodyColor: isDark ? '#e5e7eb' : '#1f2937',
                                callbacks: {
                                    label: context => `${context.parsed.x} item`
                                }
                            }
                        }
                    }
                });
            }

            document.addEventListener("DOMContentLoaded", function () {
                const isDark = document.documentElement.classList.contains('dark');
                renderChart(isDark);

                // Jika tema berubah setelah dimuat (misalnya toggle dark mode)
                const observer = new MutationObserver(() => {
                    const isDarkNow = document.documentElement.classList.contains('dark');
                    renderChart(isDarkNow);
                });

                observer.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            });
        </script>
        @endisset
        @endpush
        <!-- end Grafik Chart -->

        <!-- footer -->
        @include('dashboard.layouts.footer')

    </main>

    <!-- plugin for charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- plugin for scrollbar -->
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>

    <!-- main script file -->
    <script src="{{ asset('assets/js/argon-dashboard-tailwind.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.js') }}"></script>

    {{-- BARIS INI YANG MENYEBABKAN INFINITE LOOP. INI HARUS DIHAPUS. --}}
    {{-- @include('dashboard.layouts.setting') --}} 

    <!-- Script Theme Mode dari setting.blade.php -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // === Datatables init ===
        // Pastikan elemen dengan ID 'datatable-search' ada di view yang di-render
        if (document.getElementById('datatable-search')) {
            new DataTable("#datatable-search", {
                responsive: true
            });
        }


        // === Toggle panel setting ===
        const fixedPluginButton = document.querySelector('[fixed-plugin-button]');
        const fixedPlugin = document.querySelector('[fixed-plugin-card]');
        const closeButton = document.querySelector('[fixed-plugin-close-button]');

        if (fixedPluginButton && fixedPlugin && closeButton) {
            fixedPluginButton.onclick = () => {
                fixedPlugin.classList.toggle('-right-90');
                fixedPlugin.classList.toggle('right-0');
            };
            closeButton.onclick = () => {
                fixedPlugin.classList.add('-right-90');
                fixedPlugin.classList.remove('right-0');
            };
        }

        // === Sidenav Type Buttons ===
        const sidenav = document.getElementById('sidenav-main');
        const transparentBtn = document.querySelector('[transparent-style-btn]');
        const whiteBtn = document.querySelector('[white-style-btn]');

        function applySidenavStyle(type) {
            if (!sidenav) return;

            if (type === 'transparent') {
                sidenav.classList.remove(
                    'bg-white', 'text-slate-700', 'shadow-xl',
                    'dark:bg-slate-850', 'dark:text-white'
                );
                sidenav.classList.add(
                    'bg-transparent', 'text-white',
                    'dark:bg-black/30', 'backdrop-blur-sm'
                );
            } else {
                sidenav.classList.remove(
                    'bg-transparent', 'text-white',
                    'dark:bg-black/30', 'backdrop-blur-sm'
                );
                sidenav.classList.add(
                    'bg-white', 'text-slate-700', 'shadow-xl',
                    'dark:bg-slate-850', 'dark:text-white'
                );
            }
        }

        function setActiveButton(type) {
            if (!transparentBtn || !whiteBtn) return;

            if (type === 'transparent') {
                transparentBtn.classList.add('ring', 'ring-blue-500');
                whiteBtn.classList.remove('ring', 'ring-blue-500');
            } else {
                whiteBtn.classList.add('ring', 'ring-blue-500');
                transparentBtn.classList.remove('ring', 'ring-blue-500');
            }
        }

        // Terapkan preferensi saat load
        const sidenavPref = localStorage.getItem('sidenav');
        if (sidenavPref) {
            applySidenavStyle(sidenavPref);
            setActiveButton(sidenavPref);
        } else {
            // Set default jika belum ada preferensi (misal: 'white')
            applySidenavStyle('white');
            setActiveButton('white');
        }

        if (sidenav && transparentBtn && whiteBtn) {
            transparentBtn.addEventListener('click', () => {
                applySidenavStyle('transparent');
                localStorage.setItem('sidenav', 'transparent');
                setActiveButton('transparent');
            });

            whiteBtn.addEventListener('click', () => {
                applySidenavStyle('white');
                localStorage.setItem('sidenav', 'white');
                setActiveButton('white');
            });
        }

        // === Navbar Border Toggle ===
        const navbar = document.getElementById('navbar-main');
        const navbarBorderToggle = document.querySelector('[navbarFixed]');

        if (navbar && navbarBorderToggle) {
            navbarBorderToggle.addEventListener('change', function () {
                if (this.checked) {
                    navbar.classList.add('border-b', 'border-white/80');
                } else {
                    navbar.classList.remove('border-b', 'border-white/80');
                }
            });
        }

        // === Dark mode toggle ===
        const darkToggle = document.querySelector('[dark-toggle]');
        const htmlEl = document.documentElement;

        // Terapkan preferensi tema saat load
        if (localStorage.getItem('theme') === 'dark') {
            htmlEl.classList.add('dark');
            if (darkToggle) darkToggle.checked = true;
        } else if (darkToggle) { // Pastikan toggle tidak null sebelum mencoba mengaksesnya
             // Jika tidak ada di localStorage, cek preferensi sistem
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                htmlEl.classList.add('dark');
                darkToggle.checked = true;
            } else {
                htmlEl.classList.remove('dark');
                darkToggle.checked = false;
            }
        }

        if (darkToggle) {
            darkToggle.addEventListener('change', function () {
                if (this.checked) {
                    htmlEl.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    htmlEl.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            });
        }
    });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const links = document.querySelectorAll("a");

            links.forEach(link => {
                link.addEventListener("click", function (e) {
                    const href = link.getAttribute("href");
                    const isSamePage = href === "#" || href.startsWith("javascript");

                    // Cek link valid dan bukan link download / blank tab
                    if (!isSamePage && !link.hasAttribute("target") && !link.hasAttribute("download")) {
                        e.preventDefault();
                        document.body.classList.add("fade-out"); // Tambahkan kelas fade-out sebelum navigasi
                        setTimeout(() => {
                            window.location.href = href;
                        }, 300); // Sesuai durasi CSS
                    }
                });
            });
        });
    </script>

    <script>
        // Tampilkan loader saat link diklik
        document.addEventListener('DOMContentLoaded', function () {
            const loader = document.getElementById('loader'); // Asumsi ada elemen loader dengan id 'loader'

            // Tangkap semua link internal
            const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="javascript"])');

            links.forEach(link => {
                link.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');

                    if (href) { // Pastikan href tidak null/kosong
                        if (loader) { // Pastikan loader elemen ada
                            loader.style.display = 'flex'; // tampilkan loader
                        }
                    }
                });
            });

            // Opsional: jika ingin auto-hide saat selesai load (fallback)
            // Ini akan menyembunyikan loader saat halaman baru dimuat sepenuhnya
            window.addEventListener('pageshow', () => {
                if (loader) {
                    loader.style.display = 'none';
                }
            });
        });
    </script>

</body>
</html>
