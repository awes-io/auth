<?php

namespace AwesIO\Auth\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use AwesIO\Auth\Facades\Auth as AwesAuth;

trait AuthenticatesUsersWith2FA
{
    protected function isTwoFactorEnabled($user)
    {
        return AwesAuth::isTwoFactorEnabled() && $user->isTwoFactorEnabled();
    }

    /**
     * Handle 2FA, stores user data in session, logs out and redirects
     *
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\Response
     */
    protected function handleTwoFactorAuthentication(Request $request, $user)
    {
        $request->session()->put('two_factor', (object) [
            'user_id' => $user->id,
            'remember' => $request->has('remember')
        ]);

        Auth::logout();

        if ($request->ajax()) {
            return response()->json([
                'redirectUrl' => route('login.twofactor.index', [], false)
            ]);
        }

        return redirect()->route('login.twofactor.index');
    }
}
