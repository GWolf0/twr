<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use function App\Helpers\app_response;

// auth requests
class AuthController extends Controller
{

    // /register, METHOD=GET
    public function registerPage(Request $request): JsonResponse | RedirectResponse
    {
        return app_response($request, [], 200, "auth.register_page");
    }
    // /auth/register, METHOD=POST
    public function register(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $m_response = $authService->register($request);

        return app_response($request, $m_response->data, $m_response->status);
    }

    // /login, METHOD=GET
    public function loginPage(Request $request): JsonResponse | RedirectResponse
    {
        return app_response($request, [], 200, "auth.login_page");
    }
    // /auth/login, METHOD=POST
    public function login(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $m_response = $authService->login($request);

        return app_response($request, $m_response->data, $m_response->status);
    }

    // /auth/logout, METHOD=POST
    public function logout(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $m_response = $authService->logout($request);

        return app_response($request, $m_response->data, $m_response->status);
    }

    // /reset-password, METHOD=GET
    public function resetPasswordPage(Request $request): JsonResponse | RedirectResponse
    {
        return app_response($request, [], 200, "auth.reset_password_page");
    }
    // /auth/reset-password, METHOD=POST
    public function resetPassword(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $m_response = $authService->resetPassword($request);

        return app_response($request, $m_response->data, $m_response->status);
    }
    // /auth/send-password-reset-notification, METHOD=POST
    public function sendPasswordResetNotification(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $m_response = $authService->sendPasswordResetNotification($request);

        return app_response($request, $m_response->data, $m_response->status);
    }

    // /auth/send-email-confirmation-notification, METHOD=POST
    public function sendEmailConfirmationNotification(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $m_response = $authService->sendEmailConfirmationNotification($request);

        return app_response($request, $m_response->data, $m_response->status);
    }

    // /auth/confirm-email, METHOD=POST
    public function confirmEmail(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $m_response = $authService->confirmEmail($request);

        return app_response($request, $m_response->data, $m_response->status);
    }
}
