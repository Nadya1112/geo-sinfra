<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreInfrastrukturRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // We'll rely on Sanctum middleware + Role checks in controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'uuid' => 'nullable|string|unique:infrastruktur,uuid',
            'id_kelurahan' => 'required|integer|exists:kelurahan,id_kelurahan',
            'nama_objek' => 'required|string|max:255',
            'nama_infrastruktur' => 'required|string|max:255',
            'jenis' => 'nullable|string|max:255',
            'jenis_infrastruktur' => 'nullable|string|max:255',
            'material_eksisting' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'kondisi' => 'required|string',
            'panjang' => 'nullable|numeric',
            'lebar' => 'nullable|numeric',
            'has_drainase' => 'nullable|boolean',
            'has_gorong_gorong' => 'nullable|boolean',
            'rencana_perbaikan' => 'nullable|string',
            'foto_terbaru' => 'nullable|image|max:5120', // Max 5MB
            'tgl_survey' => 'nullable|date',
        ];
    }
}
