@extends('dashboard.layouts.dashboardmain')

@section('title', 'Form Penilaian Mandiri')

@section('content')
<div class="flex flex-wrap -mx-3" data-aos="fade-zoom-in"
     data-aos-easing="ease-in-back"
     data-aos-delay="300"
     data-aos-offset="0">
    <div class="flex items-center justify-between mb-6 px-3">
        <h2 class="text-2xl xl:text-3xl font-bold text-slate-900 dark:text-white">
            Form Penilaian Mandiri
        </h2>
    </div>

    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="dark:text-white text-lg font-semibold">Isi Penilaian untuk Alternatif Anda</h6>
            </div>
            <div class="flex-auto p-6">
                {{-- Session messages --}}
                @if(session('success'))
                    <div class="bg-green-100 dark:bg-green-700 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-100 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 dark:bg-red-700 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-100 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                @if(session('info'))
                    <div class="bg-blue-100 dark:bg-blue-700 border border-blue-400 dark:border-blue-600 text-blue-700 dark:text-blue-100 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('info') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 dark:bg-red-700 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-100 px-4 py-3 rounded relative mb-4" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(Auth::user()->status === 'pending')
                    <div class="bg-yellow-100 dark:bg-yellow-700 border border-yellow-400 dark:border-yellow-600 text-yellow-700 dark:text-yellow-100 px-4 py-3 rounded relative mb-4" role="alert">
                        Pendaftaran Anda sedang dalam peninjauan. Anda akan dapat mengisi penilaian setelah disetujui oleh admin.
                    </div>
                @elseif(!$alternatif)
                    <div class="bg-red-100 dark:bg-red-700 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-100 px-4 py-3 rounded relative mb-4" role="alert">
                        Data alternatif Anda belum terdaftar. Silakan hubungi admin untuk mendaftarkan alternatif Anda agar bisa mengisi penilaian.
                    </div>
                @elseif($criterias->isEmpty())
                    <div class="bg-yellow-100 dark:bg-yellow-700 border border-yellow-400 dark:border-yellow-600 text-yellow-700 dark:text-yellow-100 px-4 py-3 rounded relative mb-4" role="alert">
                        Belum ada kriteria yang terdaftar. Harap hubungi admin/guru.
                    </div>
                @else
                    {{-- Cek apakah sudah ada penilaian lengkap --}}
                    @php
                        $isComplete = true;
                        // Check if all criteria have a penilaian entry
                        foreach ($criterias as $criteria) {
                            // Changed array_key_exists to Collection->has()
                            if (!$latestPenilaiansForCurrentPeriod->has($criteria->id)) {
                                $isComplete = false;
                                break;
                            }
                        }
                    @endphp

                    @if($isComplete)
                        <div class="bg-blue-100 dark:bg-blue-700 border border-blue-400 dark:border-blue-600 text-blue-700 dark:text-blue-100 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong>Penilaian Anda sudah lengkap dan tidak dapat diubah lagi.</strong> Jika ada kesalahan, silakan hubungi admin.
                        </div>
                    @endif

                    <form action="{{ route('siswa.penilaian.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_alternatif" value="{{ $alternatif->id }}">
                        {{-- Added hidden input for academic_period_id --}}
                        <input type="hidden" name="academic_period_id" value="{{ $academicPeriodForStudent->id }}">

                        <div class="overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th class="px-6 py-3 font-bold text-left uppercase text-xxs text-slate-400 opacity-70 dark:text-white">Kode Kriteria</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase text-xxs text-slate-400 opacity-70 dark:text-white">Nama Kriteria</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase text-xxs text-slate-400 opacity-70 dark:text-white">Jenis Kriteria</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase text-xxs text-slate-400 opacity-70 dark:text-white">Bobot</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase text-xxs text-slate-400 opacity-70 dark:text-white">Nilai Anda</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($criterias as $criteria)
                                        <tr>
                                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80">{{ $criteria->criteria_code }}</span>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80">{{ $criteria->criteria_name }}</span>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80">{{ $criteria->criteria_type }}</span>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80">{{ $criteria->weight }}</span>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                                                @php
    // Changed array_key_exists to Collection->has() and corrected variable name
    $hasValue = $latestPenilaiansForCurrentPeriod->has($criteria->id);

    // Get the selected value for input fields
    $selected = old('nilai.' . $criteria->id, $hasValue ? $latestPenilaiansForCurrentPeriod[$criteria->id]->nilai : '');

    // Prepare certificate details for C4/C5 if existing and not old input
    $existingCertificateDetails = [];
    if ($hasValue && !empty($latestPenilaiansForCurrentPeriod[$criteria->id]->certificate_details)) {
        // Ensure certificate_details is an array, either directly from the model cast or json_decoded
        $details = $latestPenilaiansForCurrentPeriod[$criteria->id]->certificate_details;
        $existingCertificateDetails = is_array($details) ? $details : (json_decode($details, true) ?: []);
    } elseif ($errors->any() && old('certificate_level.' . $criteria->id) !== null) {
        // If there are validation errors, use old input for certificates
        $oldLevels = old('certificate_level.' . $criteria->id, []);
        $oldCounts = old('certificate_count.' . $criteria->id, []);
        foreach ($oldLevels as $index => $level) {
            $existingCertificateDetails[] = [
                'level' => $level,
                'count' => $oldCounts[$index] ?? 1,
            ];
        }
    }
@endphp

                                                @if ($hasValue && $isComplete)
                                                    {{-- Tampilkan nilai yang sudah ada dalam mode read-only --}}
                                                    <input type="text"
                                                        class="focus:shadow-primary-outline dark:bg-slate-700 dark:text-white dark:placeholder:text-white/80 dark:border-white/40 leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-gray-100 bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                                                        value="{{ $selected }}" readonly>
                                                    <input type="hidden" name="nilai[{{ $criteria->id }}]" value="{{ $selected }}">
                                                @else
                                                    {{-- Tampilkan input biasa jika belum ada nilai --}}
                                                    @if ($criteria->criteria_code === 'C1')
                                                        <input type="number" step="0.01" min="60" max="100" name="nilai[{{ $criteria->id }}]"
                                                            class="focus:shadow-primary-outline dark:bg-slate-700 dark:text-white dark:placeholder:text-white/80 dark:border-white/40 leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                                                            value="{{ $selected }}" required>
                                                    @elseif ($criteria->criteria_code === 'C2')
                                                        <select name="nilai[{{ $criteria->id }}]" required
                                                            class="dark:bg-slate-700 dark:text-white dark:border-white/40 border-gray-300 text-sm rounded-lg block w-full px-3 py-2">
                                                            <option value="">Pilih Pendapatan</option>
                                                            <option value="100" {{ $selected == 100 ? 'selected' : '' }}>≤ 2 juta</option>
                                                            <option value="80" {{ $selected == 80 ? 'selected' : '' }}>2–3 juta</option>
                                                            <option value="60" {{ $selected == 60 ? 'selected' : '' }}>3–4 juta</option>
                                                            <option value="40" {{ $selected == 40 ? 'selected' : '' }}>4–5 juta</option>
                                                            <option value="20" {{ $selected == 20 ? 'selected' : '' }}>> 5 juta</option>
                                                        </select>
                                                    @elseif ($criteria->criteria_code === 'C3')
                                                        <select name="nilai[{{ $criteria->id }}]" required
                                                            class="dark:bg-slate-700 dark:text-white dark:border-white/40 border-gray-300 text-sm rounded-lg block w-full px-3 py-2">
                                                            <option value="">Pilih Jumlah Tanggungan</option>
                                                            <option value="100" {{ $selected == 100 ? 'selected' : '' }}>≥ 5 orang</option>
                                                            <option value="80" {{ $selected == 80 ? 'selected' : '' }}>4 orang</option>
                                                            <option value="60" {{ $selected == 60 ? 'selected' : '' }}>3 orang</option>
                                                            <option value="40" {{ $selected == 40 ? 'selected' : '' }}>2 orang</option>
                                                            <option value="20" {{ $selected == 20 ? 'selected' : '' }}>1 orang</option>
                                                        </select>
                                                    @elseif ($criteria->criteria_code === 'C4' || $criteria->criteria_code === 'C5')
                                                        <div id="certificates-{{ $criteria->id }}" class="certificate-container" data-criteria-id="{{ $criteria->id }}">
                                                            <input type="hidden" name="nilai[{{ $criteria->id }}]" value="{{ $selected ?? 0 }}" class="total-score-input">
                                                            <p class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 mb-2">
                                                                Total Poin Sertifikat: <span id="total-points-display-{{ $criteria->id }}" class="font-bold text-blue-500">{{ $selected ?? 0 }}</span>
                                                            </p>

                                                            @if(!empty($existingCertificateDetails))
                                                                @foreach($existingCertificateDetails as $index => $detail)
                                                                    <div class="flex items-center space-x-2 mb-2 certificate-item">
                                                                        <select name="certificate_level[{{ $criteria->id }}][]"
                                                                            class="certificate-level dark:bg-slate-700 dark:text-white dark:border-white/40 border-gray-300 text-sm rounded-lg block w-full px-3 py-2" onchange="calculateTotalPoints()">
                                                                            <option value="">Pilih Tingkat</option>
                                                                            {{-- Using `value` attribute for point values as per criteria subs --}}
                                                                            <option value="10" {{ ($detail['level'] == 10 || $detail['level'] == 'Nasional') ? 'selected' : '' }}>Juara Nasional (10 poin)</option>
                                                                            <option value="8" {{ ($detail['level'] == 8 || $detail['level'] == 'Provinsi') ? 'selected' : '' }}>Provinsi (8 poin)</option>
                                                                            <option value="6" {{ ($detail['level'] == 6 || $detail['level'] == 'Kabupaten/Kota') ? 'selected' : '' }}>Kabupaten/Kota (6 poin)</option>
                                                                            <option value="4" {{ ($detail['level'] == 4 || $detail['level'] == 'Sekolah') ? 'selected' : '' }}>Sekolah (4 poin)</option>
                                                                            <option value="2" {{ ($detail['level'] == 2 || $detail['level'] == 'Partisipasi') ? 'selected' : '' }}>Partisipasi (2 poin)</option>
                                                                        </select>
                                                                        <input type="number" name="certificate_count[{{ $criteria->id }}][]"
                                                                            class="certificate-count w-20 dark:bg-slate-700 dark:text-white dark:border-white/40 border-gray-300 text-sm rounded-lg block px-3 py-2"
                                                                            min="1" value="{{ $detail['count'] ?? 1 }}" onchange="calculateTotalPoints()" onkeyup="calculateTotalPoints()">
                                                                        <button type="button" class="remove-certificate-btn text-red-500 hover:text-red-700 text-xl font-bold leading-none">&times;</button>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="flex items-center space-x-2 mb-2 certificate-item">
                                                                    <select name="certificate_level[{{ $criteria->id }}][]"
                                                                        class="certificate-level dark:bg-slate-700 dark:text-white dark:border-white/40 border-gray-300 text-sm rounded-lg block w-full px-3 py-2" onchange="calculateTotalPoints()">
                                                                        <option value="">Pilih Tingkat</option>
                                                                        <option value="10">Juara Nasional (10 poin)</option>
                                                                        <option value="8">Provinsi (8 poin)</option>
                                                                        <option value="6">Kabupaten/Kota (6 poin)</option>
                                                                        <option value="4">Sekolah (4 poin)</option>
                                                                        <option value="2">Partisipasi (2 poin)</option>
                                                                    </select>
                                                                    <input type="number" name="certificate_count[{{ $criteria->id }}][]"
                                                                        class="certificate-count w-20 dark:bg-slate-700 dark:text-white dark:border-white/40 border-gray-300 text-sm rounded-lg block px-3 py-2"
                                                                        min="1" value="1" onchange="calculateTotalPoints()" onkeyup="calculateTotalPoints()">
                                                                    <button type="button" class="remove-certificate-btn text-red-500 hover:text-red-700 text-xl font-bold leading-none">&times;</button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <button type="button" onclick="addCertificate('{{ $criteria->id }}')"
                                                            class="add-certificate-btn inline-block px-4 py-2 text-xs font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out mt-2">
                                                            Tambah Sertifikat
                                                        </button>
                                                    @else
                                                        <input type="number" step="0.01" name="nilai[{{ $criteria->id }}]"
                                                            class="focus:shadow-primary-outline dark:bg-slate-700 dark:text-white dark:placeholder:text-white/80 dark:border-white/40 leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                                                            value="{{ $selected }}" required min="0">
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($isComplete)
                            <div class="bg-yellow-100 dark:bg-yellow-700 border border-yellow-400 dark:border-yellow-600 text-yellow-700 dark:text-yellow-100 px-4 py-3 rounded relative mb-4" role="alert">
                                Anda tidak dapat mengubah penilaian yang sudah disubmit. Jika ada kesalahan, silakan hubungi admin.
                            </div>
                        @else
                            <button type="submit" class="inline-block px-6 py-3 mt-4 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                Simpan Penilaian
                            </button>
                        @endif
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function addCertificate(criteriaId) {
        const container = document.getElementById(`certificates-${criteriaId}`);
        const newDiv = document.createElement('div');
        newDiv.className = 'flex items-center space-x-2 mb-2 certificate-item';
        newDiv.innerHTML = `
            <select name="certificate_level[${criteriaId}][]"
                class="certificate-level dark:bg-slate-700 dark:text-white dark:border-white/40 border-gray-300 text-sm rounded-lg block w-full px-3 py-2" onchange="calculateTotalPoints()">
                <option value="">Pilih Tingkat</option>
                <option value="10">Juara Nasional (10 poin)</option>
                <option value="8">Provinsi (8 poin)</option>
                <option value="6">Kabupaten/Kota (6 poin)</option>
                <option value="4">Sekolah (4 poin)</option>
                <option value="2">Partisipasi (2 poin)</option>
            </select>
            <input type="number" name="certificate_count[${criteriaId}][]"
                class="certificate-count w-20 dark:bg-slate-700 dark:text-white dark:border-white/40 border-gray-300 text-sm rounded-lg block px-3 py-2"
                min="1" value="1" onchange="calculateTotalPoints()" onkeyup="calculateTotalPoints()">
            <button type="button" class="remove-certificate-btn text-red-500 hover:text-red-700 text-xl font-bold leading-none">&times;</button>
        `;
        container.insertBefore(newDiv, container.querySelector('.add-certificate-btn'));
        calculateTotalPoints();
    }

    function calculateTotalPoints() {
        document.querySelectorAll('.certificate-container').forEach(container => {
            const criteriaId = container.dataset.criteriaId;
            let totalPoints = 0;

            container.querySelectorAll('.certificate-item').forEach(itemDiv => {
                const levelSelect = itemDiv.querySelector('.certificate-level');
                const countInput = itemDiv.querySelector('.certificate-count');

                const levelValue = parseInt(levelSelect.value) || 0;
                const countValue = parseInt(countInput.value) || 0;

                if (levelValue > 0 && countValue > 0) {
                    totalPoints += (levelValue * countValue);
                }
            });

            document.getElementById(`total-points-display-${criteriaId}`).textContent = totalPoints;
            container.querySelector('.total-score-input').value = totalPoints;
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculateTotalPoints();

        // Event delegation for remove buttons
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-certificate-btn')) {
                event.target.closest('.certificate-item').remove();
                calculateTotalPoints();
            }
        });
    });
</script>
@endpush
