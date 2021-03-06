<?php

namespace ApiBundle\Routing;


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
    public $config = [];

    /**
     * @var string
     */
    public $configName;

    const HTTP_NOT_FOUND = "PATH NOT FOUND!";

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->parseControllerConfig();
    }

    /**
     * @param object $incomingRequest
     * @return Response|mixed
     */
    public function handleRequest($incomingRequest)
    {
        $this->url = $incomingRequest->getRequestUri();
        $requestMethodType = $incomingRequest->getRequestMethod();
        $requestProcessingOptions = [];

        foreach ($this->config as $configParams) {
           if (preg_match($configParams["pattern"], $this->url) == 1 && $configParams["method"] == $requestMethodType) {
               $requestProcessingOptions['controllerName'] = $configParams["controller"];
               $requestProcessingOptions['controllerMethod'] = $configParams["controllerMethod"];
               $requestProcessingOptions['controllerNameSpace'] = $configParams["controllerNameSpace"];
               $requestProcessingOptions['countMethodArguments'] = $configParams["countMethodArguments"];
           }
        }

        if($requestProcessingOptions) {
            $this->url = explode('/', $this->url);
            unset($this->url[0]);
            $methodControllerName = $requestProcessingOptions['controllerMethod'];
            $controllerName = $requestProcessingOptions['controllerNameSpace'] . $requestProcessingOptions['controllerName'];
            $controller = new $controllerName();
            $arrControllerNameMethodName = [$controller, $methodControllerName];
            $arrArgumentValues = [];
            if($requestProcessingOptions['countMethodArguments'] > 0) {
                $argumentsMethodController = array_slice($this->url, -$requestProcessingOptions['countMethodArguments']);
                foreach ($argumentsMethodController as $valueArgument) {
                    $arrArgumentValues[] = $valueArgument;
                }
            }
            if($incomingRequest->getPostParams()){
                    $arrArgumentValues[] = $incomingRequest->getPostParams();
            }

            return call_user_func_array($arrControllerNameMethodName, $arrArgumentValues);
        } else {
            return new Response( Response::HTTP_NOT_FOUND, self::HTTP_NOT_FOUND);
        }
    }

    /**
     * @return array
     */
    private function parseControllerConfig()
    {
        $parsConfig = file('/var/www/pfr/config/ConfigController.yml', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($parsConfig as $key => $value) {
            $configElement = explode(': ', $value);
            if(count($configElement) == 1) {
                $this->configName = trim($configElement[0], ":");
                $this->config[$this->configName] = [];
            } else {
                $this->config[$this->configName][trim($configElement[0])] = trim($configElement[1]);
            }
        }
        return $this->config;
    }
}
