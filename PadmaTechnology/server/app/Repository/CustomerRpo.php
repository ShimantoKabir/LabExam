<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 9/21/2020
 * Time: 3:15 PM
 */

namespace App\Repository;


use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CustomerRpo
{

    public static function create(Request $request)
    {

        $res = [
            'msg' => '',
            'code' => ''
        ];

        $reqCustomer = $request->customer;

        DB::beginTransaction();
        try {

            $isExist = Customer::where('email', $reqCustomer['email'])->exists();

            if ($isExist) {

                $res['code'] = 404;
                $res['msg'] = 'A customer already created by this mail.';

            } else {


                $c = new Customer();
                $c->customerName = $reqCustomer['customerName'];
                $c->email = $reqCustomer['email'];
                $c->password = $reqCustomer['password'];
                $c->address = $reqCustomer['address'];
                $c->mobile = $reqCustomer['mobile'];
                $c->save();

                $res['code'] = 200;
                $res['msg'] = 'Customer created successfully.';

            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $res['msg'] = $e->getMessage();
            $res['code'] = 404;
        }

        return response()->json($res, 200);

    }

    public static function read(Request $request)
    {

        $res = [
            'msg' => '',
            'code' => '',
            'customers' => []
        ];

        try {

            $customers = Customer::all();

            if (count($customers) > 0){
                $res['msg'] = 'Found customer.';
                $res['code'] = 200;
                $res['customers'] = $customers;
            }else{

                $res['msg'] = 'No customer Found.';
                $res['code'] = 404;

            }

        } catch (\Exception $e) {
            $res['msg'] = $e->getMessage();
            $res['code'] = 404;
        }

        return response()->json($res, 200);

    }

    public static function update(Request $request, $id)
    {

        $res = [
            'msg' => '',
            'code' => ''
        ];

        $reqCustomer = $request->customer;

        DB::beginTransaction();
        try {

            Customer::where('id', $id)->update(array(
                'customerName' => $reqCustomer['customerName'],
                'email' => $reqCustomer['email'],
                'password' => $reqCustomer['password'],
                'address' => $reqCustomer['address'],
                'mobile' => $reqCustomer['mobile']
            ));

            $res['code'] = 200;
            $res['msg'] = 'Customer updated successfully.';

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $res['msg'] = $e->getMessage();
            $res['code'] = 404;
        }

        return response()->json($res, 200);

    }

    public static function delete(Request $request, $id)
    {

        $res = [
            'msg' => '',
            'code' => ''
        ];

        DB::beginTransaction();
        try {

            Customer::where('id', $id)->delete();

            $res['code'] = 200;
            $res['msg'] = 'Customer deleted successfully.';

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $res['msg'] = $e->getMessage();
            $res['code'] = 404;
        }

        return response()->json($res, 200);

    }

    public static function filter(Request $request)
    {

        $res = [
            'code' => '',
            'msg' => '',
            'customers' => []
        ];

        $email = $request->query('email');
        $mobile = $request->query('mobile');


        try{

            if (is_null($email) && is_null($mobile)){

                // no filter query given
                $res['code'] = 404;
                $res['msg'] = "No filter query given.";

            }elseif (is_null($email) || is_null($mobile)){

                if (is_null($email)){

                    // only mobile given
                    $customers = Customer::where('mobile',$mobile)->get();

                }else {

                    // only email given

                    $customers = Customer::where('email',$email)->get();
                }

                $res['code'] = (count($customers) > 0) ? 200 : 4040;
                $res['msg'] = (count($customers) > 0) ? "Found customer." : "No customer found";
                $res['customers'] = $customers;

            }else{

                // both[email and mobile] given
                $customers = Customer::where('email',$email)->where('mobile',$mobile)->get();

                $res['code'] = (count($customers) > 0) ? 200 : 4040;
                $res['msg'] = (count($customers) > 0) ? "Found customer." : "No customer found";
                $res['customers'] = $customers;

            }

        }catch (\Exception $e){
            $res['msg'] = $e->getMessage();
            $res['code'] = 404;
        }

        return response()->json($res, 200);

    }

}