<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Criteria;
use Illuminate\Support\Facades\Log; // Tetap sertakan Log jika Anda menggunakannya untuk debugging

class CriteriaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pastikan user terautentikasi atau memiliki izin yang sesuai
        // Contoh: return auth()->check();
        return true; // Untuk saat ini, 'true' aman, tapi sebaiknya disesuaikan dengan otorisasi Anda
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // --- PERBAIKAN DI SINI: Ubah 'criterion' menjadi 'criterium' ---
        // Karena Route::resource('criteria', ...) akan menggunakan {criterium} sebagai parameter rute
        dd($this->route('criterium'));
        $criteriaToIgnore = $this->route('criterium'); 

        // Logika untuk mendapatkan ID dari objek Criteria atau langsung dari parameter
        $criteriaIdToIgnore = null;
        if ($criteriaToIgnore instanceof Criteria) {
            $criteriaIdToIgnore = $criteriaToIgnore->id;
        } elseif (is_string($criteriaToIgnore) || is_numeric($criteriaToIgnore)) {
            $criteriaIdToIgnore = $criteriaToIgnore;
        }
        // --- AKHIR PERBAIKAN ---

        Log::info("CriteriaRequest rules: Ignoring criteria ID for unique validation: " . ($criteriaIdToIgnore ?? 'NULL (for new creation)'));
        
        $rules = [
            'criteria_name' => 'required|string|max:255',
            'criteria_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('criterias')->ignore($criteriaIdToIgnore, 'id'),
            ],
            'input_type' => 'required|in:manual,poin',
            'criteria_type' => 'required|in:Benefit,Cost',
            'weight' => 'required|numeric|min:0.01|max:1',
            'no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('criterias')->ignore($criteriaIdToIgnore, 'id'),
            ],
        ];

        if ($this->input_type === 'poin') {
            $rules['subs'] = [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    $points = [];
                    foreach ($value as $index => $sub) {
                        if (isset($sub['point']) && is_numeric($sub['point'])) {
                            if (in_array($sub['point'], $points)) {
                                $fail("Poin Sub-Kriteria (nilai {$sub['point']}) tidak boleh ada yang duplikat dalam daftar yang sama.");
                                return;
                            }
                            $points[] = (int) $sub['point'];
                        }
                    }
                },
            ];
            $rules['subs.*.label'] = 'required|string|max:255';
            $rules['subs.*.point'] = 'required|numeric|min:1';
        }

        return $rules;
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'no.required' => 'Nomor Kriteria wajib diisi.',
            'no.string' => 'Nomor Kriteria harus berupa teks.',
            'no.max' => 'Nomor Kriteria tidak boleh lebih dari :max karakter.',
            'no.unique' => 'Nomor Kriteria sudah ada.',
            
            'criteria_code.required' => 'Kode Kriteria wajib diisi.',
            'criteria_code.string' => 'Kode Kriteria harus berupa teks.',
            'criteria_code.max' => 'Kode Kriteria tidak boleh lebih dari :max karakter.',
            'criteria_code.unique' => 'Kode Kriteria sudah ada.',

            'criteria_name.required' => 'Nama Kriteria wajib diisi.',
            'criteria_name.string' => 'Nama Kriteria harus berupa teks.',
            'criteria_name.max' => 'Nama Kriteria tidak boleh lebih dari :max karakter.',

            'input_type.required' => 'Tipe Input wajib dipilih.',
            'input_type.in' => 'Tipe Input tidak valid.',

            'criteria_type.required' => 'Jenis Kriteria wajib dipilih.',
            'criteria_type.in' => 'Jenis Kriteria tidak valid.',

            'weight.required' => 'Bobot wajib diisi.',
            'weight.numeric' => 'Bobot harus berupa angka.',
            'weight.min' => 'Bobot minimal :min.',
            'weight.max' => 'Bobot maksimal :max.',

            'subs.required' => 'Minimal harus ada satu Sub-Kriteria.',
            'subs.array' => 'Sub-Kriteria harus dalam format yang benar.',
            'subs.min' => 'Minimal harus ada :min Sub-Kriteria.',
            'subs.*.label.required' => 'Label Sub-Kriteria wajib diisi.',
            'subs.*.label.string' => 'Label Sub-Kriteria harus berupa teks.',
            'subs.*.label.max' => 'Label Sub-Kriteria tidak boleh lebih dari :max karakter.',
            'subs.*.point.required' => 'Poin Sub-Kriteria wajib diisi.',
            'subs.*.point.numeric' => 'Poin Sub-Kriteria harus berupa angka.',
            'subs.*.point.min' => 'Poin Sub-Kriteria minimal :min.',
        ];
    }
}