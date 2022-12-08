<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSchools extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stores_id',
        'school_name'
    ];
}
