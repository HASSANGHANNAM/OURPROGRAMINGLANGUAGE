<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\city;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cityController extends Controller
{
    public function create_city(Request $request)
    {
        $cityData = $request->validate([
            "City_name" => "required|unique:city|max:45|string",
        ]);
        $cityData = city::create($cityData);
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function update_city(Request $request)
    {
        $request->validate([
            "City_name" => "required|unique:city|max:45|string",
            "id" => "required"
        ]);
        city::where('id', $request->id)->update(array('City_name' => $request->City_name));
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function getAllCitys()
    {
        $citys = DB::table('city')->select('id', 'City_name')->orderBy('id', 'ASC')->get();
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $citys
        ]);
    }
}
