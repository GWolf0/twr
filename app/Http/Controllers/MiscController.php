<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use function App\Helpers\getFKValues;

// json api response only
// miscellanious actions
class MiscController extends Controller
{

    /**
     * GET : /api/v/misc/fk-values/{table}/{column}
     */
    public function fkValues(Request $request, string $table, string $column): JsonResponse
    {
        $page = $request->query("page", 1);
        $perPage = $request->query("per_page", 1);
        $fkValuesResponse = getFKValues($table, $column, $page, $perPage);
        
        return response()->json($fkValuesResponse->data, $fkValuesResponse->status);
    }
}
