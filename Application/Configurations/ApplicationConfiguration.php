<?php

class ApplicationConfiguration
{
    public $applicationSettings;

    public function __construct()
    {
        $this->applicationSettings = new ArrayObject();

        $this->applicationSettings['applicationName'] = 'MyStuffApplication';
        $this->applicationSettings['serverAddress'] = 'http://example.com';
        $this->applicationSettings['apiKey'] = 'DEFAULT_API_KEY';
        $this->applicationSettings['debugMode'] = true;
        $this->applicationSettings['pathToWebsiteDirectory'] = ''; //If your application is straight under the root, leave the path empty. Otherwise, type the path in following format: directory1/directory2/directory3
    }
}