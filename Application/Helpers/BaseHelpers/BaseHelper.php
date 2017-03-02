<?php
//Usability of this class is questionable.
class BaseHelper
{
    protected $stringHelper;
    protected $configurationHelper;
    protected $urlHelper;

    public function __construct()
    {
        $this->configurationHelper = new ConfigurationHelper();
        $this->stringHelper = new StringHelper();
        $this->urlHelper = new URLHelper();
    }

    protected function Redirect($URL)
    {
        $pathToWebsiteDirectory = $this->configurationHelper->GetApplicationConfiguration('pathToWebsiteDirectory');

        if ($pathToWebsiteDirectory != '')
            $pathToWebsiteDirectory = $this->stringHelper->TrimCharsFromBeginAndEnd($pathToWebsiteDirectory, '/').'/';

        header('Location: /'.$pathToWebsiteDirectory.$URL);
        header('Connection: close');
        exit();
    }
}