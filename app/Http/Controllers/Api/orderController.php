<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class orderController extends Controller
{
    public function create_order(Request $request)
    {
        $orData = $request->validate([
            "warehouse_id" => "required|integer",
            "Price" => "required|integer",
            "category_id" => "required|integer",
            "made_by_id" => "required|integer",
            "image" => "required|string",
            "marketing_name" => "required|string|max:45",
            "scientific_name" => "required|string|max:45",
            "arabic_name" => "required|string|max:45",
            "exp_date" => "required", //date
            "Quantity" => "required|integer"
        ]);
        $pdata = ['Price' => $proData['Price'], 'category_id' => $proData['category_id'], 'made_by_id' => $proData['made_by_id'], 'image' => $proData['image'], 'marketing_name' => $proData['marketing_name'], 'scientific_name' => $proData['scientific_name'], 'arabic_name' => $proData['arabic_name'], 'exp_date' => $proData['exp_date']];
        $pp = product::create($pdata);
        $pwdata = ['products_id' => $pp['id'], 'warehouse_id' => $proData['warehouse_id'], 'Quantity' => $proData['Quantity']];
        $pw = products_warehouse::create($pwdata);
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }

    public function update_order(Request $request)
    {
        $proData = $request->validate([
            "Price" => "required|integer",
            "category_id" => "required|integer",
            "made_by_id" => "required|integer",
            "image" => "required|string",
            "marketing_name" => "required|string|max:45",
            "scientific_name" => "required|string|max:45",
            "'arabic_name" => "required|string|max:45",
            "exp_date" => "required", //date
            "Quantity" => "required|integer"
        ]);
        $pdata = ['Price' => $proData['Price'], 'category_id' => $proData['category_id'], 'made_by_id' => $proData['made_by_id'], 'image' => $proData['image'], 'marketing_name' => $proData['marketing_name'], 'scientific_name' => $proData['scientific_name'], 'arabic_name' => $proData['arabic_name'], 'exp_date' => $proData['exp_date']];
        $pp = product::create($pdata);
        $pwdata = ['products_id' => $pdata['id'], 'warehouse_id' => 1, 'Quantity' => $proData['Quantity']];
        $pw = products_warehouse::create($pwdata);
        //city::where('id', $request->id)->update(array('City_name' => $request->name));
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function getAllOrders()
    {
        $proData = DB::table('products')->select('id', 'Price', 'category_id', 'made_by_id', 'image', 'marketing_name', 'scientific_name', 'arabic_name', 'exp_date')->orderBy('id', 'ASC')->get();
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $proData
        ]);
    }
    public function update_order_status(Request $request)
    {
        $proData = $request->validate([
            "Price" => "required|integer",
            "category_id" => "required|integer",
            "made_by_id" => "required|integer",
            "image" => "required|string",
            "marketing_name" => "required|string|max:45",
            "scientific_name" => "required|string|max:45",
            "'arabic_name" => "required|string|max:45",
            "exp_date" => "required", //date
            "Quantity" => "required|integer"
        ]);
        $pdata = ['Price' => $proData['Price'], 'category_id' => $proData['category_id'], 'made_by_id' => $proData['made_by_id'], 'image' => $proData['image'], 'marketing_name' => $proData['marketing_name'], 'scientific_name' => $proData['scientific_name'], 'arabic_name' => $proData['arabic_name'], 'exp_date' => $proData['exp_date']];
        $pp = product::create($pdata);
        $pwdata = ['products_id' => $pdata['id'], 'warehouse_id' => 1, 'Quantity' => $proData['Quantity']];
        $pw = products_warehouse::create($pwdata);
        //city::where('id', $request->id)->update(array('City_name' => $request->name));
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function update_order_status_payment(Request $request)
    {
        $proData = $request->validate([
            "Price" => "required|integer",
            "category_id" => "required|integer",
            "made_by_id" => "required|integer",
            "image" => "required|string",
            "marketing_name" => "required|string|max:45",
            "scientific_name" => "required|string|max:45",
            "'arabic_name" => "required|string|max:45",
            "exp_date" => "required", //date
            "Quantity" => "required|integer"
        ]);
        $pdata = ['Price' => $proData['Price'], 'category_id' => $proData['category_id'], 'made_by_id' => $proData['made_by_id'], 'image' => $proData['image'], 'marketing_name' => $proData['marketing_name'], 'scientific_name' => $proData['scientific_name'], 'arabic_name' => $proData['arabic_name'], 'exp_date' => $proData['exp_date']];
        $pp = product::create($pdata);
        $pwdata = ['products_id' => $pdata['id'], 'warehouse_id' => 1, 'Quantity' => $proData['Quantity']];
        $pw = products_warehouse::create($pwdata);
        //city::where('id', $request->id)->update(array('City_name' => $request->name));
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
}
