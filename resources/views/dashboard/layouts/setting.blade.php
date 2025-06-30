@extends('dashboard.layouts.dashboardmain')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Pengaturan Aplikasi</h1>

    <div class="bg-white dark:bg-slate-800 shadow rounded-lg p-6">
        <form action="{{ route('setting.update') }}" method="POST">
            @csrf
            @method('PUT') {{-- Gunakan PUT untuk update --}}

            <div class="mb-4">
                <label for="app_name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Nama Aplikasi:</label>
                <input type="text" id="app_name" name="app_name"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline dark:bg-slate-700 dark:border-slate-600"
                       value="{{ config('app.name') }}"> {{-- Contoh: Mengambil dari config --}}
            </div>

            <div class="mb-6">
                <label for="timezone" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Zona Waktu:</label>
                <input type="text" id="timezone" name="timezone"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline dark:bg-slate-700 dark:border-slate-600"
                       value="{{ config('app.timezone') }}"> {{-- Contoh: Mengambil dari config --}}
            </div>

            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Simpan Pengaturan
            </button>
        </form>
    </div>
</div>
@endsection