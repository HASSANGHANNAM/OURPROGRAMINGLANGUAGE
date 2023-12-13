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
        ]);
        $categoryData = category::create($categoryData);
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function update_category(Request $request)
    {
        $request->validate([
            "Category_name" => "required|unique:category|max:45|string",
            "id" => "required"
        ]);
        category::where('id', $request->id)->update(array('Category_name' => $request->Category_name));
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function getAllCategorys()
    {
        $categorys = DB::table('category')->select('id', 'Category_name')->orderBy('id', 'ASC')->get();
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $categorys
        ]);
    }
}
// ASC  DESC