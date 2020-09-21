<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/test', 'TestCtl@test');

// user
Route::post('/users/login','UserCtl@login')->middleware('user:login');
Route::post('/users','UserCtl@create')->middleware('user:create');

// customer
Route::get('/customers','CustomerCtl@read');
Route::post('/customers','CustomerCtl@create')->middleware('customer:create');
Route::put('/customers/{id}','CustomerCtl@update')->middleware('customer:update');
Route::delete('/customers/{id}','CustomerCtl@delete')->middleware('customer:delete');
Route::delete('/customers/{id}','CustomerCtl@delete')->middleware('customer:delete');

Route::get('/customers/filter','CustomerCtl@filter'); // example = http://127.0.0.1:8000/api/customers/filter?email=mail@mail.com&mobile=0294