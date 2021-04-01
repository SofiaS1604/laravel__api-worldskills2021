<?php

namespace App;
use App\BookingsModel;
use Illuminate\Database\Eloquent\Model;

class FlightsModel extends Model
{
    protected $table = 'flights';
    protected $fillable = [
        'flight_code', 'from_id', 'to_id',
        'time_from', 'time_to', 'cost',
    ];

    protected $with = [ 'airportFrom', 'airportTo'];

    public function airportFrom()
    {
        return $this->hasOne(AirportsModel::class, 'id', 'from_id');
    }

    public function airportTo()
    {
        return $this->hasOne(AirportsModel::class, 'id', 'to_id');
    }


    public function availabilityCount($date){
        $availability = 0;
        $bookings = BookingsModel::where(['flight_from' => $this->id, 'date_from' => $date])
            ->orWhere(['flight_back' => $this->id, 'date_back' => $date])->get();
        foreach ($bookings as $booking){
            $availability += PassengersModel::where(['booking_id' => $booking->id])->count();
        }
        return 60 - $availability;
    }



}
