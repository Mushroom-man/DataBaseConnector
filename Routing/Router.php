<?php
require_once 'Response.php';


class Router
{
    public $url;

    public $parsedConfig = [];

    public $configName;

    public function __construct()
    {
        $this->parseControllerConfig();
    }

    public function handleRequest($incomingRequest)
    {
        $incomingRequest = array_flip($incomingRequest);
        $this->url = array_pop($incomingRequest);
        $requestProcessingOptions = [];

        foreach ($this->parsedConfig as $configParams) {
           if (preg_match($configParams["path"], $this->url) == 1) {
               $requestProcessingOptions['controllerName'] = $configParams["controller"];
               $requestProcessingOptions['controllerMethod'] = $configParams["controllerMethod"];
           }
        }

        if($requestProcessingOptions) {
            return new Response("PATH FOUND!<br/>" . "Status code: ", 200);
        } else {
            return new Response("PATH NOT FOUND!<br/>" . "Status code: ", 404);
        }
    }

    private function parseControllerConfig()
    {
        $parsConfig = file('/var/www/html/DataBaseConnector/Routing/ConfigControllers.yml', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($parsConfig as $key => $value) {
            $configElement = explode(': ', $value);
            if(count($configElement) == 1) {
                $this->configName = trim($configElement[0], ":");
                $this->parsedConfig[$this->configName] = [];
            } else {
                $this->parsedConfig[$this->configName][trim($configElement[0])] = trim($configElement[1]);
            }
        }

        return $this->parsedConfig;
    }
}
