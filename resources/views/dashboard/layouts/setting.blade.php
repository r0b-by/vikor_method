@extends('dashboard.layouts.dashboardmain')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Futuristic Header with Gradient Text -->
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-600">
            Pengaturan Aplikasi
        </h1>
        <div class="h-1 flex-1 bg-gradient-to-r from-cyan-500/20 to-blue-500/20 ml-4 rounded-full"></div>
    </div>

    <!-- Futuristic Card with Glass Morphism Effect -->
    <div class="bg-gradient-to-br from-gray-800/80 to-gray-900/80 backdrop-blur-sm border border-gray-700/50 shadow-2xl rounded-xl p-6">
        <form action="{{ route('setting.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- App Name Field with Futuristic Styling -->
            <div class="mb-6">
                <label for="app_name" class="block text-sm font-medium text-gray-300 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Nama Aplikasi
                </label>
                <div class="relative">
                    <input type="text" id="app_name" name="app_name"
                           class="w-full bg-gray-800/50 border border-gray-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 text-gray-200 rounded-lg pl-12 pr-4 py-3 shadow-lg transition duration-200"
                           value="{{ config('app.name') }}"
                           placeholder="Masukkan nama aplikasi">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Timezone Field with Futuristic Dropdown -->
            <div class="mb-8">
                <label for="timezone" class="block text-sm font-medium text-gray-300 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Zona Waktu
                </label>
                <div class="relative">
                    <select id="timezone" name="timezone"
                            class="appearance-none w-full bg-gray-800/50 border border-gray-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 text-gray-200 rounded-lg pl-12 pr-10 py-3 shadow-lg transition duration-200">
                        @foreach(timezone_identifiers_list() as $tz)
                            <option value="{{ $tz }}" {{ config('app.timezone') == $tz ? 'selected' : '' }} class="bg-gray-800">{{ $tz }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Additional Futuristic Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- App Logo Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Logo Aplikasi
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col w-full h-32 border-2 border-dashed border-gray-700 hover:border-cyan-500 transition duration-200 rounded-lg cursor-pointer bg-gray-800/50 hover:bg-gray-800/70">
                            <div class="flex flex-col items-center justify-center pt-7">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="pt-1 text-sm text-gray-400">Upload logo</p>
                            </div>
                            <input type="file" class="opacity-0" name="app_logo">
                        </label>
                    </div>
                </div>

                <!-- Theme Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        Tema Aplikasi
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="relative">
                            <input class="sr-only peer" type="radio" name="theme" id="light-theme" value="light" checked>
                            <label class="flex flex-col items-center p-2 bg-gray-800/50 border border-gray-700 rounded-lg cursor-pointer hover:bg-gray-800 peer-checked:border-cyan-500 peer-checked:bg-gray-800 peer-checked:ring-1 peer-checked:ring-cyan-500 transition duration-200" for="light-theme">
                                <div class="w-8 h-8 rounded-full bg-yellow-300 mb-1"></div>
                                <span class="text-xs text-gray-300">Terang</span>
                            </label>
                        </div>
                        <div class="relative">
                            <input class="sr-only peer" type="radio" name="theme" id="dark-theme" value="dark">
                            <label class="flex flex-col items-center p-2 bg-gray-800/50 border border-gray-700 rounded-lg cursor-pointer hover:bg-gray-800 peer-checked:border-cyan-500 peer-checked:bg-gray-800 peer-checked:ring-1 peer-checked:ring-cyan-500 transition duration-200" for="dark-theme">
                                <div class="w-8 h-8 rounded-full bg-indigo-800 mb-1"></div>
                                <span class="text-xs text-gray-300">Gelap</span>
                            </label>
                        </div>
                        <div class="relative">
                            <input class="sr-only peer" type="radio" name="theme" id="system-theme" value="system">
                            <label class="flex flex-col items-center p-2 bg-gray-800/50 border border-gray-700 rounded-lg cursor-pointer hover:bg-gray-800 peer-checked:border-cyan-500 peer-checked:bg-gray-800 peer-checked:ring-1 peer-checked:ring-cyan-500 transition duration-200" for="system-theme">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-yellow-300 to-indigo-800 mb-1"></div>
                                <span class="text-xs text-gray-300">Sistem</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button with Futuristic Effects -->
            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 border border-transparent rounded-lg
                               font-semibold text-sm text-white uppercase tracking-wider
                               hover:from-cyan-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                               focus:ring-cyan-500/50 ring-offset-gray-900 shadow-lg transform hover:scale-105 transition-all
                               duration-200 ease-in-out flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection