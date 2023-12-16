<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class category extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "category";
    protected $fillable = ['Category_name', 'Arabic_Category_name'];
    public $timestamps = true;
    public function product()
    {
        return $this->belongsToMany(product::class);
    }
}
