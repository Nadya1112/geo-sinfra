<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanWarga;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Http\Resources\Api\V1\LaporanWargaResource;
use App\Traits\ApiResponse;
use App\Http\Requests\Api\V1\StoreLaporanWargaRequest;
use App\Services\UploadService;

class PublicController extends Controller
{
    use ApiResponse;

    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * Submit Laporan Warga
     */
    public function submitLaporan(StoreLaporanWargaRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $path = $this->uploadService->upload($request->file('foto'), 'laporan_warga');
            $data['foto'] = $path;
        }

        $laporan = LaporanWarga::create($data);

        return $this->successResponse(
            new LaporanWargaResource($laporan),
            'Laporan berhasil dikirim. Terima kasih!',
            201
        );
    }

    /**
     * Get list of Kecamatan and Kelurahan for dropdowns
     */
    public function getWilayah()
    {
        $kecamatan = Kecamatan::with('kelurahans')->get();

        return $this->successResponse(
            $kecamatan,
            'Berhasil mengambil data wilayah'
        );
    }
}
