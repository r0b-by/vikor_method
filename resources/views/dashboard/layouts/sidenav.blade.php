<aside
    id="sidenav-main"
    class="fixed inset-y-0 left-0 z-20 w-64 h-full bg-gradient-to-b from-gray-900 to-gray-800 shadow-2xl transform -translate-x-full xl:translate-x-0 transition-all duration-300 ease-in-out border-r border-gray-700/50"
    aria-expanded="false"
>
    {{-- Futuristic Header with Glow Effect --}}
    <div class="h-20 flex items-center justify-between px-6 py-4 border-b border-gray-700/50" data-aos="fade-down" data-aos-delay="100">
        <a class="text-xl font-bold whitespace-nowrap text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500 hover:from-cyan-500 hover:to-blue-600 transition-all" href="{{ route('dashboard') }}">
            VIKOR-Method <span class="text-blue-400">⚡</span>
        </a>
        <i class="fa fa-times text-gray-400 hover:text-white cursor-pointer xl:hidden transition-colors" sidenav-close></i>
    </div>

    {{-- Sidebar Content with Glass Morphism --}}
    <div class="py-4 overflow-hidden">
        <div class="max-h-[calc(100vh-12rem)] overflow-y-auto py-4 custom-scrollbar" data-aos="zoom-in" data-aos-delay="100">
            <ul class="flex flex-col space-y-1 px-4">
                {{-- Dashboard --}}
                <li class="mt-0.5 w-full" data-aos="fade-right" data-aos-delay="100">
                    <a class="{{ Request::is('dashboard') ? 'rounded-lg bg-gradient-to-r from-blue-500/20 to-cyan-500/20 font-semibold border border-blue-500/30' : 'hover:bg-gray-700/50'}} text-gray-300 py-3 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-all duration-200 group"
                    href="{{ route('dashboard') }}">
                        <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-cyan-500 text-white shadow-lg group-hover:from-blue-700 group-hover:to-cyan-600 transition-all">
                            <i class="text-sm fa fa-chart-line"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100">Dashboard</span>
                    </a>
                </li>

                {{-- Administration --}}
                @role('admin')
                <li x-data="{ open: {{ json_encode(Request::is('admin/users*') || Request::is('admin/academic-periods*')) }} }" x-cloak>
                    <div class="mt-6 mb-2 px-4" data-aos="fade-right" data-aos-delay="200">
                        <button @click="open = !open" class="w-full flex items-center justify-between py-2 text-left text-xs font-bold uppercase tracking-wide text-gray-400 hover:text-white transition-all">
                            <span class="flex items-center gap-2">
                                <i class="fa fa-user-cog text-xs text-cyan-400"></i> 
                                <span class="bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">ADMINISTRATION</span>
                            </span>
                            <i :class="open ? 'fa fa-chevron-down text-cyan-400' : 'fa fa-chevron-right text-gray-400'" class="text-xs transition-transform"></i>
                        </button>
                    </div>
                    <ul x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-screen"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 max-h-screen"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="pl-4 space-y-1 overflow-hidden border-l-2 border-gray-700/50 ml-3">
                        <li data-aos="fade-right" data-aos-delay="200">
                            <a class="{{ Request::is('admin/users') ? 'rounded-lg bg-gradient-to-r from-purple-500/20 to-indigo-500/20 font-semibold border border-purple-500/30' : 'hover:bg-gray-700/50'}} text-gray-300 py-2.7 text-sm flex items-center px-4 transition-all"
                            href="{{ route('admin.user.management') }}">
                                <i class="mr-3 fa fa-users text-purple-400"></i> Data Users
                            </a>
                        </li>
                        <li data-aos="fade-right" data-aos-delay="220">
                            <a class="{{ Request::is('admin/academic-periods*') ? 'rounded-lg bg-gradient-to-r from-indigo-500/20 to-blue-500/20 font-semibold border border-indigo-500/30' : 'hover:bg-gray-700/50'}} text-gray-300 py-2.7 text-sm flex items-center px-4 transition-all"
                            href="{{ route('admin.academic_periods.index') }}">
                                <i class="mr-3 fa fa-calendar-alt text-indigo-400"></i> Periode Akademik
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                {{-- Data Management --}}
                @role(['admin', 'guru'])
                <li x-data="{ open: {{ json_encode(Request::is('criteria') || Request::is('alternatif') || Request::is('penilaian') || Request::is('hitung')) }} }" x-cloak>
                    <div class="mt-6 mb-2 px-4" data-aos="fade-right" data-aos-delay="250">
                        <button @click="open = !open" class="w-full flex items-center justify-between py-2 text-left text-xs font-bold uppercase tracking-wide text-gray-400 hover:text-white transition-all">
                            <span class="flex items-center gap-2">
                                <i class="fa fa-database text-xs text-emerald-400"></i>
                                <span class="bg-gradient-to-r from-emerald-400 to-teal-500 bg-clip-text text-transparent">DATA MANAGEMENT</span>
                            </span>
                            <i :class="open ? 'fa fa-chevron-down text-emerald-400' : 'fa fa-chevron-right text-gray-400'" class="text-xs transition-transform"></i>
                        </button>
                    </div>

                    <ul x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-screen"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 max-h-screen"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="pl-4 space-y-1 overflow-hidden border-l-2 border-gray-700/50 ml-3">
                        @role('admin')
                        <li data-aos="fade-right" data-aos-delay="250">
                            <a class="{{ Request::is('criteria') ? 'rounded-lg bg-gradient-to-r from-orange-500/20 to-amber-500/20 font-semibold border border-orange-500/30' : 'hover:bg-gray-700/50'}} text-gray-300 py-2.7 text-sm mx-2 flex items-center px-4 transition-all"
                            href="{{ route('criteria.index') }}">
                                <i class="mr-3 fa fa-list-ol text-orange-400"></i> Data Kriteria
                            </a>
                        </li>
                        @endrole
                        <li data-aos="fade-right" data-aos-delay="250">
                            <a class="{{ Request::is('alternatif') ? 'rounded-lg bg-gradient-to-r from-emerald-500/20 to-teal-500/20 font-semibold border border-emerald-500/30' : 'hover:bg-gray-700/50'}} text-gray-300 py-2.7 text-sm mx-2 flex items-center px-4 transition-all"
                            href="{{ route('alternatif.index') }}">
                                <i class="mr-3 fa fa-users text-emerald-400"></i> Data Alternative
                            </a>
                        </li>
                        <li data-aos="fade-right" data-aos-delay="100">
                            <a class="{{ Request::is('penilaian') ? 'rounded-lg bg-gradient-to-r from-amber-500/20 to-yellow-500/20 font-semibold border border-amber-500/30' : 'hover:bg-gray-700/50'}} text-gray-300 py-2.7 text-sm mx-2 flex items-center px-4 transition-all"
                            href="{{ route('penilaian.index') }}">
                                <i class="mr-3 fa fa-table text-amber-400"></i> Data Matriks
                            </a>
                        </li>
                        <li data-aos="fade-right" data-aos-delay="250">
                            <a class="{{ Request::is('hitung') ? 'rounded-lg bg-gradient-to-r from-red-500/20 to-pink-500/20 font-semibold border border-red-500/30' : 'hover:bg-gray-700/50'}} text-gray-300 py-2.7 text-sm mx-2 flex items-center px-4 transition-all"
                            href="{{ route('dashboard.hitung') }}">
                                <i class="mr-3 fa fa-calculator text-red-400"></i> Calculate
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                {{-- Final Result --}}
                <li x-data="{ open: {{ json_encode(Request::is('hasil')) }} }" x-cloak>
                    <div class="mt-6 mb-2 px-4" data-aos="fade-right" data-aos-delay="300">
                        <button @click="open = !open" class="w-full flex items-center justify-between py-2 text-left text-xs font-bold uppercase tracking-wide text-gray-400 hover:text-white transition-all">
                            <span class="flex items-center gap-2">
                                <i class="fa fa-trophy text-xs text-yellow-400"></i>
                                <span class="bg-gradient-to-r from-yellow-400 to-amber-500 bg-clip-text text-transparent">FINAL RESULT</span>
                            </span>
                            <i :class="open ? 'fa fa-chevron-down text-yellow-400' : 'fa fa-chevron-right text-gray-400'" class="text-xs transition-transform"></i>
                        </button>
                    </div>
                    <ul x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-screen"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 max-h-screen"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="pl-4 space-y-1 overflow-hidden border-l-2 border-gray-700/50 ml-3">
                        <li data-aos="fade-right" data-aos-delay="300">
                            <a class="{{ Request::is('hasil') ? 'rounded-lg bg-gradient-to-r from-yellow-500/20 to-amber-500/20 font-semibold border border-yellow-500/30' : 'hover:bg-gray-700/50'}} text-gray-300 py-2.7 text-sm flex items-center px-4 transition-all"
                            href="{{ route('hasil.index') }}">
                                <i class="mr-3 fa fa-chart-bar text-yellow-400"></i> Calculation Result
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Permintaan --}}
                @role('admin')
                <li class="mt-0.5 w-full" data-aos="fade-right" data-aos-delay="400">
                    <a href="{{ route('admin.users.pending-registrations') }}"
                    class="{{ Request::is('admin/users/pending-registrations') ? 'rounded-lg bg-gradient-to-r from-purple-500/20 to-pink-500/20 font-semibold border border-purple-500/30' : 'hover:bg-gray-700/50'}} text-gray-300 py-3 text-sm mx-2 flex items-center px-4 relative transition-all group">
                        <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-purple-600 to-pink-500 text-white shadow-lg group-hover:from-purple-700 group-hover:to-pink-600 transition-all">
                            <i class="text-sm fa fa-bell"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100">Permintaan</span>
                        @if (isset($pendingRegistrationsCount) && $pendingRegistrationsCount > 0)
                            <span class="absolute right-4 inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 rounded-full animate-pulse">
                                {{ $pendingRegistrationsCount }}
                            </span>
                        @endif
                    </a>
                </li>
                @endrole

                {{-- Profile --}}
                @role('admin')
                <li data-aos="fade-right" data-aos-delay="450">
                    <a class="{{ Request::is('profile/edit') ? 'rounded-lg bg-gradient-to-r from-blue-500/20 to-cyan-500/20 font-semibold border border-blue-500/30' : 'hover:bg-gray-700/50'}} text-gray-300 py-3 text-sm flex items-center px-4 transition-all group"
                    href="{{ route('profile.edit') }}">
                        <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-cyan-500 text-white shadow-lg group-hover:from-blue-700 group-hover:to-cyan-600 transition-all">
                            <i class="text-sm fa fa-user"></i>
                        </div>
                        <span class="ml-1">Profile</span>
                    </a>
                </li>
                @endrole

                {{-- Setting --}}
                <li data-aos="fade-right" data-aos-delay="500">
                    <a class="{{ Request::is('setting') ? 'rounded-lg bg-gradient-to-r from-gray-500/20 to-slate-500/20 font-semibold border border-gray-500/30' : 'hover:bg-gray-700/50'}} text-gray-300 py-3 text-sm flex items-center px-4 transition-all group"
                        href="{{ route('setting') }}">
                        <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-gray-600 to-slate-500 text-white shadow-lg group-hover:from-gray-700 group-hover:to-slate-600 transition-all">
                            <i class="text-sm fa fa-cog"></i>
                        </div>
                        <span class="ml-1">Setting</span>
                    </a>
                </li>

                {{-- Siswa Specific Links --}}
                @role('siswa')
                <li class="mt-0.5 w-full" data-aos="fade-right" data-aos-delay="550">
                    <a class="{{ Request::is('siswa/dashboard') ? 'rounded-lg bg-gradient-to-r from-blue-500/20 to-cyan-500/20 font-semibold border border-blue-500/30' : 'hover:bg-gray-700/50'}} text-gray-300 py-3 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-all group"
                    href="{{ route('siswa.dashboard') }}">
                        <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-cyan-500 text-white shadow-lg group-hover:from-blue-700 group-hover:to-cyan-600 transition-all">
                            <i class="text-sm fa fa-chalkboard-teacher"></i>
                        </div>
                        <span class="ml-1">Siswa Dashboard</span>
                    </a>
                </li>
                <li class="mt-0.5 w-full" data-aos="fade-right" data-aos-delay="550">
                    <a class="{{ Request::is('siswa/profile/show') ? 'rounded-lg bg-gradient-to-r from-blue-500/20 to-cyan-500/20 font-semibold border border-blue-500/30' : 'hover:bg-gray-700/50'}} text-gray-300 py-3 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-all group"
                    href="{{ route('siswa.profile.show') }}">
                        <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-cyan-500 text-white shadow-lg group-hover:from-blue-700 group-hover:to-cyan-600 transition-all">
                            <i class="text-sm fa fa-user"></i>
                        </div>
                        <span class="ml-1">Siswa Profile</span>
                    </a>
                </li>
                <li class="mt-0.5 w-full" data-aos="fade-right" data-aos-delay="600">
                    <a class="{{ Request::is('siswa/penilaian') ? 'rounded-lg bg-gradient-to-r from-green-500/20 to-emerald-500/20 font-semibold border border-green-500/30' : 'hover:bg-gray-700/50'}} text-gray-300 py-3 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-all group"
                    href="{{ route('siswa.penilaian.index') }}">
                        <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-green-600 to-emerald-500 text-white shadow-lg group-hover:from-green-700 group-hover:to-emerald-600 transition-all">
                            <i class="text-sm fa fa-clipboard-list"></i>
                        </div>
                        <span class="ml-1">Isi Penilaian</span>
                    </a>
                </li>
                @endrole
            </ul>
        </div>
    </div>

    {{-- Futuristic Logout Button --}}
    <div class="absolute bottom-4 left-0 right-0 px-6" data-aos="fade-up" data-aos-delay="600">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full py-3 px-6 text-center text-white bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl shadow-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center group">
                <i class="fas fa-sign-out-alt mr-3 group-hover:animate-pulse"></i> 
                <span class="font-medium">Logout</span>
                <i class="fas fa-arrow-right ml-3 opacity-0 group-hover:opacity-100 group-hover:ml-4 transition-all duration-300"></i>
            </button>
        </form>
    </div>
</aside>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.05);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
        border-radius: 4px;
    }
</style>