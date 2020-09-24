<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 9/23/2020
 * Time: 7:11 PM
 */

namespace app\core;

class Router
{

    protected $routes = [];
    public $request;
    public $response;

    /**
     * Router constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }


    public function get($path, $callback)
    {

        $this->routes['GET'][$path] = $callback;

    }

    public function post($path, $callback)
    {

        $this->routes['POST'][$path] = $callback;

    }

    public function put($path, $callback)
    {

        $this->routes['PUT'][$path] = $callback;

    }

    public function delete($path, $callback)
    {

        $this->routes['DELETE'][$path] = $callback;

    }

    public function resolve()
    {

        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            $this->response->setStatusCode(404);
            echo "[Invalid Path]";
            exit;
        } else {
            echo call_user_func($callback, $this->request);
        }

    }

}