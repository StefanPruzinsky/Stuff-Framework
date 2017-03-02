<?php

class BaseDBTemplate
{
    public $dbTemplate;

    public function __construct()
    {
        $this->dbTemplate = new ArrayObject();
    }
}