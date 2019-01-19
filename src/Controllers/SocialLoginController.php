<?php

namespace AwesIO\Auth\Controllers;

use Illuminate\Http\Request;
use AwesIO\Auth\Controllers\Controller;

class SocialLoginController extends Controller
{
    public function redirect(Request $request, $service)
    {
        dd($service);
    }

    public function callback(Request $request, $service)
    {
        dd($service);
    }
}
