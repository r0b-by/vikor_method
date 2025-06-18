@extends('dashboard.layouts.dashboardmain')
@section('title', 'Hitung')
@section('content')
    <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Perhitungan VIKOR</h1>

    {{-- Tabs Navigation --}}
<ul class="flex flex-wrap border-b mb-6 text-sm font-medium text-center text-gray-500 dark:text-gray-400">
    <li class="me-2">
        <a href="{{ route('hitung.matriks') }}" class="inline-block px-4 py-2 rounded-t-lg border-b-2 border-blue-600 text-blue-600 font-semibold hover:text-blue-700">
            Matriks Keputusan
        </a>
    </li>
    <li class="me-2">
        <a href="{{ route('hitung.normalisasi') }}" class="inline-block px-4 py-2 rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-blue-600">
            Normalisasi Matriks Keputusan
        </a>
    </li>
    <li class="me-2">
        <a href="{{ route('hitung.normalisasiterbobot') }}" class="inline-block px-4 py-2 rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-blue-600">
            Normalisasi Perkalian Matriks
        </a>
    </li>
    <li class="me-2">
        <a href="{{ route('hitung.selisihideal') }}" class="inline-block px-4 py-2 rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-blue-600">
            Selisih Nilai Ideal
        </a>
    </li>
    <li class="me-2">
        <a href="{{ route('hitung.utility') }}" class="inline-block px-4 py-2 rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-blue-600">
            Nilai Utility S dan R
        </a>
    </li>
    <li class="me-2">
        <a href="{{ route('hitung.kompromi') }}" class="inline-block px-4 py-2 rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-blue-600">
            Nilai Kompromi Qi
        </a>
    </li>
</ul>


    {{-- Tab Content --}}
    <div id="matriks" class="tab-content">
        @include('dashboard.hitung.matriks')
    </div>
    <div id="normalisasi1" class="tab-content hidden">
        @include('dashboard.hitung.normalisasi')
    </div>
    <div id="normalisasi2" class="tab-content hidden">
        @include('dashboard.hitung.normalisasiterbobot')
    </div>
    <div id="ideal" class="tab-content hidden">
        @include('dashboard.hitung.selisihideal')
    </div>
    <div id="utility" class="tab-content hidden">
        @include('dashboard.hitung.utility')
    </div>
    <div id="qi" class="tab-content hidden">
        @include('dashboard.hitung.kompromi')
    </div>
</div>

{{-- Optional: Tab Switcher --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll(".tab-link");
    const tabs = document.querySelectorAll(".tab-content");

    links.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            const targetId = this.getAttribute("href").substring(1); // Ambil ID tanpa #

            // Sembunyikan semua tab
            tabs.forEach(tab => {
                tab.classList.add("hidden");
            });

            // Tampilkan tab yang sesuai
            const targetTab = document.getElementById(targetId);
            if (targetTab) {
                targetTab.classList.remove("hidden");
            }

            // Perbarui kelas aktif di tab-link
            links.forEach(l => l.classList.remove("border-blue-600", "text-blue-600", "font-semibold"));
            this.classList.add("border-blue-600", "text-blue-600", "font-semibold");
        });
    });
});
</script>
    
@endsection
