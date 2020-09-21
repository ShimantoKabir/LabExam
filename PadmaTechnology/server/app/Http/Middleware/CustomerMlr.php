<?php

namespace App\Http\Middleware;

use App\Utility\Auth;
use Closure;

class CustomerMlr
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $check)
    {
        if (strcmp($check, 'create') == 0 || strcmp($check, 'update') == 0) {

            $user = $request->user;
            $customer = $request->customer;

            $authRea = Auth::isValid($user);

            if ($authRea['code'] == 200) {

                $reqValidRes = self::checkReqValidation($customer);

                if ($reqValidRes['code'] == 200) {

                    return $next($request);

                } else {

                    return response()->json($reqValidRes, 200);

                }

            } else {

                return response()->json($authRea, 200);

            }

        } else {

            return $next($request);

        }

    }


    private static function checkReqValidation($customer)
    {

        $res = [
            'msg' => '',
            'code' => ''
        ];

        if (is_null($customer['email'])) {
            $res['msg'] = "Customer email required!";
            $res['code'] = 404;
        } else if (is_null($customer['customerName'])) {
            $res['msg'] = "Customer name password required!";
            $res['code'] = 404;
        } else if (is_null($customer['address'])) {
            $res['msg'] = "Customer address password required!";
            $res['code'] = 404;
        } else if (is_null($customer['password'])) {
            $res['msg'] = "Customer password password required!";
            $res['code'] = 404;
        } else if (is_null($customer['mobile'])) {
            $res['msg'] = "Customer mobile password required!";
            $res['code'] = 404;
        } else {
            $res['msg'] = "Request validation successful.";
            $res['code'] = 200;
        }

        return $res;

    }

}
