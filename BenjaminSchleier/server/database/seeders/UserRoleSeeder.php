<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 11/4/2021
 * Time: 7:06 AM
 */

namespace Database\Seeders;


use Database\Factories\UserRoleFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     * php artisan db:seed --class=UserRoleSeeder
     * @return void
     */
    public function run()
    {
        $userRoles = UserRoleFactory::getData();

        DB::table('user_roles')->truncate();

        foreach ($userRoles as $key => $val) {

            DB::table('user_roles')->insert([
                "role_id" => $val['role_id'],
                "role_name" => $val['role_name']
            ]);
        }
    }

}