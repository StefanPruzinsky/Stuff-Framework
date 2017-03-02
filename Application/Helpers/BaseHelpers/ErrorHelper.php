<?php

class ErrorHelper
{
    private $stringHelper;
    private $configurationHelper;
    private $urlHelper;

    public function __construct()
    {
        $this->stringHelper = new StringHelper();
        $this->configurationHelper = new ConfigurationHelper();
        $this->urlHelper = new URLHelper();
    }

    public function HandleError($errorCode, $errorMessage, $errorFile, $errorLine)
    {
        $errorMessage = $this->urlHelper->EncodeURLParameters($errorMessage);
        $errorFile = $this->urlHelper->EncodeURLParameters($this->stringHelper->CutStringAfterLastSpecificChar($errorFile, '\\'));

        if ($this->configurationHelper->GetApplicationConfiguration('debugMode'))
            $this->Redirect('fail/'.$errorCode.'/'.$errorMessage.'/'.$errorFile.'/'.$errorLine);
        else
            $this->Redirect('fail/'.$errorCode);
    }

    public function HandleThrowable($throwable)
    {
        $errorMessage = $this->urlHelper->EncodeURLParameters($throwable->getMessage());
        $errorFile = $this->urlHelper->EncodeURLParameters($this->stringHelper->CutStringAfterLastSpecificChar($throwable->getFile(), '\\'));

        if ($this->configurationHelper->GetApplicationConfiguration('debugMode'))
            $this->Redirect('fail/'.$throwable->getCode().'/'.$errorMessage.'/'.$errorFile.'/'.$throwable->getLine());
        else
            $this->Redirect('fail/'.$throwable->getCode());
    }

    public function HandleShutdown()
    {
        $thrownError = error_get_last();

        if ($thrownError !== null)
        {
            $errorMessage = $this->urlHelper->EncodeURLParameters($thrownError['message']);
            $errorFile = $this->urlHelper->EncodeURLParameters($this->stringHelper->CutStringAfterLastSpecificChar($thrownError['file'], '\\'));

            if ($this->configurationHelper->GetApplicationConfiguration('debugMode'))
                $this->Redirect('fail/'.$thrownError['type'].'/'.$errorMessage.'/'.$errorFile.'/'.$thrownError['line']);
            else
                $this->Redirect('fail/'.$thrownError['type']);
        }
    }

    private function Redirect($URL)
    {
        $pathToWebsiteDirectory = $this->configurationHelper->GetApplicationConfiguration('pathToWebsiteDirectory');

        if ($pathToWebsiteDirectory != '')
            $pathToWebsiteDirectory = $this->stringHelper->TrimCharsFromBeginAndEnd($pathToWebsiteDirectory, '/').'/';

        header('Location: /'.$pathToWebsiteDirectory.$URL);
        header('Connection: close');
        exit();
    }
}