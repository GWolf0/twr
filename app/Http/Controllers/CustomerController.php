<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use App\Services\CRUD\BookingCRUDService;
use App\Services\CRUD\UserCRUDService;
use App\Services\CRUD\VehicleCRUDService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use function App\Helpers\app_response;

class CustomerController extends Controller
{

    /**
     * book vehicle page
     * GET /vehicles/{vehicle_id}/book
     */
    public function bookVehiclePage(VehicleCRUDService $vehicleCRUDService, Request $request, string $vehicle_id): RedirectResponse | JsonResponse
    {
        $vehicle_response = $vehicleCRUDService->read($vehicle_id, $request->user());

        return app_response($request, $vehicle_response->data, $vehicle_response->status, "customer.book_vehicle_page");
    }

    /**
     * bookings list page
     * GET /bookings
     */
    public function bookingsListPage(BookingCRUDService $bookingCRUDService, Request $request): RedirectResponse | JsonResponse
    {
        $auth_user = $request->user();
        $bookings_response = $bookingCRUDService->readMany("user_id=" . $auth_user?->id, $auth_user);

        return app_response($request, $bookings_response->data, $bookings_response->status, "customer.bookings_list_page");
    }

    /**
     * profile page
     * GET /profile
     */
    public function profilePage(UserCRUDService $userCRUDService, Request $request): RedirectResponse | JsonResponse
    {
        $auth_user = $request->user();
        $user_response = $userCRUDService->read($auth_user->id, $auth_user);

        return app_response($request, $user_response->data, $user_response->status, "customer.profile_list_page");
    }

    /**
     * perform booking
     * POST /customer/bookings
     */
    public function book(BookingService $bookingService, Request $request): RedirectResponse | JsonResponse
    {
        $auth_user = $request->user();
        $bookings_response = $bookingService->createBooking($request->all(), $auth_user);

        return app_response($request, $bookings_response->data, $bookings_response->status, "customer.action.book");
    }

    /**
     * cancel booking
     * POST /customer/bookings/{booking_id}/cancel
     */
    public function cancelBooking(BookingService $bookingService, Request $request, string $booking_id): RedirectResponse | JsonResponse
    {
        $auth_user = $request->user();
        $bookings_response = $bookingService->cancel(array_merge($request->all(), $request->route()->parameters()), $auth_user);

        return app_response($request, $bookings_response->data, $bookings_response->status, "customer.action.cancel_booking");
    }
}
