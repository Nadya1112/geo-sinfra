<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LaporanWargaResource extends JsonResource
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
            'uuid' => $this->uuid,
            'nama_pelapor' => $this->nama_pelapor,
            'no_hp' => $this->no_hp,
            'deskripsi' => $this->deskripsi,
            'foto' => $this->foto ? asset('storage/' . $this->foto) : null,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'status' => $this->status,
            'skor_ai' => $this->skor_ai,
            'label_ai' => $this->label_ai,
            'jenis_ai' => $this->jenis_ai,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relationships
            'infrastruktur' => new InfrastrukturResource($this->whenLoaded('infrastruktur')),
            'surveyor' => new UserResource($this->whenLoaded('surveyor')),
        ];
    }
}
