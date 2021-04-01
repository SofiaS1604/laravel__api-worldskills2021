<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AirportsModel extends Model
{
    protected $table = 'airports';
    protected $fillable = [
        'city', 'name', 'iata'
    ];
}
