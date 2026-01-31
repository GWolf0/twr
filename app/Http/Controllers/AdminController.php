<?php

namespace App\Http\Controllers\CRUD;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\CRUD\MasterCRUDService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use function App\Helpers\app_response;

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
        $stats_data = [
            "message" => "stats to do later!"
        ];

        return app_response($request, $stats_data, 200, "admin.dashboard.stats");
    }

    // settings page (update settings single record)
    // /dashboard/settings, METHOD=GET
    public function editSettings(MasterCRUDService $crudService, Request $request): JsonResponse | RedirectResponse
    {
        $settings_instance = Setting::instance();
        $data = [
            "model" => $settings_instance
        ];

        return app_response($request, $data, 200, "admin.dashboard.settings");
    }
    // /admin/settings METHOD=PATCH
    public function updateSettings(MasterCRUDService $crudService, Request $request): JsonResponse | RedirectResponse
    {
        $user = $request->user();
        $m_response = $crudService->update("settings", 1, $request->all(), $user);

        return app_response($request, $m_response->data, $m_response->status);
    }

    // index (list filtered) model
    // /dashboard/model/{table}?query , METHOD=GET
    public function indexRecords(MasterCRUDService $crudService, Request $request, string $table): JsonResponse | RedirectResponse
    {
        $m_response = $crudService->readMany($table, $request->getQueryString(), $request->user());

        return app_response($request, $m_response->data, $m_response->status, "admin.dashboard.models.index", ["table" => $table]);
    }

    // create (create new record)
    // /dashboard/model/{table}/create, METHOD=GET
    public function createRecord(MasterCRUDService $crudService, Request $request, string $table): JsonResponse | RedirectResponse
    {
        $new_model = $crudService->get_new_model_instance($table);
        $data = [
            "new_model" => $new_model
        ];

        return app_response($request, $data, $new_model == null ? 400 : 200, "admin.dashboard.models.create", ["table" => $table]);
    }
    // /admin/model/{table}/create METHOD=POST
    public function storeRecord(MasterCRUDService $crudService, Request $request, string $table): JsonResponse | RedirectResponse
    {
        $m_response = $crudService->create($table, $request->all(), $request->user());

        return app_response($request, $m_response->data, $m_response->status);
    }

    // edit (edit record)
    // /dashboard/model/{table}/{id}, METHOD=GET
    public function editRecord(MasterCRUDService $crudService, Request $request, string $table, string $id): JsonResponse | RedirectResponse
    {
        $m_response = $crudService->read($table, $id, $request->user());

        return app_response($request, $m_response->data, $m_response->status, "admin.dashboard.models.edit", ["table" => $table, "id" => $id]);
    }
    // /admin/model/{table}/{id} METHOD=PATCH
    public function updateRecord(MasterCRUDService $crudService, Request $request, string $table, string $id): JsonResponse | RedirectResponse
    {
        $m_response = $crudService->update($table, $id, $request->all(), $request->user());

        return app_response($request, $m_response->data, $m_response->status);
    }

    // destroy (delete record(s))
    // /admin/model/{table}/{ids} METHOD=DELETE
    public function deleteRecords(MasterCRUDService $crudService, Request $request, string $table, string $ids): JsonResponse | RedirectResponse
    {
        $m_response = $crudService->delete($table, $ids, $request->user());

        return app_response($request, $m_response->data, $m_response->status);
    }
}
