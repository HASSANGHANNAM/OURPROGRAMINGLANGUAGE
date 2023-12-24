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
    public function update_city(Request $request)
    {
        $request->validate([
            "id" => "required"
        ]);

        $c = DB::table('city')->where('id', $request->id)->select('id', 'City_name', 'City_Arabic_name')->first();
        if ($c == null) {
            return response()->json([
                "status" => 0,
                "message" => "city not found "
            ]);
        }
        if ($c->City_name == $request->City_name && $c->City_Arabic_name == $request->City_Arabic_name) {
            return response()->json([
                "status" => 1,
                "message" => "succes"
            ]);
        }
        if ($c->City_name == $request->City_name) {
            $request->validate([
                "City_name" => "required|max:45|string",
                "City_Arabic_name" => "required|unique:city|max:45|string",
            ]);
            $c = city::where('id', $request->id)->update(array('City_name' => $request->City_name, 'City_Arabic_name' => $request->City_Arabic_name));
            if ($c != 0) {
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
        if ($c->City_Arabic_name == $request->City_Arabic_name) {
            $request->validate([
                "City_name" => "|unique:city|required|max:45|string",
                "City_Arabic_name" => "required|max:45|string",
            ]);
            $c = city::where('id', $request->id)->update(array('City_name' => $request->City_name, 'City_Arabic_name' => $request->City_Arabic_name));
            if ($c != 0) {
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
        $request->validate([
            "City_name" => "required|unique:city|max:45|string",
            "City_Arabic_name" => "required|unique:city|max:45|string",
            "id" => "required"
        ]);
        $c = city::where('id', $request->id)->update(array('City_name' => $request->City_name, 'City_Arabic_name' => $request->City_Arabic_name));
        if ($c != 0) {
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
