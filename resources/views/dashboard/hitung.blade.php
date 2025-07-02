@extends('dashboard.layouts.dashboardmain')
@section('title', 'Perhitungan VIKOR')

@push('styles')
<style>
    /* Loading Animation Styles */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.85);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        color: white;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .spinner {
        width: 80px;
        height: 80px;
        border: 8px solid rgba(100, 200, 255, 0.3);
        border-radius: 50%;
        border-top-color: #4fc3f7;
        border-bottom-color: #4fc3f7;
        animation: spin 1.5s linear infinite;
        margin-bottom: 25px;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .progress-container {
        width: 80%;
        max-width: 400px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        height: 10px;
        margin: 15px 0;
        overflow: hidden;
    }
    
    .progress-bar {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #4fc3f7, #64ffda);
        transition: width 0.5s ease;
    }
    
    .progress-text {
        margin-top: 15px;
        font-size: 1.1rem;
        text-align: center;
        color: #e0f7fa;
    }
    
    .tech-particles {
        position: absolute;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: -1;
    }
    
    .particle {
        position: absolute;
        background: rgba(100, 200, 255, 0.6);
        border-radius: 50%;
        pointer-events: none;
        filter: blur(1px);
    }
    
    /* Calculation Steps Animation */
    .calculation-step {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s ease, transform 0.8s ease;
        margin-bottom: 30px;
    }
    
    .calculation-step.visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Futuristic Card Style */
    .futuristic-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
    
    .futuristic-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    }
    
    /* Glowing effect for important elements */
    .glow-effect {
        text-shadow: 0 0 10px rgba(79, 195, 247, 0.7);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .progress-text {
            font-size: 0.9rem;
        }
        
        .calculation-step {
            margin-bottom: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="flex flex-wrap -mx-3" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="300" data-aos-offset="0">
    <!-- Futuristic Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="spinner"></div>
        <div class="progress-container">
            <div class="progress-bar" id="progressBar"></div>
        </div>
        <div class="progress-text" id="progressText">Menginisialisasi sistem perhitungan...</div>
        <div class="tech-particles" id="techParticles"></div>
    </div>

    <div class="flex items-center justify-between mb-6 w-full">
        <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">
            <span class="glow-effect">Perhitungan VIKOR</span> 
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-teal-400">AI-Enhanced</span>
        </h2>
    </div>

    {{-- Form for Calculation --}}
    <div class="w-full px-3 mb-6">
        <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
            <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-calendar-alt mr-2"></i>Parameter Perhitungan</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-6 overflow-x-auto">
                    <form id="calculationForm" action="{{ route('hitung.perform') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label for="tahun_ajaran_hitung" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-graduation-cap mr-1"></i>Tahun Ajaran
                                </label>
                                <select name="tahun_ajaran_hitung" id="tahun_ajaran_hitung" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                    @foreach($academicPeriods->pluck('tahun_ajaran')->unique() as $tahun)
                                        <option value="{{ $tahun }}" {{ $currentTahunAjaran == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="semester_hitung" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-calendar mr-1"></i>Semester
                                </label>
                                <select name="semester_hitung" id="semester_hitung" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                    <option value="Ganjil" {{ $currentSemester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="Genap" {{ $currentSemester == 'Genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-500 to-teal-500 hover:from-blue-600 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                                    <i class="fas fa-rocket mr-2"></i>Mulai Perhitungan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="w-full px-3 mb-6">
            <div class="relative p-4 mb-4 text-green-700 bg-green-100 rounded-lg border border-green-300 dark:bg-green-200 dark:text-green-800">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2 text-lg"></i>
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline ml-1">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="w-full px-3 mb-6">
            <div class="relative p-4 mb-4 text-red-700 bg-red-100 rounded-lg border border-red-300 dark:bg-red-200 dark:text-red-800">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2 text-lg"></i>
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline ml-1">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif
    
    @if(isset($calculationMessage))
        <div class="w-full px-3 mb-6">
            <div class="relative p-4 mb-4 text-blue-700 bg-blue-100 rounded-lg border border-blue-300 dark:bg-blue-200 dark:text-blue-800">
                <div class="flex items-center">
                    <i class="fas fa-info-circle mr-2 text-lg"></i>
                    <span class="block sm:inline">{{ $calculationMessage }}</span>
                </div>
            </div>
        </div>
    @endif

    {{-- Navigation Tabs --}}
    <div class="w-full px-3 mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="hitungTabs" data-tabs-toggle="#hitungTabContent" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-blue-300" id="proses-tab" data-tabs-target="#proses" type="button" role="tab" aria-controls="proses" aria-selected="true">
                        <i class="fas fa-calculator mr-2"></i>Proses Hitung
                    </button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-blue-600 hover:border-blue-300 dark:hover:text-blue-300" id="riwayat-tab" data-tabs-target="#riwayat" type="button" role="tab" aria-controls="riwayat" aria-selected="false">
                        <i class="fas fa-history mr-2"></i>Riwayat Hitung
                    </button>
                </li>
            </ul>
        </div>
    </div>

    {{-- Tab Content --}}
    <div id="hitungTabContent" class="w-full">
        {{-- Proses Hitung Tab --}}
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="proses" role="tabpanel" aria-labelledby="proses-tab">
            @if($noDataMessage)
                <div class="w-full px-3 mb-6">
                    <div class="relative p-4 mb-4 text-yellow-700 bg-yellow-100 rounded-lg border border-yellow-300 dark:bg-yellow-200 dark:text-yellow-800">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2 text-lg"></i>
                            <span class="block sm:inline">{{ $noDataMessage }}</span>
                        </div>
                    </div>
                </div>
            @elseif($alternatifs->isNotEmpty())
                {{-- Students List --}}
                <div id="studentsList" class="calculation-step">
                    <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
                        <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                            <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-users mr-2"></i>Daftar Siswa untuk Perhitungan</h6>
                        </div>
                        <div class="flex-auto px-0 pt-0 pb-2">
                            <div class="p-6 overflow-x-auto">
                                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                    <thead class="align-bottom">
                                        <tr>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Nama Siswa</th>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Tahun Ajaran</th>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Semester</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($alternatifs as $alt)
                                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                        {{ $alt->user->name ?? $alt->alternatif_name }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                        {{ $currentTahunAjaran }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                        {{ $currentSemester }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if($calculationPerformed)
                    {{-- Normalization Results --}}
                    <div id="normalizationResults" class="calculation-step">
                        <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
                            <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                                <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-chart-line mr-2"></i>Hasil Normalisasi</h6>
                            </div>
                            <div class="flex-auto px-0 pt-0 pb-2">
                                <div class="p-6 overflow-x-auto">
                                    <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                        <thead class="align-bottom">
                                            <tr>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Nama</th>
                                                @foreach($criterias as $c)
                                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">{{ $c->criteria_name }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($alternatifs as $alt)
                                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ $alt->user->name ?? $alt->alternatif_name }}
                                                        </span>
                                                    </td>
                                                    @foreach($criterias as $c)
                                                        <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                            <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                                {{ number_format($normalizedValues[$alt->id][$c->id] ?? 0, 4) }}
                                                            </span>
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Weighted Normalization Results --}}
                    <div id="weightedResults" class="calculation-step">
                        <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
                            <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                                <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-weight-hanging mr-2"></i>Normalisasi Terbobot</h6>
                            </div>
                            <div class="flex-auto px-0 pt-0 pb-2">
                                <div class="p-6 overflow-x-auto">
                                    <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                        <thead class="align-bottom">
                                            <tr>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Nama</th>
                                                @foreach($criterias as $c)
                                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">{{ $c->criteria_name }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($alternatifs as $alt)
                                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ $alt->user->name ?? $alt->alternatif_name }}
                                                        </span>
                                                    </td>
                                                    @foreach($criterias as $c)
                                                        <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                            <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                                {{ number_format($weightedNormalization[$alt->id][$c->id] ?? 0, 4) }}
                                                            </span>
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ideal Values --}}
                    <div id="idealValues" class="calculation-step">
                        <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
                            <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                                <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-star mr-2"></i>Nilai Ideal (F*)</h6>
                            </div>
                            <div class="flex-auto px-0 pt-0 pb-2">
                                <div class="p-6 overflow-x-auto">
                                    <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                        <thead class="align-bottom">
                                            <tr>
                                                @foreach($criterias as $c)
                                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">{{ $c->criteria_name }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                @foreach($criterias as $c)
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ number_format($ideal[$c->id] ?? 0, 4) }}
                                                        </span>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Final Results --}}
                    <div id="finalResults" class="calculation-step">
                        <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
                            <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                                <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-trophy mr-2"></i>Hasil Akhir VIKOR</h6>
                            </div>
                            <div class="flex-auto px-0 pt-0 pb-2">
                                <div class="p-6 overflow-x-auto">
                                    <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                        <thead class="align-bottom">
                                            <tr>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Nama Siswa</th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Qi</th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Si</th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Ri</th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Ranking</th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sortedResults = collect($alternatifs)->map(function ($alternatif) use ($finalValues, $ranking, $Si, $Ri) {
                                                    return [
                                                        'id' => $alternatif->id,
                                                        'name' => $alternatif->user->name ?? $alternatif->alternatif_name,
                                                        'nilai_q' => $finalValues[$alternatif->id] ?? 0,
                                                        'nilai_s' => $Si[$alternatif->id] ?? 0,
                                                        'nilai_r' => $Ri[$alternatif->id] ?? 0,
                                                        'ranking' => $ranking[$alternatif->id] ?? null,
                                                        'status' => ($ranking[$alternatif->id] !== null && $ranking[$alternatif->id] <= 10) ? 'Lulus' : 'Tidak Lulus'
                                                    ];
                                                })->sortBy('ranking')->values()->all();
                                            @endphp

                                            @foreach($sortedResults as $result)
                                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ $result['name'] }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ number_format($result['nilai_q'], 4) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ number_format($result['nilai_s'], 4) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ number_format($result['nilai_r'], 4) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ $result['ranking'] ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80 {{ $result['status'] == 'Lulus' ? 'text-green-500' : 'text-red-500' }}">
                                                            {{ $result['status'] }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Save Button --}}
                    <div id="saveSection" class="calculation-step">
                        <div class="flex justify-end mt-6">
                            <form action="{{ route('hitung.simpan') }}" method="POST">
                                @csrf
                                @foreach($alternatifs as $alt)
                                    <input type="hidden" name="alternatif_ids[]" value="{{ $alt->id }}">
                                    <input type="hidden" name="finalValues[{{ $alt->id }}]" value="{{ $finalValues[$alt->id] ?? 0 }}">
                                    <input type="hidden" name="ranking[{{ $alt->id }}]" value="{{ $ranking[$alt->id] ?? null }}">
                                    <input type="hidden" name="Si[{{ $alt->id }}]" value="{{ $Si[$alt->id] ?? 0 }}">
                                    <input type="hidden" name="Ri[{{ $alt->id }}]" value="{{ $Ri[$alt->id] ?? 0 }}">
                                @endforeach
                                <input type="hidden" name="tahun_ajaran" value="{{ $currentTahunAjaran }}">
                                <input type="hidden" name="semester" value="{{ $currentSemester }}">
                                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-save mr-2"></i>Simpan Hasil Perhitungan
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        {{-- Riwayat Hitung Tab --}}
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="riwayat" role="tabpanel" aria-labelledby="riwayat-tab">
            <div class="flex-none w-full max-w-full px-3 overflow-x-hidden">
                <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
                    <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-archive mr-2"></i>Riwayat Perhitungan</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6 overflow-x-auto">
                            @if($historicalResults->isEmpty())
                                <div class="text-center py-8">
                                    <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 dark:text-gray-400">Belum ada hasil perhitungan yang disimpan</p>
                                </div>
                            @else
                                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                    <thead class="align-bottom">
                                        <tr>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Nama Siswa</th>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Tahun Ajaran</th>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Semester</th>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Nilai Qi</th>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Ranking</th>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Status</th>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($historicalResults as $result)
                                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                        {{ $result->alternatif->user->name ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                        {{ $result->tahun_ajaran }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                        {{ $result->semester }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                        {{ number_format($result->nilai_q, 4) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                        {{ $result->ranking }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80 {{ $result->status == 'Lulus' ? 'text-green-500' : 'text-red-500' }}">
                                                        {{ $result->status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <div class="flex space-x-2">
                                                        <a href="#" class="text-blue-500 hover:text-blue-700" title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if($result->status != 'final')
                                                            <form action="{{ route('hitung.simpan', $result->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit" class="text-green-500 hover:text-green-700" title="Finalkan">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calculationForm = document.getElementById('calculationForm');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const progressText = document.getElementById('progressText');
        const progressBar = document.getElementById('progressBar');
        const techParticles = document.getElementById('techParticles');
        
        // Form submission handler
        if (calculationForm) {
            calculationForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show loading overlay with animation
                loadingOverlay.style.display = 'flex';
                createParticles();
                
                // Simulate calculation progress
                simulateCalculationProgress();
                
                // Actually submit the form after showing loading animation
                setTimeout(() => {
                    this.submit();
                }, 100);
            });
        }
        
        // Simulate calculation progress
        function simulateCalculationProgress() {
            const steps = [
                { text: "Memproses data siswa...", progress: 10 },
                { text: "Menormalisasi nilai kriteria...", progress: 25 },
                { text: "Menghitung bobot normalisasi...", progress: 40 },
                { text: "Menentukan nilai ideal...", progress: 55 },
                { text: "Menghitung nilai S dan R...", progress: 70 },
                { text: "Menyusun peringkat akhir...", progress: 85 },
                { text: "Menyiapkan hasil...", progress: 95 },
                { text: "Perhitungan selesai!", progress: 100 }
            ];
            
            let currentStep = 0;
            
            const interval = setInterval(() => {
                if (currentStep < steps.length) {
                    progressText.textContent = steps[currentStep].text;
                    progressBar.style.width = steps[currentStep].progress + '%';
                    currentStep++;
                } else {
                    clearInterval(interval);
                }
            }, 800); // Adjust timing between steps
        }
        
        // Create floating particles effect
        function createParticles() {
            // Clear existing particles
            techParticles.innerHTML = '';
            
            // Create particles
            const particleCount = 30;
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random properties
                const size = Math.random() * 10 + 5;
                const posX = Math.random() * 100;
                const posY = Math.random() * 100;
                const opacity = Math.random() * 0.5 + 0.3;
                const animationDuration = Math.random() * 15 + 5;
                const delay = Math.random() * 5;
                const color = `hsl(${Math.random() * 60 + 180}, 80%, 70%)`;
                
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${posX}%`;
                particle.style.top = `${posY}%`;
                particle.style.opacity = opacity;
                particle.style.background = color;
                particle.style.animation = `float ${animationDuration}s ${delay}s infinite ease-in-out`;
                
                techParticles.appendChild(particle);
            }
            
            // Add CSS for floating animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes float {
                    0% { transform: translateY(0) translateX(0); opacity: 0.3; }
                    50% { transform: translateY(-20px) translateX(10px); opacity: 0.8; }
                    100% { transform: translateY(0) translateX(0); opacity: 0.3; }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Animate calculation steps when they become visible
        const observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);
        
        // Observe all calculation steps
        document.querySelectorAll('.calculation-step').forEach(step => {
            observer.observe(step);
        });
        
        // If calculation was already performed (page refresh), show all steps with delays
        if ({{ $calculationPerformed ? 'true' : 'false' }}) {
            const steps = document.querySelectorAll('.calculation-step');
            steps.forEach((step, index) => {
                setTimeout(() => {
                    step.classList.add('visible');
                }, index * 300); // Staggered appearance
            });
        }
        
        // Tab switching functionality
        const hitungTabs = document.getElementById('hitungTabs');
        if (hitungTabs) {
            const tabButtons = hitungTabs.querySelectorAll('button');
            const tabContents = document.getElementById('hitungTabContent').querySelectorAll('[role="tabpanel"]');
            
            function activateTab(tabId) {
                tabButtons.forEach(button => {
                    if (button.getAttribute('data-tabs-target').substring(1) === tabId) {
                        button.classList.add('border-blue-500', 'text-blue-600');
                        button.setAttribute('aria-selected', 'true');
                    } else {
                        button.classList.remove('border-blue-500', 'text-blue-600');
                        button.setAttribute('aria-selected', 'false');
                    }
                });
                
                tabContents.forEach(content => {
                    if (content.id === tabId) {
                        content.classList.remove('hidden');
                    } else {
                        content.classList.add('hidden');
                    }
                });
            }
            
            // Handle initial tab based on URL
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab') || 'proses';
            activateTab(activeTab);
            
            // Add click handlers for tabs
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-tabs-target').substring(1);
                    activateTab(targetId);
                    
                    // Update URL without reload
                    const newUrl = new URL(window.location.href);
                    newUrl.searchParams.set('tab', targetId);
                    window.history.pushState({ path: newUrl.href }, '', newUrl.href);
                });
            });
        }
    });
</script>
@endpush
@endsection