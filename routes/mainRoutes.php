<?php

/**
 * Main app routes
 * Routes returning responses for both web and api routes
 */

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// auth (authentication reltated routes)
Route::name("auth.")->group(function () {
    // requires auth
    Route::middleware("auth")->group(function () {
        // logout
        Route::post("/auth/logout", [AuthController::class, "logout"])->name("action.logout");

        // send email confirmation notification action
        Route::post("/auth/send-email-confirmation-notification", [AuthController::class, "sendEmailConfirmationNotification"])->name("action.send_email_confirmation_notification");

        // confirm email action
        Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'confirmEmail'])->middleware(['signed', 'throttle:6,1'])->name('action.confirm_email');
    });

    // requires guest
    Route::middleware("guest")->group(function () {
        // register page
        Route::get("/register", [AuthController::class, "registerPage"])->name("page.register");

        // register action
        Route::post("/auth/register", [AuthController::class, "register"])->name("action.register");

        // login page
        Route::get("/login", [AuthController::class, "loginPage"])->name("page.login");

        // login action
        Route::post("/auth/login", [AuthController::class, "login"])->name("action.login");

        // 
        Route::get("/forgot-password", [AuthController::class, "forgotPasswordPage"])->name("page.forgot_password");

        // reset password page (redirected from received email)
        Route::get("/reset-password/{token}", [AuthController::class, "resetPasswordPage"])->name("page.reset_password");

        // reset password action
        Route::post("/auth/reset-password", [AuthController::class, "resetPassword"])->name("action.reset_password");

        // send password reset notification action
        Route::post("/auth/send-password-reset-notification", [AuthController::class, "sendPasswordResetNotification"])->name("action.send_password_reset_notification");
    });
});

// common (routes available to guest or any auth user)
Route::name("common.")->group(function () {
    // home page
    Route::get("/", [CommonController::class, "homePage"])->name("page.home");

    // search page
    Route::get("/search", [CommonController::class, "searchPage"])->name("page.search");

    // vehicle details
    Route::get("/vehicles/{vehicle_id}", [CommonController::class, "vehicleDetailsPage"])->name("page.vehicle_details");

    // email confirmed page
    Route::get("/email-confirmed", [CommonController::class, "emailConfirmedPage"])->name("page.email_confirmed");

    // display ui
    Route::get("/ui", fn() => view("common.page.ui"))->name("page.ui");
});

// admin (routes for admin users only)
Route::name("admin.")->middleware(["auth:sanctum", "user.role:admin"])->group(function () {
    // stats page
    Route::get("/dashboard/{stats?}", [AdminController::class, "stats"])->name("page.dashboard_stats")->where("stats", "stats");

    // settings page
    Route::get("/dashboard/settings", [AdminController::class, "editSettings"])->name("page.dashboard_settings");

    // update setting action
    Route::patch("/admin/settings", [AdminController::class, "updateSettings"])->name("action.update_settings");

    // index page (list of records)
    Route::get("/dashboard/model/{table}", [AdminController::class, "indexRecords"])->name("page.dashboard_records_index");

    // create record page
    Route::get("/dashboard/model/{table}/create", [AdminController::class, "createRecord"])->name("page.dashboard_record_create");

    // create record action
    Route::post("/admin/model/{table}/create", [AdminController::class, "storeRecord"])->name("action.store_record");

    // edit record page
    Route::get("/dashboard/model/{table}/{id}", [AdminController::class, "editRecord"])->name("page.dashboard_record_edit");

    // update record action
    Route::patch("/admin/model/{table}/{id}", [AdminController::class, "updateRecord"])->name("action.update_record");

    // delete record action
    Route::delete("/admin/model/{table}/{id}", [AdminController::class, "deleteRecords"])->name("action.delete_record");
});

// customer (routes for customer users only)
Route::name("customer.")->middleware("user.role:customer")->group(function () {
    // book vehicle page
    Route::get("/bookings/vehicles/{vehicle_id}", [CustomerController::class, "bookVehiclePage"])->name("page.book_vehicle");

    // bookings list page
    Route::get("/bookings", [CustomerController::class, "bookingsListPage"])->name("page.bookings_list");

    // booking details page
    Route::get("/bookings/{booking_id}", [CustomerController::class, "bookingDetailsPage"])->name("page.booking_details");

    // profile page
    Route::get("/profile", [CustomerController::class, "profilePage"])->name("page.profile");

    // perform booking
    Route::post("/customer/bookings", [CustomerController::class, "book"])->name("action.book");

    // cancel booking
    Route::post("/customer/bookings/{booking_id}/cancel", [CustomerController::class, "cancelBooking"])->name("action.cancel_booking");
});

// payment routes
Route::name("payment.")->middleware("user.role:customer")->group(function () {
    Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name("action.payment_webhook");
    Route::get('/payment/start/{booking}', [PaymentController::class, 'start'])->name("action.start_payment");
    Route::get('/payment/success', [PaymentController::class, 'successPage'])->name("page.success_page");
});
