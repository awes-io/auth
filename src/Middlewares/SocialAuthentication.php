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
        dd($request->service);
        return $next($request);
    }
}
