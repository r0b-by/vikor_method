<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Alternatif;
use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenilaianController extends Controller
{
    /**
     * Konstruktor untuk menerapkan middleware otorisasi.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|guru'])->except(['indexForStudent', 'storeOrUpdateForStudent']);
        $this->middleware(['auth', 'role:siswa'])->only(['indexForStudent', 'storeOrUpdateForStudent']);
    }

    /**
     * Display a listing of the resource for admin/guru.
     */
    public function index()
    {
        $alternatifs = Alternatif::with('user')->get();
        $criterias = Criteria::all();
        $penilaians = Penilaian::with(['criteria', 'alternatif.user'])->get();
        
        return view('dashboard.penilaian', compact('criterias', 'alternatifs', 'penilaians'));
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
        $penilaians = Penilaian::where('id_alternatif', $alternatif->id)
                        ->get()
                        ->keyBy('id_criteria');

        return view('siswa.penilaian.index', compact('criterias', 'penilaians', 'alternatifs'));
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
            'certificate_count' => 'sometimes|array'
        ]);

        DB::beginTransaction();
        try {
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

                Penilaian::updateOrCreate(
                    [
                        'id_alternatif' => $validated['id_alternatif'],
                        'id_criteria' => $criteriaId
                    ],
                    [
                        'nilai' => $nilai,
                        'certificate_details' => !empty($certificateDetails) ? $certificateDetails : null
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
            'certificate_count' => 'sometimes|array'
        ]);

        DB::beginTransaction();
        try {
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

                Penilaian::updateOrCreate(
                    [
                        'id_alternatif' => $alternatif->id,
                        'id_criteria' => $criteriaId
                    ],
                    [
                        'nilai' => $nilai,
                        'certificate_details' => !empty($certificateDetails) ? $certificateDetails : null
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