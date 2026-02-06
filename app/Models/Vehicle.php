<?php

namespace App\Models;

use App\Misc\Enums\VehicleAvailability;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "type", "media", "price_per_hour", "availability"
    ];

    protected $with = ['bookings'];

    // enums arrays
    public static function Availabilities() {
        return array_column(VehicleAvailability::cases(), "name");
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
