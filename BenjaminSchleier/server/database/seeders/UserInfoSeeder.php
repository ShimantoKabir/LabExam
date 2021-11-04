<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 11/4/2021
 * Time: 7:38 AM
 */

namespace Database\Seeders;

use Database\Factories\UserInfoFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=UserInfoSeeder
     * @return void
     */
    public function run()
    {
        $userInfos = UserInfoFactory::getData();

        DB::table('user_infos')->truncate();

        foreach ($userInfos as $key => $val) {

            DB::table('user_infos')->insert([
                "name" => $val['name'],
                "user_name" => $val['user_name'],
                "email" => $val['email'],
                "password" => $val['password'],
                "avatar" => $val['avatar'],
                "user_role" => $val['user_role'],
                "registered_at" => $val['registered_at']
            ]);
        }
    }
}