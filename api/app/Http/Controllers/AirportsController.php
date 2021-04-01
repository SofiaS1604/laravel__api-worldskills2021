<?php

namespace App\Http\Controllers;

use App\Http\Resources\AirportsResource;
use Illuminate\Http\Request;
use App\AirportsModel;

class AirportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $input = $_GET['query'] ?? '';
        $res = AirportsModel::where('name', 'like', '%'.$input.'%')
            ->orWhere('city', 'like', '%'.$input.'%')
            ->orWhere('iata', 'like', '%'.$input.'%')->get();

        return $this->outputData(200, ['items' => AirportsResource::collection($res)]);
    }
}
