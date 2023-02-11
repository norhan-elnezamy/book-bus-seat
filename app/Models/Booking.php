<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'trip_id',
        'pickup_id',
        'destenation_id',
        'seat_id'
    ];


    /**
     * has one seat.
    */
    public function seat()
    {
        return $this->hasOne(Seat::class);
    }

}
