<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicPeriodController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']); // Only admins can manage this
    }

    public function index()
    {
        // Ambil data dan simpan ke variabel $academicPeriods agar konsisten dengan compact()
        $academicPeriods = AcademicPeriod::orderBy('tahun_ajaran', 'desc')->orderBy('semester')->get();
        return view('admin.academic_periods.index', compact('academicPeriods'));
    }

    public function create()
    {
        // Perbaikan jalur view: dari dashboard.academic_periods.create menjadi admin.academic_periods.create
        return view('admin.academic_periods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'start_date' => 'required|date', // Tambahkan validasi tanggal
            'end_date' => 'required|date|after_or_equal:start_date', // Tambahkan validasi tanggal
            'is_active' => 'boolean', // Ini opsional, tergantung apakah checkbox selalu ada di form
        ]);

        // Validasi unik untuk kombinasi tahun_ajaran dan semester
        $existingPeriod = AcademicPeriod::where('tahun_ajaran', $request->tahun_ajaran)
                                        ->where('semester', $request->semester)
                                        ->first();
        if ($existingPeriod) {
            return redirect()->back()->withInput()->withErrors(['tahun_ajaran' => 'Kombinasi Tahun Ajaran dan Semester ini sudah ada.']);
        }


        DB::beginTransaction();
        try {
            if ($request->has('is_active') && $request->is_active) {
                // Deactivate all other periods if this one is set to active
                AcademicPeriod::where('is_active', true)->update(['is_active' => false]);
            } else {
                // Jika is_active tidak dicentang atau tidak ada di request, pastikan nilainya false
                $request->merge(['is_active' => false]);
            }

            AcademicPeriod::create($request->all());
            DB::commit();
            return redirect()->route('admin.academic_periods.index')->with('success', 'Academic period added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to add academic period: ' . $e->getMessage());
        }
    }

    public function edit(AcademicPeriod $academicPeriod)
    {
        // Perbaikan jalur view: dari dashboard..academic_periods.edit menjadi admin.academic_periods.edit
        return view('admin.academic_periods.edit', compact('academicPeriod'));
    }

    public function update(Request $request, AcademicPeriod $academicPeriod)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'start_date' => 'required|date', // Tambahkan validasi tanggal
            'end_date' => 'required|date|after_or_equal:start_date', // Tambahkan validasi tanggal
            'is_active' => 'boolean', // Ini opsional, tergantung apakah checkbox selalu ada di form
        ]);

        // Validasi unik untuk kombinasi tahun_ajaran dan semester, kecuali untuk record yang sedang diedit
        $existingPeriod = AcademicPeriod::where('tahun_ajaran', $request->tahun_ajaran)
                                        ->where('semester', $request->semester)
                                        ->where('id', '!=', $academicPeriod->id)
                                        ->first();
        if ($existingPeriod) {
            return redirect()->back()->withInput()->withErrors(['tahun_ajaran' => 'Kombinasi Tahun Ajaran dan Semester ini sudah ada.']);
        }

        DB::beginTransaction();
        try {
            if ($request->has('is_active') && $request->is_active) {
                // Deactivate all other periods if this one is set to active
                AcademicPeriod::where('is_active', true)->where('id', '!=', $academicPeriod->id)->update(['is_active' => false]);
            } else {
                // Jika is_active tidak dicentang atau tidak ada di request, pastikan nilainya false
                $request->merge(['is_active' => false]);
            }

            $academicPeriod->update($request->all());
            DB::commit();
            return redirect()->route('admin.academic_periods.index')->with('success', 'Academic period updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to update academic period: ' . $e->getMessage());
        }
    }

    public function destroy(AcademicPeriod $academicPeriod)
    {
        try {
            $academicPeriod->delete();
            return redirect()->route('admin.academic_periods.index')->with('success', 'Academic period deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete academic period: ' . $e->getMessage());
        }
    }
     public function showRegistrationForm()
     {
         $academicPeriods = AcademicPeriod::where('is_active', true)->get();
         return view('auth.register', compact('academicPeriods'));
     }
}