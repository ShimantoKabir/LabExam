<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 11/4/2021
 * Time: 9:05 AM
 */

namespace App\UseCases;

use App\Repositories\UserInfoRepository;
use App\Utilities\AppConstant;
use App\Utilities\PHPMailSender;
use Illuminate\Http\Request;

class UserInfoUseCase
{

    private static $userInfoRepository = null;

    public function __construct()
    {

        self::$userInfoRepository = new UserInfoRepository();

    }

    public function singUp(Request $request){

        $res = [];

        $password = $request->input("password");

        if(!$request->has("token") || empty($request->input("token"))
            || is_null($request->input("token"))){
            $res["msg"] = "Token input missing!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }else if (!$request->has("username") || empty($request->input("username"))
            || is_null($request->input("username"))){
            $res["msg"] = "Username input missing!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }else if (!$request->has("password") || empty($request->input("password"))
            || is_null($request->input("password"))){
            $res["msg"] = "Password input missing!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }else if (!$request->has("email") || empty($request->input("email"))
            || is_null($request->input("password"))){
            $res["msg"] = "Email input missing!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }else if(strlen($password) < AppConstant::$PASSWORD_MIN_LENGTH){
            $res["msg"] = "Password length must be greater then or equal "
                .AppConstant::$PASSWORD_MIN_LENGTH." and less then or equal " . AppConstant::$PASSWORD_MAX_LENGTH;
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        } else if(strlen($password) > AppConstant::$PASSWORD_MAX_LENGTH) {
            $res["msg"] = "Password length must be greater then or equal "
                .AppConstant::$PASSWORD_MIN_LENGTH." and less then or equal " . AppConstant::$PASSWORD_MAX_LENGTH;
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        } else{

            $token = $request->input("token");
            $username = $request->input("username");
            $email = $request->input("email");

            $data = [
                "token" => $token,
                "email" => $email,
                "password" => $password,
                "username" => $username
            ];

            $tokenRes = self::$userInfoRepository->checkToken($data);

            if($tokenRes["code"] == AppConstant::$SUCCESS_RESPONSE_CODE){

                $singUpRes = self::$userInfoRepository->signUp($data);

                if($singUpRes["code"] == AppConstant::$SUCCESS_RESPONSE_CODE){

                    $mailData = [
                        "body" => "PIN: ".$singUpRes["pin"]
                            . " | Verification link: "
                            . $request->getSchemeAndHttpHost() . "/api/users/verification?pin="
                            . $singUpRes["pin"]
                            . "&email=" . $email,
                        "subject" => "User Verification!",
                        "email" => $email
                    ];

                    PHPMailSender::send($mailData);

                    $res["msg"] = "A six digit pin and verification link has been sent to your mail, please click the link and verify you account!";
                    $res["code"] = AppConstant::$SUCCESS_RESPONSE_CODE;

                }else{
                    $res = $singUpRes;
                }

            }else{

                $res = $tokenRes;

            }

        }

        return response()->json($res, 200);

    }

    public function verification(Request $request)
    {

        $res = [];

        if(!$request->has("pin") || empty($request->input("pin"))
            || is_null($request->input("pin"))){
            $res["msg"] = "Pin parameter missing!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }else if (!$request->has("email") || empty($request->input("email"))
            || is_null($request->input("email"))){
            $res["msg"] = "Email parameter missing!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }else {

            $pin = $request->query('pin');
            $email = $request->query('email');

            $res = self::$userInfoRepository->verify([
                "pin" => $pin,
                "email" => $email
            ]);

        }

        return response()->json($res, 200);

    }

    public function login(Request $request)
    {

        $res = [];

        if(!$request->has("email") || empty($request->input("email"))
            || is_null($request->input("email"))){
            $res["msg"] = "Email input missing!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }else if (!$request->has("password") || empty($request->input("password"))
            || is_null($request->input("password"))){
            $res["msg"] = "Password input missing!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }else {

            $email = $request->input("email");
            $password = $request->input("password");

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $res = self::$userInfoRepository->login([
                    "email" => $email,
                    "password" => $password
                ]);

            }else{
                $res["msg"] = "Email address not in correct format!";
                $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
            }

        }

        return response()->json($res, 200);

    }


    public function update(Request $request)
    {

        $res = [];
        $token = $request->bearerToken();

        if(is_null($token)) {
            $res["msg"] = "Auth token is missing!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }else if(!$request->has("name") || empty($request->input("name"))
            || is_null($request->input("name"))){
            $res["msg"] = "Name input missing!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }else if(!$request->hasFile("avatar")){
            $res["msg"] = "Avatar file missing!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }else {

            $dimension = getimagesize($request->file('avatar'));

            if($dimension[0] != AppConstant::$IMAGE_DIMENSION && $dimension[0] != AppConstant::$IMAGE_DIMENSION){

                $res["msg"] = "Image dimension should be " . AppConstant::$IMAGE_DIMENSION . "px * "
                    .AppConstant::$IMAGE_DIMENSION."px";
                $res["code"] = AppConstant::$SUCCESS_RESPONSE_CODE;

            }else {

                $tokenRes = self::$userInfoRepository->getUserInfoByToken([
                   "token" => $token
                ]);

                if(is_null($tokenRes)){
                    $res["msg"] = "Invalid token!";
                    $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
                }else {

                    if(!is_null($tokenRes->avatar)){
                        $fileName = public_path()."/images/" . $tokenRes->avatar;
                        unlink($fileName);
                    }

                    $avatarName = time().'.'.$request->avatar->getClientOriginalExtension();
                    $request->avatar->move(public_path('images'), $avatarName);

                    $res = self::$userInfoRepository->update([
                        "token" => $token,
                        "name" =>  $request->input("name"),
                        "avatar" => $avatarName
                    ]);

                }

            }

        }

        return response()->json($res, 200);

    }

    public function read(Request $request)
    {

        $avatarBaseUrl = $request->getSchemeAndHttpHost()."/images/";

        return self::$userInfoRepository->read([
            "avatarBaseUrl" => $avatarBaseUrl
        ]);
    }

}