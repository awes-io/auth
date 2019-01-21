<?php

namespace AwesIO\Auth\Controllers;

use Illuminate\Http\Request;
use AwesIO\Auth\Models\Country;
use AwesIO\Auth\Controllers\Controller;
use AwesIO\Auth\Services\Contracts\TwoFactor;

class TwoFactorController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        
        return view('awesio-auth::twofactor.index', compact('countries'));
    }

    public function store(Request $request, TwoFactor $twoFactor)
    {
        $twoFactor->register($request->user());
    }
}
