<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class phatmacist extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "phatmacist";
    protected $fillable = ['Full_name', 'Pharmacy_name', 'user_id', 'location_id'];
    public $timestamps = true;
    public function order()
    {
        return $this->belongsToMany(order::class);
    }
    public function favorates()
    {
        return $this->belongsToMany(favorates::class);
    }
    public function location()
    {
        return $this->belongsTo(location::class);
    }
    public function user()
    {
        return $this->hasMany(user::class);
    }
}
