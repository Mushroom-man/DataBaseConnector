<?php


class Router
{
    public $url;

    public $requestData;

    public $parsedConfig = [];

    public function __construct($arr)
    {
        $arr = array_flip($arr);

        $this->requestData = $arr;

        $this->url = array_pop($arr);

        $this->parseControllerConfig();

        $this->createRout($this->url);
    }

    private function parseControllerConfig()
    {
        $parsConfig = file('/var/www/html/DataBaseConnector/Routing/ConfigControllers.yml', FILE_IGNORE_NEW_LINES);

        foreach ($parsConfig as $key => $value) {
            $parsElement = explode(': ', $value);
            $this->parsedConfig[] = $parsElement;
        }

        return $this;
    }

    private function createRout($url)
    {
        for ($k = 1; $k < count($this->parsedConfig); $k = $k + 5) {
            $matchingResult = preg_match($this->parsedConfig[$k][1], $url);
            if ($matchingResult == 1 && $this->parsedConfig[$k+1][1] == 'GET') {
                $controller = $this->parsedConfig[$k + 2][1] . '.php';
                var_dump($controller);
                $methodController = $this->parsedConfig[$k + 3][1];
                var_dump($methodController);
            }
        }

        return $this;
    }
}
