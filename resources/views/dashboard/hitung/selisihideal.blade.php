@extends('dashboard.layouts.dashboardmain')
@section('title', 'Hitung')
@section('content')

<!-- ========== Selisih terhadap Nilai Ideal (|f* - vij|) ========== -->
<div id="selisihideal" class="flex flex-wrap -mx-3 mt-6"  data-aos="fade-zoom-in"
     data-aos-easing="ease-in-back"
     data-aos-delay="300"
     data-aos-offset="0">
     <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl xl:text-3xl font-bold text-slate-900 dark:text-white">
            VVisekriterijumsko Kompromisno Rangiranje | Calculation
        </h2>
    </div>
    <div class="flex-none w-full max-w-full px-3 overflow-x-hidden">
        <div
            class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">

            <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div>
                    <h6 class="dark:text-white">Menghitung selisih terhadap nilai ideal (f)*</h6>

                    {{-- 🔗 Navigasi ke bagian lain --}}
                    <div class="mt-4 flex flex-wrap gap-2 text-sm">
                        <a href="{{ route('hitung.matriks') }}"
                        class="px-4 py-1.5 rounded-md bg-blue-50 text-blue-700 font-medium hover:bg-blue-100 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600 transition">
                            Ke Matriks Keputusan
                        </a>
                        <a href="{{ route('hitung.normalisasi') }}"
                        class="px-4 py-1.5 rounded-md bg-blue-50 text-blue-700 font-medium hover:bg-blue-100 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600 transition">
                            Ke Normalisasi Matriks
                        </a>
                        <a href="{{ route('hitung.normalisasiterbobot') }}"
                        class="px-4 py-1.5 rounded-md bg-blue-50 text-blue-700 font-medium hover:bg-blue-100 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600 transition">
                            Ke Normalisasi Terbobot
                        </a>
                        <a href="{{ route('hitung.utility') }}"
                        class="px-4 py-1.5 rounded-md bg-blue-50 text-blue-700 font-medium hover:bg-blue-100 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600 transition">
                            Ke Utility S dan R
                        </a>
                        <a href="{{ route('hitung.kompromi') }}"
                        class="px-4 py-1.5 rounded-md bg-blue-50 text-blue-700 font-medium hover:bg-blue-100 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600 transition">
                            Ke Nilai Rangking Qi
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-6 overflow-x-auto">
                    <table class="items-center w-full mb-0 overflow-x-auto align-top border-collapse dark:border-white/40 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left uppercase text-xxs text-slate-400 opacity-70">Alternatif</th>
                                @foreach ($criteria as $c)
                                    <th class="px-6 py-3 font-bold text-left uppercase text-xxs text-slate-400 opacity-70">{{ $c->criteria_code }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alternatif as $keyRow => $a)
                                <tr>
                                    <td class="px-6 py-3">
                                        <span class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                            {{ $a->alternatif_code }}
                                        </span>
                                    </td>
                                    @foreach ($criteria as $keyColumn => $c)
                                        <td class="px-6 py-3">
                                            <span class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                {{ number_format(abs($ideal[$keyColumn] - $weightedNormalization[$keyRow][$keyColumn]), 3) }}
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
</div>

@endsection
