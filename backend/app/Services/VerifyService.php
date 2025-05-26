<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserData;
use DateTime;

class VerifyService
{

    public function verifyPhoneNumber(int $user_id,string $code): array
    {
        $data = [];
        try {
            $user = User::find($user_id);
            $user_data = UserData::where('user_id',$user_id)->first();
            if (isset($user->phone_verify_at)){
                $data['status'] = false;
                $data['massage'] = "Phone Number Is Verify";
                return $data;
            }
            if ($user_data->verify_code == $code){
                $user->phone_verify_at = new DateTime();
                $user->save();
                $data['status'] = true;
                $data['massage'] = "";
                return $data;
            }
            $data['status'] = false;
            $data['massage'] = "Invalid Code";
            return $data;
        }catch (\Exception $ex){
            $data['status'] = false;
            $data['massage'] = $ex->getMessage();
            return $data;
        }
    }

}
