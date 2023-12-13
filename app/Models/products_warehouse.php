<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class products_warehouse extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "products_warehouse";
    protected $fillable = ['products_id',  'warehouse_id', 'Quantity'];
    public $timestamps = true;
}
