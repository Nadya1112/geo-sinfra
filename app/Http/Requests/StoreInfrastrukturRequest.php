<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInfrastrukturRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'surveyor';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_infrastruktur' => 'required|string|max:255',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'id_kelurahan' => 'required|exists:kelurahan,id_kelurahan',
            'latitude' => 'required',
            'longitude' => 'required',
            'foto' => 'required|max:20480',
            'kondisi' => 'nullable|string',
            'material_eksisting' => 'nullable|string',
            'panjang' => 'required|numeric',
            'lebar' => 'required|numeric',
            'has_drainase' => 'nullable|boolean',
            'has_gorong_gorong' => 'nullable|boolean',
            'rencana_perbaikan' => 'nullable|string',
            'tgl_survey' => 'nullable|date',
        ];
    }

    /**
     * Pesan validasi kustom dalam bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'nama_infrastruktur.required' => 'Nama infrastruktur atau objek wajib diisi.',
            'id_kecamatan.required' => 'Kecamatan wajib dipilih.',
            'id_kelurahan.required' => 'Kelurahan wajib dipilih.',
            'latitude.required' => 'Koordinat garis lintang (latitude) wajib diatur dari peta.',
            'longitude.required' => 'Koordinat garis bujur (longitude) wajib diatur dari peta.',
            'foto.required' => 'Foto dokumentasi lapangan wajib diunggah.',
            'foto.max' => 'Ukuran foto maksimal adalah 20MB.',
            'panjang.required' => 'Panjang infrastruktur wajib diisi.',
            'lebar.required' => 'Lebar infrastruktur wajib diisi.',
        ];
    }
}
