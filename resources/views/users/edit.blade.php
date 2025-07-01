@extends('dashboard.layouts.dashboardmain')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8 bg-white dark:bg-slate-800 shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-6">Edit Pengguna</h2>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.users.update', ['user' => $user]) }}" method="POST">
    @csrf
    @method('PUT')

        {{-- ID (Usually read-only) --}}
        <div class="mb-4">
            <label for="id" class="block text-sm font-medium text-gray-700 dark:text-slate-300">ID Pengguna</label>
            <input type="text" id="id" value="{{ $user->id }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-400 cursor-not-allowed"
                   readonly disabled> {{-- ID is typically not editable --}}
        </div>

        {{-- Nama --}}
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Nama</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                          focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50
                          dark:bg-slate-700 dark:border-slate-600 dark:text-slate-100"
                   required>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                          focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50
                          dark:bg-slate-700 dark:border-slate-600 dark:text-slate-100"
                   required>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- NIS --}}
        <div class="mb-4">
            <label for="nis" class="block text-sm font-medium text-gray-700 dark:text-slate-300">NIS</label>
            <input type="text" name="nis" id="nis" value="{{ old('nis', $user->nis) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                          focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50
                          dark:bg-slate-700 dark:border-slate-600 dark:text-slate-100">
            @error('nis')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kelas --}}
        <div class="mb-4">
            <label for="kelas" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Kelas</label>
            <input type="text" name="kelas" id="kelas" value="{{ old('kelas', $user->kelas) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                          focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50
                          dark:bg-slate-700 dark:border-slate-600 dark:text-slate-100">
            @error('kelas')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Jurusan --}}
        <div class="mb-4">
            <label for="jurusan" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Jurusan</label>
            <input type="text" name="jurusan" id="jurusan" value="{{ old('jurusan', $user->jurusan) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                          focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50
                          dark:bg-slate-700 dark:border-slate-600 dark:text-slate-100">
            @error('jurusan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Alamat --}}
        <div class="mb-4">
            <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Alamat</label>
            <textarea name="alamat" id="alamat" rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                             focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50
                             dark:bg-slate-700 dark:border-slate-600 dark:text-slate-100">{{ old('alamat', $user->alamat) }}</textarea>
            @error('alamat')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Status</label>
            <select name="status" id="status"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                           focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50
                           dark:bg-slate-700 dark:border-slate-600 dark:text-slate-100"
                    required>
                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                <option value="rejected" {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            @error('status')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Peran (Roles) --}}
        <div class="mb-6">
            <label for="roles" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Peran (Roles)</label>
            <select name="roles[]" id="roles"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                           focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50
                           dark:bg-slate-700 dark:border-slate-600 dark:text-slate-100"
                    multiple>
                @foreach(Spatie\Permission\Models\Role::all() as $role)
                    <option value="{{ $role->name }}"
                            {{ in_array($role->name, old('roles', $user->getRoleNames()->toArray())) ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
            <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Pilih satu atau lebih peran untuk pengguna ini. Gunakan CTRL/CMD + klik untuk memilih beberapa peran.</p>
            @error('roles')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password (Optional - for changing password) --}}
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Password Baru (opsional)</label>
            <input type="password" name="password" id="password"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                          focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50
                          dark:bg-slate-700 dark:border-slate-600 dark:text-slate-100">
            <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Biarkan kosong jika tidak ingin mengubah password.</p>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email Verified At (Read-only for info) --}}
        <div class="mb-4">
            <label for="email_verified_at" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Email Diverifikasi Pada</label>
            <input type="text" id="email_verified_at" value="{{ $user->email_verified_at ? \Carbon\Carbon::parse($user->email_verified_at)->format('d M Y, H:i') : 'Belum Diverifikasi' }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-400 cursor-not-allowed"
                   readonly disabled>
        </div>

        {{-- Created At (Read-only for info) --}}
        <div class="mb-4">
            <label for="created_at" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Dibuat Pada</label>
            <input type="text" id="created_at" value="{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y, H:i') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-400 cursor-not-allowed"
                   readonly disabled>
        </div>

        {{-- Updated At (Read-only for info) --}}
        <div class="mb-4">
            <label for="updated_at" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Terakhir Diperbarui</label>
            <input type="text" id="updated_at" value="{{ \Carbon\Carbon::parse($user->updated_at)->format('d M Y, H:i') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-400 cursor-not-allowed"
                   readonly disabled>
        </div>

        {{-- Approved By (Read-only for info) --}}
        <div class="mb-4">
            <label for="approved_by" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Disetujui Oleh</label>
            <input type="text" id="approved_by" value="{{ $user->approved_by ? $user->approved_by : 'Belum Disetujui' }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-400 cursor-not-allowed"
                   readonly disabled>
        </div>

        {{-- Approved At (Read-only for info) --}}
        <div class="mb-6">
            <label for="approved_at" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Disetujui Pada</label>
            <input type="text" id="approved_at" value="{{ $user->approved_at ? \Carbon\Carbon::parse($user->approved_at)->format('d M Y, H:i') : 'Belum Disetujui' }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-400 cursor-not-allowed"
                   readonly disabled>
        </div>

        {{-- Tombol Update --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md
                           font-semibold text-sm text-white uppercase tracking-wider
                           hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring
                           ring-blue-300 disabled:opacity-50 transition ease-in-out duration-150">
                Update Pengguna
            </button>
        </div>
    </form>
</div>
@endsection