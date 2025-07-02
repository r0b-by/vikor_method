@extends('dashboard.layouts.dashboardmain')
@section('title', 'Hasil VIKOR')

@section('content')
<div class="flex flex-wrap -mx-3" data-aos="fade-zoom-in"
     data-aos-easing="ease-in-back"
     data-aos-delay="300"
     data-aos-offset="0">
    <div class="flex items-center justify-between mb-6 w-full">
        <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">
            Hasil Perhitungan VIKOR
        </h2>
    </div>

    <div class="flex-none w-full max-w-full px-3 overflow-x-hidden">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">

            <div class="flex justify-between items-center p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="dark:text-white">Daftar Hasil Akhir Perhitungan VIKOR</h6>
            </div>

            {{-- Filter untuk Hasil Akhir --}}
            <div class="p-6 pt-0 mt-4">
                <form action="{{ route('hasil.index') }}" method="GET" class="flex flex-wrap items-center space-x-4">
                    <div class="mb-2 md:mb-0">
                        <label for="filter_tahun_ajaran" class="text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Ajaran:</label>
                        <select name="tahun_ajaran" id="filter_tahun_ajaran" class="block w-auto py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Semua Tahun Ajaran</option>
                            {{-- $availableTahunAjarans harus diteruskan dari controller --}}
                            @foreach($availableTahunAjarans ?? [] as $tahun)
                                <option value="{{ $tahun }}" {{ ($selectedTahunAjaran ?? '') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2 md:mb-0">
                        <label for="filter_semester" class="text-sm font-medium text-gray-700 dark:text-gray-300">Semester:</label>
                        <select name="semester" id="filter_semester" class="block w-auto py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Semua Semester</option>
                            <option value="Ganjil" {{ ($selectedSemester ?? '') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ ($selectedSemester ?? '') == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Filter
                    </button>
                </form>
            </div>
            {{-- End Filter --}}

            {{-- Displaying the filtered period --}}
            <div class="p-6 pt-0 mt-4 text-slate-700 dark:text-slate-300 text-sm">
                <p><strong>Tahun Ajaran Aktif:</strong> {{ $selectedTahunAjaran ?? 'Semua' }}</p>
                <p><strong>Semester Aktif:</strong> {{ $selectedSemester ?? 'Semua' }}</p>
            </div>
            {{-- End displaying filtered period --}}

            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-6 overflow-x-auto">
                    @if ($hasil->isNotEmpty())
                        <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="px-6 py-3 text-left font-bold uppercase text-xxs text-black dark:text-white dark:border-white/40 border-b border-b-solid">
                                        Kode
                                    </th>
                                    <th class="px-6 py-3 text-left font-bold uppercase text-xxs text-black dark:text-white dark:border-white/40 border-b border-b-solid">
                                        Nama Alternatif
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
                                    <th class="px-6 py-3 text-left font-bold uppercase text-xxs text-black dark:text-white dark:border-white/40 border-b border-b-solid">
                                        Tanggal Perhitungan
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
                                                {{ $item->alternatif->user->name ?? $item->alternatif->alternatif_name ?? '-' }}
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
                                        <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                                            <span class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                {{ \Carbon\Carbon::parse($item->tanggal_penilaian)->format('d-m-Y') }} {{ \Carbon\Carbon::parse($item->jam_penilaian)->format('H:i') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-3 text-sm text-gray-500 dark:text-white">Belum ada data hasil perhitungan VIKOR.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">Tidak ada hasil perhitungan VIKOR yang ditemukan untuk filter ini.</p>
                    @endif
                </div>
            </div>
            <div class="flex justify-end p-6 pt-0">
                @role('admin')
                <a href="{{ route('hasil.cetak', ['tahun_ajaran' => $selectedTahunAjaran, 'semester' => $selectedSemester]) }}" class="inline-block px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                </a>
                @endrole
            </div>
        </div>
    </div>
</div>
@endsection
