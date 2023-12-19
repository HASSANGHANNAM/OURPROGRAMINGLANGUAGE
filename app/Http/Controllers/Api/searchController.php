<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\productController;

class searchController extends Controller
{
    public function search_product($name = null, $category = "all", Request $request)
    {
        dd("sdf");
        // $Da = $request->validate([
        //     "phatmacist_id" => "required|integer"
        // ]);
        if (isset($name) && $name != null) {
            if (isset($category) && $category != "all") {
                $cat_id = DB::table('category')->where('Category_name', $category)->first();
                $proDataSearch = DB::table('products')->where('marketing_name', $name)->where('category_id', $cat_id->id)->orderBy('id', 'ASC')->get();
                if (isset($proDataSearch)) {
                    return response()->json([
                        "status" => 1,
                        "message" => "search",
                        "data" => $proDataSearch
                    ]);
                } else {
                    return response()->json([
                        "status" => 0,
                        "message" => "not founde"
                    ]);
                    //FF
                }
            } else {
                $proDataSearch = DB::table('products')->where('marketing_name', $name)->orderBy('id', 'ASC')->get();
                if (isset($proDataSearch)) {
                    return response()->json([
                        "status" => 1,
                        "message" => "search",
                        "data" => $proDataSearch
                    ]);
                } else {
                    return response()->json([
                        "status" => 0,
                        "message" => "not founde"
                    ]);
                }
            }
        } elseif (isset($category) && $category != "all") {
            $cat_id = DB::table('category')->where('Catgory_name', $category)->first();
            $proDataSearch = DB::table('products')->where('catgory_id', $cat_id->id)->orderBy('id', 'ASC')->get();
            if (isset($proDataSearch)) {
                return response()->json([
                    "status" => 1,
                    "message" => "search",
                    "data" => $proDataSearch
                ]);
            } else {
                return response()->json([
                    "status" => 0,
                    "message" => "not founde"
                ]);
            }
        }
        return response()->json([
            "status" => 1,
            "message" => "all products ",
        ]);
    }
}
// {$cccc?}

// $proData = DB::table('products')->select('id', 'Price', 'category_id', 'made_by_id', 'image', 'marketing_name', 'scientific_name', 'arabic_name', 'exp_date')->orderBy('id', 'ASC')->get();
