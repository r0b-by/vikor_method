<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request; 
use Illuminate\Auth\Events\Registered; 
use Illuminate\Http\JsonResponse; 

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
            'nis' => ['required', 'string', 'max:20', 'unique:users'], 
            'kelas' => ['required', 'string', 'max:255'], 
            'jurusan' => ['required', 'string', 'max:255'], 
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
            'nis' => $data['nis'],
            'kelas' => $data['kelas'],
            'jurusan' => $data['jurusan'],
            'alamat' => $data['alamat'],
            'status' => 'pending', 
        ]);

        // Tetapkan peran 'siswa' secara default
        $user->assignRole('siswa'); 

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
                        : redirect('/registration-pending'); 
        }

        // lanjutkan ke redirect default RegistersUsers (ke /home atau dashboard)
        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect($this->redirectPath());
    }
}