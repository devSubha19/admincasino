<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class superstockez extends Model
{
    use HasFactory;
    protected $table = 'superstockez'; 
    public $timestamps = false;
    
    

    public function mulstockez()
    {
        return $this->hasMany(stockez::class);
    }

}
