<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\AcademicPeriod;

class AlternatifRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('guru'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'alternatif_name' => ['required', 'string', 'max:255'],
            // Mengubah dari academic_period_combined menjadi academic_period_id
            'academic_period_id' => [
                'required',
                'integer', // Pastikan ID adalah integer
                Rule::exists('academic_periods', 'id')->where(function ($query) {
                    $query->where('is_active', true); // Memastikan periode aktif
                }),
            ],
        ];

        // Aturan unique untuk 'alternatif_code' berbeda antara create dan update
        if ($this->isMethod('POST')) { // Untuk operasi store (create)
            $rules['alternatif_code'] = ['required', 'string', 'max:255', 'unique:alternatifs,alternatif_code'];
        } elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) { // Untuk operasi update
            // Ambil ID alternatif dari route parameter untuk mengabaikan dirinya sendiri
            $alternatifId = $this->route('alternatif') ? $this->route('alternatif')->id : null;
            $rules['alternatif_code'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('alternatifs', 'alternatif_code')->ignore($alternatifId),
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'alternatif_code.required' => 'Kode alternatif wajib diisi.',
            'alternatif_code.unique' => 'Kode alternatif sudah digunakan.',
            'alternatif_name.required' => 'Nama alternatif wajib diisi.',
            // Pesan untuk academic_period_id
            'academic_period_id.required' => 'Periode akademik wajib dipilih.',
            'academic_period_id.integer' => 'ID periode akademik tidak valid.',
            'academic_period_id.exists' => 'Periode akademik yang dipilih tidak valid atau tidak aktif.',
        ];
    }
}