<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penilaian;
use App\Models\Alternatif;
use App\Models\Criteria;
use App\Models\AcademicPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User; // Pastikan model User diimpor

class PenilaianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|guru'])->except(['indexForStudent', 'storeOrUpdateForStudent']);
        $this->middleware(['auth', 'role:siswa'])->only(['indexForStudent', 'storeOrUpdateForStudent']);
    }

    public function index(Request $request)
    {
        // Hanya ambil periode akademik yang aktif
        $academicPeriods = AcademicPeriod::where('is_active', true)
            ->orderBy('tahun_ajaran', 'desc')
            ->orderBy('semester')
            ->get();

        // Handle selected academic period
        $selectedAcademicPeriod = $request->input('academic_period_id') 
            ? AcademicPeriod::where('is_active', true)->find($request->input('academic_period_id'))
            : AcademicPeriod::where('is_active', true)->first();

        // Jika tidak ada periode aktif sama sekali
        if (!$selectedAcademicPeriod) {
            return view('dashboard.penilaian', [
                'criterias' => Criteria::with('subs')->get(),
                'alternatifs' => collect(),
                'penilaians' => collect(),
                'groupedPenilaians' => collect(),
                'academicPeriods' => collect(),
                'selectedAcademicPeriod' => null,
                'latestPenilaiansForSelectedPeriod' => collect(),
            ])->with('warning', 'Tidak ada periode akademik yang aktif.');
        }

        // Load criteria with their subs
        $criterias = Criteria::with('subs')->get();
        
        // Get alternatives for the selected active period
        $alternatifs = Alternatif::with('user')
            ->where('tahun_ajaran', $selectedAcademicPeriod->tahun_ajaran)
            ->where('semester', $selectedAcademicPeriod->semester)
            ->orderByRaw('LENGTH(alternatif_code), alternatif_code')
            ->get();

        // Query assessments for the selected active period
        $penilaians = Penilaian::with(['criteria', 'alternatif.user', 'academicPeriod'])
            ->where('academic_period_id', $selectedAcademicPeriod->id)
            ->orderBy('tanggal_penilaian', 'desc')
            ->orderBy('jam_penilaian', 'desc')
            ->get();

        // Get latest assessments for pre-fill
        $latestPenilaiansForSelectedPeriod = $penilaians->isNotEmpty()
            ? $penilaians->groupBy(fn($item) => $item->id_alternatif . '_' . $item->id_criteria)
                ->map->first()
            : collect();

        // Group assessments for history view
        $groupedPenilaians = $penilaians->groupBy(function($item) {
            $alternatifName = $item->alternatif->user->name ?? $item->alternatif->alternatif_name ?? 'Unknown';
            $periodInfo = $item->academicPeriod 
                ? $item->academicPeriod->tahun_ajaran . ' ' . $item->academicPeriod->semester 
                : 'Unknown Period';
            
            return $alternatifName . ' (' . $periodInfo . ' - ' . 
                   Carbon::parse($item->tanggal_penilaian)->format('d-m-Y') . ' ' . 
                   Carbon::parse($item->jam_penilaian)->format('H:i') . ')';
        });

        return view('dashboard.penilaian', compact(
            'criterias', 
            'alternatifs', 
            'penilaians', 
            'groupedPenilaians',
            'academicPeriods', 
            'selectedAcademicPeriod', 
            'latestPenilaiansForSelectedPeriod'
        ));
    }

    public function indexForStudent()
    {
        $user = Auth::user();
        $alternatif = $user->alternatif;

        if (!$alternatif) {
            return view('siswa.penilaian.index')->with('error', 'Anda belum memiliki alternatif yang terdaftar.');
        }

        $criterias = Criteria::with('subs')->get();

        $academicPeriodForStudent = AcademicPeriod::where('tahun_ajaran', $alternatif->tahun_ajaran)
            ->where('semester', $alternatif->semester)
            ->firstOrFail();

        // Get latest assessments for current period with proper date casting
        $latestPenilaiansForCurrentPeriod = Penilaian::where('id_alternatif', $alternatif->id)
            ->where('academic_period_id', $academicPeriodForStudent->id)
            ->select('*')
            ->selectRaw('CAST(tanggal_penilaian AS DATE) as tanggal_penilaian')
            ->selectRaw('CAST(jam_penilaian AS TIME) as jam_penilaian')
            ->orderBy('tanggal_penilaian', 'desc')
            ->orderBy('jam_penilaian', 'desc')
            ->get()
            ->keyBy('id_criteria');

        // Get all assessments for history
        $allPenilaiansForStudent = Penilaian::where('id_alternatif', $alternatif->id)
            ->with('academicPeriod')
            ->select('*')
            ->selectRaw('CAST(tanggal_penilaian AS DATE) as tanggal_penilaian')
            ->selectRaw('CAST(jam_penilaian AS TIME) as jam_penilaian')
            ->orderBy('academic_period_id', 'desc')
            ->orderBy('tanggal_penilaian', 'desc')
            ->orderBy('jam_penilaian', 'desc')
            ->get();

        $groupedPenilaians = $allPenilaiansForStudent->groupBy(function($item) {
            $periodInfo = $item->academicPeriod 
                ? $item->academicPeriod->tahun_ajaran . ' ' . $item->academicPeriod->semester 
                : 'Unknown Period';
            
            $tanggal = Carbon::parse($item->tanggal_penilaian);
            $jam = Carbon::parse($item->jam_penilaian);
            
            return $periodInfo . ' - ' . $tanggal->format('d-m-Y') . ' ' . $jam->format('H:i');
        });

        return view('siswa.penilaian.index', compact(
            'criterias', 'alternatif', 'latestPenilaiansForCurrentPeriod',
            'allPenilaiansForStudent', 'groupedPenilaians', 'academicPeriodForStudent'
        ));
    }

    public function storeOrUpdateForStudent(Request $request)
    {
        // Add these lines for debugging the 403 error
        // dd(Auth::check(), Auth::user() ? Auth::user()->roles->pluck('name')->toArray() : 'No user logged in');

        $user = Auth::user();
        $alternatif = $user->alternatif;

        if (!$alternatif) {
            return back()->with('error', 'Anda belum memiliki alternatif yang terdaftar.');
        }

        $validated = $request->validate([
            'id_alternatif' => 'required|exists:alternatifs,id',
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric|min:0',
            'certificate_level' => 'sometimes|array',
            'certificate_level.*' => 'sometimes|array',
            'certificate_level.*.*' => 'nullable|string',

            'certificate_count' => 'sometimes|array',
            'certificate_count.*' => 'sometimes|array',
            'certificate_count.*.*' => 'nullable|integer|min:1',

            'academic_period_id' => 'required|exists:academic_periods,id,is_active,1',
        ]);

        DB::beginTransaction();
        try {
            $currentDate = Carbon::now();
            $alternatif = Alternatif::findOrFail($validated['id_alternatif']);

            $academicPeriod = AcademicPeriod::findOrFail($validated['academic_period_id']);
            if ($alternatif->tahun_ajaran !== $academicPeriod->tahun_ajaran ||
                $alternatif->semester !== $academicPeriod->semester) {
                throw new \Exception('Alternatif tidak sesuai dengan periode akademik yang dipilih.');
            }

            foreach ($validated['nilai'] as $criteriaId => $nilaiInput) {
                $criteria = Criteria::with('subs')->findOrFail($criteriaId);
                $nilai = 0;
                $certificateDetails = null;

                if ($criteria->input_type === 'poin') {
                    $certificateDetails = $this->processPointBasedCriteria(
                        $criteria,
                        $validated,
                        $criteriaId,
                        $nilai
                    );
                } else {
                    $nilai = $nilaiInput;
                }

                Penilaian::updateOrCreate(
                    [
                        'id_alternatif' => $alternatif->id,
                        'id_criteria' => $criteriaId,
                        'academic_period_id' => $academicPeriod->id
                    ],
                    [
                        'nilai' => $nilai,
                        'certificate_details' => $certificateDetails,
                        'tanggal_penilaian' => $currentDate->toDateString(),
                        'jam_penilaian' => $currentDate->toTimeString(),
                    ]
                );
            }

            DB::commit();
            return redirect()->back()->with('success', 'Penilaian berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage());
        }
    }

    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'id_alternatif' => 'required|exists:alternatifs,id',
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric|min:0',
            // Validasi untuk certificate_level dan certificate_count perlu diperbaiki agar lebih spesifik
            // Misalnya: 'certificate_level.*.*' => 'nullable|string',
            // Karena ini adalah array bertingkat (criteriaId => index => value)
            'certificate_level' => 'sometimes|array',
            'certificate_level.*' => 'sometimes|array', // Array level per kriteria
            'certificate_level.*.*' => 'nullable|string', // Level sertifikat itu sendiri (misal: 'Basic', 'Intermediate')

            'certificate_count' => 'sometimes|array',
            'certificate_count.*' => 'sometimes|array', // Array count per kriteria
            'certificate_count.*.*' => 'nullable|integer|min:1', // Jumlah sertifikat

            'academic_period_id' => 'required|exists:academic_periods,id,is_active,1', // Hanya boleh periode aktif
        ]);

        DB::beginTransaction();
        try {
            $currentDate = Carbon::now();
            $alternatif = Alternatif::findOrFail($validated['id_alternatif']);
            
            // Pastikan alternatif sesuai dengan periode akademik yang dipilih
            $academicPeriod = AcademicPeriod::findOrFail($validated['academic_period_id']);
            if ($alternatif->tahun_ajaran !== $academicPeriod->tahun_ajaran || 
                $alternatif->semester !== $academicPeriod->semester) {
                throw new \Exception('Alternatif tidak sesuai dengan periode akademik yang dipilih.');
            }

            foreach ($validated['nilai'] as $criteriaId => $nilaiInput) {
                $criteria = Criteria::with('subs')->findOrFail($criteriaId);
                $nilai = 0;
                $certificateDetails = null; // Inisialisasi sebagai null

                if ($criteria->input_type === 'poin') { // Hanya masuk ke sini jika tipe input adalah 'poin'
                    // Panggil helper function untuk memproses kriteria berbasis poin
                    // Pastikan $validated['certificate_level'][$criteriaId] ada sebelum memanggil
                    // processPointBasedCriteria jika itu hanya dipanggil untuk kriteria_id tertentu
                    $certificateDetails = $this->processPointBasedCriteria(
                        $criteria, 
                        $validated, 
                        $criteriaId, 
                        $nilai // $nilai akan dimodifikasi di dalam fungsi ini
                    );
                } else { // Jika input_type bukan 'poin' (misal 'manual')
                    $nilai = $nilaiInput;
                }

                // Update or create penilaian
                Penilaian::updateOrCreate(
                    [
                        'id_alternatif' => $alternatif->id,
                        'id_criteria' => $criteriaId,
                        'academic_period_id' => $academicPeriod->id
                    ],
                    [
                        'nilai' => $nilai,
                        // HAPUS json_encode() karena model Penilaian memiliki $casts = ['certificate_details' => 'array']
                        // Laravel akan secara otomatis mengkonversi array PHP ke JSON string saat menyimpan
                        'certificate_details' => $certificateDetails, 
                        'tanggal_penilaian' => $currentDate->toDateString(),
                        'jam_penilaian' => $currentDate->toTimeString(),
                    ]
                );
            }

            DB::commit();
            return redirect()->back()->with('success', 'Penilaian berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $penilaian = Penilaian::findOrFail($id);
        $penilaian->delete();
        return redirect()->back()->with('success', 'Penilaian berhasil dihapus!');
    }

    // Metode processAssessment dan validateRequest yang terpisah dari storeOrUpdate
    // tidak digunakan dalam alur storeOrUpdate yang sekarang.
    // Jika tidak digunakan di tempat lain, Anda bisa mempertimbangkan untuk menghapusnya
    // agar kode lebih bersih, atau memastikan mereka dipanggil di tempat yang benar.

    // protected function validateRequest(Request $request)
    // {
    //     return $request->validate([
    //         'id_alternatif' => 'sometimes|required|exists:alternatifs,id',
    //         'nilai' => 'required|array',
    //         'nilai.*' => 'required|numeric|min:0',
    //         'certificate_level' => 'sometimes|array',
    //         'certificate_count' => 'sometimes|array',
    //         'academic_period_id' => 'sometimes|required|exists:academic_periods,id',
    //     ]);
    // }

    // protected function processAssessment(array $validated, $alternatifId)
    // {
    //     $currentDate = Carbon::now();
    //     $academicPeriodId = $validated['academic_period_id'] ?? null;

    //     foreach ($validated['nilai'] as $criteriaId => $nilaiInput) {
    //         $criteria = Criteria::with('subs')->findOrFail($criteriaId);
    //         $nilai = 0;
    //         $certificateDetails = null;

    //         if ($criteria->input_type === 'manual') {
    //             $nilai = $nilaiInput;
    //         } elseif ($criteria->input_type === 'poin') {
    //             $certificateDetails = $this->processPointBasedCriteria(
    //                 $criteria, 
    //                 $validated, 
    //                 $criteriaId, 
    //                 $nilai
    //             );
    //         }

    //         Penilaian::create([
    //             'id_alternatif' => $alternatifId,
    //             'id_criteria' => $criteriaId,
    //             'nilai' => $nilai,
    //             'certificate_details' => $certificateDetails,
    //             'academic_period_id' => $academicPeriodId,
    //             'tanggal_penilaian' => $currentDate->toDateString(),
    //             'jam_penilaian' => $currentDate->toTimeString(),
    //         ]);
    //     }
    // }

    protected function processPointBasedCriteria($criteria, $validated, $criteriaId, &$nilai)
    {
        $certificateDetails = [];
        
        // Memastikan ada data certificate_level untuk criteriaId ini
        if (isset($validated['certificate_level'][$criteriaId]) && is_array($validated['certificate_level'][$criteriaId])) {
            foreach ($validated['certificate_level'][$criteriaId] as $index => $level) {
                // Pastikan certificate_count juga ada untuk index yang sama
                $count = $validated['certificate_count'][$criteriaId][$index] ?? 1;
                $sub = $criteria->subs->firstWhere('label', $level);
                $point = $sub ? $sub->point : 0;
                $nilai += $point * $count;

                $certificateDetails[] = [
                    'level' => $level,
                    'count' => $count,
                    'point' => $point,
                    'sub_total' => $point * $count
                ];
            }
        }
        
        // Mengembalikan array kosong jika tidak ada detail sertifikat, bukan null.
        // Ini lebih konsisten dengan tipe 'array' di $casts dan menghindari null string di DB.
        return !empty($certificateDetails) ? $certificateDetails : []; 
    }
}