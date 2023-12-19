<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\productController;

class searchController extends Controller
{
    public function search_product()
    {
        $name = request()->query('name');
        $category = request()->query('category');
        if (isset($name)) {
            if (isset($category)) {
                $cat_id = DB::table('category')->where('Category_name', $category)->first();
                if (isset($cat_id)) {
                    $proDataSearch = DB::table('products')->where('marketing_name', $name)->where('category_id', $cat_id->id)->orderBy('id', 'ASC')->get();
                    if (count($proDataSearch) != 0) {
                        return response()->json([
                            "status" => 1,
                            "message" => "search",
                            "data" => $proDataSearch
                        ]);
                    }
                    return response()->json([
                        "status" => 0,
                        "message" => "not founde"
                    ]);
                }
                return response()->json([
                    "status" => 0,
                    "message" => "not founde"
                ]);
            } else {
                $proDataSearch = DB::table('products')->where('marketing_name', $name)->orderBy('id', 'ASC')->get();
                if (count($proDataSearch) != 0) {
                    return response()->json([
                        "status" => 1,
                        "message" => "search",
                        "data" => $proDataSearch
                    ]);
                }
                return response()->json([
                    "status" => 0,
                    "message" => "not founde"
                ]);
            }
        } elseif (isset($category)) {
            $cat_id = DB::table('category')->where('Category_name', $category)->first();
            $proDataSearch = DB::table('products')->where('category_id', $cat_id->id)->orderBy('id', 'ASC')->get();
            if (count($proDataSearch) != 0) {
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
        return app('App\Http\Controllers\Api\productController')->getAllProducts();
    }
}
