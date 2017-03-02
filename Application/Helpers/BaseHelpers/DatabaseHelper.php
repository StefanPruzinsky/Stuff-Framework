<?php

class DatabaseHelper
{
    private $PDO;
    private $configurationHelper;

    public function __construct()
    {
        $this->configurationHelper = new ConfigurationHelper();
    }

    public function Connect()
    {
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_EMULATE_PREPARES => false,
        );

        $dataSourceName = 'mysql:host='.$this->configurationHelper->GetDatabaseConfiguration('hostName').';dbname='.$this->configurationHelper->GetDatabaseConfiguration('databaseName');

        $this->PDO = new PDO($dataSourceName, $this->configurationHelper->GetDatabaseConfiguration('userName'), $this->configurationHelper->GetDatabaseConfiguration('password'), $options);
    }

    public function Query($query, $data = null){
        $statement = $this->ExecuteQuery($query, $data);

        $wasSuccessful = true;

        if ($statement->errorCode() != '00000')
        {
            $wasSuccessful = false;

            throw new Exception($statement->errorInfo[1], $statement->errorInfo[2]);
        }

        return $wasSuccessful;
    }

    public function PullOne($query, $data = null){
        $statement = $this->ExecuteQuery($query, $data);

        return $statement->fetchObject();
    }

    public function PullAll($query, $data = null){
        $statement = $this->ExecuteQuery($query, $data);

        return($statement->fetchAll(PDO::FETCH_OBJ));
    }

    public function ExecuteQuery($query, $data = null)
    {
        $statement = $this->PDO->prepare($query);
        $statement->execute($data);

        return $statement;
    }
}