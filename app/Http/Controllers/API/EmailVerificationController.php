<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Models\User;

class EmailVerificationController extends Controller
{

    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Already Verified'
            ];
        }

        $request->user()->sendEmailVerificationNotification();

        return response(null, 204);
    }

    public function verify(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
            return $request->wantsJson()
                ? response(null, 204) : redirect()->intended(env('SPA_URL') . 'reset-password?verified=1');
        }

        return $request->wantsJson()
            ? response(null, 204)
            : redirect()->intended(env('SPA_URL') . 'reset-password?verified=1');
    }
}
