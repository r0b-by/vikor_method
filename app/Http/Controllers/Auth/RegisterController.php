<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alternatif;
use App\Models\AcademicPeriod; // Pastikan ini diimport
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
     * Show the application registration form.
     * Overrides the default method from RegistersUsers trait
     * to pass academic periods to the view.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        // Ambil semua periode akademik yang aktif
        $academicPeriods = AcademicPeriod::where('is_active', true)->get();

        // Kirim periode akademik ke view registrasi
        return view('auth.register', compact('academicPeriods'));
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
            'nis' => ['required', 'string', 'max:20', 'unique:users'], // Pastikan panjang NIS sesuai
            'kelas' => ['required', 'string', 'max:255'],
            'jurusan' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'], // Untuk textarea, sesuaikan max:255 jika perlu lebih panjang
            // Validasi untuk dropdown academic_period_combined
            // Memastikan nilai yang dipilih ada di tabel academic_periods dan aktif
            'academic_period_combined' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    list($tahunAjaran, $semester) = explode('|', $value);
                    $exists = AcademicPeriod::where('tahun_ajaran', $tahunAjaran)
                                            ->where('semester', $semester)
                                            ->where('is_active', true) // Hanya izinkan periode aktif
                                            ->exists();
                    if (!$exists) {
                        $fail('Periode akademik yang dipilih tidak valid atau tidak aktif.');
                    }
                },
            ],
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
        // Memecah nilai tahun_ajaran dan semester dari input gabungan
        list($tahunAjaran, $semester) = explode('|', $data['academic_period_combined']);

        // Buat user baru
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'nis' => $data['nis'],
            'kelas' => $data['kelas'],
            'jurusan' => $data['jurusan'],
            'alamat' => $data['alamat'],
            'status' => 'pending', // Set status ke 'pending' secara default
            // Kolom tahun_ajaran dan semester di tabel users (jika masih diperlukan)
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
        ]);

        // Buat record Alternatif untuk user yang baru terdaftar
        Alternatif::create([
            'user_id' => $user->id,
            'alternatif_name' => $data['name'], // Asumsi nama alternatif sama dengan nama user
            'alternatif_code' => 'ALT-' . uniqid(), // Hasilkan kode unik untuk alternatif
            'tahun_ajaran' => $tahunAjaran, // Tetapkan periode akademik yang dipilih ke Alternatif
            'semester' => $semester,       // Tetapkan periode akademik yang dipilih ke Alternatif
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

        // Lanjutkan ke redirect default RegistersUsers (ke /home atau dashboard)
        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect($this->redirectPath());
    }
}
