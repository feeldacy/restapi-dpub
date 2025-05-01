<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class tipeTanah extends Model
{
    /** @use HasFactory<\Database\Factories\TipeTanahFactory> **/
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $table ='tipe_tanah';

    protected $fillable = [
        'id',
        'nama_tipe_tanah'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function detailTanah(){
        return $this->hasMany(DetailTanah::class,'tipe_tanah_id');
    }
}
