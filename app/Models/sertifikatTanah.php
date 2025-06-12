<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class sertifikatTanah extends Model
{
    /** @use HasFactory<\Database\Factories\SertifikatTanahFactory> */
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $table = 'sertifikat_tanah';
    protected $fillable = [
        'id',
        'nama_sertifikat_tanah',
        'ukuran_sertifikat_tanah',
        'detail_tanah_id'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function detailTanah(){
        return $this->belongsTo(DetailTanah::class, 'detail_tanah_id');
    }
}
