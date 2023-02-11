<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;
use App\Models\User;
use App\Models\City;
use App\Models\TripStation;
use App\Models\Trip;
use App\Models\Booking;
use App\Models\Seat;

class TripTest extends TestCase
{
    use RefreshDatabase; 

    public $user;

    public $cities;

    public $trip;

    protected function setUp(): void
    {
        Parent::setUp();

        Seat::factory()->count(12)->create();
        $this->cities = City::factory()->count(5)->create();
        $this->user = User::factory()->create();

        $this->trip = Trip::factory()->has(
                    TripStation::factory()
                                ->count(5)
                                ->state(new Sequence(
                                    ['city_id' => $this->cities[0]['id'], 'station_order' => 1],
                                    ['city_id' => $this->cities[1]['id'], 'station_order' => 2],
                                    ['city_id' => $this->cities[2]['id'], 'station_order' => 3],
                                    ['city_id' => $this->cities[3]['id'], 'station_order' => 4],
                                    ['city_id' => $this->cities[4]['id'], 'station_order' => 5],
                                ))
                )->create();

        // create full trip booking
        Booking::factory()->count(8)->state(new Sequence(
            ['seat_id' => 1],
            ['seat_id' => 2],
            ['seat_id' => 3],
            ['seat_id' => 4],
            ['seat_id' => 5],
            ['seat_id' => 6],
            ['seat_id' => 7],
            ['seat_id' => 8],
        ))->create([
            'user_id' => $this->user->id,
            'trip_id' => $this->trip->id,
            'pickup_id' => $this->cities[0]['id'],
            'destenation_id' => $this->cities[4]['id'],
        ]);

        // book a trip start from  s1 to s2
        Booking::factory()->create([
            'user_id' => $this->user->id,
            'trip_id' => $this->trip->id,
            'pickup_id' => $this->cities[0]['id'],
            'destenation_id' => $this->cities[1]['id'],
            'seat_id' => 9
        ]);

        // book a trip start from s2 to s3
        Booking::factory()->create([
            'user_id' => $this->user->id,
            'trip_id' => $this->trip->id,
            'pickup_id' => $this->cities[1]['id'],
            'destenation_id' => $this->cities[2]['id'],
            'seat_id' => 10
        ]);
    }

    /**
     * get available seats from s1 to s5
     * o----o----o----o----o
     * x                   x
     * @return void
     */
    public function testAvailableSeatsFromStation1ToStation5()
    {
        $response = $this->getAvaialbeSeatsRequest($this->cities[0]['id'], $this->cities[4]['id']);
        $response->assertStatus(200)->assertJsonCount(2, 'available_seats');
    }

    /**
     * book a seat from s2 to s3
     * o----o----o----o----o
     *      x    x
     * @return void
     */
    public function testBookTripStartFromStation2ToStation3()
    {
        $response = $this->sendBookingRequest($this->cities[1]['id'], $this->cities[2]['id']);
        $response->assertStatus(200)->assertJson([
            'status' => TRUE
        ]);
    }

    /**
     * book a seat from s1 to s3
     * o----o----o----o----o
     * x         x
     * @return void
     */
    public function testBookTripStartFromStation1ToStation3()
    {
        $response = $this->sendBookingRequest($this->cities[0]['id'], $this->cities[2]['id']);
        $response->assertStatus(200)->assertJson([
            'status' => TRUE
        ]);
    }
    

    /**
     * get available seats from s1 to s3
     * o----o----o----o----o
     * x         x
     * @return void
     */
    public function testAvailableSeatsFromStation1ToStation3()
    {
        $this->testBookTripStartFromStation2ToStation3();
        
        $this->testBookTripStartFromStation1ToStation3();

        $response = $this->getAvaialbeSeatsRequest($this->cities[1]['id'], $this->cities[2]['id']);

        $response->assertStatus(200)->assertJsonCount(1, 'available_seats');;
    }


    /**
     * send booking post request 
     * 
     * @param $pickup city id , $destenation city id
     * 
     * @return Response
     */
    public function sendBookingRequest($pickup, $destenation)
    {
        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/trip/booking', [
            'trip_id' => $this->trip->id,
            'pickup' => $pickup,
            'destenation' => $destenation,
        ]);
        return $response;
    }

    /**
     * send get avaialble seats request 
     * 
     * @param $pickup city id , $destenation city id
     * 
     * @return Response
     */
    public function getAvaialbeSeatsRequest($pickup, $destenation)
    {
        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/trip/available/seats', [
            'trip_id' => $this->trip->id,
            'pickup' => $pickup,
            'destenation' => $destenation,
        ]);
        return $response;
    }
}
