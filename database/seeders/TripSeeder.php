<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\Trip;
use App\Models\TripStation;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Trip::factory()
            ->has(
                TripStation::factory()
                            ->count(7)
                            ->state(new Sequence(
                                ['city_id' => 1, 'station_order' => 1],
                                ['city_id' => 3, 'station_order' => 2],
                                ['city_id' => 4, 'station_order' => 3],
                                ['city_id' => 5, 'station_order' => 4],
                                ['city_id' => 6, 'station_order' => 5],
                                ['city_id' => 8, 'station_order' => 6],
                                ['city_id' => 10, 'station_order' => 7],
                            ))
            )->create();

    }
}
