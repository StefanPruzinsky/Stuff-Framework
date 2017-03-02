<?php

class RouterHelper
{
    private $URL;

    private $stringHelper;
    private $configurationHelper;
    private $urlHelper;

    public function __construct($URL)
    {
        $this->URL = $URL;

        $this->stringHelper = new StringHelper();
        $this->configurationHelper = new ConfigurationHelper();
        $this->urlHelper = new URLHelper();
    }

    public function Route()
    {
        $URLInfo = $this->urlHelper->ParseURL($this->URL);

        if (in_array($URLInfo['controllerName'].'.php', scandir('Application/Controllers/')))
        {
            $requestedController = new $URLInfo['controllerName']();
            call_user_func_array(array($requestedController, 'ProcessData'), $URLInfo['parameters']);
            $requestedController->RenderView();
        }
        else
            throw new Exception('There isn\'t any controller or a route which belongs to entered URL address.', 404);//throw 404 or similar error
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