<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function App\Helpers\appResponse;

// auth requests
class AuthController extends Controller
{

    // /register, METHOD=GET
    public function registerPage(Request $request): Response
    {
        return appResponse($request, [], 200, ["view", "auth.page.register"]);
    }
    // /auth/register, METHOD=POST
    public function register(AuthService $authService, Request $request): Response
    {
        $mResponse = $authService->register($request);

        return appResponse($request, $mResponse->data, $mResponse->status, ["redirect", "common.page.home"]);
    }

    // /login, METHOD=GET
    public function loginPage(Request $request): Response
    {
        return appResponse($request, [], 200, ["view", "auth.page.login"]);
    }
    // /auth/login, METHOD=POST
    public function login(AuthService $authService, Request $request): Response
    {
        $mResponse = $authService->login($request);
        $redirectPage = $request->user()?->isAdmin() ? "admin.page.dashboard_stats" : "common.page.home";

        return appResponse($request, $mResponse->data, $mResponse->status, ["redirect", $redirectPage]);
    }

    // /auth/logout, METHOD=POST
    public function logout(AuthService $authService, Request $request): Response
    {
        $mResponse = $authService->logout($request);

        return appResponse($request, $mResponse->data, $mResponse->status, ["redirect", "common.page.home"]);
    }

    // "/forgot-password", METHOD=GET
    public function forgotPasswordPage(Request $request): Response
    {
        return appResponse($request, [], 200, ["view", "auth.page.forgot_password"]);
    }

    // "/reset-password/{token}", METHOD=GET
    public function resetPasswordPage(Request $request, string $token): Response
    {
        $email = $request->query('email');
        return appResponse($request, ["token" => $token, "email" => $email], 200, ["view", "auth.page.reset_password"]);
    }

    // /auth/reset-password, METHOD=POST
    public function resetPassword(AuthService $authService, Request $request): Response
    {
        $mResponse = $authService->resetPassword($request);

        return appResponse($request, $mResponse->data, $mResponse->status, ["redirect", "auth.page.login"]);
    }

    // /auth/send-password-reset-notification, METHOD=POST
    public function sendPasswordResetNotification(AuthService $authService, Request $request): Response
    {
        $mResponse = $authService->sendPasswordResetNotification($request);

        return appResponse($request, $mResponse->data, $mResponse->status, ["redirect", "common.page.home"]);
    }

    // /auth/send-email-confirmation-notification, METHOD=POST
    public function sendEmailConfirmationNotification(AuthService $authService, Request $request): Response
    {
        $mResponse = $authService->sendEmailConfirmationNotification($request);

        return appResponse($request, $mResponse->data, $mResponse->status, ["redirect", "common.page.home"]);
    }

    // '/email/verify/{id}/{hash}', METHOD=GET
    public function confirmEmail(AuthService $authService, Request $request, string $id, string $hash): Response
    {
        $mResponse = $authService->confirmEmail($request);

        return appResponse($request, $mResponse->data, $mResponse->status, ["redirect", "common.page.home"]);
    }
}
