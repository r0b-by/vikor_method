<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\criteria;

class CriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $bobotSaatIni = 0;
            // Jika ini operasi update dan kriteria ada di route
            if ($this->route('criteria')) {
                $existingCriteria = criteria::find($this->route('criteria')->id);
                if ($existingCriteria) {
                    $bobotSaatIni = $existingCriteria->weight;
                }
            }

            $totalBobot = criteria::sum('weight') - $bobotSaatIni + $this->weight;

            if ($totalBobot > 1) {
                $validator->errors()->add('weight', 'Total bobot semua kriteria tidak boleh melebihi 1');
            }
        });
    }

    public function rules(): array
    {
        $rules = [
            'criteria_name' => 'required|string|max:255',
            'criteria_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('criterias')->ignore($this->route('criteria')), // OK untuk tambah & update
            ],
            'input_type' => 'required|in:manual,poin',
            'criteria_type' => 'required|in:Benefit,Cost', // Perhatikan 'criteria_type' bukan 'type'
            'weight' => 'required|numeric|min:0.01|max:1',
            'no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('criterias')->ignore($this->route('criteria')), // OK untuk tambah & update
            ],
        ];

        if ($this->input_type === 'poin') {
            $rules['subs'] = 'required|array|min:1'; // Subs harus ada dan minimal 1 item
            $rules['subs.*.label'] = 'required|string|max:255';
            $rules['subs.*.point'] = 'required|numeric|min:1'; // Point minimal 1
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'criteria_name.required' => 'Nama Kriteria wajib diisi.',
            'criteria_code.required' => 'Kode Kriteria wajib diisi.',
            'criteria_code.unique' => 'Kode Kriteria sudah ada.',
            'input_type.required' => 'Tipe Input wajib dipilih.',
            'input_type.in' => 'Tipe Input tidak valid.',
            'criteria_type.required' => 'Tipe Kriteria wajib dipilih.',
            'criteria_type.in' => 'Tipe Kriteria tidak valid.',
            'weight.required' => 'Bobot Kriteria wajib diisi.',
            'weight.numeric' => 'Bobot Kriteria harus berupa angka.',
            'weight.min' => 'Bobot Kriteria minimal :min.',
            'weight.max' => 'Bobot Kriteria maksimal :max.',
            'no.required' => 'Nomor Kriteria wajib diisi.',
            'no.unique' => 'Nomor Kriteria sudah ada.',
            'subs.required' => 'Minimal harus ada satu Sub-Kriteria jika tipe input adalah Poin.',
            'subs.array' => 'Format Sub-Kriteria tidak valid.',
            'subs.min' => 'Minimal harus ada :min Sub-Kriteria.',
            'subs.*.label.required' => 'Label Sub-Kriteria wajib diisi.',
            'subs.*.point.required' => 'Poin Sub-Kriteria wajib diisi.',
            'subs.*.point.numeric' => 'Poin Sub-Kriteria harus berupa angka.',
            'subs.*.point.min' => 'Poin Sub-Kriteria minimal :min.',
        ];
    }
}