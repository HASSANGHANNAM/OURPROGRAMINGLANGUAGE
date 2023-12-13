<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class owner extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "owner";
    protected $fillable = ['user_id', 'status'];
    public $timestamps = true;
    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function warehouse()
    {
        return $this->hasMany(warehouse::class);
    }
}
