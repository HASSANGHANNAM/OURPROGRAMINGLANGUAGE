<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class order extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "order";
    protected $fillable = ['status', 'payment_status', 'warehouse_id', 'phatmacist_id'];
    public $timestamps = true;
    public function phatmacist()
    {
        return $this->hasMany(phatmacist::class);
    }
    public function warehouse()
    {
        return $this->hasMany(warehouse::class);
    }
    public function order_product()
    {
        return $this->belongsToMany(order_product::class);
    }
}
