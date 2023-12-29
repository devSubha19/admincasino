<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stockez extends Model
{
    use HasFactory;
    protected $table = 'stockez'; 
    public $timestamps = false;

    public function superstockez()
    {
        return $this->belongsTo(superstockez::class, 'superstockez'); 
    }

    
    public function mulagent()
    {
        return $this->hasMany(agent::class);
    }
}
