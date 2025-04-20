@extends('dashboard.layouts.dashboardmain')
@section('title', 'Hasil VIKOR')

@section('content')
<!-- ========== Hasil VIKOR ========== -->
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3 overflow-x-hidden">
        <div
            class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            
            <div class="flex justify-between items-center p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="dark:text-white">Hasil Akhir Perhitungan VIKOR</h6>
                <a href="{{ route('hasil.cetak') }}" class="inline-block px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                </a>
            </div>

            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-6 overflow-x-auto">
                    <table
                        class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                            <th class="px-6 py-3 text-left font-bold uppercase text-xxs text-black dark:text-white dark:border-white/40 border-b border-b-solid">
                                Alternatif
                            </th>
                            <th class="px-6 py-3 text-left font-bold uppercase text-xxs text-black dark:text-white dark:border-white/40 border-b border-b-solid">
                                Nilai S
                            </th>
                            <th class="px-6 py-3 text-left font-bold uppercase text-xxs text-black dark:text-white dark:border-white/40 border-b border-b-solid">
                                Nilai R
                            </th>
                            <th class="px-6 py-3 text-left font-bold uppercase text-xxs text-black dark:text-white dark:border-white/40 border-b border-b-solid">
                                Nilai Q
                            </th>
                            <th class="px-6 py-3 text-left font-bold uppercase text-xxs text-black dark:text-white dark:border-white/40 border-b border-b-solid">
                                Ranking
                            </th>
                            <th class="px-6 py-3 text-left font-bold uppercase text-xxs text-black dark:text-white dark:border-white/40 border-b border-b-solid">
                                Status
                            </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hasil as $item)
                                <tr>
                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                                        <span class="text-xs font-semibold dark:text-white dark:opacity-80">
                                            {{ $item->alternatif->alternatif_code ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                                        <span class="text-xs font-semibold dark:text-white dark:opacity-80">
                                            {{ number_format($item->nilai_s, 4) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                                        <span class="text-xs font-semibold dark:text-white dark:opacity-80">
                                            {{ number_format($item->nilai_r, 4) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                                        <span class="text-xs font-semibold dark:text-white dark:opacity-80">
                                            {{ number_format($item->nilai_q, 4) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                                        <span class="text-xs font-bold dark:text-white dark:opacity-80">
                                            {{ $item->ranking }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                                        <span class="text-xs font-semibold px-3 py-1 rounded-full
                                            {{ $item->status == 'Lulus' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-sm text-gray-500 dark:text-white">Belum ada data hasil perhitungan VIKOR.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
