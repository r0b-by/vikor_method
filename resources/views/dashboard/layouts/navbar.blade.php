<br>
<!-- Navbar -->
<nav 
id="navbar-main"
class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all ease-in shadow-md duration-250 rounded-2xl lg:flex-nowrap lg:justify-start bg-white dark:bg-slate-800 text-slate-700 dark:text-white"
    navbar-main navbar-scroll="false">
    <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
        <nav>
            <!-- breadcrumb -->
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
                <li class="text-sm leading-normal">
                <a class="text-slate-500 dark:text-slate-300 opacity-70" href="{{route('dashboard')}}">Pages</a>
                </li>
                <li class="text-sm pl-2 capitalize leading-normal text-slate-700 dark:text-white">Dashboard</li>
            </ol>
            <h6 class="mb-0 font-bold capitalize text-slate-700 dark:text-white">Dashboard</h6>
        </nav>
            <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
                <!-- online builder btn  -->
                <!-- <li class="flex items-center">
            <a class="inline-block px-8 py-2 mb-0 mr-4 text-xs font-bold text-center text-blue-500 uppercase align-middle transition-all ease-in bg-transparent border border-blue-500 border-solid rounded-lg shadow-none cursor-pointer leading-pro hover:-translate-y-px active:shadow-xs hover:border-blue-500 active:bg-blue-500 active:hover:text-blue-500 hover:text-blue-500 tracking-tight-rem hover:bg-transparent hover:opacity-75 hover:shadow-none active:text-white active:hover:bg-transparent" target="_blank" href="https://www.creative-tim.com/builder/soft-ui?ref=navbar-dashboard&amp;_ga=2.76518741.1192788655.1647724933-1242940210.1644448053">Online Builder</a>
          </li> -->
                <li class="flex items-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                    class="block px-0 py-2 text-sm font-semibold text-slate-700 dark:text-white transition-all ease-nav-brand">
                            <i class="fas fa-sign-out-alt"></i>            
                            <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
                </li>
                <li class="flex items-center pl-4 xl:hidden">
                    <a href="javascript:;" class="block p-0 text-sm text-white transition-all ease-nav-brand"
                        sidenav-trigger>
                        <div class="w-4.5 overflow-hidden">
                            <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-slate-700 dark:bg-white transition-all"></i>
                            <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-slate-700 dark:bg-white transition-all"></i>
                            <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-slate-700 dark:bg-white transition-all"></i>
                        </div>
                    </a>
                </li>
                <li class="flex items-center px-4">
                    <a href="javascript:;" class="block p-0 text-sm text-slate-700 dark:text-white transition-all ease-nav-brand">
                        
                        <!-- fixed-plugin-button-nav  -->
                    </a>
                </li>

                <!-- notifications -->

            </ul>
        </div>
    </div>
</nav>

<!-- end Navbar -->
