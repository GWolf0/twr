<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    /**
     * book vehicle page
     * GET /vehicles/{vehicle_id}/book
     */
    public function bookVehiclePage(Request $request, string $vehicle_id): RedirectResponse | JsonResponse {}

    /**
     * bookings list page
     * GET /bookings
     */
    public function bookingsListPage(Request $request): RedirectResponse | JsonResponse {}

    /**
     * profile page
     * GET /profile
     */
    public function profilePage(Request $request): RedirectResponse | JsonResponse {}

    /**
     * perform booking
     * POST /customer/bookings
     */
    public function book(Request $request): RedirectResponse | JsonResponse {}

    /**
     * cancel booking
     * POST /customer/bookings/{booking_id}/cancel
     */
    public function cancelBooking(Request $request, string $booking_id): RedirectResponse | JsonResponse {}
}
