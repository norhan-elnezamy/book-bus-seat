<?php

namespace Database\Factories;

use App\Models\TripStation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripStationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TripStation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            'trip_id' => 1,
            'city_id' => 1,
        ];
    }
}
