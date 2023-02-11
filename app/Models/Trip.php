<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    /**
     * has many stations.
    */
    public function tripStations()
    {
        return $this->hasMany(TripStation::class);
    }
}
