<?php

class RoutingTableConfiguration
{
    public $routingTable;

    public function __construct()
    {
        $this->routingTable = new ArrayObject();

        /**
         * Let write here routing names of controllers. Controllers, which don't have a routing name,
         * will be routed with the name specified in URL address.
         *
         * Example:
         * $this->routingTable['routingName'] = "ControllerNameController";
         */

        $this->routingTable[''] = 'IndexController';
        $this->routingTable['fail'] = 'FailController';
        $this->routingTable['stuff'] = 'APIController';
    }
}