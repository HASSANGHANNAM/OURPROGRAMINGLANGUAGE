<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\category;
use Illuminate\Support\Facades\DB;

class categoryController extends Controller
{
    public function create_category(Request $request)
    {
        $categoryData = $request->validate([
            "Category_name" => "required|unique:category|max:45|string",
            "Arabic_Category_name" => "required|unique:category|max:45|string",
        ]);
        $c = $categoryData = category::create($categoryData);
        if (isset($c)) {
            return response()->json([
                "status" => 1,
                "message" => "succes"
            ]);
        }
        return response()->json([
            "status" => 0,
            "message" => "not succes"
        ]);
    }
    public function update_category(Request $request)
    {
        $request->validate([
            "id" => "required"
        ]);
        $c = DB::table('category')->where('id', $request->id)->select('id', 'Category_name', 'Arabic_Category_name')->first();
        if ($c == null) {
            return response()->json([
                "status" => 0,
                "message" => "catثلory not found "
            ]);
        }
        if ($c != null && $c->Category_name == $request->Category_name && $c->Arabic_Category_name == $request->Arabic_Category_name) {
            return response()->json([
                "status" => 1,
                "message" => "succes"
            ]);
        }
        if ($c != null && $c->Category_name == $request->Category_name) {
            $request->validate([
                "Category_name" => "required|max:45|string",
                "Arabic_Category_name" => "required|unique:category|max:45|string",
            ]);
            $c = category::where('id', $request->id)->update(array('Category_name' => $request->Category_name, 'Arabic_Category_name' => $request->Arabic_Category_name));
            if ($c != 0) {
                return response()->json([
                    "status" => 1,
                    "message" => "succes"
                ]);
            }
            return response()->json([
                "status" => 0,
                "message" => "not succes"
            ]);
        }
        if ($c != null && $c->Arabic_Category_name == $request->Arabic_Category_name) {
            $request->validate([
                "Category_name" => "|unique:category|required|max:45|string",
                "Arabic_Category_name" => "required|max:45|string",
            ]);
            $c = category::where('id', $request->id)->update(array('Category_name' => $request->Category_name, 'Arabic_Category_name' => $request->Arabic_Category_name));
            if ($c != 0) {
                return response()->json([
                    "status" => 1,
                    "message" => "succes"
                ]);
            }
            return response()->json([
                "status" => 0,
                "message" => "not succes"
            ]);
        }
        $request->validate([
            "Category_name" => "required|unique:category|max:45|string",
            "Arabic_Category_name" => "required|unique:category|max:45|string",
        ]);
        $c = category::where('id', $request->id)->update(array('Category_name' => $request->Category_name, 'Arabic_Category_name' => $request->Arabic_Category_name));
        if ($c != 0) {
            return response()->json([
                "status" => 1,
                "message" => "succes"
            ]);
        }
        return response()->json([
            "status" => 0,
            "message" => "not succes"
        ]);
    }
    public function getAllCategorys()
    {
        $categorys = DB::table('category')->select('id', 'Category_name', 'Arabic_Category_name')->orderBy('id', 'ASC')->get();
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $categorys
        ]);
    }
}
// ASC  DESC