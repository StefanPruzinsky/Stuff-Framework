<?php

class JoppyHelper
{
    private $viewName;
    private $layoutName;
    private $outputData;

    private $viewContent;

    private $stringHelper;
    private $fileHelper;

    public function __construct($viewName, $layoutName, $outputData)
    {
        $this->viewName = $viewName;
        $this->layoutName = $layoutName;
        $this->outputData = $outputData;

        $this->stringHelper = new StringHelper();
        $this->fileHelper = new FileHelper();
    }

    public function ProcessPage()
    {
        $this->viewContent = $this->fileHelper->LoadAndGetProcessedContent('Application/Views/'.$this->viewName.'.phtml', $this->outputData);
        $layoutTemplate = $this->fileHelper->LoadAndGetProcessedContent('Application/Views/Layouts/'.$this->layoutName.'.phtml', $this->outputData);

        echo($this->TranslateLayout($layoutTemplate));
    }

    public function TranslateLayout($layoutTemplate)
    {
        $translatedLayout = $this->CheckForElements($layoutTemplate, 'section', 'PullCurrentSection');
        $translatedLayout = $this->CheckForElements($translatedLayout, 'component', 'PullCurrentComponent');
        $translatedLayout = $this->CheckForVariableMarkers($translatedLayout);

        return $translatedLayout;
    }

    private function CheckForElements($document, $nameOfElement, $contentPullingMethod)
    {
        $checkedElement = '';

        $isReadingElementName = false;
        $nameOfCurrentElement = '';
        $idOfElementStart = 0;

        extract((array)$this->outputData);

        for ($i = 0; $i < strlen($document); $i++)
        {
            if ($isReadingElementName)
            {
                if ($document[$i] == ')')
                {
                    $isReadingElementName = false;

                    $checkedElement .= call_user_func_array(array($this, $contentPullingMethod), array($nameOfCurrentElement));

                    $nameOfCurrentElement = '';
                }
                else if (strlen($nameOfCurrentElement) + 1 > 50)
                {
                    /*$isReadingElementName = false;
                    $i = $idOfElementStart;
                    $translatedLayout .= '#';

                    $nameOfCurrentElement = "";*/

                    throw new Exception('Name of '.$nameOfElement.' \''.$nameOfCurrentElement.'\' is too long, or you forgot to close the quotes.', E_PARSE);
                }
                else
                    $nameOfCurrentElement .= $document[$i];
            }
            else if ($document[$i] == '#' && substr($document, $i + 1, strlen($nameOfElement) + 1) == $nameOfElement.'(')
            {
                $idOfElementStart = $i;
                $i += strlen($nameOfElement) + 1;
                $isReadingElementName = true;
            }
            else
                $checkedElement .= $document[$i];
        }

        return $checkedElement;
    }

    private function CheckForVariableMarkers($element)
    {
        $checkedElement = '';

        $isReadingVariableName = false;
        $nameOfCurrentVariable = '';
        $idOfVariableStart = 0;

        extract((array)$this->outputData);

        for ($i = 0; $i < strlen($element); $i++)
        {
            if ($isReadingVariableName)
            {
                if ($element[$i] == '#' && $element[$i + 1] == '#')
                {
                    $checkedElement .= ${trim($nameOfCurrentVariable)};

                    $isReadingVariableName = false;
                    $i++;

                    $nameOfCurrentVariable = "";
                }
                else if (strlen($nameOfCurrentVariable) + 1 > 30 || $i + 1 == strlen($element))
                {
                    /*$isReadingVariableName = false;
                    $i = $idOfVariableStart;
                    $checkedElement .= '#';

                    $nameOfCurrentVariable = "";*/

                    throw new Exception('Name of variable is too long, or you forgot to close variable markers. There is also an option, you used two # near to each other.', E_PARSE);
                }
                else
                    $nameOfCurrentVariable .= $element[$i];
            }
            else if ($element[$i] == '#' && $element[$i + 1] == '#')
            {
                $idOfVariableStart = $i;
                $i++;
                $isReadingVariableName = true;
            }
            else
                $checkedElement .= $element[$i];
        }

        return $checkedElement;
    }

    private function PullCurrentSection($nameOfCurrentSection)
    {
        $currentSection = $this->stringHelper->GetSubstringUsingStartAndUniqueEndFragments(
            $this->viewContent,
            '#section('.$nameOfCurrentSection.')',
            '#end('.$nameOfCurrentSection.')'
        );

        return $currentSection;
    }

    private function PullCurrentComponent($nameOfCurrentComponent)
    {
        $currentComponent = '';
        $splitComponentDefinition = $this->stringHelper->Split(str_replace(' ', '', $nameOfCurrentComponent), ',');//return '/'.$splitComponentDefinition[0].'/';

        $componentFile = $this->fileHelper->LoadAndGetProcessedContent('Application/Views/Components/'.$splitComponentDefinition[0].'.phtml', $this->outputData);

        if (count($splitComponentDefinition) == 1)
        {
            if (strpos($componentFile, '#component(default)') !== false)
               $currentComponent = $this->stringHelper->GetSubstringUsingStartAndUniqueEndFragments(
                   $componentFile,
                   '#component(default)',
                   '#end(default)'
               );
            else
                $currentComponent = $componentFile;
        }
        else
            $currentComponent = $this->stringHelper->GetSubstringUsingStartAndUniqueEndFragments(
                $componentFile,
                '#component('.$splitComponentDefinition[1].')',
                '#end('.$splitComponentDefinition[1].')'
            );

        return $currentComponent;
    }
}