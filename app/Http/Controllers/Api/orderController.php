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

use function Laravel\Prompts\table;

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
        $datarequest = $request->json()->all();
        // dd($datarequest);
        foreach ($datarequest as $product) {
            // $validatedData = $this->validate($product, [
            // "Quantity" => "required|integer",
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
    public function update_order(Request $request, $order_id)
    {
        $findd = order::find($order_id);
        if ($findd == null) {
            return response()->json([
                "status" => 0,
                "message" => "order not found"
            ]);
        }
        if ($findd->status == "Delivered") {
            return response()->json([
                "status" => 0,
                "message" => "order was Delivered"
            ]);
        }
        $datarequest = $request->json()->all();
        $cc = DB::table('order_products')->where('order_id', $order_id)->delete();
        foreach ($datarequest as $product) {
            // $validatedData = $this->validate($product, [
            // "Quantity" => "required|integer",
            //     "product_id" => "required|integer",
            // ]);
            $check = DB::table('products')->where('id', $product['product_id'])->first();
            if ($check != null) {
                $check2 = DB::table('order_products')->where("Product_id", $product['product_id'])->where("order_id", $order_id)->first();
                if ($check2 == null) {
                    $orprodata = [
                        'Quantity' => $product['Quantity'],
                        'Product_id' => $product['product_id'],
                        'order_id' => $order_id
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
    public function getAllOrders($id)
    {
        $orData = DB::table('order')->where('phatmacist_id', $id)->orderBy('id', 'ASC')->get();
        $i = 0;
        $j = 0;
        $data = [];
        foreach ($orData as $o) {
            $waorder = DB::table('warehouse')->where('id', $o->warehouse_id)->first();
            $data[$i]['status'] = $o->status;
            $data[$i]['payment_status'] = $o->payment_status;
            $data[$i]['Warehouse_name'] = $waorder->Warehouse_name;
            $allorders = DB::table('order_products')->where('order_id', $o->id)->get();
            foreach ($allorders as $or) {
                $data[$i]['products'][$j] = DB::table('products')->where('id', $or->Product_id)->first();
                $cateData = DB::table('category')->where('id',  $data[$i]['products'][$j]->id)->select('Category_name', 'Arabic_Category_name')->first();
                $madeData = DB::table('made_by')->where('id',  $data[$i]['products'][$j]->id)->select('made_by_name', 'made_by_Arabic_name')->first();
                $data[$i]['products'][$j]->made_by_name = $madeData->made_by_name;
                $data[$i]['products'][$j]->made_by_Arabic_name = $madeData->made_by_Arabic_name;
                $data[$i]['products'][$j]->Category_name = $cateData->Category_name;
                $data[$i]['products'][$j]->Arabic_Category_name =  $cateData->Arabic_Category_name;
                $j++;
            }
            $i++;
            $j = 0;
        }

        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $data
        ]);
    }
    public function getAllOrdersTowarehouse($id)
    {
        $orData = DB::table('order')->where('warehouse_id', $id)->orderBy('id', 'ASC')->get();
        $i = 0;
        $j = 0;
        $data = [];
        foreach ($orData as $o) {
            //    $waorder = DB::table('warehouse')->where('id', )->first();
            $data[$i]['status'] = $o->status;
            $data[$i]['payment_status'] = $o->payment_status;
            // $data[$i]['Warehouse_name'] = $waorder->Warehouse_name;
            $allorders = DB::table('order_products')->where('order_id', $o->id)->get();
            foreach ($allorders as $or) {
                $data[$i]['products'][$j] = DB::table('products')->where('id', $or->Product_id)->first();
                $cateData = DB::table('category')->where('id',  $data[$i]['products'][$j]->id)->select('Category_name', 'Arabic_Category_name')->first();
                $madeData = DB::table('made_by')->where('id',  $data[$i]['products'][$j]->id)->select('made_by_name', 'made_by_Arabic_name')->first();
                $data[$i]['products'][$j]->made_by_name = $madeData->made_by_name;
                $data[$i]['products'][$j]->made_by_Arabic_name = $madeData->made_by_Arabic_name;
                $data[$i]['products'][$j]->Category_name = $cateData->Category_name;
                $data[$i]['products'][$j]->Arabic_Category_name =  $cateData->Arabic_Category_name;
                $j++;
            }
            $i++;
            $j = 0;
        }
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
        $ordata = DB::table('order')->where('id', $request->order_id)->first();
        if ($request->order_status == "Delivered") {
            $productorder = DB::table('order_products')->where('order_id', $request->order_id)->get();
            foreach ($productorder as $pr) {

                $pppp = DB::table('products_warehouse')->where('products_id', $pr->Product_id)->where('warehouse_id', $ordata->warehouse_id)->first();
                products_warehouse::where('id', $pppp->id)->update(array('Quantity' => $pppp->Quantity - $pr->Quantity));
            }
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
//["On request", "In preparation", "Delivered"]
//["paid", "unpaid"]
