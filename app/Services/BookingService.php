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
use App\Types\MResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class BookingService implements IBookingInterface
{
    /**
     * Checks whether a vehicle can be booked for a given period.
     */
    public function canBook(array $data, ?User $auth_user): MResponse
    {
        $validator = Validator::make($data, [
            "vehicle_id" => ["required_without:vehicle", "exists:vehicles,id"],
            "vehicle" => ["required_without:vehicle_id"],
            "user_id" => ["required_without:user", "exists:users,id"],
            "user" => ["required_without:user_id"],
            "start_date" => ["required", "date"],
            "end_date" => ["required", "date", "after:start_date"],
        ]);

        if ($validator->failed()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validate();

        $vehicle = $validated["vehicle"] ?? Vehicle::find($validated["vehicle_id"]);
        $user = $validated["user"] ?? User::find($validated["user_id"]);
        $from = Carbon::parse($validated["start_date"]);
        $to = Carbon::parse($validated["end_date"]);

        if (!$auth_user || (!$auth_user->is_admin() && $auth_user->id != $user->id)) {
            return MResponse::create([
                "message" => 'Unauthorized'
            ], 403);
        }

        $error_msg = "";
        if ($vehicle->status !== VehicleAvailability::available->name) {
            $error_msg =  'Vehicle not available';
        }

        if ($from->gte($to)) {
            $error_msg = 'Invalid booking period';
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
            })->exists();

        if ($overlapExists) {
            $error_msg = 'Vehicle already booked for this period';
        }

        return MResponse::create([
            "message" => $error_msg,
            "success" => empty($error_msg)
        ], empty($error_msg) ? 200 : 422);
    }

    /**
     * Calculates the total booking amount for the given period.
     */
    public function calculateAmount(array $data, ?User $auth_user): MResponse
    {
        $validator = Validator::make($data, [
            "vehicle_id" => ["required_without:vehicle", "exists:vehicles,id"],
            "vehicle" => ["required_without:vehicle_id"],
            "start_date" => ["required", "date"],
            "end_date" => ["required", "date", "after:start_date"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validate();

        $vehicle = $validated["vehicle"] ?? Vehicle::find($validated["vehicle_id"]);
        $from = Carbon::parse($validated["start_date"]);
        $to = Carbon::parse($validated["end_date"]);

        $hours = max(1, ceil($from->floatDiffInHours($to)));
        $amount = $vehicle->price_per_hour * $hours;

        return MResponse::create([
            "amount" => $amount,
            "hours" => $hours,
        ]);
    }

    /**
     * Creates and persists a new booking.
     */
    public function createBooking(array $data, ?User $auth_user): MResponse
    {
        $validator = Validator::make($data, [
            "vehicle_id" => ["required_without:vehicle", "exists:vehicles,id"],
            "vehicle" => ["required_without:vehicle_id"],
            "user_id" => ["required_without:user", "exists:users,id"],
            "user" => ["required_without:user_id"],
            "start_date" => ["required", "date"],
            "end_date" => ["required", "date", "after:start_date"],
            "payment_method" => ["nullable", "string"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validate();
        $vehicle = $validated["vehicle"] ?? Vehicle::find($validated["vehicle_id"]);
        $user = $validated["user"] ?? User::find($validated["user_id"]);

        $canBook = $this->canBook(array_merge($validated, ["vehicle" => $vehicle, "user" => $user]), $auth_user);

        if ($canBook->failed()) {
            return MResponse::create($canBook->data, $canBook->status);
        }

        $from = Carbon::parse($validated["start_date"]);
        $to = Carbon::parse($validated["end_date"]);
        $amount = $this->calculateAmount(array_merge([$validated, ["vehicle" => $vehicle]]), $auth_user)->data["amount"];

        $booking = Booking::create([
            "vehicle_id" => $vehicle->id,
            "user_id" => $user->id,
            "start_date" => $from,
            "end_date" => $to,
            "status" => BookingStatus::pending->name,
            "payment_status" => BookingPaymentStatus::unpaid->name,
            "payment_method" => $validated["payment_method"] ?? BookingPaymentMethod::cash->name,
            "total_amount" => $amount,
        ]);

        return MResponse::create([
            "message" => "Booking created successfully!",
            "booking" => $booking
        ], 201);
    }

    /**
     * Confirms a pending booking (admin only).
     */
    public function confirm(array $data, ?User $auth_user): MResponse
    {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($data, [
            "booking_id" => ["required", "exists:bookings,id"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $booking = Booking::find($data["booking_id"]);

        if ($booking->status !== BookingStatus::pending->name) {
            return MResponse::create(["message" => "Booking cannot be confirmed"], 422);
        }

        $booking->update([
            "payment_status" => BookingPaymentStatus::paid->name,
            "status" => BookingStatus::confirmed->name,
        ]);

        return MResponse::create([
            "message" => "Booking confirmed!",
            "success" => true
        ]);
    }

    /**
     * Cancels a booking.
     */
    public function cancel(array $data, ?User $auth_user): MResponse
    {
        $validator = Validator::make($data, [
            "booking_id" => ["required", "exists:bookings,id"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $booking = Booking::find($data["booking_id"]);

        if (
            !$auth_user ||
            (
                !$auth_user->is_admin() &&
                (!$auth_user->is_customer() || $booking->user_id !== $auth_user->id)
            )
        ) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        if ($booking->status === BookingStatus::completed->name) {
            return MResponse::create(["message" => "Completed bookings cannot be canceled"], 422);
        }

        $booking->update([
            "status" => BookingStatus::canceled->name,
        ]);

        return MResponse::create([
            "message" => "Booking canceled!",
            "success" => true
        ]);
    }

    /**
     * Marks a booking as completed (admin only).
     */
    public function complete(array $data, ?User $auth_user): MResponse
    {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($data, [
            "booking_id" => ["required", "exists:bookings,id"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $booking = Booking::find($data["booking_id"]);

        if ($booking->status !== BookingStatus::confirmed->name) {
            return MResponse::create(["message" => "Booking cannot be completed"], 422);
        }

        $booking->update([
            "status" => BookingStatus::completed->name,
        ]);

        return MResponse::create([
            "message" => "Booking marked completed!",
            "success" => true
        ]);
    }

    /**
     * Refunds a booking payment (admin only).
     */
    public function refund(array $data, ?User $auth_user): MResponse
    {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($data, [
            "booking_id" => ["required", "exists:bookings,id"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $booking = Booking::find($data["booking_id"]);

        if ($booking->payment_status !== BookingPaymentStatus::paid->name) {
            return MResponse::create(["message" => "Booking is not paid"], 422);
        }

        $booking->update([
            "payment_status" => BookingPaymentStatus::refunded->name,
        ]);

        return MResponse::create([
            "message" => "Booking refunded!",
            "success" => true
        ]);
    }

    /**
     * Deletes a booking permanently (admin only).
     */
    public function delete(array $data, ?User $auth_user): MResponse
    {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($data, [
            "booking_id" => ["required", "exists:bookings,id"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validate();

        $deleted = Booking::destroy($validated["booking_id"]);
        // Booking::find($data["booking_id"])->delete();

        return MResponse::create([
            "message" => $deleted > 0 ? "Booking deleted!" : "Counldn't delete booking!",
            "success" => $deleted > 0
        ], $deleted > 0 ? 204 : 400);
    }
}
