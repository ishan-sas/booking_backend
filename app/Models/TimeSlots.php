<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlots extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stores_id',
        'day',
        'time_slot',
        'session'
    ];

    public function booking(){
        return $this->hasMany('App\Models\Bookings','time_slots_id','id');
    }
}
