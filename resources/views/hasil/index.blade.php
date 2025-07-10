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

            {{-- Filter --}}
            <div class="p-6 pt-0 mt-4">
                <form action="{{ route('hasil.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                    <div>
                        <label for="filter_tahun_ajaran" class="text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Ajaran:</label>
                        <select name="tahun_ajaran" id="filter_tahun_ajaran" class="block w-auto py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Semua Tahun Ajaran</option>
                            @foreach($tahunAjarans as $tahun)
                                <option value="{{ $tahun }}" {{ ($selectedTahunAjaran ?? '') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filter_semester" class="text-sm font-medium text-gray-700 dark:text-gray-300">Semester:</label>
                        <select name="semester" id="filter_semester" class="block w-auto py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Semua Semester</option>
                            <option value="Ganjil" {{ ($selectedSemester ?? '') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ ($selectedSemester ?? '') == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Filter
                    </button>
                </form>
            </div>
            {{-- End Filter --}}

            <div class="p-6 pt-0 mt-4 text-slate-700 dark:text-slate-300 text-sm">
                <p><strong>Tahun Ajaran Aktif:</strong> {{ $selectedTahunAjaran ?? 'Semua' }}</p>
                <p><strong>Semester Aktif:</strong> {{ $selectedSemester ?? 'Semua' }}</p>
            </div>

            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-6 overflow-x-auto">
                    @if ($hasil->isNotEmpty())
                        <table class="w-full border-collapse text-sm text-left text-slate-600 dark:text-slate-300">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 font-bold uppercase text-black dark:text-white border-b">Kode</th>
                                    <th class="px-6 py-3 font-bold uppercase text-black dark:text-white border-b">Nama Alternatif</th>
                                    <th class="px-6 py-3 font-bold uppercase text-black dark:text-white border-b">Nilai S</th>
                                    <th class="px-6 py-3 font-bold uppercase text-black dark:text-white border-b">Nilai R</th>
                                    <th class="px-6 py-3 font-bold uppercase text-black dark:text-white border-b">Nilai Q</th>
                                    <th class="px-6 py-3 font-bold uppercase text-black dark:text-white border-b">Ranking</th>
                                    <th class="px-6 py-3 font-bold uppercase text-black dark:text-white border-b">Status</th>
                                    <th class="px-6 py-3 font-bold uppercase text-black dark:text-white border-b">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hasil as $item)
                                    <tr>
                                        <td class="px-6 py-3 border-b">{{ $item->alternatif->alternatif_code ?? '-' }}</td>
                                        <td class="px-6 py-3 border-b">{{ $item->alternatif->user->name ?? $item->alternatif->alternatif_name ?? '-' }}</td>
                                        <td class="px-6 py-3 border-b">{{ number_format($item->nilai_s, 3) }}</td>
                                        <td class="px-6 py-3 border-b">{{ number_format($item->nilai_r, 3) }}</td>
                                        <td class="px-6 py-3 border-b">{{ number_format($item->nilai_q, 3) }}</td>
                                        <td class="px-6 py-3 border-b font-bold">{{ $item->ranking }}</td>
                                        <td class="px-6 py-3 border-b">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $item->status == 'Lulus' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 border-b">{{ \Carbon\Carbon::parse($item->tanggal_penilaian)->format('d-m-Y') }} {{ \Carbon\Carbon::parse($item->jam_penilaian)->format('H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">Tidak ada hasil perhitungan VIKOR untuk filter ini.</p>
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