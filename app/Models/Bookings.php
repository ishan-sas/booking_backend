<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stores_id',
        'no_of_kids',
        'booking_date',
        'time_slots_id',
        'extra_note',
        'status'
    ];
}
