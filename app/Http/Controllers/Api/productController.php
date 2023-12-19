<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\product;
use App\Models\products_warehouse;
use App\Models\favorates;

// TODO: image 
class productController extends Controller
{
    public function create_product(Request $request)
    {
        //$newImageName=uniqid().'-'. $request->title.'-'.$request->image->extension();
        //$request->image->move(public_path('image'),$newImageName);
        $proData = $request->validate([
            "warehouse_id" => "required|integer",
            "Price" => "required|integer",
            "category_id" => "required|integer",
            "made_by_id" => "required|integer",
            "image" => "requierd|string|mimes:jpg,png,jped|max:5048",
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
    // TODO:  // bug in query db to update
    public function update_product(Request $request)
    {
        //base64_decode($encoded_image);
        //$newImageName=uniqid().'-'. $request->title.'-'.$request->image->extension();
        //$request->image->move(public_path('image'),$newImageName);
        $proData = $request->validate([
            "Price" => "required|integer",
            "category_id" => "required|integer",
            "made_by_id" => "required|integer",
            "image" => "required|string",
            "marketing_name" => "required|string|max:45",
            "scientific_name" => "required|string|max:45",
            "'arabic_name" => "required|string|max:45",
            "exp_date" => "required",
            "Quantity" => "required|integer"
        ]);
        $imgInPublic = base64_decode($proData['image']);
        dd($imgInPublic);
        // $imgPath = $imgInPublic->move(public_path('imgInPublic'), $proData['scientific_name']);
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
            "message" => "succes" //, 'access_token' => $accessToken
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