<?php

namespace App\Http\Services;

use App\Models\TripStation;
use App\Models\Booking;
use App\Models\Seat;

class BookingService
{
    /**
     * get trip available seats
     * 
     * @param Int tripId , Int $pickup , Int $destentaion
     * @return Collection
     */
    public function getTripAvailableSeats($tripId, $pickup, $destentaion)
    {
        $BookedSeatsIds = Booking::where('trip_id', $tripId)
                            ->whereNotIn('destenation_id', $this->getAllTripStationsBeforePickup($tripId, $pickup))
                            ->whereNotIn('pickup_id', $this->getAllTripStationsAfterDistenation($tripId, $destentaion))
                            ->pluck('seat_id');
        // if($destentaion == 5)
            // dd($BookedSeatsIds);
        return Seat::whereNotIn('id', $BookedSeatsIds )->get(['id', 'number']);
    }

    /**
     * book a trip seat
     * 
     * @param Array $data , Int user_id
     * @return Array
     */
    public function bookTrip($data, $user_id)
    {
        $availableSeats = $this->getTripAvailableSeats($data['trip_id'], $data['pickup'], $data['destenation']);

        if($availableSeats){
            // dd($availableSeats[0]['id']);
            Booking::create([
                'user_id' => $user_id,
                'trip_id' => $data['trip_id'],
                'pickup_id' => $data['pickup'],
                'destenation_id' => $data['destenation'],
                'seat_id' => $availableSeats[0]['id'],
            ]);
            return ['status' => true, 'msg' => 'booked successfully .'];
        }
        return ['status' => false, 'msg' => 'somthing went wrong .'];;
    }

    /**
     * get all stations before a specific stations
     * 
     * @param Int $trip_id , Int $station
     * @return Array
     */
    public function getAllTripStationsBeforePickup($trip_id, $station)
    {
        $stationsIds = TripStation::where([
                    ['trip_id', $trip_id], 
                    ['station_order' , '<=', function($query) use($trip_id, $station) {
                        $query->where([
                            ['city_id', $station], 
                            ['trip_id', $trip_id]
                        ])->from('trip_stations as stations')->select('station_order');
                    }]
                ])->pluck('city_id');
        // dd($stationsIds);
        return $stationsIds;
    }

    /**
     * get all stations After a specific stations
     * 
     * @param Int $trip_id , Int $station
     * @return Array
     */
    public function getAllTripStationsAfterDistenation($trip_id, $station)
    {
        $stationsIds = TripStation::where([
                ['trip_id', $trip_id], 
                ['station_order' , '>=', function($query) use($trip_id, $station) {
                    $query->where([
                        ['city_id', $station], 
                        ['trip_id', $trip_id]
                    ])->from('trip_stations')->select('station_order');
                }]
            ])->pluck('city_id');
        // dd($stationsIds);
        return $stationsIds;
    }
}