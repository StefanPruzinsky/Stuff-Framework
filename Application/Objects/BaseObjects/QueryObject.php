<?php

class QueryObject
{
    private $method;
    private $conditions;
    private $groups;
    private $orders;

    public function __construct()
    {
        $this->conditions = new ArrayObject();
        $this->groups = new ArrayObject();
        $this->orders = new ArrayObject();
    }

    public function __get($variableName)
    {
        return $this->{$variableName};
    }

    public function __set($variableName, $value)
    {
        if ($variableName == 'method')
        {
            if (is_object($value) && get_class($value) == 'MethodObject')
                $this->method = $value;
            else
                throw new Exception('The method was called in incorrect form.', E_USER_ERROR);
        }
        else if ($variableName == 'conditions')
        {
            if (is_object($value) && get_class($value) == 'ConditionObject')
                $this->conditions->append($value);
            else
                throw new Exception('The condition was set in incorrect form.', E_USER_ERROR);
        }
        else if ($variableName == 'groups')
        {
            if (is_object($value) && get_class($value) == 'GroupObject')
                $this->groups->append($value);
            else
                throw new Exception('The grouping was set in incorrect form.', E_USER_ERROR);
        }
        else if ($variableName == 'orders')
        {
            if (is_object($value) && get_class($value) == 'OrderObject')
                $this->orders->append($value);
            else
                throw new Exception('The ordering was set in incorrect form.', E_USER_ERROR);
        }
        else
            throw new Exception('Initializing nonexistenting object.', E_USER_ERROR);
    }
}