<?php

class MethodObject
{
    private $nameOfMethod;
    private $categories;
    private $values;

    private $additionalMethod;

    public function __construct($nameOfMethod, $categories, $values, $additionalMethod)
    {
        $this->nameOfMethod = $nameOfMethod;
        $this->categories = $categories;
        $this->values = $values;

        $this->additionalMethod = $additionalMethod;
    }

    public function __get($variableName)
    {
        return $this->{$variableName};
    }
}