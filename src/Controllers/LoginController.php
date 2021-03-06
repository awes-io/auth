<?php

namespace AwesIO\Auth\Controllers;

use Illuminate\Http\Request;
use AwesIO\Auth\Controllers\Traits\RedirectsTo;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use AwesIO\Auth\Controllers\Traits\AuthenticatesUsersWith2FA;

class LoginController extends Controller
{
    use AuthenticatesUsers, AuthenticatesUsersWith2FA, RedirectsTo;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('awesio-auth::auth.login');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($this->isTwoFactorEnabled($user)) {
            return $this->handleTwoFactorAuthentication($request, $user);
        }

        if ($request->ajax()) {
            return $this->ajaxRedirectTo($request);
        }
    }
}
