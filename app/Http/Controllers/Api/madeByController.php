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
            "made_by_Arabic_name" => "required|unique:made_by|max:45|string",
        ]);
        $made_byData = made_by::create($made_byData);
        if (isset($made_byData)) {
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
    public function update_made_by(Request $request)
    {
        $request->validate([
            "id" => "required"
        ]);

        $c = DB::table('made_by')->where('id', $request->id)->select('id', 'made_by_name', 'made_by_Arabic_name')->first();
        if ($c == null) {
            return response()->json([
                "status" => 0,
                "message" => "made_by not found "
            ]);
        }
        if ($c->made_by_name == $request->made_by_name && $c->made_by_Arabic_name == $request->made_by_Arabic_name) {
            return response()->json([
                "status" => 1,
                "message" => "succes"
            ]);
        }
        if ($c->made_by_name == $request->made_by_name) {
            $request->validate([
                "made_by_name" => "required|max:45|string",
                "made_by_Arabic_name" => "required|unique:made_by|max:45|string",
            ]);
            $c = made_by::where('id', $request->id)->update(array('made_by_name' => $request->made_by_name, 'made_by_Arabic_name' => $request->made_by_Arabic_name));
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
        if ($c->made_by_Arabic_name == $request->made_by_Arabic_name) {
            $request->validate([
                "made_by_name" => "|unique:made_by|required|max:45|string",
                "made_by_Arabic_name" => "required|max:45|string",
            ]);
            $c = made_by::where('id', $request->id)->update(array('made_by_name' => $request->made_by_name, 'made_by_Arabic_name' => $request->made_by_Arabic_name));
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
            "made_by_name" => "required|unique:made_by|max:45|string",
            "made_by_Arabic_name" => "required|unique:made_by|max:45|string",
            "id" => "required"
        ]);
        $c = made_by::where('id', $request->id)->update(array('made_by_name' => $request->made_by_name, 'made_by_Arabic_name' => $request->made_by_Arabic_name));
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
    public function getAllMadeby()
    {
        $made_by = DB::table('made_by')->select('id', 'made_by_name', 'made_by_Arabic_name')->orderBy('id', 'ASC')->get();
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $made_by
        ]);
    }
}
