<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class city extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "city";
    protected $fillable = ['City_name'];
    public $timestamps = true;
    public function user()
    {
        return $this->belongsToMany(location::class);
    }
}
