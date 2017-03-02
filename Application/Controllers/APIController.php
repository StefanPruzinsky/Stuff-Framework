<?php

class APIController extends BaseController
{
    private $apiHelper;

    public function __construct()
    {
        $this->apiHelper = new APIHelper();

        $this->viewName = 'apiView';
        $this->layoutName = 'APILayout';

        parent::__construct();
    }

    protected function ProcessData($apiKey, $className, $methodName)
    {
        $isAPIKeyValid = $this->apiHelper->VerifyAPIKey($apiKey);

        $resultArray = new ArrayObject();

        if ($isAPIKeyValid)
            $resultArray = $this->apiHelper->RunSpecificMethodOrGetVariableValue($className, $methodName, array());
        else
        {
            $resultArray['status'] = 0;
            $resultArray['result'] = 'Invalid API key!';
        }

        $this->outputData['generatedJSON'] = $this->apiHelper->CreateJSONResponse($resultArray['status'], $resultArray['result']);
    }

    protected function ProcessData_4($apiKey, $className, $methodName, $parametersArray)
    {
        $isAPIKeyValid = $this->apiHelper->VerifyAPIKey($apiKey);
        $parametersArray = (array)$this->stringHelper->Split(html_entity_decode($parametersArray, ENT_QUOTES), '+');

        $resultArray = new ArrayObject();

        if ($isAPIKeyValid)
            $resultArray = $this->apiHelper->RunSpecificMethodOrGetVariableValue($className, $methodName, $parametersArray);
        else
        {
            $resultArray['status'] = 0;
            $resultArray['result'] = 'Invalid API key!';
        }

        $this->outputData['generatedJSON'] = $this->apiHelper->CreateJSONResponse($resultArray['status'], $resultArray['result']);
    }
}