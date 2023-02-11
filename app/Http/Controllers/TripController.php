<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TripRequest;
use App\Http\Services\BookingService;

class TripController extends Controller
{

    private $bookingService;

    
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * get trip avaialble seats  
     * 
     * @param TripRequest
     * @return Response
     */
    public function getAvailableSeats(TripRequest $request)
    {
        $availableSeats = $this->bookingService->getTripAvailableSeats($request->trip_id, $request->pickup, $request->destenation);
        return response()->json(['available_seats' => $availableSeats ]) ;
    }

    /**
     * book trip seat
     * 
     * @param TripRequest
     * @return Response
     */
    public function booking(TripRequest $request)
    {
        $result = $this->bookingService->bookTrip($request, auth()->guard('api')->id());
        return response()->json($result) ;
    }
}
