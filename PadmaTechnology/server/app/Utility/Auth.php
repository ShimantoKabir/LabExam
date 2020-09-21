<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 9/21/2020
 * Time: 2:15 PM
 */

namespace App\Utility;


use App\User;

class Auth
{

    public static function isValid($authUser)
    {

        $res = [
            'msg' => '',
            'code' => ''
        ];

        try{

            $users = User::where('email', $authUser['email'])->where('sessionId', $authUser['sessionId'])->get();

            if (count($users) > 0){
                $res['msg'] = "User authentication successful.";
                $res['code'] = 200;
            }else {
                $res['msg'] = "User authentication unsuccessful.";
                $res['code'] = 404;
            }


        }catch (\Exception $e){
            $res['msg'] = $e->getMessage();
            $res['code'] = $e->getCode();
        }

        return $res;

    }
}