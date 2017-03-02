<?php

class ReflectionHelper
{
    public function CheckIfSpecificMethodExistsInSpecificClass($class, $nameOfSpecificMethod, $onlyPublic)
    {
        //Unfortunately, get_class_methods($this) doesn't recognize private functions.
        $methodsInClass = null;

        if ($onlyPublic)
            $methodsInClass = (new \ReflectionClass($class))->getMethods(
                ReflectionMethod::IS_PUBLIC
            );
        else
            $methodsInClass = (new \ReflectionClass($class))->getMethods(
                ReflectionMethod::IS_PUBLIC |
                ReflectionMethod::IS_PROTECTED |
                ReflectionMethod::IS_PRIVATE
            );

        foreach ($methodsInClass as $method)
            if ($method->name == $nameOfSpecificMethod)
                return true;

        return false;
    }

    public function CheckIfSpecificPropertyExistsInSpecificClass($class, $nameOfSpecificProperty, $onlyPublic)
    {
        //Unfortunately, get_class_methods($this) doesn't recognize private functions.
        $propertiesInClass = null;

        if ($onlyPublic)
            $propertiesInClass = (new \ReflectionClass($class))->getProperties(
                ReflectionProperty::IS_PUBLIC
            );
        else
            $propertiesInClass = (new \ReflectionClass($class))->getProperties(
                ReflectionProperty::IS_PUBLIC |
                ReflectionProperty::IS_PROTECTED |
                ReflectionProperty::IS_PRIVATE
            );

        foreach ($propertiesInClass as $property)
            if ($property->name == $nameOfSpecificProperty)
                return true;

        return false;
    }

    public function GetNumberOfParametersOfSpecificMethod($class, $nameOfSpecificMethod)
    {
        $parametersOfSpecificMethod = (new \ReflectionMethod(get_class($class), $nameOfSpecificMethod))->getParameters();

        return count($parametersOfSpecificMethod);
    }
}