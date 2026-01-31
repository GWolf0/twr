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

    public function get_new_model_instance(): Model
    {
        return new Booking();
    }

    /**
     * Create a new booking.
     */
    public function create(array $data, ?User $auth_user): MResponse
    {
        if (!$auth_user) {
            return MResponse::create(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($data, [
            'vehicle_id'     => ['required', 'exists:vehicles,id'],
            'start_date'     => ['required', 'date'],
            'end_date'       => ['required', 'date', 'after:start_date'],
            'payment_method' => ['required', Rule::in(BookingPaymentMethodArray)],
            'user_id'        => $auth_user->is_admin()
                ? ['required', 'exists:users,id']
                : ['prohibited'],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validated();

        $user = $auth_user->is_admin()
            ? User::find($validated['user_id'])
            : $auth_user;
        if (!$user) {
            return MResponse::create(['message' => 'Specified user not found'], 404);
        }

        $vehicle = Vehicle::find($validated['vehicle_id']);
        if (!$vehicle) {
            return MResponse::create(['message' => 'Specified vehicle not found'], 404);
        }

        $bookingResponse = $this->bookingService->createBooking(
            [
                "vehicle" => $vehicle,
                "user" => $user,
                "start_date" => Carbon::parse($validated['start_date']),
                "end_date" => Carbon::parse($validated['end_date']),
            ],
            $auth_user
        );

        return $bookingResponse;
    }

    /**
     * Read a single booking.
     */
    public function read(int|string $id, ?User $auth_user): MResponse
    {
        if (!$auth_user) {
            return MResponse::create(['message' => 'Unauthorized'], 403);
        }

        $booking = Booking::find($id);

        if (!$booking) {
            return MResponse::create(['message' => 'Booking not found'], 404);
        }

        if (!$auth_user->is_admin() && $booking->user_id !== $auth_user->id) {
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
    public function readMany(string $queryParams, ?User $auth_user): MResponse
    {
        if (!$auth_user) {
            return MResponse::create(['message' => 'Unauthorized'], 403);
        }

        $query = Booking::query();

        if (!$auth_user->is_admin()) {
            $query->where('user_id', $auth_user->id);
        }

        return MResponse::create([
            'message' => 'Bookings retrieved successfully',
            'models'  => searchFiltered($query, $queryParams),
        ]);
    }

    /**
     * Updating bookings directly is forbidden.
     */
    public function update(int|string $id, array $data, ?User $auth_user): MResponse
    {
        return MResponse::create([
            'message' => 'Bookings cannot be updated. Use booking actions instead.',
        ], 405);
    }

    /**
     * Delete booking (admin only, via BookingService).
     */
    public function delete(int|string $id, ?User $auth_user): MResponse
    {
        if (!$auth_user) {
            return MResponse::create(['message' => 'Unauthorized'], 403);
        }

        $booking = Booking::find($id);

        if (!$booking) {
            return MResponse::create(['message' => 'Booking not found'], 404);
        }

        $m_response = $this->bookingService->delete($booking, $auth_user);

        return $m_response;
    }
}
