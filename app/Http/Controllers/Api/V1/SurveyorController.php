<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Infrastruktur;
use App\Http\Resources\Api\V1\InfrastrukturResource;
use App\Traits\ApiResponse;
use App\Http\Requests\Api\V1\StoreInfrastrukturRequest;
use App\Http\Requests\Api\V1\SyncDataRequest;
use App\Services\UploadService;
use Illuminate\Support\Facades\DB;

class SurveyorController extends Controller
{
    use ApiResponse;

    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * Get list of surveys assigned to the surveyor
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isSurveyor()) {
            return $this->errorResponse('Akses ditolak. Anda bukan surveyor.', 403);
        }

        $infrastruktur = Infrastruktur::with(['kelurahan.kecamatan', 'analisis', 'cnn'])
            ->where('id_user', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse(
            InfrastrukturResource::collection($infrastruktur),
            'Berhasil mengambil data infrastruktur'
        );
    }

    /**
     * Store new survey data (online mode)
     */
    public function store(StoreInfrastrukturRequest $request)
    {
        $user = $request->user();
        if (!$user->isSurveyor()) {
            return $this->errorResponse('Akses ditolak. Anda bukan surveyor.', 403);
        }

        $data = $request->validated();
        $data['id_user'] = $user->id;

        if ($request->hasFile('foto_terbaru')) {
            $path = $this->uploadService->upload($request->file('foto_terbaru'), 'infrastruktur');
            $data['foto_terbaru'] = $path;
        }

        $infrastruktur = Infrastruktur::create($data);
        $infrastruktur->load(['kelurahan.kecamatan']);

        return $this->successResponse(
            new InfrastrukturResource($infrastruktur),
            'Data survey berhasil disimpan',
            201
        );
    }

    /**
     * Sync batch data from offline mode
     */
    public function sync(SyncDataRequest $request)
    {
        $user = $request->user();
        if (!$user->isSurveyor()) {
            return $this->errorResponse('Akses ditolak. Anda bukan surveyor.', 403);
        }

        $items = $request->validated()['data'];
        $syncedIds = [];
        $failedSyncs = [];

        DB::beginTransaction();

        try {
            foreach ($items as $item) {
                // Check if already exists by UUID
                $exists = Infrastruktur::where('uuid', $item['uuid'])->exists();

                if (!$exists) {
                    $item['id_user'] = $user->id;
                    // If offline sync sends photos, they might be base64 or handled differently.
                    // For now, we assume simple fields sync first.
                    $infra = Infrastruktur::create($item);
                    $syncedIds[] = $infra->uuid;
                } else {
                    // Could update instead, but let's assume UUID implies new creation for sync.
                    $syncedIds[] = $item['uuid']; // already synced previously
                }
            }

            DB::commit();

            return $this->successResponse([
                'synced_uuids' => $syncedIds,
                'failed_syncs' => $failedSyncs
            ], 'Sinkronisasi berhasil diproses');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Gagal melakukan sinkronisasi: ' . $e->getMessage(), 500);
        }
    }
}
