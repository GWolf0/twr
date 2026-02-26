<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    /**
     * POST /api/booking/can-book
     */
    public function canBook(BookingService $bookingService, Request $request): JsonResponse
    {
        $mResponse = $bookingService->canBook($request->all(), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * POST /api/booking/calculate
     */
    public function calculate(BookingService $bookingService, Request $request): JsonResponse
    {
        $mResponse = $bookingService->calculateAmount($request->all(), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * POST /api/booking
     */
    public function create(BookingService $bookingService, Request $request): JsonResponse
    {
        $mResponse = $bookingService->createBooking($request->all(), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * POST /api/booking/{booking_id}/confirm
     */
    public function confirm(BookingService $bookingService, Request $request, string $booking_id): JsonResponse
    {
        $mResponse = $bookingService->confirm(array_merge($request->all(), $request->route()->parameters()), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * POST /api/booking/{booking_id}/cancel
     */
    public function cancel(BookingService $bookingService, Request $request, Booking $booking_id): JsonResponse
    {
        $mResponse = $bookingService->cancel(array_merge($request->all(), $request->route()->parameters()), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * POST /api/booking/{booking_id}/complete
     */
    public function complete(BookingService $bookingService, Request $request, string $booking_id): JsonResponse
    {
        $mResponse = $bookingService->complete(array_merge($request->all(), $request->route()->parameters()), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * POST /api/booking/{booking_id}/refund
     */
    public function refund(BookingService $bookingService, Request $request, string $booking_id): JsonResponse
    {
        $mResponse = $bookingService->refund(array_merge($request->all(), $request->route()->parameters()), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * DELETE /api/booking/{booking_id}
     */
    public function delete(BookingService $bookingService, Request $request, string $booking_id): JsonResponse
    {
        $mResponse = $bookingService->delete(array_merge($request->all(), $request->route()->parameters()), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }
}
