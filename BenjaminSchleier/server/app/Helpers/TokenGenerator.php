<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 11/4/2021
 * Time: 7:20 AM
 */

namespace App\Helpers;
use Illuminate\Support\Facades\Hash;

class TokenGenerator
{
    public static function generate(){

        $date = date("Y_m_d_h_i_sa");
        return StringManager::cleanString(Hash::make($date));

    }
}