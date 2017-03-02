<?php

class BaseArchivist
{
    protected $tableName;

    protected $sqlFunctionsList;

    protected $query;
    protected $ORMHelper;
    protected $databaseHelper;

    public function __construct()
    {
        $this->sqlFunctionsList = new ArrayObject(
            array('First', 'Last', 'Avg', 'Count', 'Max', 'Min', 'Sum', 'Ucase', 'Lcase', 'Mid', 'Len', 'Round')
        );

        $this->query = new QueryObject();
        $this->ORMHelper = new ORMHelper($this->tableName);

        $this->databaseHelper = new DatabaseHelper();
        $this->databaseHelper->Connect();
    }

    public function Delete()
    {
        $this->query->method = new MethodObject('delete', null, null, null);

        return $this->ORMHelper->RunQuery($this->query);
    }

    public function Insert($categories, $values)
    {
        $this->query->method = new MethodObject('insert', $categories, $values, null);

        return $this->ORMHelper->RunQuery($this->query);
    }

    public function Update($categories, $values)
    {
        $this->query->method = new MethodObject('update', $categories, $values, null);

        return $this->ORMHelper->RunQuery($this->query);
    }

    protected function Pull($categories)
    {
        $this->query->method = new MethodObject('pull', $categories, null, null);

        return $this->ORMHelper->RunQuery($this->query);
    }

    protected function Pull_2($categories, $additionalMethod)
    {
        $this->query->method = new MethodObject('pull', $categories, null, $additionalMethod);

        return $this->ORMHelper->RunQuery($this->query);
    }

    /*public function First($category)
    {
        $this->query->method = new Method('select', $category, null, 'first');

        return $this->ORMHelper->RunQuery($this->query);
    }

    public function Last($category)
    {
        $this->query->method = new Method('select', $category, null, 'last');

        return $this->ORMHelper->RunQuery($this->query);
    }*/

    protected function Where($category, $operation, $valueToCompare)
    {
        $this->query->conditions = new ConditionObject($category, $operation, $valueToCompare, null);

        return $this;
    }

    protected function Where_4($category, $operation, $valueToCompare, $logicOperation)
    {
        $this->query->conditions = new ConditionObject($category, $operation, $valueToCompare, $logicOperation);

        return $this;
    }

    protected function Order($category)
    {
        $this->query->orders = new OrderObject($category, null);

        return $this;
    }

    protected function Order_2($category, $additionalMethod)
    {
        $this->query->orders = new OrderObject($category, $additionalMethod);

        return $this;
    }

    public function Group($category)
    {
        $this->query->groups = new GroupObject($category);

        return $this;
    }

    public function __call($methodName, $arguments)
    {
        $returnValue = null;

        if ($methodName == 'Pull')
        {
            if (count($arguments) == 0)
                $returnValue = call_user_func_array(array($this, 'Pull'), array('*'));
            else if (count($arguments) == 1)
                $returnValue = call_user_func_array(array($this, 'Pull'), $arguments);
            else if (count($arguments) == 2)
                $returnValue = call_user_func_array(array($this, 'Pull_2'), $arguments);
            else
                throw new Exception('Bad number of arguments passed in to Pull method.', E_USER_ERROR);
        }
        else if ($methodName == 'Where')
        {
            if (count($arguments) == 3)
                $returnValue = call_user_func_array(array($this, 'Where'), $arguments);
            else if (count($arguments) == 4)
                $returnValue = call_user_func_array(array($this, 'Where_4'), $arguments);
            else
                throw new Exception('Bad number of arguments passed in to Where method.', E_USER_ERROR);
        }
        else if ($methodName == 'Order')
        {
            if (count($arguments) == 1)
                $returnValue = call_user_func_array(array($this, 'Order'), $arguments);
            else if (count($arguments) == 2)
                $returnValue = call_user_func_array(array($this, 'Order_2'), $arguments);
            else
                throw new Exception('Bad number of arguments passed in to Order method.', E_USER_ERROR);
        }
        else if (in_array($methodName, (array)$this->sqlFunctionsList))
        {
            if (count($arguments) == 0)
                $returnValue = call_user_func_array(array($this, 'Pull_2'), array(array('*'), $methodName));
            else if (count($arguments) == 1)
                $returnValue = call_user_func_array(array($this, 'Pull_2'), array($arguments[0], $methodName));
            else
                throw new Exception('Bad number of arguments passed in to '.$methodName.' method.', E_USER_ERROR);
        }
        else if ($methodName == 'PullAll')
            $returnValue = call_user_func_array(array($this->databaseHelper, 'PullAll'), $arguments);
        else if ($methodName == 'PullOne')
            $returnValue = call_user_func_array(array($this->databaseHelper, 'PullOne'), $arguments);
        else if ($methodName == 'Query')
            $returnValue = call_user_func_array(array($this->databaseHelper, 'Query'), $arguments);
        else
            throw new Exception('Calling to nonexistent method.', E_USER_ERROR);

        return $returnValue;
    }
}