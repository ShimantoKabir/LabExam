<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 11/4/2021
 * Time: 7:07 AM
 */

namespace Database\Factories;


class UserRoleFactory
{

    public static function getData(){

        $userRoles = [
            [
                "role_id" => 101,
                "role_name" => "Admin"
            ],
            [
                "role_id" => 102,
                "role_name" => "User"
            ]
        ];

        return $userRoles;

    }

}