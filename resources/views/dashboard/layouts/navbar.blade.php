<!-- Futuristic Navbar -->
<nav
    id="navbar-main"
    class="sticky top-0 z-10 w-full
           flex items-center justify-between px-4 py-3
           shadow-lg backdrop-blur-sm
           bg-gradient-to-r from-gray-900/95 to-gray-800/95 border-b border-gray-700/50"
>
    <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
        <!-- Breadcrumb with Glow Effect -->
        <nav class="flex items-center space-x-2">
            <ol class="flex items-center space-x-2">
                <li class="flex items-center">
                    <a href="{{route('dashboard')}}" class="text-sm font-medium text-cyan-400 hover:text-cyan-300 transition-colors duration-200">
                        <i class="fas fa-home mr-1"></i> Pages
                    </a>
                    <span class="mx-2 text-gray-400">/</span>
                </li>
                <li class="text-sm font-medium text-white">
                    {{ ucfirst(Request::segment(1)) }}
                </li>
            </ol>
            <h6 class="ml-2 text-lg font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">
                {{ ucfirst(Request::segment(1)) }}
            </h6>
        </nav>

        <!-- Right Side Controls -->
        <ul class="flex items-center space-x-4">
            <!-- Notifications for Admin -->
            @role('admin')
            <li class="relative">
                <a href="{{ route('admin.users.pending-registrations') }}" 
                   class="relative flex items-center justify-center w-10 h-10 rounded-full bg-gray-800 hover:bg-gray-700/80 transition-all duration-200 group">
                    <i class="fas fa-bell text-gray-300 group-hover:text-cyan-400"></i>
                    @if (isset($pendingRegistrationsCount) && $pendingRegistrationsCount > 0)
                        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full animate-pulse">
                            {{ $pendingRegistrationsCount }}
                        </span>
                    @endif
                </a>
            </li>
            @endrole

            <!-- User Profile Dropdown -->
            <li class="relative">
                @auth
                <div class="relative group">
                    <button type="button" id="profileDropdownToggle"
                        class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-gray-800 hover:bg-gray-700/80 transition-all duration-200 group">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden md:inline text-sm font-medium text-gray-200 group-hover:text-white">{{ Auth::user()->name }}</span>
                        <svg class="w-3 h-3 text-gray-400 group-hover:text-cyan-400 transition-transform duration-200" 
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Futuristic Dropdown Menu -->
                    <div id="profileDropdownMenu"
                        class="hidden absolute right-0 mt-2 w-56 bg-gray-800/95 backdrop-blur-lg border border-gray-700 rounded-xl shadow-2xl z-50 py-2 transition-all duration-200 origin-top-right transform opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100">
                        <div class="px-4 py-3 border-b border-gray-700/50">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <ul class="py-1">
                            <li>
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-cyan-400 transition-colors duration-150">
                                    <i class="fas fa-user-circle mr-3 text-cyan-400 w-4 text-center"></i>
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('setting') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-cyan-400 transition-colors duration-150">
                                    <i class="fas fa-cog mr-3 text-cyan-400 w-4 text-center"></i>
                                    Settings
                                </a>
                            </li>
                            <li class="border-t border-gray-700/50 my-1"></li>
                            <li>
                                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-red-400 transition-colors duration-150">
                                        <i class="fas fa-sign-out-alt mr-3 text-red-400 w-4 text-center"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                @endauth
            </li>

            <!-- Mobile Menu Toggle (Hamburger) -->
            <li class="flex items-center xl:hidden">
                <button sidenav-trigger class="p-2 rounded-lg hover:bg-gray-700/80 transition-colors duration-200 focus:outline-none">
                    <div class="w-5 space-y-1.5">
                        <span class="block h-0.5 w-full bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full transition-all duration-300"></span>
                        <span class="block h-0.5 w-4/5 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full ml-auto transition-all duration-200"></span>
                        <span class="block h-0.5 w-full bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full transition-all duration-300"></span>
                    </div>
                </button>
            </li>
        </ul>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('profileDropdownToggle');
        const dropdownMenu = document.getElementById('profileDropdownMenu');

        if (toggleBtn && dropdownMenu) {
            // Click handler for toggle button
            toggleBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
                
                // Toggle animation classes
                if (dropdownMenu.classList.contains('hidden')) {
                    dropdownMenu.classList.remove('opacity-100', 'scale-100');
                    dropdownMenu.classList.add('opacity-0', 'scale-95');
                } else {
                    dropdownMenu.classList.remove('opacity-0', 'scale-95');
                    dropdownMenu.classList.add('opacity-100', 'scale-100');
                }
            });

            // Close when clicking outside
            document.addEventListener('click', function (e) {
                if (!dropdownMenu.contains(e.target) && !toggleBtn.contains(e.target)) {
                    dropdownMenu.classList.add('hidden', 'opacity-0', 'scale-95');
                    dropdownMenu.classList.remove('opacity-100', 'scale-100');
                }
            });

            // Smooth hover effects
            toggleBtn.addEventListener('mouseenter', function() {
                if (dropdownMenu.classList.contains('hidden')) {
                    dropdownMenu.classList.remove('hidden');
                    setTimeout(() => {
                        dropdownMenu.classList.add('opacity-100', 'scale-100');
                        dropdownMenu.classList.remove('opacity-0', 'scale-95');
                    }, 10);
                }
            });
        }
    });
</script>