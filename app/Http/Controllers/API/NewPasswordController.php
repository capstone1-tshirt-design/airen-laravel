<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return [
                'status' => __($status)
            ];
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', 'min:6', RulesPassword::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response([
                'message' => 'Password reset successfully'
            ], 202);
        }

        return response([
            'message' => __($status)
        ], 500);
    }

    public function change(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:6', RulesPassword::defaults()],
        ]);

        $user = $request->user();
        $user->password = bcrypt($request->password);
        $user->login_count = $user->login_count + 1;
        $user->last_login_at = now();

        $user->save();

        return response(null, 202);
    }
}
