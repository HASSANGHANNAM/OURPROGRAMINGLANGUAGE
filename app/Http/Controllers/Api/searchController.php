<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\productController;
// TODO: substring search
class searchController extends Controller
{
    public function search_product()
    {
        $name = request()->query('name');
        $category = request()->query('category');
        $phamacist_id = request()->query('phamacist_id');
        if (isset($name)) {
            if (isset($category)) {
                $cat_id = DB::table('category')->where('Category_name', $category)->first();
                if ($cat_id == null) {
                    $cat_id = DB::table('category')->where('Arabic_Category_name', $category)->first();
                }
                if (isset($cat_id)) {
                    //'keywords','like',"php"
                    $proDataSearch = DB::table('products')->where('marketing_name', $name)->where('category_id', $cat_id->id)->orderBy('id', 'ASC')->get();
                    if (count($proDataSearch) == 0) {
                        $proDataSearch = DB::table('products')->where('arabic_name', $name)->where('category_id', $cat_id->id)->orderBy('id', 'ASC')->get();
                    }
                    if (count($proDataSearch) == 0) {
                        $proDataSearch = DB::table('products')->where('scientific_name', $name)->where('category_id', $cat_id->id)->orderBy('id', 'ASC')->get();
                    }
                    if (count($proDataSearch) != 0) {

                        foreach ($proDataSearch as $pd) {
                            $madeData = DB::table('made_by')->where('id', $pd->made_by_id)->select('made_by_name', 'made_by_Arabic_name')->first();
                            $cateData = DB::table('category')->where('id', $pd->category_id)->select('Category_name', 'Arabic_Category_name')->first();
                            $pd->made_by_name = $madeData->made_by_name;
                            $pd->made_by_Arabic_name = $madeData->made_by_Arabic_name;
                            $pd->Category_name = $cateData->Category_name;
                            $pd->Arabic_Category_name = $cateData->Arabic_Category_name;
                            if (isset($phamacist_id)) {
                                $ch = DB::table('favorates')->where('products_id', $pd->id)->where('phamacist_id', $phamacist_id)->first();
                                if (isset($ch)) {
                                    $pd->favorates = true;
                                } else {
                                    $pd->favorates = false;
                                }
                            }
                        }
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
                    foreach ($proDataSearch as $pd) {
                        $madeData = DB::table('made_by')->where('id', $pd->made_by_id)->select('made_by_name', 'made_by_Arabic_name')->first();
                        $cateData = DB::table('category')->where('id', $pd->category_id)->select('Category_name', 'Arabic_Category_name')->first();
                        $pd->made_by_name = $madeData->made_by_name;
                        $pd->made_by_Arabic_name = $madeData->made_by_Arabic_name;
                        $pd->Category_name = $cateData->Category_name;
                        $pd->Arabic_Category_name = $cateData->Arabic_Category_name;
                        if (isset($phamacist_id)) {
                            $ch = DB::table('favorates')->where('products_id', $pd->id)->where('phamacist_id', $phamacist_id)->first();
                            if (isset($ch)) {
                                $pd->favorates = true;
                            } else {
                                $pd->favorates = false;
                            }
                        }
                    }
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
            if ($cat_id == null) {
                $cat_id = DB::table('category')->where('Arabic_Category_name', $category)->first();
            }
            if ($cat_id == null) {
                return response()->json([
                    "status" => 0,
                    "message" => "not founde"
                ]);
            }
            $proDataSearch = DB::table('products')->where('category_id', $cat_id->id)->orderBy('id', 'ASC')->get();
            if (count($proDataSearch) != 0) {
                foreach ($proDataSearch as $pd) {
                    $madeData = DB::table('made_by')->where('id', $pd->made_by_id)->select('made_by_name', 'made_by_Arabic_name')->first();
                    $cateData = DB::table('category')->where('id', $pd->category_id)->select('Category_name', 'Arabic_Category_name')->first();
                    $pd->made_by_name = $madeData->made_by_name;
                    $pd->made_by_Arabic_name = $madeData->made_by_Arabic_name;
                    $pd->Category_name = $cateData->Category_name;
                    $pd->Arabic_Category_name = $cateData->Arabic_Category_name;
                    if (isset($phamacist_id)) {
                        $ch = DB::table('favorates')->where('products_id', $pd->id)->where('phamacist_id', $phamacist_id)->first();
                        if (isset($ch)) {
                            $pd->favorates = true;
                        } else {
                            $pd->favorates = false;
                        }
                    }
                }
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
        return app('App\Http\Controllers\Api\productController')->getAllProducts($phamacist_id);
    }
}
