<?php

namespace AwesIO\Auth\Controllers;

use Socialite;
use Illuminate\Http\Request;
use AwesIO\Auth\Controllers\Controller;

class SocialLoginController extends Controller
{
    public function redirect(Request $request, $service)
    {
        return $this->buildProvider($service)->redirect();
    }

    public function callback(Request $request, $service)
    {     
        $user = $this->buildProvider($service)->user();

        dd($user);
    }

    protected function buildProvider($service)
    {
        return Socialite::buildProvider(
            $this->providerClassName(ucfirst($service)), 
            $this->getConfig($service)
        );
    }

    protected function providerClassName($prefix)
    {
        return "\Laravel\Socialite\Two\\{$prefix}Provider";
    }

    protected function getConfig($service)
    {
        return config('awesio-auth.socialite.' . $service);
    } 
}
