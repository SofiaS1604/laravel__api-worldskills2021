<?php

namespace App\Http\Controllers;

use App\AirportsModel;
use App\FlightsModel;
use App\Http\Resources\FlightResource;
use App\UsersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlightsController extends Controller
{

    public function outputFlights($from, $to, $date)
    {
        $flights = FlightsModel::whereHas('airportFrom', function ($query) use ($from) {
            $query->where('iata', $from);
        })->whereHas('airportTo', function ($query) use ($to) {
            $query->where('iata', $to);
        })->get();

        $flights = $flights->filter(function ($flight) use ($date) {
            $flight->date = $date;
            return $flight->availabilityCount($date);
        });

        return $flights;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required|exists:airports,iata', 'to' => 'required|exists:airports,iata',
            'date1' => 'required|date_format:Y-m-d', 'date2' => 'date_format:Y-m-d',
            'passengers' => 'required|integer|between:1,8',
        ]);

        if ($validator->fails())
            return $this->outputData(422, $validator->errors(), 'error', 'Validation error');

        $res = ['flights_to' => [], 'flights_back' => []];
        $res['flights_to'] = FlightResource::collection($this->outputFlights($request->from, $request->to, $request->date1));
        if ($request->has('date2'))
            $res['flights_back'] = FlightResource::collection($this->outputFlights($request->to, $request->from, $request->date2));

        return $this->outputData(200, $res);
    }
}
