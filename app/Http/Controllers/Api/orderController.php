<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\order_product;
use App\Models\phatmacist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\products_warehouse;
use App\Models\warehouse;

class orderController extends Controller
{
    public function create_order(Request $request, $warehouse_id, $phatmacist_id)
    {
        $findd = warehouse::find($warehouse_id);
        $findd2 = phatmacist::find($phatmacist_id);
        if ($findd == null && $findd2 == null) {
            return response()->json([
                "status" => 0,
                "message" => "warehouse and pharmacy not found"
            ]);
        }
        if ($findd == null) {
            return response()->json([
                "status" => 0,
                "message" => "warehouse not found"
            ]);
        }
        if ($findd2 == null) {
            return response()->json([
                "status" => 0,
                "message" => "pharmacy not found"
            ]);
        }
        $ordata = [
            'warehouse_id' => $warehouse_id,
            'phatmacist_id' => $phatmacist_id,
            'status' => "In preparation",
            'payment_status' => "unpaid"
        ];
        $orDa = order::create($ordata);
        foreach ($request->p as $product) {
            // $product = $request->validate([
            //     "Quantity" => "required|integer",
            //     "product_id" => "required|integer",
            // ]);

            $check = DB::table('products')->where('id', $product['product_id'])->first();
            if ($check != null) {
                $check2 = DB::table('order_products')->where("Product_id", $product['product_id'])->where("order_id", $orDa['id'])->first();
                if ($check2 == null) {
                    $orprodata = [
                        'Quantity' => $product['Quantity'],
                        'Product_id' => $product['product_id'],
                        'order_id' => $orDa['id']
                    ];
                    $pp = order_product::create($orprodata);
                } else {
                    order_product::where('id', $check2->id)->update(array('Quantity' => $product['Quantity'] + $check2->Quantity));
                }
            } else {
                return response()->json([
                    "status" => 0,
                    "message" => "product not found"
                ]);
            }
        }
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    // TODO:    if status + delet old and create new
    public function update_order(Request $request, $warehouse_id, $phatmacist_id)
    {
        $orData = $request->validate([
            "Quantity" => "required|integer",
            "product_id" => "required|integer",
        ]);
        $ordata = [
            'warehouse_id' => $warehouse_id,
            'phatmacist_id' => $phatmacist_id,
            'status' => "In preparation",
            'payment_status' => "unpaid"
        ];
        $orDa = order::where('products_id', $orData['product_id'])->update(array('warehouse_id' => $ordata['warehouse_id'], 'phatmacist_id' => $ordata['phatmacist_id'], 'status' => $ordata['status'], 'payment_status' => $ordata['payment_status']));
        $orprodata = [
            'Quantity' => $orData['Quantity'],
            'product_id' => $orData['product_id'],
            'order_id' => $orDa['id']
        ];
        $orDa = order_product::where('order_id', $orDa['id'])->update(array('Quantity' => $orprodata['Quantity'], 'product_id' => $orprodata['product_id'], 'order_id' => $orprodata['order_id']));
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function getAllOrders($id)
    {
        //dd("sddf");
        $orData = DB::table('order')->where('phatmacist_id', $id)->orderBy('id', 'ASC')->get();
        $i = 0;
        $j = 0;
        //dd($orData);
        $allorders = [];
        foreach ($orData as $o) {
            $allorders[$i] = DB::table('order_products')->where('order_id', $o->id)->first();
            $i++;
            $j++;
        }
        // dd($allorders);
        $i = 0;
        foreach ($allorders as $or) {
            $porder[$i] = DB::table('products')->where('id', $or->Product_id)->first();
            $i++;
        }
        $i = 0;
        $waorder = [];
        foreach ($orData as $or) {
            $waorder[$i] = DB::table('warehouse')->where('id', $or->warehouse_id)->first();
            $i++;
        }
        //dd($waorder);
        //dd($orData->payment_status);
        $data = [];
        for ($k = 0; $k < $j; $k++) {
            $data[$k]['status'] = $orData[$k]->status;
            $data[$k]['payment_status'] = $orData[$k]->payment_status;
            $data[$k]['Warehouse_name'] = $waorder[$k]->Warehouse_name;
            $data[$k]['Quantity'] = $allorders[$k]->Quantity;
            $data[$k]['Price'] = $porder[$k]->Price;
            $data[$k]['image'] = $porder[$k]->image;
            $data[$k]['marketing_name'] = $porder[$k]->marketing_name;
            $data[$k]['scientific_name'] = $porder[$k]->scientific_name;
            $data[$k]['arabic_name'] = $porder[$k]->arabic_name;
            $data[$k]['exp_date'] = $porder[$k]->exp_date;
            // dd($data);
        }
        //dd($data);
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $data
        ]);
    }
    public function update_order_status(Request $request)
    {
        $orData = $request->validate([
            "order_id" => "required|integer",
            "order_status" => "required|string",
        ]);
        order::where('id', $request->order_id)->update(array('status' => $request->order_status));
        if($request->order_status=="Delivered")
        {
            
        }
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
