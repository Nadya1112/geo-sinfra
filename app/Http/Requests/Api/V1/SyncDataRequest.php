<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class SyncDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data' => 'required|array',
            'data.*.uuid' => 'required|string',
            'data.*.id_kelurahan' => 'required|integer|exists:kelurahan,id_kelurahan',
            'data.*.nama_objek' => 'required|string|max:255',
            'data.*.nama_infrastruktur' => 'required|string|max:255',
            'data.*.latitude' => 'required|numeric',
            'data.*.longitude' => 'required|numeric',
            'data.*.kondisi' => 'required|string',
            // Files might be sent separately or as base64 in sync, 
            // if sending multipart/form-data with array, it's a bit complex.
            // Usually, sync endpoints just handle JSON data, and files are uploaded before/after.
            // Or we handle 'data.*.foto_terbaru' if sent as multipart.
        ];
    }
}
