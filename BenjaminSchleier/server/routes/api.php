<?php

use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserInfoController;
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

// test
Route::get('/test', [TestController::class, 'test']);

// administration
Route::post('/administration/sing-up-email-invitations', [AdministrationController::class, 'sendSingUpEmailInvitation']);

// user
Route::post('/users/sing-up', [UserInfoController::class, 'singUp']);
Route::get('/users/verification', [UserInfoController::class, 'verification']); // http://127.0.0.1:8000/api/users/verification/pin=788024&email=maask5534@gmail.com
Route::post('/users/login', [UserInfoController::class, 'login']);
Route::post('/users', [UserInfoController::class, 'update']); // this request need bearer token, which is given on login
Route::get('/users', [UserInfoController::class, 'read']);
