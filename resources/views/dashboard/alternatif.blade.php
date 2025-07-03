@extends('dashboard.layouts.dashboardmain')
@section('title', 'Alternatif')
@section('content')
<div class="flex flex-wrap -mx-3" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="300"
    data-aos-offset="0">

    <!-- Futuristic Header -->
    <div class="flex items-center justify-between mb-6 w-full">
        <div>
            <h2 class="text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-purple-600">
                Manajemen Alternatif
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Advanced alternatif management dashboard</p>
        </div>
    </div>

    <!-- Futuristic Filter Card with Glass Morphism -->
    <div class="w-full px-3 mb-6">
        <div class="bg-white/80 dark:bg-slate-800/50 backdrop-blur-lg rounded-xl shadow-lg p-6 border border-white/20 dark:border-slate-700/50">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                        {{ count($alternatif) }} Alternatif
                    </span>
                </div>
                
                @role(['admin', 'guru'])
                <button type="button" onclick="openModal('add-alternatif')"
                    class="flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg hover:from-blue-600 hover:to-purple-700 focus:ring-4 focus:outline-none focus:ring-blue-300 shadow-lg transition-all duration-200 transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Alternatif
                </button>
                @endrole
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="w-full px-3">
        <!-- Futuristic Table Card -->
        <div class="bg-white/80 dark:bg-slate-800/50 backdrop-blur-lg rounded-xl shadow-lg border border-white/20 dark:border-slate-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                    <thead class="bg-gray-50/80 dark:bg-slate-800/80">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                Kode Alternatif
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                Nama Alternatif
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                Tahun Ajaran
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                Semester
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                Status
                            </th>
                            @role(['admin', 'guru'])
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                Aksi
                            </th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody class="bg-white/50 dark:bg-slate-800/50 divide-y divide-gray-200 dark:divide-slate-700">
                        @forelse ($alternatif as $a)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/80 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 flex items-center justify-center">
                                            <span class="text-blue-600 dark:text-purple-400 font-medium">{{ substr($a->alternatif_code, 0, 2) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $a->alternatif_code }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $a->alternatif_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">
                                    {{ $a->tahun_ajaran }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                        {{ $a->semester }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($a->status_perhitungan == 'Aktif')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                            {{ $a->status_perhitungan }}
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                            {{ $a->status_perhitungan }}
                                        </span>
                                    @endif
                                </td>
                                
                                @role(['admin', 'guru'])
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button" onclick="openModal('edit-alternatif-{{ $a->id }}')"
                                        class="text-blue-600 hover:text-blue-900 dark:text-purple-400 dark:hover:text-purple-300 mr-3 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('alternatif.destroy', $a->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alternatif ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                                @endrole
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-slate-400">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-slate-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-gray-600 dark:text-slate-300">Belum ada data alternatif.</p>
                                        <p class="text-sm text-gray-500 dark:text-slate-500 mt-1">Silakan tambahkan alternatif baru untuk memulai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($alternatif->hasPages())
            <div class="px-6 py-4 border-t border-gray-200/50 dark:border-slate-700/30">
                {{ $alternatif->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Alternatif Modal -->
@role(['admin', 'guru'])
<div id="add-alternatif" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto h-modal h-full backdrop-blur-sm">
    <div class="relative w-full h-full max-w-2xl mx-auto mt-10">
        <!-- Modal content -->
        <div class="relative bg-white rounded-xl shadow-xl dark:bg-slate-800 border border-white/20 dark:border-slate-700/50">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-5 border-b rounded-t-xl dark:border-slate-700 bg-gradient-to-r from-blue-500 to-purple-600">
                <h3 class="text-xl font-medium text-white">
                    Tambah Alternatif Baru
                </h3>
                <button type="button" onclick="closeModal('add-alternatif')"
                    class="text-white hover:bg-white/20 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition-colors duration-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="{{ route('alternatif.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="alternatif_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Alternatif</label>
                            <input type="text" name="alternatif_code" id="alternatif_code"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                placeholder="Contoh: A1" required>
                        </div>
                        <div>
                            <label for="alternatif_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Alternatif</label>
                            <input type="text" name="alternatif_name" id="alternatif_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                placeholder="Contoh: Siswa A" required>
                        </div>
                        <div>
                            <label for="academic_period_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Periode Akademik</label>
                            <select name="academic_period_id" id="academic_period_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                required>
                                <option value="">Pilih Periode Akademik</option>
                                @foreach($academicPeriods as $period)
                                    <option value="{{ $period->id }}">
                                        {{ $period->tahun_ajaran }} - {{ $period->semester }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b-xl dark:border-slate-700">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal('add-alternatif')"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-500 dark:hover:text-white dark:hover:bg-slate-600 dark:focus:ring-slate-600">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endrole

<!-- Edit Alternatif Modals -->
@role(['admin', 'guru'])
@foreach ($alternatif as $a)
<div id="edit-alternatif-{{ $a->id }}" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto h-modal h-full backdrop-blur-sm">
    <div class="relative w-full h-full max-w-2xl mx-auto mt-10">
        <!-- Modal content -->
        <div class="relative bg-white rounded-xl shadow-xl dark:bg-slate-800 border border-white/20 dark:border-slate-700/50">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-5 border-b rounded-t-xl dark:border-slate-700 bg-gradient-to-r from-blue-500 to-purple-600">
                <h3 class="text-xl font-medium text-white">
                    Edit Alternatif
                </h3>
                <button type="button" onclick="closeModal('edit-alternatif-{{ $a->id }}')"
                    class="text-white hover:bg-white/20 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition-colors duration-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="{{ route('alternatif.update', $a->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="alternatif_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Alternatif</label>
                            <input type="text" name="alternatif_code" id="alternatif_code"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                value="{{ $a->alternatif_code }}" required>
                        </div>
                        <div>
                            <label for="alternatif_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Alternatif</label>
                            <input type="text" name="alternatif_name" id="alternatif_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                value="{{ $a->alternatif_name }}" required>
                        </div>
                        <div>
                            <label for="academic_period_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Periode Akademik</label>
                            <select name="academic_period_id" id="academic_period_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                                required>
                                @foreach($academicPeriods as $period)
                                    <option value="{{ $period->id }}" {{ $a->academic_period_id == $period->id ? 'selected' : '' }}>
                                        {{ $period->tahun_ajaran }} - {{ $period->semester }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b-xl dark:border-slate-700">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Perbarui
                    </button>
                    <button type="button" onclick="closeModal('edit-alternatif-{{ $a->id }}')"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-500 dark:hover:text-white dark:hover:bg-slate-600 dark:focus:ring-slate-600">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endrole

<script>
// Modal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('backdrop-blur-sm')) {
        const modals = document.querySelectorAll('.backdrop-blur-sm');
        modals.forEach(modal => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    }
}
</script>

@endsection