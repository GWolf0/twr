<?php

namespace App\Http\Controllers;

use App\Services\CRUD\MasterCRUDService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// controller for models crud (rest api only)
// expect route format excluding "/api" to be "/api_version/table_name/?..params"
class CRUDController extends Controller
{

    // show (read)
    public function show(MasterCRUDService $crudService, Request $request, string $id): JsonResponse
    {
        $table = $request->segment(2);

        // crudService accepts nullable table (if null return error response)
        $m_response = $crudService->read($table, $id, $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    // index (readMany)
    public function index(MasterCRUDService $crudService, Request $request): JsonResponse
    {
        $table = $request->segment(2);

        $m_response = $crudService->readMany($table, $request->getQueryString(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    // store (create)
    public function store(MasterCRUDService $crudService, Request $request): JsonResponse
    {
        $table = $request->segment(2);

        $m_response = $crudService->create($table, $request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    // update (update)
    public function update(MasterCRUDService $crudService, Request $request, string $id): JsonResponse
    {
        $table = $request->segment(2);

        $m_response = $crudService->update($table, $id, $request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    // destroy (delete)
    public function destroy(MasterCRUDService $crudService, Request $request, string $ids): JsonResponse
    {
        $table = $request->segment(2);

        $m_response = $crudService->delete($table, $ids, $request->user());

        return response()->json($m_response->data, $m_response->status);
    }
}
