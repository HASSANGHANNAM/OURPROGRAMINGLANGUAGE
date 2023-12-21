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
            "City_Arabic_name" => "required|unique:city|max:45|string",
        ]);
        $cityData = city::create($cityData);

        if (isset($cityData)) {
            return response()->json([
                "status" => 1,
                "message" => "succes"
            ]);
        }
        return response()->json([
            "status" => 0,
            "message" => "not succes"
        ]);
    }
    // TODO: requerd in validate
    public function update_city(Request $request)
    {
        $request->validate([
            "City_name" => "required|unique:city|max:45|string",
            "City_Arabic_name" => "required|unique:city|max:45|string",
            "id" => "required"
        ]);
        $s = city::where('id', $request->id)->update(array('City_name' => $request->City_name, 'City_Arabic_name' => $request->City_Arabic_name));
        if ($s != 0) {
            return response()->json([
                "status" => 1,
                "message" => "succes"
            ]);
        }
        return response()->json([
            "status" => 0,
            "message" => "not succes"
        ]);
    }
    public function getAllCitys()
    {
        $citys = DB::table('city')->select('id', 'City_name', 'City_Arabic_name')->orderBy('id', 'ASC')->get();
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $citys
        ]);
    }
}
