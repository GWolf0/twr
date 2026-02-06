<?php

/**
 * Api routes
 */

use App\Http\Controllers\CRUDController;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function () {
    // include main routes (reason: most handles json response)
    include(__DIR__. "/mainRoutes.php");

    // crud api routes
    Route::name("crud.")->prefix("/crud")->middleware(["user.role:admin"])->group(function () {
        // show
        Route::get("/{table}/{id}", [CRUDController::class, "show"])->name("show");

        // index
        Route::get("/{table}", [CRUDController::class, "index"])->name("index");

        // store
        Route::post("/{table}", [CRUDController::class, "store"])->name("store");

        // update
        Route::patch("/{table}/{id}", [CRUDController::class, "update"])->name("update");

        // destroy
        Route::delete("/{table}/{id}", [CRUDController::class, "destroy"])->name("destroy");
    });

    // file upload api routes
    Route::name("file_upload.")->prefix("/file-upload")->middleware(["user.role:admin"])->group(function () {});

    // booking api routes
    Route::name("booking.")->prefix("/booking")->middleware(["auth"])->group(function () {});

    // misc api routes
    Route::name("misc.")->prefix("/misc")->group(function () {});
});
