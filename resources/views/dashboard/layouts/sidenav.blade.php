<aside
    id="sidenav-main"
    class="fixed inset-y-0 left-0 z-20 w-64 h-full bg-white dark:bg-slate-850 shadow-xl transform -translate-x-full xl:translate-x-0 transition-transform duration-300 ease-in-out"
    aria-expanded="false"
>
    {{-- Header --}}
    <div class="h-20 flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-slate-700" data-aos="fade-down" data-aos-delay="100">
        <a class="text-lg font-bold whitespace-nowrap dark:text-white text-slate-700" href="{{ route('dashboard') }}">
            VIKOR-Method 😮‍💨
        </a>
        <i class="fa fa-times text-slate-400 dark:text-slate-300 cursor-pointer xl:hidden" sidenav-close></i>
    </div>

    {{-- Sidebar Content --}}
    <div class="py-4 overflow-hidden">
        <div class="max-h-[calc(100vh-12rem)] overflow-y-auto py-4" data-aos="zoom-in" data-aos-delay="100">
            <ul class="flex flex-col space-y-1 px-4">
                {{-- Dashboard --}}
                <li class="mt-0.5 w-full" data-aos="fade-right" data-aos-delay="100">
                    <a class="{{ Request::is('dashboard') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors"
                    href="{{ route('dashboard') }}">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg text-center xl:p-2.5">
                            <i class="text-sm leading-normal text-primary ni ni-tv-2"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100">Dashboard</span>
                    </a>
                </li>
                {{-- User Manual --}}
                @role('admin')
                <li x-data="{ open: {{ json_encode(Request::is('user-management')) }} }" x-cloak>
                    <div class="mt-6 mb-2 px-4" data-aos="fade-right" data-aos-delay="200">
                        <button @click="open = !open" class="w-full flex items-center justify-between py-2 text-left text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-white/70 hover:text-blue-600 transition">
                            <span class="flex items-center gap-2">
                                <i class="fa fa-book text-[10px] opacity-70"></i> USER MANUAL
                            </span>
                            <i :class="open ? 'fa fa-chevron-down' : 'fa fa-chevron-right'" class="text-xs"></i>
                        </button>
                    </div>
                    <ul x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-screen"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 max-h-screen"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="pl-4 space-y-1 overflow-hidden">
                        <li data-aos="fade-right" data-aos-delay="200">
                            <a class="{{ Request::is('user-management') ? 'bg-blue-500/13 font-semibold rounded-lg' : '' }} dark:text-white py-2.7 text-sm flex items-center px-4 transition-colors"
                            href="{{ route('user.management') }}">
                                <i class="mr-2 text-sm text-purple-500 fa fa-users"></i> Data Users
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
                {{-- Dropdown: Data Management --}}
                @role(['admin', 'guru'])
                <li x-data="{ open: {{ json_encode(Request::is('criteria') || Request::is('alternatif') || Request::is('penilaian') || Request::is('hitung')) }} }" x-cloak>
                    <div class="mt-6 mb-2 px-4" data-aos="fade-right" data-aos-delay="250">
                        <button @click="open = !open" class="w-full flex items-center justify-between py-2 text-left text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-white/70 hover:text-blue-600 transition">
                            <span class="flex items-center gap-2">
                                <i class="fa fa-database text-[10px] opacity-70"></i> DATA MANAGEMENT
                            </span>
                            <i :class="open ? 'fa fa-chevron-down' : 'fa fa-chevron-right'" class="text-xs"></i>
                        </button>
                    </div>

                    <ul x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-screen"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 max-h-screen"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="pl-2 border-l border-slate-200 dark:border-slate-700 ml-4 mt-2 space-y-1 overflow-hidden">

                        @role('admin')
                        <li data-aos="fade-right" data-aos-delay="250">
                            <a class="{{ Request::is('criteria') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white py-2.7 text-sm mx-2 flex items-center px-4 transition-colors"
                            href="{{ route('criteria.index') }}">
                                <i class="mr-2 text-sm text-orange-500 ni ni-circle-08"></i> Data Kriteria
                            </a>
                        </li>
                        @endrole
                        <li data-aos="fade-right" data-aos-delay="250">
                            <a class="{{ Request::is('alternatif') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white py-2.7 text-sm mx-2 flex items-center px-4 transition-colors"
                            href="{{ route('alternatif.index') }}">
                                <i class="mr-2 text-sm text-emerald-500 ni ni-single-02"></i> Data Alternative
                            </a>
                        </li data-aos="fade-right" data-aos-delay="250">
                        <li data-aos="fade-right" data-aos-delay="100">
                            <a class="{{ Request::is('penilaian') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white py-2.7 text-sm mx-2 flex items-center px-4 transition-colors"
                            href="{{ route('penilaian.index') }}">
                                <i class="mr-2 text-sm text-orange-500 ni ni-calendar-grid-58"></i> Data Matriks
                            </a>
                        </li>
                        <li data-aos="fade-right" data-aos-delay="250">
                            <a class="{{ Request::is('hitung') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white py-2.7 text-sm mx-2 flex items-center px-4 transition-colors"
                            href="{{ route('hitung.index') }}">
                                <i class="mr-2 text-sm text-orange-500 ni ni-single-copy-04"></i> Calculate
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                {{-- Final Result --}}
                <li x-data="{ open: {{ json_encode(Request::is('hasil')) }} }" x-cloak>
                    <div class="mt-6 mb-2 px-4" data-aos="fade-right" data-aos-delay="300">
                        <button @click="open = !open" class="w-full flex items-center justify-between py-2 text-left text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-white/70 hover:text-blue-600 transition">
                            <span class="flex items-center gap-2">
                                <i class="fa fa-book text-[10px] opacity-70"></i> FINAL RESULT
                            </span>
                            <i :class="open ? 'fa fa-chevron-down' : 'fa fa-chevron-right'" class="text-xs"></i>
                        </button>
                    </div>
                    <ul x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-screen"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 max-h-screen"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="pl-4 space-y-1 overflow-hidden">
                        <li data-aos="fade-right" data-aos-delay="300">
                            <a class="{{ Request::is('hasil') ? 'bg-blue-500/13 font-semibold rounded-lg' : '' }} dark:text-white py-2.7 text-sm flex items-center px-4 transition-colors"
                            href="{{ route('hasil.index') }}">
                                <i class="mr-2 text-sm text-orange-500 ni ni-single-copy-04"></i> Calculation Result
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Permintaan (Admin Only) --}}
                @role('admin')
                <li class="mt-0.5 w-full" data-aos="fade-right" data-aos-delay="400">
                    <a href="{{ route('admin.pending-registrations') }}"
                    class="{{ Request::is('admin/pending-registrations') ? 'rounded-lg bg-blue-500/13 font-semibold' : '' }} dark:text-white py-2.7 text-sm mx-2 flex items-center px-4 relative transition-colors">
                        <i class="mr-2 text-sm text-purple-500 fa fa-bell"></i>
                        Permintaan
                        @if (isset($pendingRegistrationsCount) && $pendingRegistrationsCount > 0)
                            <span class="absolute top-1 right-3 inline-flex items-center justify-center px-2 py-1 text-xs font-bold text-red-100 bg-red-600 rounded-full">
                                {{ $pendingRegistrationsCount }}
                            </span>
                        @endif
                    </a>
                </li>
                @endrole
                <li data-aos="fade-right" data-aos-delay="450">
                    <a class="{{ Request::is('users/profile') ? 'bg-blue-500/13 font-semibold rounded-lg' : '' }} dark:text-white py-2.7 text-sm flex items-center px-4 transition-colors"
                    href="{{ route('users.edit', Auth::user()->id) }}">
                        <i class="mr-2 text-sm text-slate-700 ni ni-single-02"></i> Profile
                    </a>
                    </li>
                    <li data-aos="fade-right" data-aos-delay="500">
                    <a class="{{ Request::is('setting') ? 'bg-blue-500/13 font-semibold rounded-lg' : '' }} dark:text-white py-2.7 text-sm flex items-center px-4 transition-colors"
                        href="{{ route('setting') }}">
                        <i class="mr-2 text-sm text-slate-700 ni ni-settings-gear-65"></i> Setting
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Logout --}}
    <div class="absolute bottom-4 left-0 right-0 px-6">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                class="w-full py-2 px-4 text-center text-white bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </button>
        </form>
    </div>
</aside>
