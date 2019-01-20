<?php

namespace AwesIO\Auth\Controllers;

use Illuminate\Http\Request;
use AwesIO\Auth\Controllers\Controller;
use AwesIO\Auth\Repositories\Contracts\UserRepository;
use AwesIO\Auth\Services\Contracts\SocialProvidersManager;

class SocialLoginController extends Controller
{
    protected $users;

    protected $provider;

    public function __construct(
        UserRepository $users, 
        SocialProvidersManager $provider
    )
    {
        $this->users = $users;

        $this->provider = $provider;
    }

    /**
     * Redirect to OAuth provider authentication page
     *
     * @param \Illuminate\Http\Request $request
     * @param string $service
     * @return void
     */
    public function redirect(Request $request, $service)
    {
        return $this->provider
            ->buildProvider($service)
            ->redirect();
    }

    /**
     * Obtain the user information from OAuth provider
     *
     * @param \Illuminate\Http\Request $request
     * @param string $service
     * @return void
     */
    public function callback(Request $request, $service)
    {     
        $serviceUser = $this->provider
            ->buildProvider($service)
            ->user();

        $user = $this->users
            ->getUserBySocial($serviceUser, $service);

        if (! $user) {
            $user = $this->createUser($serviceUser);
        }
        
        if (! $user->hasSocial($service)) {
            $user->social()->create([
                'social_id' => $serviceUser->getId(),
                'service' => $service
            ]);
        }

        //authenticate and redirect
    }

    protected function createUser($serviceUser)
    {
        return $this->users->store([
            // TODO: What about password? make it nullable() ???
            'password' => 'password',
            'name' => $serviceUser->getName() ?: $serviceUser->getNickname(),
            'email' => $serviceUser->getEmail()
        ]);
    }
}
