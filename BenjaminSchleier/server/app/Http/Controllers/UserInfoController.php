<?php

namespace App\Http\Controllers;

use App\UseCases\UserInfoUseCase;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{

    private static $userInfoUserCase = null;

    public function __construct()
    {
        self::$userInfoUserCase = new UserInfoUseCase();
    }

    public function singUp(Request $request){

        return self::$userInfoUserCase->singUp($request);

    }

    public function verification(Request $request){

        return self::$userInfoUserCase->verification($request);

    }

    public function login(Request $request){

        return self::$userInfoUserCase->login($request);

    }

    public function update(Request $request){

        return self::$userInfoUserCase->update($request);

    }


    public function read(Request $request){

        return self::$userInfoUserCase->read($request);

    }
}
