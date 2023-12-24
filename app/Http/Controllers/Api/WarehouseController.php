<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\warehouse;
use App\Models\User;
use App\Models\owner;
use App\Models\city;
use App\Models\location;
use App\Models\phatmacist;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    public function createWarehouse(Request $request)
    {
        $request->validate([
            "owner_id" => "required|integer",
            "Warehouse_name" => "required|unique:warehouse|max:45|string",
            "city_id" => "required|integer",
            "location" => "required|max:45|string"
        ]);
        $ow = DB::table('owner')->where('id', $request->owner_id)->first();
        if ($ow == null) {
            return response()->json([
                "status" => 0,
                "message" => "owner not found"
            ]);
        }
        if ($ow->status == "acceptable") {
            $locationData = [
                'city_id' => $request->city_id,
                'address' => $request->location,
            ];
            $location = location::create($locationData);
            $warehouseData = [
                'Warehouse_name' => $request->Warehouse_name,
                'location_id' => $location['id'],
                'owner_id' => $request->owner_id
            ];
            $warehouse = warehouse::create($warehouseData);
            return response()->json([
                "status" => 1,
                "message" => "succes"
            ]);
        }
        return response()->json([
            "status" => 0,
            "message" => "status of owner .$ow->status."
        ]);
    }
    public function updateWarehouse(Request $request)
    {
        $request->validate([
            "id" => "required",
            "city_id" => "required|integer",
            "location" => "required|max:45|string"
        ]);
        $c = DB::table('warehouse')->where('id', $request->id)->select('id', 'Warehouse_name', 'location_id')->first();
        if ($c == null) {
            return response()->json([
                "status" => 0,
                "message" => "warehouse not found "
            ]);
        }
        $c2 = DB::table('location')->where('id', $c->id)->select('id', 'address', 'city_id')->first();
        if ($c->Warehouse_name == $request->Warehouse_name) {
            $request->validate([
                "Warehouse_name" => "required|max:45|string",
            ]);
        } else {
            $request->validate([
                "Warehouse_name" => "required|unique:warehouse|max:45|string",
            ]);
        }
        warehouse::where('id', $request->id)->update(array('Warehouse_name' => $request->Warehouse_name));
        location::where('id', $request->id)->update(array('city_id' => $request->city_id, 'address' => $request->location));
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
}
