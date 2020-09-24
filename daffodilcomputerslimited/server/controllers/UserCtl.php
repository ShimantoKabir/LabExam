<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 9/24/2020
 * Time: 1:43 AM
 */

namespace app\controllers;


use app\core\Application;
use app\core\Request;

class UserCtl
{

    public function create(Request $request)
    {

        $res = [
            'msg' => '',
            'code' => ''
        ];

        $db = Application::$app->db;
        $reqUser = $request->getBody()->user;

        $db->pdo->beginTransaction();
        try {

            $res['code'] = 200;
            $res['msg'] = "OK";

            $sql = "INSERT INTO users (email,first_name, last_name, password)
                    VALUES ('"
                .$reqUser->email."','"
                .$reqUser->first_name."','"
                .$reqUser->last_name."','"
                .sha1($reqUser->password)."')";

            $statement = $db->pdo->prepare($sql);
            $statement->execute();

            $db->pdo->commit();
        } catch (\Exception $e) {

            $db->pdo->rollBack();
            $res['code'] = $e->getCode();
            $res['msg'] = $e->getMessage();
        }


        return Application::$app->response->returnResponse($res,200);

    }

}