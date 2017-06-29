<?php

namespace App\Http\Middleware;

use Closure;
use Jenssegers\Agent\Agent;

class BrowserMiddleware
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

        $agent = new Agent();
        if( $agent->is('Windows') && $agent->version( $agent->browser() ) <= 9 ){

            return redirect()->guest('browser');

        }

        return $next($request);
    }
}
