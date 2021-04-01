<?php

namespace App\Http\Resources;

use App\Http\Controllers\FlightsController;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $flights = $this->flightBack ? [$this->flightFrom, $this->flightBack] : [$this->flightFrom];
        $this->flightFrom->date = $this->date_from;
        $this->has('flightBack') ? ($this->flightBack->date = $this->date_back) : null;

        return [
            'code' => $this->code,
            'cost' => $this->flightBack ? ($this->flightFrom['cost'] + $this->flightBack['cost']) : $this->flightFrom['cost'],
            'flights' => FlightResource::collection(collect($flights)),
            'passengers' => PassengerResource::collection($this->passengers),
        ];
    }
}
