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
    public function canBook(
        BookingService $bookingService,
        Request $request
    ): JsonResponse {
        $m_response = $bookingService->canBook($request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    /**
     * POST /api/booking
     */
    public function create(
        BookingService $bookingService,
        Request $request
    ): JsonResponse {
        $m_response = $bookingService->createBooking($request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    /**
     * POST /api/booking/{booking}/confirm
     */
    public function confirm(
        BookingService $bookingService,
        Booking $booking,
        Request $request
    ): JsonResponse {
        $m_response = $bookingService->confirm($request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    /**
     * POST /api/booking/{booking}/cancel
     */
    public function cancel(
        BookingService $bookingService,
        Booking $booking,
        Request $request
    ): JsonResponse {
        $m_response = $bookingService->cancel($request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    /**
     * POST /api/booking/{booking}/complete
     */
    public function complete(
        BookingService $bookingService,
        Booking $booking,
        Request $request
    ): JsonResponse {
        $m_response = $bookingService->complete($request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    /**
     * POST /api/booking/{booking}/refund
     */
    public function refund(
        BookingService $bookingService,
        Booking $booking,
        Request $request
    ): JsonResponse {
        $m_response = $bookingService->refund($request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    /**
     * DELETE /api/booking/{booking}
     */
    public function delete(
        BookingService $bookingService,
        Booking $booking,
        Request $request
    ): JsonResponse {
        $m_response = $bookingService->delete($request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }
}
