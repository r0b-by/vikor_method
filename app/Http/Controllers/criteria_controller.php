<?php

namespace App\Http\Controllers;

use App\Models\criteria;
use Illuminate\Http\Request;
use App\Http\Requests\CriteriaRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class criteria_controller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(): Factory|View
    {
        $criteria = criteria::with('subs')->orderByRaw('LENGTH(criteria_code), criteria_code')->get();
        return view('dashboard.criteria', compact('criteria'));
    }

    public function create(): void
    {
        // Metode ini kosong, jika tidak digunakan bisa dihapus
    }

    public function store(CriteriaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $criteria = criteria::create($data);

        // Perbaikan di sini: Menggunakan $request->subs
        if ($data['input_type'] === 'poin' && $request->has('subs')) {
            foreach ($request->subs as $sub) {
                $criteria->subs()->create([
                    'label' => $sub['label'] ?? null,
                    'point' => $sub['point'] ?? 0,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Kriteria berhasil ditambahkan!');
    }

    public function show(criteria $criteria): void
    {
        // Metode ini kosong, jika tidak digunakan bisa dihapus
    }

    public function edit(criteria $criteria): void
    {
        // Metode ini kosong, jika tidak digunakan bisa dihapus
    }

    public function update(CriteriaRequest $request, criteria $criteria): RedirectResponse
    {
        $data = $request->validated();
        $criteria->update($data);

        // Hapus sub-kriteria lama jika input_type poin
        if ($data['input_type'] === 'poin') {
            $criteria->subs()->delete(); // hapus semua dulu

            // Perbaikan di sini: Menggunakan $request->subs
            if ($request->has('subs')) {
                foreach ($request->subs as $sub) {
                    $criteria->subs()->create([
                        'label' => $sub['label'] ?? null,
                        'point' => $sub['point'] ?? 0,
                    ]);
                }
            }
        } else {
            $criteria->subs()->delete(); // jika berubah jadi manual, pastikan semua sub-kriteria dihapus
        }

        return redirect()->back()->with('success', 'Kriteria berhasil diperbarui!');
    }

    public function destroy(string $criteria): RedirectResponse
    {
        criteria::findOrFail($criteria)->delete();
        return redirect()->back()->with('success', 'Kriteria berhasil dihapus!');
    }
}