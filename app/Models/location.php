<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class location extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "location";
    protected $fillable = ['city_id', 'address'];
    public $timestamps = true;
    public function city()
    {
        return $this->hasOne(city::class);
    }
}
