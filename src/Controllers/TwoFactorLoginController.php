<?php

namespace AwesIO\Auth\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use AwesIO\Auth\Requests\TwoFactorVerifyRequest;

class TwoFactorLoginController extends Controller
{
    protected $redirectTo = '/';
    
    public function index()
    {
        return view('awesio-auth::twofactor.verify');
    }

    public function verify(TwoFactorVerifyRequest $request)
    {
        Auth::loginUsingId($request->user()->id, session('two_factor')->remember);

        session()->forget('two_factor');

        return redirect()->intended($this->redirectTo);
    }
}
