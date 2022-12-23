<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoresUnavailableSlots extends Model
{
    use HasFactory;

    protected $table = 'store_unavailable_slotes';
    protected $fillable = [
        'user_id',
        'stores_id',
        'relate_date',
        'time_slot_id'
    ];
}
