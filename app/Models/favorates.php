<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class favorates extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "favorates";
    protected $fillable = ['phamacist_id', 'products_id'];
    public $timestamps = true;
}
