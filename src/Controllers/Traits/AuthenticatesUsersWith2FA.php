<?php

namespace AwesIO\Auth\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait AuthenticatesUsersWith2FA
{
    protected function handleTwoFactorAuthentication(Request $request, $user)
    {
        if ($user->isTwoFactorEnabled()) {

            $request->session()->put('two_factor', (object) [
                'user_id' => $user->id,
                'remember' => $request->has('remember')
            ]);

            Auth::logout();

            // redirect
        }
    }
}
