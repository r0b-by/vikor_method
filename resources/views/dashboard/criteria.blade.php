@extends('dashboard.layouts.dashboardmain')

@section('title', 'Manajemen Kriteria')

@section('content')
<div class="container mx-auto p-4 text-gray-100">
    <!-- Header dan Informasi Bobot -->
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Data Kriteria</h1>
            <button onclick="openModal('add-criteria')" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                + Tambah Kriteria
            </button>
        </div>
        
        <!-- Panel Informasi Bobot -->
        <div class="bg-gray-800 rounded-lg p-4 mb-4 border border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-700 p-3 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-400">Total Bobot</h3>
                    <p class="text-xl font-bold">{{ number_format($totalWeight, 2) }}/1.0</p>
                </div>
                <div class="bg-gray-700 p-3 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-400">Sisa Bobot</h3>
                    <p class="text-xl font-bold">{{ number_format($remainingWeight, 2) }}</p>
                </div>
                <div class="bg-gray-700 p-3 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-400">Jumlah Kriteria</h3>
                    <p class="text-xl font-bold">{{ count($criteria) }}</p>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="flex justify-between text-sm mb-1">
                    <span>0%</span>
                    <span>100%</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full" 
                         style="width: {{ $totalWeight * 100 }}%"></div>
                </div>
            </div>
            
            @if($remainingWeight <= 0)
                <div class="mt-4 p-3 bg-yellow-900 text-yellow-200 rounded-lg">
                    Total bobot sudah mencapai 1.0. Tidak bisa menambah kriteria baru.
                </div>
            @else
                <div class="mt-4 p-3 bg-blue-900 text-blue-200 rounded-lg">
                    Anda masih bisa menambahkan kriteria dengan total bobot maksimal {{ number_format($remainingWeight, 2) }}
                </div>
            @endif
        </div>
    </div>
    
    <!-- Tabel Kriteria -->
    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left">No</th>
                    <th class="px-6 py-3 text-left">Kode</th>
                    <th class="px-6 py-3 text-left">Nama Kriteria</th>
                    <th class="px-6 py-3 text-left">Jenis</th>
                    <th class="px-6 py-3 text-left">Bobot</th>
                    <th class="px-6 py-3 text-left">Tipe Input</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach($criteria as $item)
                <tr class="hover:bg-gray-700 transition-colors">
                    <td class="px-6 py-4 text-center">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">{{ $item->criteria_code }}</td>
                    <td class="px-6 py-4">{{ $item->criteria_name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs 
                              {{ $item->criteria_type == 'Benefit' ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                            {{ $item->criteria_type }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ number_format($item->weight, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs bg-gray-600">
                            {{ $item->input_type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <form action="{{ route('criteria.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Yakin ingin menghapus kriteria ini? Semua sub-kriteria terkait juga akan dihapus.')"
                                    class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Kriteria -->
    <div id="add-criteria" class="fixed inset-0 bg-black bg-opacity-70 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-gray-800 rounded-lg w-full max-w-md border border-gray-700">
            <div class="flex justify-between items-center border-b border-gray-700 p-4">
                <h2 class="text-lg font-bold">Tambah Kriteria Baru</h2>
                <button onclick="closeModal('add-criteria')" 
                        class="text-gray-400 hover:text-white text-xl">
                    &times;
                </button>
            </div>
            <form action="{{ route('criteria.store') }}" method="POST" class="p-4">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium">Nomor Kriteria*</label>
                        <input type="number" name="no" value="{{ old('no') }}" required
                            class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        @error('no')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Kode Kriteria*</label>
                        <input type="text" name="criteria_code" value="{{ old('criteria_code') }}" required
                               class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        @error('criteria_code')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Nama Kriteria*</label>
                        <input type="text" name="criteria_name" value="{{ old('criteria_name') }}" required
                               class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        @error('criteria_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Bobot (0-1)*</label>
                        <input type="number" name="weight" step="0.01" min="0" max="1" 
                               value="{{ old('weight') }}" required
                               class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        @error('weight')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Jenis Kriteria*</label>
                        <select name="criteria_type" 
                                class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="Benefit" {{ old('criteria_type') == 'Benefit' ? 'selected' : '' }}>Benefit</option>
                            <option value="Cost" {{ old('criteria_type') == 'Cost' ? 'selected' : '' }}>Cost</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Tipe Input*</label>
                        <select name="input_type" id="add-input-type" 
                                class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="manual" {{ old('input_type') == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="poin" {{ old('input_type') == 'poin' ? 'selected' : '' }}>Poin (Sub-Kriteria)</option>
                        </select>
                    </div>
                    
                    <!-- Sub-kriteria container -->
                    <div id="add-sub-criteria-section" class="{{ old('input_type') == 'poin' ? '' : 'hidden' }}">
                        <label class="block mb-2 text-sm font-medium">Sub-Kriteria</label>
                        <div id="add-sub-criteria-list" class="space-y-2 mb-2">
                            @if(old('input_type') == 'poin')
                                @foreach(old('subs', [[]]) as $index => $sub)
                                <div class="flex items-center gap-2">
                                    <input type="text" name="subs[{{ $index }}][label]" 
                                           value="{{ $sub['label'] ?? '' }}"
                                           class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg" required>
                                    <input type="number" name="subs[{{ $index }}][point]" 
                                           value="{{ $sub['point'] ?? '' }}" min="1"
                                           class="w-20 p-2 bg-gray-700 border border-gray-600 rounded-lg" required>
                                    <button type="button" onclick="this.parentElement.remove()"
                                            class="text-red-500 hover:text-red-400 text-xl">
                                        &times;
                                    </button>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addSubCriteria('add')"
                                class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            + Tambah Sub-Kriteria
                        </button>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeModal('add-criteria')"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk membuka modal
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        // Panggil toggleSubCriteria saat modal dibuka
        if (id === 'add-criteria') {
            const inputType = document.getElementById('add-input-type');
            toggleSubCriteria('add', '', inputType.value);
        } else if (id.startsWith('edit-criteria-')) {
            const itemId = id.replace('edit-criteria-', '');
            const inputType = document.getElementById(`edit-input-type-${itemId}`);
            toggleSubCriteria('edit', itemId, inputType.value);
        }
    }

    // Fungsi untuk menutup modal
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Fungsi untuk toggle sub-kriteria
    function toggleSubCriteria(prefix, id = '', currentValue = null) {
        const inputType = currentValue || 
            document.getElementById(`${prefix}-input-type${id ? '-' + id : ''}`).value;
        const subCriteriaSection = document.getElementById(
            `${prefix}-sub-criteria-section${id ? '-' + id : ''}`
        );

        if (inputType === 'poin') {
            subCriteriaSection.classList.remove('hidden');
            // Tambahkan sub-kriteria otomatis jika belum ada
            const list = document.getElementById(`${prefix}-sub-criteria-list${id ? '-' + id : ''}`);
            if (list && list.children.length === 0) {
                addSubCriteria(prefix, id);
            }
        } else {
            if (subCriteriaSection) {
                subCriteriaSection.classList.add('hidden');
            }
        }
    }

    // Fungsi untuk menambah sub-kriteria
    function addSubCriteria(prefix, id = '') {
        const list = document.getElementById(`${prefix}-sub-criteria-list${id ? '-' + id : ''}`);
        const index = list.children.length;

        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
            <input type="text" name="subs[${index}][label]" placeholder="Label" 
                   class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg" required>
            <input type="number" name="subs[${index}][point]" placeholder="Poin" min="1"
                   class="w-20 p-2 bg-gray-700 border border-gray-600 rounded-lg" required>
            <button type="button" onclick="this.parentElement.remove()"
                    class="text-red-500 hover:text-red-400 text-xl">
                &times;
            </button>
        `;

        list.appendChild(div);
    }

    // Event listener untuk dropdown
    document.addEventListener('DOMContentLoaded', function() {
        // Untuk modal add
        const addInputType = document.getElementById('add-input-type');
        if (addInputType) {
            addInputType.addEventListener('change', function() {
                toggleSubCriteria('add', '', this.value);
            });
        }

        // Untuk modal edit (dinamis)
        document.querySelectorAll('[id^="edit-input-type-"]').forEach(select => {
            select.addEventListener('change', function() {
                const id = this.id.replace('edit-input-type-', '');
                toggleSubCriteria('edit', id, this.value);
            });
        });
    });

    // Tutup modal ketika klik di luar
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('bg-opacity-70')) {
            const modals = document.querySelectorAll('[id^="edit-criteria-"], #add-criteria');
            modals.forEach(modal => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
        }
    });
</script>
@endsection