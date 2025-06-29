@extends('dashboard.layouts.dashboardmain')
@section('title', 'User')
@section('content')
    <div class="flex flex-wrap mt-6 -mx-3"  data-aos="fade-zoom-in"
     data-aos-easing="ease-in-back"
     data-aos-delay="300"
     data-aos-offset="0">
        <div class="w-full max-w-full px-3 flex-0">
            <div
                class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6">
                    <h5 class="mb-0 dark:text-white">Manajemen Pengguna</h5>
                    <p class="mb-0 text-sm leading-normal">Kelola data pengguna aplikasi.</p>
                </div>
                <div class="table-responsive p-6 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" datatable id="datatable-search">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left uppercase text-xxs text-slate-400 opacity-70">Nama</th>
                                <th class="px-6 py-3 font-bold text-left uppercase text-xxs text-slate-400 opacity-70">Email</th>
                                <th class="px-6 py-3 font-bold text-left uppercase text-xxs text-slate-400 opacity-70">Role</th>
                                <th class="px-6 py-3 font-bold text-center uppercase text-xxs text-slate-400 opacity-70">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Contoh data placeholder. Sesuaikan dengan data pengguna dari database Anda --}}
                            <tr>
                                <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">John Doe</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">john.doe@example.com</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">Admin</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent text-center">
                                    <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Edit</button>
                                    <button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Hapus</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">Jane Smith</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">jane.smith@example.com</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">Guru</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent text-center">
                                    <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Edit</button>
                                    <button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Hapus</button>
                                </td>
                            </tr>
                            {{-- ... tambahkan lebih banyak baris sesuai kebutuhan --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
