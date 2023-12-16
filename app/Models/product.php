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
    public function favorates()
    {
        return $this->belongsToMany(favorates::class);
    }
    public function order_product()
    {
        return $this->belongsToMany(order_product::class);
    }
    public function made_by()
    {
        return $this->hasMany(made_by::class);
    }
    public function products_warehouse()
    {
        return $this->belongsToMany(products_warehouse::class);
    }
    public function location()
    {
        return $this->hasMany(location::class);
    }
}
