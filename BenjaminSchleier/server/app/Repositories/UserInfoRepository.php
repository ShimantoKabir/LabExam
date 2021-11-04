<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 11/4/2021
 * Time: 9:21 AM
 */

namespace App\Repositories;


use App\Helpers\TokenGenerator;
use App\Models\UserInfo;
use App\Utilities\AppConstant;
use Illuminate\Support\Facades\DB;

class UserInfoRepository
{

    public function checkToken($data){

        $res = [];

        try{

            $isExist = UserInfo::where("email",$data["email"])
                ->where("token",$data["token"])
                ->exists();

            $res["msg"] = $isExist ? "Token valid!" : "Token invalid!";
            $res["code"] = $isExist ? AppConstant::$SUCCESS_RESPONSE_CODE : AppConstant::$ERROR_RESPONSE_CODE;

        }catch (\Exception $e){
            $res["msg"] = "Token invalid!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }

        return $res;

    }

    public function signUp($data){

        $res = [];

        DB::beginTransaction();
        try{

            $sixDigitPin = random_int(100000, 999999);

            UserInfo::where('email', $data["email"])
            ->update([
                "user_name" => $data["username"],
                "password" => sha1($data["password"]),
                "pin" => $sixDigitPin,
                "registered_at" => date('Y-m-d H:i:s')
            ]);

            DB::commit();

            $res["pin"] = $sixDigitPin;
            $res["msg"] = "Sing up successfully!";
            $res["code"] = AppConstant::$SUCCESS_RESPONSE_CODE;

        }catch (\Exception $e){
            DB::rollBack();
            $res["msg"] = "Some went wrong, please try again!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }

        return $res;

    }

    public function verify($data){

        $res = [];

        DB::beginTransaction();
        try{


            $isExist = UserInfo::where("email",$data["email"])
                ->where("pin",$data["pin"])
                ->exists();

            if($isExist){

                UserInfo::where('email', $data["email"])
                    ->where("pin", $data["pin"])
                    ->update([
                        "is_verified" => 1
                    ]);

                DB::commit();

                $res["msg"] = "Account verified successfully!";
                $res["code"] = AppConstant::$SUCCESS_RESPONSE_CODE;

            }else{

                $res["msg"] = "This pin is not belongs to any account!";
                $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;

            }

        }catch (\Exception $e){
            DB::rollBack();
            $res["msg"] = "Some went wrong, please try again!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }

        return $res;

    }

    public function login($data){

        $res = [];

        DB::beginTransaction();
        try{

            $userInfo = UserInfo::where("email",$data["email"])
                ->where("password",sha1($data["password"]))
                ->first();

            if(is_null($userInfo)){

                $res["msg"] = "Email and password incorrect!";
                $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;

            }else{

                if($userInfo->is_verified == 1){

                    $token = TokenGenerator::generate();

                    UserInfo::where('email', $data["email"])
                        ->where("password", sha1($data["password"]))
                        ->update([
                            "token" => $token
                        ]);

                    DB::commit();

                    $res["msg"] = "Login successful!";
                    $res["token"] = $token;
                    $res["code"] = AppConstant::$SUCCESS_RESPONSE_CODE;

                }else{

                    $res["msg"] = "Account not verified yet!";
                    $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;

                }

            }

        }catch (\Exception $e){
            DB::rollBack();
            $res["msg"] = "Some went wrong, please try again!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }

        return $res;

    }

    public function getUserInfoByToken($data){
        return UserInfo::where("token",$data["token"])->first();
    }

    public function update($data){


        $res = [];

        DB::beginTransaction();
        try{

            UserInfo::where('token', $data["token"])
                ->update([
                    "name" => $data["name"],
                    "avatar" => $data["avatar"],
                ]);

            DB::commit();

            $res["msg"] = "User info updated successful!";
            $res["code"] = AppConstant::$SUCCESS_RESPONSE_CODE;

        }catch (\Exception $e){
            DB::rollBack();
            $res["msg"] = "Some went wrong, please try again!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }

        return $res;

    }

    public function read($data){

        $avatarBaseUrl = $data["avatarBaseUrl"];

        return DB::table('user_infos')
            ->join('user_roles', 'user_roles.role_id', '=',
                'user_infos.user_role')
            ->select(
                'user_infos.name',
                'user_infos.email',
                DB::raw("IF(user_infos.is_verified = 1,'Yes','No') as isVerified"),
                DB::raw("CONCAT('$avatarBaseUrl', user_infos.avatar) AS avatar"),
                'user_infos.user_name',
                'user_roles.role_name'

            )
            ->get();

    }

}