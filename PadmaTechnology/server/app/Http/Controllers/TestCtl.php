<?php


namespace App\Http\Controllers;

use App\Hostel;
use App\Member;
use Illuminate\Http\Request;

class TestCtl extends Controller
{

    public function test(Request $request)
    {

        return [
            'stats'=>'working ....!',
            'request' => $request->all()
        ];
    }

}
