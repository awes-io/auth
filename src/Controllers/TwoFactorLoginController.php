<?php

namespace AwesIO\Auth\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RedirectsUsers;
use AwesIO\Auth\Controllers\Traits\RedirectsTo;
use AwesIO\Auth\Requests\TwoFactorVerifyRequest;

class TwoFactorLoginController extends Controller
{
    use RedirectsUsers, RedirectsTo;
    
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    
    /**
     * Show the application's 2FA's token verification form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('awesio-auth::twofactor.verify');
    }

    /**
     * Verify two factor token
     *
     * @param \AwesIO\Auth\Requests\TwoFactorVerifyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function verify(TwoFactorVerifyRequest $request)
    {
        Auth::loginUsingId($request->user()->id, session('two_factor')->remember);

        session()->forget('two_factor');

        return redirect()->intended($this->redirectPath());
    }
}
