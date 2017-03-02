<?php

/**
 * Created by PhpStorm.
 * User: Štefan
 * Date: 29.11.2016
 * Time: 7:32
 */
class DatabaseConfiguration
{
    public $databaseSettings;

    public function __construct()
    {
        $this->databaseSettings = new ArrayObject();

        $this->databaseSettings['hostName'] = 'localhost';
        $this->databaseSettings['databaseName'] = 'my_stuff_database';
        $this->databaseSettings['userName'] = 'user';
        $this->databaseSettings['password'] = 'password';
    }
}