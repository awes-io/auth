<?php

namespace AwesIO\Auth\Controllers;

use Illuminate\Http\Request;
use AwesIO\Auth\Controllers\Controller;

class TwoFactorLoginController extends Controller
{
    public function verify()
    {
        dd(1);
        return view('awesio-auth::twofactor.verify');
    }
}
