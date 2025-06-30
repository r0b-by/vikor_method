{{-- resources/views/dashboard/home.blade.php --}}

@extends('dashboard.layouts.dashboardmain')
@section('title', 'Home')
@section('content')
<div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Dashboard</h2>
    </div>
    <div class="w-full px-6 pt-6 mx-auto">
        
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:w-1/2">
                <div class="relative flex flex-col bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-orange-300 dark:border-slate-600">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="w-2/3 px-3">
                                <p class="mb-0 text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase">Jumlah Alternatif</p>
                                <h5 class="text-2xl font-bold text-gray-800 dark:text-white">
                                    {{ $alternatifCount ?? 0 }}
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

            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:w-1/2">
                <div class="relative flex flex-col bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-orange-300 dark:border-slate-600">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="w-2/3 px-3">
                                <p class="mb-0 text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase">Jumlah Kriteria</p>
                                <h5 class="text-2xl font-bold text-gray-800 dark:text-white">
                                    {{ $criteriaCount ?? 0 }}
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

            <!-- Penilaian -->
    <div class="w-full max-w-full px-3 mb-6 sm:w-1/3 xl:w-1/3">
        <div class="relative flex flex-col bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-orange-300 dark:border-slate-600">
            <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                    <div class="w-2/3 px-3">
                        <p class="mb-0 text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase">Jumlah Penilaian</p>
                        <h5 class="text-2xl font-bold text-gray-800 dark:text-white">
                            {{ $penilaianCount ?? 0 }}
                        </h5>
                    </div>
                    <div class="w-1/3 px-3 text-right">
                        <div class="inline-block w-12 h-12 bg-gradient-to-br from-blue-500 to-teal-400 rounded-full text-white text-center leading-[3rem]">
                            <i class="ni ni-check-bold text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hasil VIKOR -->
    <div class="w-full max-w-full px-3 mb-6 sm:w-1/3 xl:w-1/3">
        <div class="relative flex flex-col bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-orange-300 dark:border-slate-600">
            <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                    <div class="w-2/3 px-3">
                        <p class="mb-0 text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase">Hasil VIKOR</p>
                        <h5 class="text-2xl font-bold text-gray-800 dark:text-white">
                            {{ $hasilVikorCount ?? 0 }}
                        </h5>
                    </div>
                    <div class="w-1/3 px-3 text-right">
                        <div class="inline-block w-12 h-12 bg-gradient-to-br from-green-500 to-lime-400 rounded-full text-white text-center leading-[3rem]">
                            <i class="ni ni-chart-bar-32 text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah Pengguna -->
    <div class="w-full max-w-full px-3 mb-6 sm:w-1/3 xl:w-1/3">
        <div class="relative flex flex-col bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-orange-300 dark:border-slate-600">
            <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                    <div class="w-2/3 px-3">
                        <p class="mb-0 text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase">Jumlah Pengguna</p>
                        <h5 class="text-2xl font-bold text-gray-800 dark:text-white">
                            {{ $userCount ?? 0 }}
                        </h5>
                    </div>
                    <div class="w-1/3 px-3 text-right">
                        <div class="inline-block w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full text-white text-center leading-[3rem]">
                            <i class="ni ni-single-02 text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>

        <div class="mt-6">
            <div class="p-6 bg-white dark:bg-slate-800 rounded-lg shadow-md border border-orange-300 dark:border-slate-600">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Jumlah Data Sistem</h4>
                {{-- *** PERBAIKAN PENTING DI SINI *** --}}
                {{-- Bungkus canvas dalam div dengan tinggi tetap dan posisi relatif --}}
                <div class="relative w-full h-60 max-w-2xl mx-auto">
                    <canvas id="dataChart"></canvas>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div class="p-6 bg-white dark:bg-slate-800 rounded-lg border border-orange-300 dark:border-slate-600 text-center">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Artikel Jurnal Sistem</h4>
                <a href="#" class="inline-block px-6 py-2 text-sm font-medium text-white bg-orange-500 rounded-full hover:bg-orange-600 transition">
                    Buka Jurnal
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Alternatif Count:', {{ $alternatifCount ?? 'null' }});
            console.log('Criteria Count:', {{ $criteriaCount ?? 'null' }});
            console.log('Penilaian Count:', {{ $penilaianCount ?? 'null' }});
            console.log('Hasil VIKOR Count:', {{ $hasilVikorCount ?? 'null' }});
            console.log('User Count:', {{ $userCount ?? 'null' }});

            var ctx = document.getElementById('dataChart');

            if (ctx) {
                var dataChart = new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Alternatif', 'Kriteria', 'Penilaian', 'Hasil VIKOR', 'Pengguna'],
                        datasets: [{
                            label: 'Jumlah Data',
                            data: [
                                {{ $alternatifCount ?? 0 }},
                                {{ $criteriaCount ?? 0 }},
                                {{ $penilaianCount ?? 0 }},
                                {{ $hasilVikorCount ?? 0 }},
                                {{ $userCount ?? 0 }}
                            ],
                            backgroundColor: [
                                'rgba(255, 159, 64, 0.8)',
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(153, 102, 255, 0.8)'
                            ],
                            borderColor: [
                                'rgba(255, 159, 64, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // Ini penting agar tinggi chart bisa dikontrol oleh CSS
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            } else {
                console.error('Elemen canvas dengan ID "dataChart" tidak ditemukan!');
            }
        });
    </script>
    @endpush
@endsection