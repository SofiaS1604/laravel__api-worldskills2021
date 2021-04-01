<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PassengersModel extends Model
{
    protected $table = 'passengers';
    protected $fillable = [
        'booking_id', 'first_name', 'last_name',
        'birth_date', 'document_number', 'place_from', 'place_back',
    ];
}
