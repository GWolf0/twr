<?php

namespace App\Http\Controllers;

use App\Interfaces\IAuthInterface;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

// auth requests
class AuthController extends Controller
{

    // /register, METHOD=GET
    public function registerPage(Request $request): JsonResponse | RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json();
        }

        return response()->redirectToRoute("auth.register_page");
    }
    // /auth/register, METHOD=POST
    public function register(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $m_response = $authService->register($request);

        if ($request->expectsJson()) {
            return response()->json($m_response->data, $m_response->status);
        }

        return redirect()->back()->with("data", $m_response->data);
    }

    // /login, METHOD=GET
    public function loginPage(Request $request): JsonResponse | RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json();
        }

        return response()->redirectToRoute("auth.login_page");
    }
    // /auth/login, METHOD=POST
    public function login(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $m_response = $authService->login($request);

        if ($request->expectsJson()) {
            return response()->json($m_response->data, $m_response->status);
        }

        return redirect()->back()->with("data", $m_response->data);
    }

    // /auth/logout, METHOD=POST
    public function logout(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $m_response = $authService->logout($request);

        if ($request->expectsJson()) {
            return response()->json($m_response->data, $m_response->status);
        }

        return redirect()->back()->with("data", $m_response->data);
    }

    // /reset-password, METHOD=GET
    public function resetPasswordPage(Request $request): JsonResponse | RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json();
        }

        return response()->redirectToRoute("auth.reset_password_page");
    }
    // /auth/send-password-reset-notification, METHOD=POST
    public function sendPasswordResetNotification(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $m_response = $authService->sendPasswordResetNotification($request);

        if ($request->expectsJson()) {
            return response()->json($m_response->data, $m_response->status);
        }

        return redirect()->back()->with("data", $m_response->data);
    }

    // /auth/reset-password, METHOD=POST
    public function resetPassword(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $m_response = $authService->resetPassword($request);

        if ($request->expectsJson()) {
            return response()->json($m_response->data, $m_response->status);
        }

        return redirect()->back()->with("data", $m_response->data);
    }

    // /auth/send-email-confirmation-notification, METHOD=POST
    public function sendEmailConfirmationNotification(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $m_response = $authService->sendEmailConfirmationNotification($request);

        if ($request->expectsJson()) {
            return response()->json($m_response->data, $m_response->status);
        }

        return redirect()->back()->with("data", $m_response->data);
    }

    // /auth/confirm-email, METHOD=POST
    public function confirmEmail(AuthService $authService, Request $request): JsonResponse | RedirectResponse
    {
        $m_response = $authService->confirmEmail($request);

        if ($request->expectsJson()) {
            return response()->json($m_response->data, $m_response->status);
        }

        return redirect()->back()->with("data", $m_response->data);
    }
}
