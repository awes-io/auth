<?php

namespace AwesIO\Auth\Middlewares;

use Closure;

class SocialAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $this->isServiceAvailable($request->service)) {
            return redirect()->route('login');
        }
        return $next($request);
    }

    protected function isServiceAvailable($service)
    {
        return in_array(
            strtolower($service), 
            array_keys(config('awesio-auth.socialite.services'))
        );
    }
}
