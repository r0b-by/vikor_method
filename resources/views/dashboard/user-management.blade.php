@extends('dashboard.layouts.dashboardmain')
@section('title', 'User Management')

@section('content')
    <div class="container mx-auto p-6 bg-white dark:bg-slate-900 shadow rounded-lg">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">User Management</h1>
        <p class="text-gray-700 dark:text-gray-300">This is the user management page. You can list, add, edit, and delete users here.</p>

        @isset($users)
            <div class="overflow-x-auto"> {{-- Add this div for horizontal scrolling on small screens --}}
                <table class="min-w-full bg-white dark:bg-gray-800">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left">ID</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left">Name</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left">Email</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left">NIS</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left">Kelas</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left">Jurusan</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left">Alamat</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left">Status</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left">Role</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left">Email Verified At</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->id }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->name }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->email }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->nis }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->kelas }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->jurusan }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->alamat }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->status }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->getRoleNames()->implode(', ') }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">
                                <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                {{-- Add delete button with form if needed --}}
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 ml-2">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-700 dark:text-gray-300">No users found.</p>
        @endisset
    </div>
@endsection