@extends('dashboard.layouts.dashboardmain')

@section('content')
<div class="container">
    <h2>Profil Saya</h2>
    <div class="card">
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>NIS:</strong> {{ $user->nis ?? '-' }}</p> {{-- Gunakan ?? untuk menangani nilai null --}}
            <p><strong>Kelas:</strong> {{ $user->kelas ?? '-' }}</p>
            <p><strong>Jurusan:</strong> {{ $user->jurusan ?? '-' }}</p>
            <p><strong>Alamat:</strong> {{ $user->alamat ?? '-' }}</p>
            {{-- Anda bisa menambahkan informasi lain jika ada --}}

            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profil</a>
        </div>
    </div>
</div>
@endsection