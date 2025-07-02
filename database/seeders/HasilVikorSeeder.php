<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Criteria; // Import model Criteria
use App\Models\Alternatif; // Import model Alternatif
use App\Models\Penilaian; // Import model Penilaian
use App\Models\AcademicPeriod; // Import model AcademicPeriod
use Carbon\Carbon; // Import Carbon untuk tanggal dan waktu

class HasilVikorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('hasil_vikor')->truncate();
        Schema::enableForeignKeyConstraints();

        // Ambil periode akademik yang aktif atau yang paling baru
        $activePeriod = AcademicPeriod::where('is_active', true)->first();
        if (!$activePeriod) {
            $activePeriod = AcademicPeriod::orderBy('start_date', 'desc')->first();
        }

        if (!$activePeriod) {
            $this->command->warn('Tidak ada periode akademik ditemukan. Hasil VIKOR tidak dapat di-seed.');
            return;
        }

        $tahunAjaran = $activePeriod->tahun_ajaran;
        $semester = $activePeriod->semester;

        $this->command->info("Seeding Hasil Vikor for Tahun Ajaran: {$tahunAjaran}, Semester: {$semester}");

        // Ambil data kriteria
        $criterias = Criteria::all();

        // Ambil alternatif yang relevan dengan periode akademik
        $alternatifs = Alternatif::where('tahun_ajaran', $tahunAjaran)
                                 ->where('semester', $semester)
                                 ->get();

        // Ambil semua penilaian yang terkait dengan alternatif yang sudah difilter
        // Kolom 'tahun_ajaran' dan 'semester' telah dihapus dari tabel 'penilaians',
        // jadi filter berdasarkan kolom tersebut di sini akan menyebabkan error.
        // Filter periode sudah dilakukan pada model Alternatif.
        $penilaians = Penilaian::whereIn('id_alternatif', $alternatifs->pluck('id'))
                               ->get();

        // Pastikan ada data untuk perhitungan
        if ($criterias->isEmpty()) {
            $this->command->warn('Tidak ada kriteria ditemukan. Pastikan Anda memiliki data di tabel `criterias`. Hasil VIKOR tidak dapat di-seed.');
            return;
        }

        if ($alternatifs->isEmpty()) {
            $this->command->warn("Tidak ada alternatif ditemukan untuk Tahun Ajaran: {$tahunAjaran}, Semester: {$semester}. Pastikan Anda memiliki data di tabel `alternatifs` yang terkait dengan periode ini.");
            return;
        }

        if ($penilaians->isEmpty()) {
            $this->command->warn("Tidak ada penilaian ditemukan untuk alternatif di Tahun Ajaran: {$tahunAjaran}, Semester: {$semester}. Pastikan Anda memiliki data di tabel `penilaians` yang terkait dengan alternatif di periode ini.");
            return;
        }

        // Hitung jumlah penilaian yang diharapkan untuk setiap alternatif
        $expectedPenilaianCount = $alternatifs->count() * $criterias->count();

        // Periksa apakah jumlah penilaian sudah lengkap
        // Ini adalah pemeriksaan penting untuk memastikan perhitungan VIKOR valid
        if ($penilaians->count() < $expectedPenilaianCount) {
             $this->command->warn("Jumlah penilaian tidak lengkap untuk Tahun Ajaran: {$tahunAjaran}, Semester: {$semester}. Ditemukan " . $penilaians->count() . " penilaian, tetapi diharapkan " . $expectedPenilaianCount . ". Mungkin ada alternatif yang belum dinilai untuk semua kriteria.");
             return; // Hentikan jika data tidak lengkap untuk mencegah potensi error dalam langkah perhitungan
        }


        $matrix = [];
        foreach ($alternatifs as $alt) {
            foreach ($criterias as $c) {
                // Pastikan untuk mengambil nilai dari penilaian yang sesuai
                $nilai = $penilaians->firstWhere(fn($p) => $p->id_alternatif == $alt->id && $p->id_criteria == $c->id);
                $matrix[$alt->id][$c->id] = $nilai->nilai ?? 0; // Default ke 0 jika tidak ada nilai
            }
        }

        // Step 1: Normalisasi
        $normalisasi = [];
        foreach ($criterias as $c) {
            // Pastikan max dan min dihitung dari nilai yang ada di matriks
            $columnValues = array_column($matrix, $c->id);
            // Pastikan array tidak kosong sebelum memanggil max/min
            if (empty($columnValues)) {
                $max = 0;
                $min = 0;
            } else {
                $max = max($columnValues);
                $min = min($columnValues);
            }


            foreach ($alternatifs as $a) {
                if ($max == $min) {
                    $normalisasi[$a->id][$c->id] = 0; // Hindari divide by zero
                } else {
                    if ($c->criteria_type === 'benefit') {
                        $normalisasi[$a->id][$c->id] = ($matrix[$a->id][$c->id] - $min) / ($max - $min);
                    } else { // cost
                        $normalisasi[$a->id][$c->id] = ($max - $matrix[$a->id][$c->id]) / ($max - $min);
                    }
                }
            }
        }

        // Step 2: Normalisasi Terbobot
        $terbobot = [];
        foreach ($alternatifs as $a) {
            foreach ($criterias as $c) {
                $terbobot[$a->id][$c->id] = $normalisasi[$a->id][$c->id] * $c->weight;
            }
        }

        // Step 3: Hitung S dan R
        $Si = []; $Ri = [];
        foreach ($alternatifs as $a) {
            $s = 0;
            $r = 0;
            foreach ($criterias as $c) {
                $value = $terbobot[$a->id][$c->id];
                $s += $value;
                $r = max($r, $value);
            }
            $Si[$a->id] = $s;
            $Ri[$a->id] = $r;
        }

        // Step 4: Hitung Q
        // Pastikan Si dan Ri tidak kosong sebelum memanggil min/max
        if (empty($Si) || empty($Ri)) {
            $this->command->warn('Nilai S atau R kosong, tidak dapat menghitung Q. Pastikan data penilaian lengkap.');
            return;
        }

        $Smin = min($Si);
        $Smax = max($Si);
        $Rmin = min($Ri);
        $Rmax = max($Ri);
        $v = 0.5; // Nilai v (strategi mayoritas)

        $Qi = [];
        foreach ($alternatifs as $a) {
            // Hindari divide by zero jika Smax == Smin atau Rmax == Rmin
            $s_term = ($Smax == $Smin) ? 0 : ($Si[$a->id] - $Smin) / ($Smax - $Smin);
            $r_term = ($Rmax == $Rmin) ? 0 : ($Ri[$a->id] - $Rmin) / ($Rmax - $Rmin);
            $Qi[$a->id] = round($v * $s_term + (1 - $v) * $r_term, 5);
        }

        // Step 5: Ranking
        asort($Qi); // Qi kecil = lebih baik
        $ranking = 1;
        $resultsToInsert = [];
        $currentDateTime = Carbon::now();

        foreach ($Qi as $id => $q) {
            $status = ($ranking <= 10) ? 'Lulus' : 'Tidak Lulus'; // Kriteria kelulusan (misal: top 10)
            $resultsToInsert[] = [
                'id_alternatif' => $id,
                'nilai_s' => $Si[$id],
                'nilai_r' => $Ri[$id],
                'nilai_q' => $q,
                'ranking' => $ranking++,
                'status' => $status,
                'tahun_ajaran' => $tahunAjaran, // Tambahkan tahun ajaran
                'semester' => $semester,       // Tambahkan semester
                'tanggal_penilaian' => $currentDateTime->toDateString(), // Tanggal saat seeding
                'jam_penilaian' => $currentDateTime->toTimeString(),     // Jam saat seeding
                'created_at' => $currentDateTime,
                'updated_at' => $currentDateTime,
            ];
        }

        DB::table('hasil_vikor')->insert($resultsToInsert);
        $this->command->info('Hasil VIKOR berhasil di-seed untuk periode ' . $tahunAjaran . ' ' . $semester . '.');
    }
}
