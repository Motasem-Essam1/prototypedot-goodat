<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Services\VerifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VerifyPhoneController extends Controller
{
    private $verifyService;
    public function __construct(VerifyService $verifyService)
    {
        $this->verifyService = $verifyService;
    }

    public function __invoke(){
        if (isset(Auth::user()->phone_verify_at)){
            return redirect('/');
        }
        $phone_number = Auth::user()->country_code . Auth::user()->phone_number;
        return view('auth.verify-phone', ['phone_number' => $phone_number]);
    }

    public function verifyNumber(Request $request){
        $code = implode('', $request->code);
        $user_id = Auth::user()->getAuthIdentifier();
        $response = $this->verifyService->verifyPhoneNumber($user_id, $code);
        if ($response['status']){
            Session::flash('title','Congratulations');
            Session::flash('massage','Your phone number is Verify');
            return redirect()->route('verification.congratulations');
        }else{
            return redirect()->back()->with(['massage' => $response['massage']]);
        }
    }


}
