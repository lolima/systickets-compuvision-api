<?php

class AdminSessionQueries
{
    public static function staffInfo($idstaff)
    {
        $sql = "SELECT * FROM users WHERE id = '$idstaff' AND active = 1";
        return FunctionsHelper::prepareQuery($sql);
    }

    public static function staffCheckFullInfo($requestData)
    {
        $email = $requestData['login'];

        $sql = "SELECT * FROM users WHERE email = '$email' AND active = 1";
        return FunctionsHelper::prepareQuery($sql);
    }
}
