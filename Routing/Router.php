<?php
require_once 'Response.php';


/**
 * Class Router
 */
class Router
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var array
     */
    public $parsedConfig = [];

    /**
     * @var string
     */
    public $configName;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->parseControllerConfig();
    }

    /**
     * @param $incomingRequest
     * @return object Response
     */
    public function handleRequest($incomingRequest)
    {
        $incomingRequest = array_flip($incomingRequest);
        $this->url = array_pop($incomingRequest);
        $requestProcessingOptions = [];

        foreach ($this->parsedConfig as $configParams) {
           if (preg_match($configParams["pattern"], $this->url) == 1) {
               $requestProcessingOptions['controllerName'] = $configParams["controller"];
               $requestProcessingOptions['controllerMethod'] = $configParams["controllerMethod"];
           }
        }

        if($requestProcessingOptions) {
            return new Response();
        } else {
            return new Response( Response::HTTP_NOT_FOUND, "PATH NOT FOUND!");
        }
    }

    /**
     * @return array
     */
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
