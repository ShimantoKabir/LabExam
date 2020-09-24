<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 9/24/2020
 * Time: 1:05 AM
 */

require_once __DIR__.'/vendor/autoload.php';
$env = Dotenv\Dotenv::createImmutable(__DIR__);
$env->load();

use app\core\Application;

$dbCredentials = [
    'dsn' => $_ENV['DB_DSN'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD']
];

$rootDirPath = dirname(__DIR__);

$app = new Application($rootDirPath,$dbCredentials);
$app->db->createTables();