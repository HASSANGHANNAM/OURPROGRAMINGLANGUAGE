<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class order_product extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "order_products";
    protected $fillable = ['order_id', 'number_of_produts', 'Product_name'];
    public $timestamps = true;
}
