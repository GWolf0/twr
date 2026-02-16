<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\CRUD\MasterCRUDService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use function App\Helpers\appResponse;

// controller for admin allowed actions only
// includes pages GET requests
// and other actions
class AdminController extends Controller
{

    // stats page (default dashboard page)
    // /dashboard | /dashboard/stats, METHOD=GET
    public function stats(Request $request): JsonResponse | RedirectResponse
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfToday = $now->copy()->startOfDay();

        // ================= USERS =================
        $totalUsers = User::where('role', 'customer')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $newUsersThisMonth = User::where('role', 'customer')
            ->where('created_at', '>=', $startOfMonth)
            ->count();

        // ================= VEHICLES =================
        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('availability', 'available')->count();
        $maintenanceVehicles = Vehicle::where('availability', 'maintenance')->count();
        $unavailableVehicles = Vehicle::where('availability', 'unavailable')->count();

        // ================= BOOKINGS =================
        $totalBookings = Booking::count();

        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $canceledBookings = Booking::where('status', 'canceled')->count();

        // Active bookings (currently ongoing)
        $activeBookings = Booking::where('status', 'confirmed')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->count();

        // ================= REVENUE =================
        $totalRevenue = Booking::where('payment_status', 'paid')
            ->sum('total_amount');

        $monthlyRevenue = Booking::where('payment_status', 'paid')
            ->where('created_at', '>=', $startOfMonth)
            ->sum('total_amount');

        $todayRevenue = Booking::where('payment_status', 'paid')
            ->where('created_at', '>=', $startOfToday)
            ->sum('total_amount');

        $refundedAmount = Booking::where('payment_status', 'refunded')
            ->sum('total_amount');

        // ================= PAYMENT METHODS =================
        $paymentMethodsStats = Booking::selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->pluck('count', 'payment_method');

        // ================= MOST BOOKED VEHICLE =================
        $mostBookedVehicle = Booking::selectRaw('vehicle_id, COUNT(*) as total')
            ->whereNotNull('vehicle_id')
            ->groupBy('vehicle_id')
            ->orderByDesc('total')
            ->with('vehicle')
            ->first();

        $statsData = [
            'users' => [
                'total_customers' => $totalUsers,
                'total_admins' => $totalAdmins,
                'new_this_month' => $newUsersThisMonth,
            ],

            'vehicles' => [
                'total' => $totalVehicles,
                'available' => $availableVehicles,
                'maintenance' => $maintenanceVehicles,
                'unavailable' => $unavailableVehicles,
            ],

            'bookings' => [
                'total' => $totalBookings,
                'pending' => $pendingBookings,
                'confirmed' => $confirmedBookings,
                'completed' => $completedBookings,
                'canceled' => $canceledBookings,
                'active_now' => $activeBookings,
            ],

            'revenue' => [
                'total' => $totalRevenue,
                'this_month' => $monthlyRevenue,
                'today' => $todayRevenue,
                'refunded' => $refundedAmount,
            ],

            'payment_methods' => $paymentMethodsStats,

            'most_booked_vehicle' => $mostBookedVehicle?->vehicle?->name,
        ];

        return appResponse($request, $statsData, 200, "admin.page.dashboard_stats");
    }

    // settings page (update settings single record)
    // /dashboard/settings, METHOD=GET
    public function editSettings(Request $request): JsonResponse | RedirectResponse
    {
        $settingsInstance = Setting::instance();
        $data = [
            "model" => $settingsInstance
        ];

        return appResponse($request, $data, 200, "admin.page.dashboard_settings");
    }
    // /admin/settings METHOD=PATCH
    public function updateSettings(MasterCRUDService $crudService, Request $request): JsonResponse | RedirectResponse
    {
        $user = $request->user();
        $settingsInstance = Setting::instance();
        if (!$settingsInstance) return appResponse($request, ["message" => "Settings instance not found!"], 404);

        $mResponse = $crudService->update("settings", $settingsInstance->id, $request->all(), $user);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // index (list filtered) model
    // /dashboard/model/{table}?query , METHOD=GET
    public function indexRecords(MasterCRUDService $crudService, Request $request, string $table): JsonResponse | RedirectResponse
    {
        $page = $request->query("page", 1);
        $perPage = $request->query("per_page", 30);
        $mResponse = $crudService->readMany($table, $request->getQueryString(), $request->user(), $page, $perPage);

        return appResponse($request, $mResponse->data, $mResponse->status, "admin.page.dashboard_records_index", ["table" => $table]);
    }

    // create (create new record)
    // /dashboard/model/{table}/create, METHOD=GET
    public function createRecord(MasterCRUDService $crudService, Request $request, string $table): JsonResponse | RedirectResponse
    {
        $newModel = $crudService->getNewModelInstance($table);
        $data = [
            "new_model" => $newModel
        ];

        return appResponse($request, $data, $newModel == null ? 400 : 200, "admin.page.dashboard_record_create", ["table" => $table]);
    }
    // /admin/model/{table}/create METHOD=POST
    public function storeRecord(MasterCRUDService $crudService, Request $request, string $table): JsonResponse | RedirectResponse
    {
        $mResponse = $crudService->create($table, $request->all(), $request->user());

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // edit (edit record)
    // /dashboard/model/{table}/{id}, METHOD=GET
    public function editRecord(MasterCRUDService $crudService, Request $request, string $table, string $id): JsonResponse | RedirectResponse
    {
        $mResponse = $crudService->read($table, $id, $request->user());

        return appResponse($request, $mResponse->data, $mResponse->status, "admin.page.dashboard_record_edit", ["table" => $table, "id" => $id]);
    }
    // /admin/model/{table}/{id} METHOD=PATCH
    public function updateRecord(MasterCRUDService $crudService, Request $request, string $table, string $id): JsonResponse | RedirectResponse
    {
        $mResponse = $crudService->update($table, $id, $request->all(), $request->user());

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // destroy (delete record(s))
    // /admin/model/{table}/{id} METHOD=DELETE
    public function deleteRecords(MasterCRUDService $crudService, Request $request, string $table, string $id): JsonResponse | RedirectResponse
    {
        $mResponse = $crudService->delete($table, $id, $request->user());

        return appResponse($request, $mResponse->data, $mResponse->status);
    }
}
