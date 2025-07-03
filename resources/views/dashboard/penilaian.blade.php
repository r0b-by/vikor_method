@extends('dashboard.layouts.dashboardmain')
@section('title', 'Penilaian')

@section('content')
<div class="flex flex-wrap -mx-3" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="300"
    data-aos-offset="0">

    <!-- Futuristic Header -->
    <div class="flex items-center justify-between mb-6 w-full">
        <div>
            <h2 class="text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-purple-600">
                Manajemen Penilaian
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Advanced assessment analytics dashboard</p>
        </div>
    </div>

    <!-- Futuristic Filter Card with Glass Morphism -->
    <div class="w-full px-3 mb-6">
        <div class="bg-white/80 dark:bg-slate-800/50 backdrop-blur-lg rounded-xl shadow-lg p-6 border border-white/20 dark:border-slate-700/50">
            <form action="{{ route('penilaian.index') }}" method="GET" class="flex flex-col md:flex-row items-end space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-grow w-full">
                    <label for="academic_period_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pilih Periode Akademik</label>
                    <div class="relative">
                        <select name="academic_period_id" id="academic_period_id" onchange="this.form.submit()"
                            class="appearance-none w-full pl-4 pr-10 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white/50 dark:bg-slate-700/50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-purple-500 dark:focus:border-purple-500 text-gray-900 dark:text-white transition-all duration-300 shadow-sm">
                            @foreach($academicPeriods as $period)
                                <option value="{{ $period->id }}" {{ $selectedAcademicPeriod && $selectedAcademicPeriod->id == $period->id ? 'selected' : '' }}>
                                    {{ $period->tahun_ajaran }} - {{ $period->semester }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Futuristic Tabs with Animated Underline -->
    <div class="w-full px-3 mb-6">
        <div class="relative">
            <div class="flex space-x-8 border-b border-gray-200 dark:border-slate-700">
                <button id="data-tab" data-tabs-target="#data" type="button" 
                    class="relative py-4 px-1 text-sm font-medium transition-all duration-300 focus:outline-none group">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500 group-hover:text-blue-600 dark:text-purple-400 dark:group-hover:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Data Penilaian
                    </span>
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500 dark:bg-purple-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </button>
                <button id="detail-tab" data-tabs-target="#detail" type="button" 
                    class="relative py-4 px-1 text-sm font-medium transition-all duration-300 focus:outline-none group">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500 group-hover:text-blue-600 dark:text-purple-400 dark:group-hover:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Rekam Jejak
                    </span>
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500 dark:bg-purple-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Tab Content -->
    <div id="myTabContent" class="w-full">
        <!-- Data Penilaian Section -->
        <div class="hidden p-4 rounded-xl bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm border border-white/20 dark:border-slate-700/50 shadow-md" id="data" role="tabpanel" aria-labelledby="data-tab">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Tabel Penilaian
                    @if($selectedAcademicPeriod)
                        <span class="text-sm font-normal text-blue-600 dark:text-purple-400 ml-2">
                            (Periode: {{ $selectedAcademicPeriod->tahun_ajaran }} {{ $selectedAcademicPeriod->semester }})
                        </span>
                    @endif
                </h3>
                <div class="flex items-center space-x-2">
                    <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-purple-900/30 dark:text-purple-300">
                        {{ count($alternatifs) }} Alternatif
                    </span>
                    <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                        {{ count($criterias) }} Kriteria
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                    <thead class="bg-gray-50/80 dark:bg-slate-800/80">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                Alternatif
                            </th>
                            @foreach ($criterias as $c)
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        {{ $c->criteria_code }}
                                        <span class="ml-1 text-xs text-gray-400 dark:text-slate-500" data-tooltip-target="tooltip-{{ $c->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </span>
                                        <div id="tooltip-{{ $c->id }}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-slate-700">
                                            {{ $c->nama_criteria }}
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                            @role(['admin', 'guru'])
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                    Aksi
                                </th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody class="bg-white/50 dark:bg-slate-800/50 divide-y divide-gray-200 dark:divide-slate-700">
                        @forelse ($alternatifs as $a)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/80 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 flex items-center justify-center">
                                            <span class="text-blue-600 dark:text-purple-400 font-medium">{{ substr($a->alternatif_code, 0, 2) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $a->alternatif_code }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-slate-400">
                                                {{ $a->user->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                @foreach ($criterias as $c)
                                    @php
                                        $penilaianForCriteria = $penilaians
                                            ->where('id_alternatif', $a->id)
                                            ->where('id_criteria', $c->id)
                                            ->first();
                                        $nilai = $penilaianForCriteria ? $penilaianForCriteria->nilai : 0;
                                    @endphp

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white font-medium">
                                            {{ $nilai }}
                                        </div>
                                    </td>
                                @endforeach

                                @role(['admin', 'guru'])
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button type="button" onclick="openModal('modal-{{ $a->id }}')"
                                            class="text-blue-600 hover:text-blue-900 dark:text-purple-400 dark:hover:text-purple-300 mr-3 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </td>
                                @endrole
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($criterias) + 2 }}" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-slate-400">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-slate-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-gray-600 dark:text-slate-300">Belum ada data alternatif atau penilaian untuk periode ini.</p>
                                        <p class="text-sm text-gray-500 dark:text-slate-500 mt-1">Silakan pilih periode akademik lain atau tambahkan data baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rekam Jejak Section -->
        <div class="hidden p-4 rounded-xl bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm border border-white/20 dark:border-slate-700/50 shadow-md" id="detail" role="tabpanel" aria-labelledby="detail-tab">
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Rekam Jejak Penilaian</h3>
                <p class="text-sm text-gray-500 dark:text-slate-400">Riwayat lengkap semua penilaian yang pernah dilakukan</p>
            </div>

            @forelse ($groupedPenilaians as $key => $group)
                <div class="mb-6 border border-gray-200 dark:border-slate-700 p-5 rounded-xl bg-white/70 dark:bg-slate-800/70 shadow-sm hover:shadow-md transition-shadow duration-300">
                    @php
                        list($alternatifCode, $tahunAjaran, $semester) = explode('-', $key);
                        $firstItem = $group->first();
                        $tanggal = \Carbon\Carbon::parse($firstItem->tanggal_penilaian)->format('d F Y');
                        $jam = \Carbon\Carbon::parse($firstItem->jam_penilaian)->format('H:i');
                    @endphp

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                                    {{ $alternatifCode }}
                                </span>
                                {{ $firstItem->alternatif->alternatif_name ?? $firstItem->alternatif->user->name ?? '-' }}
                            </h4>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2">
                                <span class="text-sm text-gray-600 dark:text-slate-300 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $tanggal }}
                                </span>
                                <span class="text-sm text-gray-600 dark:text-slate-300 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $jam }}
                                </span>
                                <span class="text-sm text-gray-600 dark:text-slate-300 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                    {{ $tahunAjaran }} - {{ $semester }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-3 md:mt-0">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                {{ $group->count() }} Kriteria Dinilai
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-slate-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                            <thead class="bg-gray-50/80 dark:bg-slate-800/80">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                        Kriteria
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                        Nilai
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                        Sertifikat
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white/50 dark:bg-slate-800/50 divide-y divide-gray-200 dark:divide-slate-700">
                                @foreach ($group as $item)
                                    <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/80 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $item->criteria->nama_criteria }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-slate-400">
                                                {{ $item->criteria->criteria_code }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                {{ $item->nilai }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($item->certificate_details)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach ($item->certificate_details as $cert)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                                            {{ $cert['level'] ?? 'N/A' }} ({{ $cert['count'] ?? 'N/A' }})
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-500 dark:text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('penilaian.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penilaian ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h4 class="text-lg font-medium text-gray-600 dark:text-slate-300">Belum ada rekam jejak penilaian</h4>
                    <p class="text-sm text-gray-500 dark:text-slate-500 mt-2">Mulai lakukan penilaian untuk melihat riwayat di sini</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Futuristic Modal -->
@foreach ($alternatifs as $a)
    @role(['admin', 'guru'])
        <div id="modal-{{ $a->id }}" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto h-modal h-full backdrop-blur-sm">
            <div class="relative w-full h-full max-w-4xl mx-auto mt-10">
                <!-- Modal content -->
                <div class="relative bg-white rounded-xl shadow-xl dark:bg-slate-800 border border-white/20 dark:border-slate-700/50">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-5 border-b rounded-t-xl dark:border-slate-700 bg-gradient-to-r from-blue-500 to-purple-600">
                        <h3 class="text-xl font-medium text-white">
                            Penilaian untuk <span class="font-semibold">{{ $a->alternatif_code }}</span>
                        </h3>
                        <button type="button" onclick="closeModal('modal-{{ $a->id }}')"
                            class="text-white hover:bg-white/20 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Tutup modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form action="{{ route('penilaian.storeOrUpdate') }}" method="POST">
                        @csrf
                        <div class="p-6 space-y-6">
                            <input type="hidden" name="id_alternatif" value="{{ $a->id }}">

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="academic_period_id-modal-{{ $a->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Periode Akademik</label>
                                    <select name="academic_period_id" id="academic_period_id-modal-{{ $a->id }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                        required>
                                        @foreach($academicPeriods as $period)
                                            <option value="{{ $period->id }}" {{ $selectedAcademicPeriod && $selectedAcademicPeriod->id == $period->id ? 'selected' : '' }}>
                                                {{ $period->tahun_ajaran }} - {{ $period->semester }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($criterias as $c)
                                    @php
                                        $nilaiTerbaru = $penilaians
                                            ->where('id_alternatif', $a->id)
                                            ->where('id_criteria', $c->id)
                                            ->first();
                                            
                                        $certificateDetails = $nilaiTerbaru ? $nilaiTerbaru->certificate_details : [];
                                    @endphp
                                    <div class="bg-gray-50/50 dark:bg-slate-700/50 p-4 rounded-lg border border-gray-200 dark:border-slate-700">
                                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $c->criteria_code }} - {{ $c->nama_criteria }}
                                        </label>
                                        
                                        @if(in_array($c->criteria_code, ['C4', 'C5']))
                                            <input type="hidden" name="nilai[{{ $c->id }}]" 
                                                   value="{{ $nilaiTerbaru ? $nilaiTerbaru->nilai : 0 }}">
                                            
                                            <div class="mt-3 space-y-2" id="certificate_inputs_{{ $a->id }}_{{ $c->id }}">
                                                <label class="block text-sm font-medium text-gray-900 dark:text-white">Detail Sertifikat:</label>
                                                <button type="button" onclick="addCertificateField('{{ $a->id }}', '{{ $c->id }}')" 
                                                    class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                                    Tambah Sertifikat
                                                </button>
                                                
                                                @if (!empty($certificateDetails))
                                                    @foreach ($certificateDetails as $index => $detail)
                                                        <div class="flex gap-2 items-center certificate-row">
                                                            <select name="certificate_level[{{ $c->id }}][]" 
                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                                    onchange="calculateCertificateScore('{{ $a->id }}', '{{ $c->id }}')">
                                                                <option value="">Pilih Level</option>
                                                                @foreach($c->subs as $sub)
                                                                    <option value="{{ $sub->label }}" 
                                                                            data-point="{{ $sub->point }}"
                                                                            {{ ($detail['level'] ?? '') == $sub->label ? 'selected' : '' }}>
                                                                        {{ $sub->label }} ({{ $sub->point }} poin)
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input type="number" name="certificate_count[{{ $c->id }}][]" 
                                                                   value="{{ $detail['count'] ?? 1 }}" min="1" 
                                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-20 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                                   onchange="calculateCertificateScore('{{ $a->id }}', '{{ $c->id }}')">
                                                            <button type="button" onclick="removeCertificateField(this, '{{ $a->id }}', '{{ $c->id }}')" class="text-red-500 hover:text-red-700 text-sm">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @else
                                            <input type="number" min="0" step="0.01"
                                                value="{{ $nilaiTerbaru ? $nilaiTerbaru->nilai : 0 }}"
                                                name="nilai[{{ $c->id }}]"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required onchange="formatDecimal(this)">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="flex justify-end mt-4">
                                <button type="submit"
                                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-5 h-5 me-1 -ms-1" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Simpan Penilaian
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endrole
@endforeach

<script>
    // Certificate point values
   // Certificate point values
const certificatePoints = {
    'Internasional': 5,
    'Nasional': 4,
    'Provinsi': 3,
    'Kabupaten/Kota': 2,
    'Sekolah': 1
};

// Function to add certificate field
function addCertificateField(alternatifId, criteriaId) {
    const container = document.getElementById(`certificate_inputs_${alternatifId}_${criteriaId}`);
    
    const div = document.createElement('div');
    div.className = 'flex gap-2 items-center certificate-row';
    div.innerHTML = `
        <select name="certificate_level[${criteriaId}][]" 
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                onchange="calculateCertificateScore('${alternatifId}', '${criteriaId}')">
            <option value="">Pilih Level</option>
            <option value="Internasional" data-point="5">Internasional (5 poin)</option>
            <option value="Nasional" data-point="4">Nasional (4 poin)</option>
            <option value="Provinsi" data-point="3">Provinsi (3 poin)</option>
            <option value="Kabupaten/Kota" data-point="2">Kabupaten/Kota (2 poin)</option>
            <option value="Sekolah" data-point="1">Sekolah (1 poin)</option>
        </select>
        <input type="number" name="certificate_count[${criteriaId}][]" 
               value="1" min="1" 
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-20 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
               onchange="calculateCertificateScore('${alternatifId}', '${criteriaId}')">
        <button type="button" onclick="removeCertificateField(this, '${alternatifId}', '${criteriaId}')" class="text-red-500 hover:text-red-700 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    `;
    
    container.appendChild(div);
}

// Function to remove certificate field
function removeCertificateField(button, alternatifId, criteriaId) {
    const row = button.closest('.certificate-row');
    row.remove();
    calculateCertificateScore(alternatifId, criteriaId);
}

// Function to calculate certificate score
function calculateCertificateScore(alternatifId, criteriaId) {
    const container = document.getElementById(`certificate_inputs_${alternatifId}_${criteriaId}`);
    const selects = container.querySelectorAll('select[name^="certificate_level"]');
    const inputs = container.querySelectorAll('input[name^="certificate_count"]');
    
    let totalScore = 0;
    
    selects.forEach((select, index) => {
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const point = parseFloat(selectedOption.dataset.point);
            const count = parseFloat(inputs[index].value);
            totalScore += point * count;
        }
    });
    
    // Find the hidden input for this criteria and set its value
    const hiddenInput = document.querySelector(`input[name="nilai[${criteriaId}]"]`);
    if (hiddenInput) {
        hiddenInput.value = totalScore;
    }
}

// Function to format decimal input
function formatDecimal(input) {
    // Ensure the value is a valid number with 2 decimal places
    let value = parseFloat(input.value);
    if (isNaN(value)) {
        value = 0;
    }
    input.value = value.toFixed(2);
}

// Tab switching functionality
document.querySelectorAll('[data-tabs-target]').forEach(tab => {
    tab.addEventListener('click', () => {
        const target = tab.getAttribute('data-tabs-target');
        document.querySelectorAll('#myTabContent > div').forEach(content => {
            content.classList.add('hidden');
        });
        document.querySelector(target).classList.remove('hidden');
        
        // Update active tab styling
        document.querySelectorAll('[data-tabs-target]').forEach(t => {
            t.classList.remove('text-blue-600', 'dark:text-purple-400');
            t.classList.add('text-gray-500', 'dark:text-slate-400');
        });
        tab.classList.remove('text-gray-500', 'dark:text-slate-400');
        tab.classList.add('text-blue-600', 'dark:text-purple-400');
    });
});

// Modal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('backdrop-blur-sm')) {
        const modals = document.querySelectorAll('.backdrop-blur-sm');
        modals.forEach(modal => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-tooltip-target]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        const tooltipId = tooltipTriggerEl.getAttribute('data-tooltip-target');
        const tooltipEl = document.getElementById(tooltipId);
        
        tooltipTriggerEl.addEventListener('mouseenter', function() {
            tooltipEl.classList.remove('invisible', 'opacity-0');
            tooltipEl.classList.add('visible', 'opacity-100');
        });
        
        tooltipTriggerEl.addEventListener('mouseleave', function() {
            tooltipEl.classList.add('invisible', 'opacity-0');
            tooltipEl.classList.remove('visible', 'opacity-100');
        });
    });
    
    // Activate first tab by default
    const firstTab = document.querySelector('[data-tabs-target]');
    if (firstTab) {
        firstTab.click();
    }
});
</script>

@endsection