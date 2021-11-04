<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 11/4/2021
 * Time: 7:53 AM
 */

namespace App\Http\Controllers;

use App\UseCases\AdministrationUseCase;
use Illuminate\Http\Request;

class AdministrationController
{

    private static $administrationUserCase = null;

    public function __construct()
    {
        self::$administrationUserCase = new AdministrationUseCase();
    }

    public function sendSingUpEmailInvitation(Request $request){

        return self::$administrationUserCase->sendSingUpEmail($request);

    }

}