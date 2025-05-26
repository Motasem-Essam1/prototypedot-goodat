<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserData;
use App\Providers\RouteServiceProvider;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialHandlerController extends Controller
{
    private $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Create a new controller instance.
     *
     * @return
     */
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {

        try {
            $social_user = Socialite::driver($provider)->user();
            $onlyEmailTrashed = User::where('email', $social_user->getEmail())->onlyTrashed()->first();

            // echo json_encode($onlyEmailTrashed);
            // die();

            if ($onlyEmailTrashed) {
                $response = "email is deleted";
                return redirect()->route('signup')->withErrors($response);
            }

            $find_user = UserData::where('provider_id', $social_user->id)->first();

            if($find_user) {
                $user = User::find($find_user->user_id);
                Auth::login($user);
                return redirect()->intended(RouteServiceProvider::HOME);
            } else {
                $check_user_email  = User::where('email', $social_user->getEmail())->first();
                if(empty($check_user_email)) {
                    $new_user = new User();
                    $new_user->name = $social_user->getName();
                    $new_user->email = $social_user->getEmail();
                    $new_user->password = Hash::make(Str::random(10));
                    $new_user->save();
                    $user_data = new UserData();
                    $user_data->user_id = $new_user->id;
                    $user_data->provider_id = $social_user->getId();
                    $user_data->provider_type = $provider;
                    $user_data->generated_Code = $this->userService->generateUserInviteCode();
                    $user_data->verify_code = $this->userService->generateVerificationCode();
                    if ($provider == 'facebook')
                        $user_data->avatar = $social_user->getAvatar() . '&access_token=' . $social_user->token;
                    else
                        $user_data->avatar = $social_user->getAvatar();
                    $user_data->save();
                    Auth::login($new_user);
                    return redirect()->intended(RouteServiceProvider::HOME);
                }
                else {
                    $response = "email is already use in phone email";
                    return redirect()->route('signup')->withErrors($response);
                }
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
