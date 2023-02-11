<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\TripStation;

class ValidTripStation implements Rule
{
    private $trip_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($trip_id)
    {
        $this->trip_id = $trip_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return TripStation::where([['trip_id', $this->trip_id], ['city_id', $value]])->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid selected station .';
    }
}
