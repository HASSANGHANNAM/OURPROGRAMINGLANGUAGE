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
        return $this->hasOne(User::class);
    }
}
