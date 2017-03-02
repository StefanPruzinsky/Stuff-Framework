<?php

class GroupObject
{
    private $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function __get($variableName)
    {
        return $this->{$variableName};
    }
}