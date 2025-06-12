<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class markerTanah extends Model
{
    /** @use HasFactory<\Database\Factories\MarkerTanahFactory> */
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $table = 'marker_tanah';

    protected $fillable = [
        'id',
        'latitude',
        'longitude',
        'detail_tanah_id'];

    public $incrementing = false;
    protected $keyType = 'string';


    protected static function booted()
    {
        static::deleting(function ($markerTanah) {
            if (!$markerTanah->isForceDeleting()) {

                if ($markerTanah->polygonTanah) {
                    $markerTanah->polygonTanah->delete();
                }
            }
        });

        static::restoring(function ($markerTanah){
            if ($markerTanah->polygonTanah()->withTrashed()->exists()) {
                $markerTanah->polygonTanah()->withTrashed()->first()->restore();
            }
        });
    }

    public function groundDetail(){
        return $this->belongsTo(detailTanah::class, 'detail_tanah_id');
    }

    public function polygonTanah(){
        return $this->hasOne(polygonTanah::class,'marker_id');
    }
}
