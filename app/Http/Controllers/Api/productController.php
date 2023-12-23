<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\product;
use App\Models\products_warehouse;
use App\Models\favorates;
use App\Models\warehouse;

class productController extends Controller
{
    public function create_product(Request $request, $warehouse_id)
    {
        $findd = warehouse::find($warehouse_id);
        if ($findd == null) {
            return response()->json([
                "status" => 0,
                "message" => "warehouse not found"
            ]);
        }
        foreach ($request->p as $product) {
            // $product = $request->validate([
            //     "Price" => "required|integer",
            //     "category_id" => "required|integer",
            //     "made_by_id" => "required|integer",
            //     "image" => "required",
            //     "marketing_name" => "required|string|max:45",
            //     "scientific_name" => "required|string|max:45",
            //     "arabic_name" => "required|string|max:45",
            //     "exp_date" => "required", //date
            //     "Quantity" => "required|integer"
            // ]);
            $check = DB::table('products')->where('marketing_name', $product['marketing_name'])->first();
            if ($check == null) {
                // $extension = pathinfo($product['image'], PATHINFO_EXTENSION);
                $image = base64_decode($request->image);
                $extension = ".jpg"; //. $extension;
                $path = "/images/" . $request->marketing_name . $extension;
                file_put_contents(public_path($path), $image);
                $pdata = ['Price' => $product['Price'], 'category_id' => $product['category_id'], 'made_by_id' => $product['made_by_id'], 'image' => $path, 'marketing_name' => $product['marketing_name'], 'scientific_name' => $product['scientific_name'], 'arabic_name' => $product['arabic_name'], 'exp_date' => $product['exp_date']];
                $pp = product::create($pdata);
                $pwdata = ['products_id' => $pp['id'], 'warehouse_id' => $warehouse_id, 'Quantity' => $product['Quantity']];
                $pw = products_warehouse::create($pwdata);
            } else {
                $check2 = DB::table('products_warehouse')->where("warehouse_id", $warehouse_id)->where("products_id", $check->id)->first();
                if ($check2 == null) {
                    $pwdata = ['products_id' => $check->id, 'warehouse_id' => $warehouse_id, 'Quantity' => $product['Quantity']];
                    $pw = products_warehouse::create($pwdata);
                } else {
                    $check3 = products_warehouse::where('id', $check2->id)->update(array('Quantity' => $product['Quantity'] + $check2->Quantity));
                }
            }
        }
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function update_product(Request $request)
    {
        $proData = $request->validate([
            "id" => "required|integer",
            "Price" => "required|integer",
            "category_id" => "required|integer",
            "made_by_id" => "required|integer",
            "image" => "required",
            "marketing_name" => "required|string|max:45",
            "scientific_name" => "required|string|max:45",
            "arabic_name" => "required|string|max:45",
            "exp_date" => "required",
            "Quantity" => "required|integer"
        ]);
        $image = base64_decode($request->image);
        $extension = ".jpg"; //. $extension;
        $path = "/images/" . $request->marketing_name . $extension;
        file_put_contents(public_path($path), $image);
        $pp = DB::table('products')->where('id', $request->id)->first();
        product::where('id', $request->id)->update(array('Price' => $request->Price, 'category_id' => $request->category_id, 'made_by_id' => $request->made_by_id, 'image' => $path, 'marketing_name' => $request->marketing_name, 'scientific_name' => $request->scientific_name, 'arabic_name' => $request->arabic_name, 'exp_date' => $request->exp_date));
        $pwdata = products_warehouse::where('products_id', $pp->id)->update(array('Quantity' => $request->Quantity));
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function getAllProducts($phamacist_id)
    {
        $proData = DB::table('products')->select('id', 'Price', 'category_id', 'made_by_id', 'image', 'marketing_name', 'scientific_name', 'arabic_name', 'exp_date')->get();
        $warehouse_id = 1;
        foreach ($proData as $pd) {
            $madeData = DB::table('made_by')->where('id', $pd->made_by_id)->select('made_by_name', 'made_by_Arabic_name')->first();
            $cateData = DB::table('category')->where('id', $pd->category_id)->select('Category_name', 'Arabic_Category_name')->first();
            $pd->made_by_name = $madeData->made_by_name;
            $pd->made_by_Arabic_name = $madeData->made_by_Arabic_name;
            $pd->Category_name = $cateData->Category_name;
            $pd->Arabic_Category_name = $cateData->Arabic_Category_name;
            $ch = DB::table('favorates')->where('products_id', $pd->id)->where('phamacist_id', $phamacist_id)->first();
            $ch2 = DB::table('products_warehouse')->where('products_id', $pd->id)->where('warehouse_id', $warehouse_id)->first();
            if (isset($ch)) {
                $pd->favorates = true;
            } else {
                $pd->favorates = false;
            }
            if (isset($ch2)) {
                $pd->Quantity = $ch2->Quantity;
            } else {
                $pd->Quantity = 0;
            }
        }
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $proData
        ]);
    }
    public function getAllProductsToWarehouse($warehouse_id)
    {
        $proData = DB::table('products')->select('id', 'Price', 'category_id', 'made_by_id', 'image', 'marketing_name', 'scientific_name', 'arabic_name', 'exp_date')->get();
        foreach ($proData as $pd) {
            // dd("asd");
            $madeData = DB::table('made_by')->where('id', $pd->made_by_id)->select('made_by_name', 'made_by_Arabic_name')->first();
            $cateData = DB::table('category')->where('id', $pd->category_id)->select('Category_name', 'Arabic_Category_name')->first();
            $pd->made_by_name = $madeData->made_by_name;
            $pd->made_by_Arabic_name = $madeData->made_by_Arabic_name;
            $pd->Category_name = $cateData->Category_name;
            $pd->Arabic_Category_name = $cateData->Arabic_Category_name;
        }
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $proData
        ]);
    }
    public function getSingleProduct($phamacist_id, $products_id)
    {
        $proData = DB::table('products')->select('id', 'Price', 'category_id', 'made_by_id', 'image', 'marketing_name', 'scientific_name', 'arabic_name', 'exp_date')->first();
        $ch = DB::table('favorates')->where('products_id', $phamacist_id)->where('phamacist_id', $phamacist_id)->first();
        if (isset($ch)) {
            $proData->favorates = true;
        } else {
            $proData->favorates = false;
        }
        $madeData = DB::table('made_by')->where('id', $proData->made_by_id)->select('made_by_name', 'made_by_Arabic_name')->first();
        $cateData = DB::table('category')->where('id', $proData->category_id)->select('Category_name', 'Arabic_Category_name')->first();
        $proData->made_by_name = $madeData->made_by_name;
        $proData->made_by_Arabic_name = $madeData->made_by_Arabic_name;
        $proData->Category_name = $cateData->Category_name;
        $proData->Arabic_Category_name = $cateData->Arabic_Category_name;
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $proData
        ]);
    }
    public function putInfavorates(Request $request)
    {
        $proData = $request->validate([
            "phamacist_id" => "required|integer",
            "products_id" => "required|integer",
        ]);
        $favData = favorates::create($proData);
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function getAllFavorates(Request $request)
    {
        $favData = $request->validate([
            "phamacist_id" => "required|integer",
        ]);
        $favPh = DB::table('favorates')->where('phamacist_id', $favData['phamacist_id'])->get();
        $i = 0;
        foreach ($favPh as $f) {
            $fda[$i] =  DB::table('products')->where('id', $f->products_id)->first();
            $i++;
        }
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $fda
        ]);
    }
    public function deleteFavorates(Request $request, $id)
    {
        $favData = $request->validate([
            "phamacist_id" => "required|integer",
        ]);
        $favPh = DB::table('favorates')->where('phamacist_id', $favData['phamacist_id'])->where('products_id', $id)->delete();
        return response()->json([
            "status" => 1,
            "message" => "succes",
        ]);
    }
}
