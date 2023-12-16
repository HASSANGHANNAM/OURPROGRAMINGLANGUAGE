<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\order_product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class orderController extends Controller
{
    public function create_order(Request $request)
    {
        $orData = $request->validate([
            "warehouse_id" => "required|integer",
            "phatmacist_id" => "required|integer",
            "Quantity" => "required|integer",
            "product_id" => "required|integer",
        ]);
        $ordata = [
            'warehouse_id' => $orData['warehouse_id'],
            'phatmacist_id' => $orData['phatmacist_id'],
            'status' => "In preparation",
            'payment_status' => "unpaid"
        ];
        $orDa = order::create($ordata);
        $orprodata = [
            'Quantity' => $orData['Quantity'],
            'product_id' => $orData['product_id'],
            'order_id' => $orDa['id']
        ];
        $pp = order_product::create($orprodata);
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    // TODO:
    public function update_order(Request $request)
    {
        $orData = $request->validate([
            "warehouse_id" => "required|integer",
            "phatmacist_id" => "required|integer",
            "Quantity" => "required|integer",
            "product_id" => "required|integer",
        ]);
        $ordata = [
            'warehouse_id' => $orData['warehouse_id'],
            'phatmacist_id' => $orData['phatmacist_id'],
            'status' => "In preparation",
            'payment_status' => "unpaid"
        ];
        $orDa = order::create($ordata);
        $orprodata = [
            'Quantity' => $orData['Quantity'],
            'product_id' => $orData['product_id'],
            'order_id' => $orDa['id']
        ];
        $pp = order_product::create($orprodata);
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    // TODO:
    public function getAllOrders($id)
    {
        $orData = DB::table('order')->where('phatmacist_id', $id)->orderBy('order_id', 'ASC')->get();
        $allorders = [];
        foreach ($orData as $o) {
            $allorders += DB::table('orderProducts')->where('order_id', $o->id)->get();
        }
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $orData + $allorders
        ]);
    }
    public function update_order_status(Request $request)
    {
        $orData = $request->validate([
            "order_id" => "required|integer",
            "order_status" => "required|string",
        ]);
        order::where('id', $request->order_id)->update(array('status' => $request->order_status));
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function update_order_status_payment(Request $request)
    {
        $orData = $request->validate([
            "order_id" => "required|integer",
            "order_payment_status" => "required|string",
        ]);
        order::where('id', $request->order_id)->update(array('payment_status' => $request->order_payment_status));
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
}
