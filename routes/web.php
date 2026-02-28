<?php

use Illuminate\Support\Facades\Route;

Route::middleware('throttle:60,1')->group(function () {
    // routes
    include(__DIR__ . "/mainRoutes.php");
});
