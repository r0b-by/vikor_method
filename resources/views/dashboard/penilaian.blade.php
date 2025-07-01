@extends('dashboard.layouts.dashboardmain')
@section('title', 'Penilaian')
@section('content')
    <div class="flex flex-wrap -mx-3" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="300" data-aos-offset="0">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">
                Matriks Decision
            </h2>
        </div>
        <div class="flex-none w-full max-w-full px-3 overflow-x-hidden">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="dark:text-white">Tabel Penilaian</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6 overflow-x-auto">
                        <table class="items-center w-full mb-0 overflow-x-auto align-top border-collapse dark:border-white/40 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Alternatif</th>
                                    @foreach ($criterias as $c)
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            {{ $c->criteria_code }}</th>
                                    @endforeach
                                    @role(['admin', 'guru'])
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Aksi</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alternatifs as $a)
                                    <tr>
                                        <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
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

                                            <td class="px-6 py-3 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">
                                                    {{ $penilaianForCriteria ? $penilaianForCriteria->nilai : 0 }}
                                                </span>
                                            </td>
                                        @endforeach

                                        @role(['admin', 'guru'])
                                        <td class="flex p-2 align-middle bg-transparent border-b just dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <button type="button" onclick="openModal('modal-{{ $a->id }}')"
                                                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Edit</button>
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

    @foreach ($alternatifs as $a)
        @role(['admin', 'guru'])
        <!-- Modal -->
        <div id="modal-{{ $a->id }}" tabindex="-1" aria-hidden="true"
            class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm">
            <div class="relative w-full max-w-md max-h-full mx-auto mt-20">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-center justify-between p-4 border-b rounded-t md:p-5 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Penilaian untuk {{ $a->alternatif_code }}
                        </h3>
                        <button type="button" onclick="closeModal('modal-{{ $a->id }}')"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form action="{{ route('penilaian.storeOrUpdate', $a->id) }}" method="POST">
                        @csrf
                        <div class="p-4 md:p-5">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <input type="hidden" name="id_alternatif" value="{{ $a->id }}">
                                @foreach ($criterias as $c)
                                    @php
                                        $nilai = $penilaians
                                            ->where('id_alternatif', $a->id)
                                            ->where('id_criteria', $c->id)
                                            ->first();
                                    @endphp
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="nilai_{{ $a->id }}_{{ $c->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $c->criteria_code }}
                                        </label>
                                        <input type="number" min="0" step="0.01"
                                            value="{{ $nilai ? $nilai->nilai : 0 }}"
                                            name="nilai[{{ $c->id }}]" id="nilai_{{ $a->id }}_{{ $c->id }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            required onchange="formatDecimal(this)">
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit"
                                class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg class="w-5 h-5 me-1 -ms-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                </svg>
                                Simpan Penilaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endrole
    @endforeach

    <script>
        // Fungsi untuk membuka modal
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        // Fungsi untuk menutup modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Fungsi untuk memformat input desimal
        function formatDecimal(input) {
            input.value = parseFloat(input.value).toFixed(2);
        }

        // Tutup modal saat mengklik di luar modal
        window.onclick = function(event) {
            @foreach ($alternatifs as $a)
                const modal = document.getElementById('modal-{{ $a->id }}');
                if (event.target === modal) {
                    closeModal('modal-{{ $a->id }}');
                }
            @endforeach
        }
    </script>
@endsection