<?php

class IndexController extends BaseController
{
    public function __construct()
    {
        $this->viewName = 'indexView';
        $this->layoutName = 'MainLayout';

        parent::__construct();
    }

    /**
     * Inside controller, there can be one ProcessData method without any suffix.
     *
     * Soon as there will appear more than one method with the same name,
     * you have to add a suffix (_numberOfParameters) to each of them (to each of added methods).
     *
     * Example:
     * -------
     * ProcessData($data1, $data2, $data3, $data4){} //Class with one ProcessData method
     *
     * But:
     * ProcessData($data1, $data2){} //Class with two ProcessData methods
     * ProcessData_3($data1, $data2, $data3){} //Class with two ProcessData methods
     */
    protected function ProcessData()
    {
        //TODO: Let process data and save them to an outputData ArrayObject
        $this->outputData['title'] = 'Stuff';
        $this->outputData['subtitle'] = '- just framework for object lovers -';
    }
}