<?php

class URLHelper
{
    private $stringHelper;
    private $configurationHelper;
    private $arrayHelper;

    public function __construct()
    {
        $this->stringHelper = new StringHelper();
        $this->arrayHelper = new ArrayHelper();
        $this->configurationHelper = new ConfigurationHelper();
    }

    public function ParseURL($URL)
    {
        $pathToWebsiteDirectory = $this->configurationHelper->GetApplicationConfiguration('pathToWebsiteDirectory');

        $parsedURL = $this->stringHelper->Split(trim(parse_url($URL)['path']), '/');
        $parsedPathToWebsiteDirectory = $this->stringHelper->Split(trim($pathToWebsiteDirectory), '/');

        $completedURL = $this->arrayHelper->RemoveSpecificArrayFromSourceArray($parsedURL, $parsedPathToWebsiteDirectory);

        $URLInfo = new ArrayObject();

        if ((array)$completedURL != null)
        {
            $URLInfo['controllerName'] = $this->configurationHelper->TranslateControllerName($completedURL[0]);
            $URLInfo['parameters'] = array_slice((array)$completedURL, 1);
        }
        else
        {
            $URLInfo['controllerName'] = $this->configurationHelper->TranslateControllerName('');
            $URLInfo['parameters'] = array();
        }

        return $URLInfo;
    }

    public function EncodeURLParameters($parameter)
    {
        $encodedParameter = urlencode(str_replace('\\', '%', $parameter));

        return $encodedParameter;
    }

    public function DecodeURLParameters($parameter)
    {
        $decodedParameter = str_replace('%', '\\', urldecode($parameter));

        return $decodedParameter;
    }
}