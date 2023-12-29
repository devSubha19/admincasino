<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class agent extends Authenticatable  
{
    use HasApiTokens, HasFactory;

    protected $table = 'agent'; 
    public $timestamps = false;
    protected $guarded = [];
    
    public function stockez()
    {
        return $this->belongsTo(stockez::class, 'stockez'); 
    }

    
}
