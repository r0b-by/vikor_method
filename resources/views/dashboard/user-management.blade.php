@extends('dashboard.layouts.dashboardmain')
@section('title', 'Manajemen Pengguna')

@section('content')
<div class="w-full" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="300" data-aos-offset="0">
    <!-- Header Section -->
    <div class="flex flex-col space-y-4 mb-8">
        <h2 class="text-3xl xl:text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            Manajemen Pengguna
        </h2>
        <p class="text-slate-500 dark:text-slate-400">Kelola data pengguna sistem dengan antarmuka intuitif</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden border border-slate-200 dark:border-slate-700 transition-all duration-300 hover:shadow-xl">
        <!-- Card Header with Glass Effect -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 backdrop-blur-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <h3 class="text-xl font-semibold text-white">Daftar Pengguna Sistem</h3>
                <div class="mt-4 md:mt-0">
                    <div class="inline-flex items-center px-4 py-2 bg-white/10 rounded-lg text-white/80">
                        Total: {{ $users->total() }} Pengguna | Halaman {{ $users->currentPage() }}/{{ $users->lastPage() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Area -->
        <div class="px-6 pt-4">
            @if (session('success'))
                <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 dark:text-emerald-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-emerald-800 dark:text-emerald-200">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600 dark:text-rose-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-rose-800 dark:text-rose-200">{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <!-- Table Container -->
        <div class="px-6 pb-6 overflow-x-auto">
            <div class="min-w-full">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-700">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pengguna</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kontak</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Detail</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach ($users as $index => $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <!-- Correct numbering considering pagination -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">
                                {{ $users->firstItem() + $index }}
                            </td>
                            
                            <!-- User Info -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $user->getRoleNames()->first() ?: 'No Role' }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Contact -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900 dark:text-white">{{ $user->email }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $user->nis ?? 'N/A' }}</div>
                            </td>
                            
                            <!-- Details -->
                            <td class="px-4 py-4">
                                <div class="text-sm text-slate-900 dark:text-white">
                                    <span class="font-medium">Kelas:</span> {{ $user->kelas ?? '-' }}
                                </div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $user->jurusan ?? '' }} {{ $user->tahun_ajaran ? '| ' . $user->tahun_ajaran : '' }}
                                </div>
                            </td>
                            
                            <!-- Role -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($user->getRoleNames()->first() == 'admin') bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200
                                    @elseif($user->getRoleNames()->first() == 'teacher') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200
                                    @else bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200 @endif">
                                    {{ $user->getRoleNames()->first() ?: 'Tidak Ada' }}
                                </span>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($user->status == 'active') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200
                                    @else bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-200 @endif">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 p-1 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');" class="text-rose-600 hover:text-rose-900 dark:text-rose-400 dark:hover:text-rose-300 p-1 rounded-md hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Enhanced Pagination -->
            @if($users->hasPages())
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                <div class="text-sm text-slate-500 dark:text-slate-400">
                    Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} entri
                </div>
                
                <div class="flex items-center space-x-1">
                    <!-- Previous Page Link -->
                    @if($users->onFirstPage())
                        <span class="px-3 py-1 rounded-md bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 cursor-not-allowed">
                            &laquo;
                        </span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1 rounded-md bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 transition-colors">
                            &laquo;
                        </a>
                    @endif
                    
                    <!-- Page Numbers -->
                    @foreach(range(1, $users->lastPage()) as $page)
                        @if($page == $users->currentPage())
                            <span class="px-3 py-1 rounded-md bg-blue-500 text-white dark:bg-blue-600">{{ $page }}</span>
                        @else
                            <a href="{{ $users->url($page) }}" class="px-3 py-1 rounded-md bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                    
                    <!-- Next Page Link -->
                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1 rounded-md bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 transition-colors">
                            &raquo;
                        </a>
                    @else
                        <span class="px-3 py-1 rounded-md bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 cursor-not-allowed">
                            &raquo;
                        </span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection