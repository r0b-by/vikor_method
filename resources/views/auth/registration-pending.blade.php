@extends('dashboard.layouts.dashboardmain')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Daftar Registrasi Pengguna Tertunda</h1>
    </div>

    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-slate-700 bg-gray-100 dark:bg-slate-700 text-left text-xs font-semibold text-gray-600 dark:text-slate-200 uppercase tracking-wider">
                            Nama
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-slate-700 bg-gray-100 dark:bg-slate-700 text-left text-xs font-semibold text-gray-600 dark:text-slate-200 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-slate-700 bg-gray-100 dark:bg-slate-700 text-left text-xs font-semibold text-gray-600 dark:text-slate-200 uppercase tracking-wider">
                            Tanggal Pendaftaran
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-slate-700 bg-gray-100 dark:bg-slate-700 text-right text-xs font-semibold text-gray-600 dark:text-slate-200 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendingUsers as $user)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm">
                                <p class="text-gray-900 dark:text-slate-100 whitespace-no-wrap">{{ $user->name }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm">
                                <p class="text-gray-900 dark:text-slate-100 whitespace-no-wrap">{{ $user->email }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm">
                                <p class="text-gray-900 dark:text-slate-100 whitespace-no-wrap">
                                    {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y, H:i') }}
                                </p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-right">
                                <div class="flex justify-end space-x-2">
                                    <form action="{{ route('admin.approve-registration', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow-md
                                                       transition duration-300 ease-in-out">
                                            Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reject-registration', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran pengguna ini?');">
                                        @csrf
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg shadow-md
                                                       transition duration-300 ease-in-out">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-5 border-b border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-center text-gray-600 dark:text-slate-300">
                                Tidak ada pendaftaran pengguna tertunda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection