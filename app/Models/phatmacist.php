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
}
