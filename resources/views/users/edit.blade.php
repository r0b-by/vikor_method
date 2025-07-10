@extends('dashboard.layouts.dashboardmain')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8 bg-gradient-to-br from-gray-900 to-gray-800 shadow-2xl rounded-xl border border-gray-700/50">
    <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500 mb-6">Edit Pengguna</h2>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="bg-gradient-to-r from-emerald-600/30 to-teal-700/30 border border-emerald-500/50 text-emerald-100 px-4 py-3 rounded-lg mb-4 backdrop-blur-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <strong class="font-bold">Sukses!</strong>
                <span class="block sm:inline ml-1">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.users.update', ['user' => $user]) }}" method="POST">
    @csrf
    @method('PUT')

        {{-- ID --}}
        <div class="mb-5">
            <label for="id" class="block text-sm font-medium text-gray-300">ID Pengguna</label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" id="id" value="{{ $user->id }}"
                       class="pl-10 block w-full rounded-lg bg-gray-800/50 border border-gray-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 text-gray-200 shadow-sm transition duration-200"
                       readonly disabled>
            </div>
        </div>

        {{-- Nama --}}
        <div class="mb-5">
            <label for="name" class="block text-sm font-medium text-gray-300">Nama</label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                       class="pl-10 block w-full rounded-lg bg-gray-800/50 border border-gray-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 text-gray-200 shadow-sm transition duration-200"
                       required>
            </div>
            @error('name')
                <p class="mt-1 text-xs text-rose-400 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </div>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                       class="pl-10 block w-full rounded-lg bg-gray-800/50 border border-gray-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 text-gray-200 shadow-sm transition duration-200"
                       required>
            </div>
            @error('email')
                <p class="mt-1 text-xs text-rose-400 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Student Info Section --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
            {{-- NIS --}}
            <div>
                <label for="nis" class="block text-sm font-medium text-gray-300">NIS</label>
                <input type="text" name="nis" id="nis" value="{{ old('nis', $user->nis) }}"
                       class="mt-1 block w-full rounded-lg bg-gray-800/50 border border-gray-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 text-gray-200 shadow-sm transition duration-200">
                @error('nis')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kelas --}}
            <div>
                <label for="kelas" class="block text-sm font-medium text-gray-300">Kelas</label>
                <input type="text" name="kelas" id="kelas" value="{{ old('kelas', $user->kelas) }}"
                       class="mt-1 block w-full rounded-lg bg-gray-800/50 border border-gray-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 text-gray-200 shadow-sm transition duration-200">
                @error('kelas')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jurusan --}}
            <div>
                <label for="jurusan" class="block text-sm font-medium text-gray-300">Jurusan</label>
                <input type="text" name="jurusan" id="jurusan" value="{{ old('jurusan', $user->jurusan) }}"
                       class="mt-1 block w-full rounded-lg bg-gray-800/50 border border-gray-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 text-gray-200 shadow-sm transition duration-200">
                @error('jurusan')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Alamat --}}
        <div class="mb-5">
            <label for="alamat" class="block text-sm font-medium text-gray-300">Alamat</label>
            <textarea name="alamat" id="alamat" rows="3"
                      class="mt-1 block w-full rounded-lg bg-gray-800/50 border border-gray-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 text-gray-200 shadow-sm transition duration-200">{{ old('alamat', $user->alamat) }}</textarea>
            @error('alamat')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status --}}
        <div class="mb-5">
            <label for="status" class="block text-sm font-medium text-gray-300">Status</label>
            <div class="mt-1 relative">
                <select name="status" id="status"
                        class="appearance-none block w-full rounded-lg bg-gray-800/50 border border-gray-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 text-gray-200 shadow-sm transition duration-200 pl-3 pr-10 py-2"
                        required>
                    <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }} class="bg-gray-800">Aktif</option>
                    <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }} class="bg-gray-800">Menunggu Konfirmasi</option>
                    <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }} class="bg-gray-800">Nonaktif</option>
                    <option value="rejected" {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }} class="bg-gray-800">Ditolak</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            @error('status')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Peran (Roles) --}}
        <div class="mb-5">
            <label for="roles" class="block text-sm font-medium text-gray-300">Peran (Roles)</label>
            <select name="roles[]" id="roles"
                    class="mt-1 block w-full rounded-lg bg-gray-800/50 border border-gray-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 text-gray-200 shadow-sm transition duration-200"
                    multiple>
                @foreach(Spatie\Permission\Models\Role::all() as $role)
                    <option value="{{ $role->name }}"
                            {{ in_array($role->name, old('roles', $user->getRoleNames()->toArray())) ? 'selected' : '' }}
                            class="bg-gray-800">
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
            <p class="mt-2 text-xs text-gray-400">Pilih satu atau lebih peran untuk pengguna ini. Gunakan CTRL/CMD + klik untuk memilih beberapa peran.</p>
            @error('roles')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-5">
            <label for="password" class="block text-sm font-medium text-gray-300">Password Baru (opsional)</label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="password" name="password" id="password"
                       class="pl-10 block w-full rounded-lg bg-gray-800/50 border border-gray-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 text-gray-200 shadow-sm transition duration-200">
            </div>
            <p class="mt-2 text-xs text-gray-400">Biarkan kosong jika tidak ingin mengubah password.</p>
            @error('password')
                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- User Metadata Section --}}
        <div class="bg-gray-800/30 border border-gray-700 rounded-xl p-5 mb-6">
            <h3 class="text-lg font-medium text-gray-300 mb-4">Informasi Sistem</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Email Verified At --}}
                <div>
                    <label class="block text-sm font-medium text-gray-400">Email Diverifikasi Pada</label>
                    <div class="mt-1 px-3 py-2 bg-gray-800/50 border border-gray-700 rounded-lg text-gray-300">
                        {{ $user->email_verified_at ? \Carbon\Carbon::parse($user->email_verified_at)->format('d M Y, H:i') : 'Belum Diverifikasi' }}
                    </div>
                </div>

                {{-- Created At --}}
                <div>
                    <label class="block text-sm font-medium text-gray-400">Dibuat Pada</label>
                    <div class="mt-1 px-3 py-2 bg-gray-800/50 border border-gray-700 rounded-lg text-gray-300">
                        {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y, H:i') }}
                    </div>
                </div>

                {{-- Updated At --}}
                <div>
                    <label class="block text-sm font-medium text-gray-400">Terakhir Diperbarui</label>
                    <div class="mt-1 px-3 py-2 bg-gray-800/50 border border-gray-700 rounded-lg text-gray-300">
                        {{ \Carbon\Carbon::parse($user->updated_at)->format('d M Y, H:i') }}
                    </div>
                </div>

                {{-- Approved By --}}
                <div>
                    <label class="block text-sm font-medium text-gray-400">Disetujui Oleh</label>
                    <div class="mt-1 px-3 py-2 bg-gray-800/50 border border-gray-700 rounded-lg text-gray-300">
                        {{ $user->approved_by ? $user->approved_by : 'Belum Disetujui' }}
                    </div>
                </div>

                {{-- Approved At --}}
                <div>
                    <label class="block text-sm font-medium text-gray-400">Disetujui Pada</label>
                    <div class="mt-1 px-3 py-2 bg-gray-800/50 border border-gray-700 rounded-lg text-gray-300">
                        {{ $user->approved_at ? \Carbon\Carbon::parse($user->approved_at)->format('d M Y, H:i') : 'Belum Disetujui' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 border border-transparent rounded-lg
                           font-semibold text-sm text-white uppercase tracking-wider
                           hover:from-cyan-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                           focus:ring-cyan-500/50 ring-offset-gray-900 shadow-lg transform hover:scale-105 transition-all
                           duration-200 ease-in-out">
                Update Pengguna
            </button>
        </div>
    </form>
</div>
@endsection