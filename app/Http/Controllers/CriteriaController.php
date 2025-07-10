<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\CriteriaSub; // Pastikan ini ada
use Illuminate\Http\Request;
use App\Http\Requests\CriteriaRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Untuk debugging via log jika diperlukan
use Illuminate\Support\Facades\DB;   // Untuk transaksi database

class CriteriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): Factory|View
    {
        $criteria = Criteria::with('subs')
            ->orderByRaw('LENGTH(criteria_code), criteria_code')
            ->get();
            
        $totalWeight = $criteria->sum('weight');
        $remainingWeight = 1.0 - $totalWeight;
        
        return view('dashboard.criteria', compact('criteria', 'totalWeight', 'remainingWeight'));
    }

    public function store(CriteriaRequest $request): RedirectResponse
    {
        $this->authorize('criteria-create', Criteria::class);
        
        $data = $request->validated();
        
        // PENTING: Tambahkan created_by untuk record baru
        $data['created_by'] = Auth::id(); 
        
        // updated_by akan null saat pertama kali dibuat, ini sesuai
        $data['updated_by'] = null; 

        $totalWeight = Criteria::sum('weight') + $data['weight'];
        if ($totalWeight > 1.0) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'weight' => "Total bobot akan menjadi {$totalWeight}. Maksimal 1.0"
                ]);
        }

        // Gunakan transaksi untuk memastikan semua operasi tersimpan
        try {
            DB::beginTransaction(); // Mulai transaksi

            $newCriteria = Criteria::create($data); 

            if ($data['input_type'] === 'poin' && $request->has('subs')) {
                // $newCriteria seharusnya memiliki ID valid di sini
                $this->createSubCriteria($newCriteria, $request->subs); 
            }

            DB::commit(); // Commit transaksi jika berhasil

            return redirect()->back()
                ->with('success', 'Kriteria berhasil ditambahkan!')
                ->with('total_weight', $totalWeight);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika terjadi error
            Log::error("Error storing criteria: " . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['message' => 'Terjadi kesalahan saat menambahkan kriteria. Silakan coba lagi.']);
        }
    }

    public function edit(Criteria $criteria): Factory|View
    {
        $this->authorize('update', $criteria);
        
        $criteria->load('subs'); // Load relasi subs untuk form edit
        
        return view('dashboard.criteria.edit', compact('criteria'));
    }

    public function update(CriteriaRequest $request, Criteria $criteria): RedirectResponse
    {
        $this->authorize('update', $criteria);
        
        $data = $request->validated();
        
        // Tambahkan ID user yang sedang login sebagai updated_by
        $data['updated_by'] = Auth::id(); 

        // --- PERBAIKAN PENTING DI SINI ---
        // 1. Dapatkan total bobot semua kriteria KECUALI kriteria yang sedang diedit
        $sumOfOtherCriteriaWeights = Criteria::where('id', '!=', $criteria->id)->sum('weight');
        
        // 2. Hitung total bobot baru dengan menambahkan bobot baru dari kriteria yang sedang diedit
        $newWeight = $data['weight'];
        $totalWeight = $sumOfOtherCriteriaWeights + $newWeight; 
        // --- AKHIR PERBAIKAN ---

        // (Opsional) Tambahkan log untuk debugging, bisa dihapus setelah fix
        Log::info("Update Criteria (ID: {$criteria->id}): Original weight: {$criteria->weight}, New proposed weight: {$newWeight}");
        Log::info("Update Criteria (ID: {$criteria->id}): Sum of OTHER criteria weights: {$sumOfOtherCriteriaWeights}");
        Log::info("Update Criteria (ID: {$criteria->id}): Calculated NEW total weight: {$totalWeight}");
        
        if ($totalWeight > 1.0) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'weight' => "Total bobot akan menjadi " . number_format($totalWeight, 2) . ". Maksimal 1.0"
                ]);
        }

        // Simpan ID kriteria asli untuk referensi yang pasti
        $originalCriteriaId = $criteria->id;

        // Validasi jika ID null (seharusnya tidak terjadi dengan Route Model Binding yang berhasil)
        if (is_null($originalCriteriaId)) {
            Log::error("Update method: Original Criteria ID is NULL for URI: " . $request->fullUrl());
            return redirect()->back()
                ->withErrors(['message' => 'Internal error: Could not identify criteria for update.']);
        }

        // Gunakan transaksi untuk memastikan semua operasi tersimpan
        try {
            DB::beginTransaction(); // Mulai transaksi

            $criteria->update($data); // Lakukan update pada objek $criteria

            // Re-fetch model secara eksplisit untuk memastikan mendapatkan state terbaru dari DB
            // Ini adalah tindakan defensif yang kuat
            $freshCriteria = Criteria::findOrFail($originalCriteriaId); 

            if ($data['input_type'] === 'poin') {
                $freshCriteria->subs()->delete(); // Hapus semua sub-kriteria lama
                if ($request->has('subs')) {
                    $this->createSubCriteria($freshCriteria, $request->subs); // Buat sub-kriteria baru
                }
            } else {
                // Jika input_type diubah ke non-poin, hapus semua sub-kriteria yang mungkin ada
                $freshCriteria->subs()->delete();
            }

            DB::commit(); // Commit transaksi jika berhasil

            return redirect()->back()
                ->with('success', 'Kriteria berhasil diperbarui!')
                ->with('total_weight', $totalWeight);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi
            Log::error("Error updating criteria: " . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['message' => 'Terjadi kesalahan saat memperbarui kriteria. Silakan coba lagi.']);
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        $criteria = Criteria::findOrFail($id);
        $this->authorize('delete', $criteria);

        try {
            DB::beginTransaction(); // Gunakan transaksi juga untuk delete

            $criteria->delete();

            DB::commit(); // Commit transaksi jika berhasil

            return redirect()->back()
                ->with('success', 'Kriteria berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi
            Log::error("Error deleting criteria: " . $e->getMessage());
            return redirect()->back()
                ->withErrors(['message' => 'Terjadi kesalahan saat menghapus kriteria. Silakan coba lagi.']);
        }
    }

    protected function createSubCriteria(Criteria $criteria, array $subs): void
    {
        foreach ($subs as $sub) {
            // Pastikan $criteria->id tidak null di sini (harusnya sudah dijamin oleh controller)
            $criteria->subs()->create([
                'label' => $sub['label'] ?? null,
                'point' => $sub['point'] ?? 0,
                // Tambahkan ini sebagai jaminan ekstra, meskipun relasi harusnya mengisi otomatis
                'criteria_id' => $criteria->id, 
            ]);
        }
    }
}