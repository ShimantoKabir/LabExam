<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 9/21/2020
 * Time: 3:14 PM
 */

namespace App\Http\Controllers;
use App\Repository\CustomerRpo;
use Illuminate\Http\Request;

class CustomerCtl extends Controller
{

    public function create(Request $request)
    {

        return CustomerRpo::create($request);

    }

    public function read(Request $request)
    {

        return CustomerRpo::read($request);

    }

    public function update(Request $request,$id)
    {

        return CustomerRpo::update($request,$id);

    }

    public function delete(Request $request,$id)
    {

        return CustomerRpo::delete($request,$id);

    }

    public function filter(Request $request)
    {

        return CustomerRpo::filter($request);

    }

}