<!-- Navbar -->
<nav
    id="navbar-main"
    class="sticky top-0 z-10 w-full 
           flex items-center justify-between px-4 py-3
           shadow-md rounded-lg
           bg-white dark:bg-slate-800 text-slate-700 dark:text-white"
>
    <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
        <nav>
            <!-- breadcrumb -->
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
                <li class="text-sm leading-normal">
                    <a class="text-slate-500 dark:text-slate-300 opacity-70" href="{{route('dashboard')}}">Pages</a>
                </li>
                <li class="text-sm pl-2 capitalize leading-normal text-slate-700 dark:text-white">{{ Request::segment(1) }}</li>
            </ol>
            <h6 class="mb-0 font-bold capitalize text-slate-700 dark:text-white">{{ Request::segment(1) }}</h6>
        </nav>
        <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
            <!-- Notifications for Admin -->
            @role('admin') {{-- Only show notifications if the user is an admin --}}
            <li class="relative flex items-center px-4">
                {{-- Link to pending registrations page --}}
                <a href="{{ route('admin.pending-registrations') }}" class="block p-0 text-sm text-slate-700 dark:text-white transition-all ease-nav-brand">
                    <i class="fa fa-bell cursor-pointer"></i>
                    {{-- Display notification badge if there are pending registrations --}}
                    @if (isset($pendingRegistrationsCount) && $pendingRegistrationsCount > 0)
                        <span class="absolute top-0 right-0 -mt-1 -mr-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                            {{ $pendingRegistrationsCount }}
                        </span>
                    @endif
                </a>
            </li>
            @endrole
            <li class="relative flex items-center pr-2">
                @auth
                <div class="relative group">
                    <button type="button" id="profileDropdownToggle"
                        class="group inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-white hover:text-blue-600 focus:outline-none transition duration-200">
                        <i class="fa fa-user"></i>
                        <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                        <svg class="w-3 h-3 fill-current transition-transform group-hover:rotate-180" viewBox="0 0 20 20">
                            <path d="M5.25 7.5L10 12.25L14.75 7.5H5.25Z" />
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div id="profileDropdownMenu"
                        class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-slate-700 rounded-md shadow-lg z-50 py-2 transition-all duration-150">
                        <a href="{{ route('users.edit', Auth::user()->id) }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-600">Profile</a>
                        <a href="{{ route('setting') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-600">Settings</a>
                        <div class="border-t border-gray-100 dark:border-slate-600 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-600">Logout</button>
                        </form>
                    </div>
                </div>
                @endauth
            </li>
            {{-- Mobile sidebar toggle button (visible on smaller screens) --}}
            <li class="flex items-center pl-4 xl:hidden">
                <a href="javascript:;" class="block p-0 text-sm text-white transition-all ease-nav-brand" sidenav-trigger>
                    <div class="w-4.5 overflow-hidden">
                        <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-slate-700 dark:bg-white transition-all"></i>
                        <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-slate-700 dark:bg-white transition-all"></i>
                        <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-slate-700 dark:bg-white transition-all"></i>
                    </div>
                </a>
            </li>
            {{-- Placeholder for fixed-plugin-button-nav --}}
            <li class="flex items-center px-4">
                <a href="javascript:;" class="block p-0 text-sm text-slate-700 dark:text-white transition-all ease-nav-brand">
                    <!-- fixed-plugin-button-nav -->
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- end Navbar -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('profileDropdownToggle');
        const dropdownMenu = document.getElementById('profileDropdownMenu');

        if (toggleBtn && dropdownMenu) {
            toggleBtn.addEventListener('click', function (e) {
                e.stopPropagation(); // prevent auto-close
                dropdownMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function (e) {
                if (!dropdownMenu.contains(e.target) && !toggleBtn.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        }
    });
</script>

