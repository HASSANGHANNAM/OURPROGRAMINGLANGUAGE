<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class warehouse extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "warehouse";
    protected $fillable = ['location_id', 'owner_id', 'Warehouse_name'];
    public $timestamps = true;

    public function owner()
    {
        return $this->hasOne(owner::class);
    }
    public function order()
    {
        return $this->belongsToMany(order::class);
    }
    public function products_warehouse()
    {
        return $this->belongsToMany(products_warehouse::class);
    }
    public function location()
    {
        return $this->hasOne(location::class);
    }
}
