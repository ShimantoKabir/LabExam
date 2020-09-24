<?php
/**
 * Created by PhpStorm.
 * User: kabir
 * Date: 9/24/2020
 * Time: 12:25 AM
 */

namespace app\core;


class Database
{


    public $pdo;

    /**
     * Database constructor.
     * @param array $dbCredentials
     */
    public function __construct(array $dbCredentials)
    {

        $this->pdo = new \PDO($dbCredentials['dsn'],$dbCredentials['user'],$dbCredentials['password']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);


    }

    public function createTables(){


        $files = scandir(Application::$ROOT_DIR."/server/migrations");
        $files = array_diff($files,[".",".."]);

        foreach ($files as $value){

            require_once Application::$ROOT_DIR."/server/migrations/".$value;
            $className = pathinfo($value,PATHINFO_FILENAME);
            $table = new $className();
            $table->up();

        }

    }

}