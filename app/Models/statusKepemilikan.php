<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class statusKepemilikan extends Model
{
    /** @use HasFactory<\Database\Factories\StatusKepemilikanFactory> */
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $table ='status_kepemilikan';

    protected $fillable = [
        'id',
        'nama_status_kepemilikan'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function detailTanah(){
        return $this->hasMany(DetailTanah::class,'status_kepemilikan_id');
    }
}
