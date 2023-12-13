<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class product extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "products";
    protected $fillable = ['Price', 'category_id', 'made_by_id', 'image', 'marketing_name', 'scientific_name', 'arabic_name', 'exp_date'];
    public $timestamps = true;
}
