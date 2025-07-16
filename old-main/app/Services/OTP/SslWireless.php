<?php

namespace App\Services\OTP;

use App\Contracts\SendSms;

class SslWireless implements SendSms {
    public function send($to, $from, $text, $template_id)
    {
        $to = ltrim($to, '+'); // Remove leading "+" sign
        
        $url = env("SSL_SMS_URL");
        $token = env("SSL_SMS_API_TOKEN"); //put ssl provided api_token here
        $sid = env("SSL_SMS_SID"); // put ssl provided sid here
        $data = [
            [
                "api_key" => $token,
                "type" => "text",
                "phone" => $to, // Use modified $to without leading "+"
                "senderid" => $sid,
                "message" => $text,
            ]
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data[0]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}