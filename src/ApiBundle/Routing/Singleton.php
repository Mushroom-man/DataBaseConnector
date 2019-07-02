<?php

namespace ApiBundle\Routing;


/**
 * Class Singleton
 * @package ApiBundle\Routing
 */
class Singleton
{
    use requestSingleton;

    /**
     * @var string
     */
    private $requestUri;

    /**
     * @var string
     */
    private $requestMethod;

    /**
     * @var array
     */
    private $postParams;

    public function setRequestParams()
    {
        $this->requestUri = $_SERVER['REQUEST_URI'];

        $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        if($_POST) {
            $this->postParams = $_POST;
        }
    }

    /**
     * @return string mixed
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }

    /**
     * @return string mixed
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @return array mixed
     */
    public function getPostParams()
    {
        return $this->postParams;
    }
}