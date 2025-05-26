<?php

namespace App\Http\Middleware;

use App\Utils\SmsUtil;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhoneVerify
{
    private $smsUtil;
    public function __construct(SmsUtil $smsUtil)
    {
        $this->smsUtil = $smsUtil;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!isset(Auth::user()->phone_number)){
            return redirect()->route('account.account')->withErrors(['phone'=>'Please set your phone number']);
        }
        if (!isset(Auth::user()->phone_verify_at)){
            $phone_number = Auth::user()->country_code . Auth::user()->phone_number;
            $this->smsUtil->sendVerificationCode($phone_number, Auth::user()->user_data->verify_code);
            return redirect('verify-phone');
        }
        return $next($request);
    }
}
