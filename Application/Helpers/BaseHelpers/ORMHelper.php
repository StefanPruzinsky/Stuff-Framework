<?php

class ORMHelper
{
    private $tableName;

    private $databaseHelper;
    private $stringHelper;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;

        $this->databaseHelper = new DatabaseHelper();
        $this->databaseHelper->Connect();

        $this->stringHelper = new StringHelper();
    }

    public function RunQuery($query)
    {
        $stringQuery = '';

        if ($query->method->nameOfMethod == 'delete')
            $stringQuery = $this->ComposeDeleteQuery($query);
        else if ($query->method->nameOfMethod == 'insert')
            $stringQuery = $this->ComposeInsertQuery($query);
        else if ($query->method->nameOfMethod == 'update')
            $stringQuery = $this->ComposeUpdateQuery($query);
        else
            $stringQuery = $this->ComposePullQuery($query);

        //Executing database querying
        if ($query->method->nameOfMethod == 'pull')
        {
            $resultOfQuerying = $this->databaseHelper->PullAll($stringQuery);

            if (count($resultOfQuerying) == 1)
                $resultOfQuerying = $resultOfQuerying[0];
        }
        else
            $resultOfQuerying = $this->databaseHelper->Query($stringQuery);

        return $resultOfQuerying;
    }

    private function ComposeDeleteQuery($query)
    {
        $whereCommand = $this->ComposeWhereCommand($query->conditions);

        if ($whereCommand != null)
            $whereCommand = 'WHERE '.$whereCommand;

        $stringQuery = 'DELETE FROM '.$this->tableName.' '.$whereCommand;

        return $stringQuery;
    }

    private function ComposeInsertQuery($query)
    {
        $stringQuery = 'INSERT INTO '.$this->tableName.' ('.implode(', ', (array)$query->method->categories).') VALUES (\''.implode('\', \'', (array)$query->method->values).'\')'; //Apostrophes are everywhere.

        return $stringQuery;
    }

    private function ComposeUpdateQuery($query)
    {
        $whereCommand = $this->ComposeWhereCommand($query->conditions);//echo(8);exit();

        if ($whereCommand != null)
            $whereCommand = 'WHERE '.$whereCommand;

        $categoryValuePair = new ArrayObject();

        for ($i = 0; $i < count($query->method->categories); $i++)
            $categoryValuePair->append($query->method->categories[$i].'=\''.$query->method->values[$i].'\'');

        $stringQuery = 'UPDATE '.$this->tableName.' SET '.implode(', ', (array)$categoryValuePair).' '.$whereCommand;

        return $stringQuery;
    }

    private function ComposePullQuery($query)
    {
        $whereCommand = $this->ComposeWhereCommand($query->conditions);
        $orderByCommand = $this->ComposeOrderByCommand($query->orders);
        $groupByCommand = $this->ComposeGroupByCommand($query->groups);

        if ($whereCommand != null)
            $whereCommand = 'WHERE '.$whereCommand;

        if ($orderByCommand != null)
            $orderByCommand = 'ORDER BY '.$orderByCommand;

        if ($groupByCommand != null)
            $groupByCommand = 'GROUP BY '.$groupByCommand;

        if ($query->method->additionalMethod != '')
            $selectParameters = strtoupper($query->method->additionalMethod).'('.implode('), '.strtoupper($query->method->additionalMethod).'(', (array)$query->method->categories).')';
        else
            $selectParameters = implode(', ', (array)$query->method->categories);

        $stringQuery = 'SELECT '.$selectParameters.' FROM '.$this->tableName.' '.$whereCommand.' '.$groupByCommand.' '.$orderByCommand;

        return $stringQuery;
    }

    private function ComposeWhereCommand($conditions)
    {
        $whereCommand = '';
        $i = 0;

        foreach ($conditions as $condition)
        {
            if ($i == 0)
                $whereCommand .= $condition->category.' '.$condition->operationMark.' '.$this->stringHelper->AddApostrophesIfIsString($condition->valueToCompare);
            else if ($i > 0 && $condition->logicOperation != '')
                $whereCommand .= ' '.$condition->logicOperation.' '.$condition->category.' '.$condition->operationMark.' '.$this->stringHelper->AddApostrophesIfIsString($condition->valueToCompare);
            else
                throw new Exception('There is logicCondition missing in WHERE clause.', E_USER_ERROR);

            $i++;
        }

        return $whereCommand;
    }

    private function ComposeGroupByCommand($groups)
    {
        $temporaryGroupsArray = new ArrayObject();

        foreach ($groups as $group)
            $temporaryGroupsArray->append($group->category);

        $groupByCommand = implode(', ', (array)$temporaryGroupsArray);

        return $groupByCommand;
    }

    private function ComposeOrderByCommand($orders)
    {
        $temporaryOrdersArray = new ArrayObject();

        foreach ($orders as $order)
            if ($order->additionalMethod != '')
                $temporaryOrdersArray->append($order->category.' '.$order->additionalMethod);
            else
                $temporaryOrdersArray->append($order->category);

        $orderByCommand = implode(', ', (array)$temporaryOrdersArray);

        return $orderByCommand;
    }
}