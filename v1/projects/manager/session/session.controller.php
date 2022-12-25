<?php

use Firebase\JWT\JWT;
use Tuupola\Base62;

class StaffSessionController
{
    public static function checkSignInFields($payload)
    {
        $required = array('login', 'password');

        return FunctionsHelper::checkFields($payload, $required);
    }

    public static function checkSignUpFields($payload)
    {
        $required = array('name', 'email');

        return FunctionsHelper::checkFields($payload, $required);
    }
    public static function comparePasswords($payload, $userInfo)
    {
        $typed = $payload['password'];
        
        $currentPass = $userInfo['password'];
        $pieces = explode("$", $currentPass);
        $alg = $pieces[1]; // sha-512 encryption algorithm
        $cost = $pieces[2]; // computational cost (default = 50000)
        $salt = $pieces[3]; // random string (A-Z|a-z|0-9)

        $hash = crypt($typed, "\$" . $alg . "\$" . $cost . "\$" . $salt);

        if ($currentPass == $hash) {
            return true;
        }

        return false;
    }

    public static function generateToken($payload)
    {

        $now = new DateTime();
        $future = new DateTime("now +100 days");

        $jti = Base62::encode(random_bytes(16));

        $token = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "jti" => $jti,
            "admin" => true,
            "id" => $payload['id']
        ];

        $secret = getenv('SECRET');
        return JWT::encode($token, $secret, "HS256");
    }

    public static function prepareForAPIReturn($payload, $userId = null) {
        unset($payload['password']);
        return $payload;
    }

    public static function checkRequiredPermission($permissions, $required) {
        
        foreach ($permissions as $permission) {
            if($permission['idpermission'] == $required) {
                return true;
            }
        }

        return false;
    }




}