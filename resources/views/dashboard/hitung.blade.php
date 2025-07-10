@extends('dashboard.layouts.dashboardmain')
@section('title', 'Perhitungan VIKOR')

@push('styles')
<style>
    /* Futuristic Loading Animation */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(ellipse at center, rgba(0,10,20,0.95) 0%, rgba(0,5,15,0.98) 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        color: white;
        font-family: 'Orbitron', 'Segoe UI', sans-serif;
        overflow: hidden;
    }
    
    .loading-container {
        position: relative;
        width: 80%;
        max-width: 600px;
        padding: 30px;
        background: rgba(10,25,50,0.3);
        border: 1px solid rgba(100,200,255,0.2);
        border-radius: 15px;
        box-shadow: 0 0 30px rgba(0,150,255,0.2);
        backdrop-filter: blur(10px);
        text-align: center;
    }
    
    .loading-title {
        font-size: 1.8rem;
        margin-bottom: 20px;
        color: #4fc3f7;
        text-shadow: 0 0 10px rgba(79,195,247,0.7);
        letter-spacing: 2px;
    }
    
    .loading-subtitle {
        font-size: 1rem;
        margin-bottom: 30px;
        color: #a0d8ff;
        opacity: 0.8;
    }
    
    .progress-container {
        width: 100%;
        height: 10px;
        background: rgba(50,100,150,0.2);
        border-radius: 5px;
        margin: 20px 0;
        overflow: hidden;
        position: relative;
    }
    
    .progress-bar {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #00c6ff, #0072ff);
        border-radius: 5px;
        transition: width 0.5s ease;
        position: relative;
        overflow: hidden;
    }
    
    .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            90deg,
            rgba(255,255,255,0) 0%,
            rgba(255,255,255,0.6) 50%,
            rgba(255,255,255,0) 100%
        );
        animation: shine 2s infinite;
    }
    
    @keyframes shine {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .progress-text {
        font-size: 0.9rem;
        color: #7fdbff;
        margin-top: 10px;
        min-height: 20px;
    }
    
    .progress-percent {
        font-size: 1.2rem;
        font-weight: bold;
        color: #00d4ff;
        margin-top: 5px;
    }
    
    .loading-details {
        margin-top: 20px;
        font-size: 0.8rem;
        color: #8ab4f8;
        opacity: 0.7;
    }
    
    /* Holographic Effects */
    .holographic-line {
        position: absolute;
        height: 1px;
        width: 100%;
        background: linear-gradient(90deg, transparent, rgba(0,200,255,0.5), transparent);
        animation: scanline 4s linear infinite;
    }
    
    @keyframes scanline {
        0% { top: 0%; opacity: 0; }
        10% { opacity: 0.8; }
        90% { opacity: 0.8; }
        100% { top: 100%; opacity: 0; }
    }
    
    .binary-code {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        opacity: 0.1;
        background-image: 
            linear-gradient(rgba(0,255,255,0.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0,255,255,0.1) 1px, transparent 1px);
        background-size: 20px 20px;
    }
    
    .particles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }
    
    .particle {
        position: absolute;
        background: rgba(100,200,255,0.6);
        border-radius: 50%;
        pointer-events: none;
        filter: blur(1px);
    }
    
    /* Matrix Rain Effect */
    .matrix-rain {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -1;
        opacity: 0.3;
    }
    
    .matrix-column {
        position: relative;
        float: left;
        width: 20px;
        height: 100%;
        font-size: 18px;
        writing-mode: vertical-rl;
        text-orientation: upright;
        color: #0f0;
        text-shadow: 0 0 5px #0f0;
        opacity: 0.8;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .loading-container {
            width: 90%;
            padding: 20px;
        }
        
        .loading-title {
            font-size: 1.4rem;
        }
        
        .progress-text {
            font-size: 0.8rem;
        }
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
    
    /* Calculation details styling */
    .calculation-details {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .calculation-formula {
        font-family: 'Courier New', monospace;
        background: rgba(0, 0, 0, 0.1);
        padding: 8px 12px;
        border-radius: 4px;
        margin: 10px 0;
    }
    
    .criteria-type {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: bold;
    }
    
    .criteria-benefit {
        background-color: rgba(76, 175, 80, 0.2);
        color: #4CAF50;
    }
    
    .criteria-cost {
        background-color: rgba(244, 67, 54, 0.2);
        color: #F44336;
    }
</style>
@endpush

@section('content')
<div class="flex flex-wrap -mx-3" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="300" data-aos-offset="0">

    <div class="flex items-center justify-between mb-6 w-full">
        <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">
            <span class="glow-effect">Perhitungan VIKOR</span> 
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-teal-400">AI-Enhanced</span>
        </h2>
    </div>

    <!-- Futuristic Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="matrix-rain" id="matrixRain"></div>
        <div class="particles" id="techParticles"></div>
        <div class="binary-code"></div>
        <div class="holographic-line"></div>
        
        <div class="loading-container">
            <h3 class="loading-title">SISTEM PERHITUNGAN VIKOR</h3>
            <p class="loading-subtitle">Memproses data siswa...</p>
            
            <div class="progress-container">
                <div class="progress-bar" id="progressBar"></div>
            </div>
            
            <div class="progress-percent" id="progressPercent">0%</div>
            <div class="progress-text" id="progressText">Menginisialisasi sistem...</div>
            <div class="loading-details" id="loadingDetails">
                <div>Status: <span id="statusText">Menyiapkan lingkungan komputasi</span></div>
                <div>Memori: <span id="memoryText">0 MB</span> / CPU: <span id="cpuText">0%</span></div>
            </div>
        </div>
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
                    {{-- Decision Matrix --}}
                    <div id="decisionMatrix" class="calculation-step">
                        <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
                            <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                                <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-table mr-2"></i>Matriks Keputusan Awal</h6>
                            </div>
                            <div class="flex-auto px-0 pt-0 pb-2">
                                <div class="p-6 overflow-x-auto">
                                    <div class="calculation-details mb-4">
                                        <p class="text-sm dark:text-gray-300 mb-2">
                                            Matriks keputusan awal berisi nilai-nilai alternatif untuk setiap kriteria.
                                        </p>
                                    </div>
                                    <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                        <thead class="align-bottom">
                                            <tr>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Nama</th>
                                                @foreach($criterias as $c)
                                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">
                                                        {{ $c->criteria_name }}
                                                        <span class="criteria-type {{ $c->criteria_type == 'Benefit' ? 'criteria-benefit' : 'criteria-cost' }}">
                                                            {{ $c->criteria_type }}
                                                        </span>
                                                    </th>
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
                                                                {{ $decisionMatrix[$alt->id][$c->id]['value'] ?? 0 }}
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
                                <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-bullseye mr-2"></i>Nilai Ideal (X+ dan X-)</h6>
                            </div>
                            <div class="flex-auto px-0 pt-0 pb-2">
                                <div class="p-6 overflow-x-auto">
                                    <div class="calculation-details mb-4">
                                        <p class="text-sm dark:text-gray-300 mb-2">
                                            Nilai ideal terbaik (X+) dan ideal terburuk (X-) untuk setiap kriteria.
                                        </p>
                                        <div class="calculation-formula">
                                            Untuk kriteria Benefit: X+ = max(X), X- = min(X)<br>
                                            Untuk kriteria Cost: X+ = min(X), X- = max(X)
                                        </div>
                                    </div>
                                    <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                        <thead class="align-bottom">
                                            <tr>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Kriteria</th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Tipe</th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">X+ (Ideal Terbaik)</th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">X- (Ideal Terburuk)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($criterias as $c)
                                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ $c->criteria_name }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            <span class="criteria-type {{ $c->criteria_type == 'Benefit' ? 'criteria-benefit' : 'criteria-cost' }}">
                                                                {{ $c->criteria_type }}
                                                            </span>
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ $X_plus[$c->id] ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ $X_minus[$c->id] ?? 0 }}
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

                    {{-- Normalization Results --}}
                    <div id="normalizationResults" class="calculation-step">
                        <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
                            <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                                <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-chart-line mr-2"></i>Hasil Normalisasi</h6>
                            </div>
                            <div class="flex-auto px-0 pt-0 pb-2">
                                <div class="p-6 overflow-x-auto">
                                    <div class="calculation-details mb-4">
                                        <p class="text-sm dark:text-gray-300 mb-2">
                                            Matriks yang telah dinormalisasi menggunakan rumus VIKOR.
                                        </p>
                                        <div class="calculation-formula">
                                            Untuk kriteria Benefit: (X+ - Xij) / (X+ - X-)<br>
                                            Untuk kriteria Cost: (Xij - X+) / (X- - X+)
                                        </div>
                                    </div>
                                    <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                        <thead class="align-bottom">
                                            <tr>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Nama</th>
                                                @foreach($criterias as $c)
                                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">
                                                        {{ $c->criteria_name }}
                                                        <span class="criteria-type {{ $c->criteria_type == 'Benefit' ? 'criteria-benefit' : 'criteria-cost' }}">
                                                            {{ $c->criteria_type }}
                                                        </span>
                                                    </th>
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
                                    <div class="calculation-details mb-4">
                                        <p class="text-sm dark:text-gray-300 mb-2">
                                            Matriks normalisasi yang telah dikalikan dengan bobot kriteria.
                                        </p>
                                        <div class="calculation-formula">
                                            Nilai Terbobot = Nilai Normalisasi × Bobot Kriteria
                                        </div>
                                    </div>
                                    <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                        <thead class="align-bottom">
                                            <tr>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Nama</th>
                                                @foreach($criterias as $c)
                                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">
                                                        {{ $c->criteria_name }}
                                                        <span class="text-xs">(Bobot: {{ $c->weight }})</span>
                                                    </th>
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
                                                                {{ number_format($weightedNormalizedValues[$alt->id][$c->id] ?? 0, 4) }}
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

                    {{-- Si and Ri Values --}}
                    <div id="siRiResults" class="calculation-step">
                        <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
                            <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                                <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-calculator mr-2"></i>Nilai Si dan Ri</h6>
                            </div>
                            <div class="flex-auto px-0 pt-0 pb-2">
                                <div class="p-6 overflow-x-auto">
                                    <div class="calculation-details mb-4">
                                        <p class="text-sm dark:text-gray-300 mb-2">
                                            Nilai Si (Utility Measure) dan Ri (Regret Measure) untuk setiap alternatif.
                                        </p>
                                        <div class="calculation-formula">
                                            Si = Σ (Bobot Kriteria × Nilai Normalisasi)<br>
                                            Ri = max(Bobot Kriteria × Nilai Normalisasi)
                                        </div>
                                        <p class="text-sm dark:text-gray-300 mt-2">
                                            <strong>Rentang Nilai:</strong><br>
                                            S+ = {{ number_format($S_plus, 4) }} (maksimum Si)<br>
                                            S- = {{ number_format($S_minus, 4) }} (minimum Si)<br>
                                            R+ = {{ number_format($R_plus, 4) }} (maksimum Ri)<br>
                                            R- = {{ number_format($R_minus, 4) }} (minimum Ri)
                                        </p>
                                    </div>
                                    <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                        <thead class="align-bottom">
                                            <tr>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Nama</th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Si (Utility Measure)</th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Ri (Regret Measure)</th>
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
                                                            {{ number_format($Si[$alt->id] ?? 0, 4) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ number_format($Ri[$alt->id] ?? 0, 4) }}
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

                    {{-- Qi Values and Ranking --}}
                    <div id="qiResults" class="calculation-step">
                        <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
                            <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                                <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-trophy mr-2"></i>Hasil Akhir VIKOR</h6>
                            </div>
                            <div class="flex-auto px-0 pt-0 pb-2">
                                <div class="p-6 overflow-x-auto">
                                    <div class="calculation-details mb-4">
                                        <p class="text-sm dark:text-gray-300 mb-2">
                                            Nilai Qi (Compromise Index) dan peringkat akhir.
                                        </p>
                                        <div class="calculation-formula">
                                            Qi = v × (Si - S-) / (S+ - S-) + (1 - v) × (Ri - R-) / (R+ - R-)<br>
                                            Dimana v = {{ $v_value }} (nilai default)
                                        </div>
                                    </div>
                                    <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                        <thead class="align-bottom">
                                            <tr>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Ranking</th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Nama</th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Qi (Compromise Index)</th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sortedAlternatifs = $alternatifs->sortBy(function($alt) use ($ranking) {
                                                    return $ranking[$alt->id] ?? 0;
                                                });
                                            @endphp
                                            @foreach($sortedAlternatifs as $alt)
                                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ $ranking[$alt->id] ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ $alt->user->name ?? $alt->alternatif_name }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            {{ number_format($finalValues[$alt->id] ?? 0, 4) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                        <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                            @if(in_array($alt->id, $topStudents->pluck('id')->toArray()))
                                                                <span class="text-green-500">Lulus</span>
                                                            @else
                                                                <span class="text-red-500">Tidak Lulus</span>
                                                            @endif
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

                    {{-- Stability Conditions --}}
                    <div id="stabilityConditions" class="calculation-step">
                        <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
                            <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                                <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-shield-alt mr-2"></i>Kondisi Stabilitas</h6>
                            </div>
                            <div class="flex-auto px-0 pt-0 pb-2">
                                <div class="p-6">
                                    <div class="mb-4">
                                        <h4 class="text-md font-semibold dark:text-white mb-2">1. Keuntungan yang Dapat Diterima (Acceptable Advantage)</h4>
                                        <p class="text-sm dark:text-gray-300 mb-2">
                                            Q(A2) - Q(A1) ≥ DQ, dimana DQ = 1/(m-1)
                                        </p>
                                        <p class="text-sm dark:text-gray-300">
                                            @if($condition1)
                                                <span class="text-green-500"><i class="fas fa-check-circle mr-1"></i>Kondisi terpenuhi: {{ number_format($Q_diff, 6) }} ≥ {{ number_format($DQ, 6) }}</span>
                                            @else
                                                <span class="text-red-500"><i class="fas fa-times-circle mr-1"></i>Kondisi tidak terpenuhi: {{ number_format($Q_diff, 6) }} < {{ number_format($DQ, 6) }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h4 class="text-md font-semibold dark:text-white mb-2">2. Stabilitas dalam Pengambilan Keputusan (Decision Stability)</h4>
                                        <p class="text-sm dark:text-gray-300 mb-2">
                                            Alternatif A1 harus juga memiliki ranking terbaik saat dihitung dengan v=0.7 (mayoritas) dan v=0.3 (veto)
                                        </p>
                                        <p class="text-sm dark:text-gray-300">
                                            @if($condition2)
                                                <span class="text-green-500"><i class="fas fa-check-circle mr-1"></i>Kondisi terpenuhi</span>
                                            @else
                                                <span class="text-red-500"><i class="fas fa-times-circle mr-1"></i>Kondisi tidak terpenuhi</span>
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                                        <h4 class="text-md font-semibold dark:text-white mb-2">
                                            <i class="fas fa-info-circle mr-2"></i>Kesimpulan Stabilitas
                                        </h4>
                                        <p class="text-sm dark:text-gray-300">
                                            @if($isAcceptable)
                                                <span class="text-green-500 font-semibold"><i class="fas fa-check-circle mr-1"></i>Solusi kompromi stabil dan dapat diterima</span>
                                            @else
                                                <span class="text-yellow-500 font-semibold"><i class="fas fa-exclamation-triangle mr-1"></i>Solusi kompromi tidak stabil sepenuhnya</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Save Button --}}
                    @if($showSaveButton)
                        <div id="saveSection" class="calculation-step">
                            <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
                                <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                                    <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-save mr-2"></i>Simpan Hasil</h6>
                                </div>
                                <div class="flex-auto px-0 pt-0 pb-2">
                                    <div class="p-6">
                                        <form action="{{ route('hitung.simpan') }}" method="POST">
                                        @csrf

                                        @foreach ($finalValues as $id => $value)
                                            <input type="hidden" name="finalValues[{{ $id }}]" value="{{ $value }}">
                                        @endforeach

                                        @foreach ($alternatifs as $alt)
                                            <input type="hidden" name="alternatif_ids[]" value="{{ $alt->id }}">
                                        @endforeach

                                        @foreach ($ranking as $id => $rank)
                                            <input type="hidden" name="ranking[{{ $id }}]" value="{{ $rank }}">
                                        @endforeach

                                        @foreach ($Si as $id => $s)
                                            <input type="hidden" name="Si[{{ $id }}]" value="{{ $s }}">
                                        @endforeach

                                        @foreach ($Ri as $id => $r)
                                            <input type="hidden" name="Ri[{{ $id }}]" value="{{ $r }}">
                                        @endforeach

                                        <input type="hidden" name="tahun_ajaran" value="{{ $currentTahunAjaran }}">
                                        <input type="hidden" name="semester" value="{{ $currentSemester }}">

                                        <div class="flex justify-end">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-teal-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-green-600 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-300">
                                                <i class="fas fa-save mr-2"></i>Simpan Hasil Perhitungan
                                            </button>
                                        </div>
                                    </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endif
        </div>

        {{-- Riwayat Hitung Tab --}}
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="riwayat" role="tabpanel" aria-labelledby="riwayat-tab">
            <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
                <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-history mr-2"></i>Filter Riwayat</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6 overflow-x-auto">
                        <form action="{{ route('dashboard.hitung') }}" method="GET">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <div>
                                    <label for="tahun_ajaran_history" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-graduation-cap mr-1"></i>Tahun Ajaran
                                    </label>
                                    <select name="tahun_ajaran_history" id="tahun_ajaran_history" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                        <option value="">Semua Tahun Ajaran</option>
                                        @foreach($availableTahunAjarans as $tahun)
                                            <option value="{{ $tahun }}" {{ $selectedTahunAjaranHistory == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="semester_history" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-calendar mr-1"></i>Semester
                                    </label>
                                    <select name="semester_history" id="semester_history" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                        <option value="">Semua Semester</option>
                                        <option value="Ganjil" {{ $selectedSemesterHistory == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                        <option value="Genap" {{ $selectedSemesterHistory == 'Genap' ? 'selected' : '' }}>Genap</option>
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-500 to-teal-500 hover:from-blue-600 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                                        <i class="fas fa-filter mr-2"></i>Filter Riwayat
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if($historicalResults->isNotEmpty())
                <div class="relative flex flex-col min-w-0 mb-6 break-words futuristic-card">
                    <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="dark:text-white text-lg font-semibold"><i class="fas fa-table mr-2"></i>Data Riwayat Perhitungan</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6 overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Tanggal</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Nama Siswa</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Tahun Ajaran</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Semester</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Nilai Q</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Ranking</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historicalResults as $result)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ \Carbon\Carbon::parse($result->tanggal_penilaian)->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ $result->alternatif->user->name ?? $result->alternatif->alternatif_name }}
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
                                                    {{ number_format($result->nilai_q, 3) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ $result->ranking }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span class="mb-0 text-sm font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    @if($result->status == 'Lulus')
                                                        <span class="text-green-500">{{ $result->status }}</span>
                                                    @else
                                                        <span class="text-red-500">{{ $result->status }}</span>
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="w-full px-3 mb-6">
                    <div class="relative p-4 mb-4 text-yellow-700 bg-yellow-100 rounded-lg border border-yellow-300 dark:bg-yellow-200 dark:text-yellow-800">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2 text-lg"></i>
                            <span class="block sm:inline">Tidak ada data riwayat perhitungan yang ditemukan.</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Matrix Rain Effect
    function createMatrixRain() {
        const container = document.getElementById('matrixRain');
        const chars = "01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン";
        const columns = Math.floor(window.innerWidth / 20);
        
        for (let i = 0; i < columns; i++) {
            const column = document.createElement('div');
            column.className = 'matrix-column';
            
            // Create initial characters
            const charCount = Math.floor(Math.random() * 15) + 10;
            for (let j = 0; j < charCount; j++) {
                const char = document.createElement('span');
                char.textContent = chars.charAt(Math.floor(Math.random() * chars.length));
                char.style.opacity = (j / charCount) * 0.8;
                column.appendChild(char);
            }
            
            container.appendChild(column);
            
            // Animate the column
            animateColumn(column);
        }
    }
    
    function animateColumn(column) {
        const speed = 50 + Math.random() * 100;
        const delay = Math.random() * 3000;
        
        setTimeout(() => {
            setInterval(() => {
                // Remove first character
                if (column.children.length > 15) {
                    column.removeChild(column.firstChild);
                }
                
                // Add new character at bottom
                const chars = "01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン";
                const char = document.createElement('span');
                char.textContent = chars.charAt(Math.floor(Math.random() * chars.length));
                char.style.opacity = '0.8';
                column.appendChild(char);
                
                // Fade out older characters
                Array.from(column.children).forEach((child, index) => {
                    const opacity = index / column.children.length;
                    child.style.opacity = opacity * 0.8;
                });
            }, speed);
        }, delay);
    }

    // Animation for calculation steps
    document.addEventListener('DOMContentLoaded', function() {
        // Create matrix rain effect
        createMatrixRain();
        
        // Show loading animation when form is submitted
        const calculationForm = document.getElementById('calculationForm');
        if (calculationForm) {
            calculationForm.addEventListener('submit', function(e) {
                e.preventDefault();
                document.getElementById('loadingOverlay').style.display = 'flex';
                simulateProgress();
                
                // Submit the form after a small delay to allow animation to start
                setTimeout(() => {
                    calculationForm.submit();
                }, 100);
            });
        }

        // Rest of your existing JavaScript...
        // Animate calculation steps when they come into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.calculation-step').forEach(step => {
            observer.observe(step);
        });

        // Tab functionality remains the same...
    });

    // Enhanced progress simulation with system stats
    function simulateProgress() {
        const progressBar = document.getElementById('progressBar');
        const progressPercent = document.getElementById('progressPercent');
        const progressText = document.getElementById('progressText');
        const statusText = document.getElementById('statusText');
        const memoryText = document.getElementById('memoryText');
        const cpuText = document.getElementById('cpuText');
        
        const messages = [
            "Memuat data siswa...",
            "Membangun matriks keputusan...",
            "Menghitung nilai ideal...",
            "Melakukan normalisasi...",
            "Memproses nilai terbobot...",
            "Menghitung utility measure...",
            "Menganalisis regret measure...",
            "Menentukan indeks kompromi...",
            "Memverifikasi kondisi stabilitas...",
            "Menyusun peringkat akhir..."
        ];
        
        let progress = 0;
        const interval = setInterval(() => {
            // Increment progress with some randomness
            progress += Math.random() * 8;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
            }
            
            // Update progress bar and text
            progressBar.style.width = `${progress}%`;
            progressPercent.textContent = `${Math.round(progress)}%`;
            
            // Update progress text based on progress
            const messageIndex = Math.min(Math.floor(progress / (100 / messages.length)), messages.length - 1);
            progressText.textContent = messages[messageIndex];
            
            // Update system stats
            const memory = Math.round(100 + Math.random() * 800); // 100-900 MB
            const cpu = Math.round(10 + Math.random() * 60); // 10-70%
            memoryText.textContent = `${memory} MB`;
            cpuText.textContent = `${cpu}%`;
            
            // Update status text with more technical details
            if (progress < 30) {
                statusText.textContent = "Menyiapkan lingkungan komputasi";
            } else if (progress < 60) {
                statusText.textContent = "Melakukan analisis multi-kriteria";
            } else if (progress < 90) {
                statusText.textContent = "Mengoptimalkan solusi kompromi";
            } else {
                statusText.textContent = "Menyelesaikan proses perhitungan";
            }
            
            // Add some random particles
            if (progress < 100 && Math.random() > 0.7) {
                addParticle();
            }
        }, 300);
    }

    // Add tech particles for loading animation
    function addParticle() {
        const particlesContainer = document.getElementById('techParticles');
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        // Random position
        const x = Math.random() * 100;
        const y = Math.random() * 100;
        particle.style.left = `${x}%`;
        particle.style.top = `${y}%`;
        
        // Random size and animation
        const size = Math.random() * 6 + 2;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.opacity = Math.random() * 0.7 + 0.3;
        
        // Random color from blue to cyan
        const hue = 180 + Math.random() * 60; // 180-240 (blue to cyan)
        particle.style.backgroundColor = `hsla(${hue}, 100%, 70%, ${particle.style.opacity})`;
        
        particlesContainer.appendChild(particle);
        
        // Animation
        const duration = Math.random() * 2000 + 1000;
        const angle = Math.random() * Math.PI * 2;
        const distance = Math.random() * 100 + 50;
        
        const animation = particle.animate([
            { transform: `translate(0, 0)`, opacity: particle.style.opacity },
            { transform: `translate(${Math.cos(angle) * distance}px, ${Math.sin(angle) * distance}px)`, opacity: 0 }
        ], {
            duration: duration,
            easing: 'cubic-bezier(0.4, 0, 0.2, 1)'
        });
        
        animation.onfinish = () => particle.remove();
    }
</script>
@endpush