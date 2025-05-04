<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    public function statusKepemilikan(){
        return $this->belongsTo(StatusKepemilikan::class, 'status_kepemilikan_id');
    }

    public function statusTanah(){
        return $this->belongsTo(StatusTanah::class,'status_tanah_id');
    }

    public function tipeTanah(){
        return $this->belongsTo(TipeTanah::class, 'tipe_tanah_id');
    }

    public function alamatTanah(){
        return $this->belongsTo(AlamatTanah::class, 'alamat_id');
    }

    public function addedBy(){
        return $this->belongsTo(User::class, 'added_by');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function fotoTanah(){
        return $this->hasMany(FotoTanah::class, 'detail_tanah_id');
    }

    public function sertifikatTanah(){
        return $this->hasMany(SertifikatTanah::class, 'detail_tanah_id');
    }

    public function markerTanah(){
        return $this->hasOne(MarkerTanah::class, 'detail_tanah_id');
    }
}
