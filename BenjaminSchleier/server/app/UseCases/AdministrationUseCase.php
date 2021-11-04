<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 11/4/2021
 * Time: 7:56 AM
 */

namespace App\UseCases;

use App\Helpers\TokenGenerator;
use App\Repositories\AdministrationRepository;
use App\Utilities\AppConstant;
use App\Utilities\PHPMailSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AdministrationUseCase
{

    private static $administrationRepository = null;

    public function __construct()
    {

        self::$administrationRepository = new AdministrationRepository();

    }


    public function sendSingUpEmail(Request $request){

        $res = [];

        if($request->has("email")){

            $email = $request->input("email");

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $token = TokenGenerator::generate();

                $repoRes = self::$administrationRepository->saveSignUpToken([
                   "token"=>$token,
                   "email"=>$email
                ]);

                if($repoRes["code"] == AppConstant::$SUCCESS_RESPONSE_CODE){

                    $mailDate = [
                        "email" => $email,
                        "body" => "Sing up invitation link: ". $request->getSchemeAndHttpHost()
                            . "/api/users/sing-up?token=" . $token
                            . "&email=" . $email,
                        "subject" => "Sing up Invitation!"
                    ];

                    PHPMailSender::send($mailDate);

                    $res["msg"] = "Please check you mail, a sing up invitation has been sent to you mail!";
                    $res["code"] = AppConstant::$SUCCESS_RESPONSE_CODE;

                }else{
                    $res = $repoRes;
                }

            }else{
                $res["msg"] = "Email address not in correct format!";
                $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
            }

        }else{
            $res["msg"] = "Email input field missing!";
            $res["code"] = AppConstant::$ERROR_RESPONSE_CODE;
        }

        return response()->json($res, 200);

    }

}