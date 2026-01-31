<?php

namespace App\Http\Controllers;

use App\Services\CRUD\MasterCRUDService;
use App\Services\CRUD\VehicleCRUDService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use function App\Helpers\app_response;

// controller for requests available to any user (guest or auth)
class CommonController extends Controller
{

    /**
     * Home page
     * GET /
     */
    public function homePage(Request $request): RedirectResponse | JsonResponse
    {
        return app_response($request, [], 200, "common.home", [], "home");
    }

    /**
     * Search page (vehicles search)
     * GET /search?q=...
     */
    public function searchPage(VehicleCRUDService $vehicleCRUDService, Request $request): RedirectResponse | JsonResponse
    {
        $m_response = $vehicleCRUDService->readMany($request->getQueryString(), $request->user());

        return app_response($request, $m_response->data, $m_response->status, "common.search", [], "search");
    }

    /**
     * Vehicle details/listing page
     * GET /vehicles/{vehicle_id}
     */
    public function vehicleDetailsPage(VehicleCRUDService $vehicleCRUDService, Request $request, string $vehicle_id): RedirectResponse | JsonResponse
    {
        $m_response = $vehicleCRUDService->read($vehicle_id, $request->user());

        return app_response($request, $m_response->data, $m_response->status, "common.vehicle_details", [], "vehicle_details");
    }
}
