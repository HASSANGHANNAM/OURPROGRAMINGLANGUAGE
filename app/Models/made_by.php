<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class made_by extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "made_by";
    protected $fillable = ['made_by_name', 'made_by_Arabic_name'];
    public $timestamps = true;
    public function made_by()
    {
        return $this->belongsToMany(made_by::class);
    }
}
