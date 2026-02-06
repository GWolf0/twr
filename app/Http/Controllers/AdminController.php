<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\CRUD\MasterCRUDService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use function App\Helpers\appResponse;

// controller for admin allowed actions only
// includes pages GET requests
// and other actions
class AdminController extends Controller
{

    // stats page (default dashboard page)
    // /dashboard | /dashboard/stats, METHOD=GET
    public function stats(Request $request): JsonResponse | RedirectResponse
    {
        // stats to do later
        $statsData = [
            "message" => "stats to do later!"
        ];

        return appResponse($request, $statsData, 200, "admin.page.dashboard_stats");
    }

    // settings page (update settings single record)
    // /dashboard/settings, METHOD=GET
    public function editSettings(Request $request): JsonResponse | RedirectResponse
    {
        $settingsInstance = Setting::instance();
        $data = [
            "model" => $settingsInstance
        ];

        return appResponse($request, $data, 200, "admin.page.dashboard_settings");
    }
    // /admin/settings METHOD=PATCH
    public function updateSettings(MasterCRUDService $crudService, Request $request): JsonResponse | RedirectResponse
    {
        $user = $request->user();
        $settingsInstance = Setting::instance();
        if (!$settingsInstance) return appResponse($request, ["message" => "Settings instance not found!"], 404);

        $mResponse = $crudService->update("settings", $settingsInstance->id, $request->all(), $user);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // index (list filtered) model
    // /dashboard/model/{table}?query , METHOD=GET
    public function indexRecords(MasterCRUDService $crudService, Request $request, string $table): JsonResponse | RedirectResponse
    {
        $page = $request->query("page", 1);
        $perPage = $request->query("per_page", 30);
        $mResponse = $crudService->readMany($table, $request->getQueryString(), $request->user(), $page, $perPage);

        return appResponse($request, $mResponse->data, $mResponse->status, "admin.page.dashboard_records_index", ["table" => $table]);
    }

    // create (create new record)
    // /dashboard/model/{table}/create, METHOD=GET
    public function createRecord(MasterCRUDService $crudService, Request $request, string $table): JsonResponse | RedirectResponse
    {
        $newModel = $crudService->getNewModelInstance($table);
        $data = [
            "new_model" => $newModel
        ];

        return appResponse($request, $data, $newModel == null ? 400 : 200, "admin.page.dashboard_record_create", ["table" => $table]);
    }
    // /admin/model/{table}/create METHOD=POST
    public function storeRecord(MasterCRUDService $crudService, Request $request, string $table): JsonResponse | RedirectResponse
    {
        $mResponse = $crudService->create($table, $request->all(), $request->user());

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // edit (edit record)
    // /dashboard/model/{table}/{id}, METHOD=GET
    public function editRecord(MasterCRUDService $crudService, Request $request, string $table, string $id): JsonResponse | RedirectResponse
    {
        $mResponse = $crudService->read($table, $id, $request->user());

        return appResponse($request, $mResponse->data, $mResponse->status, "admin.page.dashboard.models.edit", ["table" => $table, "id" => $id]);
    }
    // /admin/model/{table}/{id} METHOD=PATCH
    public function updateRecord(MasterCRUDService $crudService, Request $request, string $table, string $id): JsonResponse | RedirectResponse
    {
        $mResponse = $crudService->update($table, $id, $request->all(), $request->user());

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // destroy (delete record(s))
    // /admin/model/{table}/{id} METHOD=DELETE
    public function deleteRecords(MasterCRUDService $crudService, Request $request, string $table, string $id): JsonResponse | RedirectResponse
    {
        $mResponse = $crudService->delete($table, $id, $request->user());

        return appResponse($request, $mResponse->data, $mResponse->status);
    }
}
