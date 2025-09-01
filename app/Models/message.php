<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class message extends Model
{
    use HasFactory;

    protected $table = 'messages'; 
    protected $fillable = ['sender', 'message'];
}
