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
            "Warehouse_name" => "required|unique:warehouse|max:45|string",
            "id" => "required",
            "city_id" => "required|integer",
            "location" => "required|max:45|string"
        ]);
        warehouse::where('id', $request->id)->update(array('Warehouse_name' => $request->Warehouse_name));
        location::where('id', $request->id)->update(array('city_id' => $request->city_id));
        location::where('id', $request->id)->update(array('address' => $request->location));
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
}
