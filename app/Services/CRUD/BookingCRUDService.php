<?php

namespace App\Services\CRUD;

use App\Interfaces\ICRUDInterface;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\BookingService;
use App\Types\MResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function App\Helpers\searchFiltered;

class BookingCRUDService implements ICRUDInterface
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function getNewModelInstance(): array
    {
        return [

        ];
    }

    /**
     * Create a new booking.
     */
    public function create(array $data, ?User $authUser): MResponse
    {
        $bookingResponse = $this->bookingService->createBooking($data, $authUser);

        if (!$bookingResponse->success()) {
            return $bookingResponse;
        }

        return MResponse::create([
            "model" => $bookingResponse->data["booking"],
            "message" => $bookingResponse->data["message"],
        ], $bookingResponse->status);
    }

    /**
     * Read a single booking.
     */
    public function read(int|string $id, ?User $authUser): MResponse
    {
        if (!$authUser) {
            return MResponse::create(['message' => 'Unauthorized'], 403);
        }

        $booking = Booking::find($id);

        if (!$booking) {
            return MResponse::create(['message' => 'Booking not found'], 404);
        }

        if (!$authUser->isAdmin() && $booking->user_id !== $authUser->id) {
            return MResponse::create(['message' => 'Unauthorized'], 403);
        }

        return MResponse::create([
            'message' => 'Booking retrieved successfully',
            'model'   => $booking,
        ]);
    }

    /**
     * Read multiple bookings (filtered).
     */
    public function readMany(?string $queryParams, ?User $authUser, int $page = 1, int $perPage = 30): MResponse
    {
        if (!$authUser) {
            return MResponse::create(['message' => 'Unauthorized'], 403);
        }

        $query = Booking::query();

        if (!$authUser->isAdmin()) {
            $query->where('user_id', $authUser->id);
        }

        return MResponse::create([
            'message' => 'Bookings retrieved successfully',
            'models'  => searchFiltered($query, $queryParams)->paginate(perPage: $perPage, page: $page),
        ]);
    }

    /**
     * Updating bookings directly is forbidden.
     */
    public function update(int|string $id, array $data, ?User $authUser): MResponse
    {
        return MResponse::create([
            'message' => 'Bookings cannot be updated. Use booking actions instead.',
        ], 405);
    }

    /**
     * Delete booking (admin only, via BookingService).
     */
    public function delete(int|string $id, ?User $authUser): MResponse
    {
        $mResponse = $this->bookingService->delete(["booking_id" => $id], $authUser);

        return $mResponse;
    }
}
