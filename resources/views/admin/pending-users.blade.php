@extends('dashboard.layouts.dashboardmain')
@section('title', 'Konfirmasi Pendaftaran Pengguna')

@section('content')
<div class="flex flex-wrap -mx-3" data-aos="fade-zoom-in"
     data-aos-easing="ease-in-back"
     data-aos-delay="300"
     data-aos-offset="0">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl xl:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-600">
            User Registration Request
        </h2>
    </div>
    <div class="flex-none w-full max-w-full px-3 overflow-x-hidden">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-gradient-to-br from-gray-800 to-gray-900 border-0 border-transparent border-solid shadow-2xl rounded-2xl bg-clip-border">
            <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-blue-400">Pendaftaran Pengguna Menunggu Konfirmasi</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-6 overflow-x-auto">
                    {{-- Session messages for success or error --}}
                    @if (session('success'))
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-4 py-3 rounded-lg mb-4 shadow-lg" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-gradient-to-r from-rose-500 to-pink-600 text-white px-4 py-3 rounded-lg mb-4 shadow-lg" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <table class="items-center w-full mb-0 align-top border-collapse border-gray-700 text-slate-300">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-700 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap opacity-80">
                                    No
                                </th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-700 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap opacity-80">
                                    Nama Pengguna
                                </th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-700 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap opacity-80">
                                    Email
                                </th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-700 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap opacity-80">
                                    Peran Default
                                </th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-700 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap opacity-80">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendingUsers as $user)
                                <tr class="hover:bg-gray-700/50 transition-colors duration-200">
                                    <td class="p-4 align-middle bg-transparent border-b border-gray-700 whitespace-nowrap shadow-transparent">
                                        <span class="text-sm font-semibold leading-tight text-white opacity-90">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle bg-transparent border-b border-gray-700 whitespace-nowrap shadow-transparent">
                                        <span class="text-sm font-semibold leading-tight text-white opacity-90">
                                            {{ $user->name }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle bg-transparent border-b border-gray-700 whitespace-nowrap shadow-transparent">
                                        <span class="text-sm font-semibold leading-tight text-white opacity-90">
                                            {{ $user->email }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle bg-transparent border-b border-gray-700 whitespace-nowrap shadow-transparent">
                                        <span class="text-sm font-semibold leading-tight text-white opacity-90">
                                            {{ $user->getRoleNames()->first() ?: 'Tidak Ada' }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle bg-transparent border-b border-gray-700 whitespace-nowrap shadow-transparent text-center">
                                        <div class="flex justify-center space-x-2">
                                            {{-- Form to approve registration --}}
                                            <form action="{{ route('admin.users.approve-registration', $user->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg hover:from-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-opacity-50 shadow-lg transform hover:scale-105 transition-all duration-200">
                                                    Konfirmasi
                                                </button>
                                            </form>
                                            {{-- Form to reject registration --}}
                                            <form action="{{ route('admin.users.reject-registration', $user->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-rose-500 to-pink-600 rounded-lg hover:from-rose-600 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-opacity-50 shadow-lg transform hover:scale-105 transition-all duration-200">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-sm text-gray-400">Tidak ada pendaftaran yang menunggu konfirmasi.</td>
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