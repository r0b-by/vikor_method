<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penilaian;
use App\Models\Alternatif;
use App\Models\Criteria;
use App\Models\AcademicPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Pastikan ini diimpor
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Illuminate\Support\Facades\Validator; // Tambahkan ini

class PenilaianController extends Controller
{
    /**
     * Constructor to apply authorization middleware.
     * Only 'admin' and 'guru' can access methods in this controller.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|guru'])->except(['indexForStudent', 'storeOrUpdateForStudent']);
        $this->middleware(['auth', 'role:siswa'])->only(['indexForStudent', 'storeOrUpdateForStudent']);
    }

    /**
     * Display a listing of the resource for admin/guru.
     * Menambahkan filter periode akademik.
     */
    public function index(Request $request)
    {
        $criterias = Criteria::all();

        // Ambil semua periode akademik yang tersedia untuk dropdown filter
        $academicPeriods = AcademicPeriod::orderBy('tahun_ajaran', 'desc')->orderBy('semester')->get();

        // Tentukan periode akademik yang dipilih dari request, atau ambil yang paling baru sebagai default
        $selectedAcademicPeriodId = $request->input('academic_period_id');
        $selectedAcademicPeriod = null;

        if ($selectedAcademicPeriodId) {
            $selectedAcademicPeriod = AcademicPeriod::find($selectedAcademicPeriodId);
        }

        // Jika tidak ada filter yang dipilih atau periode tidak ditemukan, coba ambil periode aktif atau periode terakhir
        if (!$selectedAcademicPeriod) {
            $activePeriod = AcademicPeriod::where('is_active', true)->first();
            if ($activePeriod) {
                $selectedAcademicPeriod = $activePeriod;
                $selectedAcademicPeriodId = $activePeriod->id;
            } elseif ($academicPeriods->isNotEmpty()) {
                $selectedAcademicPeriod = $academicPeriods->first();
                $selectedAcademicPeriodId = $academicPeriods->first()->id;
            }
        }

        // Selalu ambil semua alternatif untuk form input, terlepas dari apakah mereka sudah punya penilaian atau belum.
        $alternatifs = Alternatif::with('user')->orderByRaw('LENGTH(alternatif_code), alternatif_code')->get();

        // Query penilaian berdasarkan filter yang dipilih
        // Ini akan mengambil semua penilaian untuk periode yang dipilih
        $queryPenilaians = Penilaian::with(['criteria', 'alternatif.user', 'academicPeriod']);

        if ($selectedAcademicPeriodId) {
            $queryPenilaians->where('academic_period_id', $selectedAcademicPeriodId);
        }

        $penilaians = $queryPenilaians
                            ->orderBy('tanggal_penilaian', 'desc')
                            ->orderBy('jam_penilaian', 'desc')
                            ->get();

        // Siapkan koleksi penilaian terbaru untuk pre-fill form input
        // Mengelompokkan berdasarkan kunci komposit (id_alternatif_id_criteria)
        // dan kemudian mengambil item pertama (terbaru karena sudah diurutkan) dari setiap grup.
        $latestPenilaiansForSelectedPeriod = new \Illuminate\Support\Collection();
        if ($penilaians->isNotEmpty()) {
            $latestPenilaiansForSelectedPeriod = $penilaians->groupBy(function($item) {
                                                                return $item->id_alternatif . '_' . $item->id_criteria;
                                                            })
                                                            ->map(function ($group) {
                                                                return $group->first(); // Ambil penilaian terbaru dari grup
                                                            });
        }

        // Kelompokkan semua penilaian yang diambil (yang sudah difilter berdasarkan periode)
        // untuk tampilan rekam jejak (riwayat)
        $groupedPenilaians = $penilaians->groupBy(function($item) {
            // Menggunakan nama alternatif (siswa) agar unik per siswa untuk riwayat
            $alternatifName = $item->alternatif->user->name ?? $item->alternatif->alternatif_name ?? 'Alternatif Tidak Diketahui';
            $periodInfo = $item->academicPeriod ? $item->academicPeriod->tahun_ajaran . ' ' . $item->academicPeriod->semester : 'Periode Tidak Diketahui';
            return $alternatifName . ' (' . $periodInfo . ' - ' . Carbon::parse($item->tanggal_penilaian)->format('d-m-Y H:i') . ')';
        });

        return view('dashboard.penilaian', compact('criterias', 'alternatifs', 'penilaians', 'groupedPenilaians', 'academicPeriods', 'selectedAcademicPeriodId', 'selectedAcademicPeriod', 'latestPenilaiansForSelectedPeriod'));
    }

    /**
     * Display penilaian form for siswa.
     */
    public function indexForStudent()
    {
        $user = Auth::user();
        $alternatif = $user->alternatif;

        if (!$alternatif) {
            return view('siswa.penilaian.index')->with('error', 'Anda belum memiliki alternatif yang terdaftar.');
        }

        $criterias = Criteria::all();

        // Ambil ID Periode Akademik dari Alternatif siswa
        $academicPeriodForStudent = AcademicPeriod::where('tahun_ajaran', $alternatif->tahun_ajaran)
                                                 ->where('semester', $alternatif->semester)
                                                 ->first();

        if (!$academicPeriodForStudent) {
            return view('siswa.penilaian.index')->with('error', 'Periode akademik untuk alternatif Anda tidak ditemukan.');
        }

        // Ambil penilaian terbaru siswa untuk periode akademik akunnya
        $latestPenilaiansForCurrentPeriod = Penilaian::where('id_alternatif', $alternatif->id)
            ->where('academic_period_id', $academicPeriodForStudent->id) // Filter berdasarkan academic_period_id
            ->orderBy('tanggal_penilaian', 'desc')
            ->orderBy('jam_penilaian', 'desc')
            ->get()
            ->keyBy('id_criteria'); // Untuk kemudahan akses di form

        // Ambil semua penilaian untuk siswa ini untuk rekam jejak (riwayat)
        $allPenilaiansForStudent = Penilaian::where('id_alternatif', $alternatif->id)
            ->with('academicPeriod') // Load relasi academicPeriod
            ->orderBy('academic_period_id', 'desc') // Urutkan berdasarkan ID periode
            ->orderBy('tanggal_penilaian', 'desc')
            ->orderBy('jam_penilaian', 'desc')
            ->get();

        // Kelompokkan semua penilaian untuk tampilan rekam jejak
        $groupedPenilaians = $allPenilaiansForStudent->groupBy(function($item) {
            $periodInfo = $item->academicPeriod ? $item->academicPeriod->tahun_ajaran . ' ' . $item->academicPeriod->semester : 'Periode Tidak Diketahui';
            return $periodInfo . ' - ' . Carbon::parse($item->tanggal_penilaian)->format('d-m-Y H:i');
        });

        // Data tahun ajaran dan semester saat ini dari akun siswa (untuk tampilan)
        $currentTahunAjaran = $alternatif->tahun_ajaran;
        $currentSemester = $alternatif->semester;

        return view('siswa.penilaian.index', compact(
            'criterias',
            'alternatif',
            'latestPenilaiansForCurrentPeriod', // Penilaian terbaru untuk form input
            'allPenilaiansForStudent',          // Semua penilaian untuk riwayat
            'groupedPenilaians',                // Penilaian yang dikelompokkan untuk riwayat
            'currentTahunAjaran',               // Tahun ajaran siswa (dari alternatif)
            'currentSemester',                  // Semester siswa (dari alternatif)
            'academicPeriodForStudent'          // Objek periode akademik lengkap untuk siswa
        ));
    }

    /**
     * Store or update penilaian for admin/guru.
     */
    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'id_alternatif' => 'required|exists:alternatifs,id',
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric|min:0',
            'certificate_level' => 'sometimes|array',
            'certificate_count' => 'sometimes|array',
            'academic_period_id' => 'required|exists:academic_periods,id', // Validasi academic_period_id
        ]);

        DB::beginTransaction();
        try {
            $currentDate = Carbon::now();
            $academicPeriodId = $validated['academic_period_id']; // Ambil ID periode akademik

            foreach ($validated['nilai'] as $criteriaId => $nilai) {
                $certificateDetails = [];

                if (isset($validated['certificate_level'][$criteriaId])) {
                    foreach ($validated['certificate_level'][$criteriaId] as $index => $level) {
                        $count = $validated['certificate_count'][$criteriaId][$index] ?? 1;
                        $certificateDetails[] = [
                            'level' => $level,
                            'count' => $count
                        ];
                    }
                }

                // Menggunakan create untuk membuat rekam jejak (history) penilaian baru
                Penilaian::create(
                    [
                        'id_alternatif' => $validated['id_alternatif'],
                        'id_criteria' => $criteriaId,
                        'nilai' => $nilai,
                        'certificate_details' => !empty($certificateDetails) ? $certificateDetails : null, // Laravel's cast will handle JSON encoding
                        'academic_period_id' => $academicPeriodId, // Simpan academic_period_id
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

    /**
     * Store or update penilaian for siswa.
     */
    public function storeOrUpdateForStudent(Request $request)
    {
        $user = Auth::user();
        $alternatif = $user->alternatif;

        if (!$alternatif) {
            return redirect()->back()->with('error', 'Anda belum memiliki alternatif yang terdaftar.');
        }

        $validated = $request->validate([
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric|min:0',
            'certificate_level' => 'sometimes|array',
            'certificate_count' => 'sometimes|array',
        ]);

        DB::beginTransaction();
        try {
            $currentDate = Carbon::now();

            // Ambil ID Periode Akademik dari Alternatif siswa
            $academicPeriodForStudent = AcademicPeriod::where('tahun_ajaran', $alternatif->tahun_ajaran)
                                                     ->where('semester', $alternatif->semester)
                                                     ->first();

            if (!$academicPeriodForStudent) {
                // Ini seharusnya tidak terjadi jika alur registrasi benar
                throw new \Exception('Periode akademik untuk alternatif siswa tidak ditemukan.');
            }

            foreach ($validated['nilai'] as $criteriaId => $nilai) {
                $certificateDetails = [];

                if (isset($validated['certificate_level'][$criteriaId])) {
                    foreach ($validated['certificate_level'][$criteriaId] as $index => $level) {
                        $count = $validated['certificate_count'][$criteriaId][$index] ?? 1;
                        $certificateDetails[] = [
                            'level' => $level,
                            'count' => $count
                        ];
                    }
                }

                // Menggunakan create untuk membuat rekam jejak (history) penilaian baru
                Penilaian::create(
                    [
                        'id_alternatif' => $alternatif->id,
                        'id_criteria' => $criteriaId,
                        'nilai' => $nilai,
                        'certificate_details' => !empty($certificateDetails) ? $certificateDetails : null, // Laravel's cast will handle JSON encoding
                        'academic_period_id' => $academicPeriodForStudent->id, // Simpan academic_period_id
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penilaian = Penilaian::findOrFail($id);
        $penilaian->delete();

        return redirect()->back()->with('success', 'Penilaian berhasil dihapus!');
    }
}
