<?php

namespace App\Http\Controllers;

use App\BookingsModel;
use App\Http\Resources\BookingResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use stdClass;

class BookingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return BookingResource
     */
    public function index($code)
    {
        $booking = BookingsModel::where(['code' => $code])->first();
        if(!empty($booking)){
            return new BookingResource($booking);
        }
        return [];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'flight_from' => 'required', 'flight_from.id' => 'required|exists:flights,id',
            'flight_from.date' => 'required|date_format:Y-m-d',
            'flight_back.id' => 'required_with:flight_back|exists:flights,id',
            'flight_back.date' => 'required_with:flight_back|date_format:Y-m-d',
            'passengers' => 'required|array|between:1,8', 'passengers.*.first_name' => 'required',
            'passengers.*.last_name' => 'required', 'passengers.*.birth_date' => 'required|date_format:Y-m-d',
            'passengers.*.document_number' => 'required|numeric|digits:10',
        ]);

        if ($validate->fails())
            return $this->outputData(422, $validate->errors(), 'error', 'Validation error');

        $keys = $request->has('flight_back') ? ['from', 'back'] : ['from'];

        $model = [];
        $model['code'] = Str::upper(Str::random(5));

        foreach ($keys as $key){
            $model['flight_'.$key] = $request['flight_'.$key]['id'];
            $model['date_'.$key] = $request['flight_'.$key]['date'];
        }

        $booking = BookingsModel::create($model);
        $booking->passengers()->createMany($request->passengers);

        return  $this->outputData(201, ['code' => $model['code']]);
    }
}
