<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use function App\Helpers\appResponse;

// auth requests
class AuthController extends Controller
{

    // /register, METHOD=GET
    public function registerPage(Request $request): JsonResponse | RedirectResponse
    {
        return appResponse($request, [], 200, "auth.page.register");
    }
    // /auth/register, METHOD=POST
    public function register(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $mResponse = $authService->register($request);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // /login, METHOD=GET
    public function loginPage(Request $request): JsonResponse | RedirectResponse
    {
        return appResponse($request, [], 200, "auth.page.login");
    }
    // /auth/login, METHOD=POST
    public function login(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $mResponse = $authService->login($request);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // /auth/logout, METHOD=POST
    public function logout(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $mResponse = $authService->logout($request);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // /reset-password, METHOD=GET
    public function resetPasswordPage(Request $request): JsonResponse | RedirectResponse
    {
        return appResponse($request, [], 200, "auth.page.reset_password");
    }
    // /auth/reset-password, METHOD=POST
    public function resetPassword(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $mResponse = $authService->resetPassword($request);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }
    // /auth/send-password-reset-notification, METHOD=POST
    public function sendPasswordResetNotification(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $mResponse = $authService->sendPasswordResetNotification($request);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // /auth/send-email-confirmation-notification, METHOD=POST
    public function sendEmailConfirmationNotification(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $mResponse = $authService->sendEmailConfirmationNotification($request);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // /auth/confirm-email, METHOD=POST
    public function confirmEmail(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $mResponse = $authService->confirmEmail($request);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }
}
