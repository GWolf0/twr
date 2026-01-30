<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
 * @param string|null $page           Frontend page identifier
 * @param array $authorizations       UI permissions map
 */
function app_response(
    Request $request,
    array $data = [],
    int $status = 200,
    ?string $redirectRoute = null,
    array $redirectParams = [],
    ?string $page = null,
    array $authorizations = []
): JsonResponse|RedirectResponse {
    $payload = array_merge($data, [
        'user' => $request->user(),
        'page' => $page,
        'authorizations' => $authorizations,
    ]);

    if ($request->expectsJson()) {
        return response()->json($payload, $status);
    }

    return $redirectRoute
        ? redirect()->route($redirectRoute, $redirectParams, $status)->with('data', $payload)
        : redirect()->back($status)->with('data', $payload);
}
