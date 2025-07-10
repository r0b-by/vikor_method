<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alternatif;
use App\Models\AcademicPeriod;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Ke mana redirect setelah registrasi (jika tidak pending)
     */
    protected $redirectTo = '/dashboard'; // Default jika tidak pending

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Tampilkan form registrasi dengan data academicPeriod
     */
    public function showRegistrationForm()
    {
        $academicPeriods = AcademicPeriod::where('is_active', true)->get();
        return view('auth.register', compact('academicPeriods'));
    }

    /**
     * Validasi data pendaftaran
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
            'academic_period_combined' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $parts = explode('|', $value);
                    if (count($parts) !== 2) {
                        return $fail('Format periode akademik tidak valid.');
                    }
                    [$tahunAjaran, $semester] = $parts;
                    $exists = AcademicPeriod::where('tahun_ajaran', $tahunAjaran)
                        ->where('semester', $semester)
                        ->where('is_active', true)
                        ->exists();
                    if (!$exists) {
                        $fail('Periode akademik yang dipilih tidak valid atau tidak aktif.');
                    }
                },
            ],
        ]);
    }

    /**
     * Membuat user baru + alternatif
     */
    protected function create(array $data)
    {
        [$tahunAjaran, $semester] = explode('|', $data['academic_period_combined']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'nis' => $data['nis'],
            'kelas' => $data['kelas'],
            'jurusan' => $data['jurusan'],
            'alamat' => $data['alamat'],
            'status' => 'pending',
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
        ]);

        Alternatif::create([
            'user_id' => $user->id,
            'alternatif_name' => $user->name,
            'alternatif_code' => 'ALT-' . uniqid(),
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
        ]);

        $user->assignRole('siswa');

        return $user;
    }

    /**
     * Override setelah registrasi sukses (tapi status pending)
     */
    protected function registered(Request $request, $user)
    {
        if ($user->status === 'pending') {
            $this->guard()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $request->wantsJson()
                ? new JsonResponse(['message' => 'Akun Anda sedang menunggu persetujuan'], 201)
                : redirect()->route('registration.pending');
        }

        return $request->wantsJson()
            ? new JsonResponse(['message' => 'Registrasi berhasil'], 201)
            : redirect($this->redirectPath());
    }
}
