<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\ChangePasswordAuthRequest;
use Illuminate\Support\Facades\Hash;
use App\Services\UserService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\LoginAuthRequest;
use App\Http\Requests\verifyTokenAuthRequest;
use App\Http\Requests\ResetPasswordAuthRequest;
use App\Http\Requests\ForgotPasswordAuthRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;

class AuthController extends Controller
{
    public function login(LoginAuthRequest $request)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();
        $token = Auth::attempt($validated);
        return $token ? ApiResponse::success(data: ['access_token' => $token]) : ApiResponse::error('Invalid credentials', 401);
    }

    public function logout()
    {
        Auth::logout();
        return ApiResponse::success(message: 'Successfully logged out');
    }

    public function refresh()
    {
        $token = Auth::refresh();
        return ApiResponse::success(data: ['access_token' => $token]);
    }


    public function changePassword(ChangePasswordAuthRequest $request, UserService $userService)
    {
        $validatedData = $request->safe()->only(['current_password', 'new_password', 'new_password_confirmation']);
        return $userService->changePassword($validatedData);
    }

    public function resetPassword(ResetPasswordAuthRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return  ApiResponse::success(message: __($status));
    }

    public function forgotPassword(ForgotPasswordAuthRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));
        if ($status === Password::RESET_LINK_SENT) {
            return ApiResponse::success(message: 'Reset link sent successfully');
        } else {
            return ApiResponse::error(error: 'Failed to send reset link');
        }
    }

    public function verifyToken(verifyTokenAuthRequest $request)
    {
        $validatedData = $request->safe()->only(['email', 'token']);
        $user = User::where('email', $validatedData['email'])->first();
        if (!$user) {
            return ApiResponse::error(
                error: 'Invalid or expired token.',
                statusCode: 401
            );
        }

        $broker = Password::broker();

        if ($broker->tokenExists($user, $validatedData['token'])) {
            return ApiResponse::success(
                message: 'Token verified successfully',
            );
        } else {
            return ApiResponse::error(
                error: 'Invalid or expired token',
                statusCode: 401
            );
        }
    }
}
