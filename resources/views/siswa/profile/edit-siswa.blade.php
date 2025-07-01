@extends('dashboard.layouts.dashboardmain')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-6">Edit Profil Saya</h2>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block mb-2">Nama Lengkap</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                   class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block mb-2">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                   class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white" required>
        </div>

        <div class="mb-4">
            <label for="nis" class="block mb-2">NIS</label>
            <input type="text" name="nis" id="nis" value="{{ old('nis', $user->nis) }}" 
                   class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">
        </div>

        <div class="mb-4">
            <label for="kelas" class="block mb-2">Kelas</label>
            <input type="text" name="kelas" id="kelas" value="{{ old('kelas', $user->kelas) }}" 
                   class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">
        </div>

        <div class="mb-4">
            <label for="jurusan" class="block mb-2">Jurusan</label>
            <input type="text" name="jurusan" id="jurusan" value="{{ old('jurusan', $user->jurusan) }}" 
                   class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">
        </div>

        <div class="mb-4">
            <label for="alamat" class="block mb-2">Alamat</label>
            <textarea name="alamat" id="alamat" rows="3" 
                      class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">{{ old('alamat', $user->alamat) }}</textarea>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection