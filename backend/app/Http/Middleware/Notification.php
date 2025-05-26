<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use \Illuminate\Support\Facades\View;

class Notification
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()){
            $user = auth()->user();
            $user_agent = request()->userAgent();
            $login_devices = $user->device_tokens()->pluck('user_agent')->toArray();
            $has_token = in_array($user_agent, $login_devices);
            if (!$has_token){
                View::share('request_token', true);
            }else{
                View::share('request_token', false);
            }
        }else{
            View::share('request_token', false);
        }
        return $next($request);
    }
}
