<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoresUnavailableDates extends Model
{
    use HasFactory;
    
    protected $table = 'store_unavailable_dates';
    protected $fillable = [
        'user_id',
        'stores_id',
        'unave_date'
    ];

}
