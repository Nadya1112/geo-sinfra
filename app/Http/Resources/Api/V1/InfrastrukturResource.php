<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InfrastrukturResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_infrastruktur' => $this->id_infrastruktur,
            'uuid' => $this->uuid,
            'nama_objek' => $this->nama_objek,
            'nama_infrastruktur' => $this->nama_infrastruktur,
            'jenis' => $this->jenis,
            'jenis_infrastruktur' => $this->jenis_infrastruktur,
            'material_eksisting' => $this->material_eksisting,
            'alamat' => $this->alamat,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'kondisi' => $this->kondisi,
            'panjang' => $this->panjang,
            'lebar' => $this->lebar,
            'has_drainase' => $this->has_drainase,
            'has_gorong_gorong' => $this->has_gorong_gorong,
            'rencana_perbaikan' => $this->rencana_perbaikan,
            'status_verifikasi' => $this->status_verifikasi,
            'status_validasi' => $this->status_validasi,
            'alasan_penolakan' => $this->alasan_penolakan,
            'status_perbaikan' => $this->status_perbaikan,
            'tgl_survey' => $this->tgl_survey,
            'foto_terbaru' => $this->foto_terbaru ? asset('storage/' . $this->foto_terbaru) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relationships
            'user' => new UserResource($this->whenLoaded('user')),
            'kelurahan' => $this->whenLoaded('kelurahan', function () {
                return [
                    'id_kelurahan' => $this->kelurahan->id_kelurahan,
                    'nama_kelurahan' => $this->kelurahan->nama_kelurahan,
                    'kecamatan' => $this->kelurahan->kecamatan ? [
                        'id_kecamatan' => $this->kelurahan->kecamatan->id_kecamatan,
                        'nama_kecamatan' => $this->kelurahan->kecamatan->nama_kecamatan,
                    ] : null
                ];
            }),
            'analisis' => $this->whenLoaded('analisis'),
            'cnn' => $this->whenLoaded('cnn'),
        ];
    }
}
