<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class searchController extends Controller
{
    public function search_product($name = null, $category = "all", Request $request)
    {
        $Da = $request->validate([
            "phatmacist_id" => "required|integer"
        ]);
        if (isset($name) && $name != null) {
            if (isset($category) && $category != "all") {
                $cat_id = DB::table('category')->where('Catgory_name', $category)->first();
                $proDataSearch = DB::table('products')->where('marketing_name', $name)->where('catgory_id', $cat_id->id)->orderBy('id', 'ASC')->get();
                return response()->json([
                    "status" => 1,
                    "message" => "search",
                    "data" => $proDataSearch
                ]);
            } else {
                $proDataSearch = DB::table('products')->where('marketing_name', $name)->orderBy('id', 'ASC')->get();
                return response()->json([
                    "status" => 1,
                    "message" => "search",
                    "data" => $proDataSearch
                ]);
            }
        } elseif (isset($category) && $category != "all") {
            $cat_id = DB::table('category')->where('Catgory_name', $category)->first();
            $proDataSearch = DB::table('products')->where('catgory_id', $cat_id->id)->orderBy('id', 'ASC')->get();
            return response()->json([
                "status" => 1,
                "message" => "search",
                "data" => $proDataSearch
            ]);
        }
        return response()->json([
            "status" => 1,
            "message" => "all products "
        ]);
    }
}
// {$cccc?}

// $proData = DB::table('products')->select('id', 'Price', 'category_id', 'made_by_id', 'image', 'marketing_name', 'scientific_name', 'arabic_name', 'exp_date')->orderBy('id', 'ASC')->get();
