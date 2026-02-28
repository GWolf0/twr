<?php

use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function () {
    // routes
    include(__DIR__ . "/apiRoutes.php");
});
