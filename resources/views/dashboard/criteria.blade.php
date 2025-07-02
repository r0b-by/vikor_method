@extends('dashboard.layouts.dashboardmain')
@section('title', 'Kriteria')
@section('content')
    <div class="flex flex-wrap -mx-3"  data-aos="fade-zoom-in"
     data-aos-easing="ease-in-back"
     data-aos-delay="300"
     data-aos-offset="0">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Data Criteria</h2>
    </div>
        <div class="flex-none w-full max-w-full px-3 overflow-x-hidden">
            <div
                class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="dark:text-white">Tabel Kriteria</h6>
                    @role('admin') {{-- Hanya admin yang bisa menambah kriteria --}}
                    <button type="button" onclick="openModal('add-criteria')"
                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Tambah Kriteria</button>
                    @endrole
                </div>
                <!-- Main modal untuk menambah kriteria -->
                @role('admin') {{-- Modal hanya relevan jika user bisa menambah --}}
                <div id="add-criteria" tabindex="-1" aria-hidden="true"
                    class="hidden fixed inset-0 z-50 flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center backdrop-blur-sm items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative w-full max-w-md max-h-full p-4">
                        <!-- Konten modal -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Header modal -->
                            <div
                                class="flex items-center justify-between p-4 border-b rounded-t md:p-5 dark:border-gray-600">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Buat Kriteria Baru
                                </h3>
                                <button type="button" onclick="closeModal('add-criteria')"
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
                            <form action="{{ route('criteria.store') }}" method="POST" class="p-4 md:p-5">
                                @csrf
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="col-span-2">
                                        <label for="criteria_code"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode
                                            Kriteria</label>
                                        <input type="text" name="criteria_code" id="criteria_code"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="contoh: C1" required="">
                                    </div>
                                    <div class="col-span-2">
                                        <label for="criteria_name"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                                            Kriteria</label>
                                        <input type="text" name="criteria_name" id="criteria_name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="contoh: Media" required="">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="weight"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bobot</label>
                                        <input onchange="setTwoNumberDecimal" min="0" step="0.01" value="0.00"
                                            type="number" name="weight" id="weight"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="contoh: 20" required="">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="criteria_type"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                                        <select id="criteria_type" name="criteria_type"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option selected="">Pilih kategori</option>
                                            <option value="Benefit">Benefit</option>
                                            <option value="Cost">Cost</option>
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
                                    Tambah Kriteria Baru
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endrole
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6 overflow-x-auto">
                        <table
                            class="items-center w-full mb-0 overflow-x-auto align-top border-collapse dark:border-white/40 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        No</th>
                                    <th
                                        class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Kode Kriteria</th>
                                    <th
                                        class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Nama Kriteria</th>
                                    <th
                                        class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Tipe</th>
                                    <th
                                        class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Bobot</th>
                                    @role('admin') {{-- Hanya admin yang melihat kolom aksi --}}
                                    <th
                                        class="flex justify-center px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none  dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Aksi</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($criteria as $c)
                                    <tr>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                {{ $loop->iteration }}</span>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="mb-0 text-xs font-semibold leading-tight uppercase dark:text-white dark:opacity-80">
                                                {{ $c->criteria_code }}</span>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                {{ $c->criteria_name }}</span>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                {{ $c->criteria_type }}</span>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                {{ $c->weight }}</span>
                                        </td>
                                        @role('admin') {{-- Hanya admin yang melihat tombol aksi --}}
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="flex flex-wrap items-center justify-center">
                                                <button type="button"
                                                    onclick="openModal('edit-criteria-{{ $c->id }}')"
                                                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Edit</button>
                                                <form id="{{ $c->id }}"
                                                    action="{{ route('criteria.destroy', $c->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit"
                                                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Hapus</button>
                                                </form>
                                            </div>
                                            <!-- Main modal untuk mengedit kriteria -->
                                            <div id="edit-criteria-{{ $c->id }}" tabindex="-1" aria-hidden="true"
                                                class="hidden flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center backdrop-blur-sm items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                <div class="relative w-full max-w-md max-h-full p-4">
                                                    <!-- Konten modal -->
                                                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                        <!-- Header modal -->
                                                        <div
                                                            class="flex items-center justify-between p-4 border-b rounded-t md:p-5 dark:border-gray-600">
                                                            <h3
                                                                class="text-lg font-semibold text-gray-900 dark:text-white">
                                                                Edit Kriteria
                                                            </h3>
                                                            <button type="button" onclick="closeModal('edit-criteria-{{ $c->id }}')"
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
                                                        <form id="edit-form-{{ $c->id }}"
                                                            action="{{ route('criteria.update', $c->id) }}"
                                                            class="p-4 md:p-5" method="POST">
                                                            @method('POST')
                                                            @csrf
                                                            <input name="id" type="hidden"
                                                                value="{{ $c->id }}" />
                                                            <div class="grid grid-cols-2 gap-4 mb-4">
                                                                <div class="col-span-2">
                                                                    <label for="criteria_code"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode
                                                                        Kriteria</label>
                                                                    <input type="text" value="{{ $c->criteria_code }}"
                                                                        name="criteria_code" id="criteria_code"
                                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                                        placeholder="contoh: C1" required="">
                                                                </div>
                                                                <div class="col-span-2">
                                                                    <label for="criteria_name"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                                                                        Kriteria</label>
                                                                    <input type="text" value="{{ $c->criteria_name }}"
                                                                        name="criteria_name" id="criteria_name"
                                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                                        placeholder="contoh: Media" required="">
                                                                </div>
                                                                <div class="col-span-2 sm:col-span-1">
                                                                    <label for="weight"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bobot</label>
                                                                    <input type="number" onchange="setTwoNumberDecimal"
                                                                        min="0" step="0.01"
                                                                        value="{{ $c->weight }}" name="weight"
                                                                        id="weight"
                                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                                        placeholder="contoh: 20" required="">
                                                                </div>
                                                                <div class="col-span-2 sm:col-span-1">
                                                                    <label for="criteria_type"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                                                                    <select id="criteria_type" name="criteria_type"
                                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                                        <option hidden disabled value="">
                                                                            Pilih kategori</option>
                                                                        <option
                                                                            @if ($c->criteria_type == 'Benefit') selected @endif
                                                                            value="Benefit">Benefit</option>
                                                                        <option
                                                                            @if ($c->criteria_type == 'Cost') selected @endif
                                                                            value="Cost">Cost</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <button type="submit"
                                                                class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                                Perbarui Kriteria
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        @endrole
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        myHTMLNumberInput.onchange = setTwoNumberDecimal;

        function setTwoNumberDecimal(event) {
            this.value = parseFloat(this.value).toFixed(2);
        }
    </script>
@endsection
