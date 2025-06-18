@extends('dashboard.layouts.dashboardmain')
@section('title', 'Hitung')
@section('content')

<!-- ========== Perangkingan ========== -->
<div id="kompromi" class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3 overflow-x-hidden">
        <div
            class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            
            <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div>
                    <h6 class="dark:text-white">Menghitung Nilai Kompromi (Q<sub>i</sub>) dan Peringkat Akhir</h6>

                    {{-- 🔗 Navigasi --}}
                    <div class="mt-2 text-sm space-x-3">
                        <a href="{{ route('hitung.matriks') }}" class="text-blue-600 hover:underline">Matriks</a>
                        <a href="{{ route('hitung.normalisasi') }}" class="text-blue-600 hover:underline">Normalisasi</a>
                        <a href="{{ route('hitung.normalisasiterbobot') }}" class="text-blue-600 hover:underline">Normalisasi Terbobot</a>
                        <a href="{{ route('hitung.selisihideal') }}" class="text-blue-600 hover:underline">Selisih Ideal</a>
                        <a href="{{ route('hitung.utility') }}" class="text-blue-600 hover:underline">Nilai S & R</a>
                    </div>
                </div>
            </div>

            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-6 overflow-x-auto">
                    <table
                        class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400 opacity-70">Alternatif</th>
                                <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400 opacity-70">Q<sub>i</sub></th>
                                <th class="px-6 py-3 text-left text-xxs font-bold uppercase text-slate-400 opacity-70">Ranking</th>
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
                                            {{ isset($finalValues[$key]) ? number_format($finalValues[$key], 4) : 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="text-xs font-semibold dark:text-white dark:opacity-80">
                                            {{ $ranking[$key] ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Tombol Simpan --}}
                    <div class="flex justify-end mt-6 px-6">
                        <form action="{{ route('hitung.simpan') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="inline-block px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none">
                                Simpan Perhitungan
                            </button>
                        </form>
                    </div>

                    {{-- Catatan --}}
                    <div class="mt-4 px-6 text-xs text-slate-400">
                        <em>* Q<sub>i</sub> yang lebih kecil berarti lebih baik (peringkat lebih tinggi).</em>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
