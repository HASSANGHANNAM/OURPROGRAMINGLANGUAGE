<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\order_product;
use App\Models\phatmacist;
use App\Models\Product_basket;
use App\Models\Product_basket_products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\products_warehouse;
use App\Models\warehouse;

use function Laravel\Prompts\table;

class orderController extends Controller
{
    // old funcyion
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

    public function create_order_from_basket($warehouse_id, $phatmacist_id)
    {
        $findd = warehouse::find($warehouse_id);
        $findd2 = phatmacist::find($phatmacist_id);
        $findd3 = Product_basket::find($phatmacist_id);
        $data = [];
        if ($findd == null && $findd2 == null) {
            return response()->json([
                "status" => 0,
                "message" => "warehouse and pharmacy not found",
                "data" => $data
            ]);
        }
        if ($findd == null) {
            return response()->json([
                "status" => 0,
                "message" => "warehouse not found",
                "data" => $data
            ]);
        }
        if ($findd2 == null) {
            return response()->json([
                "status" => 0,
                "message" => "pharmacy not found",
                "data" => $data

            ]);
        }
        // TODO:  coment sum all  product in order
        $allorderinwarehouse = DB::table('order')->where('warehouse_id', $warehouse_id)->get();
        $sumallpro = [];
        $i = 0;
        $j = 0;
        foreach ($allorderinwarehouse as $order) {
            $products = DB::table('order_products')->where('order_id', $order->id)->get();
            foreach ($products as $pro) {
                $check = false;
                foreach ($sumallpro as $pr) {
                    if ($pr->Product_id == $pro->Product_id) {
                        $pr->Quantity += $pro->Quantity;
                        $check = true;
                    }
                }
                if ($check == false) {
                    $sumallpro[$i++] = $pro;
                }
            }
        }
        // TODO: end
        $proData = DB::table('Product_basket_products')->where('Product_basket_id', $findd3->id)->get();
        $ordata = [
            'warehouse_id' => $warehouse_id,
            'phatmacist_id' => $phatmacist_id,
            'status' => "In preparation",
            'payment_status' => "unpaid"
        ];
        $orDa = order::create($ordata);
        $checkiforderfull = false;
        foreach ($proData as $product) {
            $checWarehouse = DB::table('products_warehouse')->where('warehouse_id', $warehouse_id)->where('products_id', $product->Product_id)->first();
            $check = DB::table('products')->where('id', $product->Product_id)->first();
            if ($check != null) {
                $check2 = DB::table('order_products')->where("Product_id", $product->Product_id)->where("order_id", $orDa['id'])->first();
                $q = 0;
                foreach ($sumallpro as $pr) {
                    if ($pr->Product_id ==  $product->Product_id) {
                        $q = $pr->Quantity;
                    }
                }
                if ($checWarehouse->Quantity - $q < $product->Quantity) {
                    if ($checWarehouse->Quantity - $q != 0) {
                        $checkiforderfull = true;
                        $orprodata = [
                            'Quantity' => $checWarehouse->Quantity - $q,
                            'Product_id' => $product->Product_id,
                            'order_id' => $orDa['id']
                        ];
                        $pp = order_product::create($orprodata);
                        $pbPh = DB::table('Product_basket')->where('phatmacist_id', $phatmacist_id)->first();
                        $p = Product_basket_products::where('Product_basket_id', $pbPh->id)->where('Product_id', $product->Product_id)->update(array('Quantity' =>  $product->Quantity - $checWarehouse->Quantity + $q));
                        $data[$j] = $check;
                        $data[$j]->Quantity = $product->Quantity - $checWarehouse->Quantity + $q;
                        $j++;
                    } else {
                        $data[$j] = $check;
                        $data[$j]->Quantity = $product->Quantity;
                        $j++;
                    }
                } else {
                    if ($product->Quantity > 0) {
                        $checkiforderfull = true;
                        $orprodata = [
                            'Quantity' => $product->Quantity,
                            'Product_id' => $product->Product_id,
                            'order_id' => $orDa['id']
                        ];
                        $pp = order_product::create($orprodata);
                        $pbPh = DB::table('Product_basket')->where('phatmacist_id', $phatmacist_id)->first();
                        $p = DB::table('Product_basket_products')->where('Product_basket_id', $pbPh->id)->where('Product_id', $product->Product_id)->delete();
                    }
                }
            } else {
                return response()->json([
                    "status" => 0,
                    "message" => "product not found",
                    "data" => $data
                ]);
            }
        }
        if ($checkiforderfull == false) {
            DB::table('order')->where('id', $orDa['id'])->delete();
            return response()->json([
                "status" => 1,
                "message" => "order not create because no product to ordered it",
                "data" => $data
            ]);
        }
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $data
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
            if ($o->status == "On request") {
                $data[$i]['status'] = 1;
            } else if ($o->status == "In preparation") {
                $data[$i]['status'] = 2;
            } else if ($o->status ==  "Delivered") {
                $data[$i]['status'] = 3;
            }
            if ($o->payment_status == "paid") {
                $data[$i]['payment_status'] = 1;
            } else if ($o->payment_status == "unpaid") {
                $data[$i]['payment_status'] = 2;
            }
            $data[$i]['Warehouse_name'] = $waorder->Warehouse_name;
            $PriceAllproducts = 0;
            $allorders = DB::table('order_products')->where('order_id', $o->id)->get();
            foreach ($allorders as $or) {
                $data[$i]['products'][$j] = DB::table('products')->where('id', $or->Product_id)->first();
                $cateData = DB::table('category')->where('id',  $data[$i]['products'][$j]->id)->select('Category_name', 'Arabic_Category_name')->first();
                $madeData = DB::table('made_by')->where('id',  $data[$i]['products'][$j]->id)->select('made_by_name', 'made_by_Arabic_name')->first();
                $data[$i]['products'][$j]->made_by_name = $madeData->made_by_name;
                $data[$i]['products'][$j]->made_by_Arabic_name = $madeData->made_by_Arabic_name;
                $data[$i]['products'][$j]->Category_name = $cateData->Category_name;
                $data[$i]['products'][$j]->Arabic_Category_name =  $cateData->Arabic_Category_name;
                $data[$i]['products'][$j]->Quantity = $or->Quantity;
                $ch = DB::table('favorates')->where('products_id',  $or->Product_id)->where('phamacist_id', $id)->first();
                if (isset($ch)) {
                    $data[$i]['products'][$j]->favorates = true;
                } else {
                    $data[$i]['products'][$j]->favorates = false;
                }
                $data[$i]['products'][$j]->PriceAllproducts = $or->Quantity * $data[$i]['products'][$j]->Price;
                $PriceAllproducts += $data[$i]['products'][$j]->PriceAllproducts;
                $j++;
            }
            $data[$i]['PriceAllproducts'] = $PriceAllproducts;
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
            if ($o->status == "On request") {
                $data[$i]['status'] = 1;
            } else if ($o->status == "In preparation") {
                $data[$i]['status'] = 2;
            } else if ($o->status ==  "Delivered") {
                $data[$i]['status'] = 3;
            }
            if ($o->payment_status == "paid") {
                $data[$i]['payment_status'] = 1;
            } else if ($o->payment_status == "unpaid") {
                $data[$i]['payment_status'] = 2;
            }
            $PriceAllproducts = 0;
            $allorders = DB::table('order_products')->where('order_id', $o->id)->get();
            foreach ($allorders as $or) {
                $data[$i]['products'][$j] = DB::table('products')->where('id', $or->Product_id)->first();
                $cateData = DB::table('category')->where('id',  $data[$i]['products'][$j]->id)->select('Category_name', 'Arabic_Category_name')->first();
                $madeData = DB::table('made_by')->where('id',  $data[$i]['products'][$j]->id)->select('made_by_name', 'made_by_Arabic_name')->first();
                $data[$i]['products'][$j]->made_by_name = $madeData->made_by_name;
                $data[$i]['products'][$j]->made_by_Arabic_name = $madeData->made_by_Arabic_name;
                $data[$i]['products'][$j]->Category_name = $cateData->Category_name;
                $data[$i]['products'][$j]->Arabic_Category_name =  $cateData->Arabic_Category_name;
                $data[$i]['products'][$j]->Quantity = $or->Quantity;
                $data[$i]['products'][$j]->PriceAllproducts = $or->Quantity * $data[$i]['products'][$j]->Price;
                $PriceAllproducts += $data[$i]['products'][$j]->PriceAllproducts;
                $j++;
            }
            $data[$i]['PriceAllproducts'] = $PriceAllproducts;
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
            "order_status" => "required|integer",
        ]);
        //["On request", "In preparation", "Delivered"]
        if ($request->order_status == 1)
            order::where('id', $request->order_id)->update(array('status' => "On request"));
        else 
        if ($request->order_status == 2) {
            order::where('id', $request->order_id)->update(array('status' =>  "In preparation"));
        } else if ($request->order_status == 3) {
            order::where('id', $request->order_id)->update(array('status' => "Delivered"));
        }
        $ordata = DB::table('order')->where('id', $request->order_id)->first();
        if ($request->order_status == 3) {
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
            "order_payment_status" => "required|integer",
        ]);
        //["paid", "unpaid"]
        if ($request->order_payment_status == 1) {
            order::where('id', $request->order_id)->update(array('payment_status' => "paid"));
        } else if ($request->order_payment_status == 2) {
            order::where('id', $request->order_id)->update(array('payment_status' => "unpaid"));
        }
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
}
//["On request", "In preparation", "Delivered"]
//["paid", "unpaid"]
