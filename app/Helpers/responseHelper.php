<?php

namespace App\Helpers;

use App\Types\MResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

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
 * - `fkValues`: frontend values to populate select elements with valid fk options data
 *
 * @param Request $request
 * @param array $data                 Payload to expose to the client
 * @param int $status                 HTTP status code
 * @param string|null $page           The identifier "name" of the page, make sure it matches the view
 * @param array $authorizations       UI permissions map
 * @param array $fkValues             fk fields possible values (if needed in UI)
 */
function appResponse(
    Request $request,
    array | MessageBag $data = [],
    int $status = 200,
    ?array $response = null,
    array $authorizations = [],
    array $fkValues = [],
): Response {
    // responseType (none, view, redirect)
    $responseType = $response[0] ?? "none";
    $page = $response[1] ?? null;
    $pageParams = $response[2] ?? [];

    $sharedPayload = [
        'user' => $request->user()?->only(['id', 'name', 'email', 'role']),
        'page' => $page,
        'status' => $status,
        'authorizations' => $authorizations,
        'fk_values' => $fkValues,
        'merrors' => !is_array($data) ? $data->toArray() : [],
    ];
    $payload = is_array($data) ? array_merge($data, $sharedPayload) : $sharedPayload;

    if ($request->expectsJson()) {
        return response()->json($payload, $status);
    }

    if ($responseType == "view") {
        return response()->view($page, $payload, $status);
    } else if ($responseType == "redirect") {
        if ($status < 400) {
            return redirect()->route($page, $pageParams)->with("message", $payload["message"])->with("status", $status);
        } else {
            return redirect()->back()->withErrors($data)->with("message", $payload["message"])->with("status", $status)->withInput();
        }
    }

    return redirect()->route("common.page.home");
}
// function appResponse(
//     Request $request,
//     array | MessageBag $data = [],
//     int $status = 200,
//     ?string $page = null,
//     array $authorizations = [],
//     array $fkValues = [],
// ): Response {
//     $sharedPayload = [
//         'user' => $request->user()?->only(['id', 'name', 'email', 'role']),
//         'page' => $page,
//         'status' => $status,
//         'authorizations' => $authorizations,
//         'fk_values' => $fkValues,
//         'merrors' => !is_array($data) ? $data->toArray() : [],
//     ];
//     $payload = is_array($data) ? array_merge($data, $sharedPayload) : $sharedPayload;

//     if ($request->expectsJson()) {
//         return response()->json($payload, $status);
//     }

//     if ($page) {
//         if ($page[0] == "/") {
//             return response()->redirectToRoute($page)->with("data", $payload);
//         } else {
//             return response()->view(
//                 ($status < 400 || $status == 422) ? $page : 'common.page.error',
//                 $payload,
//                 $status
//             );
//         }
//     }

//     return redirect()->back();
// }


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
