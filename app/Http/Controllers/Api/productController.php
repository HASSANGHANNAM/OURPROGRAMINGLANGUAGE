<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\product;
use App\Models\products_warehouse;
use App\Models\favorates;

class productController extends Controller
{
    public function create_product(Request $request)
    {
        $proData = $request->validate([
            "warehouse_id" => "required|integer",
            "Price" => "required|integer",
            "category_id" => "required|integer",
            "made_by_id" => "required|integer",
            "image" => "required",
            "marketing_name" => "required|string|max:45",
            "scientific_name" => "required|string|max:45",
            "arabic_name" => "required|string|max:45",
            "exp_date" => "required", //date
            "Quantity" => "required|integer"
        ]);

        // $extension = pathinfo($product['image'], PATHINFO_EXTENSION);
        $image = base64_decode($request->image);
        $extension = ".jpg"; //. $extension;
        $path1 = "/images/" . $request->marketing_name . $extension;
        $path = "public" . $path1;
        file_put_contents(public_path($path1), $image);
        $pdata = ['Price' => $proData['Price'], 'category_id' => $proData['category_id'], 'made_by_id' => $proData['made_by_id'], 'image' => $path, 'marketing_name' => $proData['marketing_name'], 'scientific_name' => $proData['scientific_name'], 'arabic_name' => $proData['arabic_name'], 'exp_date' => $proData['exp_date']];
        $pp = product::create($pdata);
        $pwdata = ['products_id' => $pp['id'], 'warehouse_id' => $proData['warehouse_id'], 'Quantity' => $proData['Quantity']];
        $pw = products_warehouse::create($pwdata);
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
        $path1 = "/images/" . $request->marketing_name . $extension;
        $path = "public" . $path1;
        file_put_contents(public_path($path1), $image);
        $pp = DB::table('products')->where('id', $request->id)->first();
        product::where('id', $request->id)->update(array('Price' => $request->Price, 'category_id' => $request->category_id, 'made_by_id' => $request->made_by_id, 'image' => $path, 'marketing_name' => $request->marketing_name, 'scientific_name' => $request->scientific_name, 'arabic_name' => $request->arabic_name, 'exp_date' => $request->exp_date));
        $pwdata = products_warehouse::where('products_id', $pp->id)->update(array('Quantity' => $request->Quantity));
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function getAllProducts()
    {
        $proData = DB::table('products')->select('id', 'Price', 'category_id', 'made_by_id', 'image', 'marketing_name', 'scientific_name', 'arabic_name', 'exp_date')->orderBy('id', 'ASC')->get();
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $proData
        ]);
    }
    // TODO:
    // لا تنسى التعديل حتى يرجعلك اذا كان مفضل او لا
    public function getProducts()
    {
        $proData = DB::table('products')->select('id', 'Price',  'image', 'marketing_name', 'arabic_name')->orderBy('id', 'ASC')->get();
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
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $favPh
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
            "data" => $favPh
        ]);
    }
}
