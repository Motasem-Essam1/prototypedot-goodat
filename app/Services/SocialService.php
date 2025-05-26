<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserData;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class SocialService
{
    private $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handleSocial(array $social_user)
    {
        try {
            $find_user = UserData::where('provider_id', $social_user['provider_id'])->first();
            if($find_user){
                $user = User::find($find_user->user_id);
                return $user;
            }else{
                $new_user = new User();
                $new_user->name = $social_user['name'];
                $new_user->email = $social_user['email'];
                $new_user->password = Hash::make(Str::random(10));
                $new_user->save();
                $user_data = new UserData();
                $user_data->user_id = $new_user->id;
                $user_data->provider_id = $social_user['provider_id'];
                $user_data->provider_type = $social_user['provider'];
                $user_data->generated_Code = $this->userService->generateUserInviteCode();
                $user_data->verify_code = $this->userService->generateVerificationCode();
                if ($social_user['provider'] == 'facebook')
                    $user_data->avatar = $social_user['avatar'] . '&access_token=' . $social_user['provider_id'];
                else
                    $user_data->avatar = $social_user['avatar'];
                $user_data->save();
                return $new_user;
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

}
