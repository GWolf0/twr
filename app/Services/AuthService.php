<?php

namespace App\Services;

use App\Interfaces\IAuthInterface;
use App\Models\User;
use App\Types\MResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Validator;

class AuthService implements IAuthInterface
{
    public function register(Request $request): MResponse
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $data = $validator->validated();

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($request->expectsJson()) {
            $token = $user->createToken('auth_token')->plainTextToken;
        }

        return MResponse::create([
            'message' => 'User registered successfully.',
            'user'    => $user,
            'token'   => $token
        ], 201);
    }

    public function login(Request $request): MResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $credentials = $validator->validated();

        if (!Auth::attempt($credentials)) {
            return MResponse::create([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $request->session()->regenerate();

        $user = $request->user();
        $response = [
            'message' => 'Login successful.',
            'user'    => $user,
        ];

        // Stateless (API / separated frontend)
        if ($request->expectsJson()) {
            $response['token'] = $user->createToken('auth_token')->plainTextToken;
        }

        return MResponse::create($response);
    }

    public function logout(Request $request): MResponse
    {
        $user = $request->user();

        // Token logout (stateless)
        if ($request->expectsJson() && $user?->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        // Session logout (stateful)
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return MResponse::create([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function sendPasswordResetNotification(Request $request): MResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return MResponse::create([
            'message' => __($status),
        ], $status === Password::RESET_LINK_SENT ? 200 : 400);
    }

    public function resetPassword(Request $request): MResponse
    {
        $request->validate([
            'email'                 => 'required|email',
            'token'                 => 'required|string',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return MResponse::create([
            'message' => __($status),
        ], $status === Password::PASSWORD_RESET ? 200 : 400);
    }

    public function sendEmailConfirmationNotification(Request $request): MResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return MResponse::create([
                'message' => 'Email already verified.',
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return MResponse::create([
            'message' => 'Verification email sent.',
        ]);
    }

    public function confirmEmail(Request $request): MResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return MResponse::create([
                'message' => 'Email already verified.',
            ], 400);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return MResponse::create([
            'message' => 'Email verified successfully.',
        ]);
    }
}
