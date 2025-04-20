@extends('dashboard.layouts.dashboardmain')
@section('title', 'Home')
@section('content')
    <div class="w-full px-6 pt-6 mx-auto">

        <!-- Row Cards -->
        <div class="flex flex-wrap -mx-3">
            <!-- Card 1: Alternatif -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:w-1/2">
                <div class="relative flex flex-col bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-orange-300 dark:border-slate-600">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="w-2/3 px-3">
                                <p class="mb-0 text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase">Jumlah Alternatif</p>
                                <h5 class="text-2xl font-bold text-gray-800 dark:text-white">
                                    {{ $latestAlternatif ? $latestAlternatif->id : 0 }}
                                </h5>
                            </div>
                            <div class="w-1/3 px-3 text-right">
                                <div class="inline-block w-12 h-12 bg-gradient-to-br from-orange-400 to-yellow-500 rounded-full text-white text-center leading-[3rem]">
                                    <i class="ni ni-money-coins text-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Criteria -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:w-1/2">
                <div class="relative flex flex-col bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-orange-300 dark:border-slate-600">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="w-2/3 px-3">
                                <p class="mb-0 text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase">Jumlah Kriteria</p>
                                <h5 class="text-2xl font-bold text-gray-800 dark:text-white">
                                    {{ $latestCriteria ? $latestCriteria->id : 0 }}
                                </h5>
                            </div>
                            <div class="w-1/3 px-3 text-right">
                                <div class="inline-block w-12 h-12 bg-gradient-to-br from-red-500 to-orange-500 rounded-full text-white text-center leading-[3rem]">
                                    <i class="ni ni-world text-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="mt-6">
    <div class="p-6 bg-white dark:bg-slate-800 rounded-lg border border-orange-300 dark:border-slate-600">
        <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Jumlah Data</h4>
        <canvas id="dataChart" class="w-full max-w-2xl h-60 mx-auto"></canvas>
    </div>
</div>


        <!-- Jurnal Section -->
        <div class="mt-6">
            <div class="p-6 bg-white dark:bg-slate-800 rounded-lg border border-orange-300 dark:border-slate-600 text-center">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Artikel Jurnal Sistem</h4>
                <a href="#" class="inline-block px-6 py-2 text-sm font-medium text-white bg-orange-500 rounded-full hover:bg-orange-600 transition">
                    Buka Jurnal
                </a>
            </div>
        </div>
    </div>
@endsection

