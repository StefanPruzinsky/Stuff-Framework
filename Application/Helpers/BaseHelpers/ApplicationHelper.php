<?php

class ApplicationHelper
{
    private $routerHelper;
    private $errorHelper;
    private $stringHelper;
    private $configurationHelper;
    private $fileHelper;

    public function Run()
    {
        mb_internal_encoding('UTF-8');
        spl_autoload_register(array($this, 'LoadClass'));
        session_start();

        //Errors handling
        $this->errorHelper = new ErrorHelper();

        set_error_handler(array($this->errorHelper, 'HandleError'));
        set_exception_handler(array($this->errorHelper, 'HandleThrowable'));
        register_shutdown_function(array($this->errorHelper, 'HandleShutdown'));

        //Initializing helpers
        $this->stringHelper = new StringHelper();
        $this->configurationHelper = new ConfigurationHelper();
        $this->fileHelper = new FileHelper();

        //Validation of 'pathToWebsiteDirectory' correct configuration.
        $this->ValidatePathToWebsiteDirectoryConfiguration($_SERVER['PHP_SELF']);

        //Starting app
        $routerHelper = new RouterHelper($_SERVER['REQUEST_URI']);
        $routerHelper->Route();
    }

    private function LoadClass($className)
    {
        if (preg_match('/Archivist$/', $className) && ($this->CheckPresenceOfClassDefinition($className, 'Archivist', true) || $this->CheckPresenceOfClassDefinition($className, 'Archivist', false)))
            require('Application/Archivists/'.($this->CheckPresenceOfClassDefinition($className, 'Archivist', false) ? $className.'.php' : 'BaseArchivists/'.$className.'.php'));
        else if (preg_match('/Configuration$/', $className) && $this->CheckPresenceOfClassDefinition($className, 'Configuration', false))
            require('Application/Configurations/'.$className.'.php');
        else if (preg_match('/Controller$/', $className) && ($this->CheckPresenceOfClassDefinition($className, 'Controller', true) || $this->CheckPresenceOfClassDefinition($className, 'Controller', false)))
            require('Application/Controllers/'.($this->CheckPresenceOfClassDefinition($className, 'Controller', false) ? $className.'.php' : 'BaseControllers/'.$className.'.php'));
        else if (preg_match('/DBTemplate$/', $className) && ($this->CheckPresenceOfClassDefinition($className, 'DBTemplate', true) || $this->CheckPresenceOfClassDefinition($className, 'DBTemplate', false)))
            require('Application/DBTemplates/'.($this->CheckPresenceOfClassDefinition($className, 'DBTemplate', false) ? $className.'.php' : 'BaseDBTemplates/'.$className.'.php'));
        else if (preg_match('/Helper$/', $className) && ($this->CheckPresenceOfClassDefinition($className, 'Helper', true) || $this->CheckPresenceOfClassDefinition($className, 'Helper', false)))
            require('Application/Helpers/'.($this->CheckPresenceOfClassDefinition($className, 'Helper', false) ? $className.'.php' : 'BaseHelpers/'.$className.'.php'));
        else if (preg_match('/Object$/', $className) && ($this->CheckPresenceOfClassDefinition($className, 'Object', true) || $this->CheckPresenceOfClassDefinition($className, 'Object', false)))
            require('Application/Objects/'.($this->CheckPresenceOfClassDefinition($className, 'Object', false) ? $className.'.php' : 'BaseObjects/'.$className.'.php'));
        else
            throw new Exception('Class '.$className.' doesn\'t exist.', E_USER_WARNING);
    }

    private function CheckPresenceOfClassDefinition($className, $typeOfClass, $isBase)
    {
        $nameOfDirectory = '';

        if ($isBase)
            $nameOfDirectory = 'Application/'.ucfirst($typeOfClass).'s/Base'.ucfirst($typeOfClass).'s/';
        else
            $nameOfDirectory = 'Application/'.ucfirst($typeOfClass).'s/';

        return in_array($className.'.php', scandir($nameOfDirectory));
    }

    private function ValidatePathToWebsiteDirectoryConfiguration($pathFromRootDirectory)
    {
        $currentPath = '';

        if ($this->configurationHelper->GetApplicationConfiguration('pathToWebsiteDirectory') != '')
            $currentPath = '/'.$this->stringHelper->TrimCharsFromBeginAndEnd($this->configurationHelper->GetApplicationConfiguration('pathToWebsiteDirectory'), '/');

        $pathFromRootDirectory = $this->stringHelper->RemoveRedundantConsecutiveChars($pathFromRootDirectory, '/'); //Validation for the case of '/' redundancy. e.g.: example.com//error/404

        if ($currentPath.'/index.php' != $pathFromRootDirectory)
        {
            $this->fileHelper->EditContent(
                'Application/Configurations/ApplicationConfiguration.php',
                function ($fileContent) use ($pathFromRootDirectory) {
                    return substr_replace(
                        $fileContent,
                        $this->stringHelper->TrimCharsFromBeginAndEnd(str_replace('index.php', '', $pathFromRootDirectory), '/'),
                        strpos($fileContent, '[\'pathToWebsiteDirectory\']') + 30,
                        strlen($this->stringHelper->GetSubstringUsingStartAndNonUniqueEndFragments($fileContent, '[\'pathToWebsiteDirectory\'] = \'', '\';'))
                    );
                }
            );

            header('Refresh: 0');
            exit();
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