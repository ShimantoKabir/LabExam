<?php

namespace App\Http\Middleware;

use Closure;

class UserMlr
{
    private static function checkReqValidation($user)
    {

        $res = [
            'msg' => '',
            'code' => ''
        ];

        if (is_null($user['email'])) {
            $res['msg'] = "User email required!";
            $res['code'] = 404;
        } else if (is_null($user['password'])) {
            $res['msg'] = "User password required!";
            $res['code'] = 404;
        } else {
            $res['msg'] = "Request validation successful.";
            $res['code'] = 200;
        }

        return $res;

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $check)
    {

        if (strcmp($check, 'login') == 0) {


            $user = $request->user;

            $res = self::checkReqValidation($user);

            if ($res['code'] == 200) {

                return $next($request);

            } else {
                return response()->json($res, 200);
            }

        } else if (strcmp($check, 'create') == 0) {


            $user = $request->user;

            $res = self::checkReqValidation($user);

            if ($res['code'] == 200) {

                return $next($request);

            } else {
                return response()->json($res, 200);
            }

        } else {

            return $next($request);

        }

    }
}
