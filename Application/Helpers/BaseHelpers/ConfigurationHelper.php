<?php

class ConfigurationHelper
{
    private $stringHelper;

    public function __construct()
    {
        $this->stringHelper = new StringHelper();
    }

    public function TranslateControllerName($routingName)
    {
        $routingTableConfiguration = new RoutingTableConfiguration();
        $controllerName = '';

        if (!isset($routingTableConfiguration->routingTable[$routingName]))
            $controllerName = $this->stringHelper->ConvertToCamelCase('-', $routingName).'Controller';
        else
            $controllerName = $routingTableConfiguration->routingTable[$routingName];

        return $controllerName;
    }

    public function GetApplicationConfiguration($configurationParameter)
    {
        $applicationConfiguration = new ApplicationConfiguration();

        return isset($applicationConfiguration->applicationSettings[$configurationParameter]) ? $applicationConfiguration->applicationSettings[$configurationParameter] : null;
    }

    public function GetDatabaseConfiguration($configurationParameter)
    {
        $databaseConfiguration = new DatabaseConfiguration();

        return isset($databaseConfiguration->databaseSettings[$configurationParameter]) ? $databaseConfiguration->databaseSettings[$configurationParameter] : null;
    }
}