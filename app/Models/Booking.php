<?php

namespace App\Models;

use App\Misc\Enums\BookingPaymentMethod;
use App\Misc\Enums\BookingPaymentStatus;
use App\Misc\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "vehicle_id",
        "start_date",
        "end_date",
        "status",
        "payment_status",
        "payment_method",
        "total_amount"
    ];

    // protected $with = ['user', 'vehicle'];

    // enums arrays
    public static function Statuses()
    {
        return array_column(BookingStatus::cases(), "name");
    }
    public static function PaymentStatuses()
    {
        return array_column(BookingPaymentStatus::cases(), "name");
    }
    public static function PaymentMethods()
    {
        return array_column(BookingPaymentMethod::cases(), "name");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
