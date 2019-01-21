<?php

namespace AwesIO\Auth\Controllers;

use Illuminate\Http\Request;
use AwesIO\Auth\Controllers\Controller;

class TwoFactorLoginController extends Controller
{
    public function index()
    {
        return view('awesio-auth::twofactor.verify');
    }

    public function verify(Request $request)
    {
        dd($request->all());
    }
}
