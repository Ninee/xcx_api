<?php

namespace App\Http\Middleware;

use Closure;

class YrjjToken
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
        if ($request->json('apiToken') != env('YRJJ_TOKEN')) {
            return response()->json('access denied!', 401);
        }
        return $next($request);
    }
}
