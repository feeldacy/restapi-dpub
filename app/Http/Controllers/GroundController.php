<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroundRequest;
use App\Models\detailTanah;
use App\Services\GroundService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GroundController extends Controller
{
    protected $groundService;

    public function __construct(GroundService $groundService)
    {
        $this->groundService = $groundService;
    }

    /**
     * Display a listing of the resource.
     */
    public function getAllData()
    {

       $groundData = DB::table('detail_tanah')
        ->leftJoin('marker_tanah', 'detail_tanah.id', '=', 'marker_tanah.detail_tanah_id')
        ->leftJoin('polygon_tanah', 'marker_tanah.id', '=', 'polygon_tanah.marker_id')
        ->leftJoin('status_tanah', 'detail_tanah.status_tanah_id', '=', 'status_tanah.id')
        ->leftJoin('status_kepemilikan', 'detail_tanah.status_kepemilikan_id', '=', 'status_kepemilikan.id')
        ->leftJoin('tipe_tanah', 'detail_tanah.tipe_tanah_id', '=', 'tipe_tanah.id')
        ->leftJoin('foto_tanah', 'detail_tanah.id', '=', 'foto_tanah.detail_tanah_id')
        ->leftJoin('sertifikat_tanah', 'detail_tanah.id', '=', 'sertifikat_tanah.detail_tanah_id')
        ->leftJoin('users as added_user', 'detail_tanah.added_by', '=', 'added_user.id')
        ->leftJoin('users as updated_user', 'detail_tanah.updated_by', '=', 'updated_user.id')
        ->leftJoin('users as deleted_user', 'detail_tanah.deleted_by', '=', 'deleted_user.id')
        ->leftJoin('alamat_tanah', 'alamat_tanah.id', '=', 'detail_tanah.alamat_id')
        ->whereNull('detail_tanah.deleted_at')
        ->select(
            'detail_tanah.id as detail_tanah_id',
            'alamat_tanah.detail_alamat as alamat',
            'foto_tanah.nama_foto_tanah as foto_tanah',
            'sertifikat_tanah.nama_sertifikat_tanah as sertifikat_tanah',
            'detail_tanah.nama_tanah',
            'detail_tanah.luas_tanah',
            'alamat_tanah.padukuhan',
            'alamat_tanah.rt',
            'alamat_tanah.rw',
            'detail_tanah.updated_at',
            'status_kepemilikan.nama_status_kepemilikan',
            'tipe_tanah.nama_tipe_tanah',
            'status_tanah.nama_status_tanah',
            "marker_tanah.latitude",
            "marker_tanah.longitude",
            'polygon_tanah.coordinates',
            'added_user.name as added_by_name',
            'updated_user.name as updated_by_name',
            'deleted_user.name as deleted_by_name'
        )
        ->get();


        return response()->json([
            'status' => 'success',
            'data' => $groundData
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroundRequest $request)
    {
        try {
            $result = $this->groundService->storeGroundData($request->validated());

            return response()->json([
                'success' => true,
                'message' => $result['message']
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan data.',
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function getSpecificData(string $id)
    {
        $groundData = detailTanah::query()
            ->leftJoin('marker_tanah', 'detail_tanah.id', '=', 'marker_tanah.detail_tanah_id')
            ->leftJoin('polygon_tanah', 'marker_tanah.id', '=', 'polygon_tanah.marker_id')
            ->leftJoin('status_tanah', 'detail_tanah.status_tanah_id', '=', 'status_tanah.id')
            ->leftJoin('status_kepemilikan', 'detail_tanah.status_kepemilikan_id', '=', 'status_kepemilikan.id')
            ->leftJoin('tipe_tanah', 'detail_tanah.tipe_tanah_id', '=', 'tipe_tanah.id')
            ->leftJoin('foto_tanah', 'detail_tanah.id', '=', 'foto_tanah.detail_tanah_id')
            ->leftJoin('sertifikat_tanah', 'detail_tanah.id', '=', 'sertifikat_tanah.detail_tanah_id')
            ->leftJoin('alamat_tanah', 'alamat_tanah.id', '=', 'detail_tanah.alamat_id')
            ->where('detail_tanah.id', $id)
            ->whereNull('detail_tanah.deleted_at')
            ->select(
                'detail_tanah.id as detail_tanah_id',
                'alamat_tanah.detail_alamat as alamat',
                'alamat_tanah.rt',
                'alamat_tanah.rw',
                'alamat_tanah.padukuhan',
                'foto_tanah.nama_foto_tanah as foto_tanah',
                'sertifikat_tanah.nama_sertifikat_tanah as sertifikat_tanah',
                'detail_tanah.nama_tanah',
                'detail_tanah.luas_tanah',
                'detail_tanah.updated_at',
                'detail_tanah.added_by',
                'detail_tanah.updated_by',
                'detail_tanah.deleted_at',
                'detail_tanah.tipe_tanah_id',
                'detail_tanah.status_tanah_id',
                'detail_tanah.status_kepemilikan_id',
                'status_kepemilikan.nama_status_kepemilikan',
                'tipe_tanah.nama_tipe_tanah',
                'status_tanah.nama_status_tanah',
                'marker_tanah.latitude',
                'marker_tanah.longitude',
                'polygon_tanah.coordinates',
            )
            ->first(); // hanya satu data

        if (!$groundData) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan atau sudah dihapus.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $groundData
        ], 200);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreGroundRequest $request, string $id): JsonResponse
    {

        try {
            Log::info('Request data:', $request->all());
            $validated = $request->validated();
            $result = $this->groundService->updateGroundData($id, $validated);

            return response()->json([
                'success' => true,
                'message' => $result['message']
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan data.',
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->groundService->deleteGround($id);

        if (!$result['success']) {
            return response()->json(['message' => $result['message']], 404);
        }

        return response()->json(['success' => true, 'message' => $result['message']], 200);
    }
}
