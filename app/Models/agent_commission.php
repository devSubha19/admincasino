<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class agent_commission extends Model
{
    use HasFactory;
    protected $table = 'agent_commission';
    public $timestamps = false; 
}
