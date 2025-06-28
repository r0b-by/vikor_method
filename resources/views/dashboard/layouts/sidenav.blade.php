<!-- sidenav -->
<aside
    id="sidenav-main"
    class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-visible antialiased transition-transform duration-200 -translate-x-full bg-white border-0 shadow-xl dark:shadow-none dark:bg-slate-850 max-w-64 ease-nav-brand z-990 xl:ml-6 rounded-2xl xl:left-0 xl:translate-x-0"
    aria-expanded="false">
    <!-- Konten Sidebar -->
    <div class="h-19">
        <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times dark:text-white text-slate-400 xl:hidden" sidenav-close></i>
        <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap dark:text-white text-slate-700" href="{{ route('dashboard') }}" target="_blank">
            <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">Project SPK Menyenangkan</span>
        </a>
    </div>

    <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />

    <div class="items-center block w-auto h-auto max-h-screen overflow-visible h-sidenav grow basis-full">
        <ul class="flex flex-col pl-0 mb-0">
            <li class="mt-0.5 w-full">
                <a class="py-2.7 {{ Request::is('dashboard') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white dark:opacity-80 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 text-slate-700 transition-colors"
                    href="{{ route('dashboard') }}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-blue-500 ni ni-tv-2"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Dashboard</span>
                </a>
            </li>

            {{-- Menu untuk Admin dan Guru --}}
            @role(['admin', 'guru'])
            <li class="w-full mt-4">
                <h6 class="pl-6 ml-2 font-bold uppercase dark:text-white text-slate-700 text-xs opacity-60">MASTER DATA & PERHITUNGAN</h6>
            </li>
            <li class="mt-0.5 w-full">
                <a class="{{ Request::is('alternatif') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors"
                    href="{{ route('alternatif.index') }}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center fill-current stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-emerald-500 ni ni-single-02"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Data Alternatif</span>
                </a>
            </li>
            <li class="mt-0.5 w-full">
                <a class="{{ Request::is('penilaian') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors"
                    href="{{ route('penilaian.index') }}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center fill-current stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-orange-500 ni ni-calendar-grid-58"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Matriks Penilaian</span>
                </a>
            </li>
            <li class="mt-0.5 w-full">
                <a class="{{ Request::is('hitung') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors"
                    href="{{ route('hitung.index') }}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center fill-current stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-orange-500 ni ni-single-copy-04"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Proses Hitung VIKOR</span>
                </a>
            </li>
            @endrole

            {{-- Menu khusus Admin --}}
            @role('admin')
            <li class="mt-0.5 w-full">
                <a class="{{ Request::is('criteria') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors"
                    href="{{ route('criteria.index') }}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-orange-500 ni ni-circle-08"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Kriteria Bobot</span>
                </a>
            </li>
            <li class="mt-0.5 w-full">
                <a class="{{ Request::is('user-management') || Request::is('users/*') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors"
                    href="{{ route('user.management') }}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-purple-500 fa fa-users"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Manajemen Pengguna</span>
                </a>
            </li>
            @endrole

            {{-- Menu untuk semua peran yang bisa melihat hasil (Admin, Guru, Siswa) --}}
            <li class="w-full mt-4">
                <h6 class="pl-6 ml-2 font-bold uppercase dark:text-white text-slate-700 text-xs opacity-60">HASIL AKHIR</h6>
            </li>
            <li class="mt-0.5 w-full">
                <a class="{{ Request::is('hasil') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors"
                    href="{{ route('hasil.index') }}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center fill-current stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-orange-500 ni ni-single-copy-04"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Hasil Perhitungan</span>
                </a>
            </li>

            <li class="w-full mt-4">
                <h6 class="pl-6 ml-2 font-bold uppercase dark:text-white text-slate-700 text-xs opacity-60">PENGATURAN AKUN</h6>
            </li>
            <li class="mt-0.5 w-full">
                {{-- Menggunakan Auth::user()->id untuk memastikan link ke profil pengguna yang sedang login --}}
                <a class="{{ Request::is('users/profile') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors"
                    href="{{ route('users.edit', Auth::user()->id) }}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-slate-700 ni ni-single-02"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Profile</span>
                </a>
            </li>
            <li class="mt-0.5 w-full">
                <a class="{{ Request::is('setting') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors"
                    href="{{ route('setting') }}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-slate-700 ni ni-settings-gear-65"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Setting</span>
                </a>
            </li>
        </ul>
    </div>

    {{-- Tombol Logout di bagian bawah sidebar --}}
    <div class="mx-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="inline-block w-full px-8 py-2 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in bg-gradient-to-tl from-blue-500 to-violet-500 shadow-md rounded-lg break-words cursor-pointer hover:shadow-xs leading-pro">
                <i class="fas fa-sign-out-alt mr-1"></i> Logout
            </button>
        </form>
    </div>
</aside>
<!-- end sidenav -->
