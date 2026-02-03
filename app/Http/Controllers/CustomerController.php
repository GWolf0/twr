<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use App\Services\CRUD\BookingCRUDService;
use App\Services\CRUD\UserCRUDService;
use App\Services\CRUD\VehicleCRUDService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use function App\Helpers\appResponse;

class CustomerController extends Controller
{

    /**
     * book vehicle page
     * GET /bookings/vehicles/{vehicle_id}
     */
    public function bookVehiclePage(VehicleCRUDService $vehicleCRUDService, Request $request, string $vehicle_id): RedirectResponse | JsonResponse
    {
        // get vehicle to book
        $vehicleResponse = $vehicleCRUDService->read($vehicle_id, $request->user());
        if (!$vehicleResponse->success()) {
            return appResponse($request, $vehicleResponse->data, $vehicleResponse->status);
        }
        $vehicle = $vehicleResponse->data["model"];

        // construct data response
        $data = [
            "vehicle" => $vehicle
        ];

        return appResponse($request, $data, 200, "customer.page.book_vehicle");
    }

    /**
     * bookings list page
     * GET /bookings
     */
    public function bookingsListPage(BookingCRUDService $bookingCRUDService, Request $request): RedirectResponse | JsonResponse
    {
        $authUser = $request->user();

        // get list of bookings
        $bookingsResponse = $bookingCRUDService->readMany("user_id=" . $authUser?->id, $authUser);
        if (!$bookingsResponse->success()) {
            return appResponse($request, $bookingsResponse->data, $bookingsResponse->status);
        }

        // make data
        $data = [
            "bookings" => $bookingsResponse["models"]
        ];

        return appResponse($request, $data, 200, "customer.page.bookings_list");
    }

    /**
     * booking details page
     * GET /bookings/{booking_id}
     */
    public function bookingDetailsPage(BookingCRUDService $bookingCRUDService, Request $request, string $booking_id): RedirectResponse | JsonResponse
    {
        $authUser = $request->user();

        // get the booking model
        $bookingResponse = $bookingCRUDService->read($booking_id, $authUser);
        if (!$bookingResponse->success()) {
            return appResponse($request, $bookingResponse->data, $bookingResponse->status);
        }

        // make data
        $data = [
            "booking" => $bookingResponse["model"]
        ];

        return appResponse($request, $data, 200, "customer.page.booking_details");
    }

    /**
     * profile page
     * GET /profile
     */
    public function profilePage(Request $request): RedirectResponse | JsonResponse
    {
        $authUser = $request->user();

        // data
        $data = [
            "profile" => $authUser,
        ];

        return appResponse($request, $data, 200, "customer.page.profile");
    }

    /**
     * perform booking
     * POST /customer/bookings
     */
    public function book(BookingService $bookingService, Request $request): RedirectResponse | JsonResponse
    {
        $authUser = $request->user();
        $bookingsResponse = $bookingService->createBooking($request->all(), $authUser);

        return appResponse($request, $bookingsResponse->data, $bookingsResponse->status);
    }

    /**
     * cancel booking
     * POST /customer/bookings/{booking_id}/cancel
     */
    public function cancelBooking(BookingService $bookingService, Request $request, string $booking_id): RedirectResponse | JsonResponse
    {
        $authUser = $request->user();
        $bookingsResponse = $bookingService->cancel(array_merge($request->all(), $request->route()->parameters()), $authUser);

        return appResponse($request, $bookingsResponse->data, $bookingsResponse->status);
    }
}
