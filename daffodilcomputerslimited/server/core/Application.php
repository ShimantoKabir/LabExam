<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 9/23/2020
 * Time: 7:11 PM
 */

namespace app\core;

class Application
{

    public static $ROOT_DIR;

    public $router;
    public $request;
    public $response;
    public static $app;
    public $db;

    public function __construct($rootDirPath,array $dbCredentials)
    {

        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request,$this->response);
        $this->db = new Database($dbCredentials);
        self::$ROOT_DIR = $rootDirPath;

    }

    public function run()
    {

        $this->router->resolve();

    }

}