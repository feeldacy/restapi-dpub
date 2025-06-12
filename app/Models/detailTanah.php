<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class detailTanah extends Model
{
    /** @use HasFactory<\Database\Factories\DetailTanahFactory> */
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $table ='detail_tanah';
    protected $fillable = [
        'id',
        'nama_tanah',
        'luas_tanah',
        'status_kepemilikan_id',
        'status_tanah_id',
        'tipe_tanah_id',
        'alamat_id',
        'added_by',
        'updated_by',
        'deleted_by'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::deleting(function ($detailTanah) {
            if (!$detailTanah->isForceDeleting()) {

                if ($detailTanah->alamatTanah) {
                    $detailTanah->alamatTanah->delete();
                }

                if ($detailTanah->markerTanah) {
                    $detailTanah->markerTanah->delete();
                }

                $detailTanah->sertifikatTanah()->each(function ($sertifikat) {
                    $sertifikat->delete();
                });

                $detailTanah->fotoTanah()->each(function ($foto){
                    $foto->delete();
                });
            }
        });

        static::restoring(function ($detailTanah) {
            if ($detailTanah->alamatTanah()->withTrashed()->exists()) {
                $detailTanah->alamatTanah()->withTrashed()->first()->restore();
            }

            if ($detailTanah->markerTanah()->withTrashed()->exists()) {
                $detailTanah->markerTanah()->withTrashed()->first()->restore();
            }

            $detailTanah->sertifikatTanah()->withTrashed()->get()->each(function ($sertifikat) {
                $sertifikat->restore();
            });

            $detailTanah->fotoTanah()->withTrashed()->get()->each(function ($foto) {
                $foto->restore();
            });
        });
    }

    public function statusKepemilikan(){
        return $this->belongsTo(statusKepemilikan::class, 'status_kepemilikan_id');
    }

    public function statusTanah(){
        return $this->belongsTo(statusTanah::class,'status_tanah_id');
    }

    public function tipeTanah(){
        return $this->belongsTo(tipeTanah::class, 'tipe_tanah_id');
    }

    public function alamatTanah(){
        return $this->belongsTo(alamatTanah::class, 'alamat_id');
    }

    public function addedBy(){
        return $this->belongsTo(User::class, 'added_by');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function fotoTanah(){
        return $this->hasMany(fotoTanah::class, 'detail_tanah_id');
    }

    public function sertifikatTanah(){
        return $this->hasMany(sertifikatTanah::class, 'detail_tanah_id');
    }

    public function markerTanah(){
        return $this->hasOne(markerTanah::class, 'detail_tanah_id');
    }
}
