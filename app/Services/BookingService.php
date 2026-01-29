<?php

namespace App\Services;

use App\Interfaces\IBookingInterface;
use App\Misc\BookingPaymentMethod;
use App\Misc\BookingPaymentStatus;
use App\Misc\BookingStatus;
use App\Misc\UserRole;
use App\Misc\VehicleAvailability;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Types\DOE;
use Carbon\Carbon;

class BookingService implements IBookingInterface
{
    public function can_book_vehicle(
        Vehicle $vehicle,
        ?User $auth_user,
        Carbon $from,
        Carbon $to,
        ?array $options
    ): DOE {
        // auth check
        if (!$auth_user || !$auth_user->is_customer()) {
            return DOE::create(false, 'Unauthorized');
        }

        // vehicle availability
        if ($vehicle->status !== VehicleAvailability::available->name) {
            return DOE::create(false, 'Vehicle not available');
        }

        // time validity
        if ($from->gte($to)) {
            return DOE::create(false, 'Invalid booking period');
        }

        // hours constraint
        $hours = $options['hours'] ?? null;
        if (!$hours || $hours < 1 || $hours >= 72) {
            return DOE::create(false, 'Invalid booking duration');
        }

        // overlapping booking check
        $overlapExists = Booking::query()
            ->where('vehicle_id', $vehicle->id)
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('start_date', [$from, $to])
                    ->orWhereBetween('end_date', [$from, $to])
                    ->orWhere(function ($q) use ($from, $to) {
                        $q->where('start_date', '<=', $from)
                            ->where('end_date', '>=', $to);
                    });
            })->exists();

        if ($overlapExists) {
            return DOE::create(false, 'Vehicle already booked for this period');
        }

        return DOE::create(true);
    }

    public function calculate_amount(Vehicle $vehicle, Carbon $from, Carbon $to, ?array $options): float
    {
        $pricePerHour = $vehicle->price_per_hour;
        // charge for partial hours
        $hours = max(1, ceil($from->floatDiffInHours($to)));

        return $pricePerHour * $hours;
    }

    public function book_vehicle(
        Vehicle $vehicle,
        ?User $auth_user,
        Carbon $from,
        Carbon $to,
        ?array $options
    ): DOE {
        $canBook = $this->can_book_vehicle($vehicle, $auth_user, $from, $to, $options);

        if (!$canBook->is_success() || $canBook->data !== true) {
            return DOE::create(null, $canBook->error ?? 'Booking not allowed');
        }

        $amount = $this->calculate_amount($vehicle, $from, $to, $options);

        // create booking (not persisted)
        $booking = new Booking([
            'vehicle_id' => $vehicle->id,
            'user_id'    => $auth_user->id,
            'start_date'       => $from,
            'end_date'         => $to,
            "status" => $options["status"] ?? BookingStatus::pending->name,
            "payment_status" => $options["payment_status"] ?? BookingPaymentStatus::unpaid->name,
            "payment_method" => $options["payment_method"] ?? BookingPaymentMethod::cash->name,
            "total_amount" => $amount
        ]);

        return DOE::create($booking);
    }
}
