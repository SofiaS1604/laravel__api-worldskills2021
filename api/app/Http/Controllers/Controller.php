<?php

namespace App\Http\Controllers;

use App\UsersModel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function outputData($code, $data = null, $type = null, $message = null){
        if($type === 'error')
            return response()
                ->json(['error' => ['code' => $code, 'message' => $message] + (!empty($data) ? ['errors' => $data] : [])], $code);

        if($type === 'info')
            return response()->json($data, $code);

        return response()->json((!empty($data) ? ['data' => $data] : []), $code);
    }

    public function outputUser($request){
        $token = $request->bearerToken();
        if(!empty($token)){
            $user = UsersModel::where(['api_token' => $token])->first();
            return $user ?? null;
        }
        return null;
    }
}
