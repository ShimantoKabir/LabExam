<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 11/4/2021
 * Time: 7:34 AM
 */

namespace Database\Factories;

class UserInfoFactory
{

    public static function getData(){

        $userInfos = [
            [
                "name" => "Admin",
                "user_name" => "admin",
                "email" => "admin@mail.com",
                "password" => sha1("123456"),
                "avatar" => "1635986281.jpg",
                "user_role" => 101,
                "registered_at" => date('Y-m-d H:i:s'),
            ]
        ];

        return $userInfos;

    }

}