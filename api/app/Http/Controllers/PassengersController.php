<?php

namespace App\Http\Controllers;

use App\BookingsModel;
use App\Http\Resources\BookingResource;
use App\Http\Resources\PassengerResource;
use App\PassengersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PassengersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($code)
    {
        $booking = BookingsModel::where(['code' => $code])->first();
        $res = ['occupied_from' => [], 'occupied_back' => []];
        if(!empty($booking)){
            $passengers = PassengersModel::where(['booking_id' => $booking->id])->get();
            foreach ($passengers as $passenger){
                $passenger->place_from ? $res['occupied_from'][] = ['passenger_id' => $passenger->id, 'place' => $passenger->place_from] : null;
                $passenger->place_back ? $res['occupied_back'][] = ['passenger_id' => $passenger->id, 'place' => $passenger->place_back] : null;
            }
        }
        return $this->outputData(200, $res);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $code)
    {
        $booking = BookingsModel::where(['code' => $code])->first();
        if(!empty($booking)){
            $validator = Validator::make($request->all(), [
                'passenger' => 'required|exists:passengers,id',
                'seat' => ['required', 'regex:/^(1|2|3|4|5|6|7|8|9|10|11|12)([A-D])$/'],
                'type' => 'required|in:from,back',
            ]);

            if($validator->fails())
                return $this->outputData(422, $validator->errors(), 'error', 'Validation error');

            $req = ['place_'.$request->type => $request->seat];
            $passenger = PassengersModel::where(['id' => $request->passenger]);
            $passenger->update($req);
            return $this->outputData(200, PassengerResource::collection($passenger->get()));
        }

        return $this->outputData(422, ['booking' => ['Field code booking']], 'error', 'Validation error');
    }
}
