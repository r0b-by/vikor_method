@extends('dashboard.layouts.dashboardmain')
@section('title', 'Perhitungan VIKOR')
@section('content')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div
                class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h5 class="dark:text-white">Perhitungan Metode VIKOR</h5>
                </div>

                {{-- Bagian Debug (Opsional: hapus saat produksi) 
                @if (config('app.env') === 'local') Hanya tampilkan di lingkungan lokal 
                    <div class="p-4 text-xs text-red-500">
                        <p>Jumlah Kriteria: {{ $criterias->count() }}</p>
                        <p>Jumlah Alternatif: {{ $alternatifs->count() }}</p>
                        <p>Jumlah Penilaian: {{ $penilaians->count() }} (Seharusnya: {{ $criterias->count() * $alternatifs->count() }})</p>
                        <p>Normalisasi ada? {{ empty($normalisasi) ? 'Tidak' : 'Ya' }}</p>
                        <p>Normalisasi Terbobot ada? {{ empty($weightedNormalization) ? 'Tidak' : 'Ya' }}</p>
                        <p>Ideal ada? {{ empty($ideal) ? 'Tidak' : 'Ya' }}</p>
                        <p>Si ada? {{ empty($Si) ? 'Tidak' : 'Ya' }}</p>
                        <p>Ri ada? {{ empty($Ri) ? 'Tidak' : 'Ya' }}</p>
                        <p>Qi ada? {{ empty($finalValues) ? 'Tidak' : 'Ya' }}</p>
                        <p>Ranking ada? {{ empty($ranking) ? 'Tidak' : 'Ya' }}</p>
                        <p>Perhitungan Dilakukan: {{ $calculationPerformed ? 'Ya' : 'Tidak' }}</p>
                    </div>
                @endif
                 Akhir Bagian Debug --}}

                <div class="p-6">
                    @if (session('warning'))
                        <div class="relative px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                            <strong class="font-bold">Peringatan!</strong>
                            <span class="block sm:inline">{{ session('warning') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="w-6 h-6 text-red-500 fill-current" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                            </span>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="relative px-4 py-3 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
                            <strong class="font-bold">Berhasil!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="w-6 h-6 text-green-500 fill-current" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                            </span>
                        </div>
                    @endif
                     @if (session('error'))
                        <div class="relative px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="w-6 h-6 text-red-500 fill-current" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                            </span>
                        </div>
                    @endif
                </div>


                {{-- START: Matriks Keputusan F --}}
                <div class="p-6 pt-0 pb-2">
                    <h2 class="dark:text-white text-lg font-bold mb-4">Matriks Keputusan F</h2>
                    <div class="p-6 overflow-x-auto">
                        @if ($criterias->isEmpty() || $alternatifs->isEmpty() || $penilaians->isEmpty() || $penilaians->count() !== ($alternatifs->count() * $criterias->count()))
                            <div class="relative px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                                <strong class="font-bold">Informasi:</strong>
                                <span class="block sm:inline">Data kriteria, alternatif, atau penilaian tidak lengkap. Matriks ini mungkin kosong.</span>
                            </div>
                        @else
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alternatifs as $a)
                                        <tr>
                                            <td
                                                class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ $a->alternatif_code }}
                                                </span>
                                            </td>
                                            @foreach ($criterias as $c)
                                                @php
                                                    $nilai = $penilaians->firstWhere(fn($p) => $p->id_alternatif == $a->id && $p->id_criteria == $c->id);
                                                @endphp
                                                <td
                                                    class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                        {{ $nilai ? $nilai->nilai : 0 }}
                                                    </span>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                {{-- END: Matriks Keputusan F --}}

                {{-- START: Tabel Normalisasi --}}
                <div class="p-6 pt-0 pb-2">
                    <h2 class="dark:text-white text-lg font-bold mb-4">Tabel Normalisasi (X)</h2>
                    <div class="p-6 overflow-x-auto">
                        @if (empty($normalisasi) || !$calculationPerformed)
                            <div class="relative px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                                <strong class="font-bold">Informasi:</strong>
                                <span class="block sm:inline">Data normalisasi tidak tersedia atau perhitungan belum lengkap.</span>
                            </div>
                        @else
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alternatifs as $a)
                                        <tr>
                                            <td
                                                class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ $a->alternatif_code }}
                                                </span>
                                            </td>
                                            @foreach ($criterias as $c)
                                                <td
                                                    class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                        {{ number_format($normalisasi[$a->id][$c->id] ?? 0, 3) }}
                                                    </span>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                {{-- END: Tabel Normalisasi --}}

                {{-- START: Tabel Normalisasi Terbobot --}}
                <div class="p-6 pt-0 pb-2">
                    <h2 class="dark:text-white text-lg font-bold mb-4">Tabel Normalisasi Terbobot (Y)</h2>
                    <div class="p-6 overflow-x-auto">
                        @if (empty($weightedNormalization) || !$calculationPerformed)
                            <div class="relative px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                                <strong class="font-bold">Informasi:</strong>
                                <span class="block sm:inline">Data normalisasi terbobot tidak tersedia atau perhitungan belum lengkap.</span>
                            </div>
                        @else
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alternatifs as $a)
                                        <tr>
                                            <td
                                                class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ $a->alternatif_code }}
                                                </span>
                                            </td>
                                            @foreach ($criterias as $c)
                                                <td
                                                    class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                        {{ number_format($weightedNormalization[$a->id][$c->id] ?? 0, 3) }}
                                                    </span>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                {{-- END: Tabel Normalisasi Terbobot --}}

                {{-- START: Tabel Selisih terhadap Nilai Ideal (Yi - Yj*) --}}
                <div class="p-6 pt-0 pb-2">
                    <h2 class="dark:text-white text-lg font-bold mb-4">Tabel Selisih terhadap Nilai Ideal (Yi - Yj*)</h2>
                    <div class="p-6 overflow-x-auto">
                        @if (empty($ideal) || empty($weightedNormalization) || !$calculationPerformed)
                            <div class="relative px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                                <strong class="font-bold">Informasi:</strong>
                                <span class="block sm:inline">Data selisih terhadap nilai ideal tidak tersedia atau perhitungan belum lengkap.</span>
                            </div>
                        @else
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alternatifs as $a)
                                        <tr>
                                            <td
                                                class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ $a->alternatif_code }}
                                                </span>
                                            </td>
                                            @foreach ($criterias as $c)
                                                <td
                                                    class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                        {{ number_format(abs(($ideal[$c->id] ?? 0) - ($weightedNormalization[$a->id][$c->id] ?? 0)), 3) }}
                                                    </span>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                {{-- END: Tabel Selisih terhadap Nilai Ideal --}}

                {{-- START: Tabel S dan R --}}
                <div class="p-6 pt-0 pb-2">
                    <h2 class="dark:text-white text-lg font-bold mb-4">Tabel S dan R</h2>
                    <div class="p-6 overflow-x-auto">
                        @if (empty($Si) || empty($Ri) || !$calculationPerformed)
                            <div class="relative px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                                <strong class="font-bold">Informasi:</strong>
                                <span class="block sm:inline">Data S dan R tidak tersedia atau perhitungan belum lengkap.</span>
                            </div>
                        @else
                            <table class="items-center w-full mb-0 overflow-x-auto align-top border-collapse dark:border-white/40 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Alternatif</th>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Nilai S</th>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Nilai R</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alternatifs as $a)
                                        <tr>
                                            <td
                                                class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ $a->alternatif_code }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ number_format($Si[$a->id] ?? 0, 3) }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ number_format($Ri[$a->id] ?? 0, 3) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                {{-- END: Tabel S dan R --}}

                {{-- START: Tabel Nilai Kompromi (Q) dan Ranking --}}
                <div class="p-6 pt-0 pb-2">
                    <h2 class="dark:text-white text-lg font-bold mb-4">Tabel Nilai Kompromi (Q) dan Ranking</h2>
                    <div class="p-6 overflow-x-auto">
                        @if (empty($finalValues) || empty($ranking) || !$calculationPerformed)
                            <div class="relative px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                                <strong class="font-bold">Informasi:</strong>
                                <span class="block sm:inline">Data Nilai Kompromi (Q) atau Ranking tidak tersedia atau perhitungan belum lengkap.</span>
                            </div>
                        @else
                            <table class="items-center w-full mb-0 overflow-x-auto align-top border-collapse dark:border-white/40 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Ranking</th>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Alternatif</th>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Nilai Q</th>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Mengurutkan berdasarkan ranking untuk tampilan --}}
                                    @php
                                        $sortedResults = collect($alternatifs)->map(function ($alternatif) use ($finalValues, $ranking, $Si, $Ri) {
                                            $id = $alternatif->id;
                                            return [
                                                'id' => $id,
                                                'alternatif_code' => $alternatif->alternatif_code,
                                                'nilai_q' => $finalValues[$id] ?? 0,
                                                'ranking' => $ranking[$id] ?? null,
                                                'nilai_s' => $Si[$id] ?? 0, // Tambahkan S untuk hidden input
                                                'nilai_r' => $Ri[$id] ?? 0, // Tambahkan R untuk hidden input
                                            ];
                                        })->sortBy('ranking')->values()->all();
                                    @endphp

                                    @foreach ($sortedResults as $result)
                                        <tr>
                                            <td
                                                class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ $result['ranking'] ?? '-' }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ $result['alternatif_code'] }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ number_format($result['nilai_q'] ?? 0, 3) }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="mb-0 text-xs font-xs font-semibold leading-tight dark:text-white dark:opacity-80 {{ ($result['ranking'] !== null && $result['ranking'] <= 10) ? 'text-green-500' : 'text-red-500' }}">
                                                    {{ ($result['ranking'] !== null && $result['ranking'] <= 10) ? 'Lulus' : 'Tidak Lulus' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="flex justify-end mt-4">
                                <form action="{{ route('hitung.simpan') }}" method="POST">
                                    @csrf
                                    {{-- Hidden inputs for S, R, Q, and ranking --}}
                                    @foreach ($sortedResults as $result) {{-- Gunakan $sortedResults --}}
                                        <input type="hidden" name="alternatif[]" value="{{ $result['id'] }}">
                                        <input type="hidden" name="finalValues[]" value="{{ $result['nilai_q'] }}">
                                        <input type="hidden" name="ranking[]" value="{{ $result['ranking'] }}">
                                        <input type="hidden" name="Si[]" value="{{ $result['nilai_s'] }}">
                                        <input type="hidden" name="Ri[]" value="{{ $result['nilai_r'] }}">
                                    @endforeach
                                    <button type="submit"
                                        class="inline-block px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none">
                                        Simpan Perhitungan
                                    </button>
                                </form>
                            </div>
                            <div class="mt-4 px-6 text-xs text-slate-400">
                                <em>* Q<sub>i</sub> yang lebih kecil berarti lebih baik (peringkat lebih tinggi).</em>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- END: Tabel Nilai Kompromi (Q) dan Ranking --}}

            </div>
        </div>
    </div>
@endsection