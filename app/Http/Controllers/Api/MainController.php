<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\WarehouseController;
use Illuminate\Http\Request;
use App\Models\warehouse;
use App\Models\User;
use App\Models\owner;
use App\Models\city;
use App\Models\location;
use App\Models\phatmacist;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\Product_basket;

class MainController extends Controller
{
    public function regesterowner(Request $request)
    {
        $user = $request->validate([
            "Email_address" => "required|max:255|unique:users|email|regex:/(.+)@gmail.com/",
            "Phone_number" => "required|regex:/^([0-9\+]*)$/|min: 10|max:10|unique:users|regex:/09(.+)/",
            "Password" => ['required', 'string', password::min(8)]
        ]);
        $user['type'] = 1;
        $user['wallet'] = 10000;
        $user['password'] = Hash::make($request->Password);
        $user = User::create($user);
        $ownerData = [
            'status' => "bending",
            'user_id' => $user['id']
        ];
        $owner = owner::create($ownerData);
        $accessToken = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            "status" => 1,
            "message" => "succes",
            'access_token' => $accessToken
        ]);
    }
    public function regesterpharmace(Request $request)
    {
        $user = $request->validate([
            "Full_name" => "required|max:45",
            "Email_address" => "max:255|required|unique:users|email|regex:/(.+)@gmail.com/",
            "Phone_number" => "required|regex:/^([0-9\+]*)$/|min:10|max:10|unique:users|regex:/09(.+)/",
            "Password" => ['required', 'string', password::min(8)],
            "Pharmacy_name" => "required|unique:phatmacist|max:45|string",
            "City" => "required|integer",
            "Pharmacy_address" => "required|max:45|string"
        ]);
        $locationData = [
            'city_id' => $request->City,
            'address' => $request->Pharmacy_address,
        ];
        $location = location::create($locationData);
        $user['type'] = 2;
        $user['wallet'] = 10000;
        $user['password'] = Hash::make($request->Password);
        $user = User::create($user);
        $phatmacistData = [
            'Full_name' => $request->Full_name,
            'Pharmacy_name' => $request->Pharmacy_name,
            'location_id' => $location['id'],
            'user_id' => $user['id']
        ];
        $phatmacist = phatmacist::create($phatmacistData);
        $Product_basketData = [
            'phatmacist_id' => $phatmacist['id'],
        ];
        Product_basket::create($Product_basketData);
        $accessToken = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            "status" => 1,
            "message" => "succes", 'access_token' => $accessToken
        ]);
    }
    public function login(Request $request)
    {
        $loginData =   $request->validate(
            [
                "Email_address_or_Phone_number" => "required",
                "Password" => ['required', 'string', password::min(8)]
            ]
        );
        $loginDataPhone = [
            "Phone_number" => $request->Email_address_or_Phone_number,
            "password" => $request->Password
        ];
        $loginDataEmail = [
            "Email_address" => $request->Email_address_or_Phone_number,
            "password" => $request->Password
        ];
        $userphone = DB::table('users')->where('Phone_number', $loginDataPhone['Phone_number'])->first();
        $useremail = DB::table('users')->where('Email_address', $loginDataEmail['Email_address'])->first();
        if (!auth()->attempt($loginDataPhone) && !auth()->attempt($loginDataEmail)) {
            if (isset($userphone->id)) {
                return response()->json([
                    "status" => 0,
                    "message" => "password not found"
                ]);
            }
            if (isset($useremail->id)) {
                return response()->json([
                    "status" => 0,
                    "message" => "password not found"
                ]);
            }
            return response()->json([
                "status" => 0,
                "message" => "phone or email not found"
            ]);
        }
        $accessToken = auth()->user()->createToken('auth_token')->plainTextToken;
        if (auth()->user()->type == 1) {
            $user_id = DB::table('owner')->where('user_id', auth()->user()->id)->first();
            if ($user_id->status == "acceptable") {
                $user_id = DB::table('warehouse')->where('user_id', auth()->user()->id)->first();
                return response()->json([
                    "status" => 1,
                    "message" => "loged in",
                    'access_token' => $accessToken,
                    'warehouse_id' => $user_id->id
                ]);
            }
            return response()->json([
                "status" => 1,
                "message" => "loged in",
                'access_token' => $accessToken,
                'owner_id' => $user_id->id
            ]);
        }
        if (auth()->user()->type == 2) {
            $user_id = DB::table('phatmacist')->where('user_id', auth()->user()->id)->first();
            return response()->json([
                "status" => 1,
                "message" => "loged in",
                'access_token' => $accessToken,
                'pharmacy_id' => $user_id->id
            ]);
        }
        if (auth()->user()->type == 3) {
            $user_id = DB::table('super_admin')->where('user_id', auth()->user()->id)->first();
            return response()->json([
                "status" => 1,
                "message" => "loged in",
                'access_token' => $accessToken,
                'admin_id' => $user_id->id
            ]);
        }
        return response()->json([
            "status" => 1,
            "message" => "loged in",
            'access_token' => $accessToken
        ]);
    }
    public function profile()
    {
        $user_data = auth()->user();
        if ($user_data['type'] == 2) {
            $phar_data = DB::table('phatmacist')->where('user_id', $user_data->id)->first();
            $location_data = DB::table('location')->where('id', $phar_data->location_id)->first();
            $city_data = DB::table('city')->where('id', $location_data->city_id)->first();
            $profile_data = [
                "id" => $user_data->id,
                "Full_name" => $phar_data->Full_name,
                "Email_address" => $user_data->Email_address,
                "Phone_number" => $user_data->Phone_number,
                "password" => $user_data->password,
                "Pharmacy_name" => $phar_data->Pharmacy_name,
                "City_name" => $city_data->City_name,
                "City_Arabic_name" => $city_data->City_Arabic_name,
                "Pharmacy_address" => $location_data->address,
                "wallet" => $user_data->wallet,
                "type" => $user_data->type,
                "remember_token" => $user_data->remember_token,
                "created_at" => $user_data->created_at,
                "updated_at" => $user_data->updated_at
            ];
            return response()->json([
                "status" => 1,
                "message" => "info",
                "data" => $profile_data
            ]);
        } else if ($user_data['type'] == 1) {
            $owner_data = DB::table('owner')->where('user_id', $user_data->id)->first();
            if ($owner_data['status'] == "acceptable") {
                $warehouse_data = DB::table('warehouse')->where('owner_id', $user_data->id)->first();
                $location_data = DB::table('location')->where('id', $warehouse_data->location_id)->first();
                $city_data = DB::table('city')->where('id', $location_data->city_id)->first();
                $profile_data = [
                    "id" => $user_data->id,
                    "Email_address" => $user_data->Email_address,
                    "Phone_number" => $user_data->Phone_number,
                    "password" => $user_data->password,
                    "Warehouse_name" => $warehouse_data->Warehouse_name,
                    "City" => $city_data->City_name,
                    "City_Arabic_name" => $city_data->City_Arabic_name,
                    "warehouse_address" => $location_data->address,
                    "wallet" => $user_data->wallet,
                    "type" => $user_data->type,
                    "remember_token" => $user_data->remember_token,
                    "created_at" => $user_data->created_at,
                    "updated_at" => $user_data->updated_at
                ];
                return response()->json([
                    "status" => 1,
                    "message" => "info",
                    "data" => $profile_data
                ]);
            } else {
                $profile_data = [
                    "id" => $user_data->id,
                    "Email_address" => $user_data->Email_address,
                    "Phone_number" => $user_data->Phone_number,
                    "password" => $user_data->password,
                    "wallet" => $user_data->wallet,
                    "type" => $user_data->type,
                    "statusOfowner" => $owner_data->status,
                    "remember_token" => $user_data->remember_token,
                    "created_at" => $user_data->created_at,
                    "updated_at" => $user_data->updated_at
                ];
                return response()->json([
                    "status" => 1,
                    "message" => "info",
                    "data" => $profile_data
                ]);
            }
        }
        $profile_data = [
            "id" => $user_data->id,
            "Email_address" => $user_data->Email_address,
            "Phone_number" => $user_data->Phone_number,
            "password" => $user_data->password,
            "wallet" => $user_data->wallet,
            "type" => $user_data->type,
            "remember_token" => $user_data->remember_token,
            "created_at" => $user_data->created_at,
            "updated_at" => $user_data->updated_at
        ];
        return response()->json([
            "status" => 1,
            "message" => "info",
            "data" => $profile_data
        ]);
    }
    public function update_profile(Request $request)
    {
        $user_data = auth()->user();
        if ($user_data->Email_address == $request->Email_address) {
            $user = $request->validate([
                "Email_address" => "max:255|required|email|regex:/(.+)@gmail.com/|unique:users"
            ]);
        } else {
            $user = $request->validate([
                "Email_address" => "max:255|required|email|regex:/(.+)@gmail.com/"
            ]);
        }
        if ($user_data->Phone_number == $request->Phone_number) {
            $user = $request->validate([
                "Phone_number" => "required|regex:/^([0-9\+]*)$/|min:10|max:10|regex:/09(.+)/|unique:users"
            ]);
        } else {
            $user = $request->validate([
                "Phone_number" => "required|regex:/^([0-9\+]*)$/|min:10|max:10|regex:/09(.+)/"
            ]);
        }
        // type ware or pham
        if ($user_data['type'] == 2) {
            $user = $request->validate([
                "Password" => ['required', 'string', password::min(8)],
                "City" => "required|integer",
                "Pharmacy_address" => "required|max:45|string"
            ]);
            $lo = DB::table('phatmacist')->where('user_id',  $user_data->id)->first();
            if ($lo->Full_name == $request->Full_name) {
                $user = $request->validate([
                    "Full_name" => "required|max:45|unique:phatmacist"
                ]);
            } else {
                $user = $request->validate([
                    "Full_name" => "required|max:45"
                ]);
            }
            if ($lo->Pharmacy_name == $request->Pharmacy_name) {
                $user = $request->validate([
                    "Pharmacy_name" => "required|max:45|string"
                ]);
            } else {
                $user = $request->validate([
                    "Pharmacy_name" => "required|max:45|string|unique:phatmacist"
                ]);
            }
            location::where('id', $lo->location_id)->update(array('address' => $request->Pharmacy_address, 'city_id' => $request->City));
            $location = DB::table('location')->where('address', $request->Pharmacy_address)->first();
            $user = User::where('id', $user_data->id)->update(array(
                'Phone_number' => $request->Phone_number, 'Email_address' => $request->Email_address, 'password' => Hash::make($request->Password)
            ));
            $phatmacist = phatmacist::where('user_id', $user_data->id)->update(array(
                'Full_name' => $request->Full_name, 'location_id' => $location->id, 'Pharmacy_name' => $request->Pharmacy_name
            ));
            return response()->json([
                "status" => 1,
                "message" => "succes"
            ]);
        } else if ($user_data['type'] == 1) {
            $user = $request->validate([
                "Password" => ['required', 'string', password::min(8)],
                "City" => "required|integer",
                "warehouse_address" => "required|max:45|string"
            ]);
            $lw = DB::table('owner')->where('user_id',  $user_data->id)->first();
            $lo = DB::table('warehouse')->where('owner',  $lw->id)->first();
            if ($lo->warehouse_name == $request->warehouse_name) {
                $user = $request->validate([
                    "warehouse_name" => "required|max:45|unique:warehouse"
                ]);
            } else {
                $user = $request->validate([
                    "warehouse_name" => "required|max:45"
                ]);
            }
            location::where('id', $lo->location_id)->update(array('address' => $request->warehouse_address, 'city_id' => $request->City));
            $location = DB::table('location')->where('address', $request->warehouse_address)->first();
            $user = User::where('id', $user_data->id)->update(array(
                'Phone_number' => $request->Phone_number, 'Email_address' => $request->Email_address, 'password' => Hash::make($request->Password)
            ));
            $warehouse = warehouse::where('user_id', $user_data->id)->update(array(
                'warehouse_name' => $request->warehouse_name, 'location_id' => $location->id
            ));
            return response()->json([
                "status" => 1,
                "message" => "succes"
            ]);
        }
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "status" => 1,
            "message" => "loged out"
        ]);
    }
    public function acceptOwner(Request $request)
    {
        $request->validate([
            "status_owner" => "required",
            "owner_id" => "required"
        ]);
        $user_data = auth()->user();
        if ($user_data['type'] == 3) {
            owner::where('id', $request->owner_id)->update(array('status' => $request->status_owner));
            return response()->json([
                "status" => 1,
                "message" => "accepted owner "
            ]);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "Unauthenticated"
            ]);
        }
    }
}


//'image'=>'requierd|mimes:jpg,png,jped|max:5048'
//|longText
// 'phone' => 'required|regex:/^([0-9\+]*)$/|min:9|max:15|unique:experts'
// 'email' => 'max:255|unique:experts',
// 'password' => ['required','string',Password::min(8),'confirmed']
// |min:3|max:45
// |digits_between:1,7


//$experince['image'] = request()->file('image')->store('images/experinces_photos', 'public');




// $bookings = booking::where('expert_id', $expert_id)
//       ->join('clients', 'clients.client_id', '=', 'bookings.client_id')
//       ->select('bookings.week_day_id','bookings.time_of_begin', 'clients.client_name')
//       ->get();



//Client::where('client_id', auth()->user()->client_id)->with('favorite_expert')->get()




// public function index(){
//     $favorite_expert_ids = FavoriteExpert::where('client_id', auth()->user()->client_id)
//     ->pluck('expert_id');

// $experts = Expert::whereIn('expert_id', $favorite_expert_ids)
// ->with(['consultations', 'client', 'experinces'])
// ->get();
// return response($experts);
// }








//   علاقات
//return $this->belongsTo(User::class);
// return $this->hasMany(User::class);

// model import sanctum
// use Laravel\Sanctum\HasApiTokens;
// ,HasApiTokens

//'user_id'=>auth()->user()->id


//querys in database
//where('..... ' , $.... )->first;
//                        ->delete;
// find()
//findOrFail

//migration
//$table->unsignedBigInteger('user_id');
//$table->foreign('user_id')->references('id')->on('users');


//اضافة صورة 
//$newImageName=uniqid().'-'. $request->title.'-'.$request->image->extension();
//$request->image->move(public_path('image'),$newImageName);


//validation

        //'image'=>'requierd|mimes:jpg,png,jped|max:5048'
        //|longText
        // 'phone' => 'required|regex:/^([0-9\+]*)$/|min:9|max:15|unique:users'
        // 'email' => 'max:255|required|unique:users',
        // 'password' => ['required','string',Password::min(8),'confirmed']
        // |min:3|max:45
        // |digits_between:1,7



            // if (file_exists($image_path)) {

            //     @unlink($image_path);
         
            // }