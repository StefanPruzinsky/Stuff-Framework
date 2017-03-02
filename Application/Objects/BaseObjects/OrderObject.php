<?php

class OrderObject
{
    private $category;

    private $additionalMethod;

    public function __construct($category, $additionalMethod)
    {
        $this->category = $category;

        $this->additionalMethod = $additionalMethod;
    }

    public function __get($variableName)
    {
        return $this->{$variableName};
    }
}