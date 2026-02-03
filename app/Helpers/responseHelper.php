<?php

namespace App\Helpers;

use App\Types\MResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Returns a unified application response.
 *
 * - If the request expects JSON, returns a JsonResponse.
 * - Otherwise returns a redirect response with flash data.
 *
 * Automatically appends:
 * - `user`: authenticated user (if any)
 * - `page`: frontend page identifier
 * - `authorizations`: frontend permission map (action => allowed)
 *
 * @param Request $request
 * @param array $data                 Payload to expose to the client
 * @param int $status                 HTTP status code
 * @param string|null $redirectRoute  Route name to redirect to (null = back)
 * @param array $redirectParams       Route parameters
 * @param array $page                 Page identifier
 * @param array $authorizations       UI permissions map
 * @param array $fkValues             fk fields possible values (if needed in UI)
 */
function appResponse(
    Request $request,
    array $data = [],
    int $status = 200,
    ?string $redirectRoute = null,
    array $redirectParams = [],
    ?string $page = null,
    array $authorizations = [],
    array $fkValues = [],
): JsonResponse|RedirectResponse {
    $payload = array_merge($data, [
        'user' => $request->user(),
        'page' => $page ?? $redirectRoute,
        'authorizations' => $authorizations,
        'fk_values' => $fkValues,
    ]);

    if ($request->expectsJson()) {
        return response()->json($payload, $status);
    }

    return $redirectRoute
        ? redirect()->route($redirectRoute, $redirectParams, $status)->with('data', $payload)
        : redirect()->back($status)->with('data', $payload);
}

/**
 * retrieves all records from a table as ["id" => "columnValue", ..]
 */
function getFKValues(string $table, string $column, int $page = 1, int $perPage = 30): MResponse
{
    $query = DB::table($table)->select('id', $column);

    $count = (clone $query)->count();

    $offset = ($page - 1) * $perPage;

    $data = $query->orderBy($column)->limit($perPage)->offset($offset)->pluck($column, 'id');

    return MResponse::create([
        'data' => $data,
        'total' => $count,
        'page' => $page,
        'per_page' => $perPage,
    ], 200);
}
