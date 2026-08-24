<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaporanWargaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Anyone can submit (Masyarakat)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'uuid' => 'nullable|string|unique:laporan_warga,uuid',
            'id_infrastruktur' => 'nullable|integer|exists:infrastruktur,id_infrastruktur',
            'nama_pelapor' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'foto' => 'required|image|max:5120', // Max 5MB
        ];
    }
}
