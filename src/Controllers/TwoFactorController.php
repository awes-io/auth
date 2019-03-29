<?php

namespace AwesIO\Auth\Controllers;

use Illuminate\Http\Request;
use AwesIO\Auth\Models\Country;
use AwesIO\Auth\Services\Contracts\TwoFactor;
use Illuminate\Foundation\Auth\RedirectsUsers;
use AwesIO\Auth\Controllers\Traits\RedirectsTo;
use AwesIO\Auth\Requests\TwoFactorStoreRequest;
use AwesIO\Auth\Requests\TwoFactorVerifyRequest;

class TwoFactorController extends Controller
{
    use RedirectsUsers, RedirectsTo;

    /**
     * Where to redirect users.
     *
     * @var string
     */
    protected $redirectTo = '/';

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
        $codeAndPhone = preg_split('/\s/', $request->phone, 2);

        $user = $request->user();

        $user->twoFactor()->create([
            'phone' => $codeAndPhone[1],
            'dial_code' => $codeAndPhone[0],
        ]);

        if ($response = $twoFactor->register($user)) {
            $user->twoFactor()->update([
                'identifier' => $response->user->id
            ]);
        }
        return $this->twofactored($request) ?: back();
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

        return $this->twofactored($request) ?: back();
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
        return $this->twofactored($request) ?: back();
    }

    protected function twofactored(Request $request)
    {
        if ($request->ajax()) {
            return $this->ajaxRedirectTo($request);
        }
    }
}
