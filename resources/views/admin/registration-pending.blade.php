@extends('layouts.app') {{-- Sesuaikan dengan layout utama aplikasi Anda --}}

@section('content')
{{-- Container utama untuk halaman, memastikan konten berada di tengah dan responsif --}}
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    {{-- Kartu utama yang menampung pesan, dengan shadow, rounded corners, dan padding --}}
    <div class="max-w-xl w-full mx-auto bg-dark dark:bg-gray-800 shadow-2xl rounded-xl p-8 sm:p-10 space-y-8 transform transition-all duration-300 hover:scale-105">
        {{-- Judul halaman --}}
        <h2 class="text-4xl font-extrabold text-center text-gray-900 dark:text-white leading-tight">
            Pendaftaran Anda Sedang Diproses
        </h2>

        {{-- Paragraf informasi utama --}}
        <p class="text-gray-700 dark:text-gray-200 text-center text-lg sm:text-xl leading-relaxed">
            Terima kasih telah mendaftar. Akun Anda saat ini berstatus <span class="font-bold text-yellow-600 dark:text-yellow-400 text-xl">'pending'</span>.
            Anda akan dapat login dan mengakses dashboard setelah pendaftaran Anda disetujui oleh administrator.
        </p>

        {{-- Paragraf instruksi tambahan --}}
        <p class="text-gray-700 dark:text-gray-200 text-center text-lg sm:text-xl leading-relaxed">
            Mohon tunggu konfirmasi dari pihak administrator. Anda akan menerima notifikasi setelah akun Anda aktif.
        </p>

        {{-- Bagian tombol, menggunakan flexbox untuk responsivitas --}}
        <div class="bg-dark flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4 mt-8">
            {{-- Tombol Kembali ke Halaman Login --}}
            <a href="{{ route('login') }}" class="
                inline-flex items-center justify-center
                px-8 py-4 border border-transparent
                text-base font-medium rounded-lg shadow-lg
                text-white bg-indigo-600
                hover:bg-indigo-700 hover:shadow-xl
                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                transition duration-300 ease-in-out transform hover:-translate-y-1
            ">
                Kembali ke Halaman Login
            </a>

            {{-- Tombol Kembali ke Halaman Utama --}}
            <a href="{{ url('/') }}" class="
                inline-flex items-center justify-center
                px-8 py-4 border border-gray-300 dark:border-slate-600
                text-base font-medium rounded-lg shadow-lg
                text-gray-700 dark:text-gray-200
                bg-danger dark:bg-slate-700
                hover:bg-gray-50 dark:hover:bg-slate-600 hover:shadow-xl
                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                transition duration-300 ease-in-out transform hover:-translate-y-1
            ">
                Kembali ke Halaman Utama
            </a>
        </div>
    </div>
</div>
@endsection
