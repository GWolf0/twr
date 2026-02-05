<?php

namespace App\Http\Controllers;

use App\Services\CRUD\MasterCRUDService;
use App\Services\CRUD\VehicleCRUDService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use function App\Helpers\appResponse;

// controller for requests available to any user (guest or auth)
class CommonController extends Controller
{

    /**
     * Home page
     * GET /
     */
    public function homePage(Request $request): RedirectResponse | JsonResponse
    {
        return appResponse($request, [], 200, "common.page.home");
    }

    /**
     * Email comfirmed page
     * GET /email-confirmed
     */
    public function emailConfirmedPage(Request $request): RedirectResponse | JsonResponse
    {
        return appResponse($request, [], 200, "common.page.email_confirmed");
    }

    /**
     * Search page (vehicles search)
     * GET /search?q=...
     */
    public function searchPage(VehicleCRUDService $vehicleCRUDService, Request $request): RedirectResponse | JsonResponse
    {
        $page = $request->query("page", 1);
        $perPage = $request->query("per_page", 30);
        $mResponse = $vehicleCRUDService->readMany($request->getQueryString(), $request->user(), $page, $perPage);

        return appResponse($request, $mResponse->data, $mResponse->status, "common.page.search");
    }

    /**
     * Vehicle details/listing page
     * GET /vehicles/{vehicle_id}
     */
    public function vehicleDetailsPage(VehicleCRUDService $vehicleCRUDService, Request $request, string $vehicle_id): RedirectResponse | JsonResponse
    {
        $mResponse = $vehicleCRUDService->read($vehicle_id, $request->user());

        return appResponse($request, $mResponse->data, $mResponse->status, "common.page.vehicle_details");
    }
}
