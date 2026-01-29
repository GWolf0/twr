<?php

namespace App\Http\Controllers\CRUD;

use App\Http\Controllers\Controller;
use App\Services\CRUD\MasterCRUDService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


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

        if ($request->expectsJson()) {
            return response()->json($stats_data, 200);
        }

        return response()->redirectToRoute("admin.dashboard.stats");
    }

    // settings page (update settings single record)
    // /dashboard/settings, METHOD=GET
    public function editSettings(MasterCRUDService $crudService, Request $request): JsonResponse | RedirectResponse
    {
        $user = $request->user();
        $settings_instance = $crudService->read("settings", 1, $user);
        $data = [
            "model" => $settings_instance
        ];

        if ($request->expectsJson()) {
            return response()->json($data, 200);
        }

        return response()->redirectToRoute("admin.dashboard.settings");
    }
    // /admin/settings METHOD=PATCH
    public function updateSettings(MasterCRUDService $crudService, Request $request): JsonResponse | RedirectResponse
    {
        $user = $request->user();
        $m_response = $crudService->update("settings", 1, $request->all(), $user);

        if ($request->expectsJson()) {
            return response()->json($m_response->data, $m_response->status);
        }

        return redirect()->back()->with("data", $m_response->data);
    }

    // index (list filtered) model
    // /dashboard/model/{table}?query , METHOD=GET
    public function indexRecords(MasterCRUDService $crudService, Request $request, string $table): JsonResponse | RedirectResponse
    {
        $m_response = $crudService->readMany($table, $request->getQueryString(), $request->user());

        if ($request->expectsJson()) {
            return response()->json($m_response->data, $m_response->status);
        }

        return response()->redirectToRoute(
            "admin.dashboard.models.index",
            ["table" => $table]
        )->with("data", $m_response->data);
    }

    // create (create new record)
    // /dashboard/model/{table}/create, METHOD=GET
    public function createRecord(MasterCRUDService $crudService, Request $request, string $table): JsonResponse | RedirectResponse
    {
        $new_model = $crudService->get_new_model_instance($table);
        $data = [
            "new_model" => $new_model
        ];

        if ($request->expectsJson()) {
            return response()->json($data, $new_model == null ? 400 : 200);
        }

        return response()->redirectToRoute(
            "admin.dashboard.models.create",
            ["table" => $table]
        )->with("data", $data);
    }
    // /admin/model/{table}/create METHOD=POST
    public function storeRecord(MasterCRUDService $crudService, Request $request, string $table): JsonResponse | RedirectResponse
    {
        $m_response = $crudService->create($table, $request->all(), $request->user());

        if ($request->expectsJson()) {
            return response()->json($m_response->data, $m_response->status);
        }

        return redirect()->back()->with("data", $m_response->data);
    }

    // edit (edit record)
    // /dashboard/model/{table}/{id}, METHOD=GET
    public function editRecord(MasterCRUDService $crudService, Request $request, string $table, string $id): JsonResponse | RedirectResponse
    {
        $m_response = $crudService->read($table, $id, $request->user());

        if ($request->expectsJson()) {
            return response()->json($m_response->data, $m_response->status);
        }

        return response()->redirectToRoute(
            "admin.dashboard.models.edit",
            ["table" => $table, "id" => $id]
        )->with("data", $m_response->data);
    }
    // /admin/model/{table}/{id} METHOD=PATCH
    public function updateRecord(MasterCRUDService $crudService, Request $request, string $table, string $id): JsonResponse | RedirectResponse
    {
        $m_response = $crudService->update($table, $id, $request->all(), $request->user());

        if ($request->expectsJson()) {
            return response()->json($m_response->data, $m_response->status);
        }

        return redirect()->back()->with("data", $m_response->data);
    }

    // destroy (delete record(s))
    // /admin/model/{table}/{ids} METHOD=DELETE
    public function deleteRecords(MasterCRUDService $crudService, Request $request, string $table, string $ids): JsonResponse | RedirectResponse
    {
        $m_response = $crudService->delete($table, $ids, $request->user());

        if ($request->expectsJson()) {
            return response()->json($m_response->data, $m_response->status);
        }

        return redirect()->back()->with("data", $m_response->data);
    }
}
