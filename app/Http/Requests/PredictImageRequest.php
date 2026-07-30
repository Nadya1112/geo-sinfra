<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PredictImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'required|file|max:20480',
        ];
    }

    /**
     * Kustom validasi tambahan untuk memeriksa ekstensi file gambar tanpa ketergantungan finfo.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('image') && $this->file('image')->isValid()) {
                $extension = strtolower($this->file('image')->getClientOriginalExtension());
                $allowedExtensions = ['jpeg', 'jpg', 'png', 'webp'];

                if (!in_array($extension, $allowedExtensions)) {
                    $validator->errors()->add('image', 'Format file tidak didukung! Gunakan JPG, PNG, atau WEBP.');
                }
            }
        });
    }

    /**
     * Pesan error dalam bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'image.required' => 'File gambar wajib diunggah untuk prediksi AI.',
            'image.max' => 'Ukuran gambar maksimal adalah 20MB.',
        ];
    }
}
