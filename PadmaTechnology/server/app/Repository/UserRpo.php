<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 9/21/2020
 * Time: 2:30 PM
 */

namespace App\Repository;


use App\Helpers\TokenGenerator;
use App\User;
use Illuminate\Support\Facades\DB;

class UserRpo
{

    public static function login($request)
    {

        $res = [
            'msg' => '',
            'code' => ''
        ];

        $reqUser = $request->user;

        DB::beginTransaction();
        try {

            $isUserExist = User::where('email', $reqUser['email'])->exists();

            if ($isUserExist) {

                $users = User::where('email', $reqUser['email'])
                    ->where('password', sha1($reqUser['password']))
                    ->get();

                if (count($users) > 0) {

                    $user = $users[0];
                    $sessionId = TokenGenerator::generate();

                    User::where('id', $user->id)->update(array('sessionId' => $sessionId));

                    $res['code'] = 200;
                    $res['msg'] = "User login successful.";
                    $res['user'] = [
                        'sessionId' => $sessionId,
                        'email' => $reqUser['email']
                    ];

                }else{

                    $res['code'] = 404;
                    $res['msg'] = "User email and password incorrect, please try again.";

                }

            } else {

                $res['msg'] = 'No user found by this email!';
                $res['code'] = 404;

            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $res['msg'] = $e->getMessage();
            $res['code'] = 404;
        }

        return response()->json($res, 200);

    }

    public static function create($request)
    {

        $res = [
            'msg' => '',
            'code' => ''
        ];

        $reqUser = $request->user;

        DB::beginTransaction();
        try {

            $isUserExist = User::where('email', $reqUser['email'])->exists();

            if ($isUserExist) {

                $res['msg'] = 'A account already been created using the email!';
                $res['code'] = 404;

            } else {


                $user = new User();
                $user->email = $reqUser['email'];
                $user->password = sha1($reqUser['password']);
                $user->save();

                $res['msg'] = 'Account creation successful.';
                $res['code'] = 200;

            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $res['msg'] = $e->getMessage();
            $res['code'] = 404;
        }

        return response()->json($res, 200);

    }

}