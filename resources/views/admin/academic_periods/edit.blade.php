@extends('dashboard.layouts.dashboardmain')
@section('title', 'Edit Periode Akademik')

@section('content')
<div class="flex flex-wrap -mx-3" data-aos="fade-zoom-in"
     data-aos-easing="ease-in-back"
     data-aos-delay="300"
     data-aos-offset="0">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">
            Edit Periode Akademik
        </h2>
    </div>
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="dark:text-white">Form Edit Periode Akademik</h6>
            </div>
            <div class="flex-auto p-6">
                <form action="{{ route('admin.academic_periods.update', $academicPeriod->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="{{ old('tahun_ajaran', $academicPeriod->tahun_ajaran) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                        @error('tahun_ajaran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="semester" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Semester</label>
                        <select name="semester" id="semester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Pilih Semester</option>
                            <option value="Ganjil" {{ old('semester', $academicPeriod->semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ old('semester', $academicPeriod->semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                        @error('semester')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $academicPeriod->start_date->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                        @error('start_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $academicPeriod->end_date->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                        @error('end_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tambahkan checkbox is_active untuk edit --}}
                    <div class="mb-4">
                        <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-700 dark:border-gray-600" {{ old('is_active', $academicPeriod->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-200">Aktifkan Periode Ini</label>
                        @error('is_active')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-blue-500 rounded-lg cursor-pointer leading-normal text-xs ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-xs">Update</button>
                        <a href="{{ route('admin.academic_periods.index') }}" class="inline-block px-6 py-3 font-bold text-center text-slate-700 uppercase align-middle transition-all bg-transparent border border-slate-700 rounded-lg cursor-pointer leading-normal text-xs ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-xs ml-2 dark:text-white dark:border-white">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
