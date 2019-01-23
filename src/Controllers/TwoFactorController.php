<?php

namespace AwesIO\Auth\Controllers;

use Illuminate\Http\Request;
use AwesIO\Auth\Models\Country;
use App\Http\Controllers\Controller;
use AwesIO\Auth\Services\Contracts\TwoFactor;
use AwesIO\Auth\Requests\TwoFactorStoreRequest;
use AwesIO\Auth\Requests\TwoFactorVerifyRequest;

class TwoFactorController extends Controller
{
    /**
     * Show the application's two factor setup form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::all();
        
        return view('awesio-auth::twofactor.index', compact('countries'));
    }

    /**
     * Store new user's two factor record
     *
     * @param \AwesIO\Auth\Requests\TwoFactorStoreRequest $request
     * @param \AwesIO\Auth\Services\Contracts\TwoFactor $twoFactor
     * @return void
     */
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

    /**
     * Verify two factor token
     *
     * @param \AwesIO\Auth\Requests\TwoFactorVerifyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function verify(TwoFactorVerifyRequest $request)
    {
        $request->user()->twoFactor()->update([
            'verified' => true
        ]);

        return back();
    }

    /**
     * Remove user from two factor service and application's db
     *
     * @param \Illuminate\Http\Request $request
     * @param \AwesIO\Auth\Services\Contracts\TwoFactor $twoFactor
     * @return void
     */
    public function destroy(Request $request, TwoFactor $twoFactor)
    {
        if ($twoFactor->remove($user = $request->user())) {

            $user->twoFactor()->delete();
        }
        return back();
    }
}
