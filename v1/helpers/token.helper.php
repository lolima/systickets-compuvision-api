<?php

class HeaderHelper
{
    public static function verify($auth)
    {
        //**********************
        //* Verify token expiration and authenticity
        //**********************
        $token = explode(".", $auth[0]);
        $payload = base64_decode($token[1]);
        $jsonObj = json_decode($payload);

        if ($jsonObj->exp > $jsonObj->auth_time && $jsonObj->aud != "") {
            return true;
        } else {

            return false;
        }
    }

    public static function getToken($auth)
    {
        //**********************
        //* Verify token expiration and authenticity
        //**********************
        $token = explode(".", $auth[0]);
        $payload = base64_decode($token[1]);
        $jsonObj = json_decode($payload);

        return $jsonObj;
    }

    public static function getLanguage($header) {
        return $header[0];
    }
}