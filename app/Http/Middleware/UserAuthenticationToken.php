<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class UserAuthenticationToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $input = $request->all();
        return $next($request);
    }
}
