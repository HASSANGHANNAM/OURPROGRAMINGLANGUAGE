<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product_basket_products;

class basketController extends Controller
{
    public function putInProduct_basket(Request $request)
    {
        $proData = $request->validate([
            "phamacist_id" => "required|integer",
            "product_id" => "required|integer",
            "Quantity" => "required|integer"
        ]);
        $pbPh = DB::table('Product_basket')->where('phatmacist_id', $request->phamacist_id)->first();

        if ($pbPh == null) {
            return response()->json([
                "status" => 0,
                "message" => "Product_basket not found "
            ]);
        } else {
            $pbbPh = DB::table('Product_basket_products')->where('Product_basket_id', $pbPh->id)->where('Product_id', $request->product_id)->first();
            if ($pbbPh != null) {
                Product_basket_products::where('id', $pbbPh->id)->update(array('Quantity' => $request->Quantity + $pbbPh->Quantity));
            } else {
                $proData = [
                    "Product_basket_id" => $pbPh->id,
                    "Product_id" => $request->product_id,
                    "Quantity" => $request->Quantity
                ];
                Product_basket_products::create($proData);
            }
        }
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function deleteProduct_basket(Request $request, $id)
    {
        $ppData = $request->validate([
            "phamacist_id" => "required|integer",
        ]);
        $pbPh = DB::table('Product_basket')->where('phatmacist_id', $ppData['phamacist_id'])->first();
        $p = DB::table('Product_basket_products')->where('Product_basket_id', $pbPh->id)->where('Product_id', $id)->delete();
        return response()->json([
            "status" => 1,
            "message" => "succes",
        ]);
    }
    public function getAllProductsInbasket($phamacist_id)
    {
        $pbPh = DB::table('Product_basket')->where('phatmacist_id', $phamacist_id)->first();
        if ($pbPh == null) {
            return response()->json([
                "status" => 0,
                "message" => "Product_basket not found "
            ]);
        }
        $proData = DB::table('Product_basket_products')->where('Product_basket_id', $pbPh->id)->get();
        if (count($proData) == 0) {
            return response()->json([
                "status" => 1,
                "message" => "no products Product_basket "
            ]);
        }
        foreach ($proData as $pd) {
            $pdData = DB::table('products')->where('id', $pd->Product_id)->select('id', 'Price', 'category_id', 'made_by_id', 'image', 'marketing_name', 'scientific_name', 'arabic_name', 'exp_date')->first();
            $madeData = DB::table('made_by')->where('id', $pdData->made_by_id)->select('made_by_name', 'made_by_Arabic_name')->first();
            $cateData = DB::table('category')->where('id', $pdData->category_id)->select('Category_name', 'Arabic_Category_name')->first();
            $pd->made_by_name = $madeData->made_by_name;
            $pd->made_by_Arabic_name = $madeData->made_by_Arabic_name;
            $pd->Category_name = $cateData->Category_name;
            $pd->Arabic_Category_name = $cateData->Arabic_Category_name;
            $pd->Price = $pdData->Price;
            $pd->PriceAllproducts = $pdData->Price * $pd->Quantity;
            $pd->image = $pdData->image;
            $pd->marketing_name = $pdData->marketing_name;
            $pd->scientific_name = $pdData->scientific_name;
            $pd->arabic_name = $pdData->arabic_name;
            $pd->exp_date = $pdData->exp_date;
            if ($phamacist_id != null) {
                $ch = DB::table('favorates')->where('products_id', $pdData->id)->where('phamacist_id', $phamacist_id)->first();
                if (isset($ch)) {
                    $pd->favorates = true;
                } else {
                    $pd->favorates = false;
                }
            }
        }
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $proData
        ]);
    }
}
