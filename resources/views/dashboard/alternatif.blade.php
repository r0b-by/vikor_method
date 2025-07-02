@extends('dashboard.layouts.dashboardmain')
@section('title', 'Alternatif')
@section('content')
    <div class="flex flex-wrap -mx-3" data-aos="fade-zoom-in"
         data-aos-easing="ease-in-back"
         data-aos-delay="300"
         data-aos-offset="0">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Data Alternatif</h2>
        </div>
        <div class="flex-none w-full max-w-full px-3 overflow-x-hidden">
            <div
                class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="dark:text-white">Tabel Alternatif</h6>
                    @role(['admin', 'guru']) {{-- Hanya admin dan guru yang bisa menambah alternatif --}}
                    <button type="button" onclick="openModal('add-alternatif')"
                            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Tambah Alternatif
                    </button>
                    @endrole
                </div>
                <!-- Main modal untuk menambah alternatif -->
                @role(['admin', 'guru']) {{-- Modal hanya relevan jika user bisa menambah --}}
                <div id="add-alternatif" tabindex="-1" aria-hidden="true"
                     class="hidden flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center backdrop-blur-sm items-start pt-20 w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative w-full max-w-md max-h-full p-4">
                        <!-- Konten modal -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Header modal -->
                            <div
                                class="flex items-center justify-between p-4 border-b rounded-t md:p-5 dark:border-gray-600">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Buat Alternatif Baru
                                </h3>
                                <button type="button" onclick="closeModal('add-alternatif')"
                                        class="focus:outline-none text-white bg-warning-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                         fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Tutup modal</span>
                                </button>
                            </div>
                            <!-- Body modal -->
                            <form action="{{ route('alternatif.store') }}" method="POST" class="p-4 md:p-5">
                                @csrf
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="col-span-2">
                                        <label for="alternatif_code"
                                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode
                                            Alternatif</label>
                                        <input type="text" name="alternatif_code" id="alternatif_code"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                               placeholder="contoh: A1" required="" value="{{ old('alternatif_code') }}">
                                    </div>
                                    <div class="col-span-2">
                                        <label for="alternatif_name"
                                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                                            Alternatif</label>
                                        <input type="text" name="alternatif_name" id="alternatif_name"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                               placeholder="contoh: Media" required="" value="{{ old('alternatif_name') }}">
                                    </div>

                                    {{-- Tambahkan dropdown Tahun Ajaran & Semester --}}
                                    <div class="col-span-2">
                                        <label for="academic_period_combined_add" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun Ajaran & Semester</label>
                                        <select id="academic_period_combined_add" name="academic_period_combined"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required>
                                            <option value="">Pilih Tahun Ajaran & Semester</option>
                                            @foreach($academicPeriods as $period)
                                                <option value="{{ $period->tahun_ajaran . '|' . $period->semester }}"
                                                        {{ old('academic_period_combined') == $period->tahun_ajaran . '|' . $period->semester ? 'selected' : '' }}>
                                                    {{ $period->tahun_ajaran }} - {{ $period->semester }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <button type="submit"
                                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-5 h-5 me-1 -ms-1" fill="currentColor" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                    Tambah Alternatif Baru
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endrole
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6 overflow-x-auto">
                        <table
                            class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        No</th>
                                    <th
                                        class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Kode Alternatif</th>
                                    <th
                                        class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Nama Alternatif</th>
                                    <th
                                        class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Tahun Ajaran</th>
                                    <th
                                        class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Semester</th>
                                    <th
                                        class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Status Perhitungan</th>
                                    @role(['admin', 'guru']) {{-- Hanya admin dan guru yang melihat kolom aksi --}}
                                    <th
                                        class="flex justify-center px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Aksi</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($alternatif as $a)
                                    <tr>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                {{ $loop->iteration }} </span>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="mb-0 text-xs font-semibold leading-tight uppercase dark:text-white dark:opacity-80">
                                                {{ $a->alternatif_code }} </span>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                {{ $a->alternatif_name }}</span>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                {{ $a->tahun_ajaran }}</span>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                {{ $a->semester }}</span>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                {{ $a->status_perhitungan }}</span>
                                        </td>
                                        @role(['admin', 'guru']) {{-- Hanya admin dan guru yang melihat tombol aksi --}}
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="flex flex-wrap items-center justify-center">
                                                <button type="button"
                                                        onclick="openModal('edit-alternatif-{{ $a->id }}')"
                                                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Edit</button>
                                                <form id="{{ $a->id }}"
                                                      action="{{ route('alternatif.destroy', $a->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit"
                                                            class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Hapus</button>
                                                </form>
                                            </div>
                                            <!-- Main modal untuk mengedit alternatif -->
                                            <div id="edit-alternatif-{{ $a->id }}" tabindex="-1" aria-hidden="true"
                                                 class="hidden flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center backdrop-blur-sm items-start pt-20 w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                <div class="relative w-full max-w-md max-h-full p-4">
                                                    <!-- Konten modal -->
                                                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                        <!-- Header modal -->
                                                        <div
                                                            class="flex items-center justify-between p-4 border-b rounded-t md:p-5 dark:border-gray-600">
                                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                                Edit Alternatif
                                                            </h3>
                                                            <button type="button" onclick="closeModal('edit-alternatif-{{ $a->id }}')"
                                                                    class="focus:outline-none text-white bg-warning-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                     fill="none" viewBox="0 0 14 14">
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                                          stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                                </svg>
                                                                <span class="sr-only">Tutup modal</span>
                                                            </button>
                                                        </div>
                                                        <!-- Body modal -->
                                                        <form id="edit-form-{{ $a->id }}"
                                                              action="{{ route('alternatif.update', $a->id) }}"
                                                              method="POST" class="p-4 md:p-5">
                                                            @method('PUT')
                                                            @csrf
                                                            <input name="id" type="hidden"
                                                                   value="{{ $a->id }}" />
                                                            <div class="grid grid-cols-2 gap-4 mb-4">
                                                                <div class="col-span-2">
                                                                    <label for="alternatif_code"
                                                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode
                                                                        Alternatif</label>
                                                                    <input type="text" name="alternatif_code"
                                                                           id="alternatif_code"
                                                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                                           value="{{ old('alternatif_code', $a->alternatif_code) }}"
                                                                           placeholder="contoh: C1" required="">
                                                                </div>
                                                                <div class="col-span-2">
                                                                    <label for="alternatif_name"
                                                                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                                                                        Alternatif</label>
                                                                    <input type="text" name="alternatif_name"
                                                                           id="alternatif_name"
                                                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                                           value="{{ old('alternatif_name', $a->alternatif_name) }}"
                                                                           placeholder="contoh: Media" required="">
                                                                </div>

                                                                {{-- Tambahkan dropdown Tahun Ajaran & Semester untuk Edit --}}
                                                                <div class="col-span-2">
                                                                    <label for="academic_period_combined_edit_{{ $a->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun Ajaran & Semester</label>
                                                                    <select id="academic_period_combined_edit_{{ $a->id }}" name="academic_period_combined"
                                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                                            required>
                                                                        <option value="">Pilih Tahun Ajaran & Semester</option>
                                                                        @foreach($academicPeriods as $period)
                                                                            <option value="{{ $period->tahun_ajaran . '|' . $period->semester }}"
                                                                                    {{ old('academic_period_combined', $a->tahun_ajaran . '|' . $a->semester) == $period->tahun_ajaran . '|' . $period->semester ? 'selected' : '' }}>
                                                                                {{ $period->tahun_ajaran }} - {{ $period->semester }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                            </div>
                                                            <button type="submit"
                                                                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                                <svg class="w-5 h-5 me-1 -ms-1" fill="currentColor"
                                                                     viewBox="0 0 20 20"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd"
                                                                          d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                                          clip-rule="evenodd"></path>
                                                                </svg>
                                                                Perbarui Alternatif
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        @endrole
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-2 text-center bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                                            <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80">Belum ada alternatif yang ditambahkan.</span>
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
@endsection
