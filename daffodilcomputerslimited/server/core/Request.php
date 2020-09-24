<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 9/23/2020
 * Time: 8:19 PM
 */

namespace app\core;


class Request
{

    public function getPath()
    {

        $path = $_SERVER['REQUEST_URI'];

        $position = strpos($path,'?');

        if ($position === false){
            return $path;
        }else{
            return  substr($path,0,$position);
        }

    }

    public function getMethod(){


        $method = $_SERVER['REQUEST_METHOD'];
        return $method;

    }

    public function getBody(){

        $body = [];

        if ($this->getMethod() === 'GET'){

            foreach ($_GET as $key=>$value){

                $body[$key] = filter_input(INPUT_GET,$key,FILTER_SANITIZE_SPECIAL_CHARS);

            }

        }else if ($this->getMethod() === 'POST'){

            foreach ($_POST as $key=>$value){

                filter_input(INPUT_POST,$key,FILTER_SANITIZE_SPECIAL_CHARS);

            }

            $body = file_get_contents('php://input');

        }else if ($this->getMethod() === 'PUT'){

            $body = file_get_contents('php://input');

        }else if ($this->getMethod() === 'DELETE'){

            $body = file_get_contents('php://input');

        }

        return json_decode($body);

    }


    public function getUrlParameters(){

        $args = $_SERVER["REQUEST_URI"];
        $url_components = parse_url($args);
        parse_str($url_components['query'], $params);

        return $params;
    }

}