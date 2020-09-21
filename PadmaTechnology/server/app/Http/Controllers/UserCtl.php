<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 9/21/2020
 * Time: 2:29 PM
 */

namespace App\Http\Controllers;

use App\Repository\UserRpo;
use Illuminate\Http\Request;

class UserCtl extends Controller
{


    public function login(Request $request)
    {

        return UserRpo::login($request);

    }

    public function create(Request $request)
    {

        return UserRpo::create($request);

    }

}