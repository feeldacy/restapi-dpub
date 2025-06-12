<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{

    protected $table ='visitors';

    protected $fillable = [
        'ip',
        'visitor_token', 
        'period',
        'count',
        'date'
    ];

    public $incrementing = true;

}
