<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 9/23/2020
 * Time: 9:37 PM
 */

namespace app\core;


class Response
{

    public function setStatusCode(int $code){
        http_response_code($code);
    }

    public function returnResponse($res,$code){
        http_response_code($code);
        return json_encode($res);
    }

}