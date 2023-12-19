<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class order_product extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "order_products";
    protected $fillable = ['order_id', 'Quantity', 'Product_id'];
    public $timestamps = true;
    public function order()
    {
        return $this->hasMany(order::class);
    }
    public function product()
    {
        return $this->hasMany(product::class);
    }
}
