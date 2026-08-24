<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'no_hp' => $this->no_hp,
            'role' => $this->role,
            'profile_photo' => $this->profile_photo ? asset('storage/' . $this->profile_photo) : null,
            // Assuming relationships exist
            'kecamatan' => $this->whenLoaded('kecamatan', function () {
                return $this->kecamatan->nama_kecamatan ?? null;
            }),
        ];
    }
}
