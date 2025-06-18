@extends('dashboard.layouts.dashboardmain')
@section('title', 'Hitung')
@section('content')

<!-- ========== SI dan RI ========== -->
<div id="utility" class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3 overflow-x-hidden">
        <div
            class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            
            <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div>
                    <h6 class="dark:text-white">Menghitung Nilai Utility Measure (S) dan Regret Measure (R)</h6>

                    {{-- 🔗 Navigasi ke bagian lain --}}
                    <div class="mt-2 text-sm space-x-3">
                        <a href="{{ route('hitung.matriks') }}" class="text-blue-600 hover:underline">Ke Matriks Keputusan</a>
                        <a href="{{ route('hitung.normalisasi') }}" class="text-blue-600 hover:underline">Ke Normalisasi</a>
                        <a href="{{ route('hitung.normalisasiterbobot') }}" class="text-blue-600 hover:underline">Ke Normalisasi Terbobot</a>
                        <a href="{{ route('hitung.selisihideal') }}" class="text-blue-600 hover:underline">Ke Selisih Nilai Ideal</a>
                        <a href="{{ route('hitung.kompromi') }}" class="text-blue-600 hover:underline">Ke Nilai Qi</a>
                    </div>
                </div>
            </div>

            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-6 overflow-x-auto">
                    <table class="items-center w-full mb-0 overflow-x-auto align-top border-collapse dark:border-white/40 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left uppercase text-xxs text-slate-400 opacity-70">Alternatif</th>
                                <th class="px-6 py-3 font-bold text-left uppercase text-xxs text-slate-400 opacity-70">S<sub>i</sub></th>
                                <th class="px-6 py-3 font-bold text-left uppercase text-xxs text-slate-400 opacity-70">R<sub>i</sub></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alternatif as $key => $a)
                                <tr>
                                    <td class="px-6 py-3">
                                        <span class="text-xs font-semibold dark:text-white dark:opacity-80">
                                            {{ $a->alternatif_code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="text-xs font-semibold dark:text-white dark:opacity-80">
                                            {{ isset($Si[$key]) ? number_format($Si[$key], 4) : 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="text-xs font-semibold dark:text-white dark:opacity-80">
                                            {{ isset($Ri[$key]) ? number_format($Ri[$key], 4) : 'N/A' }}
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
</div>

@endsection
