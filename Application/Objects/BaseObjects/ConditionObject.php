<?php

class ConditionObject
{
    private $category;
    private $operationMark;
    private $valueToCompare;

    private $logicOperation;

    public function __construct($category, $operation, $valueToCompare, $logicOperation)
    {
        $this->category = $category;
        $this->operationMark = $operation;
        $this->valueToCompare = $valueToCompare;

        $this->logicOperation = $logicOperation;
    }

    public function __get($variableName)
    {
        return $this->{$variableName};
    }
}