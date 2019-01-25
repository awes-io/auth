<?php

namespace AwesIO\Auth\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait AuthenticatesUsersWith2FA
{
    /**
     * Handle 2FA, stores user data in session, logs out and redirects
     *
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\Response
     */
    protected function handleTwoFactorAuthentication(Request $request, $user)
    {
        if ($user->isTwoFactorEnabled()) {

            $request->session()->put('two_factor', (object) [
                'user_id' => $user->id,
                'remember' => $request->has('remember')
            ]);

            Auth::logout();

            return redirect()->route('login.twofactor.index');
        }
    }
}
