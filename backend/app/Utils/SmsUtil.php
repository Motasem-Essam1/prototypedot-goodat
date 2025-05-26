<?php

namespace App\Utils;

use Nexmo\Laravel\Facade\Nexmo;

class SmsUtil
{

    public function sendVerificationCode(string $phoneNumber,string $code):bool{
        $text = "Thanks for your interest, your verify code is: " . $code;
        try {
            Nexmo::message()->send([
                'to'   => $phoneNumber,
                'from' => env('APP_NAME'),
                'text' => $text
            ]);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

}
