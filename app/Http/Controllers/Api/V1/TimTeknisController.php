<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Infrastruktur;
use App\Http\Resources\Api\V1\InfrastrukturResource;
use App\Traits\ApiResponse;

class TimTeknisController extends Controller
{
    use ApiResponse;

    /**
     * Get list of infrastructures pending validation
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isTimTeknis() && !$user->isAdmin()) {
            return $this->errorResponse('Akses ditolak. Anda bukan Tim Teknis.', 403);
        }

        $infrastruktur = Infrastruktur::with(['kelurahan.kecamatan', 'user', 'analisis'])
            ->where('status_verifikasi', 'diterima') // Assuming it needs to be verified first
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse(
            InfrastrukturResource::collection($infrastruktur),
            'Berhasil mengambil data untuk validasi'
        );
    }

    /**
     * Validate an infrastructure
     */
    public function validasi(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->isTimTeknis() && !$user->isAdmin()) {
            return $this->errorResponse('Akses ditolak.', 403);
        }

        $request->validate([
            'status_validasi' => 'required|in:menunggu,disetujui,ditolak',
            'alasan_penolakan' => 'nullable|string'
        ]);

        $infra = Infrastruktur::find($id);

        if (!$infra) {
            return $this->errorResponse('Data infrastruktur tidak ditemukan', 404);
        }

        $infra->status_validasi = $request->status_validasi;
        $infra->alasan_penolakan = $request->alasan_penolakan;
        $infra->save();

        return $this->successResponse(
            new InfrastrukturResource($infra),
            'Status validasi berhasil diupdate'
        );
    }
}
