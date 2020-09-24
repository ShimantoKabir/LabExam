<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 9/23/2020
 * Time: 9:49 PM
 */

namespace app\controllers;

use app\core\Request;

class TestCtl
{

    public function testGet(Request $request)
    {
        return "[GET] app is running...!";
    }

    public function testPost(Request $request)
    {
        return $request->getBody();
    }

    public function testPut(Request $request)
    {
        return $request->getBody();
    }

    public function testDelete(Request $request)
    {
        return $request->getBody();
    }

}