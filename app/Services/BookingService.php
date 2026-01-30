<?php

namespace App\Services;

use App\Interfaces\IBookingInterface;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Types\DOE;
use App\Misc\BookingStatus;
use App\Misc\BookingPaymentStatus;
use App\Misc\BookingPaymentMethod;
use App\Misc\VehicleAvailability;
use Carbon\Carbon;

class BookingService implements IBookingInterface
{
    /**
     * Checks whether a vehicle can be booked for a given period.
     */
    public function canBook(
        Vehicle $vehicle,
        ?User $user,
        Carbon $from,
        Carbon $to,
        ?array $options = null
    ): DOE {
        if (!$user || !$user->is_customer()) {
            return DOE::create(false, 'Unauthorized');
        }

        if ($vehicle->status !== VehicleAvailability::available->name) {
            return DOE::create(false, 'Vehicle not available');
        }

        if ($from->gte($to)) {
            return DOE::create(false, 'Invalid booking period');
        }

        $overlapExists = Booking::query()
            ->where('vehicle_id', $vehicle->id)
            ->whereIn('status', [
                BookingStatus::pending->name,
                BookingStatus::confirmed->name,
            ])
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('start_date', [$from, $to])
                    ->orWhereBetween('end_date', [$from, $to])
                    ->orWhere(function ($q) use ($from, $to) {
                        $q->where('start_date', '<=', $from)
                            ->where('end_date', '>=', $to);
                    });
            })
            ->exists();

        if ($overlapExists) {
            return DOE::create(false, 'Vehicle already booked for this period');
        }

        return DOE::create(true);
    }

    /**
     * Calculates the total booking amount for the given period.
     */
    public function calculateAmount(
        Vehicle $vehicle,
        Carbon $from,
        Carbon $to,
        ?array $options = null
    ): float {
        $hours = max(1, ceil($from->floatDiffInHours($to)));

        return $vehicle->price_per_hour * $hours;
    }

    /**
     * Creates and persists a new booking.
     */
    public function createBooking(
        Vehicle $vehicle,
        User $user,
        Carbon $from,
        Carbon $to,
        ?array $options = null
    ): DOE {
        $canBook = $this->canBook($vehicle, $user, $from, $to, $options);

        if (!$canBook->is_success() || $canBook->data !== true) {
            return DOE::create(null, $canBook->error ?? 'Booking not allowed');
        }

        $amount = $this->calculateAmount($vehicle, $from, $to, $options);

        $booking = Booking::create([
            'vehicle_id'     => $vehicle->id,
            'user_id'        => $user->id,
            'start_date'     => $from,
            'end_date'       => $to,
            'status'         => BookingStatus::pending->name,
            'payment_status' => BookingPaymentStatus::unpaid->name,
            'payment_method' => $options['payment_method'] ?? BookingPaymentMethod::cash->name,
            'total_amount'   => $amount,
        ]);

        return DOE::create($booking);
    }

    /**
     * Confirms a pending booking (admin only).
     */
    public function confirm(Booking $booking, User $user): DOE
    {
        if (!$user->is_admin()) {
            return DOE::create(false, 'Unauthorized');
        }

        if ($booking->status !== BookingStatus::pending->name) {
            return DOE::create(false, 'Booking cannot be confirmed');
        }

        $booking->update([
            'status' => BookingStatus::confirmed->name,
        ]);

        return DOE::create(true);
    }

    /**
     * Cancels a booking.
     */
    public function cancel(Booking $booking, User $user): DOE
    {
        if (
            !$user->is_admin() &&
            (!$user->is_customer() || $booking->user_id !== $user->id)
        ) {
            return DOE::create(false, 'Unauthorized');
        }

        if ($booking->status === BookingStatus::completed->name) {
            return DOE::create(false, 'Completed bookings cannot be canceled');
        }

        $booking->update([
            'status' => BookingStatus::canceled->name,
        ]);

        return DOE::create(true);
    }

    /**
     * Marks a booking as completed (admin only).
     */
    public function complete(Booking $booking, User $user): DOE
    {
        if (!$user->is_admin()) {
            return DOE::create(false, 'Unauthorized');
        }

        if ($booking->status !== BookingStatus::confirmed->name) {
            return DOE::create(false, 'Booking cannot be completed');
        }

        $booking->update([
            'status' => BookingStatus::completed->name,
        ]);

        return DOE::create(true);
    }

    /**
     * Refunds a booking payment (admin only).
     */
    public function refund(Booking $booking, User $user): DOE
    {
        if (!$user->is_admin()) {
            return DOE::create(false, 'Unauthorized');
        }

        if ($booking->payment_status !== BookingPaymentStatus::paid->name) {
            return DOE::create(false, 'Booking is not paid');
        }

        $booking->update([
            'payment_status' => BookingPaymentStatus::refunded->name,
        ]);

        return DOE::create(true);
    }

    /**
     * Deletes a booking permanently (admin only).
     */
    public function delete(Booking $booking, User $user): DOE
    {
        if (!$user->is_admin()) {
            return DOE::create(false, 'Unauthorized');
        }

        $booking->delete();

        return DOE::create(true);
    }
}
