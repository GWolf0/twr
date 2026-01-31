<?php

namespace App\Interfaces;

use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use App\Types\DOE;
use App\Types\MResponse;

interface IBookingInterface
{
    /**
     * Checks whether a vehicle can be booked for a given period.
     * Includes availability, overlapping bookings, and user eligibility.
     */
    public function canBook(array $data, ?User $auth_user): MResponse;

    /**
     * Calculates the total booking amount for a given period.
     * Does not persist anything.
     */
    public function calculateAmount(array $data, ?User $auth_user): MResponse;

    /**
     * Creates a new booking.
     * Customers may only book for themselves.
     * Admins may book on behalf of any user.
     */
    public function createBooking(array $data, ?User $auth_user): MResponse;

    /**
     * Confirms a pending booking.
     * Admin-only action.
     */
    public function confirm(array $data, ?User $auth_user): MResponse;

    /**
     * Cancels a booking.
     * Customers may only cancel their own bookings.
     * Admins may cancel any booking.
     */
    public function cancel(array $data, ?User $auth_user): MResponse;

    /**
     * Marks a booking as completed.
     * Admin-only action.
     */
    public function complete(array $data, ?User $auth_user): MResponse;

    /**
     * Refunds a booking payment.
     * Admin-only action.
     */
    public function refund(array $data, ?User $auth_user): MResponse;

    /**
     * Permanently deletes a booking.
     * Admin-only action. Use with caution.
     */
    public function delete(array $data, ?User $auth_user): MResponse;
}
