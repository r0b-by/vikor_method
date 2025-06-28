@extends('dashboard.layouts.dashboardmain')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">Pengaturan Aplikasi</h2>

    {{-- Wrapper utama untuk konten pengaturan --}}
    {{-- Menghapus kelas 'fixed-plugin-card', 'fixed', 'top-0', dll. karena ini bukan lagi panel geser --}}
    <div class="bg-white dark:bg-slate-800 shadow-md rounded-lg p-6">
        <div class="px-6 pt-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
            <div class="float-left">
                <h5 class="mt-4 mb-0 dark:text-white">Konfigurator Tampilan</h5>
                <p class="dark:text-white dark:opacity-80">Sesuaikan tampilan *dashboard* Anda.</p>
            </div>
            {{-- Tombol close tidak relevan di sini karena ini adalah halaman penuh --}}
            {{-- <div class="float-right mt-6">
                <button fixed-plugin-close-button
                    class="inline-block p-0 mb-4 text-sm font-bold leading-normal text-center uppercase align-middle transition-all ease-in bg-transparent border-0 rounded-lg shadow-none cursor-pointer hover:-translate-y-px tracking-tight-rem bg-150 bg-x-25 active:opacity-85 dark:text-white text-slate-700">
                    <i class="fa fa-close"></i>
                </button>
            </div> --}}
            <div class="clear-both"></div> {{-- Mengatasi float --}}
        </div>
        <hr class="h-px mx-0 my-1 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />
        <div class="flex-auto p-6 pt-0 overflow-auto sm:pt-4">
            <!-- Sidenav Type -->
            <div class="mt-4">
                <h6 class="mb-0 dark:text-white">Tipe Sidenav</h6>
                <p class="text-sm leading-normal dark:text-white dark:opacity-80">Pilih antara 2 tipe sidenav yang berbeda.</p>
            </div>
            <div class="flex mt-2">
                <button id="whiteSidenavBtn"
                    class="inline-block w-full px-4 py-2.5 mb-2 font-bold leading-normal text-center text-white capitalize align-middle transition-all bg-blue-500 border border-transparent border-solid rounded-lg cursor-pointer text-sm hover:-translate-y-px hover:shadow-xs active:opacity-85 ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 bg-gradient-to-tl from-blue-500 to-violet-500 hover:border-blue-500"
                    data-class="bg-transparent">Putih</button>
                <button id="darkSidenavBtn"
                    class="inline-block w-full px-4 py-2.5 mb-2 ml-2 font-bold leading-normal text-center text-blue-500 capitalize align-middle transition-all bg-transparent border border-blue-500 border-solid rounded-lg cursor-pointer text-sm hover:-translate-y-px hover:shadow-xs active:opacity-85 ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 bg-none hover:border-blue-500"
                    data-class="bg-transparent">Gelap</button>
            </div>
            <p class="block mt-2 text-sm leading-normal dark:text-white dark:opacity-80 xl:hidden">Anda dapat mengubah tipe sidenav hanya pada tampilan desktop.</p>

            <!-- Navbar Fixed -->
            <div class="flex my-4">
                <h6 class="mb-0 dark:text-white">Border Navbar</h6>
                <div class="block pl-0 ml-auto min-h-6">
                    <input id="navbarFixedToggle"
                        class="rounded-10 duration-250 ease-in-out after:rounded-circle after:shadow-2xl after:duration-250 checked:after:translate-x-5.3 h-5 relative float-left mt-1 ml-auto w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-slate-800/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-blue-500/95 checked:bg-blue-500/95 checked:bg-none checked:bg-right"
                        type="checkbox" />
                </div>
            </div>
            <hr class="h-px my-6 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent " />

            <!-- Light / Dark Toggle -->
            <div class="flex mt-2 mb-12">
                <h6 class="mb-0 dark:text-white">Mode Terang / Gelap</h6>
                <div class="block pl-0 ml-auto min-h-6">
                    <input id="darkModeToggle"
                        class="rounded-10 duration-250 ease-in-out after:rounded-circle after:shadow-2xl after:duration-250 checked:after:translate-x-5.3 h-5 relative float-left mt-1 ml-auto w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-slate-800/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-blue-500/95 checked:bg-blue-500/95 checked:bg-none checked:bg-right"
                        type="checkbox" />
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const darkModeToggle = document.getElementById('darkModeToggle');
        const htmlElement = document.documentElement; // Ambil elemen <html>

        // Setel status awal toggle berdasarkan status dark mode saat ini
        if (localStorage.getItem('theme') === 'dark') {
            htmlElement.classList.add('dark');
            darkModeToggle.checked = true;
        } else {
             // Jika tidak ada di localStorage, cek preferensi sistem
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                htmlElement.classList.add('dark');
                darkModeToggle.checked = true;
            } else {
                htmlElement.classList.remove('dark');
                darkModeToggle.checked = false;
            }
        }


        if (darkModeToggle) {
            darkModeToggle.addEventListener('change', function() {
                if (this.checked) {
                    htmlElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark'); // Simpan preferensi pengguna
                } else {
                    htmlElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light'); // Simpan preferensi pengguna
                }
            });
        }


        // --- JavaScript untuk Sidenav Type dan Navbar Fixed (contoh sederhana, mungkin butuh lebih banyak logika di layout utama) ---
        const whiteSidenavBtn = document.getElementById('whiteSidenavBtn');
        const darkSidenavBtn = document.getElementById('darkSidenavBtn');
        const navbarFixedToggle = document.getElementById('navbarFixedToggle');
        const navbarMain = document.getElementById('navbar-main'); // Asumsi navbar memiliki id 'navbar-main'
        const sidenav = document.getElementById('sidenav-main'); // Ambil sidenav

        // Logika untuk Sidenav Type
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
            } else { // 'white'
                sidenav.classList.remove(
                    'bg-transparent', 'text-white',
                    'dark:bg-black/30', 'backdrop-blur-sm'
                );
                sidenav.classList.add(
                    'bg-white', 'text-slate-700', 'shadow-xl',
                    'dark:bg-slate-850', 'dark:text-white'
                );
            }
            // Perbarui warna teks ikon di sidenav agar sesuai dengan tema baru
            const sidenavIcons = sidenav.querySelectorAll('.ni');
            sidenavIcons.forEach(icon => {
                if (type === 'transparent') {
                    // Jika sidenav transparan, mungkin ikon perlu warna cerah
                    icon.classList.remove('text-blue-500', 'text-orange-500', 'text-emerald-500', 'text-purple-500', 'text-slate-700');
                    icon.classList.add('text-white'); // Contoh: Jadikan putih semua
                } else {
                    // Kembalikan warna asli ikon untuk sidenav putih
                    // Ini mungkin perlu disesuaikan dengan warna ikon asli Anda
                    icon.classList.remove('text-white');
                    // Contoh: icon.classList.add('text-blue-500'); // Tergantung ikon
                }
            });
        }

        function setActiveButton(type) {
            if (!transparentBtn || !whiteBtn) return;

            if (type === 'transparent') {
                transparentBtn.classList.add('ring', 'ring-blue-500');
                whiteBtn.classList.remove('ring', 'ring-blue-500');
            } else {
                whiteSidenavBtn.classList.add('ring', 'ring-blue-500'); // Memperbaiki typo: whiteSidenavBtn
                transparentBtn.classList.remove('ring', 'ring-blue-500');
            }
        }

        // Terapkan preferensi sidenav saat load
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

            whiteSidenavBtn.addEventListener('click', () => { // Memperbaiki typo: whiteSidenavBtn
                applySidenavStyle('white');
                localStorage.setItem('sidenav', 'white');
                setActiveButton('white');
            });
        }

        // Logika untuk Navbar Fixed
        if (navbarFixedToggle && navbarMain) {
            navbarFixedToggle.addEventListener('change', function() {
                if (this.checked) {
                    navbarMain.classList.add('fixed', 'top-0', 'w-[calc(100%-48px)]', 'z-10'); // Menambahkan kelas fixed
                    navbarMain.classList.remove('relative');
                } else {
                    navbarMain.classList.remove('fixed', 'top-0', 'w-[calc(100%-48px)]', 'z-10');
                    navbarMain.classList.add('relative');
                }
            });
             // Set initial state based on current classes
            if (navbarMain.classList.contains('fixed')) {
                navbarFixedToggle.checked = true;
            } else {
                navbarFixedToggle.checked = false;
            }
        }
    });
</script>
@endsection
