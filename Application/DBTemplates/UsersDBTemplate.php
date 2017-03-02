<?php

class UsersDBTemplate extends BaseDBTemplate
{
    public function __construct()
    {
        parent::__construct();

        $this->dbTemplate['id'] = 'INT AUTO_INCREMENT PRIMARY KEY';
        $this->dbTemplate['name'] = 'VARCHAR(50)';
        $this->dbTemplate['surname'] = 'VARCHAR(50)';
        $this->dbTemplate['email'] = 'VARCHAR(100) UNIQUE';
        $this->dbTemplate['password'] = 'VARCHAR(100)';
        $this->dbTemplate['timeOfCreation'] = 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP';
        $this->dbTemplate['role'] = 'VARCHAR(50)';
    }
}