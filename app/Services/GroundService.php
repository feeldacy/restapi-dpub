<?php

    namespace App\Services;

    use App\Models\alamatTanah;
    use App\Models\detailTanah;
    use App\Models\fotoTanah;
    use App\Models\markerTanah;
    use App\Models\polygonTanah;
    use App\Models\sertifikatTanah;
use App\Models\User;
use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

    class GroundService
    {
        public function storeGroundData(array $data)
        {

            return DB::transaction(function () use ($data) {
                Log::info('Mulai menyimpan data', $data);

                // Generate custom IDs untuk alamatTanah, detailTanah, markerTanah, dan polygonTanah
                $alamatTanahID = IdGenerator::generate(['table' => 'alamat_tanah', 'length' => 8, 'prefix' => 'AT-']);
                Log::info('ID AlamatTanah', ['id' => $alamatTanahID]);
                $detailTanahID = IdGenerator::generate(['table' => 'detail_tanah', 'length' => 8, 'prefix' => 'DT-']);
                $markerTanahID = IdGenerator::generate(['table' => 'marker_tanah', 'length' => 8, 'prefix' => 'MT-']);
                $polygonTanahID = IdGenerator::generate(['table' => 'polygon_tanah', 'length' => 8, 'prefix' => 'PT-']);
                $user = auth('api')->user()->id;

                // Menyimpan alamat tanah
                $alamatTanah = AlamatTanah::create([
                    'id' => $alamatTanahID,
                    'detail_alamat' => $data['detail_alamat'],
                    'rt' => $data['rt'],
                    'rw' => $data['rw'],
                    'padukuhan' => $data['padukuhan'],
                ]);

                // Menyimpan detail tanah
                $detailTanah = DetailTanah::create([
                    'id' => $detailTanahID,
                    'nama_tanah' => $data['nama_tanah'],
                    'luas_tanah' => $data['luas_tanah'],
                    'alamat_id' => $alamatTanah->id,
                    'status_kepemilikan_id' => $data['status_kepemilikan_id'],
                    'status_tanah_id' => $data['status_tanah_id'],
                    'tipe_tanah_id' => $data['tipe_tanah_id'],
                    'added_by' => $user
                ]);

                // Menyimpan marker tanah
                $markerTanah = MarkerTanah::create([
                    'id' => $markerTanahID,
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'detail_tanah_id' => $detailTanah->id,
                ]);

                Log::info('Marker Tanah:', ['marker' => $markerTanah]);

                Log::info('Data yang akan disimpan ke polygon_tanah:', [
                    'id' => $polygonTanahID,
                    'coordinates' => $data['coordinates'],
                    'marker_id' => $markerTanah->id
                ]);

                // Menyimpan polygon tanah
                $polygonTanah = PolygonTanah::create([
                    'id' => $polygonTanahID,
                    'coordinates' => $data['coordinates'],
                    'marker_id' => $markerTanah->id,
                ]);


                // Menyimpan file foto jika ada
                if (isset($data['foto_tanah'])) {
                    $photoGroundID = IdGenerator::generate(['table' => 'foto_tanah', 'length' => 8, 'prefix' => 'FT-']);
                    $photoName = 'ground_image_' . time() . '.' . $data['foto_tanah']->getClientOriginalExtension();
                    $data['foto_tanah']->storeAs('ground_image', $photoName, 'public');

                    Log::info("Foto berhasil disimpan", ['name' => $photoName]);

                    FotoTanah::create([
                        'id' => $photoGroundID,
                        'ukuran_foto_tanah' => $data['foto_tanah']->getSize(),
                        'nama_foto_tanah' => $photoName,
                        'detail_tanah_id' => $detailTanah->id,
                    ]);
                }

                // Menyimpan file sertifikat jika ada
                if (isset($data['sertifikat_tanah'])) {
                    $sertificateGroundID = IdGenerator::generate(['table' => 'sertifikat_tanah', 'length' => 8, 'prefix' => 'ST-']);
                    $sertifName = 'ground_sertifikat_' . time() . '.' . $data['sertifikat_tanah']->getClientOriginalExtension();
                    $data['sertifikat_tanah']->storeAs('ground_sertificate', $sertifName, 'public');

                    Log::info("Sertifikat berhasil disimpan", ['name' => $sertifName]);

                    SertifikatTanah::create([
                        'id' => $sertificateGroundID,
                        'ukuran_sertifikat_tanah' => $data['sertifikat_tanah']->getSize(),
                        'nama_sertifikat_tanah' => $sertifName,
                        'detail_tanah_id' => $detailTanah->id,
                    ]);
                }

                return ['message' => 'Data berhasil ditambahkan'];
            });
        }

        public function updateGroundData($id, array $data)
        {
            return DB::transaction(function () use ($id, $data) {
                Log::info('Mulai memperbarui data tanah', ['id' => $id]);

                $user = auth('api')->user()->id;

                $detailTanah = DetailTanah::findOrFail($id);

                // Update Alamat Tanah
                $detailTanah->alamatTanah()->update([
                    'detail_alamat' => $data['detail_alamat'] ?? null,
                    'rt' => $data['rt'] ?? null,
                    'rw' => $data['rw'] ?? null,
                    'padukuhan' => $data['padukuhan'] ?? null,
                ]);

                // Update Detail Tanah
                $detailTanah->update([
                    'nama_tanah' => $data['nama_tanah'] ?? $detailTanah->nama_tanah,
                    'luas_tanah' => $data['luas_tanah'] ?? $detailTanah->luas_tanah,
                    'status_kepemilikan_id' => $data['status_kepemilikan_id'] ?? $detailTanah->status_kepemilikan_id,
                    'status_tanah_id' => $data['status_tanah_id'] ?? $detailTanah->status_tanah_id,
                    'tipe_tanah_id' => $data['tipe_tanah_id'] ?? $detailTanah->tipe_tanah_id,
                    'updated_by' => $user,
                ]);

                // Update Marker Tanah
                $detailTanah->markerTanah()->update([
                    'latitude' => $data['latitude'] ?? $detailTanah->markerTanah->latitude,
                    'longitude' => $data['longitude'] ?? $detailTanah->markerTanah->longitude,
                ]);

                // Update Polygon Tanah
                $coordinates = isset($data['coordinates']) ? json_decode($data['coordinates'], true) : null;
                $detailTanah->markerTanah->polygonTanah()->update([
                    'coordinates' => $coordinates['geometry']['coordinates'] ?? $detailTanah->markerTanah->polygonTanah->coordinates,
                ]);

                if (isset($data['foto_tanah']) && $data['foto_tanah'] instanceof UploadedFile) {
                    $foto = $data['foto_tanah'];

                    $fotoTanah = FotoTanah::where('detail_tanah_id', $detailTanah->id)->first();

                    if ($fotoTanah && Storage::disk('public')->exists('ground_image/' . $fotoTanah->nama_foto_tanah)) {
                        Storage::disk('public')->delete('ground_image/' . $fotoTanah->nama_foto_tanah);
                        Log::info("Foto lama dihapus", ['name' => $fotoTanah->nama_foto_tanah]);
                    }

                    $photoGroundID = IdGenerator::generate(['table' => 'foto_tanah', 'length' => 8, 'prefix' => 'FT-']);
                    $photoName = 'ground_image_' . time() . '.' . $foto->getClientOriginalExtension();

                    $foto->storeAs('ground_image', $photoName, 'public');
                    Log::info("Foto berhasil diperbarui", ['name' => $photoName]);

                    FotoTanah::updateOrCreate(
                        ['detail_tanah_id' => $detailTanah->id],
                        [
                            'id' => $photoGroundID,
                            'ukuran_foto_tanah' => $foto->getSize(),
                            'nama_foto_tanah' => $photoName,
                        ]
                    );
                }

                if (isset($data['sertifikat_tanah']) && $data['sertifikat_tanah'] instanceof UploadedFile) {
                    $sertifikat = $data['sertifikat_tanah'];

                    $sertifikatTanah = SertifikatTanah::where('detail_tanah_id', $detailTanah->id)->first();

                    if ($sertifikatTanah && Storage::disk('public')->exists('ground_sertificate/' . $sertifikatTanah->nama_sertifikat_tanah)) {
                        Storage::disk('public')->delete('ground_sertificate/' . $sertifikatTanah->nama_sertifikat_tanah);
                        Log::info("Sertifikat lama dihapus", ['name' => $sertifikatTanah->nama_sertifikat_tanah]);
                    }

                    $sertifikatTanahID = IdGenerator::generate(['table' => 'sertifikat_tanah', 'length' => 8, 'prefix' => 'ST-']);
                    $sertifikatTanahName = 'ground_sertificate_' . time() . '.' . $sertifikat->getClientOriginalExtension();

                    $sertifikat->storeAs('ground_sertificate', $sertifikatTanahName, 'public');
                    Log::info("Sertifikat berhasil diperbarui", ['name' => $sertifikatTanahName]);

                    SertifikatTanah::updateOrCreate(
                        ['detail_tanah_id' => $detailTanah->id],
                        [
                            'id' => $sertifikatTanahID,
                            'ukuran_sertifikat_tanah' => $sertifikat->getSize(),
                            'nama_sertifikat_tanah' => $sertifikatTanahName,
                        ]
                    );
                }

                return ['message' => 'Data berhasil diperbarui'];
            });
        }

        public function deleteGround($id)
        {
            $ground = DetailTanah::find($id);
            $user = auth('api')->user()->id;

            if (!$ground) {
                return ['success' => false, 'message' => 'Data tidak ditemukan'];
            }

            // Ambil semua foto terkait
            $photoGround = DB::table('foto_tanah')
                ->where('detail_tanah_id', $id)
                ->get();

            // Ambil semua sertifikat terkait
            $certificateGround = DB::table('sertifikat_tanah')
                ->where('detail_tanah_id', $id)
                ->get();

            // Hapus setiap file foto dari storage
            foreach ($photoGround as $photo) {
                Storage::delete('public/ground_image/' . $photo->nama_foto_tanah);
            }

            // Hapus setiap file sertifikat dari storage
            foreach ($certificateGround as $certificate) {
                Storage::delete('public/ground_sertificate/' . $certificate->nama_sertifikat_tanah);
            }

            // Hapus data dari database
            $ground->update([
                'deleted_by' => $user
            ]);
            $ground->delete();


            return ['success' => true, 'message' => 'Data ground berhasil dihapus'];
        }
    }



?>
