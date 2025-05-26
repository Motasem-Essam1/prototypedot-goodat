<?php

namespace App\Http\Controllers\Mobile\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Mobile\Auth\LoginSocial;
use App\Http\Resources\UserLoginResource;
use App\Http\Resources\UserResource;
use App\Mail\PasswordResetMail;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Mobile\Auth\LoginRequestApi;
use App\Http\Requests\Mobile\Auth\RegisterRequestApi;
use App\Services\UserService;
use App\Services\VerifyService;
use App\Services\SocialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Models\User;


class AuthController extends BaseController
{
    private $userService;
    private $verifyService;
    private $socialService;

    public function __construct(UserService $userService, VerifyService $verifyService, SocialService $socialService)
    {
        $this->userService = $userService;
        $this->verifyService = $verifyService;
        $this->socialService = $socialService;
    }

    public function login(LoginRequestApi $request): JsonResponse
    {
        if(Auth::attempt(['phone_number' => $request['phone_number'], 'password' => $request['password'], 'country_code' => $request['phone_code']], )){
              $user = UserLoginResource::make(User::where('id' , Auth::id())->with('user_data')->first());
            return $this->sendResponse($user, 'user login successfully.');
        }else {
            return $this->sendError('Unauthorised.', ['Unauthorised']);
        }
    }

    public function register(RegisterRequestApi $request):JsonResponse
    {
        $data = $request->only(['name', 'email', 'phone_number', 'phone_code', 'password', 'invitation_code', 'is_provider']);
        $data['provider'] = 'mobile';
        $data['phoned_Signed'] = true;

        $user = $this->userService->createUser($data);
        event(new Registered($user['data']));
        $result = UserResource::make(User::where('id' , $user['data']->id)->with('user_data')->first());
        if ($user['sms_status']){
            return $this->sendResponse($result, 'confirmation code sent to your number');
        }else{
            return $this->sendResponse($result, 'user created successfully');

        }
    }

    public function verifyNumber(Request $request)
    {
        if($request->user()->currentAccessToken())
        {
            $code = $request->code;
            $user_id = $request->user()->currentAccessToken()->tokenable->id;
            $response = $this->verifyService->verifyPhoneNumber($user_id, $code);
            if ($response['status']){
                return $this->sendResponse([],'confirmed successfully');
            }else{
                return $this->sendError("Can't Verify phone number", [$response['massage']]);
            }
        }
        else{
            return $this->sendError("Can't Verify phone number wrong token", [$response['massage']]);

        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);
        $user_email = $request->only('email');
        $user = User::query()->where('email', $user_email)->first();
//        if($user == "passwords.sent"){

        $status = Password::createToken($user);
        $data = [
            'token' => $status,
            'email' => $user_email
        ];
        Mail::to($user_email)->send(new PasswordResetMail($data));
            return $this->sendResponse(['send successfully'], __("passwords.sent"));
//        }else{
//            return $this->sendError('Can\'t send validation code', ["User Not found"]);
//        }
    }

    public function loginSocial(LoginSocial $request): JsonResponse
    {
        $data = $request->only(['provider_id','name', 'email', 'provider', 'avatar', 'token']);
        $user = $this->socialService->handleSocial($data);
        $token = $user->createToken($request->userAgent())->plainTextToken;
        $user->token = $token;
        $user = $user->only(['id','name', 'email', 'email_verified_at', 'phone_number', 'phone_verify_at', 'country_code', 'token']);
        if ($user){
            return $this->sendResponse($user, 'success');
        }else{
            return $this->sendError('failed.', ['something went wrong']);
        }
    }

}
