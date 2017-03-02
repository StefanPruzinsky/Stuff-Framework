<?php

class APIHelper
{
    private $configurationHelper;
    private $reflectionHelper;

    public function __construct()
    {
        $this->configurationHelper = new ConfigurationHelper();
        $this->reflectionHelper = new ReflectionHelper();
    }

    public function RunSpecificMethodOrGetVariableValue($className, $methodOrVariableName, $parametersArray)
    {
        $specificClass = null;

        $resultArray = new ArrayObject();
        $resultArray['status'] = 0;

        //Verifying request
        if (class_exists($className))
            $specificClass = new $className();
        else
        {
            $resultArray['result'] = 'The class, you have entered, doesn\'t exist.';

            return $resultArray;
        }

        $existenceOfSpecificMethod = $this->reflectionHelper->CheckIfSpecificMethodExistsInSpecificClass($specificClass, $methodOrVariableName, true);
        $existenceOfSpecificProperty = $this->reflectionHelper->CheckIfSpecificPropertyExistsInSpecificClass($specificClass, $methodOrVariableName, true);

        if (!$existenceOfSpecificMethod && !$existenceOfSpecificProperty)
        {
            $resultArray['result'] = 'Given method or property doesn\'t exist in specified context or has non-public property.';

            return $resultArray;
        }
        else if ($existenceOfSpecificMethod && $this->reflectionHelper->GetNumberOfParametersOfSpecificMethod($specificClass, $methodOrVariableName) != count($parametersArray))
        {
            $resultArray['result'] = 'Number of parameters of given method doesn\'t equal to count of given parameters.';

            return $resultArray;
        }

        //Processing request
        if ($existenceOfSpecificMethod)
        {
            $resultArray['status'] = 1;
            $resultArray['result'] = call_user_func_array(array($specificClass, $methodOrVariableName), $parametersArray);
        }
        else if ($existenceOfSpecificProperty)
        {
            $resultArray['status'] = 1;
            $resultArray['result'] = $specificClass->{$methodOrVariableName};
        }

        return $resultArray;
    }

    public function VerifyAPIKey($apiKey)
    {
        $result = false;

        if ($this->configurationHelper->GetApplicationConfiguration('apiKey') == $apiKey)
            $result = true;

        return $result;
    }

    public function CreateJSONResponse($status, $response)
    {
        $responseArray = new ArrayObject();

        $responseArray['status'] = $status;

        //TODO: Convert resource to array
        /*if (is_resource($response))
            $response = $response;*/

        $responseArray['response'] = $response;

        return json_encode($responseArray);
    }
}