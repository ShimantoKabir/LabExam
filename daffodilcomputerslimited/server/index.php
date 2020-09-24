<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 9/23/2020
 * Time: 7:05 PM
 */

require_once __DIR__.'/vendor/autoload.php';
$env = Dotenv\Dotenv::createImmutable(__DIR__);
$env->load();

use app\controllers\TestCtl;
use app\controllers\UserCtl;
use app\core\Application;

$dbCredentials = [
    'dsn' => $_ENV['DB_DSN'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD']
];

$rootDirPath = dirname(__DIR__);

$app = new Application($rootDirPath,$dbCredentials);

$app->router->get('/',[TestCtl::class,'testGet']);
$app->router->get('/test/get',[TestCtl::class,'testGet']);
$app->router->post('/test/post',[TestCtl::class,'testPost']);
$app->router->put('/test/put',[TestCtl::class,'testPut']);
$app->router->delete('/test/delete',[TestCtl::class,'testDelete']);

$app->router->post('/users',[UserCtl::class,'create']);

$app->run();