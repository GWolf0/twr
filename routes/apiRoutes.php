<?php

/**
 * Api routes
 */

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CRUDController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\MiscController;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->name("api")->group(function () {
    // include main routes (reason: most handles json response)
    include(__DIR__ . "/mainRoutes.php");

    // crud api routes
    Route::name("crud.")->prefix("/crud")->middleware(["auth:sanctum", "user.role:admin"])->group(function () {
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
    Route::name("file_upload.")->prefix("/file-upload")->middleware(["auth:sanctum", "user.role:admin"])->group(function () {
        // get uploaded files details
        Route::get("/", [FileUploadController::class, "getUploadedFiles"])->name("index");

        // upload file
        Route::post("/", [FileUploadController::class, "uploadFile"])->name("upload_single");

        // upload files
        Route::post("/many", [FileUploadController::class, "uploadFiles"])->name("upload_many");

        // delete file
        Route::delete("/{id}", [FileUploadController::class, "deleteFile"])->name("delete_single");

        // delete files
        Route::delete("/many/{ids}", [FileUploadController::class, "deleteFiles"])->name("delete_many");

        // move file
        Route::put("/{id}", [FileUploadController::class, "moveFile"])->name("move_single");

        // move files
        Route::put("/many/{ids}", [FileUploadController::class, "moveFiles"])->name("move_many");
    });

    // booking api routes
    Route::name("booking.")->prefix("/booking")->middleware(["auth:sanctum"])->group(function () {
        // can book
        Route::post("/can-book", [BookingController::class, "canBook"])->name("can_book");

        // calculate amout
        Route::post("/calculate", [BookingController::class, "calculate"])->name("calculate");

        // create
        Route::post("/", [BookingController::class, "create"])->middleware("user.role:customer")->name("create");

        // confirm
        Route::post("/{booking_id}/confirm", [BookingController::class, "confirm"])->middleware("user.role:admin")->name("confirm");

        // cancel
        Route::post("/{booking_id}/cancel", [BookingController::class, "cancel"])->middleware("user.role:admin")->name("cancel");

        // complete
        Route::post("/{booking_id}/complete", [BookingController::class, "complete"])->middleware("user.role:admin")->name("complete");

        // refund
        Route::post("/{booking_id}/refund", [BookingController::class, "refund"])->middleware("user.role:admin")->name("refund");

        // delete
        Route::delete("/{booking_id}", [BookingController::class, "delete"])->middleware("user.role:admin")->name("delete");
    });

    // misc api routes
    Route::name("misc.")->prefix("/misc")->group(function () {
        // get fk values
        Route::get("/fk-values/{table}/column", [MiscController::class, "fkValues"])->name("fk_values");
    });
});
