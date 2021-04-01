<?php

namespace App\Http\Controllers;

use App\BookingsModel;
use App\Http\Resources\BookingResource;
use App\UsersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $this->outputUser($request);
        if(!empty($user)){
            unset($user->password, $user->id, $user->api_token, $user->created_at, $user->updated_at);
            return $this->outputData(200, $user, 'info');
        }
        return $this->outputData(401, null, 'error', 'Unauthorized');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required', 'last_name' => 'required',
            'document_number' => 'required|unique:users',
            'phone' => 'required|numeric|digits:10', 'password' => 'required',
        ]);

        if($validator->fails())
            return $this->outputData(422, $validator->errors(),'error',  'Validation error');

        UsersModel::created($request->all());
        return $this->outputData(204);
    }

    public function auth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:11', 'password' => 'required',
        ]);

        if($validator->fails())
            return $this->outputData(422, $validator->errors(),'error',  'Validation error');

        if($user = UsersModel::where(['phone' => $request->phone, 'password' => $request->password])->first())
            return $this->outputData(200, ['token' => $user->saveToken($user)]);

        return $this->outputData(401, ['phone' => ['phone or password incorrect']], 'error', 'Unauthorized');
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = $this->outputUser($request);
        if(!empty($user)){
            $bookings = BookingsModel::whereHas('passengers', function ($query) use ($user){
                $query->where(['document_number' => $user->document_number]);
            })->get();

            $bookings = BookingResource::collection($bookings);
            return $this->outputData(200, $bookings);
        }
        return $this->outputData(401, null, 'error', 'Unauthorized');
    }
}
