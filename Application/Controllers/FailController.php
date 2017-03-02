<?php

class FailController extends BaseController
{
    private $urlHelper;

    public function __construct()
    {
        $this->urlHelper = new URLHelper();

        $this->viewName = 'failView';
        $this->layoutName = 'MainLayout';

        parent::__construct();
    }

    protected function ProcessData($errorCode)
    {
        //TODO: Let process data and save them to an outputData ArrayObject
        if ($errorCode == '500') //You can also compare $errorCode with number, not only string...
        {
            $this->outputData['title'] = 'Chyba 500';
            $this->outputData['errorMessage'] = 'Nastala interná chyba serveru, alebo webovej aplikácie.';
        }
        else if ($errorCode == '404')
        {
            $this->outputData['title'] = 'Chyba 404';
            $this->outputData['errorMessage'] = 'Požadavaný súbor, alebo cesta nebola nájdená.';
        }
        else if ($errorCode == '403')
        {
            $this->outputData['title'] = 'Chyba 403';
            $this->outputData['errorMessage'] = 'Nemáte právo na prístup k požadovanému objektu. Buď je chránený proti čítaniu, alebo nie je čitateľný serverom.';
        }
        else
        {
            $this->outputData['title'] = 'Chyba';
            $this->outputData['errorMessage'] = 'Došlo k neočakávanej chybe.';
        }
    }

    protected function ProcessData_4($errorCode, $errorMessage, $errorFile, $errorLine)
    {
        if ($this->configurationHelper->GetApplicationConfiguration('debugMode'))
        {
            $this->outputData['title'] = 'Error '.$errorCode;
            $this->outputData['errorMessage'] = $this->urlHelper->DecodeURLParameters($errorMessage);
            $this->outputData['errorFile'] = $this->urlHelper->DecodeURLParameters($errorFile);
            $this->outputData['errorLine'] = $errorLine;
        }
        else
            $this->Redirect('fail/'.$errorCode);
    }
}