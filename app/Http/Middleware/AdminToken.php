<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth('sanctum')->user()['role'] == "admin" || auth('sanctum')->user()['role'] == "supper admin")
        {
            return $next($request);
        }
        return response()->json('message: wrong Admin token', 401);
    }
}
