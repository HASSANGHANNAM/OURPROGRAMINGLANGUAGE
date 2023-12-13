<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class super_admin extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "super_admin";
    protected $fillable = ['user_id'];
    public $timestamps = true;
    public function user()
    {
        return $this->hasMany(User::class);
    }
}


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
