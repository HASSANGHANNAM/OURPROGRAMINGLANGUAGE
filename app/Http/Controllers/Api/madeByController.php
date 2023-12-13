<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\made_by;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class madeByController extends Controller
{
    public function create_made_by(Request $request)
    {
        $made_byData = $request->validate([
            "made_by_name" => "required|unique:made_by|max:45|string",
        ]);
        $made_byData = made_by::create($made_byData);
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function update_made_by(Request $request)
    {
        $request->validate([
            "made_by_name" => "required|unique:made_by|max:45|string",
            "id" => "required"
        ]);
        made_by::where('id', $request->id)->update(array('made_by_name' => $request->made_by_name));
        return response()->json([
            "status" => 1,
            "message" => "succes"
        ]);
    }
    public function getAllMadeby()
    {
        $made_by = DB::table('made_by')->select('id', 'made_by_name')->orderBy('id', 'ASC')->get();
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $made_by
        ]);
    }
}
