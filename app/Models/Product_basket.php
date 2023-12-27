<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Product_basket extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "Product_basket";
    protected $fillable = ['phatmacist_id'];
    public $timestamps = true;
    public function phatmacist()
    {
        return $this->hasOne(phatmacist::class);
    }

    public function Product_basket_products()
    {
        return $this->belongsToMany(Product_basket_products::class);
    }
}
