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
        return $this->belongsTo(owner::class);
    }
    public function location()
    {
        return $this->hasOne(location::class);
    }
}
