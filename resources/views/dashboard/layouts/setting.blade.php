@extends('dashboard.layouts.dashboardmain')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">Setting Themes (Next Update)</h2>

    {{-- Wrapper utama untuk konten pengaturan --}}
    <div class="bg-white dark:bg-slate-800 shadow-md rounded-lg p-6">
        <div class="px-6 pt-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
            <div class="float-left">
                <h5 class="mt-4 mb-0 dark:text-white">Konfigurator Tampilan</h5>
                <p class="dark:text-white dark:opacity-80">Sesuaikan tampilan *dashboard* Anda.</p>
            </div>
            <div class="clear-both"></div>
        </div>
        <hr class="h-px mx-0 my-1 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />
        <div class="flex-auto p-6 pt-0 overflow-auto sm:pt-4">
            <div class="mt-4">
                <h6 class="mb-0 dark:text-white">Tipe Sidenav</h6>
                <p class="text-sm leading-normal dark:text-white dark:opacity-80">Pilih antara 2 tipe sidenav yang berbeda.</p>
            </div>
            <div class="flex justify-center gap-2 mt-2">
                <button transparent-style-btn
                    class="inline-block w-full px-6 py-3 mb-2 text-xs font-bold text-center text-black uppercase align-middle transition-all ease-in bg-transparent border border-black rounded-lg shadow-none cursor-pointer hover:-translate-y-px active:shadow-xs dark:border-white dark:text-white">Transparan</button>
                <button white-style-btn
                    class="inline-block w-full px-6 py-3 mb-2 text-xs font-bold text-center text-black uppercase align-middle transition-all ease-in bg-transparent border border-black rounded-lg shadow-none cursor-pointer hover:-translate-y-px active:shadow-xs dark:border-white dark:text-white">Putih</button>
            </div>

            <div class="mt-4">
                <h6 class="mb-0 dark:text-white">Navbar Fixed</h6>
                <p class="text-sm leading-normal dark:text-white dark:opacity-80">Aktifkan *fixed* navbar.</p>
                <div class="form-check form-switch flex pt-2">
                    <input class="mt-0.54 rounded-full ease-in-out after:rounded-full duration-200" type="checkbox" id="navbarFixed">
                </div>
            </div>

            <hr class="h-px mx-0 my-6 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />

            <div class="mt-4">
                <h6 class="mb-0 dark:text-white">Mode Gelap</h6>
                <p class="text-sm leading-normal dark:text-white dark:opacity-80">Pilih mode tampilan.</p>
                <div class="flex justify-center gap-2 mt-2">
                    <button id="lightModeBtn"
                        class="inline-block w-full px-6 py-3 mb-2 text-xs font-bold text-center text-black uppercase align-middle transition-all ease-in bg-transparent border border-black rounded-lg shadow-none cursor-pointer hover:-translate-y-px active:shadow-xs dark:border-white dark:text-white">Terang</button>
                    <button id="darkModeBtn"
                        class="inline-block w-full px-6 py-3 mb-2 text-xs font-bold text-center text-black uppercase align-middle transition-all ease-in bg-transparent border border-black rounded-lg shadow-none cursor-pointer hover:-translate-y-px active:shadow-xs dark:border-white dark:text-white">Gelap</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidenav = document.getElementById('sidenav-main');
        const transparentBtn = document.querySelector('[transparent-style-btn]');
        const whiteBtn = document.querySelector('[white-style-btn]'); // Corrected to whiteBtn
        const navbarFixedToggle = document.getElementById('navbarFixed');
        const navbarMain = document.getElementById('navbar-main');

        const lightModeBtn = document.getElementById('lightModeBtn');
        const darkModeBtn = document.getElementById('darkModeBtn');

        function applySidenavStyle(style) {
            if (sidenav) {
                if (style === 'transparent') {
                    sidenav.classList.remove('bg-white');
                    sidenav.classList.add('bg-transparent');
                } else {
                    sidenav.classList.remove('bg-transparent');
                    sidenav.classList.add('bg-white');
                }
            }
        }

        function setActiveButton(activeStyle) {
            if (transparentBtn) {
                transparentBtn.classList.remove('bg-gradient-to-tl', 'from-blue-500', 'to-violet-500', 'text-white');
                transparentBtn.classList.add('bg-transparent', 'text-black', 'dark:text-white', 'border-black', 'dark:border-white');
            }
            if (whiteBtn) {
                whiteBtn.classList.remove('bg-gradient-to-tl', 'from-blue-500', 'to-violet-500', 'text-white');
                whiteBtn.classList.add('bg-transparent', 'text-black', 'dark:text-white', 'border-black', 'dark:border-white');
            }

            if (activeStyle === 'transparent' && transparentBtn) {
                transparentBtn.classList.add('bg-gradient-to-tl', 'from-blue-500', 'to-violet-500', 'text-white');
                transparentBtn.classList.remove('text-black', 'dark:text-white', 'border-black', 'dark:border-white');
            } else if (activeStyle === 'white' && whiteBtn) {
                whiteBtn.classList.add('bg-gradient-to-tl', 'from-blue-500', 'to-violet-500', 'text-white');
                whiteBtn.classList.remove('text-black', 'dark:text-white', 'border-black', 'dark:border-white');
            }
        }

        // Initial Sidenav state
        const storedSidenavStyle = localStorage.getItem('sidenav');
        if (storedSidenavStyle) {
            applySidenavStyle(storedSidenavStyle);
            setActiveButton(storedSidenavStyle);
        } else {
            // Default to white if no preference is stored
            applySidenavStyle('white');
            setActiveButton('white');
        }

        if (sidenav && transparentBtn && whiteBtn) {
            transparentBtn.addEventListener('click', () => {
                applySidenavStyle('transparent');
                localStorage.setItem('sidenav', 'transparent');
                setActiveButton('transparent');
            });

            whiteBtn.addEventListener('click', () => { // Corrected: whiteBtn
                applySidenavStyle('white');
                localStorage.setItem('sidenav', 'white');
                setActiveButton('white');
            });
        }

        // Logic for Navbar Fixed
        if (navbarFixedToggle && navbarMain) {
            navbarFixedToggle.addEventListener('change', function() {
                if (this.checked) {
                    navbarMain.classList.add('fixed', 'top-0', 'w-[calc(100%-48px)]', 'z-10');
                    navbarMain.classList.remove('relative');
                } else {
                    navbarMain.classList.remove('fixed', 'top-0', 'w-[calc(100%-48px)]', 'z-10');
                    navbarMain.classList.add('relative');
                }
                localStorage.setItem('navbarFixed', this.checked); // Persist setting
            });

            // Set initial state based on current classes or local storage
            const storedNavbarFixed = localStorage.getItem('navbarFixed');
            if (storedNavbarFixed === 'true') {
                navbarFixedToggle.checked = true;
                navbarMain.classList.add('fixed', 'top-0', 'w-[calc(100%-48px)]', 'z-10');
                navbarMain.classList.remove('relative');
            } else {
                navbarFixedToggle.checked = false;
                navbarMain.classList.remove('fixed', 'top-0', 'w-[calc(100%-48px)]', 'z-10');
                navbarMain.classList.add('relative');
            }
        }

        // Dark Mode Logic
        function applyDarkMode(isDark) {
            const body = document.body;
            if (isDark) {
                body.classList.add('dark');
                body.classList.remove('light'); // Ensure light class is removed
            } else {
                body.classList.remove('dark');
                body.classList.add('light'); // Ensure light class is added
            }
            localStorage.setItem('darkMode', isDark);
        }

        function setActiveModeButton(mode) {
            if (lightModeBtn) {
                lightModeBtn.classList.remove('bg-gradient-to-tl', 'from-blue-500', 'to-violet-500', 'text-white');
                lightModeBtn.classList.add('bg-transparent', 'text-black', 'dark:text-white', 'border-black', 'dark:border-white');
            }
            if (darkModeBtn) {
                darkModeBtn.classList.remove('bg-gradient-to-tl', 'from-blue-500', 'to-violet-500', 'text-white');
                darkModeBtn.classList.add('bg-transparent', 'text-black', 'dark:text-white', 'border-black', 'dark:border-white');
            }

            if (mode === 'dark' && darkModeBtn) {
                darkModeBtn.classList.add('bg-gradient-to-tl', 'from-blue-500', 'to-violet-500', 'text-white');
                darkModeBtn.classList.remove('text-black', 'dark:text-white', 'border-black', 'dark:border-white');
            } else if (mode === 'light' && lightModeBtn) {
                lightModeBtn.classList.add('bg-gradient-to-tl', 'from-blue-500', 'to-violet-500', 'text-white');
                lightModeBtn.classList.remove('text-black', 'dark:text-white', 'border-black', 'dark:border-white');
            }
        }

        // Initial Dark Mode state
        const storedDarkMode = localStorage.getItem('darkMode');
        if (storedDarkMode === 'true') {
            applyDarkMode(true);
            setActiveModeButton('dark');
        } else {
            applyDarkMode(false);
            setActiveModeButton('light');
        }

        if (lightModeBtn && darkModeBtn) {
            lightModeBtn.addEventListener('click', () => {
                applyDarkMode(false);
                setActiveModeButton('light');
            });

            darkModeBtn.addEventListener('click', () => {
                applyDarkMode(true);
                setActiveModeButton('dark');
            });
        }
    });
</script>
@endsection