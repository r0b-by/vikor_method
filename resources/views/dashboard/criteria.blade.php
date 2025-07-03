@extends('dashboard.layouts.dashboardmain')

@section('title', 'Manajemen Kriteria')

@section('content')
<div class="container mx-auto p-4 text-gray-100">
    <!-- Header dan Tombol Tambah -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Data Kriteria</h1>
        <button onclick="openModal('add-criteria')" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            + Tambah Kriteria
        </button>
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
                    <td class="px-6 py-4">{{ $item->criteria_type }}</td>
                    <td class="px-6 py-4">{{ $item->weight }}</td>
                    <td class="px-6 py-4">{{ $item->input_type }}</td>
                    <td class="px-6 py-4 flex gap-2">
                        <button onclick="openModal('edit-criteria-{{ $item->id }}')"
                                class="px-3 py-1 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                            Edit
                        </button>
                        <form action="{{ route('criteria.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Yakin ingin menghapus?')"
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
                    <input type="number" name="no" value="" required
                        class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Kode Kriteria*</label>
                        <input type="text" name="criteria_code" required
                               class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Nama Kriteria*</label>
                        <input type="text" name="criteria_name" required
                               class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Bobot (0-1)*</label>
                        <input type="number" name="weight" step="0.01" min="0" max="1" required
                               class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Jenis Kriteria*</label>
                        <select name="criteria_type" 
                                class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="Benefit">Benefit</option>
                            <option value="Cost">Cost</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Tipe Input*</label>
                        <select name="input_type" id="add-input-type" 
                                class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="manual">Manual</option>
                            <option value="poin">Poin (Sub-Kriteria)</option>
                        </select>
                    </div>
                    
                    <!-- Sub-kriteria container -->
                    <div id="add-sub-criteria-section" class="hidden">
                        <label class="block mb-2 text-sm font-medium">Sub-Kriteria</label>
                        <div id="add-sub-criteria-list" class="space-y-2 mb-2"></div>
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

    <!-- Modal Edit Kriteria -->
    @foreach($criteria as $item)
    <div id="edit-criteria-{{ $item->id }}" class="fixed inset-0 bg-black bg-opacity-70 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-gray-800 rounded-lg w-full max-w-md border border-gray-700">
            <div class="flex justify-between items-center border-b border-gray-700 p-4">
                <h2 class="text-lg font-bold">Edit Kriteria</h2>
                <button onclick="closeModal('edit-criteria-{{ $item->id }}')" 
                        class="text-gray-400 hover:text-white text-xl">
                    &times;
                </button>
            </div>
            <form action="{{ route('criteria.update', $item->id) }}" method="POST" class="p-4">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
    <label class="block mb-2 text-sm font-medium">Nomor Kriteria*</label>
    <input type="number" name="no" value="{{ $item->no ?? '' }}" required
           class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
</div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Kode Kriteria*</label>
                        <input type="text" name="criteria_code" value="{{ $item->criteria_code }}" required
                               class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Nama Kriteria*</label>
                        <input type="text" name="criteria_name" value="{{ $item->criteria_name }}" required
                               class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Bobot (0-1)*</label>
                        <input type="number" name="weight" step="0.01" min="0" max="1" 
                               value="{{ $item->weight }}" required
                               class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Jenis Kriteria*</label>
                        <select name="criteria_type" 
                                class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="Benefit" {{ $item->criteria_type == 'Benefit' ? 'selected' : '' }}>Benefit</option>
                            <option value="Cost" {{ $item->criteria_type == 'Cost' ? 'selected' : '' }}>Cost</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Tipe Input*</label>
                        <select name="input_type" id="edit-input-type-{{ $item->id }}"
                                class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="manual" {{ $item->input_type == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="poin" {{ $item->input_type == 'poin' ? 'selected' : '' }}>Poin (Sub-Kriteria)</option>
                        </select>
                    </div>
                    
                    <!-- Sub-kriteria container -->
                    <div id="edit-sub-criteria-section-{{ $item->id }}" class="{{ $item->input_type == 'poin' ? '' : 'hidden' }}">
                        <label class="block mb-2 text-sm font-medium">Sub-Kriteria</label>
                        <div id="edit-sub-criteria-list-{{ $item->id }}" class="space-y-2 mb-2">
                            @if($item->input_type == 'poin')
                                @foreach($item->subs as $index => $sub)
                                <div class="flex items-center gap-2">
                                    <input type="text" name="subs[{{ $index }}][label]" value="{{ $sub->label }}"
                                           class="w-full p-2 bg-gray-700 border border-gray-600 rounded-lg" required>
                                    <input type="number" name="subs[{{ $index }}][point]" value="{{ $sub->point }}" min="1"
                                           class="w-20 p-2 bg-gray-700 border border-gray-600 rounded-lg" required>
                                    <button type="button" onclick="this.parentElement.remove()"
                                            class="text-red-500 hover:text-red-400 text-xl">
                                        &times;
                                    </button>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addSubCriteria('edit', '{{ $item->id }}')"
                                class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            + Tambah Sub-Kriteria
                        </button>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeModal('edit-criteria-{{ $item->id }}')"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
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