<?php

namespace AwesIO\Auth\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use AwesIO\Auth\Repositories\Contracts\UserRepository;
use AwesIO\Auth\Services\Contracts\SocialProvidersManager;

class SocialLoginController extends Controller
{
    protected $users;

    protected $provider;

    protected $redirectTo = '/';

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
            $this->createUsersSocial($user, $serviceUser, $service);
        }

        Auth::login($user);

        return redirect()->intended($this->redirectTo);
    }

    /**
     * Create new user using service credentials
     *
     * @param \Laravel\Socialite\Two\User $serviceUser
     * @return \App\User
     */
    protected function createUser($serviceUser)
    {
        return $this->users->store([
            'password' => bcrypt(str_random(40)),
            'name' => $serviceUser->getName() ?: $serviceUser->getNickname(),
            'email' => $serviceUser->getEmail()
        ]);
    }

    /**
     * Create new user's social record using service credentials
     *
     * @param \App\User $user
     * @param \Laravel\Socialite\Two\User $serviceUser
     * @param string $service
     * @return \AwesIO\Auth\Models\UserSocial
     */
    protected function createUsersSocial($user, $serviceUser, $service)
    {
        return $user->social()->create([
            'social_id' => $serviceUser->getId(),
            'service' => $service
        ]);
    }
}
