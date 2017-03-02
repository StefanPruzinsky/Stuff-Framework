<?php

class BaseController
{
    protected $viewName;
    protected $layoutName;
    protected $outputData;

    protected $stringHelper;
    protected $configurationHelper;
    protected $joppyHelper;
    protected $reflectionHelper;

    public function __construct()
    {
        $this->stringHelper = new StringHelper();
        $this->configurationHelper = new ConfigurationHelper();
        $this->reflectionHelper = new ReflectionHelper();

        $this->outputData = new ArrayObject();
    }

    //abstract protected function ProcessData();

    protected function Redirect($URL)
    {
        $pathToWebsiteDirectory = $this->configurationHelper->GetApplicationConfiguration('pathToWebsiteDirectory');

        if ($pathToWebsiteDirectory != '')
            $pathToWebsiteDirectory = $this->stringHelper->TrimCharsFromBeginAndEnd($pathToWebsiteDirectory, '/').'/';

        header('Location: /'.$pathToWebsiteDirectory.$URL);
        header('Connection: close');
        exit();
    }

    public function RenderView()
    {
        $baseURL = '/';

        if ($this->configurationHelper->GetApplicationConfiguration('pathToWebsiteDirectory') != '')
            $baseURL = '/'.$this->stringHelper->TrimCharsFromBeginAndEnd($this->configurationHelper->GetApplicationConfiguration('pathToWebsiteDirectory'), '/').'/';

        $this->outputData['applicationName'] = $this->configurationHelper->GetApplicationConfiguration('applicationName');
        $this->outputData['baseURL'] = $baseURL;

        $this->joppyHelper = new JoppyHelper($this->viewName, $this->layoutName, $this->outputData);
        $this->joppyHelper->ProcessPage();
    }

    /**
     * Inside controller, there can be one ProcessData method without any suffix.
     *
     * Soon as there will appear more than one method with the same name,
     * you have to add a suffix (_numberOfParameters) to each of them (to each of added methods).
     *
     * Example:
     * -------
     * ProcessData($data1, $data2, $data3){} //Class with one ProcessData method
     *
     * But:
     * ProcessData($data1, $data2){} //Class with two ProcessData methods
     * ProcessData_3($data1, $data2, $data3, $data4){} //Class with two ProcessData methods
     */
    public function __call($method, $arguments)
    {
        if ($this->reflectionHelper->CheckIfSpecificMethodExistsInSpecificClass($this, $method, false) && count((new \ReflectionMethod(get_class($this), $method))->getParameters()) == count($arguments))
            call_user_func_array(array($this, 'ProcessData'), $arguments);
        else if ($this->reflectionHelper->CheckIfSpecificMethodExistsInSpecificClass($this, $method.'_'.count($arguments), false) && $this->reflectionHelper->GetNumberOfParametersOfSpecificMethod($this, $method.'_'.count($arguments)) == count($arguments))
            call_user_func_array(array($this, 'ProcessData_'.count($arguments)), $arguments);
        else
            throw new Exception('There isn\'t any ProcessData function with appropriate number of parameters in '.get_class($this).'. Please check the number of parameters, you entered.', 404);//count((new \ReflectionMethod(get_class($this), $method.'_'.count($arguments)))->getParameters())
    }
}