<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

use function App\Helpers\appResponse;

// auth requests
class AuthController extends Controller
{

    // /register, METHOD=GET
    public function registerPage(Request $request): Response
    {
        return appResponse($request, [], 200, "auth.page.register");
    }
    // /auth/register, METHOD=POST
    public function register(AuthService $authService, Request $request): Response
    {
        $mResponse = $authService->register($request);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // /login, METHOD=GET
    public function loginPage(Request $request): Response
    {
        return appResponse($request, [], 200, "auth.page.login");
    }
    // /auth/login, METHOD=POST
    public function login(AuthService $authService, Request $request): Response
    {
        $mResponse = $authService->login($request);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // /auth/logout, METHOD=POST
    public function logout(AuthService $authService, Request $request): Response
    {
        $mResponse = $authService->logout($request);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // "/reset-password/{token}", METHOD=GET
    public function resetPasswordPage(Request $request): Response
    {
        return appResponse($request, [], 200, "auth.page.reset_password");
    }
    // /auth/reset-password, METHOD=POST
    public function resetPassword(AuthService $authService, Request $request): Response
    {
        $mResponse = $authService->resetPassword($request);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }
    // /auth/send-password-reset-notification, METHOD=POST
    public function sendPasswordResetNotification(AuthService $authService, Request $request): Response
    {
        $mResponse = $authService->sendPasswordResetNotification($request);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // /auth/send-email-confirmation-notification, METHOD=POST
    public function sendEmailConfirmationNotification(AuthService $authService, Request $request): Response
    {
        $mResponse = $authService->sendEmailConfirmationNotification($request);

        return appResponse($request, $mResponse->data, $mResponse->status);
    }

    // '/email/verify/{id}/{hash}', METHOD=GET
    public function confirmEmail(AuthService $authService, Request $request): Response
    {
        $mResponse = $authService->confirmEmail($request);

        return appResponse($request, $mResponse->data, $mResponse->status, "auth.page.confirm_email");
    }
}
