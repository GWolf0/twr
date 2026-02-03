<?php

namespace App\Http\Controllers;

use App\Services\CRUD\MasterCRUDService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// controller for models crud (rest api only)
// expect route format excluding "/api" to be "/api_version/crud/table_name/...params"
class CRUDController extends Controller
{

    // show (read), GET
    public function show(MasterCRUDService $crudService, Request $request, string $table, string $id): JsonResponse
    {
        $mResponse = $crudService->read($table, $id, $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    // index (readMany), GET
    public function index(MasterCRUDService $crudService, Request $request, string $table): JsonResponse
    {
        $mResponse = $crudService->readMany($table, $request->getQueryString(), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    // store (create), POST
    public function store(MasterCRUDService $crudService, Request $request, string $table): JsonResponse
    {
        $mResponse = $crudService->create($table, $request->all(), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    // update (update), PATCH
    public function update(MasterCRUDService $crudService, Request $request, string $table, string $id): JsonResponse
    {
        $mResponse = $crudService->update($table, $id, $request->all(), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    // destroy (delete), DELETE
    public function destroy(MasterCRUDService $crudService, Request $request, string $table, string $ids): JsonResponse
    {
        $mResponse = $crudService->delete($table, $ids, $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }
}
