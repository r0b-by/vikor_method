@extends('dashboard.layouts.dashboardmain')
@section('title', 'Hitung')

@section('content')

<div class="container mx-auto p-6 bg-white dark:bg-slate-900 shadow rounded-lg" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="300" data-aos-offset="0">

    {{-- Tabs Navigation --}}
    <ul class="flex flex-wrap -mx-3 mb-6 text-sm font-medium text-center text-gray-500 dark:text-gray-400">
        <li class="px-3">
            <a href="#matriks" class="tab-link inline-block px-4 py-2 rounded-t-lg border-b-2 border-blue-600 text-blue-600 font-semibold hover:text-blue-700">
                Matriks Keputusan
            </a>
        </li>
        <li class="px-3">
            <a href="#normalisasi1" class="tab-link inline-block px-4 py-2 rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-blue-600">
                Normalisasi Matriks Keputusan
            </a>
        </li>
        <li class="px-3">
            <a href="#normalisasi2" class="tab-link inline-block px-4 py-2 rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-blue-600">
                Normalisasi Perkalian Matriks
            </a>
        </li>
        <li class="px-3">
            <a href="#ideal" class="tab-link inline-block px-4 py-2 rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-blue-600">
                Selisih Nilai Ideal
            </a>
        </li>
        <li class="px-3">
            <a href="#utility" class="tab-link inline-block px-4 py-2 rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-blue-600">
                Nilai Utility S dan R
            </a>
        </li>
        <li class="px-3">
            <a href="#qi" class="tab-link inline-block px-4 py-2 rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-blue-600">
                Nilai Kompromi Qi
            </a>
        </li>
    </ul>

    {{-- Tab Content --}}
    <div class="flex flex-wrap -mx-3">
        <div id="matriks" class="tab-content w-full px-3">
            @includeIf('dashboard.hitung.matriks')
        </div>
        <div id="normalisasi1" class="tab-content hidden w-full px-3">
            @includeIf('dashboard.hitung.normalisasi')
        </div>
        <div id="normalisasi2" class="tab-content hidden w-full px-3">
            @includeIf('dashboard.hitung.normalisasiterbobot')
        </div>
        <div id="ideal" class="tab-content hidden w-full px-3">
            @includeIf('dashboard.hitung.selisihideal')
        </div>
        <div id="utility" class="tab-content hidden w-full px-3">
            @includeIf('dashboard.hitung.utility')
        </div>
        <div id="qi" class="tab-content hidden w-full px-3">
            @includeIf('dashboard.hitung.kompromi')
        </div>
    </div>
</div>

{{-- Tab Switcher Script --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const links = document.querySelectorAll(".tab-link");
        const tabs = document.querySelectorAll(".tab-content");

        links.forEach(link => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const targetId = this.getAttribute("href").substring(1);

                tabs.forEach(tab => tab.classList.add("hidden"));

                const targetTab = document.getElementById(targetId);
                if (targetTab) {
                    targetTab.classList.remove("hidden");
                }

                links.forEach(l => l.classList.remove("border-blue-600", "text-blue-600", "font-semibold"));
                this.classList.add("border-blue-600", "text-blue-600", "font-semibold");
            });
        });
    });
</script>
@endsection
