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
                <h2 class="dark:text-white">Matriks Keputusan F</h2>
                
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6 overflow-x-auto">
                        @if ($criterias->isEmpty() || $alternatifs->isEmpty() || $penilaians->isEmpty() || $penilaians->count() !== ($alternatifs->count() * $criterias->count()))
                            <div class="alert alert-warning">
                                Data kriteria, alternatif, atau penilaian tidak lengkap. Matriks ini mungkin kosong.
                            </div>
                        @else
                            <table
                                class="items-center w-full mb-0 overflow-x-auto align-top border-collapse dark:border-white/40 text-slate-500">
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
                                                    $penilaianForCriteria = $penilaians
                                                        ->where('id_alternatif', $a->id)
                                                        ->where('id_criteria', $c->id)
                                                        ->first();
                                                @endphp

                                                <td
                                                    class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                        {{ $penilaianForCriteria ? $penilaianForCriteria->nilai : 0 }}
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
                <h6 class="dark:text-white">Normalisasi</h6>
               
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6 overflow-x-auto">
                        @if (empty($normalisasi))
                            <div class="alert alert-warning">
                                Data normalisasi tidak tersedia atau tidak lengkap. Tabel ini mungkin kosong.
                            </div>
                        @else
                            <table
                                class="items-center w-full mb-0 overflow-x-auto align-top border-collapse dark:border-white/40 text-slate-500">
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
                                                        {{ $normalisasi[$a->id][$c->id] ?? 'N/A' }}
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
                <h6 class="dark:text-white">Normalisasi Terbobot</h6>
                
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6 overflow-x-auto">
                        @if (empty($weightedNormalization))
                            <div class="alert alert-warning">
                                Data normalisasi terbobot tidak tersedia atau tidak lengkap. Tabel ini mungkin kosong.
                            </div>
                        @else
                            <table
                                class="items-center w-full mb-0 overflow-x-auto align-top border-collapse dark:border-white/40 text-slate-500">
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
                                                        {{ $weightedNormalization[$a->id][$c->id] ?? 'N/A' }}
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
                <h6 class="dark:text-white">Selisih terhadap Nilai Ideal $d_{ij} = |f^*_j - r_{ij_{terbobot}}|$</h6>
              
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6 overflow-x-auto">
                        @if (empty($ideal) || empty($weightedNormalization))
                            <div class="alert alert-warning">
                                Data ideal atau normalisasi terbobot tidak tersedia atau tidak lengkap. Tabel ini mungkin kosong.
                            </div>
                        @else
                            <table
                                class="items-center w-full mb-0 overflow-x-auto align-top border-collapse dark:border-white/40 text-slate-500">
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
                <h6 class="dark:text-white">Tabel S dan R</h6>
                
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6 overflow-x-auto">
                        @if (empty($Si) || empty($Ri))
                            <div class="alert alert-warning">
                                Data S dan R tidak tersedia atau tidak lengkap. Tabel ini mungkin kosong.
                            </div>
                        @else
                            <table
                                class="items-center w-full mb-0 overflow-x-auto align-top border-collapse dark:border-white/40 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Alternatif</th>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            S</th>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            R</th>
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
                                                    {{ $Si[$a->id] ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ $Ri[$a->id] ?? 'N/A' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <h6 class="dark:text-white">Tabel Nilai Kompromi (Q) dan Ranking</h6>
                
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6 overflow-x-auto">
                        @if (empty($finalValues) || empty($ranking))
                            <div class="alert alert-warning">
                                Data nilai kompromi atau ranking tidak tersedia atau tidak lengkap. Tabel ini mungkin kosong.
                            </div>
                        @else
                            <table
                                class="items-center w-full mb-0 overflow-x-auto align-top border-collapse dark:border-white/40 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Alternatif</th>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Q Value</th>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Ranking</th>
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
                                                    {{ $finalValues[$a->id] ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ $ranking[$a->id] ?? 'N/A' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                        <div class="flex justify-end mt-6 px-6">
                            <form action="{{ route('hitung.simpan') }}" method="POST">
                                @csrf
                                {{-- Hidden inputs for S, R, Q, and ranking --}}
                                @foreach ($alternatifs as $a)
                                    <input type="hidden" name="alternatif[]" value="{{ $a->id }}">
                                    <input type="hidden" name="finalValues[]" value="{{ $finalValues[$a->id] ?? '' }}">
                                    <input type="hidden" name="ranking[]" value="{{ $ranking[$a->id] ?? '' }}">
                                    <input type="hidden" name="Si[]" value="{{ $Si[$a->id] ?? '' }}">
                                    <input type="hidden" name="Ri[]" value="{{ $Ri[$a->id] ?? '' }}">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection