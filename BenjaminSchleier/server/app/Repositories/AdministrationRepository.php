<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 11/4/2021
 * Time: 8:19 AM
 */

namespace App\Repositories;

use App\Models\UserInfo;
use App\Utilities\AppConstant;
use Database\Factories\UserInfoFactory;
use Database\Factories\UserRoleFactory;
use Illuminate\Support\Facades\DB;

class AdministrationRepository
{

    public function saveSignUpToken($data){

        $res = [];

        DB::beginTransaction();
        try{


            $isExist = UserInfo::where("email",$data["email"])->exists();

            if($isExist){

                $res["msg"] = "This email already got the invitation link!";
                $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;

            }else{
                $userInfo = new UserInfo();
                $userInfo->email = $data["email"];
                $userInfo->token = $data["token"];
                $userInfo->user_role = self::getUserRoleId("User");
                $userInfo->save();

                DB::commit();

                $res["msg"] = "Email and token save successfully!";
                $res["code"] = AppConstant::$SUCCESS_RESPONSE_CODE;
            }


        }catch (\Exception $e){
            DB::rollBack();
            $res["msg"] = "Something went wrong, please try again!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;

        }

        return $res;
    }

    private static function getUserRoleId($roleName)
    {

        $roleId = null;

        $userRoles = UserRoleFactory::getData();

        foreach ($userRoles as $key => $val) {
            if(strtolower(str_replace(' ', '',$val['role_name']))
                == strtolower(str_replace(' ', '',$roleName))){
                $roleId = $val['role_id'];
                break;
            }
        }

        return $roleId;

    }

}