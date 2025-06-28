<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request; // Import Request
use Illuminate\Auth\Events\Registered; // Import Registered event
use Illuminate\Http\JsonResponse; // Import JsonResponse

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     * Overridden by registered() method below for 'pending' status.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // Tambahkan validasi untuk kolom baru
            'nis' => ['required', 'string', 'max:20', 'unique:users'], // Pastikan NIS unik
            'kelas' => ['required', 'string', 'max:50'],
            'jurusan' => ['required', 'string', 'max:50'],
            'alamat' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'pending', // Set status ke 'pending' secara default untuk konfirmasi admin
            'nis' => $data['nis'], // Tambahkan ini
            'kelas' => $data['kelas'], // Tambahkan ini
            'jurusan' => $data['jurusan'], // Tambahkan ini
            'alamat' => $data['alamat'], // Tambahkan ini
        ]);

        // Tetapkan peran 'siswa' secara default
        $user->assignRole('siswa'); // Ini akan menetapkan peran 'siswa' ke pengguna baru

        return $user;
    }

    /**
     * The user has been registered.
     * Override this method to customize redirection for 'pending' users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function registered(Request $request, $user)
    {
        if ($user->status === 'pending') {
            // Logout pengguna yang baru daftar dengan status pending
            $this->guard()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Arahkan ke halaman tunggu konfirmasi
            return $request->wantsJson()
                        ? new JsonResponse([], 201)
                        : redirect('/registration-pending'); // Buat route dan view ini
        }

        // Jika status bukan 'pending' (misalnya admin yang mendaftar atau status default 'active'),
        // lanjutkan ke redirect default RegistersUsers (ke /home atau dashboard)
        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect($this->redirectPath());
    }
}