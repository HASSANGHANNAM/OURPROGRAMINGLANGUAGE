<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Product_basket_products extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "Product_basket_products";
    protected $fillable = ['Product_basket_id', 'Quantity', 'Product_id'];
    public $timestamps = true;
    public function Product_basket()
    {
        return $this->hasMany(Product_basket::class);
    }
    public function product()
    {
        return $this->hasMany(product::class);
    }
}
