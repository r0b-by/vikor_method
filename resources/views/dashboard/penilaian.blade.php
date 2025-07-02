@extends('dashboard.layouts.dashboardmain')
@section('title', 'Penilaian')

@section('content')
<div class="flex flex-wrap -mx-3" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="300"
    data-aos-offset="0">

    <div class="flex items-center justify-between mb-6 w-full">
        <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">
            Manajemen Penilaian
        </h2>
    </div>

    {{-- Filter Periode Akademik --}}
    <div class="w-full px-3 mb-6">
        <form action="{{ route('penilaian.index') }}" method="GET" class="flex items-end space-x-4">
            <div class="flex-grow">
                <label for="academic_period_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Periode Akademik:</label>
                <select name="academic_period_id" id="academic_period_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Periode</option>
                    @foreach($academicPeriods as $period)
                        <option value="{{ $period->id }}" {{ $selectedAcademicPeriodId == $period->id ? 'selected' : '' }}>
                            {{ $period->tahun_ajaran }} - {{ $period->semester }} {{ $period->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="inline-block px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Tab Navigation --}}
    <div class="w-full px-3 mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="data-tab" data-tabs-target="#data" type="button" role="tab" aria-controls="data" aria-selected="true">Data Penilaian</button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="detail-tab" data-tabs-target="#detail" type="button" role="tab" aria-controls="detail" aria-selected="false">Rekam Jejak Penilaian</button>
                </li>
            </ul>
        </div>
    </div>

    {{-- Tab Content --}}
    <div id="myTabContent" class="w-full">
        {{-- Data Penilaian Section (Matriks Decision) --}}
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="data" role="tabpanel" aria-labelledby="data-tab">
            <div class="flex-none w-full max-w-full px-3 overflow-x-hidden">
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">

                    <div
                        class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="dark:text-white">Tabel Penilaian (Periode: {{ $selectedAcademicPeriod ? $selectedAcademicPeriod->tahun_ajaran . ' ' . $selectedAcademicPeriod->semester : 'Tidak Dipilih' }})</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6 overflow-x-auto">
                            <table class="items-center w-full mb-0 overflow-x-auto align-top border-collapse dark:border-white/40 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Alternatif</th>
                                        @foreach ($criterias as $c)
                                            <th
                                                class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                {{ $c->criteria_code }}</th>
                                        @endforeach
                                        @role(['admin', 'guru'])
                                            <th
                                                class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Aksi</th>
                                        @endrole
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($alternatifs as $a)
                                        <tr>
                                            <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ $a->alternatif_code }} - {{ $a->user->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            @foreach ($criterias as $c)
                                                @php
                                                    // Ambil penilaian terbaru untuk alternatif dan kriteria ini
                                                    // Filter berdasarkan periode akademik yang dipilih
                                                    $penilaianForCriteria = $penilaians
                                                        ->where('id_alternatif', $a->id)
                                                        ->where('id_criteria', $c->id)
                                                        ->where('academic_period_id', $selectedAcademicPeriodId) // Penting: filter berdasarkan periode yang dipilih
                                                        ->first();
                                                @endphp

                                                <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                        {{ $penilaianForCriteria ? $penilaianForCriteria->nilai : 0 }}
                                                    </span>
                                                </td>
                                            @endforeach

                                            @role(['admin', 'guru'])
                                                <td class="flex p-2 align-middle bg-transparent border-b just dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <button type="button" onclick="openModal('modal-{{ $a->id }}')"
                                                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Edit</button>
                                                </td>
                                            @endrole
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ count($criterias) + 2 }}" class="text-center py-4 text-gray-500">
                                                Belum ada data alternatif atau penilaian untuk periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Penilaian Section (Rekam Jejak Penilaian) --}}
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="detail" role="tabpanel" aria-labelledby="detail-tab">
            <div class="mt-8 px-3">
                <h3 class="text-2xl font-bold mb-4 text-slate-900 dark:text-slate-100">Rekam Jejak Penilaian</h3>
                @forelse ($groupedPenilaians as $key => $group)
                    <div class="mb-6 border p-4 rounded-lg bg-white shadow-md dark:bg-slate-850">
                        {{-- $key sudah berisi "Nama Alternatif (Tahun Ajaran Semester - Tanggal Jam)" --}}
                        <h4 class="text-xl font-semibold mb-2 dark:text-white">
                            {{ $key }}
                        </h4>
                        @php
                            $firstItem = $group->first();
                        @endphp
                        <p class="text-gray-600 dark:text-gray-400">Alternatif: {{ $firstItem->alternatif->alternatif_name ?? $firstItem->alternatif->user->name ?? '-' }} ({{ $firstItem->alternatif->alternatif_code ?? '-' }})</p>
                        <p class="text-gray-600 dark:text-gray-400">Periode: {{ $firstItem->academicPeriod->tahun_ajaran ?? 'N/A' }} {{ $firstItem->academicPeriod->semester ?? 'N/A' }}</p>
                        <p class="text-gray-600 dark:text-gray-400">Tanggal Penilaian: {{ \Carbon\Carbon::parse($firstItem->tanggal_penilaian)->format('d F Y') }}</p>
                        <p class="text-gray-600 dark:text-gray-400">Jam Penilaian: {{ \Carbon\Carbon::parse($firstItem->jam_penilaian)->format('H:i') }}</p>

                        <div class="overflow-x-auto mt-4">
                            <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-bold uppercase text-xxs text-black dark:text-white dark:border-white/40 border-b border-b-solid">Kriteria</th>
                                        <th class="px-6 py-3 text-left font-bold uppercase text-xxs text-black dark:text-white dark:border-white/40 border-b border-b-solid">Nilai</th>
                                        <th class="px-6 py-3 text-left font-bold uppercase text-xxs text-black dark:text-white dark:border-white/40 border-b border-b-solid">Detail Sertifikat</th>
                                        <th class="px-6 py-3 text-left font-bold uppercase text-xxs text-black dark:text-white dark:border-white/40 border-b border-b-solid">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group as $item) {{-- Loop melalui setiap penilaian dalam grup --}}
                                        <tr>
                                            <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                                                <span class="text-xs font-semibold dark:text-white dark:opacity-80">
                                                    {{ $item->criteria->criteria_name }} ({{ $item->criteria->criteria_code }})
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                                                <span class="text-xs font-semibold dark:text-white dark:opacity-80">
                                                    {{ $item->nilai }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                                                <span class="text-xs font-semibold dark:text-white dark:opacity-80">
                                                    @if ($item->certificate_details)
                                                        @foreach ($item->certificate_details as $cert)
                                                            Level: {{ $cert['level'] ?? 'N/A' }}, Count: {{ $cert['count'] ?? 'N/A' }} <br>
                                                        @endforeach
                                                    @else
                                                        -
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                                                <form action="{{ route('penilaian.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penilaian ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400">Belum ada rekam jejak penilaian.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- MODAL PENILAIAN --}}
@foreach ($alternatifs as $a)
    @role(['admin', 'guru'])
        <div id="modal-{{ $a->id }}" tabindex="-1" aria-hidden="true"
            class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm">
            <div class="relative w-full max-w-md max-h-full mx-auto mt-20">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-center justify-between p-4 border-b rounded-t md:p-5 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Penilaian untuk {{ $a->alternatif_code }} ({{ $a->user->name ?? 'N/A' }})
                        </h3>
                        <button type="button" onclick="closeModal('modal-{{ $a->id }}')"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form action="{{ route('penilaian.storeOrUpdate') }}" method="POST">
                        @csrf
                        <div class="p-4 md:p-5">
                            <input type="hidden" name="id_alternatif" value="{{ $a->id }}">

                            {{-- Ganti input tahun_ajaran dan semester dengan dropdown academic_period_id --}}
                            <div class="mb-4">
                                <label for="academic_period_id-modal-{{ $a->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Periode Akademik:</label>
                                <select name="academic_period_id" id="academic_period_id-modal-{{ $a->id }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                                    <option value="">Pilih Periode Akademik</option>
                                    @foreach($academicPeriods as $period)
                                        <option value="{{ $period->id }}"
                                            {{ old('academic_period_id', $selectedAcademicPeriodId) == $period->id ? 'selected' : '' }}>
                                            {{ $period->tahun_ajaran }} - {{ $period->semester }} {{ $period->is_active ? '(Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                @foreach ($criterias as $c)
                                    @php
                                        // Ambil nilai terbaru untuk kriteria ini pada alternatif ini
                                        // Filter berdasarkan periode akademik yang dipilih di halaman utama
                                        $nilaiTerbaru = $penilaians
                                            ->where('id_alternatif', $a->id)
                                            ->where('id_criteria', $c->id)
                                            ->where('academic_period_id', $selectedAcademicPeriodId) // Penting: filter berdasarkan periode yang dipilih
                                            ->first();

                                        // Ambil detail sertifikat jika ada
                                        $certificateDetails = $nilaiTerbaru ? $nilaiTerbaru->certificate_details : [];
                                    @endphp
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="nilai_{{ $a->id }}_{{ $c->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $c->criteria_code }}
                                        </label>
                                        <input type="number" min="0" step="0.01"
                                            value="{{ old('nilai.' . $c->id, $nilaiTerbaru ? $nilaiTerbaru->nilai : 0) }}"
                                            name="nilai[{{ $c->id }}]" id="nilai_{{ $a->id }}_{{ $c->id }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            required onchange="formatDecimal(this)">

                                        {{-- Bagian untuk input detail sertifikat jika diperlukan --}}
                                        @if ($c->criteria_type == 'Benefit' || $c->criteria_type == 'Cost') {{-- Sesuaikan dengan kriteria yang memerlukan sertifikat --}}
                                            <div class="mt-2" id="certificate_inputs_{{ $a->id }}_{{ $c->id }}">
                                                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Sertifikat:</label>
                                                <button type="button" onclick="addCertificateField('{{ $a->id }}', '{{ $c->id }}')" class="text-blue-500 text-sm hover:underline mb-2">Tambah Sertifikat</button>

                                                @if (!empty($certificateDetails))
                                                    @foreach ($certificateDetails as $index => $detail)
                                                        <div class="flex gap-2 mb-2 certificate-row">
                                                            <select name="certificate_level[{{ $c->id }}][]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                                <option value="">Pilih Level</option>
                                                                <option value="Nasional" {{ (old('certificate_level.' . $c->id . '.' . $index, $detail['level'] ?? '') == 'Nasional') ? 'selected' : '' }}>Nasional</option>
                                                                <option value="Provinsi" {{ (old('certificate_level.' . $c->id . '.' . $index, $detail['level'] ?? '') == 'Provinsi') ? 'selected' : '' }}>Provinsi</option>
                                                                <option value="Kabupaten/Kota" {{ (old('certificate_level.' . $c->id . '.' . $index, $detail['level'] ?? '') == 'Kabupaten/Kota') ? 'selected' : '' }}>Kabupaten/Kota</option>
                                                                <option value="Sekolah" {{ (old('certificate_level.' . $c->id . '.' . $index, $detail['level'] ?? '') == 'Sekolah') ? 'selected' : '' }}>Sekolah</option>
                                                                <option value="Partisipasi" {{ (old('certificate_level.' . $c->id . '.' . $index, $detail['level'] ?? '') == 'Partisipasi') ? 'selected' : '' }}>Partisipasi</option>
                                                            </select>
                                                            <input type="number" name="certificate_count[{{ $c->id }}][]" value="{{ old('certificate_count.' . $c->id . '.' . $index, $detail['count'] ?? 1) }}" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-20 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                            <button type="button" onclick="removeCertificateField(this)" class="text-red-500 text-sm hover:underline">Hapus</button>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
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
                    </form>
                </div>
            </div>
        </div>
    @endrole
@endforeach

<script>
    // Fungsi untuk membuka modal
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    // Fungsi untuk menutup modal
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Fungsi untuk memformat input desimal
    function formatDecimal(input) {
        input.value = parseFloat(input.value).toFixed(2);
    }

    // Fungsi untuk menambah field sertifikat (jika diperlukan)
    function addCertificateField(alternatifId, criteriaId) {
        const container = document.getElementById(`certificate_inputs_${alternatifId}_${criteriaId}`);
        const newRow = document.createElement('div');
        newRow.classList.add('flex', 'gap-2', 'mb-2', 'certificate-row');
        newRow.innerHTML = `
            <select name="certificate_level[${criteriaId}][]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                <option value="">Pilih Level</option>
                <option value="Nasional">Nasional</option>
                <option value="Provinsi">Provinsi</option>
                <option value="Kabupaten/Kota">Kabupaten/Kota</option>
                <option value="Sekolah">Sekolah</option>
                <option value="Partisipasi">Partisipasi</option>
            </select>
            <input type="number" name="certificate_count[${criteriaId}][]" value="1" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-20 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
            <button type="button" onclick="removeCertificateField(this)" class="text-red-500 text-sm hover:underline">Hapus</button>
        `;
        container.appendChild(newRow);
    }

    // Fungsi untuk menghapus field sertifikat
    function removeCertificateField(button) {
        button.closest('.certificate-row').remove();
    }

    // Tutup modal saat mengklik di luar modal
    window.onclick = function(event) {
        @foreach ($alternatifs as $a)
            const modal = document.getElementById('modal-{{ $a->id }}');
            if (modal && event.target === modal) { // Tambahkan cek `modal` tidak null
                closeModal('modal-{{ $a->id }}');
            }
        @endforeach
    }

    // Initialize tabs with Flowbite's JS (assuming it's loaded) or custom JS
    document.addEventListener('DOMContentLoaded', function() {
        const dataTabButton = document.getElementById('data-tab');
        const detailTabButton = document.getElementById('detail-tab');
        const dataContent = document.getElementById('data');
        const detailContent = document.getElementById('detail');

        function activateTab(tabButton, contentDiv) {
            dataTabButton.classList.remove('border-blue-600', 'text-blue-600');
            dataTabButton.classList.add('hover:text-gray-600', 'hover:border-gray-300', 'dark:hover:text-gray-300');
            detailTabButton.classList.remove('border-blue-600', 'text-blue-600');
            detailTabButton.classList.add('hover:text-gray-600', 'hover:border-gray-300', 'dark:hover:text-gray-300');

            tabButton.classList.add('border-blue-600', 'text-blue-600');
            tabButton.classList.remove('hover:text-gray-600', 'hover:border-gray-300', 'dark:hover:text-gray-300');

            dataContent.classList.add('hidden');
            detailContent.classList.add('hidden');
            contentDiv.classList.remove('hidden');
        }

        // Check if there's a selected tab in local storage or URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const activeTabFromUrl = urlParams.get('tab');

        if (activeTabFromUrl === 'detail') {
            activateTab(detailTabButton, detailContent);
        } else {
            activateTab(dataTabButton, dataContent);
        }

        dataTabButton.addEventListener('click', function() {
            activateTab(dataTabButton, dataContent);
            // Update URL without reloading page
            history.pushState(null, '', '?tab=data');
        });

        detailTabButton.addEventListener('click', function() {
            activateTab(detailTabButton, detailContent);
            // Update URL without reloading page
            history.pushState(null, '', '?tab=detail');
        });
    });
</script>
@endsection
