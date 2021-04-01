<?php

namespace App;

use App\Models\Flight;
use Illuminate\Database\Eloquent\Model;

class BookingsModel extends Model
{
    protected $table = 'bookings';
    protected $fillable = [
        'flight_from', 'flight_back', 'date_from',
        'date_back', 'code',
    ];

    public function flightFrom()
    {
        return $this->hasOne(FlightsModel::class, 'id', 'flight_from');
    }

    public function flightBack()
    {
        return $this->hasOne(FlightsModel::class, 'id', 'flight_back');
    }

    public function passengers()
    {
        return $this->hasMany(PassengersModel::class, 'booking_id', 'id');
    }
}
