<?php

namespace AwesIO\Auth\Controllers;

use Illuminate\Http\Request;
use AwesIO\Auth\Models\Country;
use AwesIO\Auth\Controllers\Controller;
use AwesIO\Auth\Services\Contracts\TwoFactor;
use AwesIO\Auth\Requests\TwoFactorStoreRequest;
use AwesIO\Auth\Requests\TwoFactorVerifyRequest;

class TwoFactorController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        
        return view('awesio-auth::twofactor.index', compact('countries'));
    }

    public function store(TwoFactorStoreRequest $request, TwoFactor $twoFactor)
    {
        $user = $request->user();

        $user->twoFactor()->create([
            'phone' => $request->phone,
            'dial_code' => $request->dial_code,
        ]);

        if ($response = $twoFactor->register($user)) {
            $user->twoFactor()->update([
                'identifier' => $response->user->id
            ]);
        }
        return back();
    }

    public function verify(TwoFactorVerifyRequest $request)
    {
        dd('OK');
    }
}
