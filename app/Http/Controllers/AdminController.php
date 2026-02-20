<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\CRUD\MasterCRUDService;
use App\Services\CRUD\SettingCRUDService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function App\Helpers\appResponse;

// controller for admin allowed actions only
// includes pages GET requests
// and other actions
class AdminController extends Controller
{

    // stats page (default dashboard page)
    // /dashboard | /dashboard/stats, METHOD=GET
    public function stats(Request $request): Response
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

        // // ================= REVENUE =================
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

        // // ================= PAYMENT METHODS =================
        $paymentMethodsStats = Booking::selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->pluck('count', 'payment_method');

        // ================= MOST BOOKED VEHICLE =================
        $mostBookedVehicleInfo = Booking::selectRaw('vehicle_id, COUNT(*) as total')->without(["user", "vehicle"])
            ->whereNotNull('vehicle_id')
            ->groupBy('vehicle_id')
            ->orderByDesc('total')
            ->first();

        // $mostBookedVehicle = $mostBookedVehicleInfo
        //     ? Vehicle::where('id', $mostBookedVehicleInfo->vehicle_id)->get()
        //     : null;

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

            'most_booked_vehicle' => null,
            // 'most_booked_vehicle' => $mostBookedVehicle,
        ];

        return appResponse($request, $statsData, 200, ["view", "admin.page.dashboard_stats"]);
    }

    // settings page (update settings single record)
    // /dashboard/settings, METHOD=GET
    public function editSettings(Request $request): Response
    {
        $settingsInstance = Setting::instance();
        $data = [
            "model" => $settingsInstance
        ];

        return appResponse($request, $data, 200, ["view", "admin.page.dashboard_settings"]);
    }
    // /admin/settings METHOD=PATCH
    public function updateSettings(SettingCRUDService $crudService, Request $request): Response
    {
        $user = $request->user();
        $settingsInstance = Setting::instance();
        if (!$settingsInstance) return appResponse($request, ["message" => "Settings instance not found!"], 404, ["redirect", "admin.page.dashboard_settings"]);

        $mResponse = $crudService->update($settingsInstance->id, $request->all(), $user);

        return appResponse($request, $mResponse->data, $mResponse->status, ["redirect", "admin.page.dashboard_settings"]);
    }

    // index (list filtered) model
    // /dashboard/model/{table}?query , METHOD=GET
    public function indexRecords(MasterCRUDService $crudService, Request $request, string $table): Response
    {
        $page = $request->query("page", 1);
        $perPage = $request->query("per_page", 30);
        $mResponse = $crudService->readMany($table, $request->getQueryString(), $request->user(), $page, $perPage);

        return appResponse($request, $mResponse->data, $mResponse->status, ["view", "admin.page.dashboard_records_index", ["table" => $table]]);
    }

    // create (create new record)
    // /dashboard/model/{table}/create, METHOD=GET
    public function createRecord(MasterCRUDService $crudService, Request $request, string $table): Response
    {
        $newModel = $crudService->getNewModelInstance($table);
        $data = [
            "new_model" => $newModel
        ];

        return appResponse($request, $data, $newModel == null ? 400 : 200, ["view", "admin.page.dashboard_record_create", ["table" => $table]]);
    }
    // /admin/model/{table}/create METHOD=POST
    public function storeRecord(MasterCRUDService $crudService, Request $request, string $table): Response
    {
        $mResponse = $crudService->create($table, $request->all(), $request->user());

        return appResponse($request, $mResponse->data, $mResponse->status, ["redirect", "admin.page.dashboard_record_create", ["table" => $table]]);
    }

    // edit (edit record)
    // /dashboard/model/{table}/{id}, METHOD=GET
    public function editRecord(MasterCRUDService $crudService, Request $request, string $table, string $id): Response
    {
        $mResponse = $crudService->read($table, $id, $request->user());

        return appResponse($request, $mResponse->data, $mResponse->status, ["view", "admin.page.dashboard_record_edit", ["table" => $table, "id" => $id]]);
    }
    // /admin/model/{table}/{id} METHOD=PATCH
    public function updateRecord(MasterCRUDService $crudService, Request $request, string $table, string $id): Response
    {
        $mResponse = $crudService->update($table, $id, $request->all(), $request->user());

        return appResponse($request, $mResponse->data, $mResponse->status, ["redirect", "admin.page.dashboard_record_edit", ["table" => $table, "id" => $id]]);
    }

    // destroy (delete record(s))
    // /admin/model/{table}/{id} METHOD=DELETE
    public function deleteRecords(MasterCRUDService $crudService, Request $request, string $table, string $id): Response
    {
        $mResponse = $crudService->delete($table, $id, $request->user());

        return appResponse($request, $mResponse->data, $mResponse->status, ["redirect", "admin.page.dashboard_records_index", ["table" => $table]]);
    }
}
