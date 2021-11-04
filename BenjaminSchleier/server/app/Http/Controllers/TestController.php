<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 11/4/2021
 * Time: 6:28 AM
 */

namespace App\Http\Controllers;

use App\Utilities\PHPMailSender;
use Illuminate\Http\Request;


class TestController
{

    public function test(Request $request){
        return [
            "code" => 200,
            "msg" => "App is running...!"
        ];
    }

}