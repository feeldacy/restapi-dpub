<?php

namespace App\Http\Controllers;

use App\Models\detailTanah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeletedGroundController extends Controller
{
    public function restore(string $id){
        try {
            $data = detailTanah::withTrashed()->findOrFail($id);

            $data->restore();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di restore'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat restore data.',
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    }

    public function getAllDeletedData(){

        try {
            $deletedGroundData = DB::table('detail_tanah')
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
            ->whereNotNull('detail_tanah.deleted_at')
            ->select(
                'detail_tanah.id as detail_tanah_id',
                'alamat_tanah.detail_alamat as alamat',
                'foto_tanah.nama_foto_tanah as foto_tanah',
                'sertifikat_tanah.nama_sertifikat_tanah as sertifikat_tanah',
                'detail_tanah.nama_tanah',
                'detail_tanah.luas_tanah',
                'detail_tanah.updated_at',
                'detail_tanah.deleted_at',
                'status_kepemilikan.nama_status_kepemilikan',
                'tipe_tanah.nama_tipe_tanah',
                'status_tanah.nama_status_tanah',
                "marker_tanah.latitude",
                "marker_tanah.longitude",
                'added_user.name as added_by_name',
                'updated_user.name as updated_by_name',
                'deleted_user.name as deleted_by_name'
            )
            ->get();


            return response()->json([
                'status' => 'success',
                'data' => $deletedGroundData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data.',
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    }

    public function getSpecificDeletedData(string $id){

        try {
            $deletedGroundData = DB::table('detail_tanah')
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
            ->where('detail_tanah.id', $id)
            ->whereNotNull('detail_tanah.deleted_at')
            ->select(
                'detail_tanah.id as detail_tanah_id',
                'alamat_tanah.detail_alamat as alamat',
                'foto_tanah.nama_foto_tanah as foto_tanah',
                'sertifikat_tanah.nama_sertifikat_tanah as sertifikat_tanah',
                'detail_tanah.nama_tanah',
                'detail_tanah.luas_tanah',
                'detail_tanah.updated_at',
                'status_kepemilikan.nama_status_kepemilikan',
                'tipe_tanah.nama_tipe_tanah',
                'status_tanah.nama_status_tanah',
                "marker_tanah.latitude",
                "marker_tanah.longitude",
                'added_user.name as added_by_name',
                'updated_user.name as updated_by_name',
                'deleted_user.name as deleted_by_name'
            )
            ->get();


            return response()->json([
                'status' => 'success',
                'data' => $deletedGroundData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data.',
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    }
}
