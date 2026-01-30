<?php

namespace App\Interfaces;

use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use App\Types\DOE;

interface IBookingInterface
{
    /**
     * Checks whether a vehicle can be booked for a given period.
     * Includes availability, overlapping bookings, and user eligibility.
     */
    public function canBook(
        Vehicle $vehicle,
        ?User $user,
        Carbon $from,
        Carbon $to,
        ?array $options = null
    ): DOE;

    /**
     * Calculates the total booking amount for a given period.
     * Does not persist anything.
     */
    public function calculateAmount(
        Vehicle $vehicle,
        Carbon $from,
        Carbon $to,
        ?array $options = null
    ): float;

    /**
     * Creates a new booking.
     * Customers may only book for themselves.
     * Admins may book on behalf of any user.
     */
    public function createBooking(
        Vehicle $vehicle,
        User $user,
        Carbon $from,
        Carbon $to,
        ?array $options = null
    ): DOE;

    /**
     * Confirms a pending booking.
     * Admin-only action.
     */
    public function confirm(Booking $booking, User $user): DOE;

    /**
     * Cancels a booking.
     * Customers may only cancel their own bookings.
     * Admins may cancel any booking.
     */
    public function cancel(Booking $booking, User $user): DOE;

    /**
     * Marks a booking as completed.
     * Admin-only action.
     */
    public function complete(Booking $booking, User $user): DOE;

    /**
     * Refunds a booking payment.
     * Admin-only action.
     */
    public function refund(Booking $booking, User $user): DOE;

    /**
     * Permanently deletes a booking.
     * Admin-only action. Use with caution.
     */
    public function delete(Booking $booking, User $user): DOE;
}
